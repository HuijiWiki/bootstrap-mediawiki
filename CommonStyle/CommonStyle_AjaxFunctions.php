<?php
/**
 * AJAX functions used by CommonStyle extension.
 */
$wgAjaxExportList[] = 'wfUpdateCssStyle';
$wgAjaxExportList[] = 'wfOpenCssStyle';
$wgAjaxExportList[] = 'getLessContent';
function wfUpdateCssStyle( $cssContent, $fileName, $cssId ) {
	
	global $wgUser, $wgHuijiPrefix, $wgSitename;
	$cssPath = "/var/www/virtual/".$wgHuijiPrefix."/style";

	$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_UNKNOWN);

	// This feature is only available for logged-in users.
	if ( !$wgUser->isLoggedIn() ) {
		$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_NOT_LOGGED_IN);
		return $out;
	}

	// No need to allow blocked users to access this page, they could abuse it, y'know.
	if ( $wgUser->isBlocked() ) {
		$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_BLOCKED);
		return $out;
	}

	// Database operations require write mode
	if ( wfReadOnly() ) {
		$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_READ_ONLY);
		return $out;
	}

	// Are we even allowed to do this?
	if ( !$wgUser->isAllowed( 'editinterface' ) ) {
		$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_NOT_ALLOWED);
		return $out;
	}

    if(!is_dir($cssPath)){
    	mkdir($cssPath, 0777);
    }
    $lessCon = $cssCon = '';
    if ( count($cssContent)>0 && !empty($cssContent) ) {
    	$named = array('aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen');
    	// var_dump($cssContent);
    	foreach ($cssContent as $key => $val) {
    		if ( !( $val == 'false' || preg_match("/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/",$val) || preg_match("/^rgb[a]?/i",$val) || preg_match("/^hsl[a]?/i",$val) || preg_match("/^[a-zA-z]+:\/\/[\S]*[.jpg | .png | .gif | .jpeg]$/",$val) || in_array(strtolower($val), $named) ) ) {
    			$ret = array('format'=> 'false');
    			$out = json_encode($ret);
				return $out;
    		}
    	}
    	$cssCon = json_encode($cssContent);
    	foreach ($cssContent as $key => $value) {
			$lessCon .= $key.":".$value.";";
    	}	
    }
    file_put_contents($cssPath.'/SiteColor.less', $lessCon); 
	$res = CommonStyle::insertSiteCss( $fileName, $cssCon, $cssId );
	if ($res) {
		$ret = array('result'=> 'true' );
		$log = new LogPage( 'CommonStyle' );
		$log->addEntry(
				'addDescription',
				SpecialPage::getTitleFor('CommonStyle'),
				wfMessage( 'user-modify-sitestyle-log-entry',array( $wgUser->getName(),$wgSitename ) )->inContentLanguage()->text(),
				array()
			);
	}else{
		$ret = array('result'=> 'false' );
	}
	$out = json_encode($ret);
	return $out;
}

function wfOpenCssStyle( $cssId ){

	global $wgUser;
	$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_UNKNOWN);

	// This feature is only available for logged-in users.
	if ( !$wgUser->isLoggedIn() ) {
		$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_NOT_LOGGED_IN);
		return $out;
	}

	// No need to allow blocked users to access this page, they could abuse it, y'know.
	if ( $wgUser->isBlocked() ) {
		$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_BLOCKED);
		return $out;
	}

	// Database operations require write mode
	if ( wfReadOnly() ) {
		$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_READ_ONLY);
		return $out;
	}

	// Are we even allowed to do this?
	if ( !$wgUser->isAllowed( 'edit' ) ) {
		$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_NOT_ALLOWED);
		return $out;
	}
	if ( $cssId != null ) {
		$res = CommonStyle::openCssStyle( $cssId );
		if ($res) {
			$out = '{"success": true,"message": "insert success"}';
		}
	}
	return $out;
}

function getLessContent(){
	global $wgUser, $wgHuijiPrefix;
	$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_UNKNOWN);

	// This feature is only available for logged-in users.
	if ( !$wgUser->isLoggedIn() ) {
		$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_NOT_LOGGED_IN);
		return $out;
	}

	// No need to allow blocked users to access this page, they could abuse it, y'know.
	if ( $wgUser->isBlocked() ) {
		$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_BLOCKED);
		return $out;
	}

	// Database operations require write mode
	if ( wfReadOnly() ) {
		$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_READ_ONLY);
		return $out;
	}

	// Are we even allowed to do this?
	if ( !$wgUser->isAllowed( 'editinterface' ) ) {
		$out = ResponseGenerator::getJson(ResponseGenerator::ERROR_NOT_ALLOWED);
		return $out;
	}
	$default = Huiji::getInstance()->getSiteDefaultColor();
	$defaultRes = array();
	foreach ($default as $key => $value) {
		$defaultRes['@'.$key] = $value;
	}
	$cssContent = CommonStyle::getCurrentCssStyle(1);
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
    return $lessStr;

}