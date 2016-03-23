
// mw.prototype.abc = function(){
// 	console.log('123123123');
// 	return 100;
// }

// $.prototype.abcd = function(){
// 	return 100;
// };
// console.log('566566');
huiji = {

	getEditRecordsOnWikiSiteFromUserIdGroupByDay: function(userId,wikiSite,fromTime,toTime,callback)
	{
		var url = 'http://huijidata.com:8080/statisticQuery/webapi/edit/getPageEditRecordsOnWikiSiteFromUserIdGroupByDay';
		jQuery.post(
			url,
			{
				userId:userId,
				sitePrefix:wikiSite,
				fromDate:fromTime,
				toDate:toTime,
			},
			function(data){
		//		console.log(data);
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

	},



	getViewRecordsOnWikiSiteFromUserIdGroupByDay: function(userId,wikiSite,fromTime,toTime,callback)
	{
		var url = 'http://huijidata.com:8080/statisticQuery/webapi/view/getPageViewRecordsOnWikiSiteFromUserIdGroupByDay';
		jQuery.post(
			url,
			{
				userId:userId,
				sitePrefix:wikiSite,
				fromDate:fromTime,
				toDate:toTime,
			},
			function(data){
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

	},

	getPreviousViewRecords: function(wikiSite,dayNumber,cb)
	{
		var date_array = new Array();
		var number_array = new Array();
		
		for(var i=1;i<=dayNumber;i++){
			date_array[i-1] = moment().subtract(dayNumber-i+1,"days").format("YYYY-MM-DD");
		}


		var callback = function(data){
			
			var newData = {};
			if(data.result != null){

				var rs = {};
                               
				for(var i=1;i<=dayNumber;i++){
					
					number_array[i-1] = data.result[date_array[i-1]];
					if(number_array[i-1] == null) number_array[i-1] = 0;

				} 
				

				rs.date_array = date_array;	
				rs.number_array = number_array;
				newData.result = rs;
				newData.status = data.status;
			}
			cb(newData);
		}


		this.getViewRecordsOnWikiSiteFromUserIdGroupByDay(-1,wikiSite,moment().subtract(1+dayNumber,"days").format("YYYY-MM-DD"),moment().subtract(1,"days").format("YYYY-MM-DD"),callback);

	},

	getPreviousEditRecords: function(wikiSite,dayNumber,cb)
	{
		var date_array = new Array();
		var number_array = new Array();
		
		for(var i=1;i<=dayNumber;i++){
			date_array[i-1] = moment().subtract(dayNumber-i+1,"days").format("YYYY-MM-DD");
		}


		var callback = function(data){
			var newData = {};	
	                 if(data.result != null){

				var rs = {};

                               	for(var i=1;i<=dayNumber;i++){
					number_array[i-1] = data.result[date_array[i-1]];
					if(number_array[i-1] == null) number_array[i-1] = 0;

				} 

                                rs.date_array = date_array;
                                rs.number_array = number_array;
                                newData.result = rs;
                                newData.status = data.status;
                        
			
			}
			cb(newData);
		}


		this.getEditRecordsOnWikiSiteFromUserIdGroupByDay(-1,wikiSite,moment().subtract(1+dayNumber,"days").format("YYYY-MM-DD"),moment().subtract(1,"days").format("YYYY-MM-DD"),callback);

	}
}


