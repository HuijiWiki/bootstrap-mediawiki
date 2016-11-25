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
        $parser->setHook( 'videoclip', 'BootstrapMediawikiHooks::getVideoClip');
        $parser->setHook( 'steam', 'BootstrapMediawikiHooks::getSteam');

        // $parser->setHook( 'siteactivity', 'getSiteActivity' );
        // $parser->setHook( 'siteactivity', 'getSiteActivity' );
        return true;
    }
    /**
     * Get Steam widget
     *
     */
    public static function getSteam( $input, $args, $parser){
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $output =  $templateParser->processTemplate(
            'steam',
            array(
                'image' => $args['image'],
                'mp4' => $args['mp4'],
                'webm' => $args['webm'],
            )
        );
        return $output;        
    }
    public static function getVideoClip( $input, $args, $parser ){
        $templateParser = new TemplateParser(  __DIR__ . '/View' );
        $output =  $templateParser->processTemplate(
            'videoclip',
            array(
                'image' => $args['image'],
                'mp4' => $args['mp4'],
                'webm' => $args['webm'],
            )
        );
        return $output;
    }

    public static function getSiteInfo( $input, $args, $parser ){
        global $wgHuijiPrefix;
        if ( isset($args['site']) && $args['site']!=''){
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
        $title = $parser->recursiveTagParse(isset( $args['title'] ) ? $args['title'] : '{{PAGENAME}}');
        $subtitle = $parser->recursiveTagParse(isset( $args['subtitle'] ) ? $args['subtitle'] : '{{SITENAME}}' );
        $fontcolor = isset( $args['fontcolor'] ) ? $args['fontcolor'] : '#FFF';
        $wide = isset( $args['wide'] ) ? $args['wide'] : 'false';
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
                'wide' => $wide,
            )
        );
        // $parser->getOutput()->addModuleStyles('skins.bootstrapmediawiki.header');
        $parser->getOutput()->addModules('skins.bootstrapmediawiki.header');
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
                'interval' => $interval,
                'width' => $width,
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
    /**
     * @see https://www.mediawiki.org/wiki/Manual:Hooks/MediaWikiPerformAction
     */
    public static function onMediaWikiPerformAction( $output, $article, $title, $user, $request, $wiki ) {
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
                        'background: url(http://fs.huijiwiki.com/www/resources/assets/preloader.gif) center no-repeat #fff;' . "\n" . 
                    '}' . "\n" . 
                '</style>');  
        $output->prependHTML('<div class="se-pre-con"></div>');
    }

    public static function wfEditSectionLinkTransform( &$parser, &$text )
    {
        //global $wgUser, $wgMobile, $wgHuijiSuffix;
        //$isVisualEditorEnabled = $wgUser->getOption('visualeditor-enable','1');
        // if ($isVisualEditorEnabled != 1){
        //     /* when disable visual editor */
            // $pattern = ''
            //         .'|'
            //         .'<a href="(.+?)" title="(.+?)">'.preg_quote(wfMessage('editsection')->plain(), '|').'<\/a>'
            //         .'|ui'
            //         ;
            // $replacement = ''
            //         .'<a href="$1" title="$2" class="icon-edit-code">'
            //         .'</a>'
            //         ;

            // $text = preg_replace( $pattern, $replacement, $text );            
        // } else {
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
            // $pattern = ''
            //         .'|'
            //         .'<a href="(.+?)" title="(.+?)">'.wfMessage('editsection')->plain().'<\/a>'
            //         .'|ui'
            //         ;
            // $replacement = ''
            //         .'<a href="$1" title="$2">'
            //         .'<i class="icon-pencil"></i>'
            //         .'</a>'
            //         ;

            // $text = preg_replace( $pattern, $replacement, $text );
            // $pattern = ''
            //         .'|'
            //         .'<a href="(.+?)" title="(.+?)">'.preg_quote(wfMsg('visualeditor-ca-editsource-section'),'|').'<\/a>'
            //         .'|ui'
            //         ;    
            // $replacement = ''
            //         .'<a href="$1" title="$2">'
            //         .'<i class="icon-edit-code"></i>'
            //         .'</a>';
                    // .'<span class="mw-editsection-bracket">]</span>'
                   //;
                   //echo $patten."\n".$replacement;//die();
            // $text = preg_replace( $pattern, $replacement, $text ); 
        // }     
        return true;
    }
    public static function onOutputPageMakeCategoryLinks( &$out, $categories, &$links ) { 
        $out->addModules('skins.bootstrapmediawiki.editcategory');
    }
    // public static function UserLinkBegin( $dummy, $target, &$html, &$customAttribs, &$query,
    //     &$options, &$ret ) {
    //     if ($target->getNamespace() == NS_USER){
    //         $customAttribs['class'] = 'mw-userlink';
    //         $customAttribs['rel'] = 'nofollow';
    //     } elseif( $target->getNamespace()== NS_FILE || $target){
    //         $path = pathinfo($target->getFullText());
    //         if (array_key_exists('extension', $path) && pathinfo($target->getFullText())['extension'] == 'ass'){
    //             $customAttribs['download'] = $target->getText();
    //         };
    //     }
    //     return true;
    // }
    public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {
        global $wgHuijiPrefix, $wgUser, $wgMobile, $wgHuijiSuffix;
        /* add norec and rec config vars */
        $titles = $out->getTitle();
        $result = PageProps::getInstance()->getProperties($titles, 'norec');
        if (count($result) > 0){
            $out->addJsConfigVars('wgNoRec', true);
        } 
        $result = PageProps::getInstance()->getProperties($titles, 'rec');
        if (count($result) > 0){
            $out->addJsConfigVars('wgRec', true);
        }
        $result = PageProps::getInstance()->getProperties($titles, 'recbyuser');
        if (count($result) > 0){
            foreach ($result as $key => $value) {
                $out->addJsConfigVars('wgRecByUser', json_decode($value)); 
            }
        }       
        $out->addModules( 
            array('skins.bootstrapmediawiki.bottom', 'ext.HuijiMiddleware.feedback' )
        );
        if ($wgHuijiPrefix !== 'www' && $wgHuijiPrefix !== 'test'){
            $out->setHTMLTitle( $out->getHTMLTitle() . ' - 灰机wiki' );
        } else {
            $out->addModules( 'skins.bootstrapmediawiki.huiji.globalsearch.suggest');
        }
        $NS = $out->getTitle()->getNamespace();
        if ( $out->getUser()->isEmailConfirmed() && ($NS == NS_TEMPLATE || $NS == NS_MODULE ) && $out->getTitle()->exists()){
            $out->addModules( array('skins.bootstrapmediawiki.fork') );
        }
        if ( $NS == NS_SPECIAL ){
            $out->addModules( array('skins.bootstrapmediawiki.special.less') );
        }
        /* bypass CDN for admins */
        return true;

    }
    public static function onResourceLoaderGetLessVars( &$lessVars ) {
        global $wgHuijiPrefix;
        // $lessVars['colorpath'] = "\"http://huiji-fs.oss-cn-qingdao-internal.aliyuncs.com/$wgHuijiPrefix/style/SiteColor.less\"";
        // $lessVars['main-base'] = "#000";
        // $lessVars['bg'] = "#000";

        $lessVars = CommonStyle::getLessVars( $lessVars );

    }
    public static function onSpecialSearchResultsPrepend( $specialSearch, $output, $term ) { 
        $output->addModules('skins.bootstrapmediawiki.search');
        return true;
    }
    /**
     * Creates SocialProfile's new database tables when the user runs
     * /maintenance/update.php, the MediaWiki core updater script.
     *
     * @param $updater DatabaseUpdater
     * @return Boolean
     */
    public static function onLoadExtensionSchemaUpdates( $updater ) {
        $dir = dirname( __FILE__ );
        $dbExt = '';

        if ( $updater->getDB()->getType() == 'postgres' ) {
            $dbExt = '.postgres';
        }
        $updater->addExtensionUpdate( array( 'addTable', 'common_css', "$dir/CommonStyle/common_css$dbExt.sql", true ) );
        return true;
    }
    public static function onImportSources( &$sources ){
        $sources = Huiji::getInstance()->getSitePrefixes(true);
    }

    public static function onContentHandlerDefaultModelFor( Title $title, &$model ) {
        if ( $title->getPrefixedText() == 'MediaWiki:App' || $title->getPrefixedText() == 'MediaWiki:CommonStyle' || $title->getPrefixedText() == 'MediaWiki:Huiji-translation-pairs'  )  {
            $model = CONTENT_MODEL_JSON;
            return false;
        }
        return true;
    }
    
}
