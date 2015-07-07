<div class="wrapper">
    <div class="content-wrapper">
        <script>
        if(localStorage.getItem('static') == 'back'){
            $('.content-wrapper').addClass('hide').remove();
        }
        </script>
        <header>
            <img src="/resources/frontpage/huiji_white.png" class="logo">
            <div class="login">
                <div><a href="#home-content-signup">注册用户</a></div>
                <div><a href="http://home.huiji.wiki/wiki/%E5%88%9B%E5%BB%BA%E6%96%B0wiki">创建站点</a></div>
            </div>
        </header>
        <div class="head">
            <div class="intro-container">
                <div class="intro">
                    <p><span>你的兴趣 在此起飞</span><span>有多热爱 就飞多高</span></p>
                    <span class="page-front-enter"></span>
                </div>
            </div>
        </div>
        <div class="page-front-content">
        <div class="head-content">
            <!--<p>We are <span>wiki</span>,an interest family</p>-->
            <!--<h2>Bla bla bla</h2>-->
        </div>
        <div class="firstbg perspectivebg lazy" data-original="/resources/frontpage/lotr.jpg">
            <div class="firstbg-intro"></div>
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
            <div class="secondbg-intro"></div>
        </div>
        <div class="smain-content">

        </div>
        <div class="thirdbg perspectivebg lazy" data-original="/resources/frontpage/spn.jpg">
            <div class="thirdbg-intro"></div>
        </div>
        <div class="content-bottom">
            <div class="bottom-wrapper">
                <div class="bottom-intro">
                    <h2>灰机wiki</h2>
                    <p>——你的兴趣，在这里起飞。</p>
                    <button class="page-front-enter"></button>
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
        </div>
        </div>
    </div>
    <div class="wiki-wrapper">
    <script>
        if(localStorage.getItem('static') == 'back'){
            $('.wiki-wrapper').addClass('back');
        }
    </script>
        <header class="header navbar navbar-default navbar-fixed-top <?php echo $wgNavBarClasses; ?>" role="navigation">
                    <div class="navbar-container">
                        <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                        <div class="navbar-header">
                            <button class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="http://huiji.wiki" title="<?php echo $wgSitename ?>"><?php echo isset( $wgLogo ) && $wgLogo ? "<img src='{$wgLogo}' alt='Logo'/> " : ''; ?></a>
                        </div>

                        <div class="collapse navbar-collapse">
                            <ul id="icon-section" class="nav navbar-nav">
                                    <li>
                                        <a href="<?php echo $this->data['nav_urls']['mainpage']['href'] ?>"><?php 
                                        if( $wgSitename < 8) {
                                            echo $wgSitename; 
                                        }else{
                                            echo 'wiki首页';
                                        }?></a>
                                    </li>
                                    <li class="dropdown">
                                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">推荐wiki <span class="caret"></span></a>
                                      <ul class="dropdown-menu" role="menu">
                                        <li><a href="http://lotr.huiji.wiki">魔戒中文维基</a></li>
                                        <li><a href="http://asoiaf.huiji.wiki">冰与火之歌中文维基</a></li>
                                        <li><a href="http://allglory.huiji.wiki">荣耀百科全书</a></li>
                                        <li><a href="http://wire.huiji.wiki">火线中文维基</a></li>
                                        <li><a href="http://spn.huiji.wiki">邪恶力量中文wiki</a></li>
                                        <li><a href="/wiki/Special:Randomwiki">随机一下试试</a></li>
                                      </ul>
                                    </li>
                                    <li>
                                        <a href="http://home.huiji.wiki/wiki/创建新wiki">创建新wiki</a>
                                    </li>
                            </ul>
                        <?php
                        if ( $wgUser->isLoggedIn() ) {
                            if ( count( $this->data['personal_urls'] ) > 0 ) {
                                $avatar = new wAvatar( $wgUser->getID(), 'l' );
                                // $user_icon = '<span class="user-icon"><img src="https://secure.gravatar.com/avatar/'.md5(strtolower( $wgUser->getEmail())).'.jpg?s=20&r=g"/></span>';
                                $user_icon = '<span class="user-icon" style="border: 0px;">'.$avatar->getAvatarURL().'</span>';
                                $name =  $wgUser->getName() ;
                                $personal_urls = $this->data['personal_urls'];
                                unset($personal_urls['notifications']);
                                $user_nav = $this->dropdownAdapter( $personal_urls, $user_icon . $name, 'user' );
                                $user_notify = $this->nav_notification($this->notificationAdapter($this->data['personal_urls']));
                                ?>
                                <ul<?php $this->html('userlangattributes') ?> class="nav navbar-nav navbar-right">
                                    <?php echo $user_notify; ?><?php echo $user_nav; ?>
                                </ul>
                                <?php
                            }//end if

                        /*  if ( count( $this->data['content_actions']) > 0 ) {
                                $content_nav = $this->get_array_links( $this->data['content_actions'], 'Page', 'page' );
                                ?>
                                <ul class="nav navbar-nav navbar-right content-actions"><?php echo $content_nav; ?></ul>
                                <?php
                            }//end if */
                        } else {  // else if is logged in 
                                    //old login 
                            ?>

                            <ul class="nav navbar-nav navbar-right">
                                <li id= "pt-login" data-toggle="modal" data-target=".user-login">
                                    <a class="login-in">登录</a>
<!--                                    --><?php //echo Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), wfMsg( 'login' ), array('id' => 'pt-anonlogin' ) ); ?>
                                </li>
                                <li>
                                    <?php echo Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), '注册', array('id' => 'pt-createaccount' ),array('type' => 'signup') ); ?>
                                </li>   
                            </ul>
                            <?php
                        }
                        ?>
                        <form class="navbar-search navbar-form navbar-right" action="<?php $this->text( 'wgScript' ) ?>" id="searchform" role="search">
                            <div>
                                <input class="form-control" type="search" name="search" placeholder="Search" title="Search <?php echo $wgSitename; ?> [ctrl-option-f]" accesskey="f" id="searchInput" autocomplete="off">
                                <input type="hidden" name="title" value="Special:Search">
                            </div>
                        </form>
                        </div>
                    </div>
            </header><!-- topbar -->
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
                    <h3>你的兴趣 在此起飞</h3>
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
            <a href='http://home.huiji.wiki/wiki/%E7%81%B0%E6%9C%BA%E5%81%9C%E6%9C%BA%E5%9D%AA' class='all-wiki'>查看推荐维基</a>
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
                <a href='http://home.huiji.wiki/wiki/%E7%81%B0%E6%9C%BA%E5%81%9C%E6%9C%BA%E5%9D%AA' class='all-wiki'>更多推荐维基</a>
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
                                $user_list[] = array(
                                    'user_id' => $row->up_user_id,
                                    'user_name' => $row->up_user_name,
                                    'points' => $row->up_points
                                );
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
                                <b><?php echo number_format($user['points']) ;?></b> 公里
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
                                $user_list_month[] = array(
                                    'user_id' => $row->up_user_id,
                                    'user_name' => $row->up_user_name,
                                    'points' => $row->up_points
                                );
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
                                <b><?php echo number_format($user['points']) ;?></b> 公里
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
                            $user_list_total[] = array(
                                'user_id' => $row->stats_user_id,
                                'user_name' => $row->stats_user_name,
                                'points' => $row->stats_total_points
                            );
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
                            <b><?php echo number_format($user['points']) ;?></b> 公里
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
                    <li>
                        <a href="http://asoiaf.huiji.wiki/wiki/%E7%8F%8A%E8%8E%8E%C2%B7%E5%8F%B2%E5%A1%94%E5%85%8B" class="wiki-entry" >
                            <div class="relative">
                                <img src="/resources/frontpage/wiki1.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>珊莎·史塔克</p>
                                        <p>冰与火之歌中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>珊莎·史塔克（Sansa Stark）是凯特琳?徒利和艾德?史塔克的长女。她有三个兄弟罗柏，布兰和瑞肯，还有一个妹妹，艾莉亚。她曾和七大王国的王储乔佛里?拜拉席恩订婚，也曾与提利昂?兰尼斯特有一段强扭的姻缘。现在，她正化名阿莲?石东藏身于艾林谷。珊莎是书中的一个主要POV人物。在电视剧中由索菲?特纳Sophie Tunner扮演。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://spn.huiji.wiki/wiki/%E9%A9%AC%E5%85%8B%C2%B7%E8%B0%A2%E6%B3%BC%E5%BE%B7">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki2.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>马克·谢泼德</p>
                                        <p>邪恶力量中文wiki</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>马克·安德亚斯·谢泼德（1964年5月30日生）是一位英国演员和音乐家。他出生在伦敦，具有爱尔兰和德国血统。目前他与妻子杰西卡、两个儿子马克斯米兰和威廉住在洛杉矶。马克在“邪恶力量”中饰演恶魔克劳利。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://lotr.huiji.wiki/wiki/%E4%BC%8A%E5%A5%A5%E6%B8%A9">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki3.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>伊奥温</p>
                                        <p>魔戒中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>伊奥温（蒾wyn），她是洛汗的公主，也被称为执盾女士、洛汗的白公主、伊希利恩亲王夫人。她是洛汗国王希奥顿的妹妹希奥德温与伊奥蒙德之女，其哥哥是伊奥梅尔。魔戒大战后与法拉米尔结婚，并生育一儿子Elboron。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://lotr.huiji.wiki/wiki/%E9%9C%8D%E6%AF%94%E7%89%B9%E4%BA%BA">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki4.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>霍比特人</p>
                                        <p>魔戒中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>霍比特人（Hobbits）是一个体型很小的种族，主要居住在夏尔地底的洞府里，与人类有亲缘关系。他们在中洲平静生活了漫长的年月，直到比尔博和弗罗多的时代才突然变得重要且著名。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://allglory.huiji.wiki/wiki/%E5%91%A8%E6%B3%BD%E6%A5%B7">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki5.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>周泽楷</p>
                                        <p>荣耀百科全书</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>周泽楷，是有“枪王”之称的顶尖荣耀职业选手。轮回战队队长，首届荣耀世界邀请赛中国国家队队员。周泽楷的职业是神枪手，使用账号卡一枪穿云。银武左轮手枪荒火（右手枪）、碎霜（左手枪）。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://asoiaf.huiji.wiki/wiki/%E9%93%81%E7%8E%8B%E5%BA%A7">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki6.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>铁王座</p>
                                        <p>冰与火之歌中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>铁王座（Iron Throne），是七大王国国王的王座，也是经常被用作比喻及代替国王权威的词语。国王会坐在王座上听取民意。国王不在时，代替他履行职务的摄政王或国王之手会坐在铁王座上聆听民众的申诉，并为他们主持正义和宣告他的判决。铁王座本身又冷又硬，还有许多尖刺和倒鈎，刻意令人坐得不舒服，用以警戒上位者。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://asoiaf.huiji.wiki/wiki/%E7%90%BC%E6%81%A9%C2%B7%E9%9B%AA%E8%AF%BA">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki7.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>琼恩雪诺</p>
                                        <p>冰与火之歌中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>琼恩·雪诺（Jon Snow）是艾德?史塔克的私生子，生母不明，对此有诸多推测。[1][2]他和父亲嫡生的孩子，即琼恩同父异母的兄弟姐妹，一起长大，却在接近成年之时加入了守夜人。他的冰原狼白灵通常伴随其左右。在卷一《权力的游戏》开始时，琼恩十四岁。他是书中主要的POV人物之一。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://lotr.huiji.wiki/wiki/%E9%AD%94%E8%8B%9F%E6%96%AF">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki8.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>魔苟斯</p>
                                        <p>魔戒中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>魔苟斯（Morgoth）是一位爱努。他扰乱了爱努的大乐章并违抗了一如的意志。他偷走了精灵宝钻，并因此与诺多势不两立。最终，他被维拉以铁链束缚并投入了空虚之境，然而他所创造的邪恶却依然遗祸世界。根据预言，魔苟斯终将脱离束缚，并在末日之战中被彻底杀死。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://wire.huiji.wiki/wiki/Jimmy_McNulty">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki9.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>Jimmy McNulty</p>
                                        <p>The Wire中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>James "Jimmy" McNulty （James McNulty）爱尔兰裔，巴尔的摩警察局警探，他思维聪敏，有着极强的办案能力。但是过于倔强认真，为了案情常常无视上下级管辖，越权查案，屡屡触怒上司。在工作之外他也是麻烦缠身，他酗酒、私生活混乱，财务状况也很糟糕，与前妻的之间还有关于子女抚养的纠纷。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://lotr.huiji.wiki/wiki/%E7%B2%BE%E7%81%B5%E5%AE%9D%E9%92%BB">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki10.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>精灵宝钻</p>
                                        <p>魔戒中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>精灵宝钻（Silmarils），有时也简称为“宝钻”，是费艾诺制造的三颗光辉璀璨的硕大宝石，它们蕴含着维林诺双圣树的光辉。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://downtonabbey.huiji.wiki/wiki/%E7%BD%97%E4%BC%AF%E7%89%B9%C2%B7%E5%85%8B%E5%8A%B3%E5%88%A9">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki11.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>罗伯特·克劳利</p>
                                        <p>唐顿庄园中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>罗伯特·克劳利（Robert Crawley），格兰瑟姆伯爵，唐顿子爵，简称格兰瑟姆老爷，生于1866年[1]，是克劳利家族的家长，唐顿庄园的共同所有人（与其长女）。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://boardwalkempire.huiji.wiki/wiki/%E6%9F%A5%E7%90%86%C2%B7%E5%8D%A2%E8%A5%BF%E4%BA%9A%E8%AF%BA">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki12.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>查理·卢西亚诺</p>
                                        <p>大西洋帝国中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>“幸运的”查尔斯·卢西亚诺（Charles "Lucky" Luciano）是一位富有野心与前途的意大利裔美国私酒贩子和黑帮成员。其原型为同名历史人物。剧中由文森特?皮亚扎（Vincent Piazza）饰演。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://hearthstone.huiji.wiki/wiki/%E9%BB%91%E7%9F%B3%E5%B1%B1%E7%9A%84%E9%98%B4%E5%BD%B1">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki13.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>黑石山的阴影</p>
                                        <p>炉石传说中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>黑石山的阴影是《炉石传说》推出的首个乱斗模式。其国服开放时间为2015年6月18日03：00到2015年6月22日6：00。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://allglory.huiji.wiki/wiki/%E5%8F%B6%E4%BF%AE">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki14.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>叶修</p>
                                        <p>荣耀百科全书</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>叶修是《全职高手》的主角。他是人称“荣耀教科书”的全职业精通高手，荣耀网游第一批玩家，荣耀职业联赛初代选手，不光有辉煌的职业战绩，还创造了许多荣耀战斗技巧和赛场战术。在职业联盟初期带领嘉世战队取得三连冠，缔造了一代王朝，后来又在第十赛季带领新队兴欣战队再夺冠军。作为领队加入了首届荣耀世界邀请赛中国队。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://asoiaf.huiji.wiki/wiki/%E7%A7%98%E5%AF%86%E5%A9%9A%E7%BA%A6">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki15.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>秘密婚约</p>
                                        <p>冰与火之歌中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>秘密婚约（Secret marriage pact），是指在《冰与火之歌》系列小说正传开篇时的多年以前，在布拉佛斯秘密签署的一桩婚约。婚约承诺流亡狭海对岸的韦赛里斯?坦格利安王子将迎娶多恩亲王道朗?马泰尔的长公主亚莲恩小姐，多恩则将帮助坦格利安家族重夺被劳勃?拜拉席恩篡夺的铁王座。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://lotr.huiji.wiki/wiki/%E5%AE%89%E6%B3%95%E4%B9%8C%E6%A0%BC%E7%A0%BE%E6%96%AF">
                            <div class="relative">
                                <img src="/resources/frontpage/wiki16.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>安法乌格砾斯</p>
                                        <p>魔戒中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>安法乌格砾斯（Anfauglith），是贝烈瑞安德北部的一片荒漠焦土。它原本名为阿德嘉兰，是一片富饶的绿色平原。但在第一纪元455年爆发的骤火之战中，自桑戈洛锥姆倾泻出的烈焰河流将平原付之一炬，终成一片焦土。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://downtonabbey.huiji.wiki/wiki/%E6%9D%B0%E5%85%8B%C2%B7%E7%BD%97%E6%96%AF">
                            <div class="relative">
                                <img src="http://downtonabbey.huiji.wiki/uploads/8/87/JackRoss.png">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>杰克·罗斯</p>
                                        <p>唐顿庄园中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>杰克·罗斯（Jack Ross） ，生于1892年到1897间[1]，是一位美国黑人爵士音乐家与歌手。他曾经在芝加哥工作过，后来来到伦敦，在莲花爵士俱乐部演出。</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="wiki-entry" href="http://downtonabbey.huiji.wiki/wiki/%E7%90%86%E6%9F%A5%E5%BE%B7%C2%B7%E5%85%8B%E6%8B%89%E5%85%8B%E6%A3%AE">
                            <div class="relative">
                                <img src="http://downtonabbey.huiji.wiki/uploads/d/da/Dr._Clarkson.jpg">
                                <div class="wiki-info">
                                    <div class="entry-header">
                                        <p>理查德·克拉克森</p>
                                        <p>唐顿庄园中文维基</p>
                                    </div>
                                    <div class="entry-content">
                                        <p>理查德·克拉克森（Richard Clarkson）医生是为克劳利家族服务的本地医生，他是看着玛丽，伊迪丝和西比尔三位小姐长大的。克拉克森医生在1899年到1902年期间的布尔战争中服役。他被授予女王南非服役勋章以及国王南非服役勋章。 </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="cleared"></li>
                </ul>
            </div>
        </div>
        <div class="bottom">
            <div class="container">
                <?php $this->includePage('Bootstrap:Footer'); ?>
                <footer>
                    <p><a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://home.huiji.wiki/wiki/%E7%81%B0%E6%9C%BA%E5%81%9C%E6%9C%BA%E5%9D%AA">灰机停机坪</a>|<a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://home.huiji.wiki/wiki/%E7%BB%B4%E5%9F%BA%E5%AE%B6%E5%9B%AD%E8%AE%A1%E5%88%92">维基家园计划</a>|<a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://home.huiji.wiki/wiki/%E5%AE%87%E5%AE%99%E5%B0%BD%E5%A4%B4%E7%9A%84%E7%81%B0%E6%9C%BAwiki">关于灰机wiki</a><br>Powered by <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://mediawiki.org">MediaWiki</a> <a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet" href="http://www.miitbeian.gov.cn/">京ICP备15015138号</a></p>                                          
                </footer>
            </div><!-- container -->
        </div><!-- bottom -->
    </div>
</div>
