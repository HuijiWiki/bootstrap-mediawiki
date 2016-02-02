<?php
/**
 * Bootstrap MediaWiki
 *
 * @bootstrap-mediawiki.php
 * @ingroup Skins
 * @author Matthew Batchelder (http://borkweb.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
if ( ! defined( 'MEDIAWIKI' ) ) die( "This is an extension to the MediaWiki package and cannot be run standalone." );
$wgExtensionCredits['skin'][] = array(
	'path'        => __FILE__,
	'name'        => 'Bootstrap Mediawiki',
	'url'         => 'http://borkweb.com',
	'author'      => '[http://borkweb.com Matthew Batchelder]',
	'description' => 'MediaWiki skin using Bootstrap 3',
);
$wgValidSkinNames['bootstrapmediawiki'] = 'BootstrapMediaWiki';
$wgAutoloadClasses['SkinBootstrapMediaWiki'] = __DIR__ . '/BootstrapMediaWiki.skin.php';
$wgAutoloadClasses['BootstrapMediawikiHooks'] = __DIR__ . '/BootstrapMediawikiHooks.php';
$wgAutoloadClasses['Preloads'] = __DIR__ . '/Preload.php';
$wgAutoloadClasses['HuijiSkinTemplate'] = __DIR__ . '/HuijiSkinTemplate.php';
$wgAutoloadClasses['FrontPage'] = __DIR__ . '/frontpage.php';
$wgAutoloadClasses['CUtf8_PY'] = __DIR__ . '/CUtf8_PY.php';
$wgMessagesDirs['bootstrapmediawiki'] = __DIR__ . '/i18n';
$skinDirParts = explode( DIRECTORY_SEPARATOR, __DIR__ );
$skinDir = array_pop( $skinDirParts );
$src = '/var/www/src';
$wgResourceModules['skins.bootstrapmediawiki.hover'] = array(
	'styles' => array(
		$skinDir . '/css/hover-min.css'                          => array( 'media' => 'all' ),
	),
	'dependencies' => array(
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'top',
);
$wgResourceModules['skins.bootstrapmediawiki.ihover'] = array(
	'styles' => array(
		$skinDir . '/css/ihover.min.css'                          => array( 'media' => 'all' ),
	),
	'dependencies' => array(
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'top',
);
$wgResourceModules['skins.bootstrapmediawiki.top'] = array(
	'styles' => array(
		$skinDir . '/bootstrap/css/bootstrap.min.css'            => array( 'media' => 'all' ),
		$skinDir . '/css/fonts.css'                              => array( 'media' => 'all' ),
		$skinDir . '/style.css'                                  => array( 'media' => 'all' ),
		$skinDir . '/default_theme.less'                         => array( 'media' => 'all' ),
		$skinDir . '/style.less'                                 => array( 'media' => 'all' ),
		$skinDir . '/css/huiji.ext.css'                          => array( 'media' => 'all' ),
	),
	'scripts' => array(
		$skinDir . '/bootstrap/js/bootstrap.js',		
		$skinDir . '/js/huiji.preload.js',
	),
	'dependencies' => array(
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'top',
);
$wgResourceModules['skins.bootstrapmediawiki.bottom'] = array(
	'scripts' => array(
		$skinDir . '/js/fastclick.js',
		$skinDir . '/js/scroll.js',
		$skinDir . '/js/jquery.ba-dotimeout.min.js',
		$skinDir . '/js/huiji.flow.js',
		$skinDir . '/js/huiji.collectPageViewRecord.js',
		$skinDir . '/js/huiji.ready.js',
		$skinDir . '/js/qqLogin.js',
		$skinDir . '/js/recommend.js',
	),
	'styles' => array(
		$skinDir . '/css/huiji.ready.css'                                  => array( 'media' => 'all' ),
	),
	'dependencies' => array(
//	    'skins.editable',
		'skins.bootstrapmediawiki.top',
		'mediawiki.cookie',
		'mediawiki.notification',
		'mediawiki.api.options'
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'bottom',	
);
$wgResourceModules['skins.bootstrapmediawiki.fork'] = array(
	'scripts' => array(
		$skinDir . '/js/fork.js',
	),	
	'styles' => array(
		$skinDir . '/css/fork.css'                 => array( 'media' => 'all' ),
	),
	'dependencies' => array(
//	    'skins.editable',
		'skins.bootstrapmediawiki.top',
		'mediawiki.notification',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'bottom',		
);
$wgResourceModules['skins.bootstrapmediawiki.editcategory'] = array(
	'styles' => array(
		$skinDir . '/css/editcategory.css'                 => array( 'media' => 'all' ),
	),
	'scripts' => array(
		$skinDir . '/js/editcategory.js',
	),
	'dependencies' => array(
		'skins.bootstrapmediawiki.top',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'bottom',
);
$wgResourceModules['skins.editable'] = array(
	'styles' => array(
		$skinDir . '/css/bootstrap-editable.css'                 => array( 'media' => 'all' ),
	),
	'scripts' => array(
		$skinDir . '/js/bootstrap-editable.min.js',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'bottom',
);
$wgResourceModules['skins.frontpage'] = array(
	'styles' => array(
		$skinDir . '/css/style.css'         					 => array( 'media' => 'all' ),
	),
	'scripts' => array(
		$skinDir . '/js/effect.js',
	),
	'dependencies' => array(
		'skins.bootstrapmediawiki.top',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'top',
);
$wgResourceModules['skins.bootstrapmediawiki.frontpage.cloud'] = array(
	'scripts' => array(
// 		$skinDir . '/js/three.min.js',
// //		$skinDir . '/js/stats.min.js',
		$skinDir . '/js/cloud.js',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'bottom',
);
/* Resource for wiki editor */
$wgResourceModules['ext.wikieditor.huijiextra.top'] = array(
	'styles' => array(
		$skinDir . '/css/huiji.editor.css'         					 => array( 'media' => 'all' ),
	),	
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],	
	'position' => 'top',
);
$wgResourceModules['ext.wikieditor.huijiextra.bottom'] = array(
	'scripts' => array(
		$skinDir . '/sisyphus/sisyphus.js',
		$skinDir . '/js/huiji.editor.js',
	), 
	'messages' => array( 
		'edittools'
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],	
	'position' => 'bottom',
);
$wgResourceModules['skins.bootstrapmediawiki.huiji.getrecordsinterface.js'] = array(
	'scripts' => array(
		$skinDir . '/js/huiji.getRecordsInterface.js',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'bottom',	
);
$wgResourceModules['skins.bootstrapmediawiki.huiji.globalsearch'] = array(
	'scripts' => array(
		$skinDir . '/js/huiji.globalsearch.js',
	),
	'styles' => array(
		$skinDir . '/css/huiji.globalsearch.css'         					 => array( 'media' => 'all' ),
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'bottom',	
);
if ( isset( $wgSiteJS ) ) {
	$wgResourceModules['skins.bootstrapmediawiki']['scripts'][] = $skinDir . '/' . $wgSiteJS;
}//end if
if ( isset( $wgSiteCSS ) ) {
	$wgResourceModules['skins.bootstrapmediawiki']['styles'][] = $skinDir . '/' . $wgSiteCSS;
}//end if
// update page's cache
$wgHooks['NewRevisionFromEditComplete'][] = 'BootstrapMediawikiHooks::onNewRevisionFromEditComplete';
$wgHooks['OutputPageBeforeHTML'][]  = 'BootstrapMediawikiHooks::wfEditSectionLinkTransform'; 
$wgHooks['MediaWikiPerformAction'][] = 'BootstrapMediawikiHooks::onMediaWikiPerformAction';
$wgHooks['GetDefaultSortkey'][] = 'BootstrapMediawikiHooks::onGetDefaultSortkey';
$wgHooks['EditPage::showEditForm:initial'][] = 'BootstrapMediawikiHooks::addEditModule';
$wgHooks['GalleryGetModes'][] = 'BootstrapMediawikiHooks::onGalleryGetModes';
$wgHooks['ParserFirstCallInit'][] = 'BootstrapMediawikiHooks::registerParserHook';
$wgHooks['OutputPageMakeCategoryLinks'][] = 'BootstrapMediawikiHooks::onOutputPageMakeCategoryLinks';
$wgHooks['LinkBegin'][] = 'BootstrapMediawikiHooks::UserLinkBegin';
$wgHooks['BeforePageDisplay'][] = 'BootstrapMediawikiHooks::onBeforePageDisplay';
// new permission
$wgAvailableRights[] = 'quickpurge';
$wgGroupPermissions['sysop']['quickpurge'] = true;
$wgAvailableRights[] = 'quickdebug';
$wgGroupPermissions['sysop']['quickdebug'] = true;
