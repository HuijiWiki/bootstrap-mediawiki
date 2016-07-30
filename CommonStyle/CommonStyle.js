///**
// * Created by huiji-001 on 2016/4/25.
// */
//$(function(){
//    var file;
//    var obj = new Object();
////    $('.picker-color').click(function(e){
//////        e.preventDefault();
////        e.stopPropagation();
////    })
//    $.fn.uniqueScroll = function () {
//        $(this).on('mousewheel', _pc)
//            .on('DOMMouseScroll', _pc);
//
//        function _pc(e) {
//            var scrollTop = $(this)[0].scrollTop,
//                scrollHeight = $(this)[0].scrollHeight,
//                height = $(this)[0].clientHeight;
//
//            var delta = (e.originalEvent.wheelDelta) ? e.originalEvent.wheelDelta : -(e.originalEvent.detail || 0);
//
//            if ((delta > 0 && scrollTop <= delta) || (delta < 0 && scrollHeight - height - scrollTop <= -1 * delta)) {
//                this.scrollTop = delta > 0 ? 0 : scrollHeight;
//                e.stopPropagation();
//                e.preventDefault();
//            }
//        }
//
//        $(this).on('touchstart', function (e) {
//            var targetTouches = e.targetTouches ? e.targetTouches : e.originalEvent.targetTouches;
//            $(this)[0].tmPoint = {x: targetTouches[0].pageX, y: targetTouches[0].pageY};
//        });
//        $(this).on('touchmove', _mobile);
//        $(this).on('touchend', function (e) {
//            $(this)[0].tmPoint = null;
//        });
//        $(this).on('touchcancel', function (e) {
//            $(this)[0].tmPoint = null;
//        });
//
//        function _mobile(e) {
//
//            if ($(this)[0].tmPoint == null) {
//                return;
//            }
//
//            var targetTouches = e.targetTouches ? e.targetTouches : e.originalEvent.targetTouches;
//            var scrollTop = $(this)[0].scrollTop,
//                scrollHeight = $(this)[0].scrollHeight,
//                height = $(this)[0].clientHeight;
//
//            var point = {x: targetTouches[0].pageX, y: targetTouches[0].pageY};
//            var de = $(this)[0].tmPoint.y - point.y;
//            if (de < 0 && scrollTop <= 0) {
//                e.stopPropagation();
//                e.preventDefault();
//            }
//
//            if (de > 0 && scrollTop + height >= scrollHeight) {
//                e.stopPropagation();
//                e.preventDefault();
//            }
//        }
//    };
//    $('.picker-color .color-box').each(function(){
//        var variable = $(this).attr('data-variable');
//        var color =  $(this).attr('value');
//        obj[variable] = color;
//    });
//    $('.jcolor').each(function(){
//        if($(this).attr('value')=='false'){
//            $(this).css('color','#000');
//        }
//        $(this).colorpicker({
//            labels: true,
//            displayColorSpace: 'hsla',
//            displayColor: 'css'
//        })
//    });
//    $('aside').on('newcolor','.jcolor',function(e,colorpicker){
//        var variable = $(this).attr('data-variable');
//        var color = colorpicker.toCssString();
//        $(this).siblings('.input-color').val(color);
//        obj[variable] = color;
//        less.modifyVars(obj);
//    });
//
//    $('.picker-img .color-box').click(function(){
//        $(this).siblings('.file-btn').trigger('click');
//    });
//    $('.picker-img .file-btn').change(function(e){
//        var reader = new FileReader();
//        var self = $(this);
//        var file;
//        var selector = $(this).attr('data-selector');
//        file = e.target.files[0];
//        reader.readAsDataURL(file);
//        reader.onload=function(e) {
//            var src = this.result;
//            self.siblings('.color-box').css('background-image','url("'+src+'")');
//            $(selector).css({'background-image':'url("'+src+'")','background-size':'100%'});
//        }
//    });
//    $('.input-color').blur(function(){
//        var val = $(this).val();
//        var variable = $(this).siblings('.color-box').attr('data-variable');
//        if(!display_Check(val)) return;
//        $(this).siblings('.color-box').colorpicker().destroy();
//        $(this).siblings('.color-box').css("color",val).colorpicker();
//        obj[variable] = val;
//        less.modifyVars(obj);
//    });
//    $('.color-picker').uniqueScroll();
//    $('.commonstyle-reset').click(function(){
//        var that = this;
//        $(this).attr('disabled','');
//        var reset = [];
//        var api = new mw.Api();
//        api.postWithToken('edit', {
//            action: 'commonstyle',
//            task: 'reset',
//            format: 'json'
//        }).done( function(data){
//            $(that).removeAttr('disabled');
//            var res = data;
//            if (res.commonstyle.res.success == 'true'){
//                mw.notification.notify('设置成功');
//                location.reload();
//            } else {
//                mw.notification.notify('请使用调试模式刷新页面重试',{tag:'error'});
//            }
//        } );
//        // $.ajax({
//        //     url:mw.util.wikiScript(),
//        //     data:{
//        //         action: 'ajax',
//        //         rs: 'wfUpdateCssStyle',
//        //         rsargs: ['','HuijiColor1',1]
//        //     },
//        //     type: 'post',
//        //     format: 'json',
//        //     success: function(data){
//        //         $(that).removeAttr('disabled');
//
//        //         var res = JSON.parse(data);
//        //         if(res.result == 'true'){
//        //             mw.notification.notify('设置成功');
//        //             location.reload();
//        //         }else{
//        //             mw.notification.notify('请使用调试模式刷新页面重试',{tag:'error'});
//        //         }
//        //     }
//        // });
//    });
//    $('.commonstyle-submit').click(function(){
//        var that = this;
//        $(this).attr('disabled','');
//        var state = $('.is-new').val();
//        var api = new mw.Api();
//        console.log(obj);
//        api.postWithToken('edit', {
//            action: "commonstyle",
//            task: "save",
//            content: JSON.stringify(obj)
//        }).done(function(data){
//            $(that).removeAttr('disabled');
//            var res = data;
//            if(res.commonstyle.res.success == 'true'){
//                mw.notification.notify('设置成功');
//                location.reload();
//            }else{
//                mw.notification.notify('请请使用调试模式刷新页面重试',{tag:'error'})
//            }
//        });
//
//        // $.ajax({
//        //     url:mw.util.wikiScript(),
//        //     data:{
//        //         action: 'ajax',
//        //         rs: 'wfUpdateCssStyle',
//        //         rsargs: [obj,'HuijiColor1',state]
//        //     },
//        //     type: 'post',
//        //     format: 'json',
//        //     success: function(data){
//        //         $(that).removeAttr('disabled');
//
//        //         var res = JSON.parse(data)
//        //         if(res.result == 'true'){
//        //             mw.notification.notify('设置成功');
//        //             location.reload();
//        //         }else{
//        //             mw.notification.notify('请请使用调试模式刷新页面重试',{tag:'error'})
//        //         }
//        //     }
//        // });
//    });
//    $('.picker-label li').click(function(){
//        var index = $(this).index();
//        $('.picker-label li').removeClass('active');
//        $(this).addClass('active');
//        $('.picker-detail li').removeClass('active');
//        $(this).parents('li').find('.picker-detail li').eq(index).addClass('active');
//    });
//
//    var content = '<link rel="stylesheet/less" href="/wiki/special:DynamicLess"><script type="text/javascript">' +
//        'less = {env: "development",async: false,fileAsync: false,poll: 1000,functions: {},dumpLineNumbers: "comments", relativeUrls: true, rootpath: ":/a.com/" };</script>' +
//        '<script src="http://fs.huijiwiki.com/www/resources/assets/less.min.js" type="text/javascript"></script>';
//    $('head').append(content);
//
//    $('.color-picker-item-toggle').click(function(){
//        $(this).siblings('.picker-color').toggle();
//    });
//
//
//});
//function inspect_Color(strColor)
//{
//    var oSpan = document.createElement('span');
//    oSpan.setAttribute('style','color:'+strColor);
//    if(oSpan.style.color != ""||strColor == "false")
//    {
//        return true;
//    }
//    else
//    {
//        return false;
//    }
//    oSpan = null;
//}
//
//function display_Check(strColor)
//{
//    if(inspect_Color(strColor)){
//        return true;
//    }else
//    {
//        mw.notification.notify("请输入正确的颜色",{tag:"color"})
//        return false;
//    }
//
//}
