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
$wgMessagesDirs['bootstrapmediawiki'] = __DIR__ . '/i18n';

$skinDirParts = explode( DIRECTORY_SEPARATOR, __DIR__ );
$skinDir = array_pop( $skinDirParts );
$src = '/var/www/src';

$wgResourceModules['skins.bootstrapmediawiki'] = array(
	'styles' => array(
		$skinDir . '/bootstrap/css/bootstrap.min.css'            => array( 'media' => 'all' ),
		$skinDir . '/google-code-prettify/prettify.css'          => array( 'media' => 'all' ),
	    $skinDir . '/style.css'                                  => array( 'media' => 'all' ),
		$skinDir . '/default_theme.less'                         => array( 'media' => 'all' ),
		$skinDir . '/style.less'                                 => array( 'media' => 'all' ),
	),
	'scripts' => array(
		$skinDir . '/bootstrap/js/bootstrap.js',
		$skinDir . '/google-code-prettify/prettify.js',
		$skinDir . '/js/jquery.cookie.js',
		$skinDir . '/js/jquery.ba-dotimeout.min.js',
		$skinDir . '/js/flow.js',
		$skinDir . '/js/behavior.js',
	),
	'dependencies' => array(
		'jquery',
		'jquery.mwExtension',
		'jquery.client',
		//'jquery.cookie',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath'  => &$GLOBALS['wgStyleDirectory'],
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
);


if ( isset( $wgSiteJS ) ) {
	$wgResourceModules['skins.bootstrapmediawiki']['scripts'][] = $skinDir . '/' . $wgSiteJS;
}//end if

if ( isset( $wgSiteCSS ) ) {
	$wgResourceModules['skins.bootstrapmediawiki']['styles'][] = $skinDir . '/' . $wgSiteCSS;
}//end if


//update page's cache
$wgHooks['NewRevisionFromEditComplete'][] = 'updatePageRow';
/**
 * Update page's cache when someone edit the page(Admin,subnav,footer)
 */
function updatePageRow( $article, $revision, $baseRevId ) {
	global $wgUser, $wgMemc, $wgParser;

	if ( $article->getTitle()->getFullText() === '首页/Admin' 
		|| $article->getTitle()->getFullText() === 'Bootstrap:TitleBar' 		
		|| $article->getTitle()->getFullText() === 'Bootstrap:Footer' 
		|| $article->getTitle()->getFullText() === 'Bootstrap:Subnav' ){
		$option = new ParserOptions($wgUser);
    	$key = wfMemcKey( 'page', 'getPageRaw', 'all', $article->getTitle()->getFullText() );
		$output = $wgParser->preprocess($article->getRawText(), $article->getTitle(), $option );
		$wgMemc->set( $key, $output );
		return true;
	}
	
}
