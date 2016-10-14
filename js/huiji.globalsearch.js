//    search autocomplete
$('#globalSearchInput').keyup(function (e) {
    e.stopPropagation();
    var length = $(this).width()+'px';
    var searchname = $(this).val();
    searchname = searchname.replace(/\s+/g, ' ');
    if (searchname == ''||searchname == ' ')
    return;
    $.get('http://121.42.179.100:8080/queryService/webapi/page/suggest/' + searchname, function (data) {
        var content = '';
        $('#huiji-search-form #huiji-suggest-result').remove();
        if (data=='')
            return;
        data.forEach(function (item) {
            content += '<li><a href="'+item.address+'">' + item.title + '</a><a href="http://'+item.sitePrefix+'.huiji.wiki">'+item.siteName+'</a></li>';
        });
        $('#huiji-search-form').append('<ul id="huiji-suggest-result">' + content + '</ul>');
        $('#huiji-suggest-result').css('width',length);
    });
}).focus(function(){
    $('#huiji-suggest-result').show();
}).blur(function(){
    setTimeout("$('#huiji-suggest-result').hide();",200);  //计时器用来防止链接点击时不执行跳转
});