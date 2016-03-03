function checkName(title,url,token,video_from, video_id, video_player_url, video_tags, video_duration, n){
    $.post('/api.php',{
        action:'upload',
        filename: title+n+'.video',
        url: url,
        token: token,
        format: 'json'
    },function(data){
        if(data.upload) {
            if (data.upload.warnings) {
                if(data.upload.warnings.exists) {
                    if (n == '')
                        n = 0;
                    n++;
                    checkName(title, url, token, video_from, video_id, video_player_url, video_tags, video_duration, n);
                }else{
                    $.post('/api.php',{
                        action:'upload',
                        filename: title+n+'.video',
                        url: url,
                        token: token,
                        ignorewarnings: true,
                        format: 'json'
                    },function(data){
                        var filename = data.upload.filename;
                        $.post(
                            mw.util.wikiScript(), {
                                action: 'ajax',
                                rs: 'wfinsertVideoInfo',
                                rsargs: [video_from, filename, video_id, title + n, video_player_url, video_tags, video_duration]
                            },
                            function (data) {
                                var res = jQuery.parseJSON(data);
                                if (res.success) {
                                    empty();
                                    if ($('#wpTextbox1').val()) {
                                        var caret = window.caret || 0;
                                        var content = $('#wpTextbox1').val().substring(0, caret) + "[[File:" + filename + "]]" + $('#wpTextbox1').val().substring(caret);
                                        $('#wpTextbox1').val(content);
                                        $('.video-upload-modal').modal('hide');
                                        mw.notification.notify('上传成功');
                                    } else {
                                        mw.notification.notify('上传成功');
                                    }
                                } else {
                                    mw.notification.notify('上传失败');
                                }
                                $('.video-upload-modal-btn').removeAttr("disabled");
                            }
                        );
                    });
                }
            }
            else if (data.upload.result == 'Success') {
                /**
                 * api.php  upload file bigThumbnail as image
                 * named as video_title
                 */
                var filename = data.upload.filename;
                 $.post(
                        mw.util.wikiScript(), {
                            action: 'ajax',
                            rs: 'wfinsertVideoInfo',
                            rsargs: [video_from, filename, video_id, title + n, video_player_url, video_tags, video_duration]
                        },
                        function (data) {
                            var res = jQuery.parseJSON(data);
                            if (res.success) {
                                if ($('#wpTextbox1').val()) {
                                    var caret = window.caret || 0;
                                    var content = $('#wpTextbox1').val().substring(0, caret) + "[[File:" + filename + "]]" + $('#wpTextbox1').val().substring(caret);
                                    $('#wpTextbox1').val(content);
                                    $('.video-upload-modal').modal('hide');
                                    mw.notification.notify('上传成功');
                                } else {
                                    mw.notification.notify('上传成功');
                                }
                                empty();
                            } else {
                                mw.notification.notify('上传失败');
                            }
                            $('.video-upload-modal-btn').removeAttr("disabled");
                        }
                 );
            }
        }else if(data.error){
            mw.notification.notify(data.error.code);
            $('.video-upload-modal-btn').removeAttr("disabled");
        }
    });
}
function empty(){
    $('#video-upload-modal-url').val('');
    $('#video-upload-modal-name').val('');
}
$(function(){
    $('#video-upload-modal-url').keyup(function() {
        if($(this).val() != '') {
           $('.video-upload-modal-btn').removeAttr("disabled");
        }
    });
    $('body').on('click','.video-upload-modal-btn',function(){
//        $('#wpTextbox1').focus();
        var url = $('#video-upload-modal-url').val();
        if ($('#uploadvideos').val() == ''){
            mw.notification.notify('请输入视频URL');
            return;
        }
        //check url & get video_id
        var regex = /\.(\w+)\.com/;
        var match = url.match(regex);
        var video_id,video_from,video_title;
        $(this).attr('disabled','');
        if(!match){
            mw.notification.notify('暂不支持该视频URL');
            return;
        }
        var video_from, video_id;
        var video_orig_title;
        var video_full_name;
        var video_name;
        var video_player_url;
        var video_tags;
        var video_thum;
        var video_duration;
        var token;
        switch(match[1]){
            case 'youku':
                var regex2 = /id_([\w]+?)(?:==|\.html)/;
                var id = url.match(regex2);
                if (id != null && id[1] != null){
                    video_id = id[1];
                    video_from = 'youku';
                }else{
                    mw.notification.notify('无法找到优酷视频id');
                    return;
                }
                //get video info from youkuapi
                $.get("https://openapi.youku.com/v2/videos/show.json",{
                    'client_id':'adc1f452c0653f53',
                    'video_id':video_id
                },function(data){
                    video_orig_title = data.title;
                    video_full_name;
                    video_name = $('#video-upload-modal-name').val();
                    if (video_name == ''){
                        video_name = video_orig_title;
                    }
                    if (video_name.indexOf('.video') < 0){
                        video_full_name = video_name + '.video';
                    } else {
                        video_full_name = video_name;
                        video_name = video_full_name.substr(0, video_full_name.lastIndexOf('.'));
                    }

                    // alert(video_title);
                    video_player_url = data.player;
                    video_tags = data.tags;
                    video_thum = data.bigThumbnail;
                    video_duration = data.duration;
                    token = mw.user.tokens.get('editToken');
                    checkName(video_name,video_thum,token,video_from, video_id, video_player_url, video_tags, video_duration,'');
                    //ajax insert video's info
                });
            break;
            case 'bilibili':
                var regex2 = /av([\d]+?)\//;
                var regex3 = /index_([\d]+?).html/;
                var id = url.match(regex2);
                if (id != null && id[1] != null){
                    video_id = id[1];
                    video_from = 'bilibili';
                    var page = url.match(regex3);
                    var pageNum = 1;
                    var suffix = '';
                    if (page != null && page[1] != null){
                        pageNum = page[1];
                        suffix = page[1];

                    }
                }else{
                    mw.notification.notify('上传失败（URL不支持）');
                    return;
                }
                //bili ajax
                $.post(
                    mw.util.wikiScript(), {
                        action: 'ajax',
                        rs: 'wfGetBiliVideoInfo',
                        rsargs: [video_id, pageNum]
                    },
                    function (data) {
                        console.log(data);
                        var data = jQuery.parseJSON(data);
                        if (data.title) {
                            // var title_str = data.title.replace('.video','');
                            video_orig_title = data.title;
                            video_full_name;
                            video_name = $('#video-upload-modal-name').val();
                            if (video_name == ''){
                                video_name = video_orig_title;
                            }
                            if (video_name.indexOf('.video') < 0){
                                video_full_name = video_name + '.video';
                            } else {
                                video_full_name = video_name;
                                video_name = video_full_name.substr(0, video_full_name.lastIndexOf('.'));
                            }

                            // alert(video_title);
                            // http://bilibili.cloudmoe.com/?api=h5_hd&page=1&id=2637306
                            video_player_url = 'http://bilibili.cloudmoe.com/?api=h5_hd&page='+data.pages+'&id='+video_id;
                            var video_page_id = video_id+'-'+pageNum;
                            video_tags = data.tag;
                            video_thum = data.pic;
                            video_duration = '';
                            token = mw.user.tokens.get('editToken');
                            checkName(video_name,video_thum,token,video_from, video_page_id, video_player_url, video_tags, video_duration, '');
                        } else {
                            mw.notification.notify('上传失败（bilibili返回错误）');
                            return;
                        }
                    }
                );
                break;
            // case 'qq':
            // default:
        }


    });

});