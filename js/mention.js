/**
 * Created by huiji-001 on 2016/2/17.
 */
(function($) {
    $.fn.caret = function(pos) {
        var target = this[0]
//        console.log(target);
        if (!target) return;
        var isContentEditable = target.contentEditable === 'true';
        //get
        if (arguments.length == 0) {
            //HTML5
            if (window.getSelection) {
                //contenteditable
                if (isContentEditable) {
                    target.focus();
                    var range1 = window.getSelection().getRangeAt(0),
                        range2 = range1.cloneRange();
//                    console.log(window.getSelection());
//                    console.log(range1)
//                    console.log(range2)
                    range2.selectNodeContents(target);
                    range2.setEnd(range1.endContainer, range1.endOffset);
//                    console.log(range2.toString().length);
                    return range2.toString().length;
                }
                //textarea
                return target.selectionStart;
            }
            //IE<9
            if (document.selection) {
                target.focus();
                //contenteditable
                if (isContentEditable) {
                    var range1 = document.selection.createRange(),
                        range2 = document.body.createTextRange();
                    range2.moveToElementText(target);
                    range2.setEndPoint('EndToEnd', range1);
                    return range2.text.length;
                }
                //textarea
                var pos = 0,
                    range = target.createTextRange(),
                    range2 = document.selection.createRange().duplicate(),
                    bookmark = range2.getBookmark();
                range.moveToBookmark(bookmark);
                while (range.moveStart('character', -1) !== 0) pos++;
                return pos;
            }
            // Addition for jsdom support
            if (target.selectionStart)
                return target.selectionStart;
            //not supported
            return;
        }
        //set
        if (pos == -1)
            pos = this[isContentEditable? 'text' : 'val']().length;
        //HTML5
        if (window.getSelection) {
            //contenteditable
            if (isContentEditable) {
                target.focus();
                window.getSelection().collapse(target.firstChild, pos);
            }
            //textarea
            else
                target.setSelectionRange(pos, pos);
        }
        //IE<9
        else if (document.body.createTextRange) {
            if (isContentEditable) {
                var range = document.body.createTextRange();
                range.moveToElementText(target);
                range.moveStart('character', pos);
                range.collapse(true);
                range.select();
            } else {
                var range = target.createTextRange();
                range.move('character', pos);
                range.select();
            }
        }
        if (!isContentEditable)
            target.focus();
        return this;
    }
})(jQuery);

var mention = {
    follow: [],
    area: $('.mention-area').get(0),
    popover: $('.mention-popover').get(0),
    pos: '',
    active: false,

    //get user follow list
    getFollow: function(){
        if (mw.config.get('wgUserName') == ''){
            return false;
        }
        $.post(
            mw.util.wikiScript(), {
                action: 'ajax',
                rs: 'wfGetUserFollowing',
                rsargs: [mw.config.get('wgUserName')]
            },
            function( data ) {
                var res=JSON.parse(data);
                if(res.result) {
                    res.result.forEach(function (item) {
                        mention.follow.push(item.user_name);
                    });
                }
            }
        );
    },

    addModal: function(){
//        $('body').append('<div></div>>');
    },

    trigger: function(e){
        e.preventDefault();
        var self = this;
//        var getRangeIndex = function(selectionObject) {
//            var textarea = self.area;
//            console.log([textarea.selectionStart, textarea.selectionEnd]);
//            if (window.getSelection)
//                return [textarea.selectionStart, textarea.selectionEnd];
//            else { // 较老版本Safari!
//                var range               = document.selection.createRange();             //对选择的文字create Range
//                // var selectText          = range.text;                                //选中的文字
//                var selectTextLength    = range.text.length;                            //选中文字长度
//                textarea.select();                                                      //textarea全选
//                //StartToStart、StartToEnd、EndToStart、EndToEnd
//                range.setEndPoint("StartToStart", document.selection.createRange());    //指针移动到选中文字开始
//                var selectTextPosition  = range.text.length;                            //选中文字的结束位置
//                range.collapse(false);                                                  //将插入点移动到当前范围的开始
//                range.moveEnd("character", -selectTextLength);   //更改范围的结束位置，减去长度，字符开始位置，character不能改
//                range.moveEnd("character", selectTextLength);   //再更改范围的结束位置，到字符结束位置
//                range.select();                                                         //然后选中字符
//
//                //返回字符的开始和结束位置
//                return [selectTextPosition - selectTextLength, selectTextPosition];
//            }
//        }
//        var selection = window.getSelection();
//        console.log(selection);
//        var rangeIndex = getRangeIndex(selection);
//        console.log(rangeIndex);
        var caret = $('.emoji-wysiwyg-editor').caret();
        var content = $('.emoji-wysiwyg-editor').text().substring(caret-1,caret);
        if (content == '@') {
            this.addTag();
            this.getPos();
            this.addPopover(this.follow);
        }
    },

    addTag: function(){
        var start = $('.emoji-wysiwyg-editor').caret();
        var content;
        content = $('.mention-area.emoji-wysiwyg-editor').text().substring(0, start-1)+'<b>'+$('.mention-area.emoji-wysiwyg-editor').text().substring(start-1,start)+'</b>'+$('.mention-area.emoji-wysiwyg-editor').text().substring(start);

        //将数据模拟在val里
        $('.mention-area.emoji-wysiwyg-editor').val(content);
    },

    getPos: function(  ){

    },

    addPopover: function( follow ){
        var content = '';
//        for(var i=0;i<follow.length;i++){
//            content+='<li>'+follow[i]+'</li>'
//        }
        content = '<div class="mention-popover">' +
            '<div class="mention-edit">'+
            '<input class="mention-input">'+
            '</div>' +
            '<p class="mention-title">选择你想@的人</p>'+
            '<ul class="mention-list"></ul>'+
            '</div>';
        $('.mention-area.emoji-wysiwyg-editor').after(content);
        $('.mention-input').focus();
    },

    getUser: function(e){
        var self = this;
        $('body').on('keyup','.mention-input',function(e){
            var that = $(this).val().toLowerCase();
            $('.mention-list').empty().append('<li>'+$(this).val()+'</li>');
            if (that == '') return;
            self.follow.forEach(function(item){
                if(item.toLowerCase().indexOf(that)>=0&&$('.mention-list li').length<=4){
                    $('.mention-list').append('<li>'+item+'</li>')
                }
            });
        });
    },

    clickUser: function(e){
        var self = this;

        //使用mousedown防止和blur冲突
        $('body').on('mousedown','.mention-list li',function(e){
            console.log($('.mention-area.emoji-wysiwyg-editor').val());
            var content = $('.mention-area.emoji-wysiwyg-editor').val().replace('<b>@</b>','@'+$(this).text()+' ');
            console.log(content)
            $('.mention-area.emoji-wysiwyg-editor').val(content).text(content);
        });
    },

    popoverBlur: function(){
        $('body').on('blur','.mention-input',function(){
            var content = $('.mention-area.emoji-wysiwyg-editor').val().replace('<b>@</b>','@');
            $('.mention-area.emoji-wysiwyg-editor').val(content);
            if($('.mention-popover').length>0)
            $('.mention-popover').remove();
        });
    },

    savaPos: function(){
        var self = this;

        // 点击添加表情按钮时 记录光标位置
        $('body').on('blur','.emoji-wysiwyg-editor',function(e){
            var that = $(this);
            function checkBtn(){
                if(self.active){
                    that.focus();
                    self.active = false;
                }
            }
            setTimeout(checkBtn(),100);
        }).on('mousedown touchstart','.emoji-picker-icon',function(){
            self.active = true;
        }).on('mousedown touchstart','#custom_comment',function(e){
            e.stopPropagation();
            self.active = true;
            $('.custom-face').show(200);
        });

    },

    addEvent: function(){
        this.getUser();
        this.clickUser();
        this.popoverBlur();
        this.savaPos();
    },

    loadEmoji: function(){
        var self = this;
        mw.loader.using('skins.bootstrapmediawiki.emoji',function(){
            // Initializes and creates emoji set from sprite sheet
            window.emojiPicker = new EmojiPicker({
                emojiable_selector: '[data-emojiable=true]',
                assetsPath:'/skins/bootstrap-mediawiki/emoji-picker/lib/img/',
                popupButtonClasses: 'fa fa-smile-o'
            });
            // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
            // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
            // It can be called as many times as necessary; previously converted input fields will not be converted again
            window.emojiPicker.discover();
            $('.emoji-wysiwyg-editor').get(0).addEventListener("keyup", function(e) { self.trigger(e); }, false);
            self.addEvent();
        });
    },

    init: function(){
        var self = this;
        if(this.area){
            this.getFollow();
            this.addModal();
//
            this.loadEmoji();
        }
    }

};

$(function(){

    mention.init();

});