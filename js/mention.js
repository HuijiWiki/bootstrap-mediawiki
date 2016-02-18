/**
 * Created by huiji-001 on 2016/2/17.
 */

var mention = {
    follow: [],
    area: $('.mention-area').get(0),
    popover: $('.mention-popover').get(0),

    //get user follow list
    getFollow: function(){
        $.post(
            mw.util.wikiScript(), {
                action: 'ajax',
                rs: 'wfGetUserFollowing',
                rsargs: [mw.config.get('wgUserName')]
            },
            function( data ) {
                var res=JSON.parse(data);
                res.result.forEach(function(item){
                    mention.follow.push(item.user_name);
                });
            }
        );
    },

    trigger: function(e){
        e.preventDefault();
        if (e.shiftKey && e.keyCode == 50) {
            mention.getPos();
            this.addPopover(this.follow);
        }
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
        $('.mention-area').after(content);
        $('.mention-input').focus();
    },

    getUser: function(e){
        var self = this;
        $('body').on('keyup','.mention-input',function(e){
            var that = $(this).val().toLowerCase();
            $('.mention-list').empty();
            if (that == '') return;
            self.follow.forEach(function(item){
                if(item.toLowerCase().indexOf(that)>=0){
                    $('.mention-list').append('<li>'+item+'</li>')
                }
            });
        });
    },

    addEvent: function(){
        this.getUser();
    },

    init: function(){
        var self = this;
        if(this.area){
            this.getFollow();
            this.area.addEventListener("keyup", function(e) { self.trigger(e); }, false);
            this.addEvent();
        }
    }

};

$(function(){

    mention.init();

});