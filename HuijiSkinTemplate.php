<?php
Class HuijiSkinTemplate extends BaseTemplate {
	/**
	 * Reserved Page parts
	 */
	private $pageParts = array('首页/Admin','Bootstrap:TitleBar','Bootstrap:Footer','Bootstrap:Subnav');
	private $sharedPageParts = array('Mediawiki:Sitenotice');
	public static function getPageParts(){
		return $this->pageParts;
	}
	public static function getSharedParts(){
		return 	$this->sharedPageParts;
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
     * Render one or more navigations elements by name, automatically reveresed
     * when UI is in RTL mode
     */
    protected function nav( $nav ) {
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
    protected function nav_notification( $nav ) {
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
    protected function notificationAdapter($array){
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

    /**
     * Display the wiki header area.
     */
    public function showHeader(){
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
?>
