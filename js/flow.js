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

		var html = '<li class="talk-thread">'+
						'<img class="sprite talk-two" src="data:image/gif;base64,R0lGODlhAQABAIABAAAAAP///yH5BAEAAAEALAAAAAABAAEAQAICTAEAOw%3D%3D">'+
						'<h4>'+
							'<a href="'+items['titleLink']+'" class="talk-thread-title">'+
								items['title']+
							'</a>'+
						'</h4>'+
						'<div class="talk-total-replies">'+items['postNum']+'条信息</div>'+
						'<ul class="talk-replies">';
		for (var l = 0; l < items.posts.length; l++){
			if ( l < items.posts.length - postLimit){
				continue;
			}
			html += 
							'<li class="talk-reply">'+
								'<img class="talk-user-avatar" src="http://vignette4.wikia.nocookie.net/common/avatars/a/a7/25462110.png/revision/latest/scale-to-width/50?cb=1412307978&amp;format=jpg">'+
								'<div class="talk-user-name">'+
									'<a href="'+items.posts[l]['userLink']+'">'+items.posts[l]['userName']+'</a>'+
								'</div>'+
								'<div class="talk-message-body">'+
									items.posts[l]['content']+		'<time title="'+items.posts[l]['date']+'" class="talk-timestamp timeago" datetime="'+items.posts[l]['date']+'">'+items.posts[l]['date']+'</time>'+
								'</div>'+

							'</li>';
		}
		html += '		</ul>'+
					'</li>';
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
		var html = '<section id="DiscussionSnapshot" class="DiscussionSnapshot"><h2><span class="mw-headline" id="h2-discuss">讨论'+pagename+'</span><a class="mw-ui-button mw-ui-constructive talk-new-post " href="'+items['newPostLink']+'" title="发表一个新话题">发表新话题</a></h2><div class="talk-content"><ul class="talk-discussions">';
		for (var j = 0; j < items.topics.length; j++){
			html += self.adaptTopic(items.topics[j], options);
			topicLimit --;
			if (topicLimit == 0){
				break;
			}
		}
		html += '</ul><div class="talk-see-more">'+
			'<a href="'+items['seeMoreLink']+'">查看更多讨论 &gt;</a>'+
		'</div></div></section>';
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
