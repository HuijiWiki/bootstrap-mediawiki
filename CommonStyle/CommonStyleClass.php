<?php
/**
 * This Class manages Common style
 */
class CommonStyle{
	function __construct() {

	}
	//insert file
	public static function insertSiteCss( $fileName, $cssContent, $cssId ){
		$dbw = wfGetDB( DB_MASTER );
			if ( $cssId == 0 ) {
			$dbw -> insert(
					'common_css',
					array(
						'css_name' => $fileName,
						'css_content' => $cssContent,
						'css_status' => 2,
						'update_date' => date('Y-m-d H:i:s', time()),
					),
					 __METHOD__
				);
			if ( $dbw->insertId() ) {
				return $dbw->insertId();
			}
		}else{
			$dbw -> update(
					'common_css',
					array(
						'css_name' => $fileName,
						'css_content' => $cssContent,
						'update_date' => date('Y-m-d H:i:s', time()),
					),
					array(
						'css_id' => $cssId,
					),
					 __METHOD__
				);
			return true;
		}
		
	}

	//check is exist css file
	public static function checkCssFile( $fileName ){
		$result = false;
		if ( $fileName != null ) {
			$dbr = wfGetDB( DB_SLAVE );
			$dbr -> select(
					'common_css',
					array( 'status' ),
					array(
						'css_filename' => $fileName
					),
					__METHOD__
				);
			foreach ($dbr as $key => $value) {
				if ( $value->status > 0 ) {
					$result = true;
				}
			}
		}
		return $result;
	}

	//open css style
	public static function openCssStyle( $cssId ){
		global $wgHuijiPrefix;
		$result = false;
		if ( $cssId != null ) {
			$dbw = wfGetDB( DB_MASTER );
			$res = $dbw -> update(
						'common_css',
						array(
							'css_status' => 2
						),
						array(
							'css_status' => 1
						),
						__METHOD__
					);
			if ( $res ) {
				$req = $dbw -> update(
							'common_css',
							array(
								'css_status' => 1
							),
							array(
								'css_id' => $cssId
							),
							__METHOD__
						);
				if ( $req ) {
					$cssRes = self::getCurrentCssStyle( $cssId );
					$cssPath = "/var/www/virtual/".$wgHuijiPrefix."/uploads/css";
					file_put_contents($cssPath.'/'.$fileName, $cssRes['cssContent']); 
					$result = true;
				}
			}
		}
		return $result;
	}

	/**
	 * get current css
	 * @param  [int] $cssId if null, get the current css
	 * @return [type]        [description]
	 */
	public static function getStyle(){
		global $wgMemc;
		$key = wfMemcKey("commonStyle", "getStyle" );

		$cssContent = $wgMemc->get($key);
		if ($cssContent != ''){
			return $cssContent;
		}
		if ($wgMemc)
		$content = '';
		$title = ApiCommonStyle::getStyleTitle();
        $wp = new WikiPage($title);
        if ($wp->exists() || null !== ($wp->getContent())){
        	$content = $wp->getContent()->getNativeData();
        }
        if ($content == ''){
            $cssContent = CommonStyle::getCurrentCssStyle(1); //backward capability;
        } elseif($content != '-' ) {
            $cssContent['cssContent'] = $content;
        } else {
            $cssContent['cssContent'] = null;
        }
        $wgMemc->set($key, $cssContent);
        return $cssContent;
	}

	public static function clearCache( $context = null ){
		global $wgMemc, $wgResourceModules;
		$key = wfMemcKey("commonStyle", "getStyle" );
		$wgMemc->delete($key);		
		//Force Rebuild Resourceloader module.
		//$fileName = __DIR__ ."/../less/huiji.less";

		$styles = $wgResourceModules['skins.bootstrapmediawiki.top']['styles'];
		foreach ($styles as $key => $value){
			if ( pathinfo($key, PATHINFO_EXTENSION) === 'less' ){
				$fileName = $wgResourceModules['skins.bootstrapmediawiki.top']['localBasePath'].'/'.$key;
				$varsHash = hash( 'md4', serialize( [] ) );
				$cache = ObjectCache::getLocalServerInstance( CACHE_ANYTHING );
				$cacheKey = $cache->makeGlobalKey( 'LESS', $fileName, $varsHash );
				$cachedCompile = $cache->delete( $cacheKey );	
			}
		}


		//$compiler = $context->getResourceLoader()->getLessCompiler( [] );
		// touch( __DIR__ ."/../less/huiji.less");
		//array_map('unlink', glob("$wgFileCacheDirectory/*.lesscache"));
	}

	/**
	 * get current css
	 * @param  [int] $cssId if null, get the current css
	 * @return [type]        [description]
	 * @deprecated
	 */
	public static function getCurrentCssStyle( $cssId ){
		$dbr = wfGetDB( DB_SLAVE );
		if ( $cssId != null ) {
			$where = array(
						'css_id' => $cssId
					);
		}else{
			$where = array(
						'css_id' => 1
					);
		}
		$res = $dbr -> select(
				'common_css',
				array(
					'css_name',
					'css_content',
					'update_date',
				),
				$where,
				__METHOD__,
				array(
					'LIMIT' => 1
				)
			);
		$result = array();
		if ($res) {
			foreach ($res as $value) {
				$result['cssName'] = $value->css_name;
				$result['cssContent'] = $value->css_content;
				$result['updateDate'] = $value->update_date;
			}
		}else{
			return false;
		}
		return $result;
	}
}
