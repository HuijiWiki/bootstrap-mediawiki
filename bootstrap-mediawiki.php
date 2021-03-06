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
$wgAutoloadClasses['SpecialCommonStyle'] = __DIR__ . '/CommonStyle/SpecialCommonStyle.php';
$wgAutoloadClasses['CommonStyle'] = __DIR__ . '/CommonStyle/CommonStyleClass.php';
$wgAutoloadClasses['SpecialDynamicLess'] = __DIR__ . '/CommonStyle/SpecialDynamicLess.php';
$wgAutoloadClasses['ApiCommonStyle'] = __DIR__ . '/CommonStyle/api/ApiCommonStyle.php';
$wgAutoloadClasses['AdsManager'] = __DIR__ . '/AdsManager.php';
$wgSpecialPages['CommonStyle'] = 'SpecialCommonStyle';
$wgMessagesDirs['bootstrapmediawiki'] = __DIR__ . '/i18n';
$wgMessagesDirs['bootstrapmediawikiCommonStyle'] = __DIR__ . '/CommonStyle/i18n';
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
$wgResourceModules['skins.bootstrapmediawiki.prettify' ] = array(
    'styles' => array(
		$skinDir . '/google-code-prettify/prettify.css'          => array( 'media' => 'all' ),
	),
	'scripts' => array(
		$skinDir . '/google-code-prettify/prettify.js',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
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
		$skinDir . '/css/mention.css'                                  => array( 'media' => 'all' ),
	),
	'scripts' => array(
		$skinDir . '/bootstrap/js/bootstrap.js',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'top',
);
$wgResourceModules['skins.bootstrapmediawiki.header'] = array(
	'styles' => array(
		$skinDir . '/less/local/huiji.header.less'            => array( 'media' => 'all' ),
	),
	'scripts' => array(	
		$skinDir . '/js/huiji.header.js',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'top',
);
$wgResourceModules['skins.bootstrapmediawiki.bottom'] = array(
	'scripts' => array(
		$skinDir . '/js/huiji.preload.js',
		$skinDir . '/js/fastclick.js',
		$skinDir . '/js/scroll.js',
		// $skinDir . '/js/jquery.ba-dotimeout.min.js',
		$skinDir . '/js/huiji.collectPageViewRecord.js',
		$skinDir . '/js/huiji.ready.js',
		$skinDir . '/js/mention.js',
	),
	'styles' => array(
		$skinDir . '/css/huiji.ready.css'                                  => array( 'media' => 'all' ),
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
$wgResourceModules['skins.bootstrapmediawiki.huiji.globalsearch.suggest'] = array(
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
	'dependencies' => array(
            'skins.bootstrapmediawiki.bottom',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'bottom',	
);
$wgResourceModules['skins.bootstrapmediawiki.search'] = array(
	'scripts' => array(
	),
	'styles' => array(
		$skinDir . '/less/local/huiji.search.less'         					 => array( 'media' => 'all' ),
	),
	'dependencies' => array(
		'mediawiki.special.search',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'top',	
);
//$wgResourceModules['mediawiki.special.search']['styles'] = [];

//commonstyle
$wgResourceModules['socialprofile.commonstyle.css'] = array(
	'styles' => array('/CommonStyle/jcolor.min.css',
			'/CommonStyle/CommonStyle.css'),
    'dependencies' => array(
                    'skins.bootstrapmediawiki.top',
                    'oojs-ui'
                    ),
	'localBasePath' => __DIR__,
	'remoteBasePath' => &$GLOBALS['wgStyleDirectory'],
	'position' => 'top',
);

$wgResourceModules['ext.socialprofile.commonstyle.js'] = array(
	'scripts' =>array(
		'jcolor.min.js',
		'CommonStyle.js',
		'palette.js'
	),
	'templates' => array(
		'page1.mustache' =>  '/pages/page1.mustache',
		'page2.mustache' =>  '/pages/page2.mustache',
		'page3.mustache' =>  '/pages/page3.mustache',
		'page4.mustache' =>  '/pages/page4.mustache',
		'page5.mustache' =>  '/pages/page5.mustache',
		'page6.mustache' =>  '/pages/page6.mustache',
		'dummypage.mustache' => '/pages/dummypage.mustache',
		// 'page3.mustache' =>  '/pages/page3.mustache',
		// 'page4.mustache' =>  '/pages/page4.mustache',
		// 'page5.mustache' =>  '/pages/page5.mustache',
		// 'page6.mustache' =>  '/pages/page6.mustache',
	),
	'dependencies' => array(
		'mediawiki.notification',
		'oojs-ui'
	),
	'localBasePath' => __DIR__.'/CommonStyle',
	'remoteBasePath' => 'skins/bootstrap-mediawiki/CommonStyle',
	'position' => 'bottom',
);
$wgResourceModules['skins.bootstrapmediawiki.special.less'] = array(
	'styles' => array(
		$skinDir . '/less/local/huiji.special.less',
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
	'oojs-ui-core' => $skinDir.'/less/oojs-ui/oojs-ui-core-mediawiki.less',
	'oojs-ui-widgets' => $skinDir.'/less/oojs-ui/oojs-ui-widgets-mediawiki.less',
	'oojs-ui-toolbars' => $skinDir.'/less/oojs-ui/oojs-ui-toolbars-mediawiki.less',
	'oojs-ui-windows' => $skinDir.'/less/oojs-ui/oojs-ui-windows-mediawiki.less',
	'ext.flow.styles.base' => $skinDir.'/less/flow/huiji.flow.less',
 	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],

);
require_once( "$IP/skins/bootstrap-mediawiki/CommonStyle/CommonStyle_AjaxFunctions.php" );
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
$wgHooks['LoadExtensionSchemaUpdates'][] = 'BootstrapMediawikiHooks::onLoadExtensionSchemaUpdates';
$wgHooks['ImportSources'][] = 'BootstrapMediawikiHooks::onImportSources';
$wgHooks['ContentHandlerDefaultModelFor'][] = 'BootstrapMediawikiHooks::onContentHandlerDefaultModelFor';
$wgHooks['ParserSectionCreate'][] = 'BootstrapMediawikiHooks::onParserSectionCreate';
//API
$wgAPIModules['commonstyle'] = "ApiCommonStyle";

//Log Type
$wgLogTypes[]                    = 'CommonStyle';
$wgLogNames['CommonStyle']           = 'commonstylepage';
$wgLogHeaders['CommonStyle']         = 'commonstylepagetext';
$wgLogActions['CommonStyle/addDescription'] = 'commonstylelogentry';
$wgLogActions['CommonStyle/setSiteProperty'] = 'commonstylelogentry';
// new permission
$wgAvailableRights[] = 'quickpurge';
$wgGroupPermissions['sysop']['quickpurge'] = true;
$wgAvailableRights[] = 'quickdebug';
$wgGroupPermissions['sysop']['quickdebug'] = true;

//Register modules in VE
$wgVisualEditorPluginModules[]='skins.bootstrapmediawiki.huiji.ve';
//Less Path
