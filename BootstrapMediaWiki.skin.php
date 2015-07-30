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
        global $wgSiteJS, $wgHuijiPrefix;
        parent::initPage( $out );
        if (($wgHuijiPrefix === 'test' || $wgHuijiPrefix === 'home' || $wgHuijiPrefix === 'slx.test' ) && ($this->getSkin()->getTitle()->isMainPage()) ){
            $out->addModules( 'skins.frontpage');
        } 
        $out->addModules( 
            array('skins.bootstrapmediawiki.bottom',
                'skins.bootstrapmediawiki.top',
                )
        ); # add js and messages  
        //$out->addModuleScripts( 'skins.bootstrapmediawiki.top' );          
	$out->setHTMLTitle( $out->getHTMLTitle() . ' - 灰机wiki' );
	$out->addMeta( 'description', '灰机wiki是关注动漫游戏影视等领域的UGC兴趣百科社区。');
        $out->addMeta( 'viewport', 'width=device-width, initial-scale=1, maximum-scale=1' );
    }//end initPage
    /**
     * prepares the skin's CSS
     */
    public function setupSkinUserCss( OutputPage $out ) {
        global $wgSiteCSS, $wgHuijiPrefix;
        parent::setupSkinUserCss( $out );
        $out->addModuleStyles( 'skins.bootstrapmediawiki.top' ); 
        // we need to include this here so the file pathing is right
        $out->addStyle( 'bootstrap-mediawiki/font-awesome/css/font-awesome.min.css' );
    }//end setupSkinUserCss
}
/**
 * @package MediaWiki
 * @subpackage Skins
 */
class BootstrapMediaWikiTemplate extends BaseTemplate {
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
        global $wgLogo, $wgHuijiPrefix;
        global $wgTOCLocation;
        global $wgNavBarClasses;
        global $wgSubnavBarClasses;
        global $wgParser, $wgTitle;
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
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-10190882-3', 'auto');
            ga('send', 'pageview');
        </script>
        <div id="wrapper" class="toggled">
            <div class="modal alert-return">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <p></p>
                    </div>
                </div>
            </div>
        <!-- followed list -->
        <div class="modal fade follow-msg" tabindex="-1" role="dialog" aria-labelledby="mySmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">关注了本站的用户</h4>
                    </div>
                    <div class="modal-body">
                       <ul class="follow-modal">
                       </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade user-login" tabindex="-1" role="dialog" aria-labelledby="userLoginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="ModalLabel">登录</h4>
                    </div>
                    <div class="modal-body">
                        <div class="mw-ui-container login-wrap">
                            <div class="userloginprompt"></div>
                            <div class="userloginForm">
                                <form name="userlogin" class="mw-ui-vform" method="post">
                                    <section class="mw-form-header">
                                    </section>

                                    <div class="mw-ui-vform-field user-name">
                                        <label for="wpName1">
                                            用户名
                                        </label>
                                        <input id="login-user-name" class="loginText mw-ui-input form-control" type="text"  tabindex="1" size="20" placeholder="请输入你的用户名" name="wpName">
                                    </div>

                                    <div class="mw-ui-vform-field">
                                        <label for="wpPassword1">
                                            密码 <a href="/wiki/%E7%89%B9%E6%AE%8A:%E9%87%8D%E8%AE%BE%E5%AF%86%E7%A0%81" title="特殊:重设密码" class="mw-ui-flush-right">忘记密码？</a>
                                        </label>
                                        <input id="login-user-password" class="loginPassword mw-ui-input form-control"  tabindex="2" size="20" autofocus="" placeholder="请输入你的密码" type="password" name="wpPassword">
                                    </div>



                                    <div class="mw-ui-vform-field">
                                        <div class="">
                                            <input name="wpRemember" type="checkbox" value="1" id="wpRemember" tabindex="4" style="margin-right: 5px;"><label for="wpRemember">
                                                记住我的登录状态</label>
                                        </div>
                                    </div>

                                    <div class="mw-ui-vform-field">
                                        <input id="wpLoginAttempt" tabindex="6" class="mw-ui-button  mw-ui-block mw-ui-constructive" type="button" value="登录" name="wpLoginAttempt">
                                    </div>

<!--                                     <div class="mw-ui-vform-field" id="mw-userlogin-help">
                                        <a href="https://www.mediawiki.org/wiki/Special:MyLanguage/Help:Logging_in">登录帮助</a>
                                    </div> -->

                                    <div id="mw-createaccount-cta">
                                        没有账户？<?php echo Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), '注册', array('id' => 'pt-createaccount' ),array('type' => 'signup') ); ?>
                                    </div>
                                    <input type="hidden" name="wpLoginToken" value="5b59d95be44d1173971ec0b44d9fffa4">
                                </form>
                            </div>
                        </div>



                    </div>
<!--                    <div class="modal-footer">-->
<!--                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
<!--                    </div>-->
                </div>
            </div>
        </div>

<!--            <div class="alert-wrap">-->
<!--                <div class="alert" role="alert">good</div>-->
<!--            </div>-->
        <?php if (($wgHuijiPrefix === 'test' || $wgHuijiPrefix === 'home'  || $wgHuijiPrefix === 'slx.test') && ($this->getSkin()->getTitle()->isMainPage()) ){ 
            include ('frontpage.php');
        } else {?>
                <!-- Sidebar -->
            <div id="sidebar-wrapper">
                
                <ul class="sidebar-nav">
                    <li class="left-follow">
                        <ul>
                            <li>
                                <a class="navbar-brand logo-wiki-user" href="<?php echo $this->data['nav_urls']['mainpage']['href'] ?>" title="<?php echo $wgSitename ?>"><?php echo isset( $wgSiteLogo ) && $wgSiteLogo ? "<img src='{$wgSiteLogo}' alt='Logo'/> " : ''; echo $wgSitenameshort ?: $wgSitename; ?></a>
                            </li>
                            <li><button id="user-site-follow" class="mw-ui-button  <?php echo $followed?'':'mw-ui-progressive' ?><?php echo $followed?'unfollow':'' ?> "><?php echo $followed?'取消关注':'<span class="glyphicon glyphicon-plus"></span>关注' ?></button> </li>
                            <li><p><a id="site-article-count" href="<?php echo $url_prefix; ?>Special:AllPages"><?php 
                                $dbr = wfGetDB( DB_SLAVE );
                                $counter = new SiteStatsInit( $dbr );
                                $result = $counter->articles();
                                echo $result;
                            ?></a>篇条目，<span id="site-follower-count" data-toggle="modal" data-target=".follow-msg"><?php echo UserSiteFollow::getSiteCount($wgHuijiPrefix) ?></span>人关注。</p></li>
                        </ul>
                    </li>
                    <li class="sidebar-brand left-nav">
                        <a href="#">
                            站点导航
                        </a>
                        <ul>
                            <?php echo $this->nav( $this->get_page_links( 'Bootstrap:Subnav' ) ); ?>
                        </ul>
                    </li>
                    <li class="sidebar-brand left-tool">
                        <a href="#">
                            工具
                        </a>
                        <ul>
                            <li><a href="<?php echo $url_prefix; ?>Special:RecentChanges" class="recent-changes"><i class="fa fa-edit"></i> 最近更改</a></li>
                            <li><a href="<?php echo $url_prefix; ?>Special:Randompage" class="random-page"><i class="fa fa-random "></i> 随机页面</a></li>
                            <?php if ( $wgEnableUploads ) { ?>
                                <li><a href="<?php echo $url_prefix; ?>Special:Upload" class="upload-a-file"><i class="fa fa-upload"></i> 上传文件</a></li>
                            <?php } ?>
                            <li>
                                <a href="http://home.huiji.wiki/wiki/Help:编辑手册"><i class="fa fa-book"></i> 编辑手册</a>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-bar-chart"></i> 数据统计 <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $url_prefix; ?>Special:EditRank" class="bootstrap-subnav"><i class="fa fa-list-ol"></i> 本站编辑排行</a></li>
                                    <li><a href="<?php echo $url_prefix; ?>Special:TopUsers" class="bootstrap-subnav"><i class="fa fa-list-ol"></i> 等级积分排行</a></li>
                                    <li><a href="<?php echo $url_prefix; ?>Special:统计信息" class="bootstrap-subnav"><i class="fa fa-line-chart"></i> 统计信息</a></li>
                                    <li><a href="<?php echo $url_prefix; ?>Special:所有页面" class="bootstrap-subnav"><i class="fa fa-folder-open"></i> 所有页面</a></li>
                                    <li><a href="<?php echo $url_prefix; ?>Special:SpecialPages" class="special-pages"><i class="fa fa-star-o"></i> 特殊页面</a></li>
                                </ul>
                            </li>
                            <?php if ( $wgUser->isLoggedIn() && $wgUser->isAllowed('editinterface')){ ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-briefcase"></i> 管理员选项 <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $url_prefix; ?>Bootstrap:Subnav" class="bootstrap-subnav"><i class="fa fa-bars"></i> 修改站点导航</a></li>
                                    <li><a href="<?php echo $url_prefix; ?>Mediawiki:Common.css" class="bootstrap-subnav"><i class="fa fa-css3"></i> 修改站点CSS</a></li>
                                    <li><a href="<?php echo $url_prefix; ?>Mediawiki:Common.js" class="bootstrap-subnav"><i class="fa fa-code"></i> 修改站点js</a></li>
                                </ul>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php if (($NS==0 or $NS==1) and ($action != 'edit')) { ?>
                    <li class="sidebar-brand left-author">
                        <a href="#">
                            主要编辑者
                        </a>
                        <?php
                        $contrib = '{{#contributors:{{FULLPAGENAME}}}}';
                        $wgParserOptions = new ParserOptions($wgUser);
                        $parserOutput = $wgParser->parse($contrib, $this->getSkin()->getTitle(), $wgParserOptions);
                        echo $parserOutput->getText();
                        }
                        ?>
                    </li>

                    <?php if($this->data['language_urls']){ ?>
                    <li class="sidebar-brand left-lang">
                        <a href="#">
                            语言
                        </a>                        
                        <ul>
                        <?php
                            $langlinks = $this->data['language_urls'];
                            echo $this->nav($this->listAdapter($langlinks));
                        ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <li class="sidebar-create">
                        <div class="mw-inputbox-centered" style="">
                            <form name="createbox" class="createbox" action="/index.php" method="get">
                                <input name="action" value="edit" type="hidden"><input name="preload" value="" type="hidden">
                                <input name="editintro" value="" type="hidden"><input name="summary" value="" type="hidden">
                                <input name="nosummary" value="" type="hidden"><input name="prefix" value="" type="hidden">
                                <input name="minor" value="" type="hidden"><input name="title" class="createboxInput form-control" value="" placeholder="输入页面名称" dir="ltr" type="text">
                                <input name="create" class="mw-ui-button mw-ui-progressive" value="创建页面" type="submit">
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
            <a class="navbar-brand" href="#menu-toggle" id="menu-toggle">
                <span class="icon-huiji"></span>
            </a>
            <script>
                var menutoggle = localStorage.getItem('menu-toggle');
                $('#wrapper').attr('class',menutoggle);
                if($('#wrapper').hasClass('toggled')){
                    $('#menu-toggle').addClass('menu-active');
                }
            </script>
            <!-- /#sidebar-wrapper -->
            <?php echo $this->showHeader(); ?>


            <div id="wiki-outer-body">
                <?php
                if(($subnav_links = $this->listAdapter( $this->data['content_actions'])) && $NS !== NS_USER && $NS !== NS_USER_TALK ) {
                    ?>
                    <div id="content-actions" class="subnav subnav-fixed">
                        <div class="container">
                            <?php
                            $subnav_select = $this->nav_select( $subnav_links );
                            if ( trim( $subnav_select ) ) {
                                ?>
                                <select id="subnav-select">
                                    <?php echo $subnav_select; ?>
                                </select>
                            <?php
                            }//end if
                            ?>
                            <ul class="nav nav-pills">

                                <?php echo $this->nav( $subnav_links ); ?>
                                <!--                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i>工具 <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $url_prefix; ?>Special:RecentChanges" class="recent-changes"><i class="fa fa-edit"></i> 最近更改</a></li>
                                    <li><a href="<?php echo $url_prefix; ?>Special:SpecialPages" class="special-pages"><i class="fa fa-star-o"></i> 特殊页面</a></li>
                                    <?php if ( $wgEnableUploads ) { ?>
                                    <li><a href="<?php echo $url_prefix; ?>Special:Upload" class="upload-a-file"><i class="fa fa-upload"></i> 上传文件</a></li>
                                    <?php } ?>
                                </ul>
                            </li>    -->
                            </ul>



                        </div>
                    </div>
                <?php
                }//end if
                ?>
                <div id="wiki-body" class="container">
                    <div id="content">
                        <?php
                            if ( 'sidebar' == $wgTOCLocation ) {
                                ?>
                                <div class="row">
                                    <nav class="hidden-md hidden-sm hidden-xs hidden-print toc-sidebar" role="complementary navigation"></nav>
                                    <section class="col-md-12 wiki-body-section" role="main">
                                <?php
                            }//end if
                        ?>
                        <?php if ( $this->data['isarticle'] ) { ?><div id="siteSub" class="alert alert-info visible-print-block" role="alert"><?php $this->msg( 'tagline' ); ?></div><?php } ?>

                        <?php if( $this->data['sitenotice'] ) { ?><div id="siteNotice" class="alert-message warning"><?php $this->html('sitenotice') ?></div><?php } ?>
                        <?php if ( $this->data['undelete'] ): ?>
                        <!-- undelete -->
                        <div id="contentSub2" class="alert alert-warning alert-dismissible"><?php $this->html( 'undelete' ) ?></div>
                        <!-- /undelete -->
                        <?php endif; ?>
                        <?php if($this->data['newtalk'] ): ?>
                        <!-- newtalk -->
                        <div class="usermessage"><?php $this->html( 'newtalk' )  ?></div>
                        <!-- /newtalk -->
                        <?php endif; ?>

                        <div id="firstHeading" class="pagetitle page-header">
                            <div class="pull-right"><?php if ( $this->data['isarticle'] ) { echo $this->getIndicators();} ?> </div>
                            <h1><?php $this->html( 'title' ) ?> <div id="contentSub"><small><?php $this->html('subtitle') ?>
                            <?php
                                if ($this->data['isarticle'] &&  !($this->getSkin()->getTitle()->isMainPage()) && $this->getSkin()->getTitle()->exists()){
                                    $revPageId = $this->getSkin()->getTitle()->getArticleId();
                                    $editinfo = UserStats::getLastEditer($revPageId,$wgHuijiPrefix);
                                    $userPage = Title::makeTitle( NS_USER, $editinfo['rev_user_text'] );
                                    $userPageURL = htmlspecialchars( $userPage->getFullURL() );
                                    $bjtime = strtotime( $editinfo['rev_timestamp'] ) + 8*60*60;
                                    $edittime = CommentFunctions::getTimeAgo( $bjtime );
                                    if ($edittime === '刚刚') {
                                        echo '<a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="'.$userPageURL.'">'.$editinfo['rev_user_text'].'</a>'.$edittime.'编辑了此页面';
                                    }else{
                                        echo '<a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="'.$userPageURL.'">'.$editinfo['rev_user_text'].'</a>于'.$edittime.'前编辑了此页面';
                                    }
                                }
                            ?>
                            </small></div></h1>
                        </div>  

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
                        <div class="tucao">
                        <?php 
                        if ($this->data['isarticle'] &&  !($this->getSkin()->getTitle()->isMainPage()) && $this->getSkin()->getTitle()->exists()){
                            $articles = $this->msgWiki('tucao-wikitext');
                            $wgParserOptions = new ParserOptions($wgUser);
                            $parserOutput = $wgParser->parse($articles, $this->getSkin()->getTitle(), $wgParserOptions);
                            echo $parserOutput->getText();
                        }?>
                        </div>

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
            <div class="bottom">
                <div class="container">
                    <?php $this->includePage('Bootstrap:Footer'); ?>
                    <footer>
                        <p><a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://home.huiji.wiki/wiki/%E7%81%B0%E6%9C%BA%E5%81%9C%E6%9C%BA%E5%9D%AA">灰机停机坪</a>|<a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://home.huiji.wiki/wiki/%E7%BB%B4%E5%9F%BA%E5%AE%B6%E5%9B%AD%E8%AE%A1%E5%88%92">维基家园计划</a>|<a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://home.huiji.wiki/wiki/%E5%AE%87%E5%AE%99%E5%B0%BD%E5%A4%B4%E7%9A%84%E7%81%B0%E6%9C%BAwiki">关于灰机wiki</a><br>Powered by <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://mediawiki.org">MediaWiki</a> <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://www.miitbeian.gov.cn/">京ICP备15015138号</a></p> 
                    </footer>
                </div><!-- container -->
            </div><!-- bottom -->
        </div><!-- /#wrapper -->
        <?php }?> <!-- mainpage if -->
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
        </body>
        </html>
        <?php
    }//end execute
    /**
     * Render one or more navigations elements by name, automatically reveresed
     * when UI is in RTL mode
     */
    private function nav( $nav ) {
        $output = '';
        foreach ( $nav as $topItem ) {
            $pageTitle = Title::newFromText( $topItem['link'] ?: $topItem['title'] );
            if ( array_key_exists( 'sublinks', $topItem ) ) {
                $output .= '<li class="dropdown">';
                $output .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $topItem['title'] . ' <b class="caret"></b></a>';
                $output .= '<ul class="dropdown-menu">';
                foreach ( $topItem['sublinks'] as $subLink ) {
                    if ( 'divider' == $subLink ) {
                        $output .= "<li class='divider'></li>\n";
                    } elseif ( $subLink['textonly'] ) {
                        $output .= "<li class='nav-header'>{$subLink['title']}</li>\n";
                    } else {
                        if( $subLink['local'] && $pageTitle = Title::newFromText( $subLink['link'] ) ) {
                            $href = $pageTitle->getLocalURL();
                        } else {
                            $href = $subLink['link'];
                        }//end else
                        $slug = strtolower( str_replace(' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', trim( strip_tags( $subLink['title'] ) ) ) ) );
                        $output .= "<li {$subLink['attributes']}><a href='{$href}' class='{$subLink['class']} {$slug}'>{$subLink['title']}</a>";
                    }//end else
                }
                $output .= '</ul>';
                $output .= '</li>';
            } else {
                $requestUrl = $this->getSkin()->getRequest()->getRequestURL();
                $myLink = $topItem['link'];
                $query = explode('&', $requestUrl);
                if (count($query) > 1 && strpos( $requestUrl ,'action' )!== false){
                    $match = ($query[1] === explode('&amp;', $myLink)[1]) &&  ($query[0] === explode('&amp;', $myLink)[0]);
                }else{
                    if (strpos($requestUrl, 'index.php?title=') != false ){
                        $requestUrl = str_replace("index.php?title=", "wiki/", $requestUrl);
                    }
                    $match = (strpos($requestUrl , $myLink) !==false);
                }
                $output .= '<li' . ( $match ? ' class="active" id="ca-'.$topItem['id'] : ' id="ca-'.$topItem['id']) . '"><a href="' . ( $topItem['link']  ) . '">' . $topItem['title'] . '</a></li>';
            }//end else
        }//end foreach
        return $output;
    }//end nav
    /**
     * Render one or more navigations elements by name as notifications, automatically reveresed
     * when UI is in RTL mode
     */
    private function nav_notification( $nav ) {
        $output = '';
        foreach ( $nav as $topItem ) {
            $pageTitle = Title::newFromText( $topItem['link'] ?: $topItem['title'] );
            $output .= '<li id="pt-notifications" ><a class="'.$topItem['class'].'" href="' . ( $topItem['link']  ) . '">' . $topItem['title'] . '</a></li>';
            
        }//end foreach
        return $output;
    }//end nav
    /**
     * Render one or more navigations elements by name in a dropdown select style, automatically reveresed
     * when UI is in RTL mode
     */
    private function nav_select( $nav ) {
        $output = '';
        foreach ( $nav as $topItem ) {
            $pageTitle = Title::newFromText( $topItem['link'] ?: $topItem['title'] );
            $output .= '<optgroup label="'.strip_tags( $topItem['title'] ).'">';
            if ( array_key_exists( 'sublinks', $topItem ) ) {
                foreach ( $topItem['sublinks'] as $subLink ) {
                    if ( 'divider' == $subLink ) {
                        $output .= "<option value='' disabled='disabled' class='unclickable'>----</option>\n";
                    } elseif ( $subLink['textonly'] ) {
                        $output .= "<option value='' disabled='disabled' class='unclickable'>{$subLink['title']}</option>\n";
                    } else {
                        if( $subLink['local'] && $pageTitle = Title::newFromText( $subLink['link'] ) ) {
                            $href = $pageTitle->getLocalURL();
                        } else {
                            $href = $subLink['link'];
                        }//end else
                        $output .= "<option value='{$href}'>{$subLink['title']}</option>";
                    }//end else
                }//end foreach
            } else {
                $output .= '<option value="' . $topItem['link'] . '">' . $topItem['title'] . '</option>';
            }//end else
            $output .= '</optgroup>';
        }//end foreach
        return $output;
    }//end nav_select
    /* generate links from a source page */
    private function get_page_links( $source ) {
        $titleBar = $this->getPageRawText( $source );
        $nav = array();
        foreach(explode("\n", $titleBar) as $line) {
            if(trim($line) == '') continue;
            if( preg_match('/^\*\*\s*divider/', $line ) ) {
                $nav[ count( $nav ) - 1]['sublinks'][] = 'divider';
                continue;
            }//end if
            $sub = false;
            $link = false;
            $external = false;
            if(preg_match('/^\*\s*([^\*]*)\[\[:?(.+)\]\]/', $line, $match)) {
                $sub = false;
                $link = true;
            }elseif(preg_match('/^\*\s*([^\*\[]*)\[([^\[ ]+) (.+)\]/', $line, $match)) {
                $sub = false;
                $link = true;
                $external = true;
            }elseif(preg_match('/^\*\*\s*([^\*\[]*)\[([^\[ ]+) (.+)\]/', $line, $match)) {
                $sub = true;
                $link = true;
                $external = true;
            }elseif(preg_match('/\*\*\s*([^\*]*)\[\[:?(.+)\]\]/', $line, $match)) {
                $sub = true;
                $link = true;
            }elseif(preg_match('/\*\*\s*([^\* ]*)(.+)/', $line, $match)) {
                $sub = true;
                $link = false;
            }elseif(preg_match('/^\*\s*(.+)/', $line, $match)) {
                $sub = false;
                $link = false;
            }
            $dir = '/wiki/';
            if( strpos( $match[2], '|' ) !== false ) {
                $item = explode( '|', $match[2] );
                $item = array(
                    'title' => $match[1].$item[1],
                    'link' => $sub?$item[0]:$dir.$item[0],
                    'local' => true,
                );
            } else {
                if( $external ) {
                    $item = $match[2];
                    $title = $match[1] . $match[3];
                } else {
                    $item = $match[1] . $match[2];
                    $title = $item;
                    if (!$sub){
                        $item = $dir.$item;
                    } 
                }//end else
                if( $link ) {
                    $item = array('title'=> $title, 'link' => $item, 'local' => ! $external , 'external' => $external );
                } else {
                    $item = array('title'=> $title, 'link' => $item, 'textonly' => true, 'external' => $external );
                }//end else
            }//end else
            if( $sub ) {
                $nav[count( $nav ) - 1]['sublinks'][] = $item;
            } else {
                $nav[] = $item;
            }//end else
        }
        return $nav;    
    }//end get_page_links
    /* notification adapter */
    private function notificationAdapter($array){
        $nav = array();
        $item = next($array);
        $key = key($array);
        $link = array(
            'id' => Sanitizer::escapeId( $key ),
            'attributes' => $item['attributes'],
            'link' => htmlspecialchars( $item['href'] ),
            'key' => $item['key'],
            'class' => htmlspecialchars( $item['class'] ),
            'title' => htmlspecialchars( $item['text'] ),
        );
        $link['title'] = '<i class="fa fa-bell"></i> <span class="badge">' . $link['title'] .'</span>';
        $nav[] = $link;
        return $nav;        
    }
    /* dropdown button adapter */
    private function dropdownAdapter( $array, $title, $which ) {
        $nav = array();
        $nav[] = array('title' => $title );
        foreach( $array as $key => $item ) {
            $link = array(
                'id' => Sanitizer::escapeId( $key ),
                'attributes' => $item['attributes'],
                'link' => htmlspecialchars( $item['href'] ),
                'key' => $item['key'],
                'class' => htmlspecialchars( $item['class'] ),
                'title' => htmlspecialchars( $item['text'] ),
            );
            if( 'page' == $which ) {
                switch( $link['title'] ) {
                case 'Page': $icon = 'file'; break;
                case 'Discussion': $icon = 'comment'; break;
                case 'Edit': $icon = 'pencil'; break;
                case 'History': $icon = 'clock-o'; break;
                case 'Delete': $icon = 'remove'; break;
                case 'Move': $icon = 'arrows'; break;
                case 'Protect': $icon = 'lock'; break;
                case 'Watch': $icon = 'eye-open'; break;
                case 'Unwatch': $icon = 'eye-slash'; break;
                }//end switch
                $link['title'] = '<i class="fa fa-' . $icon . '"></i> ' . $link['title'];
            } elseif( 'user' == $which ) {
                switch( $link['title'] ) {
                case '讨论': $icon = 'comment'; break;
                case '设置': $icon = 'cog'; break;
                case '监视列表': $icon = 'eye'; break;
                case '贡献': $icon = 'list-alt'; break;
                case '退出': $icon = 'power-off'; break;
                default: $icon = 'user'; break;
                }//end switch
                $link['title'] = '<i class="fa fa-' . $icon . '"></i> ' . $link['title'];
            }//end elseif
            $nav[0]['sublinks'][] = $link;
        }//end foreach
        return $this->nav( $nav );
    }//end get_array_links
    /* general a list of links adater */
    private function listAdapter( $array ) {
        $nav = array();
        foreach( $array as $key => $item ) {
            $link = array(
                'id' => Sanitizer::escapeId( $key ),
                'attributes' => $item['attributes'],
                'link' => htmlspecialchars( $item['href'] ),
                'key' => $item['key'],
                'class' => htmlspecialchars( $item['class'] ),
                'title' => htmlspecialchars( $item['text'] ),
            );
            switch( $link['title'] ) {
                case '页面': $icon = 'file'; break;
                case '讨论': $icon = 'comment'; break;
                case '编辑': $icon = 'pencil'; break;
                case '编辑源代码': $icon = 'edit'; break;
                case '历史': $icon = 'clock-o'; break;
                case '删除': $icon = 'remove'; break;
                case '移动': $icon = 'arrows'; break;
                case '保护': $icon = 'lock'; break;
                case '更改保护': $icon = 'unlock-alt'; break;
                case '监视': $icon = 'eye'; break;
                case '取消监视': $icon = 'eye-slash'; break;
                case '创建': $icon = 'plus'; break;
                case '创建源代码': $icon = 'plus'; break;
                case '查看源代码': $icon = 'file-code-o'; break;
                case '特殊页面': $icon = 'flask'; break;
                default: $icon = 'bookmark'; break;
            }
            $link['title'] = '<i class="fa fa-' . $icon . '"></i> ' . $link['title'];
            $nav[] = $link;
        }//end foreach
        return $nav ;
    }//end get_edit_links   
    function getPageRawText($title) {
        $output = self::getPageRawTextCache( $title );
        if ( $output != '' ) {
            return $output;
        } else {
            return self::getPageRawTextPage( $title );
        }
    }
    static function getPageRawTextCache( $title ){
        global $wgMemc;
        $key = wfMemcKey( 'page', 'getPageRaw', 'all', $title );
        $output = $wgMemc->get( $key );
        return $output;
    }
    static function getPageRawTextPage( $title ){
        global $wgParser, $wgMemc, $wgUser;
        $key = wfMemcKey( 'page', 'getPageRaw', 'all', $title );
        $pageTitle = Title::newFromText($title);
        if(!$pageTitle->exists()) {
            return 'Create the page [[Bootstrap:TitleBar]]';
        } else {
            $article = new Article($pageTitle);
            $wgParserOptions = new ParserOptions($wgUser);
            // get the text as static wiki text, but with already expanded templates,
            // which also e.g. to use {{#dpl}} (DPL third party extension) for dynamic menus.
            $output = $wgParser->preprocess($article->getRawText(), $pageTitle, $wgParserOptions );
            $wgMemc->set( $key, $output );
            return $output;
        }
    }
    function includePage($title) {
        global $wgParser, $wgUser;
        $pageTitle = Title::newFromText($title);
        if(!$pageTitle->exists()) {
            echo 'The page [[' . $title . ']] was not found.';
        } else {
            $wgParserOptions = new ParserOptions($wgUser);
            $parserOutput = $wgParser->parse($this->getPageRawText($title), $pageTitle, $wgParserOptions);
            echo $parserOutput->getText();
        }
    }
    //index content block
    // $socurce  json data
    function getIndexBlock( $source ) {
        $content = $this->getPageRawText( $source );
        $result = json_decode( $content );
        return $result;
    }
    //show header
    function showHeader(){
        global $wgUser, $wgSitename;
        global $wgNavBarClasses;
        
        // $output = '';
        $output ='
            <header class="header navbar navbar-default navbar-fixed-top'.$wgNavBarClasses.'" role="navigation">
                    <div class="navbar-container">
                        <div class="navbar-header">
                            <button class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        <a title="灰机wiki" href="http://huiji.wiki" class="navbar-brand"><img alt="Logo" src="/resources/assets/huiji_white.png"> </a>  </div>
                        <div class="collapse navbar-collapse">
                            <ul id="icon-section" class="nav navbar-nav">
                                    <li>
                                        <a href="'.$this->data['nav_urls']['mainpage']['href'].'">';
                                        if( $wgSitename < 8) {
                                            $output .= $wgSitename; 
                                        }else{
                                            $output .= 'wiki首页';
                                        }
                                        $output .= '</a>
                                    </li>
                                    <li class="dropdown">
                                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">推荐wiki <span class="caret"></span></a>
                                      <ul class="dropdown-menu" role="menu">
                                        <li><a href="http://lotr.huiji.wiki">魔戒中文维基</a></li>
                                        <li><a href="http://asoiaf.huiji.wiki">冰与火之歌中文维基</a></li>
                                        <li><a href="http://allglory.huiji.wiki">荣耀百科全书</a></li>
                                        <li><a href="http://downtonabbey.huiji.wiki/">唐顿庄园中文维基</a></li>
                                        <li><a href="http://jiuzhou.huiji.wiki">九州奇幻世界百科</a></li>
                                        <li><a href="/wiki/Special:Randomwiki">随机一下试试</a></li>
                                      </ul>
                                    </li>
                                    <li>
                                        <a href="http://home.huiji.wiki/wiki/创建新wiki">创建新wiki</a>
                                    </li>
                            </ul>';
                        if ( $wgUser->isLoggedIn() ) {
                            if ( count( $this->data['personal_urls'] ) > 0 ) {
                                $avatar = new wAvatar( $wgUser->getID(), 'l' );
                                // $user_icon = '<span class="user-icon"><img src="https://secure.gravatar.com/avatar/'.md5(strtolower( $wgUser->getEmail())).'.jpg?s=20&r=g"/></span>';
                                $user_icon = '<span class="user-icon" style="border: 0px;">'.$avatar->getAvatarURL().'</span>';
                                $name =  $wgUser->getName() ;
                                $personal_urls = $this->data['personal_urls'];
                                unset($personal_urls['notifications']);
                                $user_nav = $this->dropdownAdapter( $personal_urls, $user_icon.$name, 'user' );
                                $user_notify = $this->nav_notification($this->notificationAdapter($this->data['personal_urls']));
                            }
                            $output .= '<ul'.$this->html('userlangattributes').' class="nav navbar-nav navbar-right">'.$user_notify.$user_nav.'</ul>';
                        } else {  // else if is logged in 
                                    //old login 
                            $output .= '<ul class="nav navbar-nav navbar-right">
                                <li id= "pt-login" data-toggle="modal" data-target=".user-login">
                                    <a class="login-in">登录</a>
                                </li>
                                <li>'.Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), '注册', array('id' => 'pt-createaccount' ),array('type' => 'signup') ).'
                                </li>   
                            </ul>';
                        }
                        
                        
                        $output .= '<form class="navbar-search navbar-form navbar-right" action="/index.php" id="searchform" role="search">
                            <div>
                                <input class="form-control" type="search" name="search" placeholder="在'.$wgSitename.'内搜索" title="Search '.$wgSitename.' [ctrl-option-f]" accesskey="f" id="searchInput" autocomplete="off">
                                <input type="hidden" name="title" value="Special:Search">
                            </div>
                        </form>
                        </div>
                    </div>
            </header>';
            
            return $output;
    }
}
