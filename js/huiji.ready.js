var alreturn = $('.alert-return');
var alertp = $('.alert-return p');
function alertime(){
    alreturn.show();
    setTimeout(function(){
        alreturn.hide()
    },1000);
}
$(document).ready(function(){
    
    $('#preftoc').addClass('nav nav-tabs');
    /* add missing icons caused by visual editor */
    $('#ca-edit.collapsible > a:nth-child(1)').prepend('<i class="fa fa-file-code-o"></i> ');
    // $( "#commentForm" ).sisyphus( { locationBased: true, timeout: 10 } ); 
	// if (mw.cookie.get('Animation') == 'none'){
	// 	$('#menu-toggle').css({
 //            'animation':'none',
 //            '-webkit-animation':'none',
 //            '-moz-animation':'none',
 //            '-o-animation':'none'
 //        });
	// }
	var fromSource    = document.referrer; 
	var navigatorInfo = navigator.userAgent.toLowerCase();
	var userId    = mw.config.get("wgUserId");
	var userName  = mw.config.get("wgUserName");
	var wikiSite  = mw.config.get("wgHuijiPrefix");
	var siteName  = mw.config.get("wgSiteName");
	var titleName = mw.config.get("wgPageName");
	var articleId = mw.config.get("wgArticleId");
	var url = 'http://test.huiji.wiki:50007/insertViewRecord/';
	insertRecordIntoDB(url,navigatorInfo,fromSource,userId,userName,wikiSite,siteName,titleName,articleId);

    $('#menu-toggle').click(function(e) {
        e.preventDefault();

        // if(mw.cookie.get('Animation') == 'none' || mw.cookie.get('Animation') == null) {
        //     mw.cookie.set('Animation', 'none');
        //     $('#menu-toggle').css({
        //         'animation': 'none',
        //         '-webkit-animation': 'none',
        //         '-moz-animation': 'none',
        //         '-o-animation': 'none'
        //     });
        // }
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
        document.domain = "huiji.wiki";
        if($('#wrapper').hasClass('toggled')){
            localStorage.setItem('menu-toggle','toggled');
        }else{
            localStorage.setItem('menu-toggle','');
        }
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
                    if (data.flow){
                        var talkCount = data.flow["view-topiclist"].result.topiclist.roots.length;
                        if (talkCount > 0){
                            $("#ca-talk a").append("<sup><span class='badge' style='display:inline'>"+talkCount+"</span></sup>");
                            if (!mw.config.get('wgIsMainPage')){
                                flowAdapter.init(data);
                                var items = flowAdapter.convert(data);
                                var html = flowAdapter.adapt(items, {postLimit:2, topicLimit:2});
                                $('#mw-content-text').after(html);
                            }
                        }               
                    }

                });        
    } else if (mw.config.get('wgIsArticle') && sessionStorage['flowcache_'+talkpage]){
        renderFlowAbstract(sessionStorage['flowcache_'+talkpage]);
    }
    /**
    * Render the flow abstract for article pages and display flow count bubble.
    * @param data obj from api
    */
    function renderFlowAbstract(data){
        if (data.flow){
            var talkCount = data.flow["view-topiclist"].result.topiclist.roots.length;
            if (talkCount > 0){
                $("#ca-talk a").append("<sup>&nbsp;<span class='badge'>"+talkCount+"</span></sup>");
                if (!mw.config.get('wgIsMainPage')){
                    flowAdapter.init(data);
                    var items = flowAdapter.convert(data);
                    var html = flowAdapter.adapt(items, {postLimit:2, topicLimit:2});
                    $('#mw-content-text').after(html);
                }
            }               
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

    // add functions for sidebar buttons
    $('#ca-purge').click(function(event){
        event.preventDefault();
        window.location.assign(updateQueryStringParameter(location.href, 'action', 'purge'));
    });
    $('#ca-debug').click(function(event){
        event.preventDefault();
        window.location.assign(updateQueryStringParameter(location.href, 'debug', '1'));
	
    });

    function wiki_auth(login, pass, ref){
        $.post('/api.php?action=login&lgname=' + login + '&lgpassword=' + pass + '&format=json',function(data){
            if(data.login.result == 'NeedToken'){
                $.post('/api.php?action=login&lgname=' + login + '&lgpassword=' + pass +'&lgtoken=' + data.login.token + '&format=json',function(data){
                   if(!data.error){
                        if(data.login.result == "Success"){
                            alertime();
                            alertp.text('登录成功');
                            //document.location.reload();
                            if (mw.config.get('wgCanonicalSpecialPageName') === 'Userlogout'){
                                location.href = updateQueryStringParameter($('#mw-returnto a').attr('href'), 'loggingIn', '1');
                            }else {                                
                                location.href = updateQueryStringParameter(location.href, 'loggingIn', '1');
                            }
                        }else{
                            alertime();
                            if(data.login.result=='NotExists'){
                                alertp.text('用户名不存在');
                            }else if(data.login.result=='EmptyPass'){
                                alertp.text('请输入密码');
                            }else if(data.login.result=='WrongPass') {
                                alertp.text('密码错误');
                            }else if(data.login.result=='Throttled') {
                                alertp.text('由于您多次输入密码错误，请先休息一会儿。');
                            }else if(data.login.result=='NoName') {
                                alertp.text('您必须键入用户名。');
                            }else if(data.login.result=='Illegal') {
                                alertp.text('您的用户名中含有非法字符');
                            }else if(data.login.result=='Blocked') {
                                alertp.text('您暂时被封禁了');
                            }else{
                                alertp.text('Result:' + data.login.result);
                            }
                        }
                   }else{
                       alertime();
                       alertp.text('Error:' + data.error);
                   }
                });
            }else{
                alertime();
                alertp.text('请输入用户名');
            }
            if(data.error){
                alertime();
                alertp.text('Error:' + data.error);
            }
        });
    }
    $('#wpLoginAttempt').click(function(){
        $("#login-user-name").each(function(){
            login = $(this).val();
        });
        $("#login-user-password").each(function(){
           pass = $(this).val();
        });
        wiki_auth(login,pass,'/');
    })
    $('.login-in').click(function(){
       $(document).keyup(function(event){
           if(event.keyCode == 13){
               $('#wpLoginAttempt').trigger('click');
           }
       })
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
                        "<span>"+res.result.level+"</span><p>"+res.result.status+"</p></div></div><div class='user-card-mid'><div class='user-card-msg'><ul><li>关注：<span>"+res.result.usercounts+"</span></li>" +
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
    $('#wiki-body a[href*="huiji.wiki/wiki/%E7%94%A8%E6%88%B7:"] .headimg, #wiki-body a[href*="huiji.wiki/wiki/User:"] .headimg, #wiki-body a[href*="huiji.wiki/wiki/%E7%94%A8%E6%88%B7:"]:not(":has(img)"), #wiki-body a[href*="huiji.wiki/wiki/User:"]:not(":has(img)")').hover(function(e){
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
    }, function() {
        enter = false;
        removeCard();
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

});
