<?php
class AdsManager{
	private static $whiteList = array('lotr', 'dnfcn');
	private static $headerWhiteList = array('movie');
	public function __construct($site){
		global $wgUser, $wgIsProduction, $wgEnableAds;
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
		if (empty($wgEnableAds)){
			$this->showAds = false;
		}
	}
	public function getWideHeader(){
		if ($this->showAds && !in_array($this->site->getPrefix(), self::$headerWhiteList)){
			$tpl = <<<html
<div class="form-group">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- HeaderReponsive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1928320312730168"
     data-ad-slot="2661087135"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
html;
			return $tpl;
		}
	}
	public function getFloatBanner(){
// 		if ($this->showAds){
// 			$tpl = <<<html
// <script type="text/javascript">
//     /*120*270 创建于 12/15/2016*/
//     var cpro_id = "u2846052";
// </script>
// <script type="text/javascript" src="http://cpro.baidustatic.com/cpro/ui/f.js"></script>
// html;
// 			return $tpl;
// 		}
	}
	public function getBlockScreen(){
		if ($this->showAds){
			$tpl = <<<html
<div class="ads-screen float-right">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- blockscreen -->
<ins class="adsbygoogle"
style="display:inline-block;width:300px;height:250px"
data-ad-client="ca-pub-1928320312730168"
data-ad-slot="8507314333"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
html;
			return $tpl;
		}else{
			return '';
		}
	}
	public function getFooter(){
		if ($this->showAds ){
			$tpl = <<<html
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- FooterResponsive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1928320312730168"
     data-ad-slot="4137820330"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
html;
			return $tpl;
		}		
	}
	public function getSmartMobile(){
		if ($this->showAds){
			$tpl = <<<html
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-1928320312730168",
    enable_page_level_ads: true
  });
</script>
html;
			return $tpl;
		}	else {
			return '';
		}
	}
	public function getDumbMobile(){
		if ($this->showAds){
			$tpl = <<<html
<div class="form-group">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Dumbmobile2 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1928320312730168"
     data-ad-slot="7078050737"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
html;
			return $tpl;
		}			
	}

	
}
?>
