<?php 
Class BootstrapMediawikiHooks {
    /**
     * Register <siteactivity> hook with the Parser
     *
     * @param $parser Parser
     * @return Boolean
     */
    public static $nextId = 0;
    public static function registerParserHook( &$parser ) {
        $parser->setHook( 'tab', 'BootstrapMediawikiHooks::getNavs' );
        $parser->setHook( 'dropdown', 'BootstrapMediawikiHooks::getDropdown');
        $parser->setHook( 'tip', 'BootstrapMediawikiHooks::getTooltip');
        $parser->setHook( 'pop', 'BootstrapMediawikiHooks::getPopover');
        $parser->setHook( 'alert', 'BootstrapMediawikiHooks::getAlert');
        $parser->setHook( 'collapse', 'BootstrapMediawikiHooks::getCollapse');
        $parser->setHook( 'accordion', 'BootstrapMediawikiHooks::getAccordion');
        $parser->setHook( 'carousel', 'BootstrapMediawikiHooks::getCarousel');
        $parser->setHook( 'callout', 'BootstrapMediawikiHooks::getCallout');
        $parser->setHook( 'heading', 'BootstrapMediawikiHooks::getHeading');
        $parser->setHook( 'rec', 'BootstrapMediawikiHooks::getRec');
        $parser->setHook( 'hover.css', 'BootstrapMediawikiHooks::getHoverCss');
        $parser->setHook( 'ihover.css', 'BootstrapMediawikiHooks::getIHoverCss');
        $parser->setHook( 'siteinfo', 'BootstrapMediawikiHooks::getSiteInfo');

        // $parser->setHook( 'siteactivity', 'getSiteActivity' );
        // $parser->setHook( 'siteactivity', 'getSiteActivity' );
        return true;
    }
    public static function getSiteInfo( $input, $args, $parser ){
        global $wgHuijiPrefix;
        if ($args['site']!=''){
            $site = WikiSite::newFromPrefix($args['site']);
            if (!$site->exists()){
                return "site {$args['site']} does not exist";
            }
        } else {
            $site = WikiSite::newFromPrefix($wgHuijiPrefix);
        }
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $content = $site->getDescription();
        $footer = '<em>创始人：</em>'.$site->getFounder()->getName().'<br><em>建立时间：</em>'.$site->getDate().'<br><em>类型：</em>'.$site->getType();
        $output =  $templateParser->processTemplate(
            'siteinfo',
            array(
                'content' => $content,
                'footer' => $footer,
            )
        );
        return $output;
    }
    public static function getIHoverCss( $input, $args, $parser ){
        $output = '<script type="text/javascript">window.onload = function(){mw.loader.load("skins.bootstrapmediawiki.ihover","text/css");}</script>';
        return $output;
    }
    public static function getHoverCss( $input, $args, $parser ){
        $output = '<script type="text/javascript">window.onload = function(){mw.loader.load("skins.bootstrapmediawiki.hover","text/css");}</script>';
        return $output;
    }
    public static function getRec( $input, $args, $parser ){
        global $wgHuijiPrefix, $wgOut;
        $arr = explode(PHP_EOL, $input);
        $li = array();
        $i = 0;
        foreach ($arr as $line){
            if (trim($line) == ''){
                // $i++;
                continue;
            }
            $t = Title::newFromText(trim($line));
            if (!($t instanceof Title)){
                continue;
            }
            $group = array();
            if ($t->isExternal()){
                $group['site'] = $t->getInterwiki();
            } else {
                $group['site'] = $wgHuijiPrefix;
            }
            if ($t->getNamespace() != 0){
                $group['title'] = $t->getNsText().":".$t->getText();
            } else {
                $group['title'] = $t->getText();
            }
            $group['id'] = $t->getArticleID();
            $group['siteName'] = HuijiPrefix::prefixToSiteName($group['site']);
            $i++;
            $li[] = $group;
        }
        $parser->getOutput()->setProperty('recbyuser', json_encode($li));
        // $wgOut->addJsConfigVars('wgRecByUser', '');
        // $templateParser = new TemplateParser(  __DIR__ . '/View' );
        // $output =  $templateParser->processTemplate(
        //     'rec',
        //     array(
        //         'li' => $li,
        //     )
        // );
        // return $output;
        return '';
    }
    public static function getHeading($input, $args, $parser ) {
        global $wgHuijiSuffix;
        $m = array();
        $bga = isset( $args['background'] ) ? $args['background'] : 'http://cdn'.$wgHuijiSuffix.'/shareduploads/uploads/d/d7/Huijibanner_default.png';
        $title = isset( $args['title'] ) ? $args['title'] : $parser->recursiveTagParse('{{PAGENAME}}');
        $subtitle = isset( $args['subtitle'] ) ? $args['subtitle'] : $parser->recursiveTagParse('{{SITENAME}}');
        $fontcolor = isset( $args['fontcolor'] ) ? $args['fontcolor'] : '#FFF';
        if (isset($input)) {
            $title = $input;
        }
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $output =  $templateParser->processTemplate(
            'heading',
            array(
                'bg' => $bga,
                'title' => $title,
                'subtitle' => $subtitle,
                'fontcolor' => $fontcolor,
            )
        );
        return $output;
    }
    public static function getNavs( $input, $args, $parser ) {
        // $parser->disableCache();
        $class = isset( $args['class'] ) ? $args['class'] : 'nav-tabs';
        $arr = explode(PHP_EOL, $input);
        $li = array();
        $i = 0;
        foreach ($arr as $line){
            if (trim($line) == ''){
                // $i++;
                continue;
            }
            $group = array();
            $temp = explode('|', $line);
            $group['active'] = ($i == 0?'active':'');
            $group['id'] = hash('sha1', $temp[0].$i, false);
            $group['title'] = $parser->recursiveTagParse($temp[0]);
            $group['content'] = isset($temp[1])?$parser->recursiveTagParse($temp[1]):'';
            $i++;
            $li[] = $group;
        }
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $output =  $templateParser->processTemplate(
            'navs',
            array(
                'class' => $class,
                'li' => $li,
            )
        );
        return $output;

        // $class = ( isset( $args['args'] ) && is_numeric( $args['args'] ) ) ? $args['args'] : 10;


    }
    public static function getDropdown( $input, $args, $parser ) {
        // global $wgUser;
        // $parser->disableCache();
        $class = isset( $args['class'] ) ? $args['class'] : 'default dropdown-toggle';
        $button = isset( $args['title'] ) ? $args['title'] : '下拉菜单';
        $id = isset( $args['id'] ) ? $args['id']: 'dropdown'.(self::$nextId++);
        $arr = explode(PHP_EOL, $input);
        $li = array();
        $i = 0;
        foreach ($arr as $line){
            if (trim($line) == ''){
                // $i++;
                continue;
            }
            $group = array();
            $temp = explode('|', $line);
            $group['a'] = $parser->recursiveTagParse('[['.$line.']]');
            $i++;
            $li[] = $group;
        }
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $output =  $templateParser->processTemplate(
            'dropdown',
            array(
                'class' => $class,
                'button' => $button,
                'id' => $id,
                'li' => $li,
            )
        );
        return $output;

        // $class = ( isset( $args['args'] ) && is_numeric( $args['args'] ) ) ? $args['args'] : 10;
    } 
    public static function getAccordion( $input, $args, $parser ) {
        // global $wgUser;
        // $parser->disableCache();
        $class = isset( $args['class'] ) ? $args['class'] : 'default';
        // $button = isset( $args['button'] ) ? $args['button'] : '下拉菜单';
        // $id = isset( $args['id'] ) ? $args['id']: hash('sha1', $button, false);
        $arr = explode(PHP_EOL, $input);
        $li = array();
        $i = 0;
        foreach ($arr as $line){
            if (trim($line) == ''){
                // $i++;
                continue;
            }
            $group = array();
            // $options = ParserOptions::newFromUser($wgUser);
            $temp = explode('|', $line);
            $group['title'] = $parser->recursiveTagParse($temp[0]);
            $group['body'] = isset($temp[1])?$parser->recursiveTagParse($temp[1]):'';
            $group['hid'] = hash('sha1', $group['title'].$i, false);
            $group['id'] = hash('sha1', $group['body'].$i, false);
            $i++;
            $li[] = $group;
        }
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $output =  $templateParser->processTemplate(
            'accordion',
            array(
                'class' => $class,
                'li' => $li,
            )
        );
        return $output;

        // $class = ( isset( $args['args'] ) && is_numeric( $args['args'] ) ) ? $args['args'] : 10;
    } 
    public static function getPopover( $input, $args, $parser ){
        // $parser->disableCache();
        $class = isset( $args['class'] ) ? $args['class'] : 'default dropdown-toggle';
        $placement = isset( $args['placement'] ) ? $args['placement'] : 'top';
        $title = isset( $args['title'] ) ? $args['title'] : '气泡标题';
        $content = isset( $args['content'] ) ? $args['content'] : '气泡内容';
        $trigger = isset( $args['trigger'] ) ? $args['trigger'] : 'focus';
        $text = $parser->recursiveTagParse($input);
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $output =  $templateParser->processTemplate(
            'popover',
            array(
                'class' => $class,
                'placement' => $placement,
                'title' => $title,
                'content' => $content,
                'text' => $text,
            )
        );
        return $output;       
    }
    public static function getTooltip( $input, $args, $parser ){
        // $parser->disableCache();
        $placement = isset( $args['placement'] ) ? $args['placement'] : 'top';
        $title = isset( $args['content'] ) ? $args['content'] : '提示消息';
        $text = $parser->recursiveTagParse($input);
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $output =  $templateParser->processTemplate(
            'tooltip',
            array(
                'placement' => $placement,
                'title' => $title,
                'text' => $text,
            )
        );
        return $output;       
    }
    public static function getAlert( $input, $args, $parser ){
        // $parser->disableCache();
        $class = isset( $args['class'] ) ? $args['class'] : 'danger';
        $text = $parser->recursiveTagParse($input);
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $output =  $templateParser->processTemplate(
            'alert',
            array(
                'class' => $class,
                'text' => $text,
            )
        );
        return $output;       
    }
    public static function getCallout( $input, $args, $parser ){
        // $parser->disableCache();
        $class = isset( $args['class'] ) ? $args['class'] : 'default';
        $text = $parser->recursiveTagParse($input);
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $output =  $templateParser->processTemplate(
            'callout',
            array(
                'class' => $class,
                'text' => $text,
            )
        );
        return $output;       
    }
    public static function getCollapse( $input, $args, $parser ){
        // $parser->disableCache();
        $class = isset( $args['class'] ) ? $args['class'] : 'default';
        $title = isset( $args['title'] ) ? $args['title'] : '剧透警告';
        $id = isset( $args['id'] ) ? $args['id']: 'collapse'.(self::$nextId++);
        $text = $parser->recursiveTagParse($input);
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $output =  $templateParser->processTemplate(
            'collapse',
            array(
                'class' => $class,
                'text' => $text,
                'title' => $title,
                'id' => $id,
            )
        );
        return $output;       
    }
    public static function getCarousel($input, $args, $parser){
        // global $wgUser;
        // $parser->disableCache();
        $id = isset( $args['id'] ) ? $args['id']:'carousel-generic'.(self::$nextId++);
        $interval = isset( $args['interval'] ) ? $args['interval']:'5000';
        $width = isset( $args['width'] ) ? $args['width']:null;
        // $button = isset( $args['button'] ) ? $args['button'] : '下拉菜单';
        // $id = isset( $args['id'] ) ? $args['id']: hash('sha1', $button, false);
        $arr = explode(PHP_EOL, $input);
        $li = array();
        $i = 0;
        foreach ($arr as $line){
            if (trim($line) == ''){
                // $i++;
                continue;
            }
            $group = array();
            $temp = explode('|', $line);
            // $options = ParserOptions::newFromUser($wgUser);
            $group['id'] = $id;
            $group['image'] = $parser->recursiveTagParse('[['.$temp[0].($width!=null?"|$width":"").']]');
            $group['caption'] = isset($temp[1])?$parser->recursiveTagParse($temp[1]):'';
            $group['i'] = $i;
            if ($i == 0) {
                $group['active'] = 'active';
            } else {
                $group['active'] = '';
            }
            // $group['caption'] = $parser->recursiveTagParse($temp[0]);
            // $group['body'] = $parser->recursiveTagParse($temp[1]);
            // $group['hid'] = hash('sha1', $group['title'].$i, false);
            // $group['id'] = hash('sha1', $group['body'].$i, false);
            $i++;
            $li[] = $group;
        }
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $output =  $templateParser->processTemplate(
            'carousel',
            array(
                'id' => $id,
                'li' => $li,
            )
        );
        return $output;        
    }


	/**
     * Update page's cache when someone edit the page(Admin,subnav,footer)
     */
    public static function onNewRevisionFromEditComplete( $article, Revision $rev, $baseID, User $user ){
        global $wgMemc, $wgParser, $wgHuijiPrefix;
        if ( in_array($article->getTitle()->getFullText(), HuijiSkinTemplate::getPageParts()) ){
            $option = new ParserOptions($user);
            $key = wfMemcKey( 'page', 'getPageRaw', 'all', $article->getTitle()->getFullText() );
            $output = $wgParser->preprocess($article->getContent()->getNativeData(), $article->getTitle(), $option );
            $wgMemc->set( $key, $output );
        } elseif ( $wgHuijiPrefix == 'www' && in_array($article->getTitle()->getFullText(), HuijiSkinTemplate::getSharedParts())) {
            $option = new ParserOptions($user);
            $key = wfForeignMemcKey( 'huiji','','page', 'getPageRaw', 'shared', $article->getTitle()->getFullText() );
            $output = $wgParser->preprocess($article->getContent()->getNativeData(), $article->getTitle(), $option );
            $wgMemc->set( $key, $output );            
        }
        return true;


        
    }
    public static function onMediaWikiPerformAction( $output, $article, $title, $user, $request, $wiki ) {
        global $wgMobile, $IP, $wgScriptPath, $wgLogo, $wgUploadPath, $wgUploadDirectory, $wgCdnScriptPath, $wgLoadScript, $wgStylePath, $wgExtensionAssetsPath,  $wgResourceBasePath;
        if ($user->isAllowed('editinterface') && !$wgMobile){
            $GLOBALS['wgCdnScriptPath'] = $wgScriptPath;
            $GLOBALS['wgLoadScript'] = "{$wgCdnScriptPath}/load.php";
            $GLOBALS['wgStylePath'] = "{$wgCdnScriptPath}/skins";
            $GLOBALS['wgExtensionAssetsPath'] = "{$wgCdnScriptPath}/extensions";
            $GLOBALS['wgResourceBasePath'] = $wgCdnScriptPath;     
        } 
        // if ($user->isAllowed('reupload')){
        //     $wgUploadPath       = "{$wgScriptPath}/uploads";
        //     #$wgUploadDirectory  = "{$IP}/uploads";            
        // }
        return true;
    }
    public static function onGalleryGetModes( array &$modeArray ) {
        $modeArray['traditional'] = 'PackedImageGallery';
    }

    public static function onGetDefaultSortkey( $title, &$sortkey ) { 
        $sortkey = CUtf8_PY::encode($title->getText(),'all');
        if (empty($sortkey)){
        	//put * here to walk around cases where CUtf8_PY returns an empty string.
        	$sortkey = "*";
        }
    }
    public static function addEditModule(EditPage $editPage, OutputPage $output ) {
        $output->addModules( 'skins.bootstrapmediawiki.videohandler');
        $output->addModules( 'ext.wikieditor.huijiextra.top' );
        $output->addModules( 'ext.wikieditor.huijiextra.bottom' );
        $output->addHeadItem('loader',
                '<script language="JavaScript">' . "\n" . 
                    'var showEditor = function() {' . "\n" . 
                    '// Animate loader off screen' . "\n" . 
                        '$(".se-pre-con").fadeOut("slow");' . "\n" . 
                        'var editFormSisyphus = $( "#editform" ).sisyphus( {' . "\n" . 
                            'locationBased: true, ' . "\n" . 
                            'timeout: 0,' . "\n" . 
                            'autoRelease: true,' . "\n" . 
                            'onBeforeRestore:function(){' . "\n" . 
                                '$("#autoRestoreModal").modal({' . "\n" . 
                                    'keyboard: false,' . "\n" . 
                                    'backdrop: "static"' . "\n" . 
                                '}); ' . "\n" . 
                                'return false;' . "\n" . 
                            '}' . "\n" . 
                        '} ); ' . "\n" . 
                    '}' . "\n" .
                    'if(window.addEventListener){' . "\n" . 
                        'window.addEventListener("load", showEditor)' . "\n" . 
                    '}else{' . "\n" . 
                        'window.attachEvent("onload", showEditor)' . "\n" . 
                    '}' . "\n" .  
                '</script>');  
        $output->addHeadItem('loadercss',
                '<style>' . "\n" . 
                    '.no-js #loader { display: none;  }' . "\n" . 
                    '.js #loader { display: block; position: absolute; left: 100px; top: 0; }' . "\n" . 
                    '.se-pre-con {' . "\n" . 
                        'position: fixed;' . "\n" . 
                        'left: 0px;' . "\n" . 
                        'top: 0px;' . "\n" . 
                        'width: 100%;' . "\n" . 
                        'height: 100%;' . "\n" . 
                        'z-index: 9999;' . "\n" . 
                        'background: url(/resources/assets/preloader.gif) center no-repeat #fff;' . "\n" . 
                    '}' . "\n" . 
                '</style>');  
        $output->prependHTML('<div class="se-pre-con"></div>');
    }

    public static function wfEditSectionLinkTransform( &$parser, &$text )
    {
        global $wgUser, $wgMobile, $wgHuijiSuffix;
        $isVisualEditorEnabled = $wgUser->getOption('visualeditor-enable','1');
        if ($isVisualEditorEnabled != 1){
            /* when disable visual editor */
            $pattern = ''
                    .'|'
                    .'<span class="mw-editsection-bracket">\[<\/span>'
                    .'<a href="(.+?)" title="(.+?)">'.wfMessage('editsection')->plain().'<\/a>'
                    .'<span class="mw-editsection-bracket">\]<\/span>'
                    .'|ui'
                    ;
            $replacement = ''
                    .'<a href="$1" title="$2" class="icon-edit-code">'
                    .'</a>'
                    ;

            $text = preg_replace( $pattern, $replacement, $text );            
        } else {
            /* when enable visual editor */
            // $replacement = '';
            // $pattern = '#<span class="mw-editsection-divider">'.wfMessage('pipe-separator')->plain().'<\/span>#Ui';
            // $text = preg_replace( $pattern, $replacement, $text );
            $pattern = '<span class="mw-editsection-bracket">[</span>';
            $replacement = '';
            $text = str_replace( $pattern, $replacement, $text );  
            $pattern = '<span class="mw-editsection-bracket">]</span>';
            $replacement = '';
            $text = str_replace( $pattern, $replacement, $text );              
            $pattern = ''
                    .'|'
                    .'<a href="(.+?)" title="(.+?)">'.wfMessage('editsection')->plain().'<\/a>'
                    .'|ui'
                    ;
            $replacement = ''
                    .'<a href="$1" title="$2">'
                    .'<i class="icon-pencil"></i>'
                    .'</a>'
                    ;

            $text = preg_replace( $pattern, $replacement, $text );
            $pattern = ''
                    .'|'
                    .'<a href="(.+?)" title="(.+?)">'.wfMessage('visualeditor-ca-editsource-section')->plain().'<\/a>'
                    .'|ui'
                    ;    
            $replacement = ''
                    .'<a href="$1" title="$2">'
                    .'<i class="icon-edit-code"></i>'
                    .'</a>'
                    // .'<span class="mw-editsection-bracket">]</span>'
                    ;
            $text = preg_replace( $pattern, $replacement, $text ); 
        }     
        return true;
    }
    public static function onOutputPageMakeCategoryLinks( &$out, $categories, &$links ) { 
        $out->addModules('skins.bootstrapmediawiki.editcategory');
    }
    public static function UserLinkBegin( $dummy, $target, &$html, &$customAttribs, &$query,
        &$options, &$ret ) {
        if ($target->getNamespace() == NS_USER){
            $customAttribs['class'] = 'mw-userlink';
            $customAttribs['rel'] = 'nofollow';
        }
        return true;
    }
    public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {
        global $wgHuijiPrefix, $wgUser, $wgMobile, $wgHuijiSuffix;
        /* add norec and rec config vars */
        $titles = $out->getTitle();
        $result = PageProps::getInstance()->getProperty($titles, 'norec');
        if (count($result) > 0){
            $out->addJsConfigVars('wgNoRec', true);
        } 
        $result = PageProps::getInstance()->getProperty($titles, 'rec');
        if (count($result) > 0){
            $out->addJsConfigVars('wgRec', true);
        }
        $result = PageProps::getInstance()->getProperty($titles, 'recbyuser');
        if (count($result) > 0){
            foreach ($result as $key => $value) {
                $out->addJsConfigVars('wgRecByUser', json_decode($value)); 
            }
        }       
        $out->addModules( 
            array('skins.bootstrapmediawiki.bottom')
        );
        if ($wgHuijiPrefix !== 'www' && $wgHuijiPrefix !== 'test'){
            $out->setHTMLTitle( $out->getHTMLTitle() . ' - 灰机wiki' );
        } else {
            $out->addModules( 'skins.bootstrapmediawiki.huiji.globalsearch');
        }
        $NS = $out->getTitle()->getNamespace();
        if ( $out->getUser()->isEmailConfirmed() && ($NS == NS_TEMPLATE || $NS == NS_MODULE ) && $out->getTitle()->exists()){
            $out->addModules( array('skins.bootstrapmediawiki.fork') );
        }
        /* bypass CDN for admins */
        if ($out->getUser()->isAllowed('reupload') && !$wgMobile){ 
            $out->mBodytext = str_replace('http://cdn.huijiwiki.com/', 'http://cdn'.$wgHuijiSuffix.'/', $out->mBodytext);
        }        

        return true;

    }
    
}
?>
