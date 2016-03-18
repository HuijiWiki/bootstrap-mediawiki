function insertRecordIntoDB(url,navigatorInfo,fromSource,userId,userName,wikiSite,siteName,titleName,articleId) {
        jQuery.post(
                url,
                {
                        navigatorInfo:navigatorInfo,
                        fromSource:fromSource,
                        userId:userId,
                        userName:userName,
                        articleId:articleId,
                        titleName:titleName,
                        siteName:siteName,
                        wikiSite:wikiSite,
                 }
        )
}


function insertIntoMongoDB(url,navigatorInfo,fromSource,userId,userName,wikiSite,siteName,titleName,articleId) {

        jQuery.ajax({
               url: url,
		type:'POST',
                data:
                {
                        client_userAgent:navigatorInfo,
                        source:fromSource,
                        user_id:userId,
                        user_name:userName,
                        article_id:articleId,
                        article_title:titleName,
                        site_name:siteName,
                        site_prefix:wikiSite,
                 },
		 success:
                 function(data){
			console.log(data)
		},
		error:
		function(data){
		 console.log(data)
		}
	 }
        )

}



function clearSourceUrl(sourceUrl){
        var e = new RegExp('^(?:(?:https?|ftp):)/*(?:[^@]+@)?([^:/#]+)'),
        matches = e.exec(sourceUrl);
        return matches ? matches[1]:sourceUrl;
}




