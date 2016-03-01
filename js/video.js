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
    $('body').on('click','.video-upload-modal-btn',function(){
//        $('#wpTextbox1').focus();
        var url = $('#video-upload-modal-url').val();
        //check url & get video_id
        var regex = /\.(\w+)\.com/;
        var match = url.match(regex);
        var video_id,video_from,video_title;
        $(this).attr('disabled','');
        switch(match[1]){
            case 'youku':
                var regex2 = /id_([\w]+?)(?:==|\.html)/;
                var id = url.match(regex2);
                if (id != null && id[1] != null){
                    video_id = id[1];
                    video_from = 'youku';
                }else{
                    alert('failed');
                }
            // case 'qq':
            // default:
        }
        //get video info from youkuapi
        $.get("https://openapi.youku.com/v2/videos/show.json",{
            'client_id':'adc1f452c0653f53',
            'video_id':video_id
        },function(data){
            var title_str = $('#video-upload-modal-name').val().replace('.video','');
            video_title = title_str.replace(/\s/g, '_');
            // alert(video_title);
            var video_player_url = data.player;
            var video_tags = data.tags;
            var video_thum = data.bigThumbnail;
            var video_duration = data.duration;
            var token = mw.user.tokens.get('editToken');
            checkName(video_title,video_thum,token,video_from, video_id, video_player_url, video_tags, video_duration,'');
            //ajax insert video's info
        });

    });

});