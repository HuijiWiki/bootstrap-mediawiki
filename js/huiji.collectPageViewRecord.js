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

function getPageViewCountOnAllWikis(url,fromTimeStamp)
{
	jQuery.post(
		url,
		{
			fromTimeStamp:fromTimeStamp,
		},
		function(data){
			if(data.status == 'success'){
				//alert(data.count);
				return data.count;

			}else{
				alert("backend err");
			}	
		}
	).error(function(){alert("error")});	

}
	
function getPageViewCountOnWikisiteId(url,wikiSiteId,fromTimeStamp)
{




}

function getActiveUsersCountOnAllWikis(url,fromTimeStamp)
{
	
	jQuery.post(
		url,
		{
			fromTimeStamp:fromTimeStamp,
		},
		function(data){
			if(data.status == 'success'){
				alert(data.count);
				return data.count;

			}else{
				alert("backend err");
			}	
		}
	).error(function(){alert("error")});	
}


function getEditRecordsFromUserId(url,userId,fromTimeStamp)
{
	jQuery.post(
		url,
		{
			userId:userId,
			fromTimeStamp:fromTimeStamp,

		},
		function(data){
			if(data.status == 'success'){
				console.log(data.result);
				return data;

			}else{
				console.log("backend err");
			}	
		}
	).error(function(){
		console.log("error");
	});	
	
}

function get1(userId){
	var url = 'http://test.huiji.wiki:50007/get1/';
	jQuery.post(
		url,
		{
			userId:userId,
		},
		function(data){
			if(data.status == 'success'){
				console.log(data.result);
				return data;

			}else{
				console.log("backend err");
			}	
		}
	).error(function(){
		console.log("error");
	});	
}




