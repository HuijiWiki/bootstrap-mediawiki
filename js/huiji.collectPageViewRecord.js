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
		}
	)	

}
	
/*
var fromSource    = document.referrer; 
var navigatorInfo = navigator.userAgent.toLowCase();
var userId    = mw.config.get("wgUserId");
var userName  = mw.config.get("wgUserName");
var wikiSite  = mw.config.get("wgHuijiPrefix");
var siteName  = mw.config.get("wgSiteName");
var titleName = mw.config.get("wgPageName");
var articleId = mw.config.get("wgArticleId");
var url = 'http://test.huiji.wiki:50007/insertViewRecord/';


insertRecordIntoDB(url,navigatorInfo,fromSource,userId,userName,wikiSite,siteName,titleName,articleId);
*/	


