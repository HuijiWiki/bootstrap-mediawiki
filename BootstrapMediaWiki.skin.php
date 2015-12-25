<?php
/**
 * Bootstrap - A basic MediaWiki skin based on Twitter's excellent Bootstrap CSS framework
 *
 * @Version 1.0.0
 * @Author Matthew Batchelder <borkweb@gmail.com>
 * @Copyright Matthew Batchelder 2012 - http://borkweb.com/
 * @License: GPLv2 (http://www.gnu.org/copyleft/gpl.html)
 */
if ( ! defined( 'MEDIAWIKI' ) ) {
    die( -1 );
}//end if
//File removed on new mediawiki versions (1.24.1 at least).
//require_once('includes/SkinTemplate.php');
if(file_exists('includes/SkinTemplate.php')){
    require_once('includes/SkinTemplate.php');
}
/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinBootstrapMediaWiki extends SkinTemplate {
    /** Using Bootstrap */
    public $skinname = 'bootstrap-mediawiki';
    public $stylename = 'bootstrap-mediawiki';
    public $template = 'BootstrapMediaWikiTemplate';
    public $useHeadElement = true;
    /**
     * initialize the page
     */
    public function initPage( OutputPage $out ) {
        global $wgSiteJS, $wgHuijiPrefix, $wgSiteNotice, $wgCentralServer;
        // set site notice programatically.
        $wgSiteNotice = BootstrapMediaWikiTemplate::getPageRawText('huiji:MediaWiki:Sitenotice');
        parent::initPage( $out );
        if (($wgHuijiPrefix === 'slx.test' || $wgHuijiPrefix === 'test' || $wgHuijiPrefix === 'zs.test' || $wgHuijiPrefix === 'www' ) && ($this->getSkin()->getTitle()->isMainPage()) ){
            $out->addScript('//cdn.bootcss.com/three.js/r73/three.min.js');
            $out->addModules( 'skins.frontpage');
            $out->addMeta( 'description', '灰机wiki是关注动漫游戏影视等领域的兴趣百科社区，追求深度、系统、合作，你也可以来创建和编写。在这里邂逅与你频率相同的“机”友，构建你的专属兴趣世界，不受束缚的热情创造。贴吧大神、微博达人、重度粉、分析狂人、考据党都在这里！');
            $out->addHeadItem( 'canonical',
                '<link rel="canonical" href="'.$wgCentralServer.'" />' . "\n");    
            //$out->addHeadItem('meta','<meta property="qc:admins" content="6762163113460512167131" />'); 
            //$out->addHeadItem('meta','<meta property="wb:webmaster" content="913ad381cb9b4ad7" />');

        } else {
            $out->addHeadItem( 'canonical',
                '<link rel="canonical" href="' . htmlspecialchars( $out->getTitle()->getCanonicalURL()) . '" />' . "\n");            
        } 
        $out->addModules( 
            array('skins.bootstrapmediawiki.bottom')
        ); # add js and messages  
        $out->addModuleScripts( 'skins.bootstrapmediawiki.top' );          
        if ($wgHuijiPrefix !== 'www' && $wgHuijiPrefix !== 'test'){
            $out->setHTMLTitle( $out->getHTMLTitle() . ' - 灰机wiki' );
        } else {
            $out->addModules( 'skins.bootstrapmediawiki.huiji.globalsearch');
        }
        $out->addMeta( 'viewport', 'width=device-width, initial-scale=1, maximum-scale=1' );
    }//end initPage
    /**
     * prepares the skin's CSS
     */
    public function setupSkinUserCss( OutputPage $out ) {
        global $wgSiteCSS, $wgHuijiPrefix;
        parent::setupSkinUserCss( $out );
        if (($wgHuijiPrefix === 'slx.test' || $wgHuijiPrefix === 'test' || $wgHuijiPrefix === 'zs.test' || $wgHuijiPrefix === 'www' ) && ($this->getSkin()->getTitle()->isMainPage()) ){
            $out->addModuleStyles( 'skins.frontpage' );  
        }
        $out->addModuleStyles( array('skins.bootstrapmediawiki.top','mediawiki.ui.button') );
        // we need to include this here so the file pathing is right
        $out->addStyle( '//cdn.bootcss.com/font-awesome/4.4.0/css/font-awesome.min.css' );
    }//end setupSkinUserCss
}
/**
 * @package MediaWiki
 * @subpackage Skins
 */
class BootstrapMediaWikiTemplate extends HuijiSkinTemplate {
    /**
     * @var Cached skin object
     */
    public $skin;
    /**
     * Template filter callback for Bootstrap skin.
     * Takes an associative array of data set from a SkinTemplate-based
     * class, and a wrapper for MediaWiki's localization database, and
     * outputs a formatted page.
     *
     * @access private
     */
    public function execute() {
        global $wgRequest, $wgUser, $wgSitename, $wgSitenameshort, $wgCopyrightLink, $wgCopyright, $wgBootstrap, $wgArticlePath, $wgGoogleAnalyticsID, $wgSiteCSS;
        global $wgEnableUploads;
        global $wgLogo, $wgHuijiPrefix, $wgFavicon;
        global $wgTOCLocation;
        global $wgNavBarClasses;
        global $wgSubnavBarClasses;
        global $wgParser, $wgTitle, $wgEmailAuthentication;
        $wgFavicon = (new wSiteAvatar($wgHuijiPrefix, 'l'))->getAvatarImage();
        $this->skin = $this->data['skin'];
        $action = $wgRequest->getText( 'action' );
        $url_prefix = str_replace( '$1', '', $wgArticlePath );
        $NS = $wgTitle->getNamespace();
        // Suppress warnings to prevent notices about missing indexes in $this->data
        wfSuppressWarnings();
        $this->html('headelement');
        if ($wgUser->isLoggedIn()){
            $usf = new UserSiteFollow();
            $followed = ($usf->checkUserSiteFollow($wgUser, $wgHuijiPrefix) !== false);         
        }else{
            $followed = false;
        }
        ?>
        <!--[if lt IE 8]>
            <p class="alert alert-warning alert-dismissible browsehappy">
              你正在使用一个<strong>过时</strong>的浏览器。请<a class="link" href="http://browsehappy.com">升级你的浏览器</a>以查看此页面。</p>
            </p>
        <![endif]-->
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-10190882-3', 'auto');
            ga('send', 'pageview');
        </script>
        <div id="wrapper" class="toggled">
        <script>
            var menutoggle;
            document.domain = getDomainName(window.location.host);
            menutoggle = localStorage.getItem("menu-toggle");
            document.getElementById('wrapper').className = menutoggle;
            function getDomainName(hostName)
            {
                return hostName.substring(hostName.lastIndexOf(".", hostName.lastIndexOf(".") - 1) + 1);
            }
        </script>
        <?php echo $this->showHeader(); ?>
        <script>
            var classlst;
            var x = 0;
            classlst = document.getElementById('wrapper').className.split(/\s+/);
            for(x in classlst) {
                if(classlst[x] == 'toggled') {
                    var list = document.getElementById('menu-toggle').className;
                    var blank,added;
                    blank = (list!='')?' ':'';
                    added = list + blank + 'menu-active';
                    document.getElementById('menu-toggle').className = added;
                }
            }
        </script>
        <?php if (($wgHuijiPrefix === 'slx.test' ||$wgHuijiPrefix === 'test' || $wgHuijiPrefix === 'zs.test' || $wgHuijiPrefix === 'www') && ($this->getSkin()->getTitle()->isMainPage()) ){
            include 'View/Sidebar.php';
            echo FrontPage::showPage();
            include 'View/Modal.php';
        } else {?>
            
            <?php include 'View/Sidebar.php';?>

            <div id="wiki-outer-body">

                <div id="content-actions" class="subnav subnav-fixed">
                    <div class="container-fluid">
                        <ul class="nav nav-pills">
                            <li>
                                <a class="navbar-brand logo-wiki-user" href="<?php echo $this->data['nav_urls']['mainpage']['href'] ?>" title="<?php echo $wgSitename ?>"><?php echo (new wSiteAvatar($wgHuijiPrefix, 'm'))->getAvatarHtml(array('style' => 'height : 1em; padding-bottom:0.2em;')); echo '&nbsp;'.($wgSitenameshort ?$wgSitenameshort: $wgSitename); ?></a>
                            </li>
                            <li><span id="user-site-follow" class="mw-ui-button <?php echo $followed?'':'mw-ui-progressive' ?><?php echo $followed?'unfollow':'' ?> "><?php echo $followed?'取消关注':'<span class="glyphicon glyphicon-plus"></span>关注' ?></span> </li>
                            <?php echo $this->nav( $this->get_page_links( 'Bootstrap:Subnav' ) ); ?>
                            <li class="site-count"><p><a id="site-article-count" href="<?php echo $url_prefix; ?>Special:AllPages"><?php
                                $result = self::format_nice_number(SiteStats::articles());
                                $result2 = self::format_nice_number(SiteStats::edits());
                                echo $result;
                            ?></a>页面<a href="/wiki/Special:RecentChanges"><?php echo $result2; ?></a>编辑<a id="site-follower-count" data-toggle="modal" data-target=".follow-msg"><?php echo self::format_nice_number(UserSiteFollow::getFollowerCount($wgHuijiPrefix)) ?></a>关注</p></li>
                            <span id="subnav-toggle"><i class="fa fa-ellipsis-h"></i></span>
                        </ul>
                    </div>
                </div>

                <div id="wiki-body" class="container">
                    <div id="content">
                        <div class="row">
                            <nav class="hidden-md hidden-sm hidden-xs hidden-print toc-sidebar" role="complementary navigation"></nav>
                            <section class="col-md-12 wiki-body-section" role="main">



                        <div id="firstHeading" class="pagetitle page-header">
                            <div class="pull-right"><?php if ( $this->data['isarticle'] ) { echo $this->getIndicators();} ?> </div>
                            <h1><?php $this->html( 'title' ) ?> 
                                <?php 
                                    if (isset( $this->data['content_actions']['edit']) ){
                                        $isVisualEditorEnabled = $wgUser->getOption('visualeditor-enable','1');
                                        $editHref = $this->data['content_actions']['edit']['href'];
                                        $veHref = $this->data['content_actions']['ve-edit']['href'];
                                        if ($isVisualEditorEnabled == 1 && isset($this->data['content_actions']['ve-edit'])){ ?>
                                            <div id="huiji-h1-edit-button" class="huiji-h1-edit-button">

                                                <a id="ca-ve-edit" href="<?php echo $veHref; ?>" class="icon-pencil" data-toggle="tooltip" data-placement="top" title="使用可视化编辑器"></a>
                                                <span class="mw-editsection-divider"></span>
                                                <a id="ca-edit" href="<?php echo $editHref ?>" class="icon-edit-code " data-toggle="tooltip" data-placement="top" title="使用源代码编辑器"></a>
                                            </div>
                                        <?php } else { ?>
                                            <div id="huiji-h1-edit-button" class="huiji-h1-edit-button">
                                                <a id="ca-edit" href="<?php echo $editHref ?>" class="icon-edit-code" title="<?php echo wfMsg('bootstrap-mediawiki-view-edit'); ?>"></a>
                                            </div>                                   
                                        <?php }
                                    } ?>
                               
                                <div id="contentSub">
                                    <small>
                                    <?php $this->html('subtitle') ?>
                                    <?php
                                        if ($this->data['isarticle'] &&  !($this->skin->getTitle()->isMainPage()) && $this->skin->getTitle()->exists() && $action == 'view'){
                                            $rev = Revision::newFromTitle($this->skin->getTitle());
                                            $revId = $rev->getId();
                                            $editorId = $rev->getUser();
                                            if ($editorId !== 0){
                                                $linkAttr = array('class' => 'mw-ui-anchor mw-ui-progressive mw-ui-quiet');
                                                $editor = User::newFromId( $editorId );
                                                $editorName = $editor->getName();
                                                $editorLink = Linker::Link($editor->getUserPage(), $editorName, $linkAttr);
                                                $bjtime = strtotime( $rev->getTimestamp() ) + 8*60*60;
                                                $edittime = HuijiFunctions::getTimeAgo( $bjtime );
                                                $diff = SpecialPage::getTitleFor('Diff',$revId);
                                                $diffLink = Linker::LinkKnown($diff,'修改',$linkAttr);
                                                echo $editorLink.'&nbsp于'.$edittime.'前'.$diffLink.'了此页面';
                                            }
                                            // $revPageId = $this->getSkin()->getTitle()->getArticleId();
                                            // $editinfo = UserStats::getLastEditer($revPageId,$wgHuijiPrefix);
                                            // $userPage = Title::makeTitle( NS_USER, $editinfo['rev_user_text'] );
                                            // $userPageURL = htmlspecialchars( $userPage->getFullURL() );
                                            // $bjtime = strtotime( $editinfo['rev_timestamp'] ) + 8*60*60;
                                            // $edittime = HuijiMiddleware::getTimeAgo( $bjtime );
                                            
                                            echo '<div class="bdsharebuttonbox pull-right hidden-sm hidden-xs" data-tag="share_2"><a href="#" class="icon-weixin-share" data-tag="share_2" data-cmd="weixin" title="分享到微信"></a><a href="#" class="icon-weibo-share" data-tag="share_2" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="icon-qqspace-share" data-tag="share_2" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="icon-tieba-share" data-tag="share_2" data-cmd="tieba" title="分享到百度贴吧"></a><a href="#" class="icon-douban-share" data-tag="share_2" data-cmd="douban" title="分享到豆瓣网"></a></div>';
                                        }
                                    ?>
                                    </small>
                                </div>
                                
                            </h1>
                        </div>
                        <?php if ( $this->data['isarticle'] ) { ?><div id="siteSub" class="alert alert-info visible-print-block" role="alert"><?php $this->msg( 'tagline' ); ?></div><?php } ?>
                        <!-- ConfirmEmail -->
                        <?php
                            if ( $wgUser->isLoggedIn()&&!$wgUser->isEmailConfirmed() && !($this->getSkin()->getTitle()->isMainPage()) ) {
                        ?>
                        <div class="alert alert-danger" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <span class="sr-only">Error:</span>
                            只有确认邮件后才能对页面进行编辑&nbsp:)
                            <a href="/wiki/%E7%89%B9%E6%AE%8A:%E7%A1%AE%E8%AE%A4%E7%94%B5%E5%AD%90%E9%82%AE%E4%BB%B6">点此确认</a>&nbsp|&nbsp
                            <a href="/wiki/特殊:修改邮箱地址">修改邮箱地址</a>
                        </div> 
                        <?php
                            }
                        ?>  
                        <!-- /ConfirmEmail -->
                        <?php if ( $this->data['undelete'] ): ?>
                        <!-- undelete -->
                        <div id="contentSub2" class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?php $this->html( 'undelete' ) ?>
                        </div>
                        <!-- /undelete -->
                        <?php endif; ?>
                        <?php if($this->data['newtalk'] ): ?>
                        <!-- newtalk -->
                        <div class="usermessage"><?php $this->html( 'newtalk' )  ?></div>
                        <!-- /newtalk -->
                        <?php endif; ?>
                        <div id="bodyContent" class="body">                     
                        <?php $this->html( 'bodytext' ) ?>
                        </div>
                        <?php if ( $this->data['catlinks'] ): ?>
                        <div class="category-links">
                        <!-- catlinks -->
                        <?php $this->html( 'catlinks' ); ?>
                        <!-- /catlinks -->
                        </div>
                        <?php endif; ?>
                        <div class="bdsharebuttonbox pull-right" data-tag="share_1"><a href="#" class="icon-weixin-share hidden-xs hidden-sm" data-tag="share_1" data-cmd="weixin" title="分享到微信"></a><a href="#" class="icon-weibo-share" data-tag="share_1" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="icon-qqspace-share" data-tag="share_1" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="icon-tieba-share" data-tag="share_1" data-cmd="tieba" title="分享到百度贴吧"></a><a href="#" class="icon-douban-share" data-tag="share_1" data-cmd="douban" title="分享到豆瓣网"></a></div>
                        <?php 
                        if ($this->data['isarticle'] &&  !($this->getSkin()->getTitle()->isMainPage()) && $this->getSkin()->getTitle()->exists()){
                            $commentHtml = '<div class="clearfix"></div>';
                            $wgParser->setTitle($this->getSkin()->getTitle());
                            $commentHtml .= CommentsHooks::displayComments( '', array(), $wgParser); 
                            echo $commentHtml;
                        }?>
                        <?php if ( $this->data['dataAfterContent'] ): ?>
                        <div class="data-after-content">
                        <!-- dataAfterContent -->
                        <?php $this->html( 'dataAfterContent' ); ?>                    
                        <!-- /dataAfterContent -->
                        </div>
                        <?php endif; ?>
                        <?php
                            if ( 'sidebar' == $wgTOCLocation ) {
                                ?>
                                </section></section>
                                <?php
                            }//end if
                        ?>
                    </div>
                </div><!-- container -->
            </div>
            <?php include ('View/Modal.php'); ?>
            <div class="bottom">
                <div class="container">
                    <?php self::includePage('Bootstrap:Footer'); ?>
                    <?php if( $this->data['sitenotice'] ) { ?>
                        <div id="siteNotice" class="site-notice">
                            <?php $this->html('sitenotice') ?>
                        </div>
                    <?php } ?>
                    <footer>
                        <p class="text-center">
                            <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://www.huiji.wiki/wiki/%E7%81%B0%E6%9C%BA%E5%81%9C%E6%9C%BA%E5%9D%AA">灰机停机坪</a> |
                            <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://www.huiji.wiki/wiki/%E7%BB%B4%E5%9F%BA%E5%AE%B6%E5%9B%AD%E8%AE%A1%E5%88%92">维基家园计划</a> |
                            <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://www.huiji.wiki/wiki/%E5%AE%87%E5%AE%99%E5%B0%BD%E5%A4%B4%E7%9A%84%E7%81%B0%E6%9C%BAwiki">关于灰机wiki</a> |
                            <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://www.huiji.wiki/wiki/%E7%81%B0%E6%9C%BAwiki:%E4%BD%BF%E7%94%A8%E6%9D%A1%E6%AC%BE%E5%92%8C%E5%86%85%E5%AE%B9%E5%A3%B0%E6%98%8E">使用条款和声明</a> |
                            <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://www.huiji.wiki/wiki/%E7%81%B0%E6%9C%BAwiki:%E7%94%A8%E6%88%B7%E7%BC%96%E8%BE%91%E6%9D%A1%E6%AC%BE">编辑条款</a><br>Powered by
                            <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://mediawiki.org">MediaWiki</a> <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://www.miitbeian.gov.cn/">京ICP备15015138号</a></p>
                    </footer>
                </div><!-- container -->
            </div><!-- bottom -->
        </div><!-- /#wrapper -->
        <?php }?> <!-- mainpage if end -->
        <?php
        $this->html('bottomscripts'); /* JS call to runBodyOnloadHook */
        $this->html('reporttime');
        if ( $this->data['debug'] ) {
            ?>
            <!-- Debug output:
            <?php $this->text( 'debug' ); ?>
            -->
            <?php
        }//end if
        ?>
        <script>
        (function(){
            var bp = document.createElement('script');
            bp.src = '//push.zhanzhang.baidu.com/push.js';
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(bp, s);
        })();
        </script>
        <script>with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>        
        </body>
        </html>
        <?php
    }//end execute
}
