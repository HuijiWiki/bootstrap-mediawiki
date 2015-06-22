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
        $.post('http://test.huiji.wiki/api.php?action=createaccount&name='+login+'&email='+email+'&password='+pass+ '&format=json',function(data){
            if(login==''){
                loginerror.show().empty().text('您的用户名不能为空');
            }
            else if(email==''){
                loginerror.show().empty().text('您的邮箱必须填写');
            }
            else if(data.createaccount.result=='NeedToken'){
                $.post('http://test.huiji.wiki/api.php?action=createaccount&name='+login+'&email='+email+'&password='+pass+'&token='+data.createaccount.token+ '&format=json',function(data){
                    if(!data.error){
                        if(data.createaccount.result=="Success"){
                            loginerror.show().empty().text('注册成功');
//                            window.location.reload();
                        }
                        else{
                            loginerror.show().empty().text(data.createaccount.result);
                        }
                    }
                    else{
                        if(data.error.code=='userexists'){
                            loginerror.show().empty().text('用户名已存在');
                        }else if(data.error.code=='passwordtooshort'){
                            loginerror.show().empty().text('密码太短');
                        }else if(data.error.code=='password-name-match'){
                            loginerror.show().empty().text('您的密码不能与用户名相同');
                        }else if(data.error.code=='invalidemailaddress'){
                            loginerror.show().empty().text('请您输入正确的邮箱');
                        }else if(data.error.code=='createaccount-hook-aborted'){
                            loginerror.show().empty().text('您的用户名不合法');
                        }else if(data.error.code=='wrongpassword'){
                            loginerror.show().empty().text('错误的密码进入，请重试');
                        }else if(data.error.code=='mustbeposted'){
                            loginerror.show().empty().text('需要一个post请求');
                        }else if(data.error.code=='externaldberror'){
                            loginerror.show().empty().text('有一个身份验证数据库错误或您不允许更新您的外部帐户');
                        }else if(data.error.code=='password-login-forbidden'){
                            loginerror.show().empty().text('使用这个用户名或密码被禁止');
                        }else if(data.error.code=='sorbs_create_account_reason'){
                            loginerror.show().empty().text('你的IP地址被列为DNSBL代理');
                        }else if(data.error.code=='nocookiesfornew'){
                            loginerror.show().empty().text('没有创建用户账户，请确保启用cookie刷新重试');
                        }
                        else {
                            loginerror.show().empty().text('error' + data.error.code);
                            console.log(data.error.code);
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
            $('.wiki-flog-left img').attr('src',"img/"+now+"left.jpg");
            $('.wiki-flog-right img').attr('src',"img/"+now+"right.jpg");
        },700);
    });
    $('.wiki-content-header li').hover(function(){
        $(this).css('width','40%');
        $(this).siblings().css('width','15%');
    },function(){
        $('.wiki-content-header li').css('width','20%');
    });
});
