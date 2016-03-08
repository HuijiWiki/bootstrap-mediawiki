mw.VideoHandler = {
	doUpload: function( video_name,video_thum,token,video_from, video_id, video_player_url, video_tags, video_duration, n, success, error, is_new_revision ){

	    if ( is_new_revision > 0 ) {
	    	if ( video_from == '163' ) {
	    		var ext = '.audio';
	    	}else{
	    		var ext = '.video';
	    	}
	        $.post('/api.php',{
	            action:'upload',
	            filename: video_name+ext, 
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
		if ( video_from == '163' ) {
    		var ext = '.audio';
    	}else{
    		var ext = '.video';
    	}
		$.post('/api.php',{
	        action:'upload',
	        filename: title+n+ext,
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
	                        filename: title+n+ext,
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
                    video_player_url = 'http://bilibili.cloudmoe.com/?api=h5_hd&page='+pageNum+'&id='+video_id;
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
	},
	query163: function( url, music_names, success, error, is_new_revision ){
		var success = success;
		var error = error;
		var music_name = music_names;
		/**
		 * [regex2 ]
		 * http://music.163.com/#/album?id=3154175
		 * album type:1
		 * song type:2
		 * playlist type:0
		 */
		var regex2 = /\#\/([\w]+?)\?id/;
        var regex3 = /\?id=([\d]+?)$/;
        var type, music_id, music_from, music_orig_title, music_full_name, music_player_url, music_tags, music_thum, music_duration,token;
        var id = url.match(regex3);
        if (id != null && id[1] != null){
            music_id = id[1];
            music_from = '163';
            var music_type = url.match(regex2);
            if ( music_type[1] == 'song' ) {
            	type = 2;
            }else if( music_type[1] == 'album' ){
            	type = 1;
            }else if( music_type[1] == 'playlist' ){
            	type = 0;
            }
        }else{
            mw.notification.notify('上传失败（URL不支持）');
            error();
            return;
        }
        //163 ajax
        $.post(
            mw.util.wikiScript(), {
                action: 'ajax',
                rs: 'wfGet163MusicInfo',
                rsargs: [music_id, type]
            },
            function (data) {
                var data = jQuery.parseJSON(data);
                if (data.code == 200) {
                	//song album playlist
                	if ( type == 2 ) {
		            	music_orig_title = data.songs[0].name;
		            	music_thum = data.songs[0].album.picUrl;
		            	music_tags = data.songs[0].album.tags;
		            	music_duration = data.songs[0].duration;
		            }else if( type == 1 ){
		            	music_orig_title = data.album.name;	
		            	music_thum = data.album.picUrl;
		            	music_tags = '';
		            	music_duration = '';
		            }else if( type == 0 ){
 		            	music_orig_title = data.result.name;
		            	music_thum = data.result.coverImgUrl;
		            	music_tags = '';
		            	music_duration = '';
		            }

		            if (music_name == ''){
                    	music_name = music_orig_title;
                    }
                    if (music_name.indexOf('.audio') < 0){
                        music_full_name = music_name + '.audio';
                    } else {
                        music_full_name = music_name;
                        music_name = music_full_name.substr(0, music_full_name.lastIndexOf('.'));
                    }
		            music_player_url = 'http://music.163.com/outchain/player?type='+type+'&id='+music_id+'&auto=0&height=66';
                    token = mw.user.tokens.get('editToken');
                    mw.VideoHandler.doUpload( music_name,music_thum,token,music_from, music_id, music_player_url, music_tags, music_duration, '', success, error, is_new_revision );
                } else {
                    mw.notification.notify('上传失败（music.163.com返回错误）');
                    error();
                    return;
                }
            }
        );
        

	}

}