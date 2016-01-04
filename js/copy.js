/**
 * Created by huiji001 on 2015/9/10.
 */
function copyWiki(){
    this.init();
}

function getFormattedDate() {
    var date = new Date();
    var str = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + " " +  date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
    return str;
}

copyWiki.prototype ={
    init: function(){
        this._addListener();
        this._addModal();
    },
    _addListener: function(){
        $('#ca-fork a').on('click', $.proxy(this._getFollow, this));
        $('body').on('click','.copy-modal .copy', $.proxy(this._wikiSelect, this));
    },
    _getFollow: function(){
        var user_name = mw.config.get('wgUserName');
        $('.copy-modal').modal();
        if(user_name == null){
            e.preventDefault();
            $('.wiki-copy .dropdown-menu').empty().append('请登录');
            $('.user-login').modal();
        }else {
            $.post(
                mw.util.wikiScript(), {
                    action: 'ajax',
                    rs: 'wfUserSiteFollowsDetailsResponse',
                    rsargs: [user_name, user_name]
                },
                function (data) {
                    var res = $.parseJSON(data);
                    var content = '';
                    if (res.success == true) {
                        if (res.result.length == 0) {
                            content = '<a>您要把这个页面搬运到哪里呢？不妨先关注那个维基吧</a>';
                            $('.copy-modal .modal-body').empty().append(content);
                        }
                        $.each(res.result,
                            function (i, item) {
                                content += '<p><a class="copy" data-src="' + item.key + '">' + item.val + '</a></p>'
                            }
                        );
                        $('.copy-modal .modal-body').empty().append(content);
                    }
                }
            );
        }
    },
    _addModal: function(){
        var modal = '<div class="modal fade copy-modal in" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">'+
            '<div class="modal-dialog modal-sm">'+
            '<div class="modal-content">'+
                '<div class="modal-header">'+
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                    '<h4 class="modal-title" id="mySmallModalLabel">把这个页面搬运到哪？</h4>'+
                    '<p>根据页面历史长短和嵌套的模板数量，搬运会需要相应的等候时间。</p>'+
                '</div>'+
                '<div class="modal-body">'+
                '<i class="fa fa-spinner fa-pulse"></i>'+
                '</div>'+
            '</div><!-- /.modal-content -->'+
        '</div><!-- /.modal-dialog -->'+
        '</div>';
        $("body").append(modal);
    },
    _wikiSelect: function(e){
        e.stopPropagation();
        var targetPrefix = $(e.target).data('src');
        var ajaxurl = 'http://'+$(e.target).data('src')+mw.config.get('wgHuijiSuffix')+'/api.php';
        var redirectUrl = 'http://'+$(e.target).data('src')+mw.config.get('wgHuijiSuffix')+'/wiki/'+mw.config.get('wgPageName');
        $(e.target).append('<i class="fa fa-spinner fa-pulse"></i>');
        this.targetPrefix = targetPrefix;
        this.ajaxurl = ajaxurl;
        this.redirectUrl = redirectUrl;
        this._getToken();
    },
    _getToken: function(){
        $.ajax({
            url: this.ajaxurl,
            data: {
                action: 'query',
                meta: 'tokens',
                format: 'json',
                origin:'http://'+mw.config.get('wgHuijiPrefix')+mw.config.get('wgHuijiSuffix')
            },
            xhrFields: {
                withCredentials: true
            },
            dataType: 'json',
            type: 'POST',
            context: this,
            success: function( data ){
                var token = data.query.tokens.csrftoken;
                this._queryPage(token);
            },
            error: function( error ){
                console.log( error );
            }
        })
    },
    _queryData: function(){
        var _this = copyWiki.prototype;
        console.log(_this);
//        $.ajax({
//            url:'api.php?action=query&titles=API&export&exportnowrap',
//            title: mw.config.get( "wgPageName" )
//        })
        $.ajax({
            url: mw.util.wikiScript( 'api' ),
            data: {
                format: 'xml',
                action: 'query',
                titles: '首页',
                prop: 'revisions',
                rvprop: 'content'
            },
            type: 'POST',
            success: function( data ) {
                console.log(data);
                this._importData(data);
            },
            error: function( xhr ) {
                alert( 'Error: Request failed.' );
            }
        });
    },
    _copyContent: function(){

    },
    _queryPage: function(token){
        $.ajax({
            url:this.ajaxurl,
            data:{
                action: 'query',
                titles: mw.config.get('wgPageName'),
                format: 'json',
                origin:'http://'+mw.config.get('wgHuijiPrefix')+mw.config.get('wgHuijiSuffix')
            },
            xhrFields: {
                withCredentials: true
            },
            type: 'post',
            success: $.proxy(function(data){


                for (var key in data.query.pages){
                    if (key < 0){
                        this._importWiki(token);
                    }else{

                        var warn = '<div class="alert alert-danger copy-warn alert-dismissible fade in" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' +
                            '<h4>页面已存在!</h4>' +
                            '<p>点击覆盖，将覆盖您维基已有的同名页面内容。否则请关闭。</p>' +
                            '<p><button type="button" class="btn btn-danger" data-loading-text="搬运中" autocomplete="off">覆盖</button></p>' +
                            '</div>';
                        $('body').append(warn).on('click', '.copy-warn .btn', $.proxy(this._importWiki, this, token));
                        $('body').append(warn).on('click', '.copy-warn .close',function(){
                            $('.copy-modal').modal('hide');
                            $('.copy-warn').remove();
                        });

                    }
                }
            },this),
            error: function(error){
                console.log(error)
            }
        })
    },
    _importWiki: function(token){
        var $btn = $('.copy-warn .btn').button('loading');
        $.ajax({
            url: this.ajaxurl+'?origin=http://'+mw.config.get('wgHuijiPrefix')+mw.config.get('wgHuijiSuffix'),
            data: {
                action: 'import',
                interwikisource: mw.config.get('wgHuijiPrefix'),
                interwikipage: mw.config.get('wgPageName'),
                token: token,
                format:'json',
                // fullhistory: true,
                templates: true,
                createonly: true
            },
            type: 'post',
            xhrFields: {
                withCredentials: true
            },
            success: $.proxy(function(data){

                var result = data.import||[];
                var error = data.error||'';
                $('.copy .fa-spinner').remove();
                if(result.length>0) {
                    $btn.button('reset');
                    $('.copy-warn .close').trigger('click');
                    var options = {
                        tag: 'import',
                        type: 'info'
                    }
                    $('.copy-modal').modal('hide');
                    this._addSource(token);
                    jQuery.post(
                        mw.util.wikiScript(), {
                        action: 'ajax',
                        rs: 'wfAddForkCount',
                        rsargs: [mw.config.get('wgArticleId'), mw.config.get('wgHuijiPrefix')],
                    },function(){
                        mw.notification.notify('搬运成功', options);
                    });
                }else if(error.code == 'cantimport'){
                    var options = {
                        tag: 'import',
                        type: 'error'
                    }
                    mw.notification.notify('您在目标维基没有管理员权限', options);
                }else if(error.code == 'unknown_interwikisource'){
                    var options = {
                        tag: 'import',
                        type: 'error'
                    }
                    mw.notification.notify('您选择的维基不能为当前维基', options);
                }else{
                    console.log(data);
                }
            },this),
            error: function( error ){
                console.log( error );
            }
        });

    },
    _addSource: function(token){
        $.ajax({
            url: this.ajaxurl+'?origin=http://'+mw.config.get('wgHuijiPrefix')+mw.config.get('wgHuijiSuffix'),
            data:{
                action: "edit",
                title: mw.config.get('wgPageName'),
                summary: "注明出处",
                format:"json",
                appendtext: "<noinclude>{{raw:templatemanager:ForkCredit|time="+getFormattedDate()+"|source_page=[["+mw.config.get('wgHuijiPrefix')+":"+mw.config.get('wgPageName')+"]]"+"|carrier=[[User:"+mw.config.get('wgUserName')+"]]}}</noinclude>",
                token: token
            },
            type: 'post',
            xhrFields: {
                withCredentials: true
            },
            success: $.proxy(function(data){
                var that = this;
                jQuery.post(
                    mw.util.wikiScript(), {
                    action: 'ajax',
                    rs: 'wfAddForkInfo',
                    rsargs: [mw.config.get('wgArticleId'), wgNamespaceNumber, mw.config.get('wgPageName'), mw.config.get('wgHuijiPrefix'), this.targetPrefix]
                },function(){
                    window.location = that.redirectUrl;
                });
            },this)

        });
        
    }

};
$(function() {
    if(wgNamespaceNumber==10||wgNamespaceNumber==828) {
        jQuery.post(
            mw.util.wikiScript(), {
                action: 'ajax',
                rs: 'wfGetForkInfoByPageId',
                rsargs: [mw.config.get('wgArticleId'), mw.config.get('wgHuijiPrefix')]
            },
            function (data) {
                if (data != '[]') {
                    var res = JSON.parse(data);
                    var content = '<span>&nbsp;&nbsp;&nbsp;搬运自<a href="http://' + res.fork_from + '.huiji.wiki">' + res.fork_sitename + '</span>';
                    $('#contentSub small').append(content);
                }
            }
        );
        jQuery.post(
            mw.util.wikiScript(), {
            action: 'ajax',
            rs: 'wfGetForkCountByPageId',
            rsargs: [mw.config.get('wgArticleId')]
        },function(data){
            if (data != '[]') {
                var res = JSON.parse(data);
                var content = '<span>&nbsp;&nbsp;&nbsp;已被搬运' + res + '次</span>';
                $('#contentSub small').append(content);
            }
            window.location = that.redirectUrl;
        });
    }
    
    return new copyWiki();
});