/**
 * OOjs layout of Common Style
 */
function inspect_Color(strColor)
{
    var oSpan = document.createElement('span');
    oSpan.setAttribute('style','color:'+strColor);
    if(oSpan.style.color != ""||strColor == "false")
    {
        return true;
    }
    else
    {
        return false;
    }
    oSpan = null;
}

function display_Check(strColor)
{
    if(inspect_Color(strColor)){
        return true;
    }else
    {
        mw.notification.notify("请输入正确的颜色",{tag:"color"})
        return false;
    }

}

var palette = {

	init: function(){
		// load dummy page
		// var data = {
		// 	hasStar: false
		// };
		// if (mw.loader.getState('ext.voteNY.styles')){
		// 	data.hasStar = true;
		// }
		// var p = mw.template.get( 'ext.socialprofile.commonstyle.js', 'dummypage.mustache' );
		// var html = p.render(data);
		// $( '#content' ).html( html );
		var api = new mw.Api();

		// api request
		this.promise = api.postWithToken('edit', {
			action : 'commonstyle',
			task : 'get'
		});	
		this.promise.done(this.render);



	
	} ,
	promise: null,
	cssContent: null,
	render: function(data){

		//console.log(data);
		try{
			this.cssContent = JSON.parse(data.commonstyle.cssContent);
		} catch(e){
			this.cssContent = [];
		}

		OO.inheritClass( palette.Page, OO.ui.PageLayout );
		palette.Page.prototype.historyUrl = data[1];
		palette.Page.prototype.historyCount = data[0];
		if (this.cssContent == null){
			this.cssContent = [];
		}		
		var data1 = {
			color:{
				mainBase: this.cssContent['@main-base']||"#333",
				bg: this.cssContent['@bg']||"#fff",
				bgInner: this.cssContent['@bg-inner']||"#fff",
				a: this.cssContent['@a']||"#428bca",
				subBg: this.cssContent['@sub-bg']||"#f6f8f8",
				well: this.cssContent['@well']||"#f5f5f5",
				subA: this.cssContent['@sub-a']||"#333",
				modal: this.cssContent['@modal']||"#222"		
			},
			outline:"快捷配色",
			template: "page1.mustache",
		}
		var page1 = new palette.Page( 'one', data1 );
		var data2 = {
			color:{
				default: this.cssContent['@brand-default']||"#fff",
				primary: this.cssContent['@brand-primary']||"#337ab7",
				success: this.cssContent['@brand-success']||"#5cb85c",
				info: this.cssContent['@brand-info']||"#5bc0de",
				warning: this.cssContent['@brand-warning']||"#f0ad4e",
				danger: this.cssContent['@brand-danger']||"#d9534f"				
			},
			outline:"语义配色",
			template: "page2.mustache",
		}	
		var page2 = new palette.Page( 'Two', data2 );
		var data3 = {
			color: {
				styleArr: [
					{
						ish: false,
						name: "文字",
						variable: "@detail-color",
						value: this.cssContent['@detail-color']||"false"
					},{
						ish: false,
						name: "辅助文字",
						variable: "@detail-secondary",
						value: this.cssContent['@detail-secondary']||"false"						
					},{
						ish: false,
						name: "副标题",
						variable: "@detail-contentsub",
						value: this.cssContent['@detail-contentsub']||"false"						
					},{
						ish: false,
						name: "已存在的链接“蓝链”",
						variable: "@detail-a",
						value: this.cssContent['@detail-a']||"false"						
					},{
						ish: false,
						name: "不存在的链接“红链”",
						variable: "@detail-new",
						value: this.cssContent['@detail-new']||"false"						
					},{
						ish: false,
						name: "边框和分割线",
						variable: "@detail-border",
						value: this.cssContent['@detail-a']||"false"						
					},{
						ish: false,
						name: "目录（未选中状态）",
						variable: "@detail-toc-a",
						value: this.cssContent['@detail-toc-a']	||"false"					
					},{
						ish: false,
						name: "目录（选中状态）",
						variable: "@detail-toc-a",
						value: this.cssContent['@detail-toc-a-hover']||"false"						
					},{
						ish: true,
						label: "标题",
						h: [{
								name2: "h1",
								variable2: "@detail-h1",
								value2: this.cssContent['@detail-h1']||"false"
							},
							{
								name2: "h2",
								variable2: "@detail-h2",
								value2: this.cssContent['@detail-h2']||"false"								
							},{
								name2: "h3",
								variable2: "@detail-h3",
								value2: this.cssContent['@detail-h3']||"false"									
							},{
								name2: "h4",
								variable2: "@detail-h4",
								value2: this.cssContent['@detail-h4']||"false"	
							},{
								name2: "h5",
								variable2: "@detail-h5",
								value2: this.cssContent['@detail-h5']||"false"	
							}
						]					
					},{
						ish: true,
						label: "表格（.wikitable）",
						h: [{
								name2: "背景",
								variable2: "@detail-wikitable-bg",
								value2: this.cssContent['@detail-wikitable-bg']||"false"
							},
							{
								name2: "文字",
								variable2: "@detail-wikitable-color",
								value2: this.cssContent['@detail-wikitable-color']||"false"								
							},{
								name2: "链接",
								variable2: "@detail-wikitable-a",
								value2: this.cssContent['@detail-wikitable-a']||"false"									
							},{
								name2: "边框",
								variable2: "@detail-wikitable-border",
								value2: this.cssContent['@detail-wikitable-border']||"false"	
							},{
								name2: "表格标题(th)背景",
								variable2: "@detail-wikitable-th-bg",
								value2: this.cssContent['@detail-wikitable-th-bg']||"false"	
							}
						]
					}

				]

			},
			template: "page3.mustache",
			outline: "内容细节配色"
			// var data2 = {
		}
		var page3 = new palette.Page( 'Three', data3 );
		var data4 = {
			color: {
				styleArr: [
					{
						ish: false,
						name: "外部背景（.wiki-outer-body）",
						variable: "@detail-bg",
						value: this.cssContent['@detail-bg']||"false"
					},{
						ish: false,
						name: "内部背景（.wiki-body）",
						variable: "@detail-inner-bg",
						value: this.cssContent['@detail-inner-bg']||"false"						
					},{
						ish: true,
						label: "导航",
						h: [{
								name2: "背景",
								variable2: "@detail-sub-bg",
								value2: this.cssContent['@detail-sub-bg']||"false"
							},
							{
								name2: "文字",
								variable2: "@detail-sub-a",
								value2: this.cssContent['@detail-sub-a']||"false"								
							},{
								name2: "选中状态",
								variable2: "@detail-sub-a-hover-bg",
								value2: this.cssContent['@detail-sub-a-hover-bg']||"false"									
							},{
								name2: "统计数字",
								variable2: "@detail-sub-site-count",
								value2: this.cssContent['@detail-sub-site-count']||"false"	
							}
						]					
					},{
						ish: true,
						label: "页面底部",
						h: [{
								name2: "背景",
								variable2: "@detail-bottom-bg",
								value2: this.cssContent['@detail-bottom-bg']||"false"
							},
							{
								name2: "文字",
								variable2: "@detail-bottom-color",
								value2: this.cssContent['@detail-bottom-color']	||"false"							
							}
						]
					}
				]

			},
			template: "page4.mustache",
			outline: "区域细节配色"
			// var data2 = {
		},
		data5 = {
			color: {
				styleArr: [
					{
						ish: true,
						label: "quote",
						h: [{
								name2: "背景",
								variable2: "@detail-quote-bg",
								value2: this.cssContent['@detail-quote-bg']||"false"
							},
							{
								name2: "文字",
								variable2: "@detail-quote-color",
								value2: this.cssContent['@detail-quote-color']||"false"								
							},{
								name2: "链接",
								variable2: "@detail-quote-a",
								value2: this.cssContent['@detail-quote-a']||"false"									
							}
						]					
					},{
						ish: true,
						label: "infobox整体",
						h: [{
								name2: "背景",
								variable2: "@detail-infobox-bg",
								value2: this.cssContent['@detail-infobox-bg']||"false"
							},
							{
								name2: "文字",
								variable2: "@detail-infobox-color",
								value2: this.cssContent['@detail-infobox-color']||"false"								
							},
							{
								name2: "链接",
								variable2: "@detail-infobox-a",
								value2: this.cssContent['@detail-infobox-a']||"false"								
							},
							{
								name2: "边框",
								variable2: "@detail-infobox-border",
								value2: this.cssContent['@detail-infobox-border']||"false"								
							}
						]
					}, {
						ish: true,
						label: "infobox title",
						h: [{
								name2: "背景",
								variable2: "@detail-infobox-title-bg",
								value2: this.cssContent['@detail-infobox-title-bg']||"false"							
							},
							{
								name2: "文字",
								variable2: "@detail-infobox-title-color",
								value2: this.cssContent['@detail-infobox-title-color']||"false"
							},{
								name2: "链接",
								variable2: "@detail-infobox-title-a",
								value2: this.cssContent['@detail-infobox-title-a']||"false"
							}
						]
					}, {
						ish: true,
						label: "infobox header",
						h: [{
								name2: "背景",
								variable2: "@detail-infobox-item-title-bg",
								value2: this.cssContent['@detail-infobox-item-title-bg']||"false"							
							},
							{
								name2: "文字",
								variable2: "@detail-infobox-item-title-color",
								value2: this.cssContent['@detail-infobox-item-title-color']||"false"
							},{
								name2: "链接",
								variable2: "@detail-infobox-header-a",
								value2: this.cssContent['@detail-infobox-header-a']||"false"
							}
						]
					}, {
						ish: true,
						label: "infobox label",
						h: [{
								name2: "背景",
								variable2: "@detail-infobox-item-label-bg",
								value2: this.cssContent['@detail-infobox-item-label-bg']||"false"							
							},
							{
								name2: "文字",
								variable2: "@detail-infobox-item-label-color",
								value2: this.cssContent['@detail-infobox-item-label-color']||"false"
							},{
								name2: "链接",
								variable2: "@detail-infobox-item-label-a",
								value2: this.cssContent['@detail-infobox-item-label-a']	||"false"						
							},
							{
								name2: "边框",
								variable2: "@detail-infobox-item-label-border",
								value2: this.cssContent['@detail-infobox-item-label-border']||"false"
							}
						]


					}, {
						ish: true,
						label: "infobox data",
						h: [{
								name2: "背景",
								variable2: "@detail-infobox-item-detail-bg",
								value2: this.cssContent['@detail-infobox-item-detail-bg']||"false"							
							},
							{
								name2: "文字",
								variable2: "@detail-infobox-item-detail-color",
								value2: this.cssContent['@detail-infobox-item-detail-color']||"false"
							},{
								name2: "链接",
								variable2: "@detail-infobox-item-detail-a",
								value2: this.cssContent['@detail-infobox-item-detail-a']||"false"							
							},
							{
								name2: "边框",
								variable2: "@detail-infobox-item-detail-border",
								value2: this.cssContent['@detail-infobox-item-detail-border']||"false"
							}
						]
					},{
						ish: true,
						label: "navbox整体",
						h: [{
								name2: "背景",
								variable2: "@detail-navbox-bg",
								value2: this.cssContent['@detail-navbox-bg']||"false"
							},
							{
								name2: "文字",
								variable2: "@detail-navbox-color",
								value2: this.cssContent['@detail-navbox-color']	||"false"							
							},
							{
								name2: "链接",
								variable2: "@detail-navbox-a",
								value2: this.cssContent['@detail-navbox-a']	||"false"						
							}
						]
					},{
						ish: true,
						label: "navbox title",
						h: [{
								name2: "背景",
								variable2: "@detail-navbox-title-bg",
								value2: this.cssContent['@detail-navbox-title-bg']||"false"							
							},
							{
								name2: "文字",
								variable2: "@detail-navbox-title-color",
								value2: this.cssContent['@detail-navbox-title-color']||"false"
							},
							{
								name2: "链接",
								variable2: "@detail-navbox-title-a",
								value2: this.cssContent['@detail-navbox-title-a']||"false"
							},							
						]
					},{
						ish: true,
						label: "navbox group",
						h: [{
								name2: "背景",
								variable2: "@detail-navbox-group-bg",
								value2: this.cssContent['@detail-navbox-group-bg']||"false"							
							},
							{
								name2: "文字",
								variable2: "@detail-navbox-group-color",
								value2: this.cssContent['@detail-navbox-group-color']||"false"
							},
							{
								name2: "链接",
								variable2: "@detail-navbox-group-a",
								value2: this.cssContent['@detail-navbox-group-a']||"false"								
							}
						]
					}, {
						ish: true,
						label: "navbox list",
						h: [{
								name2: "背景",
								variable2: "@detail-navbox-list-bg",
								value2: this.cssContent['@detail-navbox-list-bg']||"false"							
							},
							{
								name2: "文字",
								variable2: "@detail-navbox-list-color",
								value2: this.cssContent['@detail-navbox-list-color']||"false"
							},{
								name2: "已存在的链接“蓝链”",
								variable2: "@detail-navbox-list-a",
								value2: this.cssContent['@detail-navbox-list-a']	||"false"						
							},
							{
								name2: "不存在的链接“红链”",
								variable2: "@detail-navbox-list-new",
								value2: this.cssContent['@detail-navbox-list-new']||"false"
							},{					
								name2: "奇数背景",
								variable2: "@detail-navbox-list-odd-bg",
								value2: this.cssContent['@detail-navbox-list-odd-bg']||"false"
							},{					
								name2: "偶数背景",
								variable2: "@detail-navbox-list-even-bg",
								value2: this.cssContent['@detail-navbox-list-even-bg']||"false"
							}
						]						
					}, {
						ish: true,
						label: "navbox above below",
						h: [{
								name2: "背景",
								variable2: "@detail-navbox-abovebelow-bg",
								value2: this.cssContent['@detail-navbox-abovebelow-bg']	||"false"						
							},
							{
								name2: "文字",
								variable2: "@detail-navbox-abovebelow-color",
								value2: this.cssContent['@detail-navbox-abovebelow-color']||"false"
							},{
								name2: "链接",
								variable2: "@detail-navbox-abovebelow-a",
								value2: this.cssContent['@detail-navbox-abovebelow-a']||"false"							
							}
						]						
					}
				]

			},
			template: "page5.mustache",
			outline: "模板细节配色"
			// var data2 = {
		},
		data6 = {
			color:null,
			template: "page6.mustache",
			outline: "代码编辑"
		};
		if (mw.loader.getState('ext.voteNY.styles')){
			data4.color.styleArr.push({
						ish: true,
						label: "评分",
						h: [{
								name2: "文字",
								variable2: "@detail-vote-color",
								value2: this.cssContent['@detail-vote-color']||"false"							
							},
							{
								name2: "分数背景",
								variable2: "@detail-vote-score-bg",
								value2: this.cssContent['@detail-vote-score-bg']||"false"
							},
							{
								name2: "分数字体",
								variable2: "@detail-vote-score-color",
								value2: this.cssContent['@detail-vote-score-color']||"false"
							},
							{
								name2: "星星颜色",
								variable2: "@detail-vote-star",
								value2: this.cssContent['@detail-vote-star']||"false"							
							},
							{
								name2: "选中颜色",
								variable2: "@detail-vote-active-star",
								value2: this.cssContent['@detail-vote-active-star']||"false"
							}
						]
					});
		}
		var page4 = new palette.Page( 'Four', data4 );
		var page5 = new palette.Page( 'Five', data5 );
		var page6 = new palette.Page( 'Six', data6 );
		// }
		if (window.innerWidth >= 768){
			var booklet = new OO.ui.BookletLayout( {
			    outlined: true,

			} );			
		} else {
			var booklet = new OO.ui.BookletLayout( {
			    outlined: false,
			    continuous: true
			} );			
		}
		booklet.addPages ( [ page1, page2, page3, page4, page5, page6 ] );
		$( '#color-container' ).html( booklet.$element );
		
		// jquery stuff
	    var file;
	    var obj = new Object();
	//    $('.picker-color').click(function(e){
	////        e.preventDefault();
	//        e.stopPropagation();
	//    })
	    $.fn.uniqueScroll = function () {
	        $(this).on('mousewheel', _pc)
	            .on('DOMMouseScroll', _pc);

	        function _pc(e) {
	            var scrollTop = $(this)[0].scrollTop,
	                scrollHeight = $(this)[0].scrollHeight,
	                height = $(this)[0].clientHeight;

	            var delta = (e.originalEvent.wheelDelta) ? e.originalEvent.wheelDelta : -(e.originalEvent.detail || 0);

	            if ((delta > 0 && scrollTop <= delta) || (delta < 0 && scrollHeight - height - scrollTop <= -1 * delta)) {
	                this.scrollTop = delta > 0 ? 0 : scrollHeight;
	                e.stopPropagation();
	                e.preventDefault();
	            }
	        }

	        $(this).on('touchstart', function (e) {
	            var targetTouches = e.targetTouches ? e.targetTouches : e.originalEvent.targetTouches;
	            $(this)[0].tmPoint = {x: targetTouches[0].pageX, y: targetTouches[0].pageY};
	        });
	        $(this).on('touchmove', _mobile);
	        $(this).on('touchend', function (e) {
	            $(this)[0].tmPoint = null;
	        });
	        $(this).on('touchcancel', function (e) {
	            $(this)[0].tmPoint = null;
	        });

	        function _mobile(e) {

	            if ($(this)[0].tmPoint == null) {
	                return;
	            }

	            var targetTouches = e.targetTouches ? e.targetTouches : e.originalEvent.targetTouches;
	            var scrollTop = $(this)[0].scrollTop,
	                scrollHeight = $(this)[0].scrollHeight,
	                height = $(this)[0].clientHeight;

	            var point = {x: targetTouches[0].pageX, y: targetTouches[0].pageY};
	            var de = $(this)[0].tmPoint.y - point.y;
	            if (de < 0 && scrollTop <= 0) {
	                e.stopPropagation();
	                e.preventDefault();
	            }

	            if (de > 0 && scrollTop + height >= scrollHeight) {
	                e.stopPropagation();
	                e.preventDefault();
	            }
	        }
	    };
		$('.picker-color .color-box').each(function(){
        	var variable = $(this).attr('data-variable');
        	var color =  $(this).attr('value');
        	obj[variable] = color;
	    });
	    $('.jcolor').each(function(){
	        if($(this).attr('value')=='false'){
	            $(this).css('color','#000');
	        }
	        $(this).colorpicker({
	            labels: true,
	            displayColorSpace: 'hsla',
	            displayColor: 'css'
	        })
	    });
	    booklet.$element.on('newcolor','.jcolor',function(e,colorpicker){
	        var variable = $(this).attr('data-variable');
	        var color = colorpicker.toCssString();
	        $(this).siblings('.input-color').val(color);
	        obj[variable] = color;
	        less.modifyVars(obj);
	    });	
	    $('.picker-img .color-box').click(function(){
        	$(this).siblings('.file-btn').trigger('click');
    	});
    	$('.picker-img .file-btn').change(function(e){
        var reader = new FileReader();
	        var self = $(this);
	        var file;
	        var selector = $(this).attr('data-selector');
	        file = e.target.files[0];
	        reader.readAsDataURL(file);
	        reader.onload=function(e) {
           		var src = this.result;
            	self.siblings('.color-box').css('background-image','url("'+src+'")');
            	$(selector).css({'background-image':'url("'+src+'")','background-size':'100%'});
        	}
       	}); 
	    $('.input-color').blur(function(){
	        var val = $(this).val();
	        var variable = $(this).siblings('.color-box').attr('data-variable');
	        if(!display_Check(val)) return;
	        $(this).siblings('.color-box').colorpicker().destroy();
	        $(this).siblings('.color-box').css("color",val).colorpicker();
	        obj[variable] = val;
	        less.modifyVars(obj);
	    });   
	    $('.color-picker').uniqueScroll();
	    $('.commonstyle-reset').click(function(){
	        var that = this;
	        $(this).attr('disabled','');
	        var reset = [];
	        var api = new mw.Api();
	        api.postWithToken('edit', {
	            action: 'commonstyle',
	            task: 'reset',
	            format: 'json'
	        }).done( function(data){
	            $(that).removeAttr('disabled');
	            var res = data;
	            if (res.commonstyle.res.success == 'true'){
	                mw.notification.notify('设置成功');
	                location.reload();                
	            } else {
	                mw.notification.notify('请使用调试模式刷新页面重试',{tag:'error'});
	            }
	        } );
	        // $.ajax({
	        //     url:mw.util.wikiScript(),
	        //     data:{
	        //         action: 'ajax',
	        //         rs: 'wfUpdateCssStyle',
	        //         rsargs: ['','HuijiColor1',1]
	        //     },
	        //     type: 'post',
	        //     format: 'json',
	        //     success: function(data){
	        //         $(that).removeAttr('disabled');

	        //         var res = JSON.parse(data);
	        //         if(res.result == 'true'){
	        //             mw.notification.notify('设置成功');
	        //             location.reload();
	        //         }else{
	        //             mw.notification.notify('请使用调试模式刷新页面重试',{tag:'error'});
	        //         }
	        //     }
	        // });
	    });
	    $('.commonstyle-submit').click(function(){
	        var that = this;
	        $(this).attr('disabled','');
	        var state = $('.is-new').val();
	        var api = new mw.Api();
	        console.log(obj);
	        api.postWithToken('edit', {
	            action: "commonstyle",
	            task: "save",
	            content: JSON.stringify(obj)
	        }).done(function(data){
	            $(that).removeAttr('disabled');
	            var res = data;
	            if(res.commonstyle.res.success == 'true'){
	                mw.notification.notify('设置成功');
	                location.reload();
	            }else{
	                mw.notification.notify('请请使用调试模式刷新页面重试',{tag:'error'})
	            }            
	        });

	        // $.ajax({
	        //     url:mw.util.wikiScript(),
	        //     data:{
	        //         action: 'ajax',
	        //         rs: 'wfUpdateCssStyle',
	        //         rsargs: [obj,'HuijiColor1',state]
	        //     },
	        //     type: 'post',
	        //     format: 'json',
	        //     success: function(data){
	        //         $(that).removeAttr('disabled');

	        //         var res = JSON.parse(data)
	        //         if(res.result == 'true'){
	        //             mw.notification.notify('设置成功');
	        //             location.reload();
	        //         }else{
	        //             mw.notification.notify('请请使用调试模式刷新页面重试',{tag:'error'})
	        //         }
	        //     }
	        // });
	    });
	    $('.picker-label li').click(function(){
	        var index = $(this).index();
	        $('.picker-label li').removeClass('active');
	        $(this).addClass('active');
	        $('.picker-detail li').removeClass('active');
	        $(this).parents('li').find('.picker-detail li').eq(index).addClass('active');
	    });

	    var content = '<link rel="stylesheet/less" href="/wiki/special:DynamicLess"><script type="text/javascript">' +
	        'less = {env: "development",async: false,fileAsync: false,poll: 1000,functions: {},dumpLineNumbers: "comments", relativeUrls: true, rootpath: ":/a.com/" };</script>' +
	        '<script src="http://fs.huijiwiki.com/www/resources/assets/less.min.js" type="text/javascript"></script>';
	    $('head').append(content);

	    $('.color-picker-item-toggle').click(function(){
	        $(this).siblings('.picker-color').toggle();
	    });	        

	},
	Page: function( name, config ){
		palette.Page.parent.call( this, name, config );
		var p = mw.template.get( 'ext.socialprofile.commonstyle.js', config.template );
		$html = p.render(config.color);
		this.$element.append( $html );
		this.outlineTitle = config.outline;
		palette.Page.prototype.setupOutlineItem = function(){
			this.outlineItem.setLabel( this.outlineTitle );
		}
		if (config.color){
			this.$element.append('<div class="color-picker-bottom darken"><button class="btn btn-primary commonstyle-submit">保存</button><button class="btn btn-danger commonstyle-reset">重置</button><a href="'+this.historyUrl+'" class="btn btn-default">历史 <span class="badge">'+this.historyCount+'</span></a></div>');
		}
	}
}
$(function(){

	console.log('go');
	palette.init();
	// function PageOneLayout( name, config ) {
	//     PageOneLayout.parent.call( this, name, config );
	//    	var p1 = mw.template.get( 'ext.socialprofile.commonstyle.js', 'page1.mustache' );
	//     var data = {};
	//     $html = p1.render(data);
	//     this.$element.append( $html );
	// }
	// OO.inheritClass( PageOneLayout, OO.ui.PageLayout );
	// PageOneLayout.prototype.setupOutlineItem = function () {
	//     this.outlineItem.setLabel( '主题介绍' );
	// }

	// function PageTwoLayout( name, config ) {
	//     PageTwoLayout.parent.call( this, name, config );
	//     this.$element.append( '<p>Second page</p>' );
	// }
	// OO.inheritClass( PageTwoLayout, OO.ui.PageLayout );
	// function PageThreeLayout( name, config ) {
	//     PageThreeLayout.parent.call( this, name, config );
	//     this.$element.append( '<p>Second page</p>' );
	// }
	// OO.inheritClass( PageThreeLayout, OO.ui.PageLayout );
	// function PageFourLayout( name, config ) {
	//     PageFourLayout.parent.call( this, name, config );
	//     this.$element.append( '<p>Second page</p>' );
	// }
	// OO.inheritClass( PageFourLayout, OO.ui.PageLayout );
	// function PageFiveLayout( name, config ) {
	//     PageFiveLayout.parent.call( this, name, config );
	//     this.$element.append( '<p>Second page</p>' );
	// }
	// OO.inheritClass( PageFiveLayout, OO.ui.PageLayout );
	// function PageSixLayout( name, config ) {
	//     PageSixLayout.parent.call( this, name, config );
	//     this.$element.append( '<p>Second page</p>' );
	// }
	// OO.inheritClass( PageSixLayout, OO.ui.PageLayout );


	// PageTwoLayout.prototype.setupOutlineItem = function () {
	//     this.outlineItem.setLabel( 'Page Two' );
	// }

	// var page1 = new PageOneLayout( 'one' ),
	//     page2 = new PageTwoLayout( 'two' );
	//     page3 = new PageThreeLayout( 'three' );
	//     page4 = new PageFourLayout( 'four' );
	//     page5 = new PageFiveLayout( 'five' );
	//     page6 = new PageSixLayout( 'six' );

	// var booklet = new OO.ui.BookletLayout( {
	//     outlined: true,
	// } );

	// booklet.addPages ( [ page1, page2, page3, page4, page5, page6 ] );
	// $( '#mw-content-text' ).html( booklet.$element );

});
