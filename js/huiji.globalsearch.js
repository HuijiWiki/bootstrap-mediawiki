//    search autocomplete
$('#globalSearchInput').keyup(function (e) {
    e.stopPropagation();
    var length = $(this).width()+'px';
    var searchname = $(this).val();
    $.get('http://huijidata.com:8080/queryService/webapi/page/suggest/' + searchname, function (data) {
        var content = '';
        $('#searchform #search-result').remove();
        if (data=='')
        return;
        data.forEach(function (item) {
            content += '<li><a href="http://'+item.sitePrefix+'.huiji.wiki/index.php?curid='+item.id+'">' + item.title + '</a></li>';
        });
        $('#searchform').append('<ul id="search-result">' + content + '</ul>');
        $('#search-result').css('width',length);
    });
}).focus(function(){
    $('#search-result').show();
}).blur(function(){
    setTimeout("$('#search-result').hide();",200);  //计时器用来防止链接点击时不执行跳转
});