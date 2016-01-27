/**
 * Created by huiji-001 on 2016/1/22.
 */
function getImg(title,id,sitePrefix){
    console.log(title+id)
    $.ajax({
        url:'http://'+sitePrefix+'.huiji.wiki/api.php?action=query&prop=pageimages&format=json&pithumbsize=250&titles='+title,
        type: 'get',
        success: function(data){
            if(data.query.pages[id].thumbnail) {
                var img = '<img src="' + data.query.pages[id].thumbnail.source + '">';
                $('#' + id).prepend(img);
                $('#'+id).removeClass('re-opacity');
            }else{
                $('#'+id).remove();
            }
        }
    });
}
$(function(){
    if($('body').hasClass('ns-0')){
        var searchname = mw.config.get('wgPageName').substr(0,2);
        var myid = mw.config.get('wgArticleId');
        if( searchname == ''|| searchname == null|| searchname == '首页') return;
        $.post('http://121.42.179.100:8080/queryService/webapi/page/search/',{content:searchname,size:4,offset:0}, function (data) {
        var content = '<h3 class="recommend-header"></h3><ul class="recommend">';
        var res = data.sites;
        res.forEach(function (item) {
            var searchtitle = item.title;
            var id = item.id;
            if (id == myid) return;
            var sitePrefix = item.sitePrefix;
            searchtitle=searchtitle.replace(/<em>/,'').replace(/<\/em>/,'');
            content += '<li id="'+id+'" class="re-opacity"><div class="recommend-title"><a href="'+item.address+'" >' + item.title + '</a><a href="http://'+item.sitePrefix+'.huiji.wiki">'+item.siteName+'</a></div></li>';
            getImg(searchtitle,id,sitePrefix);
        });
            content +='</ul>';
            $('.comments-body').before(content);
        });
    }
});
        