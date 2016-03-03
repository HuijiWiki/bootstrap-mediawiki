mw.VideoHandler = {
	doUpload: function( video_name,video_thum,token,video_from, video_id, video_player_url, video_tags, video_duration, n, success, error, is_new_revision ){

	    if ( is_new_revision > 0 ) {
	        $.post('/api.php',{
	            action:'upload',
	            filename: video_name+'.video', 
	            // filename: 'video-test7',
	            url: video_thum, 
	            token: token, 
	            ignorewarnings: true,
	            format: 'json',
	            comment: '添加视频'
	        },function(data){
	            if (! data.upload){
	                mw.notification.notify('上传失败（无法上传新版本）');
	            	error();
	                return;
	            }
	            var file_name = data.upload.filename;
	            $.post(
	                mw.util.wikiScript(), {
	                    action: 'ajax',
	                    rs: 'wfinsertVideoInfo',
	                    rsargs: [video_from, file_name, video_id, video_name, video_player_url, video_tags, video_duration]
	                },
	                function( data ) {
	                    var res = jQuery.parseJSON(data);
	                    if (res.success){
	                        /**
	                         * api.php  upload file bigThumbnail as image 
	                         * named as video_title
	                         */
	                        mw.notification.notify('上传成功');
	                        success( file_name );
	                    }else{
	                        mw.notification.notify('上传失败（无法获取视频信息）');
	                        error();
	                    }
	                }
	            );
	            
	        });
	        
	    }else{
	        mw.VideoHandler.checkName(video_name,video_thum,token,video_from, video_id, video_player_url, video_tags, video_duration, n, success, error );
	    }
	},
	checkName: function( title,url,token,video_from, video_id, video_player_url, video_tags, video_duration, n, success, error ){
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
	                    mw.VideoHandler.checkName(title, url, token, video_from, video_id, video_player_url, video_tags, video_duration, n, success, error);
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
	                                    mw.notification.notify('上传成功');
	                                	success(filename);
	                                } else {
	                                    mw.notification.notify('上传失败（Video数据库返回错误1）');
	                                	error();
	                                }
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
	                                mw.notification.notify('上传成功');
	                            	success(filename);
	                            } else {
	                                mw.notification.notify('上传失败（Video数据库返回错误2）');
	                            	error();
	                            }
	                        }
	                 );
	            }
	        }else if(data.error){
	            mw.notification.notify(data.error.code);
	        	error();
	        }
	    });

	},
	queryYouku: function( url, video_names, success, error, is_new_revision ){
		var success = success;
		var error = error;
		var video_name = video_names;
		var regex2 = /id_([\w]+?)(?:==|\.html)/;
        var id = url.match(regex2);
        var video_id, video_from, video_orig_title, video_full_name, video_name, video_player_url, video_tags, video_thum, video_duration,token;
        if (id != null && id[1] != null){
            video_id = id[1];
            video_from = 'youku';
        }else{
            mw.notification.notify('无法找到优酷视频id');
            error();
            return;
        }
        //get video info from youkuapi
        $.get("https://openapi.youku.com/v2/videos/show.json",{
            'client_id':'adc1f452c0653f53',
            'video_id':video_id
        },function(data){
            video_orig_title = data.title;
            if (video_name == ''){
                video_name = video_orig_title;
            }
            if (video_name.indexOf('.video') < 0){
                video_full_name = video_name + '.video';
            } else {
                video_full_name = video_name;
                video_name = video_full_name.substr(0, video_full_name.lastIndexOf('.'));
            }
            video_player_url = data.player;
            video_tags = data.tags;
            video_thum = data.bigThumbnail;
            video_duration = data.duration;
            token = mw.user.tokens.get('editToken');
            mw.VideoHandler.doUpload( video_name,video_thum,token,video_from, video_id, video_player_url, video_tags, video_duration, '', success, error, is_new_revision );
            // mw.VideoHandler.checkName(video_name,video_thum,token,video_from, video_id, video_player_url, video_tags, video_duration,'', success, error );
            //ajax insert video's info
        });
	},
	queryBilibili: function( url, video_names, success, error, is_new_revision ){
		var success = success;
		var error = error;
		var video_name = video_names;
		var regex2 = /av([\d]+?)\//;
        var regex3 = /index_([\d]+?).html/;
        var video_id, video_from, video_orig_title, video_full_name, video_player_url, video_tags, video_thum, video_duration,token;
        var id = url.match(regex2);
        if (id != null && id[1] != null){
            video_id = id[1];
            video_from = 'bilibili';
            var page = url.match(regex3);
            var pageNum = 1;
            if (page != null && page[1] != null){
                pageNum = page[1];
                var video_page_id = video_id+'-'+pageNum;
            }else{
            	video_page_id = video_id;
            }
        }else{
            mw.notification.notify('上传失败（URL不支持）');
            error();
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
                var data = jQuery.parseJSON(data);
                if (data.title) {
                    video_orig_title = data.title;
                    if (video_name == ''){
                        video_name = video_orig_title;
                    }
                    if (video_name.indexOf('.video') < 0){
                        video_full_name = video_name + '.video';
                    } else {
                        video_full_name = video_name;
                        video_name = video_full_name.substr(0, video_full_name.lastIndexOf('.'));
                    }
                    video_player_url = 'http://bilibili.cloudmoe.com/?api=h5_hd&page='+data.pages+'&id='+video_id;
                    video_tags = data.tag;
                    video_thum = data.pic;
                    video_duration = '';
                    token = mw.user.tokens.get('editToken');
                    mw.VideoHandler.doUpload( video_name,video_thum,token,video_from, video_id, video_player_url, video_tags, video_duration, '', success, error, is_new_revision );
                } else {
                    mw.notification.notify('上传失败（bilibili返回错误）');
                    error();
                    return;
                }
            }
        );
	}
}