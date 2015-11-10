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
    else if(window.innerWidth>1200){
        mw.loader.using('skins.three');
    }
    $('svg .day').tooltip({title:"tooltip - title", container:"body"});
    $('#user .nav-tab li').click(function(){
        $(this).addClass('active').siblings().removeClass('active');
        var index = $(this).index();
        $('.top-users').eq(index).removeClass('hide').siblings('.top-users').addClass('hide');
    });
    (function(){
        var config = {
            filter: jQuery('.user-home-feed.active').data('filter'),
            item_type: jQuery('.user-home-feed.active').data('item_type'),
            limit: jQuery('.user-home-feed.active').data('limit')
        }
        var username = mw.config.get('wgUserName');
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
        function more(){
            showPlaceholder();
            jQuery.post(
                mw.util.wikiScript(), {
                    action: 'ajax',
                    rs: 'wfUserActivityResponse',
                    rsargs: [username, filter, item_type, limit, continuation]
                },
                function( data ) {
                    var res = jQuery.parseJSON(data);
                    console.log(res);
                    if (res.success){
                        removePlaceholder();
                        jQuery('.user-home-feed.active .user-home-feed-content').append(res.output);
                        continuation = res.continuation;
                        if(res.end==true) {
                            $('.user-home-feed.active .user-activity-more').hide();
                        }
                    }
                }
            );
        };
        function refreshFeed( nowtime ){
            filter = $('.user-home-feed.active').data('filter');
            item_type = $('.user-home-feed.active').data('item_type');
            limit = $('.user-home-feed.active').data('limit');
            continuation = nowtime;
            console.log(continuation);
            $('.user-home-feed-content').empty();
            $('.user-activity-more').show();
            more();
        }
        more();
        $('.user-activity-more').on('click',more);
        $('#home-feed-tabs a[data-toggle="tab"]').on('shown.bs.tab',function(){
            refreshFeed( null );
        });
        $('#following').on('click','.info-user-list span ',function(){
            var follower = mw.config.get('wgUserName');
            var followee = $(this).siblings().find('a').text();
            var that = $(this);
            $.post(
                mw.util.wikiScript(), {
                    action: 'ajax',
                    rs: 'wfUserFollowsRecommend',
                    rsargs: [follower, followee]
                },
                function(data){
                    var res = $.parseJSON(data);
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
            )
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
                        refreshFeed();
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
                $.each(res.result,function(i,item){
                    content += '<li><p class="source"><a class="entry-name" href="'+item.wikiurl+'">'+item.title+'</a>来自<a class="entry-source" href="'+item.siteurl+'">'+item.wikiname+
                        '</a></p><div class="recommend-info"><a href="'+item.wikiurl+'" class="image"><img alt="Wit by Botanica.jpg" src="'+item.backgroungimg+'"></a><div class="entry-description">'+item.desc+'</div></div></li>';
                });
                $('.recommend').append(content);
            }
        );
    }
    if($('#all').hasClass('active')){
        getSite();
    }
    $('#all-tab').on('click',function(){
        $('.recommend').empty();
        getSite();
    })

});
