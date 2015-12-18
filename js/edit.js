/**
 * Created by huiji-001 on 2015/12/15.
 */
function addCategory(category){
    var api = new mw.Api()
    api.postWithToken( "edit", {
        action: "edit",
        title: mw.config.get( "wgPageName" ),
        summary: "快速添加分类",
        appendtext: category
    } ).done( function( result, jqXHR ) {
        var a ='';
        var b = category.split(']][[Category:');
        b[0] = b[0].substring(11);
        b[b.length-1] = b[b.length-1].substring(0,b[b.length-1].length-2);
        b.forEach(function(i){
            a += '<li><a href="/wiki/%E5%88%86%E7%B1%BB:'+i+'" title="分类:'+i+'">'+i+'</a></li>'
        })
        $('#catlinks .last').before(a);
        $('#add-category-btn').popover('hide');
    } ).fail( function( code, result ) {
        if ( code === "http" ) {
            console.log( "HTTP error: " + result.textStatus ); // result.xhr contains the jqXHR object
        } else if ( code === "ok-but-empty" ) {
            mw.log( "Got an empty response from the server" );
        } else {
            mw.log( "API error: " + code );
        }
    } );
}
function addSpan(){
    var val = $('.edit-field input').val();
    var html = '<li class="edit-span"><i class="glyphicon glyphicon-remove"></i>'+val+'</li>';
    if(val == ''||val == ' '){
        mw.notification.notify('分类不能为空，请重新输入');
        $('.edit-field input').val('');
        return;
    }
    $('#mw-normal-catlinks>ul>li:not(.last)>a,.edit-span').each(function(){
        if($(this).text()==val){
            mw.notification.notify('分类不能重复，请重新输入');
            $('.edit-field input').val('');
            return;
        }
    });
    $('.edit-field').before(html);
    $('.edit-field input').val('');
}
function getData(){
    var data='';
    $('.edit-choices .edit-span').each(function(){
        data += '[[Category:'+$(this).text()+']]';
    });
    return data;
}
$(function(){
    if (mw.config.get('wgUserName')==''){
        return;
    }
    var addbtn = '<li class="last"><a id="add-category-btn" data-placement="bottom">添加分类</a></li>';
    var content = '<div class="edit">' +
        '<div class="edit-input">'+
        '<ul class="edit-choices">'+
        '<li class="edit-field"><input type="text"></li>'+
        '</ul>'+
        '</div>'+
        '<p class='edit-tutorial'>可输入多个，在每个分类后轻敲回车。</p>'+
        '<div class="edit-buttons">' +
        '<button class="btn btn-primary edit-submit"><i class="glyphicon glyphicon-ok"></i></button>&nbsp;' +
        '<button type="button" class="btn edit-cancel"><i class="glyphicon glyphicon-remove"></i></button>' +
        '</div>' +
        '</div>';
    $('#mw-normal-catlinks>ul').append(addbtn);
    $('#add-category-btn').popover({title:'添加分类',content:content,html:true});
    $('#mw-normal-catlinks').on('keyup','.edit-field input',function(event){
       if(event.keyCode == 13){
           addSpan();
       }
    }).on('click','.edit-input i',function(){
        $(this).parents('.edit-choices li').remove();
    }).on('click','.edit-input',function(){
        $('.edit-field input').focus();
    }).on('click','.edit-submit',function(e){
        e.stopPropagation();
        var data = getData();
        addCategory(data);
    }).on('click','.edit-cancel',function(){
        $('#add-category-btn').popover('hide');
    });
});
