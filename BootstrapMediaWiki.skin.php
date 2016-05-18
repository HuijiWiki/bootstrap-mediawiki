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
        global $wgHuijiPrefix, $wgSiteNotice, $wgCentralServer, $wgUser, $wgThanksConfirmationRequired, $wgHasComments;
        // set site notice programatically.
        $wgSiteNotice = BootstrapMediaWikiTemplate::getPageRawText('huiji:MediaWiki:Sitenotice');
        parent::initPage( $out );
        if (! $wgUser->isLoggedIn() && $wgHuijiPrefix != 'lotr'){
            $out->addHeadItem( 'ads', '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <script>
              (adsbygoogle = window.adsbygoogle || []).push({
                google_ad_client: "ca-pub-4790099329067811",
                enable_page_level_ads: true
              });
            </script>');
        }
        if (($wgHuijiPrefix === 'slx.test' || $wgHuijiPrefix === 'test' || $wgHuijiPrefix === 'zs.test' || $wgHuijiPrefix === 'www' ) && ($this->getSkin()->getTitle()->isMainPage()) ){
            $out->addModuleScripts( 'skins.frontpage');
            $out->addMeta( 'description', '灰机wiki是关注动漫游戏影视等领域的兴趣百科社区，追求深度、系统、合作，你也可以来创建和编写。在这里邂逅与你频率相同的“机”友，构建你的专属兴趣世界，不受束缚的热情创造。贴吧大神、微博达人、重度粉、分析狂人、考据党都在这里！');
            $out->addMeta( 'keywords', '维基, 百科, wiki');
            $out->addHeadItem( 'canonical',
                '<link rel="canonical" href="'.$wgCentralServer.'" />' . "\n");    
            //$out->addHeadItem('meta','<meta property="qc:admins" content="6762163113460512167131" />'); 
            //$out->addHeadItem('meta','<meta property="wb:webmaster" content="913ad381cb9b4ad7" />');

        } else {
            $site = WikiSite::newFromPrefix($wgHuijiPrefix);
            if ($this->getSkin()->getTitle()->isMainPage()){
                $out->addMeta( 'description', $site->getDescription());
                $out->addMeta( 'keywords', $site->getName().', 维基, 百科, wiki');
            } else {
               $out->addMeta( 'keywords', $site->getName().', '.$this->getSkin()->getTitle()->getText().', 维基, 百科, wiki'); 
            }
            $out->addHeadItem( 'canonical',
                '<link rel="canonical" href="' . htmlspecialchars( $out->getTitle()->getCanonicalURL()) . '" />' . "\n");            
        } 
         # add js and messages  
        $out->addModuleScripts( 'skins.bootstrapmediawiki.top' );          
        if ($this->getSkin()->getTitle()->hasSourceText() &&  !($this->getSkin()->getTitle()->isMainPage())
            && $this->getSkin()->getTitle()->exists() && $this->getRequest()->getText('action') == ''
            && class_exists( 'EchoNotifier' ) && $this->getSkin()->getUser()->isLoggedIn() 
        ){
            $out->addModules( array( 'ext.thanks.revthank' ) );
            $out->addJsConfigVars( 'thanks-confirmation-required',
                $wgThanksConfirmationRequired 
            );
        }
        if ($this->getSkin()->getTitle()->exists() 
            && $this->getSkin()->getTitle()->isContentPage() 
            && $this->getRequest()->getText('action') == '' 
            && !($this->getSkin()->getTitle()->isMainPage())
        ){
            $out->addModules( array( 'skins.bootstrapmediawiki.content' ) );
        }
        $out->addMeta( 'viewport', 'width=device-width, initial-scale=1, maximum-scale=1' );
    }//end initPage
    /**
     * prepares the skin's CSS
     */
    public function setupSkinUserCss( OutputPage $out ) {
        global $wgHuijiPrefix;
        parent::setupSkinUserCss( $out );
        if (($wgHuijiPrefix === 'slx.test' || $wgHuijiPrefix === 'test' || $wgHuijiPrefix === 'zs.test' || $wgHuijiPrefix === 'www' ) && ($this->getSkin()->getTitle()->isMainPage()) ){
            $out->addModuleStyles( 'skins.frontpage' );  
        }

        $out->addModuleStyles( array('skins.bootstrapmediawiki.top','mediawiki.ui.button') );
        // we need to include this here so the file pathing is right
        $out->addStyle( '//cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css' );
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
        global $wgRequest, $wgUser, $wgSitename, $wgSitenameshort, $wgCopyrightLink, $wgCopyright, $wgBootstrap, $wgArticlePath, $wgGoogleAnalyticsID, $wgSiteCSS, $wgLang;
        global $wgEnableUploads;
        global $wgLogo, $wgHuijiPrefix, $wgFavicon, $wgCdnHuijiSuffix;
        global $wgTOCLocation, $wgHasComments, $wgMobile;
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
        $site = WikiSite::newFromPrefix($wgHuijiPrefix);
        $stats = $site->getStats();
        if ($wgUser->isLoggedIn()){         
            $followed = $site->isFollowedBy($wgUser);        
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
            ga('create', 'UA-10190882-3', 'auto', {allowLinker: true});
            ga('require', 'linker');
            ga('linker:autoLink', ['huiji.wiki','huijiwiki.com']);
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
            
            <?php include 'View/Sidebar.php';
            if ( $this->isPrimaryContent() || $this->getSkin()->getTitle()->isMainPage() ){
            	$customClass = " class='huiji-css-hook'";
            }
            ?>

            <div id="wiki-outer-body"<?php echo $customClass;?>>

                <nav id="content-actions" class="subnav subnav-fixed">
                    <div class="container-fluid">
                        <ul class="nav nav-pills">
                            <li>
                                <a class="navbar-brand logo-wiki-user" href="<?php echo $this->data['nav_urls']['mainpage']['href'] ?>" title="<?php echo $wgSitename ?>"><?php echo $site->getAvatar('m')->getAvatarHtml(array('style' => 'height : 1em; padding-bottom:0.2em;')); echo '&nbsp;'.$wgLang->truncate( $site->getName(), 30); ?></a>
                            </li>
                            <li><span id="user-site-follow" class="mw-ui-button <?php echo $followed?'':'mw-ui-progressive' ?><?php echo $followed?'unfollow':'' ?> "><?php echo $followed?'取消关注':'<span class="glyphicon glyphicon-plus"></span>关注' ?></span> </li>
                            <?php echo $this->nav( $this->get_page_links( 'Bootstrap:Subnav' ) ); ?>
                            <li class="site-count"><p><span class="article-count"><a href="<?php echo $url_prefix; ?>Special:AllPages"><?php
                                $result = self::format_nice_number(SiteStats::articles());
                                $result2 = self::format_nice_number(SiteStats::edits());
                                echo $result;
                                //echo $site->getRating();
                            ?></a>条目</span><span class="edit-count"><a href="/wiki/Special:RecentChanges"><?php echo $result2; ?></a>编辑</span><span class="follower-count"><a id="site-follower-count" data-toggle="modal" data-target=".follow-msg"><?php echo $stats['followers'] ?></a>关注</p></span></li>
                            <span id="subnav-toggle"><i class="fa fa-ellipsis-h"></i></span>
                        </ul>
                    </div>
                </nav>

                <div id="wiki-body" class="container">
                    <main id="content">
                        <div class="row">
                            <aside class="hidden-md hidden-sm hidden-xs hidden-print toc-sidebar" role="complementary navigation"><div class="toc-ul-wrap"></div></aside>
                            <article class="col-md-12 wiki-body-section" role="main">
                                <?php if ($NS != 2 && !($this->getSkin()->getTitle()->isMainPage()) ):?>
                                <header id="firstHeading" class="page-header">
                                    <div class="pull-right"><?php if ( $this->data['isarticle'] ) { echo $this->getIndicators();} ?> </div>
                                    <h1><?php $this->html( 'title' ) ?></h1>
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
                                                        <a id="ca-edit" href="<?php echo $editHref ?>" class="icon-edit-code" title="<?php echo wfMessage('bootstrap-mediawiki-view-edit')->plain(); ?>"></a>
                                                    </div>                                   
                                                <?php }
                                            } ?>
                                                
                                    <div id="contentSub">
                                        <?php $this->html('subtitle') ?>
                                        <?php
                                            echo $this->getSub($NS);
                                        ?>
                                    </div>
                                        
                                </header>
                                <?php else:?>
                                <header id="firstHeading" class="void page-header">
                                     <div class="pull-right"><?php if ( $this->data['isarticle'] ) { echo $this->getIndicators();} ?> </div>
                                </header>
                                <?php endif;?><!-- end header -->
                                <?php if ( $this->data['isarticle'] ) { ?><div id="siteSub" class="alert alert-info visible-print-block" role="alert"><?php $this->msg( 'tagline' ); ?></div><?php } ?>
                                <!-- ConfirmEmail -->
                                <?php
                                    if ( $wgUser->isLoggedIn() && !wgMobile  && !$wgUser->isEmailConfirmed() && $this->isPrimaryContent() ) {
                                ?>
                                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                <!-- top aids -->
                                <ins class="adsbygoogle"
                                     style="display:inline-block;width:728px;height:90px"
                                     data-ad-client="ca-pub-4790099329067811"
                                     data-ad-slot="4487503881"></ins>
                                <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                                <section class="alert alert-danger" role="alert">
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    <span class="sr-only">Error:</span>
                                    只有确认邮件后才能对页面进行编辑&nbsp:)
                                    <a href="/wiki/%E7%89%B9%E6%AE%8A:%E7%A1%AE%E8%AE%A4%E7%94%B5%E5%AD%90%E9%82%AE%E4%BB%B6">点此确认</a>&nbsp|&nbsp
                                    <a href="/wiki/特殊:修改邮箱地址">修改邮箱地址</a>
                                </section> 
                                <?php
                                    }
                                ?>  
                                <!-- Not Logged in notice -->
                                <?php

                                    if ( !$wgUser->isLoggedIn() && !wgMobile && $this->isPrimaryContent() ) {
                                        $login = '
                                            <span data-toggle="modal" data-target=".user-login">
                                                <a rel="nofollow" class="login-in btn btn-default">登录</a>
                                            </span>
                                            <span>'.Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), '注册', array('rel' => 'nofollow', 'class'=>'btn btn-default'),array('type' => 'signup') ).'
                                            </span>';
                                ?>
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                <!-- top aids -->
                                <ins class="adsbygoogle"
                                     style="display:inline-block;width:728px;height:90px"
                                     data-ad-client="ca-pub-4790099329067811"
                                     data-ad-slot="4487503881"></ins>
                                <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                                <!--<section class="alert alert-warning hidden-xs hidden-sm" role="alert">
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    <span class="sr-only">Warning:</span>
                                    花1分钟创建用户后就能进行编辑&nbsp:)
                                    <?php echo $login; ?>
                                    <span class="pull-right">
                                        联合登陆&nbsp;&nbsp;
                                        <a href="https://api.weibo.com/oauth2/authorize?client_id=2445834038&amp;redirect_uri=http%3A%2F%2Fhuijiwiki.com%2Fwiki%2Fspecial%3Acallbackweibo&amp;response_type=code" class="icon-weibo-share" style="line-height: 2;"></a>&nbsp;&nbsp;
                                        <?php
                                            global $wgHuijiPrefix;
                                            echo '<a href="https://graph.qq.com/oauth2.0/authorize?response_type=code&amp;client_id=101264508&amp;state='.$wgHuijiPrefix.'&amp;redirect_uri=http%3a%2f%2fwww.huiji.wiki%2fwiki%2fspecial%3acallbackqq" class="icon-qq-share" style="line-height: 2;"></a>';
                                        ?>
                                    </span>
                                </section> -->
                                <?php
                                    }
                                ?>  
                                <!-- /Not Logged in notice -->
                                <!-- /ConfirmEmail -->
                                <?php if ( $this->data['undelete'] ): ?>
                                <!-- undelete -->
                                <section id="contentSub2" class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?php $this->html( 'undelete' ) ?>
                                </section>
                                <!-- /undelete -->
                                <?php endif; ?>
                                <?php if($this->data['newtalk'] ): ?>
                                <!-- newtalk -->
                                <section class="usermessage"><?php $this->html( 'newtalk' )  ?></section>
                                <!-- /newtalk -->
                                <?php endif; ?>
                                <section id="bodyContent" class="body">                     
                                <?php $this->html( 'bodytext' ) ?>
                                </section>
                                <?php if ( $this->data['catlinks'] ): ?>
                                <section class="category-links">
                                <!-- catlinks -->
                                <?php $this->html( 'catlinks' ); ?>
                                <!-- /catlinks -->
                                </section>
                                <?php endif; ?>
                                <?php if( $this->isPrimaryContent() ): ?>
                                    <div class="row">
                                    <?php wfRunHooks( 'SkinRatingData', array(&$this) );?>
                                    <div class="col-md-2 col-md-offset-6 bdsharebuttonbox pull-right" data-tag="share_1"><a href="#" class="icon-weixin-share hidden-xs hidden-sm" data-tag="share_1" data-cmd="weixin" title="分享到微信"></a><a href="#" class="icon-weibo-share" data-tag="share_1" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="icon-share-alt" data-tag="share_1" title="复制固定链接"></a></div>
                                    </div>
                                <?php endif; ?>
                                <?php 
                                if ( $this->isPrimaryContent() )
                                {
                                    $commentHtml = '<div class="clearfix"></div>';
                                    $wgParser->setTitle($this->getSkin()->getTitle());
                                    $commentHtml .= CommentsHooks::displayComments( '', array(), $wgParser); 
                                    echo $commentHtml;
                                }?>
                                <?php if ( $this->data['dataAfterContent'] ): ?>
                                <section class="data-after-content">
                                <!-- dataAfterContent -->
                                <?php $this->html( 'dataAfterContent' ); ?>                    
                                <!-- /dataAfterContent -->
                                </section>
                                <?php endif; ?>

                            </article>
                        </div>
      
                    </main>
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
                            <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" rel="nofollow" href="http://www.huiji.wiki/wiki/%E5%AE%87%E5%AE%99%E5%B0%BD%E5%A4%B4%E7%9A%84%E7%81%B0%E6%9C%BAwiki">关于灰机wiki</a> |
                            <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" rel="nofollow" href="http://www.huiji.wiki/wiki/%E7%81%B0%E6%9C%BAwiki:%E4%BD%BF%E7%94%A8%E6%9D%A1%E6%AC%BE%E5%92%8C%E5%86%85%E5%AE%B9%E5%A3%B0%E6%98%8E">使用条款和声明</a> |
                            <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" rel="nofollow" href="http://www.huiji.wiki/wiki/%E7%81%B0%E6%9C%BAwiki:%E7%94%A8%E6%88%B7%E7%BC%96%E8%BE%91%E6%9D%A1%E6%AC%BE">编辑条款</a><br>Powered by
                            <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" rel="nofollow" href="http://mediawiki.org">MediaWiki</a> <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" rel="nofollow" href="http://www.miitbeian.gov.cn/">京ICP备15015138号</a></p>
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
        <script>window._bd_share_config={
            common: {
                bdSnsKey: {},
                bdText: "",
                bdMini: "2",
                bdMiniList: false,
                bdPic: "",
                bdStyle: "2",
                bdUrl:"http://<?php echo $wgHuijiPrefix.$wgCdnHuijiSuffix; ?>/index.php?curid=<?php echo $this->skin->getTitle()->getArticleId(); ?>",
            },
            share: [
                {
                    tag: "share_1",
                    bdSize: 16,
                    bdCustomStyle:""
                },
                {
                    tag: "share_2",
                    bdSize: 16,
                    bdCustomStyle:""
                }
            ]
        };with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>        
        </body>
        </html>
        <?php
    }//end execute
}
