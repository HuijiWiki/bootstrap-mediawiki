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
$wgResourceModules['skins.bootstrapmediawiki.sitecolor' ] = array(
    'styles' => array(
		 '/style/SiteColor.less'                 =>array( 'media' => 'all' ),
	),
	'remoteBasePath' => '',
	'localBasePath' => $IP,
	'position' => 'top',
);
$wgResourceModules['skins.bootstrapmediawiki.top'] = array(
	'styles' => array(
		$skinDir . '/bootstrap-3.3.5/bootstrap-3.3.5/less/bootstrap.less'            => array( 'media' => 'all' ),
		$skinDir . '/css/fonts.css'                              => array( 'media' => 'all' ),
		$skinDir . '/style.css'                                  => array( 'media' => 'all' ),

		$skinDir . '/less/huiji.less'                                 => array( 'media' => 'all' ),
		$skinDir . '/css/huiji.ext.css'                          => array( 'media' => 'all' ),
		$skinDir . '/css/video.css'                              => array( 'media' => 'all' ),
		$skinDir . '/css/huiji.navbox.css'					     => array( 'media' => 'all' ),
	),
	'scripts' => array(
		$skinDir . '/bootstrap/js/bootstrap.js',		
		$skinDir . '/js/huiji.preload.js',
	),
	'dependencies' => array(
	    'skins.bootstrapmediawiki.sitecolor'
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
		$skinDir . '/js/mention.js',
	),
	'styles' => array(
		$skinDir . '/css/huiji.ready.css'                                  => array( 'media' => 'all' ),
		$skinDir . '/css/mention.css'                                  => array( 'media' => 'all' ),
		
	),
	'dependencies' => array(
//	    'skins.editable',
		'skins.bootstrapmediawiki.top',
		'mediawiki.cookie',
		'mediawiki.notification',
		'mediawiki.api.options',
		'skins.bootstrapmediawiki.emoji',
		'ext.HuijiMiddleware.feedback',
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

$wgResourceModules['skins.bootstrapmediawiki.emoji'] = array(
    'styles' => array(
		$skinDir . '/emoji-picker/lib/css/nanoscroller.css'         					 => array( 'media' => 'all' ),
		$skinDir . '/emoji-picker/lib/css/emoji.css'         					        => array( 'media' => 'all' ),
	),
	'scripts' => array(
		$skinDir . '/emoji-picker/lib/js/nanoscroller.min.js',
		$skinDir . '/emoji-picker/lib/js/tether.min.js',
		$skinDir . '/emoji-picker/lib/js/config.js',
		$skinDir . '/emoji-picker/lib/js/util.js',
		$skinDir . '/emoji-picker/lib/js/jquery.emojiarea.js',
		$skinDir . '/emoji-picker/lib/js/emoji-picker.js',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'bottom',
);
$wgResourceModules['skins.bootstrapmediawiki.videohandler'] = array(
	'scripts' => array(
		$skinDir . '/js/huiji.videoHandler.js',
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
	'dependencies' => 'jquery.wikiEditor.toolbar',
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],	
	'position' => 'top',
);
$wgResourceModules['ext.wikieditor.huijiextra.sisyphus'] = array(
	'scripts' => array(
		$skinDir . '/sisyphus/sisyphus.js',
	), 
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],	
	'position' => 'bottom',	
);
$wgResourceModules['ext.wikieditor.huijiextra.bottom'] = array(
	'scripts' => array(
		$skinDir . '/js/huiji.editor.js',
	), 
	'messages' => array( 
		'edittools'
	),
	'dependencies' => array(
		'skins.bootstrapmediawiki.videohandler',
		'oojs-ui'
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
$wgResourceModules['skins.bootstrapmediawiki.huiji.ve'] = array(
	'scripts' => array(
		$skinDir . '/js/huiji.ve.js',
	),
	'styles' => array(
		$skinDir . '/css/huiji.ve.css'         					 => array( 'media' => 'all' ),
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'bottom',	
);
$wgResourceModules['skins.bootstrapmediawiki.content'] = array(
	'scripts' => array(
		$skinDir . '/js/owl.carousel.min.js',
	    $skinDir . '/js/huiji.content.js',
	),
	'styles' => array(
		$skinDir . '/css/owl.carousel.css'                               => array( 'media' => 'all' ),
		$skinDir . '/css/huiji.content.css'         					 => array( 'media' => 'all' ),
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'bottom',	
);
$wgResourceModules['skins.bootstrapmediawiki.search'] = array(
	'scripts' => array(
	),
	'styles' => array(
		$skinDir . '/css/huiji.search.css'         					 => array( 'media' => 'all' ),
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'top',	
);

$wgResourceModuleSkinStyles['BootstrapMediawiki'] = array(
	'mediawiki.ui' => $skinDir. '/less/mediawiki.ui/default.less',
	'mediawiki.ui.checkbox' => $skinDir.'/less/mediawiki.ui/components/checkbox.less',
	'mediawiki.ui.radio' => $skinDir.'/less/mediawiki.ui/components/radio.less',
	'mediawiki.ui.anchor' => $skinDir.'/less/mediawiki.ui/components/anchors.less',
	'mediawiki.ui.button' => $skinDir.'/less/mediawiki.ui/components/buttons.less',
	'mediawiki.ui.input' => $skinDir.'/less/mediawiki.ui/components/inputs.less',
	'mediawiki.ui.icon' => $skinDir.'/less/mediawiki.ui/components/icons.less',
	'mediawiki.ui.text' => $skinDir.'/less/mediawiki.ui/components/text.less',
	'oojs-ui.styles' => $skinDir.'/less/oojs-ui/oojs-ui-mediawiki-noimages.less',
 	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
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
// $wgHooks['LinkBegin'][] = 'BootstrapMediawikiHooks::UserLinkBegin';
$wgHooks['BeforePageDisplay'][] = 'BootstrapMediawikiHooks::onBeforePageDisplay';
$wgHooks['ResourceLoaderGetLessVars'][] = 'BootstrapMediawikiHooks::onResourceLoaderGetLessVars';
$wgHooks['SpecialSearchResultsPrepend'][] = 'BootstrapMediawikiHooks::onSpecialSearchResultsPrepend';
// new permission
$wgAvailableRights[] = 'quickpurge';
$wgGroupPermissions['sysop']['quickpurge'] = true;
$wgAvailableRights[] = 'quickdebug';
$wgGroupPermissions['sysop']['quickdebug'] = true;

//Register modules in VE
$wgVisualEditorPluginModules[]='skins.bootstrapmediawiki.huiji.ve';
//Less Path