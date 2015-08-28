<div class="wrapper">
    <div class="content-wrapper">
        <script>
            if(localStorage.getItem('view') == 'notfirst'){
                $('.content-wrapper').addClass('hide').remove();
            }else{
                $('.wrapper').addClass('first-view');
            }
        </script>
        <header>
            <img src="/resources/frontpage/huiji_white.png" class="logo">
            <div class="login">
                <div><a href="#home-content-signup">注册用户</a></div>
                <div><a href="http://www.huiji.wiki/wiki/%E5%88%9B%E5%BB%BA%E6%96%B0wiki">创建站点</a></div>
            </div>
        </header>
        <div class="head">
        <script>
            var pagetop = ($('body').height()-150);
                if(pagetop<550){
                    pagetop = 550;
                }
            $(".head").css('height',pagetop+"px");
        </script>
            <div class="intro-container">
                <div class="intro">
                    <p><span>你的兴趣 在此起飞</span><span>有多热爱 就飞多高</span></p>
                    <span class="page-front-enter"></span>
                </div>
            </div>
        </div>
        <div class="page-front-content">
        <div class="head-content">
        </div>
        <div class="firstbg perspectivebg lazy" data-original="/resources/frontpage/lotr.jpg">
            <a href="http://lotr.huiji.wiki" target="_black"><div class="firstbg-intro"></div></a>
        </div>
        <div class="fmain-content">
            <ul class="fmain-content-list">
                <li>
                    <img src="/resources/frontpage/iconsearch.png">
                    <h2>邂逅</h2>
                    <p>频率相同的“机”友</p>
                </li>
                <li>
                    <img src="/resources/frontpage/iconwrite.png">
                    <h2>构建</h2>
                    <p>你的专属兴趣世界</p>
                </li>
                <li>
                    <img src="/resources/frontpage/iconidea.png">
                    <h2>发现</h2>
                    <p>不受束缚的热情创造</p>
                </li>
            </ul>
        </div>
        <div class="secondbg perspectivebg lazy" data-original="/resources/frontpage/asoiaf.jpg">
            <a href="http://asoiaf.huiji.wiki" target="_black"><div class="secondbg-intro"></div></a>
        </div>
        <div class="smain-content">

        </div>
        <div class="thirdbg perspectivebg lazy" data-original="/resources/frontpage/spn.jpg">
            <a href="http://spn.huiji.wiki" target="_black"><div class="thirdbg-intro"></div></a>
        </div>
        <div class="content-bottom">
            <div class="bottom-wrapper">
                <div class="bottom-intro">
                    <h2>灰机wiki</h2>
                    <p>——你的兴趣，在这里起飞。</p>
                </div>
                <div class="bottom-login">
                    <form id="home-content-signup">
                        <input type="text" placeholder="昵称" class="sign-username">
                        <input type="text" placeholder="邮箱" class="sign-email">
                        <input type="password" placeholder="密码" class="sign-pass">
                        <!--<input type="password" placeholder="再次输入密码" class="sign-pass-agin">-->
                        <!--<div class="login-error"></div>-->
                        <div class="signup-submit">注册灰机</div>
                    </form>
                </div>
            </div>
            <a class="scroll"></a>
        </div>
        </div>
    </div>
    <div class="wiki-wrapper">
    <script>
        if(localStorage.getItem('static') == 'back'){
            $('.wiki-wrapper').addClass('back');
        }
    </script>
    <?php include 'View/Sidebar.php'; ?>
    <?php
        echo $this->showHeader();
    ?>
        <div class="wiki-top">
            <div class="wiki-flog-left">
                <img src="/resources/frontpage/asoiafbannerleft.jpg">
            </div>
            <div class="wiki-flog-right">
                <img src="/resources/frontpage/asoiafbannerright.jpg">
            </div>
            <div class="wiki-flog-wrap">
                <div class="wiki-flog">
                    <ul id="sb-slider" class="sb-slider">
                        <li class="wiki-flog-1">
                            <div class="wiki-flog-asoiaf-intro"></div>
                            <img src="/resources/frontpage/asoiafbanner.jpg" data-src="asoiafbanner">
                        </li>
                        <li class="wiki-flog-1">
                            <div class="wiki-flog-lotr-intro"></div>
                            <img src="/resources/frontpage/lotrbanner.jpg" data-src="lotrbanner">
                        </li>
                        <li class="wiki-flog-1">
                            <div class="wiki-flog-spn-intro"></div>
                            <img src="/resources/frontpage/spnbanner.jpg" data-src="spnbanner">
                        </li>
                    </ul>
                </div>
                <div class="wiki-top-right">
                    <img src="/resources/frontpage/huijilargelogo.png">
                    <h3><a href="http://www.huiji.wiki/wiki/%E7%81%B0%E6%9C%BA%E5%81%9C%E6%9C%BA%E5%9D%AA">推荐wiki >></a></h3>
                    <ul>
                        <li>
                            <h5>现有条目</h5>
                            <p>45,270</p>
                        </li>
                        <li>
                            <h5>文件上传</h5>
                            <p>22,356</p>
                        </li>
                        <li>
                            <h5>编辑次数</h5>
                            <p>286,321</p>
                        </li>
                        <li>
                            <h5>页面浏览</h5>
                            <p>4,861,338</p>
                        </li>
                        <li>
                            <h5>注册用户</h5>
                            <p>6,540</p>
                        </li>
                        <li>
                            <h5>引用次数</h5>
                            <p>95,360</p>
                        </li>
                    </ul>
                </div>
                <a class="previous"></a>
                <a class="next"></a>
            </div>
        </div>
        <div class="wiki-content">
            <div class="wiki-content-header">
                <ul>
                    <li>
                        <a href="http://allglory.huiji.wiki">
                            <div class="avatar1 wiki-thumbnail"></div>
                            <div class="thumbnail-intro">
                                <h4>荣耀百科全书</h4>
                                <h3>编写者招募中！</h3>
                                <p>起点中文网第一部千盟作品，蝴蝶蓝长篇网游小说《全职高手》相关wiki，记录有关荣耀大陆和职业联盟的一切，欢迎粉丝加入编写队伍！入群暗号：虫爹是设定的敌人！</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="http://spn.huiji.wiki">
                            <div class="avatar2 wiki-thumbnail"></div>
                            <div class="thumbnail-intro">
                                <h4>邪恶力量中文维基</h4>
                                <h3>编写者招募中！</h3>
                                <p>人物，剧集，鬼怪，神器，还有来自剧集粉丝与靠谱编辑的爱意。一切尽在国内最精准、最详尽的邪恶力量wiki！料足劲大好卖安利！</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="http://boardwalkempire.huiji.wiki">
                            <div class="avatar3 wiki-thumbnail"></div>
                            <div class="thumbnail-intro">
                                <h4>大西洋帝国中文维基</h4>
                                <h3>编写者招募中！</h3>
                                <p>如果来城里的人想要阅读《圣经》，我们就给他们《圣经》。但是没有人想要《圣经》。他们想要的是美酒、女人和赌博，于是我们就给他们这一切。</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="http://downtonabbey.huiji.wiki">
                            <div class="avatar4 wiki-thumbnail"></div>
                            <div class="thumbnail-intro">
                                <h4>唐顿庄园中文维基</h4>
                                <h3>编写者招募中！</h3>
                                <p>“你看到的是终将到来的残砖败瓦，断壁颓垣，风霜侵蚀，荒凉满目”，我看到的是我们在一起即将建立起一座充满希望的新家园。庄园风雨后，你我相依时。</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="http://wire.huiji.wiki">
                            <div class="avatar5 wiki-thumbnail"></div>
                            <div class="thumbnail-intro">
                                <h4>The Wire中文维基</h4>
                                <h3>编写者招募中！</h3>
                                <p>HBO纪实主义力作，被誉为美剧教科书的现象级经典。随着高清/BD重置版的发售和腾讯视频的正版引进，重新掀起观剧热潮。The Wire中文维基欢迎粉丝加入！</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <aside class="wiki-sidebar">
                <div class="user-rank">
                    <div class="chart">
                        <p>灰机里程榜</p>
                    </div>
                    <!-- weekly -->
                    <div class="tab-content">
                    <div class="top-users weekly-rank">
                        <?php
                            $count = 10;
                            $params['ORDER BY'] = 'up_points DESC';
                            $params['LIMIT'] = $count;

                            $dbr = wfGetDB( DB_SLAVE );
                            $res = $dbr->select(
                                "user_points_weekly",
                                array( 'up_user_id', 'up_user_name', 'up_points' ),
                                array( 'up_user_id <> 0' ),
                                __METHOD__,
                                $params
                            );

                            foreach ( $res as $row ) {
                                $userObj = User::newFromId( $row->up_user_id );
                                $user_group = $userObj->getEffectiveGroups();
                                if ( !in_array('bot', $user_group) && !in_array('bot-global',$user_group)  ) {
                                    $user_list[] = array(
                                        'user_id' => $row->up_user_id,
                                        'user_name' => $row->up_user_name,
                                        'points' => $row->up_points
                                    );
                                }
                            }
                            $x = 0;
                            foreach ( $user_list as $user ) {
                                $user_title = Title::makeTitle( NS_USER, $user['user_name'] );
                                $avatar = new wAvatar( $user['user_id'], 'ml' );
                                $avatarImage = $avatar->getAvatarURL();
                                $x++;
                        ?>
                        <div class="top-fan-row">
                            <span class="top-fan-num"><?php echo $x; ?></span>
                            <span class="top-fan">
                                <?php echo $avatarImage; ?> <a href="<?php echo htmlspecialchars( $user_title->getFullURL() ); ?>"><?php echo $user['user_name']; ?></a>
                            </span>
                            <span class="top-fan-points">
                                <b><?php echo number_format($user['points']>=0?$user['points']:0) ;?></b> 公里
                            </span>
                            <div class="cleared">

                            </div>
                        </div>
                        <?php
                            }
                        ?>

                    </div>
                    <!-- monthly -->
                    <div class="top-users monthly-rank hide">
                        <?php
                            $count = 10;
                            $params['ORDER BY'] = 'up_points DESC';
                            $params['LIMIT'] = $count;

                            $dbr = wfGetDB( DB_SLAVE );
                            $res = $dbr->select(
                                "user_points_monthly",
                                array( 'up_user_id', 'up_user_name', 'up_points' ),
                                array( 'up_user_id <> 0' ),
                                __METHOD__,
                                $params
                            );

                            foreach ( $res as $row ) {
                                $userObj = User::newFromId( $row->up_user_id );
                                $user_group = $userObj->getEffectiveGroups();
                                if ( !in_array('bot', $user_group) && !in_array('bot-global',$user_group)  ) {
                                    $user_list_month[] = array(
                                        'user_id' => $row->up_user_id,
                                        'user_name' => $row->up_user_name,
                                        'points' => $row->up_points
                                    );
                                }
                            }
                            $y = 0;
                            foreach ( $user_list_month as $user ) {
                                $user_title = Title::makeTitle( NS_USER, $user['user_name'] );
                                $avatar = new wAvatar( $user['user_id'], 'ml' );
                                $avatarImage = $avatar->getAvatarURL();
                                $y++;
                        ?>
                        <div class="top-fan-row">
                            <span class="top-fan-num"><?php echo $y; ?></span>
                            <span class="top-fan">
                                <?php echo $avatarImage; ?> <a href="<?php echo htmlspecialchars( $user_title->getFullURL() ); ?>"><?php echo $user['user_name']; ?></a>
                            </span>
                            <span class="top-fan-points">
                                <b><?php echo number_format($user['points']>=0?$user['points']:0) ;?></b> 公里
                            </span>
                            <div class="cleared">

                            </div>
                        </div>
                        <?php
                            }
                        ?>

                    </div>
                    <!-- total -->
                    <div class="top-users total-rank hide">
                    <?php
                        $count = 10;
                        $params['ORDER BY'] = 'stats_total_points DESC';
                        $params['LIMIT'] = $count;
                        $dbr = wfGetDB( DB_SLAVE );
                        $res = $dbr->select(
                            'user_stats',
                            array( 'stats_user_id', 'stats_user_name', 'stats_total_points' ),
                            array( 'stats_user_id <> 0' ),
                            __METHOD__,
                            $params
                        );
                        foreach ( $res as $row ) {
                            $userObj = User::newFromId( $row->stats_user_id );
                            $user_group = $userObj->getEffectiveGroups();
                            if ( !in_array('bot', $user_group) && !in_array('bot-global',$user_group)  ) {
                                $user_list_total[] = array(
                                    'user_id' => $row->stats_user_id,
                                    'user_name' => $row->stats_user_name,
                                    'points' => $row->stats_total_points
                                );
                            }
                        }
                        $z = 0;
                        foreach ( $user_list_total as $user ) {
                                $user_title = Title::makeTitle( NS_USER, $user['user_name'] );
                                $avatar = new wAvatar( $user['user_id'], 'ml' );
                                $avatarImage = $avatar->getAvatarURL();
                                $z++;
                    ?>
                    <div class="top-fan-row">
                        <span class="top-fan-num"><?php echo $z; ?></span>
                        <span class="top-fan">
                            <?php echo $avatarImage; ?> <a href="<?php echo htmlspecialchars( $user_title->getFullURL() ); ?>"><?php echo $user['user_name']; ?></a>
                        </span>
                        <span class="top-fan-points">
                            <b><?php echo number_format($user['points']>=0?$user['points']:0) ;?></b> 公里
                        </span>
                        <div class="cleared">
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    </div>
                    </div>
                    <ul class="nav-tab">
                        <li class="active">周里程<span>/</span></li>
                        <li>月里程<span>/</span></li>
                        <li>总里程</li>
                    </ul>
                </div>
            </aside>
            <div class="wikis-wrap ">
                <ul class="wikis">
                <?php
                    $block = BootstrapMediaWikiTemplate::getIndexBlock( '首页/Admin' );
                    $n = count($block);
                    // if( $block ){
                        for ($i=0; $i < $n; $i++) {
                ?>
                    <li>
                        <a href="<?php echo $block[$i]->wikiurl; ?>" class="wiki-entry" >
                            <img src="/resources/frontpage/grey.gif" class="lazy" data-original="<?php echo $block[$i]->backgroungimg; ?>">
                            <div class="relative">

                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p><?php echo $block[$i]->title; ?></p>
                                        <p><?php echo $block[$i]->wikiname; ?></p>
                                    </div>
                                    <div class="entry-content">
                                        <p><?php echo $block[$i]->desc; ?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php
                        }
                    // }
                ?>
                    <li class="cleared"></li>
                </ul>
            </div>
        </div>
        <div class="bottom">
            <div class="container">
                <?php self::includePage('Bootstrap:Footer'); ?>
                <footer>
                    <p class="text-center"><a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://www.huiji.wiki/wiki/%E7%81%B0%E6%9C%BA%E5%81%9C%E6%9C%BA%E5%9D%AA">灰机停机坪</a> | <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://www.huiji.wiki/wiki/%E7%BB%B4%E5%9F%BA%E5%AE%B6%E5%9B%AD%E8%AE%A1%E5%88%92">维基家园计划</a> | <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://www.huiji.wiki/wiki/%E5%AE%87%E5%AE%99%E5%B0%BD%E5%A4%B4%E7%9A%84%E7%81%B0%E6%9C%BAwiki">关于灰机wiki</a><br>Powered by <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://mediawiki.org">MediaWiki</a> <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://www.miitbeian.gov.cn/">京ICP备15015138号</a></p> 
                </footer>
            </div><!-- container -->
        </div><!-- bottom -->
    </div>
</div>
