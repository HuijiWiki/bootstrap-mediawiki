$(document).ready(function(){
	$('.set-menu>li:last-child').on('click','a',function(e){
        QC.Login.signOut();
   });
	if(mw.config.get('wgUserId') != null){
		return '';
	}
	QC.Login({
		//插入按钮的节点id
        btnId:"qqLoginBtn",	
        //按钮尺寸，可用值[A_XL| A_L| A_M| A_S|  B_M| B_S| C_S]，可选，默认B_S
		 size: "A_M"
	});

	var paras = {};
	var openid;

	QC.api("get_user_info", paras)
	// .success(function(s){//成功回调
	// 	alert("获取用户信息成功！当前用户昵称为："+s.data.nickname);

	// })
	// .error(function(f){//失败回调
	// 	alert("获取用户信息失败！");
	// })
	.complete(function(c){//完成请求回调
		// alert("获取用户信息完成！"+c.data.nickname);
		QC.Login.getMe(function(openId, accessToken){
			// alert(openId);
			openid = openId;
			var OauthType = 'qq';
			$.post(
                mw.util.wikiScript(), {
                    action: 'ajax',
                    rs: 'wfCheckOauth',
                    rsargs: [openId, OauthType]
                },
                function (data) {
                    var res = $.parseJSON(data);
                    if (res.success == true) {
                        if (res.result == null){
                        	var content = '';
                        	content = '<form><input type="text" id="qqloginusername" value="'+c.data.nickname+'"><input type="email" id="qqloginemail" placeholder="邮箱">'+
                        	'<input type="password" id="qqloginpassword" placeholder="密码"><div id="qqConfirm">提交</div></form>';
                        	$('.userloginForm').append(content);
                        }else{
                        	// var t = 'aaaaaa';
                        	// $.post('/api.php?action=login&lgname='+c.data.nickname+'&lgpassword='+t+'&format=json',function(data){
                        	// 	if(data.login.result == 'NeedToken'){
                        	// 		$.post('/api.php?action=login&lgname='+c.data.nickname+'&lgpassword='+t+'&lgtoken='+data.login.token+'&format=json',function(data){
                        	// 			console.log('aaa'+data.login.result);
                        	// 		});
                        	// 	}	
                        	// 	// console.log(data.login.result);
                        	// 	});
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
                    				if (mw.config.get('wgCanonicalSpecialPageName') === 'Userlogout'){
		                                location.href = updateQueryStringParameter($('#mw-returnto a').attr('href'), 'loggingIn', '1');
		                            }else {                                
		                                location.href = updateQueryStringParameter(location.href, 'loggingIn', '1');
		                            }
		                            // location.href = updateQueryStringParameter(location.href, 'debug', '1');
			                		// console.log(userId);
			                		// console.log(1111111111111111);
			                		// window.location.reload(true);
			                	}
			                });
			               
                        }
                    } 
                }
            );
		});
		// ajax
		/**
		 * check Oauth
		 * if success (refresh page)
		 * else (alert Registere to registere, then validated nick&email&password, then add info to DB)
		 * set cookie
		 */
	});
	$("body").on("click","#qqConfirm",function(){
		var username = $("#qqloginusername").val();
		var email = $("#qqloginemail").val();
		var pass = $("#qqloginpassword").val();
		wiki_signup("qq",username,email,pass);
	})

	// if(QC.Login.check()){//如果已登录
	// QC.Login.getMe(function(openId, accessToken){
	// 	alert(["当前登录用户的", "openId为："+openId, "accessToken为："+accessToken].join("\n"));
	// });
	//这里可以调用自己的保存接口
	//...
	// }


	var loginerror = $('.login-error');
    function wiki_signup(type,login,email,pass){
        $.post('/api.php?action=createaccount&name='+login+'&email='+email+'&password='+pass+ '&format=json',function(data){
            if(login==''){
                alert('您的用户名不能为空');
            }
            else if(email==''){
                alert('您的邮箱必须填写');
            }
            else if(data.createaccount.result=='NeedToken'){
                $.post('/api.php?action=createaccount&name='+login+'&email='+email+'&password='+pass+'&token='+data.createaccount.token+ '&format=json',function(data){
                    if(!data.error){
                        if(data.createaccount.result=="Success"){
                            alert('注册成功');
                            addOauth(type,openid,data.createaccount.userid);
                        }
                        else{
                            alert(data.createaccount.result);
                        }
                    }
                    else{
                        if(data.error.code=='userexists'){
                            alert('用户名已存在');
                        }else if(data.error.code=='passwordtooshort'){
                            alert('密码太短');
                        }else if(data.error.code=='password-name-match'){
                            alert('您的密码不能与用户名相同');
                        }else if(data.error.code=='invalidemailaddress'){
                            alert('请您输入正确的邮箱');
                        }else if(data.error.code=='createaccount-hook-aborted'){
                            alert('您的用户名不合法');
                        }else if(data.error.code=='wrongpassword'){
                            alert('错误的密码进入，请重试');
                        }else if(data.error.code=='mustbeposted'){
                            alert('需要一个post请求');
                        }else if(data.error.code=='externaldberror'){
                            alert('有一个身份验证数据库错误或您不允许更新您的外部帐户');
                        }else if(data.error.code=='password-login-forbidden'){
                            alert('使用这个用户名或密码被禁止');
                        }else if(data.error.code=='sorbs_create_account_reason'){
                            alert('你的IP地址被列为DNSBL代理');
                        }else if(data.error.code=='nocookiesfornew'){
                            alert('没有创建用户账户，请确保启用cookie刷新重试');
                        }
                        else {
                            alert('error' + data.error.code);
                            console.log(data.error.code);
                        }
                    }
                });
            }
            else{
                alert('error' + data.error.code);
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
                	console.log(res.data);
                    window.location.reload(true);
                } 
            }
        );
    }

});