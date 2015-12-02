$(document).ready(function(){

    // check weibo login WB2.checkLogin()
    if (WB2.checkLogin()==true && mw.config.get('wgUserId') == null) {
        $('.qqlogin').modal({backdrop: 'static', keyboard: false});
    };
    var u_gender;
    var u_avatar;
    u_gender = mw.cookie.get( 'user_gender' );
    u_avatar = mw.cookie.get( 'user_avatar' );
    //gender & del gender cookie
    if( u_gender !=  null && mw.config.get( 'wgUserId' ) != null ){
        // var api = new mw.Api();
        new mw.Api().saveOption( 'gender',u_gender );
        mw.cookie.set( 'user_gender' , null);
    }
    //user avatar & del avatar cookie
    if( u_avatar != null && mw.config.get( 'wgUserId' ) != null ){
    	var api = new mw.Api();
    	api.postWithToken('edit', { 'action': 'avatarsubmit', 'format': 'json', 'avatar_src': u_avatar })
			.done(function( response ) {
				mw.cookie.set( 'user_avatar' , null);
			} );
    }
	$('.set-menu>li:last-child').on('click','a',function(e){
		WB2.logout();
   });
	if(mw.config.get('wgUserId') != null){
		return '';
	}

	var paras = {};
	// var qqOpenid;
	var wbOpenId;

	$('#qqConfirm').click(function(){
		var username = $("#qqloginusername").val();
		var email = $("#qqloginemail").val();
		var pass = $("#qqloginpassword").val();
		var qqOpenId = $('#qqOpenId').val();
		var userGender = $('#userGender').val();
		var userAvatar = $('#userAvatar').val();
		var userType = $('#userType').val();
		mw.cookie.set( 'user_gender', userGender );
		mw.cookie.set( 'user_avatar', userAvatar );
		wiki_signup(userType,username,email,pass,qqOpenId);
	})
	var loginerror = $('.login-error');
    function wiki_signup(type,login,email,pass,outhId){
        $.post('/api.php?action=createaccount&name='+login+'&email='+email+'&password='+pass+ '&format=json',function(data){
            if(login==''){
                $('#qqConfirm').button('reset');
                mw.notification.notify('您的用户名不能为空');
            }
            else if(email==''){
                $('#qqConfirm').button('reset');
                mw.notification.notify('您的邮箱必须填写');
            }
            else if(data.createaccount.result=='NeedToken'){
                $.post('/api.php?action=createaccount&name='+login+'&email='+email+'&password='+pass+'&token='+data.createaccount.token+ '&format=json',function(data){
                    // console.log(data);
                    if(!data.error){
                        if(data.createaccount.result=="Success" ){
                            mw.notification.notify('注册成功，请稍候');
                            addOauth(type,outhId,data.createaccount.userid);
                        }
                        else{
                            $('#qqConfirm').button('reset');
                            mw.notification.notify(data.createaccount.result);
                        }
                    }
                    else{
                        $('#qqConfirm').button('reset');
                        if(data.error.code=='userexists'){
                            mw.notification.notify('用户名已存在');
                        }else if(data.error.code=='passwordtooshort'){
                            mw.notification.notify('密码太短');
                        }else if(data.error.code=='password-name-match'){
                            mw.notification.notify('您的密码不能与用户名相同');
                        }else if(data.error.code=='invalidemailaddress'){
                            mw.notification.notify('请您输入正确的邮箱');
                        }else if(data.error.code=='createaccount-hook-aborted'){
                            mw.notification.notify('您的用户名不合法');
                        }else if(data.error.code=='wrongpassword'){
                            mw.notification.notify('错误的密码进入，请重试');
                        }else if(data.error.code=='mustbeposted'){
                            mw.notification.notify('需要一个post请求');
                        }else if(data.error.code=='externaldberror'){
                            mw.notification.notify('有一个身份验证数据库错误或您不允许更新您的外部帐户');
                        }else if(data.error.code=='password-login-forbidden'){
                            mw.notification.notify('使用这个用户名或密码被禁止');
                        }else if(data.error.code=='sorbs_create_account_reason'){
                            mw.notification.notify('你的IP地址被列为DNSBL代理');
                        }else if(data.error.code=='nocookiesfornew'){
                            mw.notification.notify('没有创建用户账户，请确保启用cookie刷新重试');
                        }else {
                            mw.notification.notify('error' + data.error.code);
                            console.log(data.error.code);
                        }
                    }
                });
            }
            else{
                $('#qqConfirm').button('reset');
                mw.notification.notify('error' + data.error.code);
            }
        });
    }

    function addOauth(type,openid,userid){

        $.post(
            mw.util.wikiScript(), {
                action: 'ajax',
                rs: 'wfAddInfoToOauth',
                rsargs: [type, openid, userid]
            },
            function (data) {
                var res = $.parseJSON(data);
                if (res.success == true) {
                	window.location.href = 'http://huiji.wiki';
                } 
            }
        );
    }
    WB2.anyWhere(function (W) {
	    W.widget.connectButton({
	        id: "wb_connect_btn",
	        type: '3,2',
	        callback: {
	            login: function (o) { //登录后的回调函数
	                // alert("login: " + o.screen_name)
	                // console.log("o"+o);
	                // var wbId = o.id; o.avatar_large gender: "m"
	                if (o.gender == 'm'){
			            gender = 'male';
			        }else if(o.gender == 'f'){
			            gender = 'female';
			        }else{
			            gender = 'unknown';
			        }
			        mw.cookie.set( 'user_gender', gender );
			        mw.cookie.set( 'user_avatar', o.avatar_large );
	                wbOpenId = o.id;
	                $.post(
		                mw.util.wikiScript(), {
		                    action: 'ajax',
		                    rs: 'wfCheckOauth',
		                    rsargs: [wbOpenId, 'weibo']
		                },
		                function (data) {
		                    var res = $.parseJSON(data);
		                    if (res.success == true) {
		                        if (res.result == null){
		                        	var content = '';
		                        	content = '<form><label for="wbloginname">用户名</label><input type="text" id="wbloginusername" class="form-control" value="'+o.name+'" name="wbloginname">'+
		                            '<label for="wbloginemail">邮箱</label><input type="email" class="form-control" id="wbloginemail" placeholder="请输入邮箱" name="wbloginemail">'+
		                        	'<label for="wbloginpass">密码</label><input type="password" id="wbloginpassword" class="form-control" placeholder="请输入密码" name="qqloginpass"><div class="mw-ui-button  mw-ui-block mw-ui-constructive" id="wbConfirm">提交</div></form>';
		                            $('.qqlogin').modal({backdrop: 'static', keyboard: false});
		                        	$('.qqlogin .modal-body').append(content);
		                        }else{
		                        	var userId = res.result;
		                        	// alert(res.result);
		                        	$.post(
		                        		mw.util.wikiScript(), {
					                    action: 'ajax',
					                    rs: 'wfAddUserOauthCookie',
					                    rsargs: [ userId ]
					                },
					                function (data) {
					                	var res = $.parseJSON(data);
		                    			if (res.success == true) {                   				                               
				                            location.href = updateQueryStringParameter(location.href, 'loggingIn', '1');
					                	}
					                });
					               
		                        }
		                    } 
		                }
		            );
	            },
	            logout: function () { //退出后的回调函数
	                alert('logout');
	            }
	        }
	    });
	});
	$("body").on("click","#wbConfirm",function(){
		var username = $("#wbloginusername").val();
		var email = $("#wbloginemail").val();
		var pass = $("#wbloginpassword").val();
		wiki_signup("weibo",username,email,pass,wbOpenId);
	});
});


// WB2.checkLogin()   true or false
