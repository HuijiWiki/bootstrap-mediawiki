/**
 * Created by huiji001 on 2015/9/10.
 */
function copyWiki(){
    this.init();
}

copyWiki.prototype ={
    init: function(){
        this._addListener();
    },
    _addListener: function(){
        $('.wiki-copy a').on('click', $.proxy(this._getFollow, this));
        $('.wiki-copy').on('click','.copy', $.proxy(this._wikiSelect, this));
    },
    _getFollow: function(){
        var user_name = mw.config.get('wgUserName');
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
                            content = '<li><a>没有关注维基</a></li>';
                            $('.wiki-copy .dropdown-menu').empty().append(content);
                        }
                        $.each(res.result,
                            function (i, item) {
                                content += '<li><a class="copy" data-src="' + item.key + '">' + item.val + '</a></li>'
                            }
                        );
                        $('.wiki-copy .dropdown-menu').empty().append(content);
                    }
                }
            );
        }
    },
    _wikiSelect: function(e){
        e.stopPropagation();
        var ajaxurl = 'http://'+$(e.target).data('src')+'.huiji.wiki/api.php';
        $(e.target).append('<i class="fa fa-spinner fa-pulse"></i>');
        this.ajaxurl = ajaxurl;
        this._getToken();
    },
    _getToken: function(){
        $.ajax({
            url: this.ajaxurl,
            data: {
                action: 'query',
                meta: 'tokens',
                format: 'json',
                origin:'http://'+mw.config.get('wgHuijiPrefix')+'.huiji.wiki'
            },
            xhrFields: {
                withCredentials: true
            },
            dataType: 'json',
            type: 'POST',
            context: this,
            success: function( data ){
                var token = data.query.tokens.csrftoken;
                this._createPage(token);
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
                _this._importData(data);
            },
            error: function( xhr ) {
                alert( 'Error: Request failed.' );
            }
        });
    },
    _copyContent: function(){

    },
    _createPage: function(token){
        $.ajax({
            url:this.ajaxurl,
            data:{
                action: 'query',
                titles: mw.config.get('wgTitle'),
                
                format: 'json',
                
                origin:'http://'+mw.config.get('wgHuijiPrefix')+'.huiji.wiki'
            },
            xhrFields: {
                withCredentials: true
            },
            type: 'post',
            success: $.proxy(function(data){
                console.log(data.query.pages);
                for (var key in data.query.pages){
                    if (key < 0){
                        this._importWiki(token);
                    }else{
                        console.log(data);
                        var warn = '<div class="alert alert-danger copy-warn alert-dismissible fade in" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' +
                            '<h4>页面已存在!</h4>' +
                            '<p>点击覆盖，将覆盖您维基已有的本词条页面内容。否则请关闭</p>' +
                            '<p><button type="button" class="btn btn-danger" data-loading-text="搬运中" autocomplete="off">覆盖</button></p>' +
                            '</div>';
                        $('body').append(warn).on('click', '.copy-warn .btn', $.proxy(this._importWiki, this, token));
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
            url: this.ajaxurl+'?orgin = http://'+mw.config.get('wgHuijiPrefix')+'.huiji.wiki',
            data: {
                action: 'import',
                interwikisource: mw.config.get('wgHuijiPrefix'),
                interwikipage: mw.config.get('wgTitle'),
                token: token,
                format:'json',
                fullhistory: true,
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
                    alertime();
                    alertp.text('搬运成功');
                    this._addSource(token);
                }else if(error.code == 'cantimport'){
                    alertime();
                    alertp.text('您在目标维基没有管理员权限');
                }else if(error.code == 'unknown_interwikisource'){
                    alertime();
                    alertp.text('您的目标维基不能为当前维基');
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
            url: this.ajaxurl+'?orgin = http://'+mw.config.get('wgTitle')+'.huiji.wiki',
            data:{
                action: "edit",
                title: mw.config.get('wgTitle'),
                section: "new",
                summary: "出处",
                text: "来自于"+mw.config.get('wgHuijiPrefix')+".test.huiji.wiki/wiki/"+mw.config.get('wgTitle'),
                token: token
            },
            type: 'post',
            xhrFields: {
                withCredentials: true
            }
        });
    }
};

$(function(){
   return new copyWiki();
});