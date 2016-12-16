<?php
class AdsManager{
	private static $whiteList = array('lotr');
	private static $headerWhiteList = array('movie');
	public function __construct($site){
		global $wgUser, $wgIsProduction;
		$this->site = $site;
		$prevmonth = date('Y-m', strtotime("last month"));
		if ($site->hasMetDonationGoal($prevmonth)){
			$this->showAds = false;
		} elseif($wgUser->isLoggedIn()) {
			$this->showAds = false;
		} elseif(in_array($site->getPrefix(), self::$whiteList)){
			$this->showAds = false;
		} else {
			$this->showAds = true;
		}
		if ($wgIsProduction==false){
			$this->showAds = true;
		}
	}
	public function getWideHeader(){
		if ($this->showAds && !in_array($this->site->getPrefix, self::$headerWhiteList)){
			$tpl = <<<html
<div class="form-group">
<script type="text/javascript">
    /*Header*/
    var cpro_id = "u2846048";
</script>
<script type="text/javascript" src="http://cpro.baidustatic.com/cpro/ui/c.js"></script>
</div>
html;
			return $tpl;
		}
	}
	public function getFloatBanner(){
		if ($this->showAds){
			$tpl = <<<html
<script type="text/javascript">
    /*120*270 创建于 12/15/2016*/
    var cpro_id = "u2846052";
</script>
<script type="text/javascript" src="http://cpro.baidustatic.com/cpro/ui/f.js"></script>
html;
			return $tpl;
		}
	}
	public function getBlockScreen(){
		if ($this->showAds){
			$tpl = <<<html
<script type="text/javascript"> 
     /*blockScreen*/ 
     
     if(document.body.clientWidth>=768) {
     	var cpro_id = "u2846093";
     	console.log('blockscreen');
     }
</script>
<script type="text/javascript" src="http://cpro.baidustatic.com/cpro/ui/c.js"></script>
html;
			return $tpl;
		}		
	}
	public function getFooter(){
		if ($this->showAds){
			$tpl = <<<html
<script type="text/javascript">
    /*1024*125 创建于 12/15/2016*/
    var cpro_id = "u2846113";
</script>
<script type="text/javascript" src="http://cpro.baidustatic.com/cpro/ui/c.js"></script>
html;
			return $tpl;
		}		
	}
	public function getSmartMobile(){
		if ($this->showAds){
			$tpl = <<<html
<script type="text/javascript"> 
     /*smartMobile*/ 
     if(document.body.clientWidth<768) {
     	var cpro_id = "u2846082";
     }
</script>
<script type="text/javascript" src="http://cpro.baidustatic.com/cpro/ui/cm.js"></script>
html;
			return $tpl;
		}	
	}
	public function getDumbMobile(){
		if ($this->showAds){
			$tpl = <<<html
<div class="form-group">
<script type="text/javascript">
    /*dumbmobile*/
    var cpro_id = "u2846276";
</script>
<script type="text/javascript" src="http://cpro.baidustatic.com/cpro/ui/cm.js"></script>
</div>
html;
			return $tpl;
		}			
	}

	
}
?>