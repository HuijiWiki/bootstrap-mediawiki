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

        // $parser->setHook( 'siteactivity', 'getSiteActivity' );
        // $parser->setHook( 'siteactivity', 'getSiteActivity' );
        return true;
    }
    public static function getHeading($input, $args, $parser ) {
        $m = array();
        $bga = isset( $args['background'] ) ? $args['background'] : 'http://cdn.huiji.wiki/shareduploads/uploads/d/d7/Huijibanner_default.png';
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
            $group['image'] = $parser->recursiveTagParse('[['.$temp[0].']]');
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
        $output->addModules( 'ext.wikieditor.huijiextra.top' );
        $output->addModules( 'ext.wikieditor.huijiextra.bottom' );
        $output->addHeadItem('loader',
                '<script language="JavaScript">' . "\n" . 
                    '$(window).load(function() {' . "\n" . 
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
                    '});' . "\n" . 
                '</script>');  
        $output->prependHTML('<div class="se-pre-con"></div>');
    }

    public static function wfEditSectionLinkTransform( &$parser, &$text )
    {
        global $wgUser, $wgMobile;
        $isVisualEditorEnabled = $wgUser->getOption('visualeditor-enable','1');
        if ($isVisualEditorEnabled != 1){
            /* when disable visual editor */
            $pattern = ''
                    .'|'
                    .'<span class="mw-editsection-bracket">\[<\/span>'
                    .'<a href="(.+)" title="(.+)">'.wfMsg('editsection').'<\/a>'
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
            // $pattern = '#<span class="mw-editsection-divider">'.wfMsg('pipe-separator').'<\/span>#Ui';
            // $text = preg_replace( $pattern, $replacement, $text );
            $pattern = ''
                    .'|'
                    .'<span class="mw-editsection-bracket">\[<\/span>'
                    .'<a href="(.+)" title="(.+)">'.wfMsg('editsection').'<\/a>'

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
                    .'<a href="(.+)" title="(.+)">'.wfMsg('visualeditor-ca-editsource-section').'<\/a>'
                    .'<span class="mw-editsection-bracket">\]<\/span>'
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
        if ($wgUser->isAllowed('reupload') && !$wgMobile){ 
            $text = str_replace('http://cdn.huijiwiki.com/', 'http://cdn.huiji.wiki/', $text);
        }        
        return true;
    }
}
?>
