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
$wgAutoloadClasses['HuijiSkinTemplate'] = __DIR__ . '/HuijiSkinTemplate.php';
$wgAutoloadClasses['CUtf8_PY'] = __DIR__ . '/CUtf8_PY.php';
$wgMessagesDirs['bootstrapmediawiki'] = __DIR__ . '/i18n';
$skinDirParts = explode( DIRECTORY_SEPARATOR, __DIR__ );
$skinDir = array_pop( $skinDirParts );
$src = '/var/www/src';
$wgResourceModules['skins.bootstrapmediawiki.top'] = array(
	'styles' => array(
		$skinDir . '/bootstrap/css/bootstrap.min.css'            => array( 'media' => 'all' ),
		$skinDir . '/google-code-prettify/prettify.css'          => array( 'media' => 'all' ),
		$skinDir . '/css/fonts.css'                              => array( 'media' => 'all' ),
		$skinDir . '/style.css'                                  => array( 'media' => 'all' ),
		$skinDir . '/default_theme.less'                         => array( 'media' => 'all' ),
		$skinDir . '/style.less'                                 => array( 'media' => 'all' ),
		$skinDir . '/css/huiji.ext.css'                          => array( 'media' => 'all' ),
	),
	'scripts' => array(
		$skinDir . '/bootstrap/js/bootstrap.js',		
		$skinDir . '/js/fastclick.js',
	),
	'dependencies' => array(
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'top',
);
$wgResourceModules['skins.bootstrapmediawiki.bottom'] = array(
	'scripts' => array(
		$skinDir . '/google-code-prettify/prettify.js',
		$skinDir . '/js/jquery.ba-dotimeout.min.js',
		$skinDir . '/js/huiji.preload.js',
		$skinDir . '/js/huiji.flow.js',
		$skinDir . '/js/huiji.collectPageViewRecord.js',
		$skinDir . '/js/huiji.ready.js',
	),
	'styles' => array(
		$skinDir . '/css/huiji.ready.css'                                  => array( 'media' => 'all' ),
	),
	'dependencies' => array(
		'mediawiki.cookie',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'bottom',	
);
$wgResourceModules['skins.frontpage'] = array(
	'styles' => array(
		$skinDir . '/css/style.css'         					 => array( 'media' => 'all' ),
		$skinDir . '/css/slicebox.css'                           => array( 'media' => 'all' ),
	),
	'scripts' => array(
		$skinDir . '/js/modernizr.custom.46884.js',
		$skinDir . '/js/jquery.lazyload.min.js',
		$skinDir . '/js/jquery.slicebox.js',
		$skinDir . '/js/effect.js',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
	'position' => 'top',
);
/* Resource for wiki editor */
$wgResourceModules['ext.wikieditor.huijiextra'] = array(
	'scripts' => array(
		$skinDir . '/sisyphus/sisyphus.js',
		$skinDir . '/js/huiji.editor.js',
	), 
	'styles' => array(
		$skinDir . '/css/editor.css'         					 => array( 'media' => 'all' ),
	),	
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
