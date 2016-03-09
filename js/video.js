function empty(){
    $('#video-upload-modal-url').val('');
    $('#video-upload-modal-name').val('');
}
function onUploadSuccess(filename){
    $('.video-upload-modal').modal('hide');
    var options = {};
    if (filename.indexOf('.audio') > -1){
        options.pre = "[[File:" + filename;
    } else {
        options.pre = "[[File:" + filename + "|thumb|300px|";
    }
    options.post = "]]";
    options.ownline = true;
    //$( '#wpTextbox1' ).textSelection('encapsulateSelection', options);
    mw.toolbar.insertTags("[[File:" + filename + "|thumb|300px|",']]','');
    // if ($('#wpTextbox1').val()) {
    //     var caret = window.caret || 0;
    //     var content = $('#wpTextbox1').val().substring(0, caret) + "[[File:" + filename + "|thumb|300px]]" + $('#wpTextbox1').val().substring(caret);
    //     $('#wpTextbox1').val(content);
    //     $('.video-upload-modal').modal('hide');
    // } else {
    //     $('#wpTextbox1').val("[[File:" + filename + "]]");
    // }
    empty();
    $('.video-upload-modal-btn').removeAttr("disabled");
}
function onUploadError(){
    $('.video-upload-modal-btn').removeAttr("disabled");
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
            mw.notification.notify('请输入URL');
            return;
        }
        //check url & get video_id
        var regex = /\.(\w+)\.com/;
        var match = url.match(regex);
        var video_id,video_from,video_title;
        $(this).attr('disabled','');
        if(!match){
            mw.notification.notify('暂不支持该URL');
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
        var video_name = $('#video-upload-modal-name').val();
        switch(match[1]){
            case 'youku':
                mw.VideoHandler.queryYouku(url, video_name, onUploadSuccess, onUploadError);
                break;
            case 'bilibili':
                mw.VideoHandler.queryBilibili(url, video_name, onUploadSuccess, onUploadError);
                break;
            case '163':
                mw.VideoHandler.query163(url, video_name, onUploadSuccess, onUploadError);
                break; 
            // case 'qq':
            // default:
        }


    });

});