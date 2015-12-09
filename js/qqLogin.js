$(document).ready(function(){

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
                       location.href = document.referrer;
                } 
            }
        );
    }

});
