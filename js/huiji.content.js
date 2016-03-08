var recommend = {
    searchName: mw.config.get('wgPageName'),
    myId: mw.config.get('wgArticleId'),
    category: '',
    pageRec: mw.config.get('wgRecByUser')||[],
    arr: [],
    item: 0,
    netData:'',

    funAddBox: function(){
        $('.comments-body').before('<div id="recommend" class="recommend owl-carousel"></div>');
    },

    funGetPageRec: function(){
        var self = this;
        this.pageRec.forEach(function(item,i){
            if(i<4) {
                var content = '';
                content += '<div id="' + i + '" class="re-opacity recommend-item">' +
                    '<div class="recommend-title"><a href="http://' + item.site + '.huiji.wiki/wiki/' + item.title + '" >' +
                    item.title + '</a><a href="http://' + item.site + '.huiji.wiki">' + item.siteName + '</a></div></div>';
                self.arr.push(item.title+item.siteName);
                self.funGetImg(item.title, i, item.site);
                $('.recommend').append(content);
            }
        });
    },

    funGetCategory: function(){
        var self = this;
        if($('#mw-normal-catlinks').length!=0) {
            $('#mw-normal-catlinks li:not(.last)').each(function () {
                self.category += $(this).text() + ' ';
            });
        }
    },

    funGetNetRec: function(){
        var self = this;
        $.ajax({
            url:'http://121.42.179.100:8080/queryService/webapi/page/recommend/',
            data:{content:self.searchName,category:self.category,sitePrefix:mw.config.get('wgHuijiPrefix')},
            success: function (data) {
                var content = '';
                var len = self.pageRec.length;
                self.netData = data;
                if (data.length == 0) return;

                //除去指定推荐，剩下的进行遍历
                if(document.body.clientWidth>768) {
                    for (var i = len; i < 10; i++) {
                        var item = data[i - len];
                        var searchtitle = item.title;
                        var id = item.id;

                        //排除本词条和重复词条
//                    if (id != self.myId && self.arr.indexOf(searchtitle+item.siteName)<0) {
                        var sitePrefix = item.sitePrefix;

                        //去em标签
                        searchtitle = searchtitle.replace(/<em>/g, '').replace(/<\/em>/g, '');

                        content += '<div id="' + i + '" class="re-opacity recommend-item"><div class="recommend-title"><a href="' + item.address + '" >' + item.title + '</a><a href="http://' + item.sitePrefix + '.huiji.wiki">' + item.siteName + '</a></div></div>';
                        self.funGetImg(searchtitle, i, sitePrefix, item.address, false);
                    }
                    self.item = i;
                }else{
                    for (var i = len; i < 4; i++) {
                        var item = data[i - len];
                        var searchtitle = item.title;
                        var id = item.id;

                        //排除本词条和重复词条
//                    if (id != self.myId && self.arr.indexOf(searchtitle+item.siteName)<0) {
                        var sitePrefix = item.sitePrefix;

                        //去em标签
                        searchtitle = searchtitle.replace(/<em>/g, '').replace(/<\/em>/g, '');

                        content += '<div id="' + i + '" class="re-opacity recommend-item"><div class="recommend-title"><a href="' + item.address + '" >' + item.title + '</a><a href="http://' + item.sitePrefix + '.huiji.wiki">' + item.siteName + '</a></div></div>';
                        self.funGetImg(searchtitle, i, sitePrefix, false);
                    }
                    self.item = i;
                }
//                }
                $('.recommend').append(content);
            },
            type: 'post'
        });
    },

    funGetImg: function(title,id,sitePrefix,address,ajax){
        var self = this;
        $.ajax({
            url:'http://'+sitePrefix+'.huiji.wiki/api.php?action=query&prop=pageimages&format=json&pithumbsize=250&titles='+title,
            type: 'get',
            success: function(data){
                var x;
                for( x in data.query.pages) {
                    if (data.query.pages[x].thumbnail) {
                        var img = '<a href="'+address+'"><img class="lazyOwl" src="' + data.query.pages[x].thumbnail.source + '"></a>';
                        var wid;
                        var wid2;
                        var percent = data.query.pages[x].thumbnail.width / data.query.pages[x].thumbnail.height;

                        $('#' + id ).prepend(img);
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
                            $('#' + id).removeClass('re-opacity');
                        }

                    } else {
                        $('#' + id).removeClass('re-opacity');
                        $('#' + id).prepend('<a href="'+address+'"><img class="lazyOwl" src="/skins/bootstrap-mediawiki/img/recommend.png"></a>');
                    }
                }
                if(sitePrefix!=mw.config.get('wgHuijiPrefix')){
                    $('#'+id+' .recommend-title>a:last-child').show();
                }
                if(ajax == false) {
                    if ($('.recommend-header').length == 0) {
                        $('.recommend').before('<h2 class="recommend-header">更多推荐</h2>');
                    }

                    //最后一次取图判断是否去掉推荐内容
                    if (id == self.item-1) {
                        if ($('.recommend .recommend-item').length == 0) $('.recommend').remove();
                        $("#recommend").owlCarousel({
                            items: 5,
//                            lazyLoad: true,
                            beforeMove: function () {
                                var sp = $('.owl-wrapper').css('transform').split(',');
                                var len = self.pageRec.length;
                                var data = self.netData;
                                var n = self.item;
                                sp = Math.abs(parseInt(sp[4]));


                                if(n<30&&document.body.clientWidth>768) {
                                    if (sp > ($('.owl-item').length - 5) * $('.owl-item').width()) {
                                        setTimeout(function(){
                                            var load = '<div class="recommend-load"></div>';
                                            $('#recommend').data('owlCarousel').addItem(load);
                                            $('#recommend').data('owlCarousel').jumpTo(n);
                                        },100);
                                        for (var i = n; i < n + 2; i++) {
                                            var item = data[i - len];
                                            var searchtitle = item.title;
                                            var id = item.id;
                                            //排除本词条和重复词条
//                                        if (id != self.myId && self.arr.indexOf(searchtitle+item.siteName)<0) {
                                            var sitePrefix = item.sitePrefix;

                                            //去em标签
                                            searchtitle = searchtitle.replace(/<em>/g, '').replace(/<\/em>/g, '');

                                            self.funGetImg2(searchtitle, i, sitePrefix, address, item, n, true);
//                                        }
                                            self.item++;
                                        }
                                    }
                                }else if(n>=30&&document.body.clientWidth>768){
                                    if (sp > ($('.owl-item').length - 5) * $('.owl-item').width()) {
                                        setTimeout(function(){
                                            for(var i=n; i<n+2; i++){
                                                var img = '<div id="'+id+'" class="recommend-item"><a href="'+address+'"><img class="" src="/skins/bootstrap-mediawiki/img/found.jpg"></a><div class="recommend-title">' +
                                                    '<a href="http://www.huiji.wiki/wiki/Special:SendHiddenGift?award=72" class="recommend-found-a">U FOUND ME</a><p class="recommend-found-p">cool cool cool!</p></div>';
                                                $('#recommend').data('owlCarousel').addItem(img);
                                                $('#recommend').data('owlCarousel').jumpTo(n);
                                                self.item++;
                                            }
                                        },100);

                                    }
                                }
                            }
                        });
                    }
                }
            }
        });
    },
    funGetImg2: function(title,id,sitePrefix, address, item, n, ajax){
        var self = this;
        $.ajax({
            url: 'http://' + sitePrefix + '.huiji.wiki/api.php?action=query&prop=pageimages&format=json&pithumbsize=250&titles=' + title,
            type: 'get',
            success: function (data) {
                var x;
                var img;
                for (x in data.query.pages) {
                    if (data.query.pages[x].thumbnail) {
                        img = '<div id="' + id + '" class="recommend-item"><a href="'+address+'"><img class="lazyOwl" src="' + data.query.pages[x].thumbnail.source + '"></a><div class="recommend-title">' +
                            '<a href="' + item.address + '" >' + item.title + '</a><a href="http://' + item.sitePrefix + '.huiji.wiki">' + item.siteName + '</a></div></div>';
                        var wid;
                        var wid2;
                        var percent = data.query.pages[x].thumbnail.width / data.query.pages[x].thumbnail.height;
                        if($('.recommend-load').length>0){
                            $('#recommend').data('owlCarousel').removeItem();
                        }
                        $('#recommend').data('owlCarousel').addItem(img);
//                        $('.owl-wrapper-outer').css('transform','translate3d(-'+(n-5)*$('.owl-item').width()+'px, 0px, 0px);');
                        $('#recommend').data('owlCarousel').jumpTo(n);
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

                    } else {
                        img = '<div id="' + id + '" class="recommend-item"><a href="'+address+'"><img class="lazyOwl" src="/skins/bootstrap-mediawiki/img/recommend.png"></a><div class="recommend-title">' +
                            '<a href="' + item.address + '" >' + item.title + '</a><a href="http://' + item.sitePrefix + '.huiji.wiki">' + item.siteName + '</a></div></div>';
                    }
                    if(sitePrefix!=mw.config.get('wgHuijiPrefix')){
                        $('#'+id+' .recommend-title>a:last-child').show();
                    }
                }
            }
        });
    },

    init: function(){
        if(mw.config.get('wgRec')||$('body').hasClass('ns-0')&&!mw.config.get('wgNoRec')){
            this.funAddBox();
            this.funGetPageRec();
            this.funGetCategory();
            this.funGetNetRec();
        }
    }
}
$(function(){

    recommend.init();

});
