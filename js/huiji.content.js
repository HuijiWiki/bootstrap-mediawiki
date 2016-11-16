var recommend = {
    searchName: mw.config.get('wgPageName'),
    myId: mw.config.get('wgArticleId'),
    category: '',
    pageRec: mw.config.get('wgRecByUser')||[],
    arr: [],
    item: 0,
    netData:'',
    defaultEntryShowLength: 10,

    funAddBox: function(){
        $('.comments-body').before('<div id="recommend" class="recommend owl-carousel"></div>');
    },

    funGetCategory: function(){
        var self = this;
        if($('#mw-normal-catlinks').length!=0) {
            $('#mw-normal-catlinks li:not(.last)').each(function () {
                self.category += $(this).text() + ' '; //获取推荐内容时需要的参数
            });
        }
    },

    funGetPageRec: function(){
        var self = this;
        this.pageRec.forEach(function(item,i){
            if(i<self.defaultEntryShowLength) {
                var content = '';
                var address = 'http://' + item.site + '.huiji.wiki/wiki/' + item.title;
                content += '<div id="page' + i + '" class="re-opacity recommend-item lazy-loading">' +
                    '<div class="recommend-title"><a href="http://' + item.site + '.huiji.wiki/wiki/' + item.title + '" title="'+item.title+'" >' +
                    item.title + '</a><a href="http://' + item.site + '.huiji.wiki">' + item.siteName + '</a></div></div>';
                self.arr.push(item.title+item.siteName);
                self.funGetPageImg(item.title, 'page'+i, item.site,address,false);
                $('.recommend').append(content);
            }
        });
        self.item = self.pageRec.length>=self.defaultEntryShowLength?self.defaultEntryShowLength:self.pageRec.length; //设置已有项目数，得到默认接口推荐的可显示数量
    },

    funGetNetRec: function(){
        var obj = new Object();
        var self = this;
        $.ajax({
            url:'http://121.42.179.100:8080/queryService/webapi/page/recommend/',
            data:{content:self.searchName,category:self.category,sitePrefix:mw.config.get('wgHuijiPrefix')},
            success: function (data) {
                var len = self.item,dataLen = data.length,dataArr = new Array();
                if (data.length == 0) return;

                //获得要删除数组的下标
                for(var m = 0; m<dataLen; m++){
                    var num = self.funCheckReapet(data, m);
                    if(num) dataArr.push(m-dataArr.length); //数组元素减去此时数组长度是为了在splice后，下一次splice不会删错
                }
                //得到新的data数组
                for(var x = 0; x<dataArr.length; x++){
                    data.splice(dataArr[x],1);
                }
                self.netData = data;

                //根据推荐词条的站点进行分组
                for(var n=len;n<10;n++) {

                    if (obj[data[n-len].sitePrefix] == null) {
                        obj[data[n-len].sitePrefix] = new Array();
                        obj[data[n-len].sitePrefix].value = data[n-len].siteName;
                    }
                        obj[data[n-len].sitePrefix].push(data[n-len].title)
                }
                for(var i in obj) { //每个站点发一个合并请求
                    var title = '';
                    obj[i].forEach(function (item) {
                        title += item + '|';
                    });
                    title = title.substring(0, title.length - 1);
                    self.funGetDefaultImg(obj,title,i);
                }
            },
            type: 'post'
        });
    },

    funCheckReapet: function(data , n){

        var siteName = data[n].siteName, title = data[n].title, self = this, num;
        this.pageRec.forEach(function(item,index){
            if(index<self.defaultEntryShowLength && item.siteName == siteName && item.title == title){
                num = n;
            }
        });

        return num;

    },

    funGetDefaultImg: function(obj,title,i){
        var address = 'http://' + i + '.huiji.wiki';
        var self = this;
        $.ajax({
            url: 'http://' + i + '.huiji.wiki/api.php?action=query&prop=pageimages&pilimit=max&format=json&pithumbsize=250&maxage=2592000&smaxage=2592000&titles=' + title,
            type: 'get',
            success: function (data) {

                for (var x in data.query.pages) {
                    if (data.query.pages[x].thumbnail) {
                        var percent = data.query.pages[x].thumbnail.width / data.query.pages[x].thumbnail.height;
                        var content = '<div id="' + self.item + '" class="recommend-item lazy-loading"><a href="' + address + '/wiki/' + data.query.pages[x].title + '"><img data-src="' + data.query.pages[x].thumbnail.source + '"></a>' +
                            '<div class="recommend-title">' +
                            '<a href="http://' + i + '.huiji.wiki/wiki/' + data.query.pages[x].title + '" title="' + data.query.pages[x].title + '">' + data.query.pages[x].title + '</a>' +
                            '<a href="' + address + '">' + obj[i].value + '</a></div></div>';

                        $('.recommend').append(content);
                        self.funClipImg(percent, self.item);

                    } else {
                        var content = '<div id="' + self.item + '" class="recommend-item lazy-loading"><a href="' + address + '/wiki/' + data.query.pages[x].title + '"><img data-src="http://cdn.huijiwiki.com/www/skins/bootstrap-mediawiki/img/recommend.png"></a>' +
                            '<div class="recommend-title">' +
                            '<a href="http://' + i + '.huiji.wiki/wiki/' + data.query.pages[x].title + '" title="' + data.query.pages[x].title + '">' + data.query.pages[x].title + '</a>' +
                            '<a href="' + address + '">' + obj[i].value + '</a></div></div>';
                        $('.recommend').append(content);
                    }
                    self.item++;
                }
                setTimeout(function () {
                    lazyLoad.autocheck();
                }, 100);
                if (document.body.clientWidth > 768&&self.item==10)
                    self.funDoOwl(address);
            }
        });
    },

    funGetPageImg: function(title,id,sitePrefix,address,ajax){
        var self = this;
        $.ajax({
            url:'http://'+sitePrefix+'.huiji.wiki/api.php?action=query&prop=pageimages&format=json&pithumbsize=250&maxage=2592000&smaxage=2592000&titles='+title,
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
                        $('#' + id).prepend('<a href="'+address+'"><img data-src="http://cdn.huijiwiki.com/www/skins/bootstrap-mediawiki/img/recommend.png"></a>');
                    }
                }
                if(ajax == false) {
                    if ($('.recommend-header').length == 0) {
                        $('.recommend').before('<h2 class="recommend-header">更多推荐</h2>');
                    }

                    //最后一次取图
                    if (id == self.item-1) {
                        if ($('.recommend .recommend-item').length == 0) $('.recommend').remove();
                    }
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
        //比例适中缩放
        else {
            $('#' + id).find('img').css('height', '200px');
            $('#' + id).removeClass('re-opacity');
        }
    },

    funDoOwl: function(address){
        var self = this;
        $("#recommend").owlCarousel({
            items: 5,
            beforeMove: function () {
                var sp = $('.owl-wrapper').css('transform').split(',');
                var len = self.pageRec.length>=self.defaultEntryShowLength?self.defaultEntryShowLength:self.pageRec.length;
                var data = self.netData;
                var n = self.item;
                sp = Math.abs(parseInt(sp[4]));

                if (n < 30) {
                    if (sp > ($('.owl-item').length - 5) * $('.owl-item').width()) {
                        setTimeout(function () {
                            var load = '<div class="recommend-load"></div>';
                            self.funAddItem(load, n);
                        }, 100);
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
                } else {
                    if (sp > ($('.owl-item').length - 5) * $('.owl-item').width()) {
                        setTimeout(function () {
                            for (var i = n; i < n + 2; i++) {
                                var img = '<div id="' + id + '" class="recommend-item"><a href="http://www.huiji.wiki/wiki/Special:SendHiddenGift?award=72"><img class="" src="/skins/bootstrap-mediawiki/img/found.jpg"></a><div class="recommend-title">' +
                                    '<a href="http://www.huiji.wiki/wiki/Special:SendHiddenGift?award=72" class="recommend-found-a">U FOUND ME</a><p class="recommend-found-p">cool cool cool!</p></div>';
                                self.funAddItem(img, n);
                                self.item++;
                            }
                        }, 100);


                    }
                }
            }
        });
    },

    funAddItem: function(img,n){
        $('#recommend').data('owlCarousel').addItem(img);
        $('#recommend').data('owlCarousel').jumpTo(n);
    },

    funGetImg2: function(title,id,sitePrefix, address, item, n, ajax){
        var self = this;
        $.ajax({
            url: 'http://' + sitePrefix + '.huiji.wiki/api.php?action=query&prop=pageimages&format=json&pithumbsize=250&maxage=2592000&smaxage=2592000&titles=' + title,
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
                        img = '<div id="' + id + '" class="recommend-item"><a href="'+address+'"><img src="http://cdn.huijiwiki.com/www/skins/bootstrap-mediawiki/img/recommend.png"></a><div class="recommend-title">' +
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
        while(p.offsetParent){x
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
var collapseMobileToc = {
    init: function(){
        // JavaScript Document
        if($("#toc").length && window.innerWidth <= 414){
            $("body").append("<button class=\"fa fa-bars\" id=\"menu-toggle-sm\" ></button>");
            $("#toc>#toctitle>.toctoggle").remove();
            $('#toc > ul').append('<li><a href="#firstHeading">回到顶部</a></li>');
            $("#menu-toggle-sm").click(function(e) {
                    $('#toc').addClass('open');
                    $('#content>div.row>article').addClass('open'); 
            });
            try{
                FastClick.attach(document.body);
            }catch(e){}
            $(document).mouseup(function (e) {
                var container = $('#toc');
                if ((!container.is(e.target) && $("#toc>#toctitle").has(e.target).length === 0) 
                    && 
                    (!($('#menu-toggle-sm').is(e.target)) && $('#menu-toggle-sm').has(e.target).length === 0)) {
                        $('#toc').removeClass("open");
                        $('#content>div.row>article').removeClass('open');
                        //$("#toc").hide(200);
                }
            });
            $(document).scroll(function(){
                if($(document).scrollTop() < 50){
                    $("#toc").css("padding-top","60px");
                    $("#menu-toggle-sm").removeClass('scroll');;
                }
                else{
                    $("#toc").css("padding-top","10px");
                    $("#menu-toggle-sm").addClass('scroll');
                };
            });
        }

    }
};

$(function(){
    recommend.init();
    lazyLoad.init();
    collapseMobileToc.init();
});
