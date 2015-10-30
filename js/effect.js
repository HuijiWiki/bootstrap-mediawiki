/**
 * Created by huiji001 on 2015/5/6.
 */
$(document).ready(function(){
    //new page
    if(document.body.clientWidth>1024) {
        $('.wrapper').prepend('<div class="home-bg"></div>');
    }
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
        console.log(config.filter+'/'+config.item_type);
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
            console.log( username + filter + item_type + limit + continuation);
            jQuery.post(
                mw.util.wikiScript(), {
                    action: 'ajax',
                    rs: 'wfUserActivityResponse',
                    rsargs: [username, filter, item_type, limit, continuation]
                },
                function( data ) {
                    var res = jQuery.parseJSON(data);
                    if (res.success){
                        console.log(res.earlierThan);
                        removePlaceholder();
                        jQuery('.user-home-feed.active .user-home-feed-content').append(res.output);
                        continuation = res.continuation;
                        if(res.end==true) {
                            $('.user-home-feed.active .user-activity-more').hide();
                            alertime();
                            alertp.text('没有更多了');
                        }
                    }
                }
            );
        };
        function refreshFeed(){
            filter = $('.user-home-feed.active').data('filter');
            item_type = $('.user-home-feed.active').data('item_type');
            limit = $('.user-home-feed.active').data('limit');
            continuation = null;
            $('.user-home-feed-content').empty();
            $('.user-activity-more').show();
            more();
        }
        more();
        $('.user-activity-more').on('click',more);
        $('#home-feed-tabs a[data-toggle="tab"]').on('shown.bs.tab', refreshFeed);
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
                    console.log(data);
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
                        refreshFeed();
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
                    console.log(data);
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
                        refreshFeed();
                    }
                }
            )
        })
    })();

});
