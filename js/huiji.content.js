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
//        var self = this;
//        this.pageRec.forEach(function(item,i){
//            if(i<4) {
//                var content = '';
//                var address = 'http://' + item.site + '.huiji.wiki/wiki/' + item.title;
//                content += '<div id="' + i + '" class="re-opacity recommend-item lazy-loading">' +
//                    '<div class="recommend-title"><a href="http://' + item.site + '.huiji.wiki/wiki/' + item.title + '" title="'+item.title+'" >' +
//                    item.title + '</a><a href="http://' + item.site + '.huiji.wiki">' + item.siteName + '</a></div></div>';
//                self.arr.push(item.title+item.siteName);
//                self.funGetImg(item.title, i, item.site,address,false);
//                $('.recommend').append(content);
//            }
//        });
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
        var obj = new Object();
        var self = this;
        this.pageRec.forEach(function(item,i){
            if(i<4) {
                var content = '';
                var address = 'http://' + item.site + '.huiji.wiki/wiki/' + item.title;
                content += '<div id="page' + i + '" class="re-opacity recommend-item lazy-loading">' +
                    '<div class="recommend-title"><a href="http://' + item.site + '.huiji.wiki/wiki/' + item.title + '" title="'+item.title+'" >' +
                    item.title + '</a><a href="http://' + item.site + '.huiji.wiki">' + item.siteName + '</a></div></div>';
                self.arr.push(item.title+item.siteName);
                self.funGetImg(item.title, 'page'+i, item.site,address,false);
                $('.recommend').append(content);
            }
        });
//        self.item = self.pageRec.length>=4?4:self.pageRec.length;
        $.ajax({
            url:'http://121.42.179.100:8080/queryService/webapi/page/recommend/',
            data:{content:self.searchName,category:self.category,sitePrefix:mw.config.get('wgHuijiPrefix')},
            success: function (data) {
                var len = self.pageRec.length>=4?4:self.pageRec.length;
                self.netData = data;
                if (data.length == 0) return;
                for(var n=len;n<10;n++) {
                    if (obj[data[n].sitePrefix] == null) {

                        obj[data[n].sitePrefix] = new Array();
                        obj[data[n].sitePrefix].value = data[n].siteName;

                    }
                        obj[data[n].sitePrefix].push(data[n].title)

                }
                for(var i in obj){
                    var title = '';
                    var address = 'http://'+i+'.huiji.wiki';
                    obj[i].forEach(function(item){
                        title+=item+'|';
                    });
                    title = title.substring(0,title.length-1);
                    $.ajax({
                        url: 'http://' + i + '.huiji.wiki/api.php?action=query&prop=pageimages&pilimit=max&format=json&pithumbsize=250&titles=' + title,
                        type: 'get',
                        success: function (data) {
                            for( var x in data.query.pages) {
                                if (data.query.pages[x].thumbnail) {
                                    var percent = data.query.pages[x].thumbnail.width / data.query.pages[x].thumbnail.height;
                                    var content = '<div id="' + self.item + '" class="recommend-item lazy-loading"><a href="'+address+'/wiki/'+data.query.pages[x].title+'"><img data-src="' + data.query.pages[x].thumbnail.source + '">' +
                                        '<div class="recommend-title">' +
                                        '<a href="http://'+i+'.huiji.wiki/wiki/'+data.query.pages[x].title+'" title="'+data.query.pages[x].title+'">' + data.query.pages[x].title + '</a>' +
                                        '<a href="'+address+'">' + obj[i].value + '</a></div></div>';

                                    $('.recommend').append(content);
                                    self.funClipImg(percent,self.item);

                                } else {
                                    var content = '<div id="' + self.item + '" class="recommend-item lazy-loading"><a href="'+address+'/wiki/'+data.query.pages[x].title+'"><img data-src="/skins/bootstrap-mediawiki/img/recommend.png">' +
                                        '<div class="recommend-title">' +
                                        '<a href="http://'+i+'.huiji.wiki/wiki/'+data.query.pages[x].title+'" title="'+data.query.pages[x].title+'">' + data.query.pages[x].title + '</a>' +
                                        '<a href="'+address+'">' + obj[i].value + '</a></div></div>';
                                    $('.recommend').append(content);
                                }
                                self.item++;
                            }
                            setTimeout(function(){
                                lazyLoad.autocheck();
                            },100);
                            if(document.body.clientWidth>768)
                                doOwl();
                            function doOwl(){
                                $("#recommend").owlCarousel({
                                    items: 5,
                                    beforeMove: function () {
                                        var sp = $('.owl-wrapper').css('transform').split(',');
                                        var len = self.pageRec.length;
                                        var data = self.netData;
                                        var n = self.item;
                                        sp = Math.abs(parseInt(sp[4]));


                                        if(n<30) {
                                            if (sp > ($('.owl-item').length - 5) * $('.owl-item').width()) {
                                                setTimeout(function(){
                                                    var load = '<div class="recommend-load"></div>';
                                                    self.funAddItem(load,n);
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
                                        }else{
                                            if (sp > ($('.owl-item').length - 5) * $('.owl-item').width()) {
                                                setTimeout(function(){
                                                    for(var i=n; i<n+2; i++){
                                                        var img = '<div id="'+id+'" class="recommend-item"><a href="http://www.huiji.wiki/wiki/Special:SendHiddenGift?award=72"><img class="" src="/skins/bootstrap-mediawiki/img/found.jpg"></a><div class="recommend-title">' +
                                                            '<a href="http://www.huiji.wiki/wiki/Special:SendHiddenGift?award=72" class="recommend-found-a">U FOUND ME</a><p class="recommend-found-p">cool cool cool!</p></div>';
                                                        self.funAddItem(img,n);
                                                        self.item++;
                                                    }
                                                },100);


                                            }
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
//                var content = '';
//                var len = self.pageRec.length;
//                console.log(data);
//                self.netData = data;
//                if (data.length == 0) return;
//
//                //除去指定推荐，剩下的进行遍历
//                document.body.clientWidth>768?pcTraverse():mobilTraverse();
//                $('.recommend').append(content);
//
//
//                function pcTraverse(){
//                    for (var i = len; i < 10; i++) {
//                        var item = data[i - len];
//                        var searchtitle = item.title;
//                        var id = item.id;
//
//                        //排除本词条和重复词条
////                    if (id != self.myId && self.arr.indexOf(searchtitle+item.siteName)<0) {
//                        var sitePrefix = item.sitePrefix;
//
//                        //去em标签
//                        searchtitle = searchtitle.replace(/<em>/g, '').replace(/<\/em>/g, '');
//
//                        content += '<div id="' + i + '" class="re-opacity recommend-item lazy-loading"><div class="recommend-title">' +
//                            '<a href="' + item.address + '" title="'+item.title+'">' + item.title + '</a><a href="http://' + item.sitePrefix + '.huiji.wiki">' + item.siteName + '</a></div></div>';
//                        self.funGetImg(searchtitle, i, sitePrefix, item.address, false);
//                    }
//                    self.item = i;
//                }
//                function mobilTraverse(){
//                    for (var i = len; i < 4; i++) {
//                        var item = data[i - len];
//                        var searchtitle = item.title;
//                        var id = item.id;
//
//                        //排除本词条和重复词条
////                    if (id != self.myId && self.arr.indexOf(searchtitle+item.siteName)<0) {
//                        var sitePrefix = item.sitePrefix;
//
//                        //去em标签
//                        searchtitle = searchtitle.replace(/<em>/g, '').replace(/<\/em>/g, '');
//
//                        content += '<div id="' + i + '" class="re-opacity recommend-item lazy-loading"><div class="recommend-title">' +
//                            '<a href="' + item.address + '" title="'+item.title+'">' + item.title + '</a><a href="http://' + item.sitePrefix + '.huiji.wiki">' + item.siteName + '</a></div></div>';
//                        self.funGetImg(searchtitle, i, sitePrefix, item.address, false );
//                    }
//                    self.item = i;
//                }
////                }

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
                        var img = '<a href="'+address+'"><img data-src="' + data.query.pages[x].thumbnail.source + '"></a>';
                        var percent = data.query.pages[x].thumbnail.width / data.query.pages[x].thumbnail.height;

                        $('#' + id ).prepend(img);
                        $('#' + id).removeClass('re-opacity');

                        self.funClipImg(percent,id);

                    } else {
                        $('#' + id).removeClass('re-opacity');
                        $('#' + id).prepend('<a href="'+address+'"><img data-src="/skins/bootstrap-mediawiki/img/recommend.png"></a>');
                    }
                }
                if(ajax == false) {
                    if ($('.recommend-header').length == 0) {
                        $('.recommend').before('<h2 class="recommend-header">更多推荐</h2>');
                    }

                    //最后一次取图
                    if (id == self.item-1) {
                        if ($('.recommend .recommend-item').length == 0) $('.recommend').remove();
                        setTimeout(function(){
                            lazyLoad.autocheck();
                        },100);
                        if(document.body.clientWidth>768)
                        doOwl();
                    }
                }

                function doOwl(){
                    $("#recommend").owlCarousel({
                        items: 5,
                        beforeMove: function () {
                            var sp = $('.owl-wrapper').css('transform').split(',');
                            var len = self.pageRec.length;
                            var data = self.netData;
                            var n = self.item;
                            sp = Math.abs(parseInt(sp[4]));


                            if(n<30) {
                                if (sp > ($('.owl-item').length - 5) * $('.owl-item').width()) {
                                    setTimeout(function(){
                                        var load = '<div class="recommend-load"></div>';
                                        self.funAddItem(load,n);
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
                            }else{
                                if (sp > ($('.owl-item').length - 5) * $('.owl-item').width()) {
                                    setTimeout(function(){
                                        for(var i=n; i<n+2; i++){
                                            var img = '<div id="'+id+'" class="recommend-item"><a href="http://www.huiji.wiki/wiki/Special:SendHiddenGift?award=72"><img class="" src="/skins/bootstrap-mediawiki/img/found.jpg"></a><div class="recommend-title">' +
                                                '<a href="http://www.huiji.wiki/wiki/Special:SendHiddenGift?award=72" class="recommend-found-a">U FOUND ME</a><p class="recommend-found-p">cool cool cool!</p></div>';
                                            self.funAddItem(img,n);
                                            self.item++;
                                        }
                                    },100);

                                }
                            }
                        }
                    });
                }
            }
        });
    },

    funClipImg: function(percent,id){
        var wid,wid2;
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
    },

    funAddItem: function(img,n){
        $('#recommend').data('owlCarousel').addItem(img);
        $('#recommend').data('owlCarousel').jumpTo(n);
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
                        img = '<div id="' + id + '" class="recommend-item"><a href="'+address+'"><img src="' + data.query.pages[x].thumbnail.source + '"></a><div class="recommend-title">' +
                            '<a href="' + item.address + '" >' + item.title + '</a><a href="http://' + item.sitePrefix + '.huiji.wiki">' + item.siteName + '</a></div></div>';
                        var percent = data.query.pages[x].thumbnail.width / data.query.pages[x].thumbnail.height;
                        if($('.recommend-load').length>0){
                            $('#recommend').data('owlCarousel').removeItem();
                        }
                        self.funAddItem(img,n);
                        self.funClipImg(percent,id);

                    } else {
                        img = '<div id="' + id + '" class="recommend-item"><a href="'+address+'"><img src="/skins/bootstrap-mediawiki/img/recommend.png"></a><div class="recommend-title">' +
                            '<a href="' + item.address + '" title="'+item.title+'">' + item.title + '</a><a href="http://' + item.sitePrefix + '.huiji.wiki">' + item.siteName + '</a></div></div>';
                        if($('.recommend-load').length>0){
                            $('#recommend').data('owlCarousel').removeItem();
                        }
                        self.funAddItem(img,n);
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

var lazyLoad = {
    area1: $('#recommend').get(0),
    //懒加载子域集合
    arr:[this.area1],


    getClient:function (){
    var l, t, w, h;
        l = document.documentElement.scrollLeft || document.body.scrollLeft;
        t = document.documentElement.scrollTop || document.body.scrollTop;
        w = document.documentElement.clientWidth;
        h = document.documentElement.clientHeight;
        return { left: l, top: t, width: w, height: h };
    },
    getSubClient:function (p){
        var l = 0, t = 0, w, h;
        w = p.offsetWidth;
        h = p.offsetHeight;
        while(p.offsetParent){
            l += p.offsetLeft;
            t += p.offsetTop;
            p = p.offsetParent;
        }
        return { left: l, top: t, width: w, height: h };
    },
    intens:function (rec1, rec2){
        var lc1, lc2, tc1, tc2, w1, h1;
        lc1 = rec1.left + rec1.width / 2;
        lc2 = rec2.left + rec2.width / 2;
        tc1 = rec1.top + rec1.height / 2 ;
        tc2 = rec2.top + rec2.height / 2 ;
        w1 = (rec1.width + rec2.width) / 2 ;
        h1 = (rec1.height + rec2.height) / 2;
        return Math.abs(lc1 - lc2) < w1 && Math.abs(tc1 - tc2) < h1 ;
    },
    detection: function (arr, prec1, callback){
        var prec2;
        var self = this;
        for (var i = arr.length - 1; i >= 0; i--) {
            if (arr[i]) {
                prec2 = self.getSubClient(arr[i]);
                if (self.intens(prec1, prec2)) {
                    callback(arr[i]);
    // 加载资源后，删除监测
                    delete arr[i];
                }
            }
        }
    },
    autocheck: function (){
        var prec1 = this.getClient();
        var arr = [$('#recommend').get(0)];
        this.detection(arr, prec1, function(obj){
            $('.lazy-loading img').each(function(){
                this.src = $(this).data('src');
                $(this).removeAttr('data-src');
            })
        })
    },

    init: function(){
        var self = this;
        self.autocheck();
        window.onscroll = function(){
            self.autocheck()
        };
        window.onresize = function(){
            self.autocheck()
        }
    }

}
$(function(){

    recommend.init();

    lazyLoad.init();
});
