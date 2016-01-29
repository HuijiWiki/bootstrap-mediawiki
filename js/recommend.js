/**
 * Created by huiji-001 on 2016/1/22.
 */
function getImg(title,id,sitePrefix){
    $.ajax({
        url:'http://'+sitePrefix+'.huiji.wiki/api.php?action=query&prop=pageimages&format=json&pithumbsize=250&titles='+title,
        type: 'get',
        success: function(data){
            if(data.query.pages[id].thumbnail) {
                var img = '<img src="' + data.query.pages[id].thumbnail.source + '">';
                var wid;
                var wid2;
                var percent = data.query.pages[id].thumbnail.width/data.query.pages[id].thumbnail.height;
                $('#' + id).prepend(img);
                $('#'+id).removeClass('re-opacity');
                if(percent>0.7){
                    wid = percent*100-70;
                    wid2 = percent*100+70;
                    $('#'+id).find('img').css({'clip':'rect(0,'+wid2+'px,200px,'+wid+'px)','left':'-'+wid+'px','height':'200px'});
                }else if(percent<0.7){
                    wid = 1/percent*70-100;
                    wid2 = 1/percent*70+100;
                    $('#'+id).find('img').css({'clip':'rect('+wid+'px,140px,'+wid2+'px,0)','top':'-'+wid+'px','width':'140px'});
                }
                if($('.recommend-header').length==0){
                    $('.recommend').before('<h3 class="recommend-header">更多推荐</h3>');
                }
            }else{
                $('#'+id).remove();
            }
        }
    });
}
$(function(){
    if($('body').hasClass('ns-0')){
        var searchname = mw.config.get('wgPageName');
        var myid = mw.config.get('wgArticleId');
        if( searchname == ''|| searchname == null|| searchname == '首页') return;
        $.ajax({
            url:'http://121.42.179.100:8080/queryService/webapi/page/recommend/',
            data:{content:searchname},
            success: function (data) {
                var content = '<ul class="recommend">';
                if (data.length == 0) return;
                for(var i=0;i<4;i++){
                    var item = data[i];
                    var searchtitle = item.title;
                    var id = item.id;
                    if (id == myid) return;
                    var sitePrefix = item.sitePrefix;
                    console.log(item);
                    searchtitle = searchtitle.replace(/<em>/g, '').replace(/<\/em>/g, '');
                    content += '<li id="' + id + '" class="re-opacity"><div class="recommend-title"><a href="' + item.address + '" >' + item.title + '</a><a href="http://' + item.sitePrefix + '.huiji.wiki">' + item.siteName + '</a></div></li>';
                    getImg(searchtitle, id, sitePrefix);
                }
                content += '</ul>';
                $('#bodyContent').append(content);
            },
            type: 'post'
        });
    }
});
        