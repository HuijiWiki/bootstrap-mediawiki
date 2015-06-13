$(function() {
    $('html').removeClass('client-nojs');
    //function menuToggle(){
    //    var menuToggle = $('#menu-toggle');
    //    if(menuToggle.css('right')=="20px") {
    //        if(!($('#wrapper').hasClass('toggled'))) {
    //            menuToggle.fadeOut(0).css('right', '-60px').fadeIn(1000);
    //        }
    //    }else{
    //        menuToggle.fadeOut(0).css('right', '20px').fadeIn(1000);
    //    }
    //}
    //menuToggle();
    //setInterval(function(){
    //    if(window.innerWidth<1366){
    //        $('#wrapper').removeClass('toggled');
    //        $('#menu-toggle').fadeOut(0).css('right', '-60px').fadeIn(1000);
    //    }
    //},300);
    $('#menu-toggle').click(function(e) {
        e.preventDefault();
        //var menuToggle = $('#menu-toggle');
        //if(menuToggle.css('right')=="20px") {
        //        menuToggle.fadeOut(0).css('right', '-60px').fadeIn(1000);
        //}else{
        //    menuToggle.fadeOut(0).css('right', '20px').fadeIn(1000);
        //}

        //if($('#wrapper').css('padding-left')=='0px'){
        //    $('#wrapper').css('padding-left','265px');
        //    $('#sidebar-wrapper').css('width','265px');
        //}
        //else{
        //    $('#wrapper').css('padding-left','0px');
        //    $('#sidebar-wrapper').css('width','0');
        //}

        $('#wrapper').toggleClass("toggled").toggleClass('smtoggled');
        $('#menu-toggle').toggleClass('menu-active').toggleClass('smenu-active');
        if(window.innerWidth>=1366){
            $('#wrapper').removeClass('smtoggled');
            $('#menu-toggle').removeClass('smenu-active');
        }
        if($('#wrapper').hasClass('toggled')){
            localStorage.setItem('menu-toggle','toggled')
        }else{
            localStorage.setItem('menu-toggle','')
        }
    });
    setInterval(function(){
        if(window.innerWidth>=1366){
            $('#wrapper').removeClass('smtoggled');
        }
    },500);
    $('#preftoc').addClass('nav nav-tabs');

    $('a[href^=#cite_note]').each(function(){
        var self = $(this);
        var options = {};
        var ref = self.attr('href');
        var innerHtml = $(ref+' .reference-text').html();
        options.content = innerHtml;
        options.placement = 'auto';
        options.html = true;
        options.trigger = 'focus';
        self.popover(options);
    });
//    不要限制TOC 因为正文或其他部分也有可能存在#链接。
    $('body').on('click','a[href^=#][role!=tab]:not(#menu-toggle)',function(e){
        e.preventDefault();
        var self = $(this);
//         Let popover.js handle cite note
        if (self.attr('href').match(/^\#cite_note/g)){
            return;
        }
        var target = self.attr('href').replace(/\./g, '\\.');
        if (target != '#' && $( target ) != undefined){
            $('html, body').animate({
                scrollTop: $( target ).offset().top - 200
            }, 250);
        }
    });

    $( document ).on('change', '#subnav-select', function() {
        window.location = $(this).val();
    });

    $('table.article-table')
        .each(function() {
            var $el = $(this);

            if( $el.closest('form').length == 0 ) {
                $el.addClass('table')
                     .addClass('table-striped')
                     .addClass('table-bordered');
            }//end if
        });

    $('pre:not([data-raw="true"])').addClass('prettyprint linenums');
    $('.jumbotron pre').removeClass('prettyprint linenums');

    $('.editButtons').addClass('well');
    $('input[type=submit],input[type=button],input[type=reset]').addClass('mw-ui-button');
    $('input[type=submit]').addClass(' mw-ui-progressive');


    // $('input[type=checkbox],input[type=radio]').each(function() {
    //  var $el = $(this);

    //  var id = $el.attr('id');
    //  $( 'label[for=' + id + ']' ).each(function() {
    //      var $label = $(this);
    //      if( $.trim( $label.text() ) != '' ) {
    //          $el.prependTo( $label );
    //      }//end if

    //      $label.wrap( '<div class="checkbox"/>' );
    //  });

    //  $el.closest('label').addClass($el.attr('type'));
    // });

    //Temperory fix
    $('.mw-ui-vform-field div').removeClass('mw-ui-checkbox');
    $('#wpRemember').css('margin-right','5px');

    $('.tip').tooltip();
    $('[data-toggle="popover"]').popover();

    if ( $('.toc-sidebar').length > 0 ) {
        if ( 0 === $('#toc').length ) {
            $('.toc-sidebar').remove();

        } else {
            $('.wiki-body-section').removeClass('col-md-12').addClass('col-md-10');
            $('.toc-sidebar').removeClass('hidden-md').addClass('col-md-2');
            // $('.toc-sidebar').append('<h3>摘要</h3>');
            $('#toc').each(function() {
                $(this).find('ul:first').appendTo( '.toc-sidebar' );
                $(this).remove();
            });
            $('nav.toc-sidebar > ul').append('<li><a href="#top">回到顶部</a></li>');
            $('nav.toc-sidebar > ul').addClass('hidden-sm hidden-xs hidden-print').attr('data-spy','affix');
            $('nav.toc-sidebar > ul').affix({
              offset: {
                top: 0,
                bottom: function () {
                  return (this.bottom = $('.bottom').outerHeight(true))
                }
              }
            })
            $('nav.toc-sidebar ul').addClass('nav nav-list');
            $('.toc-sidebar').attr('id', 'toc');
            $('body').scrollspy({target: '#toc', offset:230});
        }//end else
    } else {
        $('#toc').each(function() {
            var $toc = $(this);
            var $title = $toc.find('#toctitle');
            var $links = $title.siblings('ul');

            $('.page-header').prepend('<ul class="nav nav-pills pull-right"><li class="dropdown" id="page-contents"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list"></i> Contents <span class="caret"></span></a> <ul class="dropdown-menu"></ul></li></ul>');

            $('.page-header #page-contents').find('.dropdown-menu').html( $links.html() );
        });

        if( $('.page-header .nav').length === 0 ) {
            $('.page-header').prepend('<ul class="nav nav-pills pull-right"></li></ul>');
        }//end if

        var $header = $('.page-header');
        var $hero = $('.hero-unit');
        var $edit = $('.navbar .content-actions .edit');
        if( $edit.length > 0 ) {
            if( $hero.length ) {
                if( ! $hero.find('.nav-pills').length ) {
                    $hero.prepend('<ul class="nav nav-pills pull-right"></ul>');
                }//end if

                $edit.closest('li').clone().prependTo( $hero.find('.nav-pills') );
            } else {
                $edit.closest('li').clone().prependTo( $header.find('.nav-pills') );
            }//end else
        }//end if
    }//end if

    prettyPrint();

    $('#wiki-body .body a[title="Special:UserLogin"]').click();
    $('.dropdown-toggle').dropdown();
    // Hide Header on on scroll down
    var didScroll;
    var lastScrollTop = 0;
    var delta = 100;
    var navbarHeight = $('header').outerHeight();
    var ww = $(window).width();
    if (ww < 768){
        $('#pt-notifications').removeAttr('id');
        $('ul.navbar-right').removeClass('navbar-right');
    }
    $(window).scroll(function(event){
        didScroll = true;
    });

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

    var ahover = false;      //是否在a标签上
    var areaon = false;        //是否进入应该出现nav的这个区域并且不包括a标签
    var clientY;
    $('body').on("mouseover mouseout","a",function(event){
        if(event.type == "mouseover"){
            ahover = true;
        }else if(event.type == "mouseout"){
            ahover = false;
        }
        areaon = false;
    });
    $('body').on('mousemove',function(e){
        clientY= e.clientY;
        if($('body').scrollTop()>0) {
            if (clientY >= 60 && clientY <= 110) {
                if(!ahover) areaon = true;
                setTimeout(function () {
                    if (ahover||!areaon) return;
                    if ($('#content-actions').hasClass('subnav-up')) {
                        $('#content-actions').removeClass('subnav-up').addClass('subnav-down');
                    }
                }, 300)
            } else if ((clientY > 110 && clientY < 120) || (clientY > 50 && clientY < 60)) {
                areaon = false;
                if ($('#content-actions').hasClass('subnav-down')) {
                    $('#content-actions').addClass('subnav-up').removeClass('subnav-down');
                }
            }
        }
    });
    //parallax Jumbotron
    var jumboHeight = $('.parallax-jumbotron').outerHeight();
    if (jumboHeight > 0){
        $('#firstHeading > h1').hide();
        $('#firstHeading').css('border-bottom', 'none');
        var bg_image = $('.heading-hero-image a').attr('href');
        $('.parallax-bg').css('background', 'url(\"'+bg_image+'\") no-repeat center center');
    }
    function parallax(){
        var scrolled = $(window).scrollTop();
        $('.parallax-bg').css('height', (jumboHeight-scrolled+100) + 'px');
    }
    function is_touch_device() {
        return (('ontouchstart' in window)
            || (navigator.MaxTouchPoints > 0)
            || (navigator.msMaxTouchPoints > 0));
    }
    if (!is_touch_device()){
        $(window).scroll(function(e){
            parallax();
        });
    } else {
        $('.parallax-bg').css('position', 'absolute');
        $('.parallax-bg').css('height', '300px');
        $('.parallax-jumbotron').css('height', '300px');
    }

    //alert-return
    var alreturn = $('.alert-return');
    var alertp = $('.alert-return p');
    var login ='';
    var pass = '';
        //login
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
                                history.go(-1);
                            }else {
                                window.location.reload();
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
    function alertime(){
        alreturn.show();
        setTimeout(function(){
            alreturn.hide()
        },1000);
    }
    $('.login-in').click(function(){
       $(document).keyup(function(event){
           if(event.keyCode == 13){
               $('#wpLoginAttempt').trigger('click');
           }
       })
    });

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
    // bell animation
    if($('#pt-notifications span').text()!=0){
        $('#pt-notifications i').addClass('bell-animation');
        $('.mw-ui-quiet').click(function(){
            $('.badge').text('0');
            $('#pt-notifications i').removeClass('bell-animation');
        });
    }
    $('#ca-edit > a:nth-child(1)').prepend('<i class="fa fa-file-code-o"></i> ');

    //fix thumbinner
    $('.thumbinner').each(function(){
        $(this).width($(this).width()+6);
    });

    // show the total number of active talks
    var pagename = mw.config.get('wgTitle').replace(' ', '_');
    var namespace = mw.config.get('wgCanonicalNamespace').replace(' ', '_');
    if (namespace != ''){
        var talkpage = namespace+'_talk:'+pagename;
    } else {
        var talkpage = 'Talk:'+pagename;
    }
    $.get( "/api.php", {
        action:"flow",
        submodule:"view-topiclist",
        page:talkpage,
        vtlrender: "",
        format:"json"})
        .done(function(data){
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

        });

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
                if(res.result.gender == "female"){
                    res.result.gender = "♀";
                }else if(res.result.gender == "male"){
                    res.result.gender = "♂";
                }else{
                    res.result.gender ='♂/♀';
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
                        "<li class='cut'>被关注：<span>"+res.result.usercounted+"</span></li><li>编辑：<span>"+res.result.editcount+"</span></li></ul><button class='user-card-follow "+isfollow+" user-user-follow' data-username = '"+res.result.username+"'>"+follow+"</button><button class='user-card-gift'>礼物</button></div></div>" +
                        "<div class='user-card-bottom'><p class='follow-him'>共同关注了Ta("+res.result.minefollowerhim.length+"):<span>"+ps+"</span></p><p class='common-follow'>与Ta共同关注("+res.result.commonfollow.length+"):<span>"+com+"</span></p></div>";
                    $(".user-card").empty().append(msg);
                    $('.follow-him i:last,.common-follow i:last').remove();
                    if(username==null){
                        $('.user-card-bottom').remove();
                    }
                }
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
    var thisposX,thisposY;
    var own = false;
    $('#wiki-body a[href*="huiji.wiki/wiki/%E7%94%A8%E6%88%B7:"] .headimg,#wiki-body a[href*="huiji.wiki/wiki/%E7%94%A8%E6%88%B7:"]:not(":has(img)")').hover(function(e){
        var card = "<div class='user-card'><i class='fa fa-spinner fa-spin'></i></div>";
        var x= 200-(e.currentTarget.offsetWidth/2),y=e.currentTarget.offsetHeight;
        var posX = getPos(e.currentTarget).x, posY = getPos(e.currentTarget).y;
        var carduser;
//        $(this).tagName;
        if($(this).hasClass('headimg')){
            carduser = $(this).attr('data-name');
        }else{
            carduser = $(this).text();
        }
//        tag = $(this).get(0).tagName;
        enter = true;
        if(thisposX==posX&&thisposY==posY){
            own = true;
        }else{
            own = false;
        }
        appendCard(x,y,card,posX,posY);
        userCard(mw.config.get('wgUserName'),carduser);
    }, function() {
        enter = false;
        removeCard();
    });
    function appendCard(x,y,card,posX,posY){
        if((enter&&!exist)||(enter&&!own)){
            $('.user-card').remove();
            $("body").append(card);
            $('.user-card').css({
                "top":+(posY+10)+"px",
                "left":+(posX-x)+"px"
            });
            setTimeout(function(){
                $('.user-card').css({
                    "top":+(posY+y)+"px",
                    "opacity":"1"
                });
            },600);
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
});

