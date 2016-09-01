<?php
Class HuijiSkinTemplate extends BaseTemplate {
	/**
	 * Reserved Page parts
	 */
	private static $pageParts = array('首页/Admin','首页/Header','Bootstrap:TitleBar','Bootstrap:Footer','Bootstrap:Subnav');
	private static $sharedPageParts = array('Mediawiki:Sitenotice');
	public static function getPageParts(){
		return self::$pageParts;
	}
	public static function getSharedParts(){
		return self::$sharedPageParts;
	}
	/**
     * Template filter callback for Bootstrap skin.
     * Takes an associative array of data set from a SkinTemplate-based
     * class, and a wrapper for MediaWiki's localization database, and
     * outputs a formatted page.
     *
     * @access private
     */
    public function execute() {}
    /**
     * detect if the current page is “primary page”
     */
    protected function isPrimaryContent(){
        global $wgRequest;
        $title = $this->getSkin()->getTitle();
        if ($title->getFullText() === 'Bootstrap:自定义主题'){
            return true;
        }
        return !($title->isMainPage()) && $this->getSkin()->getOutput()->isArticle() && !($title->isTalkPage()) && ($title->getNamespace()!=NS_USER) && $wgRequest->getText( 'action' )=='' && $title->exists();
    }
    /**
     * Generate subtitles based on title implications
     */
    protected function getSub($NS){
        $res = '';
        if (!Hooks::run( 'SkinGetSub', array($this->getSkin()->getTitle(), &$res, $this->getSkin()->getContext()) ) ){
            return $res;
        }
        if ($this->isPrimaryContent() ){
            $title = $this->skin->getTitle();
            if ($title->getFullText() === 'Bootstrap:自定义主题'){
                echo "这篇条目实际上储存于灰机的模板管理站，并不能真正的编辑。";
                return;
            }
            $rev = Revision::newFromTitle($title);
            $revId = $rev->getId();
            $editorId = $rev->getUser();
            if ($editorId !== 0){
                $linkAttr = array('class' => 'mw-ui-anchor mw-ui-progressive mw-ui-quiet', 'rel'=>'nofollow');
                $editorAttr = array('class' => 'mw-ui-anchor mw-ui-progressive mw-ui-quiet mw-userlink', 'rel'=>'nofollow');
                $editor = User::newFromId( $editorId );
                $editorName = $editor->getName();
                $editorLink = Linker::Link($editor->getUserPage(), $editorName, $editorAttr);
                $bjtime = strtotime( $rev->getTimestamp() ) + 8*60*60;
                $edittime = HuijiFunctions::getTimeAgo( $bjtime );
                $diff = SpecialPage::getTitleFor('Diff',$revId);
                $diffLink = Linker::LinkKnown($diff,'修改了',$linkAttr);
                $thankLink = '';
                if ( class_exists( 'EchoNotifier' )
                    && $this->skin->getUser()->isLoggedIn() 
                    && $this->skin->getUser()->getId() !== $editorId
                ) {
                    // Load the module for the thank links
                    $thankLink .= '（<a class="mw-ui-anchor mw-ui-progressive mw-ui-quiet mw-thanks-thank-link" data-revision-id="'
                        .$revId.'" href="javascript:void(0);">'.wfMessage('thanks-thank').'</a>）';
                }
                //Make it a td to reuse ext.thank.revthank   
                $res.= $editorLink.'&nbsp于'.$edittime.'前'.$diffLink.'此页面'.$thankLink;

            }
            if ($NS == NS_TEMPLATE || $NS == NS_MODULE ){
                $pageId = $this->skin->getTitle()->mArticleID;
                $forkCount = TemplateFork::getForkCountByPageId( $pageId, $wgHuijiPrefix );
                $text = '本'.(($NS == NS_TEMPLATE)?'模板':'模块').'已被搬运'.$forkCount.'次';
                if ( $forkCount > 0 ) {
                    $res.= '&nbsp;&nbsp;&nbsp;<span class="label label-info" data-toggle="tooltip" data-placement="top" title="'.$text.'">'.$forkCount.'次搬运</span>';
                }
            }
        }
        return $res;
    }
    /**
     * Render one or more navigations elements by name, automatically reveresed
     * when UI is in RTL mode
     */
    protected function nav( $nav,$nl = '' ) {
        $output = '';
        foreach ( $nav as $topItem ) {
            $pageTitle = Title::newFromText( $topItem['link'] ?: $topItem['title'] );
            if ( array_key_exists( 'sublinks', $topItem ) ) {
                if($nl == 'set'){
                $output .= '<li class="dropdown set">';
                $output .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $topItem['title'] . '</a>';
                $output .= '<ul class="dropdown-menu set-menu">';
                }else{
                $output .= '<li class="dropdown">';
                                $output .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $topItem['title'] . '&nbsp;<span class="caret"></span></a>';
                                $output .= '<ul class="dropdown-menu">';
                }
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
                        if ( array_key_exists('id', $subLink) ){
                            $id = "id = "."pt-{$subLink['id']} ";
                        }
                        if (array_key_exists('rel', $subLink) ){
                            $rel = "rel = {$subLink['rel']} ";
                        }
                        $slug = strtolower( str_replace(' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', trim( strip_tags( $subLink['title'] ) ) ) ) );
                        $output .= "<li {$subLink['attributes']} $id><a href='{$href}' class='{$subLink['class']} {$slug} {$rel}'>{$subLink['title']}</a>";
                    }//end else
                }
                $output .= '</ul>';
                $output .= '</li>';
            } else {
                // $requestUrl = $this->getSkin()->getRequest()->getRequestURL();
                // $myLink = $topItem['link'];
                // $query = explode('&', $requestUrl);
                // if (count($query) > 1 && strpos( $requestUrl ,'action' )!== false){
                //     $match = ($query[1] === explode('&amp;', $myLink)[1]) &&  ($query[0] === explode('&amp;', $myLink)[0]);
                // }else{
                //     if (strpos($requestUrl, 'index.php?title=') != false ){
                //         $requestUrl = str_replace("index.php?title=", "wiki/", $requestUrl);
                //     }
                //     $match = (strpos($requestUrl , $myLink) !==false);
                // }
                if (array_key_exists('rel', $topItem)){
                    $rel = "rel = \"".$subLink['rel']."\" ";
                }
                $hasId = $topItem['id']?' id="ca-'.$topItem['id'].'"':'';
                $output .= '<li class="primary-nav dropdown"'.$hasId. '><a href="' . ( $topItem['link']  ) .'" '.$rel.'>' . $topItem['title'] . '</a></li>';
            }//end else
        }//end foreach
        return $output;
    }//end nav
    /**
     * Render one or more navigations elements by name as notifications, automatically reveresed
     * when UI is in RTL mode
     */
    protected function nav_notification( $nav ) {
        $output = '';
        foreach ( $nav as $topItem ) {
            if ($topItem == ''){
                continue;
            }
            $output .= '<li id="pt-'.$topItem['id'].'"><a class="'.$topItem['class'].'" href="' . ( $topItem['link']  ) . '">' . $topItem['title'] . '</a>'.'</li>';
        }//end foreach
        return $output;
    }//end nav

    /**
     * Render one or more navigations elements by name in a dropdown select style, automatically reveresed
     * when UI is in RTL mode
     */
    protected function nav_select( $nav ) {
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
    protected function get_page_links( $source ) {
        $titleBar = self::getPageRawText( $source );
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
    protected function alertAdapter($array){
        $nav = array();
        $item = $array['notifications-alert'];
        $key = 'notifications-alert';
        $link = array(
            'id' => Sanitizer::escapeId( $key ),
            'attributes' => $item['attributes'],
            'link' => htmlspecialchars( $item['href'] ),
            'key' => $item['key'],
            'class' => htmlspecialchars( $item['class'][0] ),
            'title' => htmlspecialchars( $item['text'] ),
            'icon' => '<i class="fa fa-bell-o"></i>',
        );
        $nav[] = $link;
        return $nav;        
    }
    /* notification adapter */
    protected function messageAdapter($array){
        if (!empty($array['notifications-message'])){
            $nav = array();
            $item = $array['notifications-message'];
            $key = 'notifications-message';
            $link = array(
                'id' => Sanitizer::escapeId( $key ),
                'attributes' => $item['attributes'],
                'link' => htmlspecialchars( $item['href'] ),
                'key' => $item['key'],
                'class' => htmlspecialchars( $item['class'][0] ),
                'title' => htmlspecialchars( $item['text'] ),
                'icon' => '<i class="fa fa-envelope-o"></i>',
            );
            $nav[] = $link;
            return $nav;                   
        } 
        return '';

    }
    /* dropdown button adapter (useful for personal tools) */
    protected function dropdownAdapter( $array, $title, $which ) {
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
                case '称号': $icon = 'graduation-cap'; break;
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

        return $this->nav( $nav,$nl = 'set' );
    }//end get_array_links

    /* general a list of links adater */
    protected function listAdapter( $array ) {
        $nav = array();
        foreach( $array as $key => $item ) {

            $link = array(
                'id' => Sanitizer::escapeId( $key ),
                'attributes' => $item['attributes'],
                'link' => htmlspecialchars( $item['href'] ),
                'key' => $item['key'],
                'class' => htmlspecialchars( $item['class'] ),
                'title' => htmlspecialchars( $item['text'] ),
                'rel' => 'nofollow',
            );
            switch( $link['title'] ) {
                case '页面': $icon = 'file'; break;
                case '信息': $icon = 'file'; break;
                case '项目页面': $icon = 'file'; break;
                case '讨论': $icon = 'comment'; break;
                case '编辑': $icon = 'pencil'; break;
                case '编辑源代码': $icon = 'code';
                    $link['id'] = 'huiji-edit';
                    break;
                case '历史': $icon = 'clock-o'; break;
                case '删除': $icon = 'remove'; break;
                case '移动': $icon = 'arrows'; break;
                case '保护': $icon = 'lock'; break;
                case '更改保护': $icon = 'unlock-alt'; break;
                case '监视': $icon = 'eye'; break;
                case '取消监视': $icon = 'eye-slash'; break;
                case '创建': $icon = 'plus'; break;
                case '创建源代码': $icon = 'plus'; 
                	$link['id'] = 'huiji-create';
                	break;
                case '查看源代码': $icon = 'file-code-o'; break;
                case '分类': $icon = 'files-o'; break;
                case '模板': $icon = 'puzzle-piece'; break;
                case '模块': $icon = 'cube'; break;
                case '特殊页面': $icon = 'flask'; break;
                case '链入页面': $icon = 'link'; break;
                case '搬运': $icon = 'code-fork'; break;
                case '清除缓存': $icon = 'eraser'; break;
                case '调试': $icon = 'plug'; break;
                default: $icon = 'clone'; break;
            }
            $link['title'] = '<i class="fa fa-' . $icon . '"></i> ' . $link['title'];
            $nav[] = $link;
        }//end foreach
        return $nav ;
    }//end get_edit_links   
    
    #    Output easy-to-read numbers
    #    by james at bandit.co.nz
    static function format_nice_number($n) {
        // first strip any formatting;
        $n = (0+str_replace(",","",$n));
        
        // is this a number?
        if(!is_numeric($n)) return false;
        
        // now filter it;
        if($n>1000000000000) return round(($n/1000000000000),1).'T';
        else if($n>1000000000) return round(($n/1000000000),1).'B';
        else if($n>1000000) return round(($n/1000000),1).'M';
        else if($n>1000) return round(($n/1000),1).'K';
        
        return number_format($n);
    }

    /**
     * Get Page text from a page on the wiki
     * @param $title the title object
     */
    static function getPageRawText($title) {
    	$pageTitle = Title::newFromText($title);
        $output = self::getPageRawTextFromCache( $pageTitle );
        if ( $output != '' ) {
            return $output;
        } else {
            return self::getPageRawTextFromPage( $pageTitle );
        }
    }
    static function getPageRawTextFromCache( $pageTitle ){
        global $wgMemc;
        $output = '';
        if ($pageTitle->isExternal()){
        	$key = wfForeignMemcKey( 'huiji', '', 'page', 'getPageRaw', 'shared', $pageTitle->getFullText() );
        	$output = $wgMemc->get( $key );
        } else {
        	$key = wfMemcKey( 'page', 'getPageRaw', 'all', $pageTitle->getFullText() );
        	$output = $wgMemc->get( $key );
        }
        return $output;
    }
    static function getPageRawTextFromPage( $pageTitle ){
        global $wgParser, $wgMemc, $wgUser;
		$wgParserOptions = new ParserOptions($wgUser);
        if ($pageTitle->isExternal()){
        	$content = $wgParser->interwikiTransclude( $pageTitle, 'raw');
        	$output = $wgParser->preprocess($content, $pageTitle, $wgParserOptions );
        	$key = wfForeignMemcKey( 'huiji','','page', 'getPageRaw', 'shared', $pageTitle->getFullText() );
        	 $wgMemc->set( $key, $output );
        } else {
	        if(!$pageTitle->isKnown()) {
	            return 'Create the page [[' . $pageTitle->getFullText() . ']]';
	        } else {
	        	$key = wfMemcKey( 'page', 'getPageRaw', 'all', $pageTitle->getFullText() );
	            $page = new WikiPage($pageTitle);
	            
	            // get the text as static wiki text, but with already expanded templates,
	            // which also e.g. to use {{#dpl}} (DPL third party extension) for dynamic menus.
	            $output = $wgParser->preprocess($page->getContent()->getNativeData(), $pageTitle, $wgParserOptions );
	            $wgMemc->set( $key, $output );
	        }        	
        }
        return $output;

    }
    /**
     * include a fully parsed page.
     * @param title object
     */
    static function includePage($title) {
        global $wgParser, $wgUser;
        $pageTitle = Title::newFromText($title);
        if(!$pageTitle->isKnown()) {
            echo 'The page [[' . $title . ']] was not found.';
        } else {
            $wgParserOptions = new ParserOptions($wgUser);
            $parserOutput = $wgParser->parse(self::getPageRawText($title), $pageTitle, $wgParserOptions);
            echo $parserOutput->getText();
        }
    }
    /**
     * get a json file stored on wiki page. Then decode that file.
     * @return obj;
     */
    static function getIndexBlock( $source ) {
        $content = self::getPageRawText( $source );
        $result = json_decode( $content );
        return $result;
    }

    //show header
    function showHeader(){
        global $wgUser, $wgSitename, $wgHuijiPrefix;
        global $wgNavBarClasses, $wgLogo;
        if ( $wgHuijiPrefix == 'test'|| $wgHuijiPrefix == 'www') {
            $key = 'key';
            $specialPage = 'Special:GlobalSearch';
            $searchFormId = 'globalSearchInput';
        }else{
            $key = 'search';
            $specialPage = 'Special:Search';
            $searchFormId = 'searchInput';
        }
        // $output = '';
        $rec = file_get_contents( __DIR__."/View/Recommand.html" );
        $output ='
            <header class="header navbar navbar-default navbar-fixed-top'.$wgNavBarClasses.'" role="navigation">
                <div class="navbar-container">
                    <div class="navbar-header">
                        <a rel="nofollow" class="navbar-brand" href="#menu-toggle" id="menu-toggle">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <a class="navbar-toggle">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                        <a class="visible-xs-inline-block search-toggle">
                            <span class="fa fa-search navbar-search"></span>
                        </a>
                        <a title="灰机wiki" href="http://huiji.wiki" class="navbar-brand"><img alt="Logo" src="'.$wgLogo.'"> </a>
                        <a class="visible-sm-block wiki-toggle">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                        <form class="navbar-search navbar-form" action="/index.php" id="searchformphone" role="search">
                            <div>
                                <input class="form-control" type="search" name="'.$key.'" placeholder="在'.$wgSitename.'内搜索" title="搜索'.$wgSitename.' [ctrl-option-f]" accesskey="f" id="searchInputPhone" autocomplete="off">
                                <input type="hidden" name="title" value="'.$specialPage.'">
                            </div>
                        </form>
                    </div>

                    <nav class="navbar-collapse">
                        <ul id="icon-section" class="nav navbar-nav">
                                <li class="dropdown">
                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">推荐wiki <span class="caret"></span></a>
                                  '. $rec .'
                                </li>
                                <li class="hidden-xs">
                                    <a href="http://www.huiji.wiki/wiki/special:CreateWiki">创建wiki</a>
                                </li>
                                <li class="hidden-xs hidden-sm">
                                    <a href="http://www.huiji.wiki/wiki/帮助:用户手册">帮助文档</a>
                                </li>
                        </ul>';
                    if ( $wgUser->isLoggedIn() ) {
                        if ( count( $this->data['personal_urls'] ) > 0 ) {
                            $avatar = new wAvatar( $wgUser->getID(), 'l' );
                            // $user_icon = '<span class="user-icon"><img src="https://secure.gravatar.com/avatar/'.md5(strtolower( $wgUser->getEmail())).'.jpg?s=20&r=g"/></span>';
                            $user_icon = '<i class="fa fa-cog"></i>';
                            $name =  $wgUser->getName() ;
                            $personal_urls = $this->data['personal_urls'];
                            unset($personal_urls['uls']);
                            unset($personal_urls['notifications-alert']);
                            unset($personal_urls['notifications-message']);
                            unset($personal_urls['userpage']);
                            unset($personal_urls['mytalk']);
                            $personal_urls = array_slice($personal_urls, 0, 1, true) + 
                            array('designation' => array(
                                'text' => '称号',
                                'href' => '/wiki/Special:designation',
                                'active' => false,
                            ))+
                            array_slice($personal_urls, 1, count($personal_urls)-1, true);
                            $user_nav = $this->dropdownAdapter( $personal_urls, $user_icon, 'user' );
                            $user_alert = $this->nav_notification($this->alertAdapter($this->data['personal_urls']));
                            $user_message = $this->nav_notification($this->messageAdapter($this->data['personal_urls']));
                        }
                        $userPage = Title::makeTitle( NS_USER, $wgUser->getName() );
                        $userPageURL = htmlspecialchars( $userPage->getFullURL() );
                        /*$avatar = new wAvatar( $wgUser->getID(), 'l' );*/
                        $output .= '<ul'.$this->html('userlangattributes').' class="nav navbar-nav navbar-right navbar-user">';
                        $output .= '<li id="pt-user-icon"><a href="'.$userPageURL.'"><span class="user-icon" style="border: 0px;">'.$avatar->getAvatarURL().'</span><span class="hidden-xs">'.$wgUser->getName().'</span></a></li>';
                        $output .= $user_alert;
                        $output .= $user_message;
                        $output .= '<li id="pt-following" class="mw-echo-ui-notificationBadgeButtonPopupWidget"><a title="我关注的站点" href="/index.php?title=Special:ShowFollowedSites&user_id='.$wgUser->getID().'&target_user_id='.$wgUser->getID().'"><i class="fa fa-heart-o"></i></a>';
                        $output .= '</li>';
                        $output .= $user_nav;
                        $output .= '</ul>';
                    } else {  // else if is logged in
                                //old login
                        $output .= '<ul class="nav navbar-nav navbar-right navbar-login">
                            <li>'.
                                Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), '登录', array('id' => 'pt-login', 'rel'=>'nofollow', 'class'=> 'login'), array('returnto' =>htmlspecialchars( $this->getSkin()->getTitle()->getFullText() ) ) )  
                            .'</li>
                            <li>'.Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), '注册', array('id' => 'pt-createaccount', 'rel' => 'nofollow'),array('type' => 'signup') ).'
                            </li>
                        </ul>';
                    }
                    $output .= '<form class="navbar-search navbar-form table-cell hidden-xs" action="/index.php" id="searchform" role="search">
                            <div>
                                <span class="fa fa-search navbar-search"></span>
                                <input class="form-control" type="search" name="'.$key.'" placeholder="在'.$wgSitename.'内搜索" title="搜索'.$wgSitename.' [ctrl-option-f]" accesskey="f" id="'.$searchFormId.'" autocomplete="off">
                                <input type="hidden" name="title" value="'.$specialPage.'">
                            </div>
                        </form>
                    </nav>
                </div>
            </header>';
            
            return $output;
    }
}
?>
