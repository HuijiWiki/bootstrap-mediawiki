var flowAdapter = {
	data: {
	},
	init: function(data){
		this.data = data;
	},
	adaptTopic: function(items, options){
		var postLimit = options.postLimit;
		if (postLimit > items['postNum']){
			postLimit = items['postNum'];
		}
		if (postLimit == 0){
			return '';
		}

		var html = '<li class="media talk-thread">'+
						'<div class="pull-left"><a href="#"><i class="media-object fa fa-comments fa-2x"></i></a></div>'+
						'<div class="media-body"><h4 class="media-heading">'+
							'<a href="'+items['titleLink']+'" class="talk-thread-title">'+
								items['title']+
							'</a>'+
						'</h4>'+
						'<div class="talk-total-replies">'+items['postNum']+'条信息</div>'+
						'<ul class="media-list talk-replies">';
		for (var l = 0; l < items.posts.length; l++){
			if ( l < items.posts.length - postLimit){
				continue;
			}
			html += 
							'<li class="media talk-reply">'+
								'<div class="pull-left"><a href="#"><i class="media-object fa fa-comment fa-2x"></i></a></div>'+
								'<div class="media-body"><div class="media-heading talk-user-name">'+
									'<a href="'+items.posts[l]['userLink']+'">'+items.posts[l]['userName']+'</a>'+' ('+items.posts[l]['date']+')'+
								'</div>'+
								'<div class="talk-message-body">'+
									items.posts[l]['content']+
								'</div></div>'+

							'</li>';
		}
		html += '		</ul>'+
					'</div></li>';
		return html;
	},
	adapt: function(items, options){
		var self = this;
		var topicLimit = options.topicLimit;
		var pagename = mw.config.get('wgTitle');
		if (topicLimit > items['topicNum']){
			topicLimit = items['topicNum'];
		}
		if (topicLimit == 0){
			return '';
		}
		var html = '<section id="DiscussionSnapshot" class="DiscussionSnapshot "><div class="panel panel-info"><div class="panel-heading">讨论'+pagename+'</div><div class="talk-content panel-body"><a class="pull-right mw-ui-button talk-new-post hidden-xs" href="'+items['newPostLink']+'" title="发表一个新话题"><i class="fa fa-comment-o"></i> 发表新话题</a><ul class="media-list talk-discussions">';
		for (var j = 0; j < items.topics.length; j++){
			html += self.adaptTopic(items.topics[j], options);
			topicLimit --;
			if (topicLimit == 0){
				break;
			}
		}
		html += '</ul><div class="talk-see-more">'+
			'<a class="mw-ui-button talk-new-post visible-xs-*" href="'+items['newPostLink']+'" title="发表一个新话题"><i class="fa fa-comment-o"></i> 发表新话题</a><a href="'+items['seeMoreLink']+'">查看更多讨论 &gt;</a>'+
		'</div></div></div></section>';
		return html;
	},
	convert: function(){
		var self = this;
		var items = {};
		var rootRev = '';
		items.topicNum = self.data.flow["view-topiclist"].result.topiclist.roots.length;
		items.newPostLink = self.data.flow["view-topiclist"].result.topiclist.actions.newtopic.url;
		var pagename = mw.config.get('wgTitle').replace(' ', '_');
	    var namespace = mw.config.get('wgCanonicalNamespace').replace(' ', '_');
	    if (namespace != ''){
	    	var talkpage = namespace+'_talk:'+pagename;
	    } else {
	    	var talkpage = 'Talk:'+pagename;
	    }
		items.seeMoreLink = '/wiki/'+talkpage;
		items.topics = [];
		for (var i = 0; i < self.data.flow["view-topiclist"].result.topiclist.roots.length; i++){
			var root = self.data.flow["view-topiclist"].result.topiclist.roots[i];
			rootRev = self.data.flow["view-topiclist"].result.topiclist.posts[root][0];
			items.topics.push(self.convertTopic(rootRev));
		}
		console.log(items);
		return items;
	},
	convertTopic: function(rev){
		var self = this;
		var topic = {};
		topic.postNum = self.data.flow["view-topiclist"].result.topiclist.revisions[rev].reply_count;
		topic.title = self.data.flow["view-topiclist"].result.topiclist.revisions[rev].content.content;
		topic.titleLink = '/wiki/Topic:'+self.data.flow["view-topiclist"].result.topiclist.revisions[rev].workflowId;
		topic.posts = [];
		for (var k = 0; k < self.data.flow["view-topiclist"].result.topiclist.revisions[rev].replies.length; k++){
			var reply = self.data.flow["view-topiclist"].result.topiclist.revisions[rev].replies[k];
			var mReply = self.data.flow["view-topiclist"].result.topiclist.revisions[reply];
			if (!mReply){
				continue;
			} 
			var post = {
				userLink: mReply.author.links.userpage.url,
				userName: mReply.author.name,
				content: mReply.content.content,
				date: mReply.timestamp_readable
			};
			topic.posts.push(post);
		}
		return topic;

	}

}
