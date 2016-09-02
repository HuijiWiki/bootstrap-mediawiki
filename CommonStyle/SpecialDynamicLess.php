<?php   
use \MediaWiki as mw;
/**
* DynamicLess
*/
class SpecialDynamicLess extends UnlistedSpecialPage{
    
    function __construct(){
        parent::__construct( 'DynamicLess' );
    }

    public function execute( $params ) {
        global $wgHuijiPrefix;
        $default = Huiji::getInstance()->getSiteDefaultColor();
        $defaultRes = array();
        foreach ($default as $key => $value) {
            $defaultRes['@'.$key] = $value;
        }
        $cssContent = CommonStyle::getThemedStyle();
        
        if ( $cssContent['cssContent'] == null ) {
            $lessCon = array();
        }else{
            $lessCon = (array)json_decode( $cssContent['cssContent'] );
        }
        $result = array_merge( $defaultRes, $lessCon );
        $lessStr = '';
        foreach ($result as $key => $value) {
            $lessStr .= $key.":".$value.";";
        }
        $lessPath = "/var/www/virtual/".$wgHuijiPrefix."/skins/bootstrap-mediawiki/less/custom.less";
        $lessStr .= file_get_contents($lessPath);
        $re = "/[\"']([a-z]*+.less)[\"']/"; 
        $lessStr = preg_replace_callback($re,
                            function($matches){
                                global $wgHuijiPrefix;
                                return "\"/skins/bootstrap-mediawiki/less/{$matches[1]}\"";
                            }, 
                            $lessStr);
        mw\suppressWarnings();
        $this->getOutput()->setArticleBodyOnly(true);
        echo $lessStr;
        $this->getOutput()->output();
    }
}