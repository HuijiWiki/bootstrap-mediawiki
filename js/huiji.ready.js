window.updateQueryStringParameter = function(url, key, value) {
    if (!url) url = window.location.href;
    var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
        hash;

    if (re.test(url)) {
        if (typeof value !== 'undefined' && value !== null)
            return url.replace(re, '$1' + key + "=" + value + '$2$3');
        else {
            hash = url.split('#');
            url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
            if (typeof hash[1] !== 'undefined' && hash[1] !== null) 
                url += '#' + hash[1];
            return url;
        }
    }
    else {
        if (typeof value !== 'undefined' && value !== null) {
            var separator = url.indexOf('?') !== -1 ? '&' : '?';
            hash = url.split('#');
            url = hash[0] + separator + key + '=' + value;
            if (typeof hash[1] !== 'undefined' && hash[1] !== null) 
                url += '#' + hash[1];
            return url;
        }
        else
            return url;
    }
}
window.imgLoadCall = function( that ){
    var src = that.data('video');
    var wid = that.width();
    var hig = that.height();
    var time = that.data('video-duration');
    var timespan = '';
    if (time!="00:00:00"){
        timespan = '<span class="video-time">'+time+'</span>';
    }
    if (that.data('video-from') == '163'){
        that.after('<iframe frameborder="no" border="0" marginwidth="0" marginheight="0" width=300 height=86 src="'+src+'"></iframe>');
        that.hide();
        return;
    }
    if(document.body.clientWidth>768) {
        if (wid >= 400 && hig >= 225) {
            that.after('<iframe src="' + src + '" width="' + wid + '" height="' + hig + '" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" allowTransparency="true" frameborder="0" type="application/x-shockwave-flash"></iframe>')
            that.hide();
        } else if(wid >=200&&wid<400) {
            that.parent('a').attr({href: '#', class: 'video-play-wrap'}).append(timespan+'<span class="video-circle glyphicon glyphicon-play-circle" style="top:' + (hig / 2 - 50) + 'px;left:' + (wid / 2 - 50) + 'px"></span>');
        }else{
            that.parent('a').attr({href: '#', class: 'video-play-wrap'}).append(timespan+'<span class="video-circle glyphicon glyphicon-play-circle" style="top:' + (hig / 2 - 25) + 'px;left:' + (wid / 2 - 25) + 'px; font-size:50px"></span>');
        }
    }else{
        if(wid>= 200) {
            that.parent('a').attr({href: '#', class: 'video-play-wrap'}).append(timespan+'<span class="video-circle glyphicon glyphicon-play-circle" style="top:' + (hig / 2 - 50) + 'px;left:' + (wid / 2 - 50) + 'px"></span>');
        }else{
            that.parent('a').attr({href: '#', class: 'video-play-wrap'}).append(timespan+'</span><span class="video-circle glyphicon glyphicon-play-circle" style="top:' + (hig / 2 - 25) + 'px;left:' + (wid / 2 - 25) + 'px; font-size:50px"></span>');
        }
    }
};
window.videoInitialize = function(){
    for(var i=0; i<$('.video-player-asyn').length; i++){
        var that = $('.video-player-asyn').get(i);
        if (that.complete || $(that).data('video-from') == '163' ){
            imgLoadCall($(that));
        }else{
            that.onload = function(e){
                imgLoadCall($(e.target));
            }
        }
    }
    $('.video-player-asyn').removeClass('video-player-asyn');

}
$(document).ready(function(){

    mw.notification.autoHideSeconds = 3;

    //table responsive
    $('#mw-content-text table.wikitable, .mw-datatable, .smwb-factbox, .smwtable, .mw-json, .property-page-results').each(function(){
       if (!$(this).parent('div.table-responsive').length){
           $(this).wrap('<div class="table-responsive"></div>');
       }
    });
    $('#preftoc').addClass('nav nav-tabs');
    ///* add missing icons caused by visual editor */
    //$('#ca-edit.collapsible > a:nth-child(1)').prepend('<i class="fa fa-file-code-o"></i> ');
    $('.sidebar-create .createboxInput').keyup(function(){
        if($(this).val().length>0) {
            $('.sidebar-create .btn-primary').removeClass('disabled');
        }else{
            $('.sidebar-create .btn-primary').addClass('disabled');
        }
    });
    $('#mw-input-preload').change(function(){
        if ($("#mw-input-preload option:selected").attr('prefix') != ''){
            $('.createbox input[name=prefix]').val($("#mw-input-preload option:selected").attr('prefix'));
        }
        $('.createbox').submit();
    });
    // $( "#commentForm" ).sisyphus( { locationBased: true, timeout: 10 } );
    // if (mw.cookie.get('Animation') == 'none'){
    //  $('#menu-toggle').css({
//            'animation':'none',
//            '-webkit-animation':'none',
//            '-moz-animation':'none',
//            '-o-animation':'none'
//        });
    // }
    var fromSource    = document.referrer;
    var navigatorInfo = navigator.userAgent;
    var userId    = mw.config.get("wgUserId");
    var userName  = mw.config.get("wgUserName");
    var wikiSite  = mw.config.get("wgHuijiPrefix");
    var siteName  = mw.config.get("wgSiteName");
    var titleName = mw.config.get("wgPageName");
    var articleId = mw.config.get("wgArticleId");
    var pageNamespace   = mw.config.get("wgNamespaceNumber");
    var url = 'http://huijidata.com:50007/insertViewRecord/';
//    insertRecordIntoDB(url,navigatorInfo,fromSource,userId,userName,wikiSite,siteName,titleName,articleId);
    insertIntoMongoDB('http://huijidata.com:8080/statisticQuery/webapi/view/insertOnePageViewRecord',navigatorInfo,fromSource,userId,userName,wikiSite,siteName,titleName,articleId,pageNamespace);

    $('#menu-toggle,.sidebar-toggle').click(function(e) {
        e.preventDefault();
        $('#wrapper').toggleClass("toggled").toggleClass('smtoggled');
        $('#menu-toggle').toggleClass('menu-active').toggleClass('smenu-active');
        if(window.innerWidth>=1366){
            $('#wrapper').removeClass('smtoggled');
            $('#menu-toggle').removeClass('smenu-active');
        }
        else if(window.innerWidth<=767&&window.innerWidth>=320){
            if($('#wrapper').hasClass('smtoggled')){
                $('body').append('<div class="phone-wrapper"></div>');
            }else{
                $('.phone-wrapper').remove();
            }
        }
        document.domain = mw.config.get('wgHuijiSuffix').substring(1);
        if($('#wrapper').hasClass('toggled')){
            localStorage.setItem('menu-toggle','toggled');
        }else{
            localStorage.setItem('menu-toggle','');
        }
        ga('send', 'event', 'menu-toggle', 'click', 'skin', 1);
    });

    $('body').on('touchstart','.phone-wrapper',function(){
        $('#menu-toggle').trigger('click');
    });
    $('.hub-list li').click(function(e){
        e.stopPropagation();
        $(this).addClass('active').siblings().removeClass('active');
        var toggle = $(this).data('toggle');
        $('.a').find('.'+toggle+'-link').addClass('active').siblings().removeClass('active');
    });
    $('.wiki-toggle').click(function(){
       $('#icon-section').toggleClass('xs-show');
    });
    $('.search-toggle').click(function(){
       $('#huiji-mobile-search-form').toggleClass('visible-xs-block');
    });
    $('.subnav #subnav-toggle').click(function(){
        var length;
        $('.subnav .nav .dropdown').toggle();
        length = $('.subnav .nav>.dropdown').length;
        if($('.subnav .nav').hasClass('phone-open')){
            $('.subnav .nav').removeClass('phone-open')
        }else{
            $('.subnav .nav').addClass('phone-open');
        }
        if(length<6){
            $('.subnav .nav .dropdown-menu').css('height','192px');
        }else {
            $('.subnav .nav .dropdown-menu').css('height', length * 32 + 'px');
        }
    });
    $('.nav .dropdown>a').click(function(){
        $(this).parent().addClass('phone-active').siblings().removeClass('phone-active');
        $('.nav .dropdown').find('.dropdown-menu').removeClass('phone-active');
        $(this).parent().find('.dropdown-menu').addClass('phone-active');
        $('.mw-echo-overlay').remove();
    });

    $('a[href^=#cite_note]').each(function(){
        var self = $(this);
            var options = {};
            var ref = $.escape(self.attr('href'));
            var innerHtml = $(ref+' .reference-text').html();
            options.content = innerHtml;
            options.placement = 'auto';
            options.html = true;
            options.trigger = 'focus';
            self.popover(options);
    });

    $('.navbar-toggle').on('click', function(){
        $('.navbar-collapse').toggleClass('xs-show');
    });

//    $('#wiki-outer-body').on('click','a[href^=#][role!=tab][role!=button]',function(e){
//        if ($('html').hasClass('ve-active')){
//            return;
//        }
//        var self = $(this);
//        e.preventDefault();
//        // Let popover.js handle cite note
//        if (self.attr('href').match(/^\#cite_note/g)){
//            if (document.activeElement != this){
//                self.focus();
//            }
//            return;
//        }
////        var target = self.attr('href').replace(/\./g, '\\.');
////        if (target != '#' && $( target ) != undefined){
////            $('html, body').animate({
////                scrollTop: $( target ).offset().top - ($(window).width()>=768?200:50)
////            }, 250);
////        }
//    });
    $('#wiki-outer-body').on('click','a[href^=#cite_note-]',function(e){
        e.preventDefault();
    });
    $( document ).on('change', '#subnav-select', function() {
        window.location = $(this).val();
    });


    // show the total number of active talks
    // var pagename = mw.config.get('wgTitle').replace(' ', '_');
    // var namespace = mw.config.get('wgCanonicalNamespace').replace(' ', '_');
    // if (namespace != ''){
    //     var talkpage = namespace+'_talk:'+pagename;
    // } else {
    //     var talkpage = 'Talk:'+pagename;
    // }
    // if (mw.config.get('wgIsArticle') && !sessionStorage['flowcache_'+talkpage]){
    //     $.get( "/api.php", {
    //             action:"flow",
    //             submodule:"view-topiclist",
    //             page:talkpage,
    //             format:"json"})
    //             .done(function(data){
    //                 sessionStorage.setItem('flowcache_'+talkpage, data);
    //                 renderFlowAbstract(data);

    //             });
    // } else if (mw.config.get('wgIsArticle') && sessionStorage['flowcache_'+talkpage]){
    //     renderFlowAbstract(sessionStorage.getItem('flowcache_'+talkpage));
    // }
    // *
    // * Render the flow abstract for article pages and display flow count bubble.
    // * @param data obj from api
    
    // function renderFlowAbstract(data){
    //     if (data.flow){
    //         var talkCount = data.flow["view-topiclist"].result.topiclist.roots.length;
    //         // if (talkCount > 0){
    //         //     $("#ca-talk a").append("<small><span class='help-block ca-help-text'>"+talkCount+"</span></small>");
    //         //     if (!mw.config.get('wgIsMainPage')){
    //         //         flowAdapter.init(data);
    //         //         var items = flowAdapter.convert(data);
    //         //         var html = flowAdapter.adapt(items, {postLimit:2, topicLimit:2});
    //         //         $('#mw-content-text').after(html);
    //         //     }
    //         // }
    //     }
    // }

    //alert-return
    var login ='';
    var pass = '';
        //login

    // add functions for sidebar buttons
    $('#ca-purge').click(function(event){
        event.preventDefault();
        ga('send', 'event', 'sidebar', 'click', 'purge', 1);
        window.location.assign(window.updateQueryStringParameter(location.href, 'action', 'purge'));
    });
    $('#ca-debug').click(function(event){
        event.preventDefault();
        ga('send', 'event', 'sidebar', 'click', 'debug', 1);
        window.location.assign(window.updateQueryStringParameter(location.href, 'debug', '1'));
    });



//    var ahover = false;      //是否在a标签上
//    var areaon = false;        //是否进入应该出现nav的这个区域并且不包括a标签
//    var clientY;
//    $('body').on("mouseover mouseout","a",function(event){
//        if(event.type == "mouseover"){
//            ahover = true;
//        }else if(event.type == "mouseout"){
//            ahover = false;
//        }
//        areaon = false;
//    });
//    $('body').on('mousemove',function(e){
//        clientY= e.clientY;
//        if($('body').scrollTop()>0) {
//            if (clientY >= 60 && clientY <= 110) {
//                if(!ahover) areaon = true;
//                setTimeout(function () {
//                    if (ahover||!areaon) return;
//                    if ($('#content-actions').hasClass('subnav-up')) {
//                        $('#content-actions').removeClass('subnav-up').addClass('subnav-down');
//                    }
//                }, 300)
//            } else if ((clientY > 110 && clientY < 120) || (clientY > 50 && clientY < 60)) {
//                areaon = false;
//                if ($('#content-actions').hasClass('subnav-down')) {
//                    $('#content-actions').addClass('subnav-up').removeClass('subnav-down');
//                }
//            }
//        }
//    });
    //user card
    function userCard(username,carduser){
        $.ajax({
            url:'/api.php',
            data:{
                action:'getuserfollowinfo',
                username:carduser,
                format: 'json'
            },
            type:'post',
            success:function(data){
                var res = data.getuserfollowinfo;
                var sex;
                if(!res.result) return;
                if(res.result.gender == "female"){
                    res.result.gender = "♀";
                    sex = "她";
                }else if(res.result.gender == "male"){
                    res.result.gender = "♂";
                    sex = "他";
                }else{
                    res.result.gender ='♂/♀';
                    sex = "Ta";
                }
                var ps = '';
                var com = '';
                var follow = '';
                var isfollow = '';
                if(res.result.minefollowerhim.length == 0){
                    ps = "暂无";
                }else{
                    $.each(res.result.minefollowerhim,function(i,item){
                        ps += "<a href='/wiki/User:"+item+"'>"+item+"</a><i>、</i>";
                    })
                }
                if(res.result.commonfollow.length == 0){
                    com = "暂无";
                }else{
                    $.each(res.result.commonfollow,function(i,item){
                        com += "<a href='/wiki/User:"+item+"'>"+item+"</a><i>、</i>";
                    });
                }
                if(res.result.is_follow=='Y'){
                    follow = '取关';
                    isfollow = 'unfollow';
                }else{
                    follow = '关注';
                }
                if (!res.result.status){
                    res.result.status = "";
                }
                var msg = "<div class='user-card-top'>"+res.result.url+"<div class='user-card-info'><span><a href='/wiki/User:"+res.result.username+"'>"+res.result.designation+"</a></span><span>"+res.result.gender+"</span>" +
                    "<span>"+res.result.level+"</span><p>"+mw.html.escape(res.result.status)+"</p></div></div><div class='user-card-mid'><div class='user-card-msg'><ul><li>关注：<span>"+res.result.usercounts+"</span></li>" +
                    "<li class='cut'>被关注：<span>"+res.result.usercounted+"</span></li><li>编辑：<span>"+res.result.editcount+"</span></li></ul><a class='user-card-follow "+isfollow+" user-user-follow' data-username = '"+res.result.username+"'><i class='fa fa-plus-square-o'></i>"+follow+"</a>" +
                    "<a href='/index.php?title=%E7%89%B9%E6%AE%8A:GiveGift&amp;user="+res.result.username+"'  class='user-card-gift' title='特殊:GiveGift'><i class='fa fa-gift'></i>礼物</a></div></div>" +
                    "<div class='user-card-bottom'><p class='follow-him'>我关注的人也关注了"+sex+"("+res.result.minefollowerhim.length+"):<span>"+ps+"</span></p><p class='common-follow'>共同关注("+res.result.commonfollow.length+"):<span>"+com+"</span></p></div>";
                $(".user-card").empty().append(msg);
                $('.follow-him i:last,.common-follow i:last').remove();
                if(username==null){
                    $('.user-card-bottom').remove();
                }else if(username == res.result.username){
                    $('.user-card-follow,.user-card-gift').hide();
                }
                getDirection();
                
            }
        });
    }
    //获取元素相对于屏幕的位置
    function getPos(ele){
        var position={x:null,y:null}
        var offsetParent=ele.offsetParent;
        while(offsetParent){
            position.x+=ele.offsetLeft;
            position.y+=ele.offsetTop;
            ele=ele.offsetParent;
            offsetParent=ele.offsetParent;
            //if(offsetParent==document.body)
            //return pos;
            //只有body没有offsetParent，body已经是顶级元素了
        }
        return position;
    }
    var enter = false;
    var exist = false;
    var own = false;
    var x, y,posX,posY,thisposX,thisposY;
    var card;
    $('#wiki-body a[href*="/wiki/%E7%94%A8%E6%88%B7:"] .headimg,#wiki-body a[href*="/wiki/User:"] .headimg, #wiki-body .mw-userlink').hover(function(e){
        if(document.body.clientWidth<=1024){
            e.preventDefault();
        }else {
            card = "<div class='user-card'><i class='fa fa-spinner fa-spin'></i></div>";
            x = 200 - (e.currentTarget.offsetWidth / 2);
            y = e.currentTarget.offsetHeight;
            posX = getPos(e.currentTarget).x;
            posY = getPos(e.currentTarget).y;
            var carduser;
            if ($(this).parents().hasClass('back-links')) {
                return;
            }
            if ($(this).hasClass('headimg')) {
                carduser = $(this).attr('data-name');
            } else {
                carduser = $(this).attr('title');
                if (carduser){
                   carduser = carduser.replace('用户:',''); 
                } else {
                    return;
                }
            }
            enter = true;
            if (thisposX == posX && thisposY == posY) {
                own = true;
            } else {
                own = false;
            }
            appendCard(carduser);
        }
    }, function() {
        enter = false;
        removeCard();
    });
    $('#home-feed-content').on('mouseenter mouseleave','.mw-user-link,.headimg,a[href~="/wiki/%E7%94%A8%E6%88%B7:"]:not(":has(img)"),a[href~="/wiki/User:"]:not(":has(img)")',function(e){
        if(e.type == "mouseenter"){
            if(document.body.clientWidth<=1024){
                e.preventDefault();
            }else {
                card = "<div class='user-card'><i class='fa fa-spinner fa-spin'></i></div>";
                x = 200 - (e.currentTarget.offsetWidth / 2);
                y = e.currentTarget.offsetHeight;
                posX = getPos(e.currentTarget).x;
                posY = getPos(e.currentTarget).y;
                var carduser;
                if ($(this).parents().hasClass('back-links')) {
                    return;
                }
                if ($(this).hasClass('headimg')) {
                    carduser = $(this).attr('data-name');
                } else {
                    carduser = $(this).text();
                }
                enter = true;
                if (thisposX == posX && thisposY == posY) {
                    own = true;
                } else {
                    own = false;
                }
                appendCard(carduser);
            }
        }else if(e.type == "mouseleave") {
            enter = false;
            removeCard();
        }
    });
    function appendCard(carduser){
        if((enter&&!exist)||(enter&&!own)){
            $('.user-card').remove();
            userCard(mw.config.get('wgUserName'), carduser);
            $("body").append(card);
            exist = true;
            hoverCard();
            thisposX = posX;
            thisposY = posY;
        }
    }
    function hoverCard(){
        $('.user-card').hover(function(){
            enter = true;
        },function(){
            enter = false;
            removeCard();
        });
    }
    function removeCard(){
        setTimeout(function(){
            if(!enter&&exist){
                $('.user-card').remove();
                exist=false;
            }
        },500)
    }
    function getDirection(){
        var height = $('.user-card').height();
        var scroll = $(document).scrollTop();
        if(posY-scroll<=420) {
            $('.user-card').css({
                "top": +(posY + 10) + "px",
                "left": +(posX - x) + "px"
            });
            setTimeout(function () {
                    $('.user-card').css({
                            "top": +(posY + y) + "px",
                            "opacity": "1"
                    });
            }, 600);
        }else{
            $('.user-card').css({
                "top": +(posY + y -height -10) + "px",
                "left": +(posX - x) + "px"
            });
            setTimeout(function () {
                $('.user-card').css({
                    "top": +(posY - height) + "px",
                    "opacity": "1"
                });
            }, 600);
        }
    }


    $('#wiki-body .body a[title="Special:UserLogin"]').click();
    if ($.fn.dropdown)
    	$('.dropdown-toggle').dropdown();
    // Hide Header on on scroll down
    var didScroll;
    var lastScrollTop = 0;
    var delta = 100;
    var navbarHeight = $('header').outerHeight();
    var ww = $(window).width();
    $(window).scroll(function(event){
        didScroll = true;
    });

    //enable tooltip and popover
    $('.tip').tooltip();
    $('[data-toggle="popover"]').popover();

    setInterval(function() {
        if (didScroll) {
            hasScrolled();
            didScroll = false;
        }
    }, 250);

    function hasScrolled() {
        var st = $(this).scrollTop();

        // Make sure they scroll more than delta
        if(Math.abs(lastScrollTop - st) <= delta)
            return;
        // If they scrolled down and are past the navbar, add class .nav-up.
        // This is necessary so you never see what is "behind" the navbar.
        if (st > lastScrollTop && st > navbarHeight){
            // Scroll Down
            if(!$('.subnav').hasClass('alwaysDown')){
                $('.subnav').removeClass('subnav-down').addClass('subnav-up');
            }
            if (ww < 768){
                $('.navbar').removeClass('nav-down').addClass('nav-up');
            }
            $('#sidebar-wrapper').removeClass('sidebar-wrapper-down').addClass('sidebar-wrapper-up');
        } else {
            // Scroll Up
            if(!$('.subnav').hasClass('alwaysDown')){
                $('.subnav').removeClass('subnav-down').addClass('subnav-up');
            }
            if(st + $(window).height() < $(document).height()) {
                $('.subnav').removeClass('subnav-up').addClass('subnav-down');
                if (ww < 768){
                    $('.navbar').removeClass('nav-up').addClass('nav-down');
                }
                $('#sidebar-wrapper').removeClass('sidebar-wrapper-up').addClass('sidebar-wrapper-down');
            }
        }
        if(st==0) {
            $('.subnav').removeClass('subnav-down subnav-up');
            if(ww<768) {
                $('.navbar').removeClass('nav-up nav-down');
            }
            $('#sidebar-wrapper').removeClass('sidebar-wrapper-down sidebar-wrapper-up');
        }
        lastScrollTop = st;
    }

    //show edit section
    $('h2, h3, h4, h5, h6').hover(function(){
        $(this).find('.mw-editsection').addClass('edit-active');
    }, function(){
        $(this).find('.mw-editsection').removeClass('edit-active');
    });

    $('[data-toggle="tooltip"]').tooltip();
    if ( $('.bdsharebuttonbox .icon-share-alt').length > 0 ){
        $.getScript( "//cdn.bootcss.com/clipboard.js/1.5.5/clipboard.min.js" )
          .done(function( script, textStatus ) {
            var clipboard = new Clipboard('.bdsharebuttonbox .icon-share-alt', {
                text: function(trigger) {
                    if (!$('.icon-share-alt').data('share-link')){
                        return document.title+" "+window._bd_share_config.common.bdUrl;}
                    return $('.icon-share-alt').data('share-link');
                }
            });
            clipboard.on('success', function(e) {
                mw.notification.notify('已复制链接到剪贴板，分享给小伙伴吧:)');
            });

            clipboard.on('error', function(e) {
                window.prompt("请按下 Ctrl+C 复制链接到剪贴板：", document.title+" "+window._bd_share_config.common.bdUrl);
            });
          })
          .fail(function( jqxhr, settings, exception ) {
            console.log('unable to download clipboard.min.js');
        });
    }

    //feed img show more
    $('#home-feed-content,#bodyContent').on('click','.show-btn',function(){
        $(this).parents('.user-home-item-img-wrap').addClass('show');
        $(this).hide();
        $(this).parents('.user-home-item-img-wrap').append('<span class="hide-btn">收起</span>')
    }).on('click','.hide-btn',function(){
        $(this).parents('.user-home-item-img-wrap').removeClass('show');
        $(this).siblings('.show-btn').show();
        $(this).remove();
    });

    //Initialize Video
    videoInitialize();

    //video play
    $('body').on('click','.video-player,.video-circle',function(e){
        e.preventDefault();
        e.stopPropagation();
        var src = $(this).parents('.video-play-wrap').find('.video-player').data('video');
        var title = $(this).parents('.video-play-wrap').find('.video-player').data('video-title');
        var from = $(this).parents('.video-play-wrap').find('.video-player').data('video-from');
        var link = $(this).parents('.video-play-wrap').find('.video-player').data('video-link');
        ga('send', 'event', 'video', 'play', from, 1);
        if(document.body.clientWidth>768) {
            $('body').append('<div class="video-wrapper"><span class="icon-close video-close"></span><h3>'+title+'</h4><span class="video-from">来自 '+from+'</span><iframe src="' + src + '" frameborder="0" allowfullscreen="true"></iframe></div>');
        }else{
            window.open(link);
        }
    }).on('click','.video-close',function(){
        $('.video-wrapper').remove();
    })

    var mousedown = false;
    var positionX,positionY;


    //move video
    $('body').on('mousedown','.video-wrapper',function(e){
        mousedown = true;
        positionX = e.clientX;
        positionY = e.clientY;
    }).on('mousemove','.video-wrapper',function(e){
        var x, y,nx,ny;
        if(mousedown === true){
            $(this).css('cursor','move');
            x = e.clientX-positionX;
            y = e.clientY-positionY;
            nx = parseInt($(this).css('left'));
            ny = parseInt($(this).css('top'));
            $(this).css({left:x+nx+'px',top:y+ny+'px'});
            positionX = e.clientX;
            positionY = e.clientY;
        }
    }).on('mouseup','.video-wrapper',function(){
        mousedown = false;
        $(this).css('cursor','default');
    });

//    aside topic
    if($('.toc-sidebar .toc-ul-wrap>ul').length>0) {
        var maxheight = $(window).height() - $('.toc-sidebar').offset().top + 'px';
        $('.toc-ul-wrap').css('height',maxheight);
        $('.toc-sidebar .toc-ul-wrap>ul').css('max-height', maxheight);
    }

    var file;
    var formData = new FormData();
    $('#caption-file').on('change',function(e){
         file = e.target.files[0];
    });
    $('.caption-submit').click(function(){
        var self = $(this);
        formData.append('action','srtsubmit');
        formData.append('id',$('#caption-id').val());
        formData.append('description',$('#caption-des').val());
        formData.append('file',file);
        formData.append('format','json');
        self.attr('disabled','');
        $.ajax({
            url:'/api.php',
            data: {
                action:'query',
                meta:'tokens',
                type:'csrf',
                format:'json'
            },
            success:function(data){
                var token = data.query.tokens.csrftoken;
                formData.append('token',token);
                $.ajax({
                    url: '/api.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function(data){
                        console.log(data);
                        self.removeAttr('disabled');
                        if($('#caption-id').val()==''){
                            mw.notification.notify("id不能为空");
                            return;
                        }else if(!file){
                            mw.notification.notify("文件不可为空");
                            return;
                        }
                        if(data.error){
                            mw.notification.notify("上传失败");
                            return;
                        }
                        if(data.srtsubmit.res.message == 'success'){
                            var src = data.srtsubmit.res.result;
                            window.location.href = src;
                        }
                        else{
                            mw.notification.notify(data.srtsubmit.res.message);
                        }
                    }
                })
            }
        })
    });
    $('.create-srt').click(function(e){
        e.preventDefault();
        $('#caption-wrap').addClass('wrap-show');
    });
    $('.trans-modal-close').click(function(e){
        $(this).parents('.trans-modal-wrap').removeClass('wrap-show');
    });
    $('#site-follower-count').click(function(e){
        mw.loader.using('oojs-ui', function(){
            // Example: Creating and opening a process dialog window. 

            // Subclass ProcessDialog.
            function ProcessDialog( config ) {
              ProcessDialog.super.call( this, config );
            }
            OO.inheritClass( ProcessDialog, OO.ui.ProcessDialog );

            // Specify a static title and actions.
            ProcessDialog.static.title = '关注本维基的用户';
            ProcessDialog.static.actions = [
              { action: 'more', label: '更多', flags: 'primary', modes:'hasmore' },
              { label: '关闭', flags: 'safe', modes:"nomore" },
              { label: '关闭', flags: 'safe', modes:"hasmore" },
            ];

            // Use the initialize() method to add content to the dialog's $body, 
            // to initialize widgets, and to set up event handlers. 
            ProcessDialog.prototype.initialize = function () {
              ProcessDialog.super.prototype.initialize.apply( this, arguments );

              this.content = new OO.ui.PanelLayout( { $: this.$, padded: true, expanded: false } );
              //this.content.$element.append( '<i class="fa fa-spinner fa-spin fa-5x"></i>' );
              this.$body.append( this.content.$element ); 
            };
            // Add a 'broken' function to getSetupProcess() for purposes of this example.
            ProcessDialog.prototype.getSetupProcess = function ( data ) {
              return ProcessDialog.super.prototype.getSetupProcess.call( this, data )
              .next( function () {
                  var dfd = $.Deferred();
                  var user = mw.config.get('wgUserName');
                  var site_name = mw.config.get('wgHuijiPrefix');
                  var dialog = this;
                  $.post(
                      mw.util.wikiScript(), {
                        action: 'ajax',
                        rs: 'wfUsersFollowingSiteResponse',
                        rsargs: [user, site_name]
                      },
                      function( data ) {
                        var res = jQuery.parseJSON(data);
                        if(res.success){
                          if(res.result.length==0){
                            var sitename = mw.config.get('wgSiteName');
                            dfd.rejected(new OO.ui.Error('暂时还没人关注'+sitename+' >-<'),  { recoverable: false } );
                          }else{
                            var msg = '<li class="row follower-popup"><span class="col-xs-8 col-md-8 col-sm-8">昵称</span><span class="hidden-xs col-md-2 col-sm-2">等级</span><span class="col-xs-4 col-md-2 col-sm-2">编辑</span></li>'
                            dialog.content.$element.append(msg);
                            if(res.result.length>4) {
                                var i;
                                for( i=0;i<4;i++ ){
                                    var msg = '<li class="row follower-popup"><span class="col-xs-8 col-md-8 col-sm-8 follower-info"><a href="' + res.result[i].userUrl + '" class="follower-headimg">' + res.result[i].url + '</a><a href="' + res.result[i].userUrl + '" class="follower-username">' + res.result[i].user + '</a></span><span class="follower-level hidden-xs col-md-2 col-sm-2">' + res.result[i].level + '</span><span class="follower-editnum col-xs-4 col-md-2 col-sm-2">' + res.result[i].count + '</span></li>';
                                    dialog.content.$element.append(msg);
                                }
                                dialog.actions.setMode('hasmore');
                                dfd.resolve();
                                //dialog.content.$element.append('<div class="follow-modal-more"><a href="/wiki/Special:EditRank">更多</a></div>');
                            }
                            else{
                                $.each(res.result,
                                    function (i, item) {
                                        var msg = '<li class="row follower-popup"><span class="col-xs-8 col-md-8 col-sm-8 follower-info"><a href="' + item.userUrl + '" class="follower-headimg">' + item.url + '</a><a href="' + item.userUrl + '" class="follower-username">' + item.user + '</a></span><span class="follower-level hidden-xs col-md-2 col-sm-2">' + item.level + '</span><span class="follower-editnum col-xs-4 col-md-2 col-sm-2">' + item.count + '</span></li>';
                                        dialog.content.$element.append(msg);
                                    }

                                );
                                dialog.actions.setMode('nomore');
                                dfd.resolve();
                            }
                        }
                      }else{
                        dfd.rejected(new OO.ui.Error('网络通讯错误'));
                        //oops
                      }
                    }
                  );
                  return dfd.promise();
              }, this );
            };
            // Use the getActionProcess() method to specify a process to handle the 
            // actions (for the 'save' action, in this example).
            ProcessDialog.prototype.getActionProcess = function ( action ) {
              var dialog = this;
              if ( action === 'more' ) {
                return new OO.ui.Process( function () {
                  dialog.close( { action: action } );
                  window.open( "/wiki/特殊:编辑排行");
                  // go to url
                } );
              } else {
                return new OO.ui.Process( function () {
                  dialog.close( { action: action } );
                  // go to url
                } );
              }
            // Fallback to parent handler.
              return ProcessDialog.super.prototype.getActionProcess.call( this, action );
            };

            // Get dialog height.
            ProcessDialog.prototype.getBodyHeight = function () {
              //return this.content.$element.outerHeight( true );
              return 300;
            };

            // Create and append the window manager.
            var windowManager = new OO.ui.WindowManager();
            $( 'body' ).append( windowManager.$element );

            // Create a new dialog window.
            var processDialog = new ProcessDialog({
              size: 'medium'
            });

            // Add windows to window manager using the addWindows() method.
            windowManager.addWindows( [ processDialog ] );

            // Open the window.
            windowManager.openWindow( processDialog );

        });
    });
    //add feedback button
    if (!window.is_mobile_device()){
       $.feedback({
            ajaxURL: '/api.php?action=feedback&format=json',
            html2canvasURL: 'http://cdn.bootcss.com/html2canvas/0.5.0-beta4/html2canvas.min.js',
            initButtonText:'反馈bug',
            tpl:{
                description:'<div id="feedback-welcome"><div class="feedback-logo">反馈bug</div><p>您的反馈是我们不断改进产品的推动力。</p><p>描述一下您遇到的问题:</p><textarea id="feedback-note-tmp"></textarea><p>下一步中我们将请您圈中页面的问题区域</p><button id="feedback-welcome-next" class="feedback-next-btn feedback-btn-gray">下一步</button><div id="feedback-welcome-error">请填写问题描述。</div><div class="feedback-wizard-close"></div></div>',
                highlighter: '<div id="feedback-highlighter"><div class="feedback-logo">反馈bug</div><p>点击并拖动鼠标，圈选出现问题的区域。您还可以拖动这个对话窗口。</p><button class="feedback-sethighlight feedback-active"><div class="ico"></div><span>添加高亮区域</span></button><label>使用此工具来注明bug区域</label><button class="feedback-setblackout"><div class="ico"></div><span>添加涂黑区域</span></button><label class="lower">使用此工具遮蔽您的隐私信息。</label><div class="feedback-buttons"><button id="feedback-highlighter-next" class="feedback-next-btn feedback-btn-gray">下一步</button><button id="feedback-highlighter-back" class="feedback-back-btn feedback-btn-gray">上一步</button></div><div class="feedback-wizard-close"></div></div>',
                overview: '<div id="feedback-overview"><div class="feedback-logo">反馈bug</div><div id="feedback-overview-description"><div id="feedback-overview-description-text"><h3>bug描述</h3><h3 class="feedback-additional">我们还将收集</h3><div id="feedback-additional-none"><span>无</span></div><div id="feedback-browser-info"><span>浏览器型号</span></div><div id="feedback-page-info"><span>Cookie</span></div><div id="feedback-page-structure"><span>页面结构</span></div></div></div><div id="feedback-overview-screenshot"><h3>截图</h3></div><div class="feedback-buttons"><button id="feedback-submit" class="feedback-submit-btn feedback-btn-blue">提交</button><button id="feedback-overview-back" class="feedback-back-btn feedback-btn-gray">上一步</button></div><div id="feedback-overview-error">请填写问题描述。</div><div class="feedback-wizard-close"></div></div>',
                submitSuccess: '<div id="feedback-submit-success"><div class="feedback-logo">反馈bug</div><p>感谢您提交的反馈。</p><p>很遗憾我们无法逐一回复，但我们将通过您的反馈来不断改善用户体验。</p><button class="feedback-close-btn feedback-btn-blue">好的</button><div class="feedback-wizard-close"></div></div>',
                submitError: '<div id="feedback-submit-error"><div class="feedback-logo">反馈bug</div><p>提交反馈时不幸出现了问题，请重试。</p><button class="feedback-close-btn feedback-btn-blue">好的</button><div class="feedback-wizard-close"></div></div>'
            }

        });
        window.setTimeout(function(){
            // convert text edit-section uf8
            $(".mw-editsection a").each(function(){
                $this = $(this);
                if (($this).html().indexOf('&') > -1)
                {        
                    $this.html($this.text());
                }
            });
        }, 2000);
    } else {
        // Collapse certain rows in mobile browser
        var collapseRow = {
            heading: '',
            init: function(options){
                this.heading = options.heading;
                var $headingElement = $('h2>span.mw-headline');
                for( var i in $headingElement ){
                    if ( $.inArray( $headingElement[i].textContent, this.heading )  >= 0  && $($headingElement[i])){
                        $wraper = $($headingElement[i]).parent();
                        $wraper.attr("id","collapseHead_"+i);
                        $wraper.attr("data-toggle","collapse");
                        // $wraper.attr("data-parent","#accordion");
                        $wraper.attr("href","#collapse_"+i);
                        $wraper.next().attr("id","collapse_"+i);
                        $wraper.next().attr( "class","collapse");
                        //console.log($headingElement[i]);
                        $( $headingElement[i] ).after("<span class=\"glyphicon glyphicon-triangle-bottom collapse_arrow\"></span>");
                        $('#collapse_'+i).on('show.bs.collapse', function () {
                            $(".collapse_arrow").attr( "class","glyphicon glyphicon-triangle-top collapse_arrow");
                            $wraper.removeClass('secondary');
                        });
                        $('#collapse_'+i).on('hide.bs.collapse', function () {
                            $(".collapse_arrow").attr( "class","glyphicon glyphicon-triangle-bottom collapse_arrow");
                            $wraper.addClass('secondary');
                        });
                    }
                }
                $(".collapse_arrow").css("padding","0 10px 0 10px");
                $(".collapse_arrow").css("font-size","15px");
                $(".collapse_arrow").css("vertical-align","middle");
            }
        }

        var option = {
            heading: ['引用与注释', '引用和注释', '引用', '注释', '出处', '来源', '参考资料', 'References'],
        }
        collapseRow.init(option);
       
    }
    //Make {{USERNAME}} work
    $('.insertusername').html(mw.config.get('wgUserName')||'朋友'); 
    if (mw.config.get('wgUserName') != null){
        var echoApi = new mw.echo.api.EchoApi();
        var res = echoApi.fetchNotifications( 'alert', 'local');
        var trash = [];
        res.done(function(data){
            for (var i = 0; i < data.list.length; i++){
                if (data.list[i].read == null){
                    if (data.list[i].category == 'system-gift-receive' ){
                        var url = data.list[i]['*'].links.primary.url;
                        var link = '<a href="'+url+'">'+ data.list[i]['*'].body +'</a>';
                        mw.notification.notify($(link), {
                            autoHide: false,
                            type: 'progress',
                            tag: "achievement",
                            title: '新成就'
                        });
                        trash.push(data.list[i].id);
                    } else if (data.list[i].category == 'advancement' ){
                        var url = data.list[i]['*'].links.primary.url;
                        var link = '<a href="'+url+'">'+ data.list[i]['*'].header +'</a>';
                         mw.notification.notify($(link), {
                            autoHide: false,
                            type: 'progress',
                            tag: "advancement",
                            title: '升级'
                        }); 
                        trash.push(data.list[i].id);                  
                    } else if (data.list[i].category == 'gift-receive' ){
                        var url = data.list[i]['*'].links.primary.url;
                        var link = '<a href="'+url+'">'+ data.list[i]['*'].header +'</a>';
                        mw.notification.notify($(link), {
                            autoHide: false,
                            type: 'progress',
                            tag: "gift",
                            title: '新礼物'
                        }); 
                        trash.push(data.list[i].id);                   
                    }
                }
            }
            // var echoApi = new mw.echo.api.EchoApi();
            echoApi.markItemsRead( trash, 'local', true);
                    
        });
    }
});


