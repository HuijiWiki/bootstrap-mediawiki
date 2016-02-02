/**
 * Created by huiji-001 on 2016/1/22.
 */
function getImg(title,id,sitePrefix){
    $.ajax({
        url:'http://'+sitePrefix+'.huiji.wiki/api.php?action=query&prop=pageimages&format=json&pithumbsize=250&titles='+title,
        type: 'get',
        success: function(data){
            var x;
            for( x in data.query.pages) {
                if (data.query.pages[x].thumbnail) {
                    var img = '<img src="' + data.query.pages[x].thumbnail.source + '">';
                    var wid;
                    var wid2;
                    var percent = data.query.pages[x].thumbnail.width / data.query.pages[x].thumbnail.height;
                    $('#' + id).prepend(img);
                    $('#' + id).removeClass('re-opacity');
                    if (percent > 0.7) {
                        wid = percent * 100 - 70;
                        wid2 = percent * 100 + 70;
                        $('#' + id).find('img').css({'clip': 'rect(0,' + wid2 + 'px,200px,' + wid + 'px)', 'left': '-' + wid + 'px', 'height': '200px'});
                    } else if (percent < 0.7) {
                        wid = 1 / percent * 70 - 100;
                        wid2 = 1 / percent * 70 + 100;
                        $('#' + id).find('img').css({'clip': 'rect(' + wid + 'px,140px,' + wid2 + 'px,0)', 'top': '-' + wid + 'px', 'width': '140px'});
                    }
                    else {
                        $('#' + id).find('img').css('height', '200px');
                    }
                    if ($('.recommend-header').length == 0) {
                        $('.recommend').before('<h3 class="recommend-header">更多推荐</h3>');
                    }
                } else {
                    $('#' + id).remove();
                }
            }
            if(id==3){
                if($('.recommend li').length == 0) $('.recommend').remove();
            }
        }
    });
}
$(function(){
    if(mw.config.get('wgRec')||$('body').hasClass('ns-0')){
        var searchname = mw.config.get('wgPageName');
        var myid = mw.config.get('wgArticleId');
        if( searchname == ''|| searchname == null|| mw.config.get('wgIsMainPage') == true) return;
        var category='';
        var rec;
        var content = '<ul class="recommend"></ul>';
        if(mw.config.get('wgNoRec')) return;

        $('.comments-body').before(content);

        rec = mw.config.get('wgRecByUser')||[];
        rec.forEach(function(item,i){
            var content = '';
            content += '<li id="'+i+ '" class="re-opacity"><div class="recommend-title"><a href="' + item.site + '.huiji.wiki/wiki/'+item.title+'" >' +
                item.title + '</a><a href="http://' + item.site + '.huiji.wiki">' + item.siteName + '</a></div></li>';
            getImg(item.title,i,item.site);
            $('.recommend').append(content);
        });

        if($('#mw-normal-catlinks').length!=0) {
            $('#mw-normal-catlinks li:not(.last)').each(function () {
                category += $(this).text() + ' ';
            });
        }
        $.ajax({
            url:'http://121.42.179.100:8080/queryService/webapi/page/recommend/',
            data:{content:searchname,category:category,sitePrefix:mw.config.get('wgHuijiPrefix')},
            success: function (data) {
                var content = '';
                var len = rec.length;
                if (data.length == 0) return;
                for(var i=len;i<4-len;i++){
                    var item = data[i];
                    var searchtitle = item.title;
                    var id = item.id;
                    if (id != myid) {
                        var sitePrefix = item.sitePrefix;
                        searchtitle = searchtitle.replace(/<em>/g, '').replace(/<\/em>/g, '');
                        content += '<li id="' + i + '" class="re-opacity"><div class="recommend-title"><a href="' + item.address + '" >' + item.title + '</a><a href="http://' + item.sitePrefix + '.huiji.wiki">' + item.siteName + '</a></div></li>';
                        getImg(searchtitle, i, sitePrefix);
                    }
                }
                $('.recommend').append(content);
            },
            type: 'post'
        });
    }
});
        