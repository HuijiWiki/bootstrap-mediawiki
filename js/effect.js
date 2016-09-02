/**
 * Created by huiji001 on 2015/5/6.
 */

$(document).ready(function(){
    //new page
    var Detector = {

        canvas : !! window.CanvasRenderingContext2D,
        webgl : ( function () { try { return !! window.WebGLRenderingContext && !! document.createElement( 'canvas' ).getContext( 'experimental-webgl' ); } catch( e ) { return false; } } )(),
        workers : !! window.Worker,
        fileapi : window.File && window.FileReader && window.FileList && window.Blob,

        Showimg : function () {

            var domElement = document.createElement( 'div' );


            if ( ! this.webgl ) {

                domElement = window.WebGLRenderingContext ? [
                    '您的显卡不支持webgl'
                ].join( '\n' ) : [
                    '您的浏览器不支持webgl',
                    '请使用',
                    '<a href="http://www.google.com/chrome">Chrome 10</a>, ',
                    '<a href="http://www.mozilla.com/en-US/firefox/all-beta.html">Firefox 4</a> or',
                    '<a href="http://nightly.webkit.org/">Safari 6</a>'
                ].join( '\n' );

                $('.wrapper').prepend('<div class="home-bg"></div>');
                console.log(domElement);
            }



        }

    };

    if ( ! Detector.webgl ) {
        Detector.Showimg();
    }
    else if(window.innerWidth>=1200){
        $.getScript( "//cdn.bootcss.com/three.js/r55/three.min.js" )
          .done(function( script, textStatus ) {
            mw.loader.load('skins.bootstrapmediawiki.frontpage.cloud');
          })
          .fail(function( jqxhr, settings, exception ) {
            console.log('unable to download three.js');
        });
    }
    $('svg .day').tooltip({title:"tooltip - title", container:"body"});
    $('#user .nav-tab li').click(function(){
        $(this).addClass('active').siblings().removeClass('active');
        var index = $(this).index();
        $('.top-users').eq(index).removeClass('hide').siblings('.top-users').addClass('hide');
    });
    $('#site .nav-tab li').click(function(){
        $(this).addClass('active').siblings().removeClass('active');
        var index = $(this).index();
        $('.top-sites').eq(index).removeClass('hide').siblings('.top-sites').addClass('hide');
    });
    // $('#nav-rank-content .nav-rank li').click(function(){^M
    //     $(this).addClass('active').siblings().removeClass('active');^M
    //     var index = $(this).index();^M
    //     $('.top-users-total-rank').eq(index).removeClass('hide').siblings('.top-users-total-rank').addClass('hide');^M
    // });^M
    (function(){
        var config = {
            filter: jQuery('.user-home-feed.active').data('filter'),
            item_type: jQuery('.user-home-feed.active').data('item_type'),
            limit: jQuery('.user-home-feed.active').data('limit')
        }
        var username = mw.config.get('wgUserName');
        if (username == null){
            return false;
        }
        var filter = config.filter;
        var item_type = config.item_type;
        var limit = config.limit;
        var continuation = null;
        var showPlaceholder = function(){
            jQuery('.user-home-feed.active .user-home-feed-content').append('<p class="text-center placeholder"><i class="fa fa-spinner fa-2x fa-spin"></i></p>');
        };
        var removePlaceholder = function(){
            jQuery('.placeholder').remove();
        };
        var response;
        function more(){
            showPlaceholder();
            if(response) response.abort();
            response = jQuery.post(
                mw.util.wikiScript(), {
                    action: 'ajax',
                    rs: 'wfUserActivityResponse',
                    rsargs: [username, filter, item_type, limit, continuation]
                },
                function( data ) {
                    var res = jQuery.parseJSON(data);
                    if (res.success){
                        removePlaceholder();
                        jQuery('.user-home-feed.active .user-home-feed-content').append(res.output);
                        continuation = res.continuation;
                        if(res.end==true) {
                            $('.user-home-feed.active .user-activity-more').hide();
                            mw.notification.notify('没有更多了');
                        }
                        //user-home-item img show
                        $('.user-home-item-img-wrap').each(function(){
                            if($(this).find('a').length>4&&$(this).children('.show-btn').length==0){
                                $(this).append('<span class="show-btn">显示全部</span>')
                            }
                        });
                        videoInitialize();
                    }
                    response = null;
                }
            );
        };
        function refreshFeed( nowtime ){
            filter = $('.user-home-feed.active').data('filter');
            item_type = $('.user-home-feed.active').data('item_type');
            limit = $('.user-home-feed.active').data('limit');
            continuation = nowtime;
            $('.user-home-feed-content').empty();
            $('.user-activity-more').show();
            more();
        }
        more();
        $('.user-activity-more').on('click',more);
        $('[href="#following"], [href="#following_sites"]').on('shown.bs.tab',function(){
            refreshFeed( null );
        });
        $('#following').on('click','.info-user-list span ',function(){
            var follower = mw.config.get('wgUserName');
            var followee = $(this).siblings().find('a').text();
            var that = $(this);
            $.ajax({
                url:'/api.php',
                data:{
                    action:'getuserfollowrecommend',
                    follower:follower,
                    followee:followee,
                    format: 'json'
                },
                type:'post',
                success:function(data){
                    var res = data.getuserfollowrecommend;
                    if(res.result == null){
                        that.parents('.info-user-list').remove();
                        refreshFeed();
                    }else {
                        var parent = that.parents('.info-user-list ul');
                        var img = res.result.avatar;
                        var user = res.result.username;
                        var url = res.result.userurl;
                        var content;
                        content = '<li>' + img + '<div><b><a href="' + url + '">' + user + '</a></b><span>+关注</span></div></li>';
                        that.parents('.info-user-list li').remove();
                        parent.append(content);
                        var nowtime = Date.parse(new Date());
                        refreshFeed( nowtime );
                    }
                }
            })
            // $.post(
            //     mw.util.wikiScript(), {
            //         action: 'ajax',
            //         rs: 'wfUserFollowsRecommend',
            //         rsargs: [follower, followee]
            //     },
            //     function(data){
            //         console.log(data);
            //         var res = $.parseJSON(data);
            //         if(res.result == null){
            //             that.parents('.info-user-list').remove();
            //             refreshFeed();
            //         }else {
            //             var parent = that.parents('.info-user-list ul');
            //             var img = res.result.avatar;
            //             var user = res.result.username;
            //             var url = res.result.userurl;
            //             var content;
            //             content = '<li>' + img + '<div><b><a href="' + url + '">' + user + '</a></b><span>+关注</span></div></li>';
            //             that.parents('.info-user-list li').remove();
            //             parent.append(content);
            //             var nowtime = Date.parse(new Date());
            //             refreshFeed( nowtime );
            //         }
            //     }
            // )
        });
        $('#following_sites').on('click','.info-user-list span ',function(){
            var username = mw.config.get('wgUserName');
            var severname = $(this).siblings().find('a').data('src');
            var that = $(this);
            $.post(
                mw.util.wikiScript(), {
                    action: 'ajax',
                    rs: 'wfSiteFollowsRecommend',
                    rsargs: [username, severname]
                },
                function(data){
                    var res = $.parseJSON(data);
                    if(res.result == null){
                        that.parents('.info-user-list').remove();
                        refreshFeed( null );
                    }else {
                        var parent = that.parents('.info-user-list ul');
                        var img = res.result.s_avatar;
                        var user = res.result.s_name;
                        var url = res.result.s_url;
                        var content;
                        content = '<li>' + img + '<div><b><a href="' + url + '">' + user + '</a></b><span>+关注</span></div></li>';
                        that.parents('.info-user-list li').remove();
                        parent.append(content);
                        var nowtime = Date.parse(new Date());
                        refreshFeed( nowtime );
                    }
                }
            )
        })
    })();
    function getSite(){
        $.post(
            mw.util.wikiScript(), {
                action: 'ajax',
                rs: 'wfGetRecommendContent'
            },
            function(data){
                var res = $.parseJSON(data);
                var content='';
                console.log(data);
                $.each(res.result,function(i,item){
                    content += '<li><p class="source"><a class="entry-name" href="'+item.wikiurl+'">'+item.title+'</a>来自<a class="entry-source" href="'+item.siteurl+'">'+item.wikiname+
                        '</a></p><div class="recommend-info"><a href="'+item.wikiurl+'" class="image"><img alt="huiji-image" src="'+item.backgroungimg+'"></a><div class="entry-description">'+item.desc+'</div></div></li>';
                });
                $('.page-recommend').append(content);
            }
        );
    }
    function getBlog(){
        mw.loader.using('ext.blogPage.bloglist').done(function(){
            var option = {
                count: 7,
                mode: 'expanded',
                exchars: '300'
            }
            mw.bloglist(option, function($html){
                $('.blog-recommend').append($html);
            });
        });        
    }
    if($('#all').hasClass('active')){
        getSite();
    }
    if($('#blog').hasClass('active')){
        getBlog();
    }
    $('#all-tab').on('click',function(){
        $('.page-recommend').empty();
        getSite();
    });
    $('#blog-tab').on('click', function(){
        $('.blog-recommend').empty();
        getBlog();
    });
    //alert-return
    var login ='';
    var pass = '';
    function wiki_auth(login, pass, ref){
        var options = {
            tag: 'login',
            type: 'info'
        }
        $.ajax({
            url: '/api.php?action=query&meta=tokens&type=login&format=json',
            type: 'post',
            timeout: 10000,
            success: function(data){
                if(data.query){
                    //fix cookie issue for huijiwiki.com
                    if ('.'+document.domain != mw.config.get('wgHuijiSuffix')){
                        mw.cookie.set('_session', data.login.sessionid, {domain:'.'+document.domain});
                    }
                    var token = data.query.tokens.logintoken;
                    var params = 
                    {
                        logintoken: token,
                        username: login,
                        password: pass,
                        loginreturnurl: location.href
                    };
                    console.log(params);
                    $.ajax({
                        url: '/api.php?action=clientlogin&format=json&rememberMe=1',
                        data: params,
                        type: 'POST',
                        dataType: 'json',
                        timeout: 10000,
                        success: function (data) {
                            console.log(data);
                            $('#wpLoginAttempt,#frLoginAttempt').button('reset');
                            if(!data.error){
                                if(data.clientlogin.status == "PASS"){
                                    mw.notification.notify('登录成功', options);
                                    // document.location.reload();
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
                                    $('#wpLoginAttempt,#frLoginAttempt').button('reset');
                                    mw.notification.notify(data.clientlogin.message, options);
                                }
                            }else{
                                options = {
                                    tag: 'login',
                                    type: 'error'
                                }
                                $('#wpLoginAttempt,#frLoginAttempt').button('reset');
                                mw.notification.notify(data.error.info, options);
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

});
