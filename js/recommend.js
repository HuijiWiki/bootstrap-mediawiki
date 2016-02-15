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

                    $('#' + id + '>a').prepend(img);
                    $('#' + id).removeClass('re-opacity');

                    //更宽裁剪两边
                    if (percent > 0.7) {
                        wid = percent * 100 - 70;
                        wid2 = percent * 100 + 70;
                        $('#' + id).find('img').css({'clip': 'rect(0,' + wid2 + 'px,200px,' + wid + 'px)', 'left': '-' + wid + 'px', 'height': '200px'});
                    }
                    //更高裁剪上下
                    else if (percent < 0.7) {
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

            //最后一次取图判断是否去掉推荐内容
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
        var arr = [],arr2 = [];
        var content = '<ul class="recommend"></ul>';
        if(mw.config.get('wgNoRec')) return;

        $('.comments-body').before(content);

        //获得指定推荐
        rec = mw.config.get('wgRecByUser')||[];
        rec.forEach(function(item,i){
            if(i<4) {
                var content = '';
                content += '<li id="' + i + '" class="re-opacity"><a href="http://' + item.site + '.huiji.wiki/wiki/' + item.title + '" ></a>' +
                    '<div class="recommend-title"><a href="http://' + item.site + '.huiji.wiki/wiki/' + item.title + '" >' +
                    item.title + '</a><a href="http://' + item.site + '.huiji.wiki">' + item.siteName + '</a></div></li>';
                arr.push(item.title+item.siteName);
                getImg(item.title, i, item.site);
                $('.recommend').append(content);
            }
        });

        //推荐大于等于四个
        if (rec.length>=4) return;

        //获得分类
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

                //除去指定推荐，剩下的进行遍历
                for(var i=len;i<=4-len;i++){
                    var item = data[i];
                    var searchtitle = item.title;
                    var id = item.id;

                    //排除本词条和重复词条
                    if (id != myid && arr.indexOf(searchtitle+item.siteName)<0) {
                        var sitePrefix = item.sitePrefix;

                        //去em标签
                        searchtitle = searchtitle.replace(/<em>/g, '').replace(/<\/em>/g, '');

                        content += '<li id="' + i + '" class="re-opacity"><a href="' + item.address + '" ></a><div class="recommend-title"><a href="' + item.address + '" >' + item.title + '</a><a href="http://' + item.sitePrefix + '.huiji.wiki">' + item.siteName + '</a></div></li>';
                        getImg(searchtitle, i, sitePrefix);
                    }
                }
                $('.recommend').append(content);
            },
            type: 'post'
        });
    }
});
        