   <!-- Sidebar -->
<div id="sidebar-wrapper">

    <ul class="sidebar-nav" id="sidebar-content">
        <li class="sidebar-header">
            <a href="<?php echo $this->getSkin()->getTitle()->getFullUrl(array('action'=>'info')); ?>"><?php echo $this->getSkin()->getTitle()->getPrefixedText(); ?></a>
            <?php
                if ( isset( $this->data['content_actions']['watch'])){
                    echo '<button class="mw-ui-button mw-ui-progressive" id="'.$this->data['content_actions']['watchlist']['id'].'">
                    <a href="'.$this->data['content_actions']['watch']['href'].'">'.$this->data['content_actions']['watch']['text'].'</a></button>';
                    unset( $this->data['content_actions']['watch']);
                } else if ( isset( $this->data['content_actions']['unwatch'])){
                    echo '<button class="mw-ui-button mw-ui-progressive" id="'.$this->data['content_actions']['unwatch']['id'].'">
                    <a href="'.$this->data['content_actions']['unwatch']['href'].'">'.$this->data['content_actions']['unwatch']['text'].'</a></button>';
                    unset ( $this->data['content_actions']['unwatch']);
                }
             ?>

        </li>
        <li class="sidebar-behavior">
            <ul>
            <?php
            if ( $this->data['isarticle'] && $wgUser->isEmailConfirmed() && ($NS == NS_TEMPLATE || $NS == NS_MODULE )){
                $this->data['content_actions']['fork'] = array(
                        "key" => "fork",
                        "href" => "#",
                        "class" => "wiki-copy ",
                        "text" => "搬运",
                    );
            }
            if ( $this->data['isarticle'] && $wgUser->isAllowed('quickpurge') ){
                $this->data['content_actions']['purge'] = array(
                        "key" => "purge",
                        "href" => "?action=purge",
                        "class" => "purge ",
                        "text" => "清除缓存",
                    );
            }
            if ( $wgUser->isAllowed('quickdebug') ){
                $this->data['content_actions']['debug'] = array(
                        "key" => "debug",
                        "href" => "?debug=1",
                        "class" => "debug ",
                        "text" => "调试",
                    );
            }
            $subnav_links = $this->listAdapter( $this->data['content_actions']);
            if( $NS !== NS_USER && $NS !== NS_USER_TALK){
                echo $this->nav( $subnav_links );
            }
            ?>
            </ul>
        </li>
        <li class="sidebar-brand left-tool">
            <a href="#">
                站点工具
            </a>
            <ul>
                <li><a href="<?php echo $url_prefix; ?>Special:RecentChanges" class="recent-changes" rel="nofollow"><i class="fa fa-edit"></i> 最近更改</a></li>
                <li><a href="<?php echo $url_prefix; ?>Special:Randompage" class="random-page" rel="nofollow"><i class="fa fa-random "></i> 随机页面</a></li>
                <?php if ( $wgEnableUploads ) { ?>
                    <li><a href="<?php echo $url_prefix; ?>Special:Upload" class="upload-a-file" rel="nofollow"><i class="fa fa-upload"></i> 上传文件</a></li>
                <?php } ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bar-chart"></i> 数据统计 <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $url_prefix; ?>Special:EditRank" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-list-ol"></i> 本站编辑排行</a></li>
                        <li><a href="<?php echo $url_prefix; ?>Special:TopUsers" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-th-list"></i> 等级积分排行</a></li>
                        <li><a href="<?php echo $url_prefix; ?>Special:统计信息" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-line-chart"></i> 统计信息</a></li>
                        <li><a href="<?php echo $url_prefix; ?>Special:SpecialPages" class="special-pages" rel="nofollow"><i class="fa fa-star-o"></i> 特殊页面</a></li>
                    </ul>
                </li>
                <?php if ( $wgUser->isLoggedIn() && $wgUser->isAllowed('editinterface')){ ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-briefcase"></i> 管理员选项 <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $url_prefix; ?>特殊:AdminDashboard" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-tachometer"></i> 管理面板</a></li>
                        <li><a href="<?php echo $url_prefix; ?>Bootstrap:Subnav" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-bars"></i> 修改站点导航</a></li>
                        <li><a href="<?php echo $url_prefix; ?>Bootstrap:Footer" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-hand-o-down"></i> 修改站点页脚</a></li>
                        <li><a href="<?php echo $url_prefix; ?>Mediawiki:Common.css" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-css3"></i> 修改站点CSS</a></li>
                        <li><a href="<?php echo $url_prefix; ?>Mediawiki:Common.js" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-code"></i> 修改站点js</a></li>
                    </ul>
                </li>
                <?php } ?>
                <li class="sidebar-create">
                    <div class="mw-inputbox-centered" style="">
                        <form name="createbox" class="createbox" action="/index.php" method="get">
                            <input name="action" value="edit" type="hidden"><input name="preload" value="" type="hidden">
                            <input name="editintro" value="" type="hidden"><input name="summary" value="" type="hidden">
                            <input name="nosummary" value="" type="hidden"><input name="prefix" value="" type="hidden">
                            <input name="minor" value="" type="hidden">
                            <div class="input-group create-group">
                                <input name="title" class="createboxInput form-control" placeholder="新页面名称" value="" dir="ltr" type="text">
                                <span class="input-group-btn">
                                    <input name="create" class="createboxButton btn btn-default" type="submit" value="创建" disabled>
                                </span>
                            </div>
                        </form>
                    </div>
                </li>
            </ul>
        </li>
        <li class="sidebar-brand left-manager">
            <a>管理员</a>
            <div>
            <?php
                $fanbox_link = SpecialPage::getTitleFor( 'UserList' );
                $group = 'sysop';
                $ums = self::getSiteManager( $wgHuijiPrefix,$group );
                foreach ($ums as $value) {
                    $uname = User::newFromId( $value );
                    $user_group = $uname->getEffectiveGroups();
                    if ( !in_array('bot', $user_group) && !in_array('bot-global',$user_group) ) {
                        $usersys['user_name'] = $uname->getName();
                        $usersys['count'] = UserStats::getSiteEditsCount( $uname, $wgHuijiPrefix );
                        $userPage = Title::makeTitle( NS_USER, $uname->getName() );
                        $usersys['url'] = htmlspecialchars( $userPage->getFullURL() );
                        $avatar = new wAvatar( $value, 'm' );
                        $usersys['avatar'] = $avatar->getAvatarURL();
                        $sysop[] = $usersys;
                    }
                }
                foreach ($sysop as $key => $value) {
                    $count[$key] = $value['count'];
                }
                array_multisort($count, SORT_DESC, $sysop);
                $nums = ( count($ums) > 5 )?5:count($ums);
                for ($j=0; $j < $nums; $j++) {
                    echo '<a href="'.$sysop[$j]['url'].'"  title="'.$sysop[$j]['user_name'].'">'.$sysop[$j]['avatar'].'</a>';
                }
                if ( count($ums) > 5 ) {
                    echo Linker::link( $fanbox_link, '>>', array('class'=> 'more'), array( 'group' => $group,'limit' => 50 ) );
                }
                ?>
            </div>
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
    </ul>
</div>
<!-- /#sidebar-wrapper -->
