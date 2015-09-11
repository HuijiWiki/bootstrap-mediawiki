function insertRecordIntoDB(url,navigatorInfo,fromSource,userId,userName,wikiSite,siteName,titleName,articleId) {
        jQuery.post(
                url,
                {
                        navigatorInfo:navigatorInfo,
                        fromSource:clearSourceUrl(fromSource),
                        userId:userId,
                        userName:userName,
                        articleId:articleId,
                        titleName:titleName,
                        siteName:siteName,
                        wikiSite:wikiSite,
                 }
        )
}

function clearSourceUrl(sourceUrl){
        var e = new RegExp('^(?:(?:https?|ftp):)/*(?:[^@]+@)?([^:/#]+)'),
        matches = e.exec(sourceUrl);
        return matches ? matches[1]:sourceUrl;
}



function getViewRecordsFromUserIdGroupByWikiSite(userId,fromTime,toTime,callback){
	var url = 'http://test.huiji.wiki:50007/getViewRecordsFromUserIdGroupByWikiSite/';
	jQuery.post(
		url,
		{
			userId:userId,
			fromTime:fromTime,
			toTime:toTime,
		},
		function(data){
		//	console.log(data);
			if(callback != null) {
				callback(data);
			}else{
				return data;
			}				
		}
	).error(function(){
		//console.log("error");
		var errInfo = {'status':'fail'};
		if(callback != null){
			 callback(errInfo);
		}else{
			return errInfo;
		}	
	});	
}

function getEditRecordsFromUserIdGroupByWikiSite(userId,fromTime,toTime,callback){
	var url = 'http://test.huiji.wiki:50007/getEditRecordsFromUserIdGroupByWikiSite/';
	jQuery.post(
		url,
		{
			userId:userId,
			fromTime:fromTime,
			toTime:toTime,
		},
		function(data){
		//	console.log(data);
			if(callback != null){	
				 callback(data);
			}else{
				return data;
			}		
		}
	).error(function(){
		//console.log("error");
		var errInfo = {'status':'fail'};
		if(callback != null) {
			callback(errInfo);
		}else{
			return errInfo;
		}
	});	
}

function getEditorCountGroupByWikiSite(fromTime,toTime,callback){
	var url = 'http://test.huiji.wiki:50007/getEditorCountGroupByWikiSite/';
	jQuery.post(
		url,
		{
			fromTime:fromTime,
			toTime:toTime,
		},
		function(data){
		//	console.log(data);
			if(callback != null) {
				callback(data);
			}else{
				return data;
			}				
		}
	).error(function(){
		//console.log("error");
		var result = {'status':'fail'};
		if(callback != null) {
			callback(result);
		}else{
			return result;
		}
	});	
}

function getEditRecordsOnWikiSiteFromUserIdGroupByDay(userId,wikiSite,fromTime,toTime,callback)
{
	var url = 'http://test.huiji.wiki:50007/getEditRecordsOnWikiSiteFromUserIdGroupByDay/';
	jQuery.post(
		url,
		{
			userId:userId,
			wikiSite:wikiSite,
			fromTime:fromTime,
			toTime:toTime,
		},
		function(data){
		//	console.log(data);
			if(callback != null) {
				callback(data);
			}else{
				return data;
			}				
		}
	).error(function(){
		//console.log("error");
		var result = {'status':'fail'};
		if(callback != null){
			 callback();
		}else{
			return result;
		}
	});	

} 



function getViewRecordsOnWikiSiteFromUserIdGroupByDay(userId,wikiSite,fromTime,toTime,callback)
{
	var url = 'http://test.huiji.wiki:50007/getViewRecordsOnWikiSiteFromUserIdGroupByDay/';
	jQuery.post(
		url,
		{
			userId:userId,
			wikiSite:wikiSite,
			fromTime:fromTime,
			toTime:toTime,
		},
		function(data){
//			console.log(data);
			if(callback != null) {
				callback(data);
			}else{
				return data;
			}				
		}
	).error(function(){
		//console.log("error");
		var result = {'status':'fail'};
		if(callback != null){
			 callback();
		}else{
			return result;
		}
	});	

}


