   <!-- Sidebar -->
<aside id="sidebar-wrapper">

    <ul class="sidebar-nav" id="sidebar-content">
        <li class="sidebar-header">
            <a href="<?php echo $this->getSkin()->getTitle()->getFullUrl(); ?>"><?php echo $this->getSkin()->getTitle()->getPrefixedText(); ?></a>
            <?php
                foreach ($this->data['content_actions'] as $key => $value) {
                    if (substr($key, 0, 6) == "nstab-") {
                        $tab = $key;                    
                        break;
                    }
                }
                // if (!$this->getSkin()->getTitle()->isTalkPage()){
                //     unset($this->data['content_actions']['nstab-main']);
                // }
                if ( !$this->getSkin()->getTitle()->isTalkPage() && !$this->getSkin()->getTitle()->isSpecialPage()){
                    echo '<button class="mw-ui-button mw-ui-progressive" id="'.$this->data['content_actions']['talk']['id'].'">
                    <a href="'.$this->data['content_actions']['talk']['href'].'">'.$this->data['content_actions']['talk']['text'].'</a></button>';
                    unset( $this->data['content_actions']['talk']);
                } else if ( !$this->getSkin()->getTitle()->isSpecialPage() ){
                    echo '<button class="mw-ui-button mw-ui-progressive" id="'.$this->data['content_actions'][$tab]['id'].'">
                    <a href="'.$this->data['content_actions'][$tab]['href'].'">'.$this->data['content_actions'][$tab]['text'].'</a></button>';
                    
                    unset( $this->data['content_actions']['talk']);
                }
                unset($this->data['content_actions'][$tab]);
             ?>
        </li>
        <li class="sidebar-behavior">
            <ul>
            <?php         
            if ($this->data['isarticle']){
                $this->data['content_actions']['info'] = array(
                        "key" => "info",
                        "href" => $this->getSkin()->getTitle()->getFullUrl(array('action'=>'info')),
                        "class" => "info ",
                        "text" => "信息",
                    );                
            }
            if ( $this->data['isarticle'] && $wgUser->isEmailConfirmed() && ($NS == NS_TEMPLATE || $NS == NS_MODULE ) && $this->skin->getTitle()->exists()){
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
                        "href" => "javascript:void(0)",
                        "class" => "purge ",
                        "text" => "清除缓存",
                    );
            }
            if ( $wgUser->isAllowed('quickdebug') ){
                $this->data['content_actions']['debug'] = array(
                        "key" => "debug",
                        "href" => "javascript:void(0)",
                        "class" => "debug ",
                        "text" => "调试",
                    );
            }
            $subnav_links = $this->listAdapter( $this->data['content_actions']);
            if( $NS !== NS_USER || !$this->getSkin()->getTitle()->isSubpage()){
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
                    <li><a href="<?php echo $url_prefix; ?>Special:文件上传" class="upload-a-file" rel="nofollow"><i class="fa fa-upload"></i> 上传文件</a></li>
                <?php } ?>
                <!-- <li><a href="<?php echo $url_prefix; ?>Special:CreatePoll" class="poll-page" rel="nofollow"><i class="icon-pie-chart "></i> 创建投票</a></li> -->
                <?php wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this ) );?>
                <li><a target="_blank" href="http://www.huiji.wiki/wiki/帮助讨论:用户手册" class="upload-a-file" rel="nofollow"><i class="fa fa-question-circle"></i> 求助提问</a></li>
                <?php if ( $wgHuijiPrefix !== 'www' && $this->data['isarticle'] ) { ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-flask"></i> 特殊页面 <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $url_prefix.'Special:whatlinkshere/'.$this->getSkin()->getTitle()->getPrefixedText()?>" class="what-links-here" rel="nofollow"><i class="fa fa-link "></i> 链入页面</a></li>
                        <li><a href="<?php echo $url_prefix.'Special:最近链出更改/'.$this->getSkin()->getTitle()->getPrefixedText()?>" class="related-changes" rel="nofollow"><i class="fa fa-paperclip "></i> 相关更改</a></li>
                        <li><a href="<?php echo $url_prefix; ?>Special:SpecialPages" class="special-pages" rel="nofollow"><i class="fa fa-star-o"></i> 全部特殊页面</a></li>
                    </ul>
                </li>
                <?php } ?>
                <?php if ( $wgHuijiPrefix !== 'www' ) { ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bar-chart"></i> 数据统计 <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $url_prefix; ?>Special:EditRank" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-list-ol"></i> 本站编辑排行</a></li>
                        <li><a href="<?php echo $url_prefix; ?>Special:TopUsers" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-th-list"></i> 等级积分排行</a></li>
                        <li><a href="<?php echo $url_prefix; ?>Special:统计信息" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-line-chart"></i> 本站统计信息</a></li>
                    </ul>
                </li>
                <?php } ?>
                <?php if ( $wgUser->isLoggedIn() && $wgUser->isAllowed('editinterface')){ ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-briefcase"></i> 管理员选项 <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $url_prefix; ?>特殊:AdminDashboard" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-tachometer"></i> 管理面板</a></li>
                        <li><a href="<?php echo $url_prefix; ?>Bootstrap:Subnav" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-bars"></i> 修改站点导航</a></li>
                        <li><a href="<?php echo $url_prefix; ?>Bootstrap:Footer" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-hand-o-down"></i> 修改站点页脚</a></li>
                        <?php if ($wgUser->getOption('showeditcss') == 1): ?>
                        <li><a href="<?php echo $url_prefix; ?>Mediawiki:Common.css" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-css3"></i> 修改站点CSS</a></li>
                        <?php endif; ?>
                        <?php if ($wgUser->getOption('showeditjs') == 1): ?>
                        <li><a href="<?php echo $url_prefix; ?>Mediawiki:Common.js" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-code"></i> 修改站点js</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo $url_prefix; ?>Bootstrap:自定义主题" class="bootstrap-subnav" rel="nofollow"><i class="fa fa-paint-brush"></i> 自定义主题</a></li>
                    </ul>
                </li>
                <?php } ?>
                <li class="sidebar-create">
                    <div class="mw-inputbox-centered" style="">
                        <form name="createbox" class="createbox" action="/index.php" method="get">
                            <input name="action" value="edit" type="hidden">
                            <input name="editintro" value="" type="hidden"><input name="summary" value="" type="hidden">
                            <input name="nosummary" value="" type="hidden"><input name="prefix" value="" type="hidden">
                            <input name="minor" value="" type="hidden">
                            <div class="input-group create-group btn-group">
                                <input name="title" class="createboxInput form-control" placeholder="新页面名称" value="" dir="ltr" type="text">
                                <button name="create" id="createbtn" class="btn btn-primary disabled" type="submit" >创建</button>
                                <?php
                                    $pre = new Preloads(array("class"=>"form-control btn btn-primary disabled", "name"=>"preload"));
                                    echo $pre->getInputHtml('');
                                ?>
                            </div>
                        </form>
                    </div>
                </li>
            </ul>
        </li>
        <li class="sidebar-brand left-donate">
            <a href="#">
                支持<?php echo $wgSitename ?>
            </a>
            <div>
              <a href="/wiki/Special:Donate" class="button mw-ui-button mw-ui-progressive"><i class="fa fa-jpy" aria-hidden="true"></i> 加油</a>
            </div>
        </li>
        <li class="sidebar-brand left-manager">
            <a>管理员</a>
            <div>
            <?php
                $fanbox_link = SpecialPage::getTitleFor( 'UserList' );
                $group = 'sysop';
                // $ums = self::getSiteManager( $wgHuijiPrefix,$group );
                // foreach ($ums as $value) {
                //     $uname = User::newFromId( $value );
                //     $user_group = $uname->getEffectiveGroups();
                //     if ( !in_array('bot', $user_group) && !in_array('bot-global',$user_group) ) {
                //         $usersys['user_name'] = $uname->getName();
                //         $usersys['count'] = UserStats::getSiteEditsCount( $uname, $wgHuijiPrefix );
                //         $userPage = Title::makeTitle( NS_USER, $uname->getName() );
                //         $usersys['url'] = htmlspecialchars( $userPage->getFullURL() );
                //         $avatar = new wAvatar( $value, 'm' );
                //         $usersys['avatar'] = $avatar->getAvatarURL();
                //         $sysop[] = $usersys;
                //     }
                // }
                // foreach ($sysop as $key => $value) {
                //     $count[$key] = $value['count'];
                // }
                // array_multisort($count, SORT_DESC, $sysop);
                $sysop = $site->getUsersFromGroup($group);
                $nums = ( count($sysop) > 5 )?5:count($sysop);
                for ($j=0; $j < $nums; $j++) {
                    echo Linker::linkKnown(User::newFromName($sysop[$j]['user_name'])->getUserPage(), $sysop[$j]['avatar'], [], [], ['known','no-designation']);
                    //echo '<a href="'.$sysop[$j]['url'].'"  title="'.$sysop[$j]['user_name'].'">'.$sysop[$j]['avatar'].'</a>';
                }
                if ( count($sysop) > 5 ) {
                    echo Linker::linkKnown( $fanbox_link, '>>', array('class'=> 'more'), array( 'group' => $group,'limit' => 50 ) );
                }
                ?>
            </div>
        </li>
        <?php if ($this->isPrimaryContent() ) { ?>
        <li class="sidebar-brand left-author">
            <a href="#">
                主要编辑者
            </a>
            <?php
                $options = array();
                $contributors = new Contributors( $this->getSkin()->getTitle(), $options );
                echo $contributors->getNormalList( $wgLang );
            // $contrib = '{{#contributors:{{FULLPAGENAME}}}}';
            // $wgParserOptions = new ParserOptions($wgUser);
            // $parserOutput = $wgParser->parse($contrib, $this->getSkin()->getTitle(), $wgParserOptions);
            // echo $parserOutput->getText();
            ?>
        </li>
        <?php } ?>
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
    <div class="sidebar-toggle"><i class="fa fa-angle-right"></i></div>
</aside>
<!-- /#sidebar-wrapper -->
