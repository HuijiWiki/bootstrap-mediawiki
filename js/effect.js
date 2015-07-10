/**
 * Created by huiji001 on 2015/5/6.
 */
$(document).ready(function(){
//   $('.content-wrapper').scroll(function(){
//       var scrolltop = $(this).scrollTop();
//       $('header').offset({top:scrolltop});
//       if($(this).scrollTop()>50){
//           $(this).addClass('header-scroll');
//       }else{
//           $(this).removeClass('header-scroll');
//       }
//       console.log($(this).scrollTop());
//   });
//    alert($('.content-wrapper').height());
    var alreturn = $('.alert-return');
    var alertp = $('.alert-return p');
    function alertime(){
        alreturn.show();
        setTimeout(function(){
            alreturn.hide()
        },1000);
    }
    function getTopHeight(){
        var pagetop = ($('body').height()-150);
        if(pagetop<550){
            pagetop = 550;
        }
        $(".head").css('height',pagetop+"px");
    }
    getTopHeight();
    window.onresize = function(){
        getTopHeight();
    }
    $("div.lazy").lazyload({
        effect : "fadeIn",
        container: $(".content-wrapper")
    });
   $('.page-front-enter').click(function(){
       $('.wrapper').addClass('home');
       setTimeout(function(){
           $('.content-wrapper').hide();
       },500);
       localStorage.setItem('static','back');
   });
    $('.content-wrapper').scroll(function(){
        var scrollTop = $(this).scrollTop();
        var fscroll = 60-scrollTop*5/100+"px";
        var tscroll = 100 - scrollTop*5/100+"px";
        $('.firstbg,.secondbg').css('background-position','50%'+''+fscroll);
        $('.thirdbg').css('background-position','50%'+''+tscroll);
    });
    var loginerror = $('.login-error');
    function wiki_signup(login,email,pass){
        $.post('/api.php?action=createaccount&name='+login+'&email='+email+'&password='+pass+ '&format=json',function(data){
	    if(login==''){
            alertime();
            alertp.text('您的用户名不能为空');
            }
            else if(email==''){
            alertime();
            alertp.text('您的邮箱必须填写');
            }
            else if(data.createaccount.result=='NeedToken'){
                $.post('/api.php?action=createaccount&name='+login+'&email='+email+'&password='+pass+'&token='+data.createaccount.token+ '&format=json',function(data){
                    if(!data.error){
                        if(data.createaccount.result=="Success"){
                            alertime();
                            alertp.text('注册成功');
                            localStorage.setItem('static','back');
                            $.post('/api.php?action=login&lgname=' + login + '&lgpassword=' + pass + '&format=json',function(data) {
                                if (data.login.result == 'NeedToken') {
                                    $.post('/api.php?action=login&lgname=' + login + '&lgpassword=' + pass + '&lgtoken=' + data.login.token + '&format=json',function(){
                                        window.location.reload();
                                    });
                                }
                            });
                        }
                        else{
                            loginerror.show().empty().text(data.createaccount.result);
                        }
                    }
                    else{
                        alertime();
                        if(data.error.code=='userexists'){
                            alertp.text('用户名已存在');
                        }else if(data.error.code=='passwordtooshort'){
                            alertp.text('密码太短');
                        }else if(data.error.code=='password-name-match'){
                            alertp.text('您的密码不能与用户名相同');
                        }else if(data.error.code=='invalidemailaddress'){
                            alertp.text('请您输入正确的邮箱');
                        }else if(data.error.code=='createaccount-hook-aborted'){
                            alertp.text('您的用户名不合法');
                        }else if(data.error.code=='wrongpassword'){
                            alertp.text('错误的密码进入，请重试');
                        }else if(data.error.code=='mustbeposted'){
                            alertp.text('需要一个post请求');
                        }else if(data.error.code=='externaldberror'){
                            alertp.text('有一个身份验证数据库错误或您不允许更新您的外部帐户');
                        }else if(data.error.code=='password-login-forbidden'){
                            alertp.text('使用这个用户名或密码被禁止');
                        }else if(data.error.code=='sorbs_create_account_reason'){
                            alertp.text('你的IP地址被列为DNSBL代理');
                        }else if(data.error.code=='nocookiesfornew'){
                            alertp.text('没有创建用户账户，请确保启用cookie刷新重试');
                        }
                        else {
                            alertp.text('error' + data.error.code);
                        }
                    }
                });
            }
            else{
                loginerror.show().empty().text('error' + data.error.code);
            }
        });
    }
    $('#home-content-signup .signup-submit').click(function(){
        var login=$('#home-content-signup .sign-username').val();
        var email=$('#home-content-signup .sign-email').val();
        var pass=$('#home-content-signup .sign-pass').val();
//        var passagin=$('#home-content-signup .sign-pass-agin').val();
        wiki_signup(login,email,pass);
    });
    $('#home-content-signup .sign-username,#home-content-signup .sign-email,#home-content-signup .sign-pass').focus(function(){
        $('.login-error').hide();
    });

    //slicebox
    var $slicebox;
    $(function() {
        $slicebox = $('#sb-slider').slicebox({
            interval:6000,
            orientation : "r", //表示幻灯片的切换方向，可取 (v)垂直方向, (h)水平方向 or (r)随机方向
            perspective : 800, //透视点距离，可以通过改变其值查看效果
            cuboidsCount : 1, //幻灯片横向或纵向被切割的块数，切割的每一块将会以3D的形式切换
            cuboidsRandom : true, //是否随机 cuboidsCount 参数的值
            maxCuboidsCount : 1, //设置一个值用来规定最大的 cuboidsCount 值
            colorHiddenSides : "#333", //隐藏的幻灯片的颜色
            sequentialFactor : 150, //幻灯片切换时间（毫秒数）
            speed : 600, //每一块3D立方体的速度
            autoplay : false, //是否自动开始切换
            onBeforeChange : function(position) { return false; },
            onAfterChange : function(position) { return false; }
        });
        $('.previous').click(function () {
            $slicebox.previous();
        });
        $('.next').click(function(){
           $slicebox.next();
        });
        $('.jump').click(function () {
            $slicebox.jump(4);
        })
    });
    $('.previous,.next').click(function(){
        setTimeout(function(){
            var now = $('.sb-slider .sb-current img').data('src');
            $('.wiki-flog-left img').attr('src',"/resources/frontpage/"+now+"left.jpg");
            $('.wiki-flog-right img').attr('src',"/resources/frontpage/"+now+"right.jpg");
        },700);
    });
    $('.wiki-content-header li').hover(function(){
        $(this).css('width','40%');
        $(this).siblings().css('width','15%');
    },function(){
        $('.wiki-content-header li').css('width','20%');
    });
    $('.wiki-content-header li:last').hover(function(){
        $('.all-wiki').addClass('act');
    },function(){
        $('.all-wiki').removeClass('act');
    });
    $('.nav-tab li').click(function(){
       $(this).addClass('active').siblings().removeClass('active');
        var index = $(this).index();
        $('.tab-content>div').eq(index).removeClass('hide').siblings().addClass('hide');
    });
    $('.wikis li').hover(function(){
        var height = $(this).height()-13;
        console.log(height);
        $(this).find('.wiki-info').css('height',height);
    },function(){
        $(this).find('.wiki-info').css('height','110px');
    });
});
