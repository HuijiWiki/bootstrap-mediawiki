function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    }
    else {
        return uri + separator + key + "=" + value;
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
}
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
    $('#mw-content-text table').each(function(){
       if ($(this).width() > $('#mw-content-text').width() && !$(this).parent('div.table-responsive').length){
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
    var url = 'http://huijidata.com:50007/insertViewRecord/';
//    insertRecordIntoDB(url,navigatorInfo,fromSource,userId,userName,wikiSite,siteName,titleName,articleId);
    insertIntoMongoDB('http://huijidata.com:8080/statisticQuery/webapi/view/insertOnePageViewRecord',navigatorInfo,fromSource,userId,userName,wikiSite,siteName,titleName,articleId);

    $('#menu-toggle').click(function(e) {
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
       $('#searchformphone').toggleClass('visible-xs-block');
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
            $('.subnav .nav .dropdown-menu').css('max-height','192px');
        }else {
            $('.subnav .nav .dropdown-menu').css('max-height', length * 32 + 'px');
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

    $('#wiki-outer-body').on('click','a[href^=#][role!=tab][role!=button]',function(e){
        if ($('html').hasClass('ve-active')){
            return;
        }
        var self = $(this);
        e.preventDefault();
        // Let popover.js handle cite note
        if (self.attr('href').match(/^\#cite_note/g)){
            if (document.activeElement != this){
                self.focus();
            }
            return;
        }
        var target = self.attr('href').replace(/\./g, '\\.');
        if (target != '#' && $( target ) != undefined){
            $('html, body').animate({
                scrollTop: $( target ).offset().top - ($(window).width()>=768?200:50)
            }, 250);
        }
    });
    $( document ).on('change', '#subnav-select', function() {
        window.location = $(this).val();
    });


    // show the total number of active talks
    var pagename = mw.config.get('wgTitle').replace(' ', '_');
    var namespace = mw.config.get('wgCanonicalNamespace').replace(' ', '_');
    if (namespace != ''){
        var talkpage = namespace+'_talk:'+pagename;
    } else {
        var talkpage = 'Talk:'+pagename;
    }
    if (mw.config.get('wgIsArticle') && !sessionStorage['flowcache_'+talkpage]){
        $.get( "/api.php", {
                action:"flow",
                submodule:"view-topiclist",
                page:talkpage,
                format:"json"})
                .done(function(data){
                    sessionStorage.setItem('flowcache_'+talkpage, data);
                    renderFlowAbstract(data);

                });
    } else if (mw.config.get('wgIsArticle') && sessionStorage['flowcache_'+talkpage]){
        renderFlowAbstract(sessionStorage.getItem('flowcache_'+talkpage));
    }
    /**
    * Render the flow abstract for article pages and display flow count bubble.
    * @param data obj from api
    */
    function renderFlowAbstract(data){
        if (data.flow){
            var talkCount = data.flow["view-topiclist"].result.topiclist.roots.length;
            // if (talkCount > 0){
            //     $("#ca-talk a").append("<small><span class='help-block ca-help-text'>"+talkCount+"</span></small>");
            //     if (!mw.config.get('wgIsMainPage')){
            //         flowAdapter.init(data);
            //         var items = flowAdapter.convert(data);
            //         var html = flowAdapter.adapt(items, {postLimit:2, topicLimit:2});
            //         $('#mw-content-text').after(html);
            //     }
            // }
        }
    }

    // config for popup.
    mw.loader.using( [ 'ext.popups' ], function() { //wait for popups to be loaded

        // Time to wait in ms before showing a popup on hover. Default is 500.
        mw.popups.render.POPUP_DELAY = 500;

        // Time to wait in ms before closing a popup on de-hover. Default is 300.
        mw.popups.render.POPUP_CLOSE_DELAY = 300;

        // Time to wait in ms before starting the API queries on hover, must be <= POPUP_DELAY. Default is 50.
        // Don't change this unless you know what you're doing.
        mw.popups.render.API_DELAY = 50;

    });

    //alert-return
    var login ='';
    var pass = '';
        //login

    // add functions for sidebar buttons
    $('#ca-purge').click(function(event){
        event.preventDefault();
        ga('send', 'event', 'sidebar', 'click', 'purge', 1);
        window.location.assign(updateQueryStringParameter(location.href, 'action', 'purge'));
    });
    $('#ca-debug').click(function(event){
        event.preventDefault();
        ga('send', 'event', 'sidebar', 'click', 'debug', 1);
        window.location.assign(updateQueryStringParameter(location.href, 'debug', '1'));
    });

    function wiki_auth(login, pass, ref){
        var options = {
            tag: 'login',
            type: 'info'
        }
        $.ajax({
            url: '/api.php?action=login&lgname=' + login + '&lgpassword=' + pass + '&format=json',
            type: 'post',
            timeout: 10000,
            success: function(data){
                if(data.login.result == 'NeedToken'){
                    //fix cookie issue for huijiwiki.com
                    if ('.'+document.domain != mw.config.get('wgHuijiSuffix')){
                        mw.cookie.set('_session', data.login.sessionid, {domain:'.'+document.domain});
                    }
                    $.ajax({
                        url: '/api.php?action=login&lgname=' + login + '&lgpassword=' + pass +'&lgtoken=' + data.login.token + '&format=json',
                        type: 'post',
                        timeout: 10000,
                        success: function (data) {
                            $('#wpLoginAttempt,#frLoginAttempt').button('reset');
                            if(!data.error){
                                if(data.login.result == "Success"){
                                    mw.notification.notify('登录成功', options);
                                    //document.location.reload();
                                    if (mw.config.get('wgCanonicalSpecialPageName') === 'Userlogout'){
                                        location.href = updateQueryStringParameter($('#mw-returnto a').attr('href'), 'loggingIn', '1');
                                    }else {
                                        location.href = updateQueryStringParameter(location.href, 'loggingIn', '1');
                                    }
                                }else{
                                    options = {
                                        tag: 'login',
                                        type: 'error'
                                    }
                                    if(data.login.result=='NotExists'){
                                        mw.notification.notify('用户名不存在', options);
                                    }else if(data.login.result=='WrongPass') {
                                        mw.notification.notify('密码错误', options);
                                    }else if(data.login.result=='Throttled') {
                                        mw.notification.notify('由于您多次输入密码错误，请先休息一会儿。', options);
                                    }else if(data.login.result=='NoName') {
                                        mw.notification.notify('您必须键入用户名。', options);
                                    }else if(data.login.result=='Illegal') {
                                        mw.notification.notify('您的用户名中含有非法字符', options);
                                    }else if(data.login.result=='Blocked') {
                                        mw.notification.notify('您暂时被封禁了', options);
                                    }else if(data.login.result=='NeedToken') {
                                        mw.notification.notify('无法获取token', options);
                                    }else{
                                        mw.notification.notify('登录错误：'+ data.login.result, options);
                                    }
                                }
                            }else{
                                options = {
                                    tag: 'login',
                                    type: 'error'
                                }
                                mw.notification.notify('登录错误，请正确填写用户名。（'+ data.login.result+'）', options);
                            }
                        },
                        error:function(){
                            $('#wpLoginAttempt,#frLoginAttempt').button('reset');
                            mw.notification.notify('网络错误');
                        }
                    });
                }else{
                    options = {
                        tag: 'login',
                        type: 'error'
                    }
                    mw.notification.notify('登录错误：'+ data.error, options);
                }
                if(data.error){
                    options = {
                        tag: 'login',
                        type: 'error'
                    }
                    mw.notification.notify('登录错误：'+ data.error, options);
                }
            },
            error: function(){
                $('#wpLoginAttempt,#frLoginAttempt').button('reset');
                mw.notification.notify('网络错误');
            }
        });

    }
    $('body:not(.mw-special-Userlogin) #wpLoginAttempt').click(function(){

        $("#login-user-name").each(function(){
            login = $(this).val();
        });
        $("#login-user-password").each(function(){
           pass = $(this).val();
        });
        $(this).button('loading');
        wiki_auth(login,pass,'/');
    })
    $('.login-in').click(function(){
       $(document).keyup(function(event){
           if(event.keyCode == 13){
               $('#wpLoginAttempt').trigger('click');
           }
       })
    });
    $('#frLoginAttempt').click(function(){
        $("#fr-login-user-name").each(function(){
            login = $(this).val();
        });
        $("#fr-login-user-password").each(function(){
            pass = $(this).val();
        });
        $(this).button('loading');
        wiki_auth(login,pass,'/');
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
        $.post(
            mw.util.wikiScript(),{
                action:'ajax',
                rs:'wfUserFollowsInfoResponse',
                rsargs:[carduser]
            },
            function(data){
                var res = jQuery.parseJSON(data);
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
                if(res.success){
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
                    var msg = "<div class='user-card-top'>"+res.result.url+"<div class='user-card-info'><span><a href='/wiki/User:"+res.result.username+"'>"+res.result.username+"</a></span><span>"+res.result.gender+"</span>" +
                        "<span>"+res.result.level+"</span><p>"+mw.html.escape(res.result.status)+"</p></div></div><div class='user-card-mid'><div class='user-card-msg'><ul><li>关注：<span>"+res.result.usercounts+"</span></li>" +
                        "<li class='cut'>被关注：<span>"+res.result.usercounted+"</span></li><li>编辑：<span>"+res.result.editcount+"</span></li></ul><button class='user-card-follow "+isfollow+" user-user-follow' data-username = '"+res.result.username+"'>"+follow+"</button>" +
                        "<a href='/index.php?title=%E7%89%B9%E6%AE%8A:GiveGift&amp;user="+res.result.username+"'  class='user-card-gift' title='特殊:GiveGift'><i class='fa fa-gift'></i>礼物</a></div></div>" +
                        "<div class='user-card-bottom'><p class='follow-him'>我关注的人也关注了"+sex+"("+res.result.minefollowerhim.length+"):<span>"+ps+"</span></p><p class='common-follow'>共同关注("+res.result.commonfollow.length+"):<span>"+com+"</span></p></div>";
                    $(".user-card").empty().append(msg);
                    $('.follow-him i:last,.common-follow i:last').remove();
                    if(username==null){
                        $('.user-card-bottom').remove();
                    }else if(username == res.result.username){
                        $('.user-card-follow,.user-card-gift').hide();
                    }
                }
                getDirection();
            }
        )
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
    $('#wiki-body a[href*="/wiki/%E7%94%A8%E6%88%B7:"] .headimg,#wiki-body a[href*="/wiki/User:"] .headimg, #wiki-body .mw-userlink, #wiki-body .mw-userlink').hover(function(e){
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
                carduser = carduser.replace('用户:','');
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
    $('#home-feed-content').on('mouseenter mouseleave','.headimg,a[href~="/wiki/%E7%94%A8%E6%88%B7:"]:not(":has(img)"),a[href~="/wiki/User:"]:not(":has(img)")',function(e){
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
            userCard(mw.config.get('wgUserName'),carduser);
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

    $('body').on('click','.icon-weibo-share',function(){
        var redirect_url = mw.config.get('wgHuijiPrefix');
        mw.cookie.set( 'redirect_url', redirect_url );
        window.location.href='https://api.weibo.com/oauth2/authorize?client_id=2445834038&redirect_uri=http%3A%2F%2Fhuijiwiki.com%2Fwiki%2Fspecial%3Acallbackweibo&response_type=code';
    });

//    aside topic
    if($('.toc-sidebar .toc-ul-wrap>ul').length>0) {
        var maxheight = $(window).height() - $('.toc-sidebar').offset().top + 'px';
        $('.toc-ul-wrap').css('height',maxheight);
        $('.toc-sidebar .toc-ul-wrap>ul').css('max-height', maxheight);
    }

});
