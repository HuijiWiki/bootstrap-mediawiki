<?php 
Class BootstrapMediawikiHooks {
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
        } elseif ( $wgHuijiPrefix == 'home' && in_array($article->getTitle()->getFullText(), HuijiSkinTemplate::getSharedParts())) {
            $option = new ParserOptions($user);
            $key = wfForeignMemcKey( 'huiji','','page', 'getPageRaw', 'shared', $article->getTitle()->getFullText() );
            $output = $wgParser->preprocess($article->getContent()->getNativeData(), $article->getTitle(), $option );
            $wgMemc->set( $key, $output );            
        }
        return true;


        
    }
    public static function onMediaWikiPerformAction( $output, $article, $title, $user, $request, $wiki ) {
        global $wgScriptPath, $wgLogo, $wgFavicon, $wgUploadPath, $wgUploadDirectory, $wgCdnScriptPath, $wgLoadScript, $wgStylePath, $wgExtensionAssetsPath,  $wgResourceBasePath;
        if ($user->isAllowed('editinterface')){
            $wgScriptPath = "";
            $wgLogo = "$wgScriptPath/resources/assets/huiji_white.png";
            $wgFavicon = "$wgScriptPath/resources/assets/favicon.ico";
            $wgUploadPath       = "/uploads";
            $wgUploadDirectory  = "$IP/uploads";
            $wgCdnScriptPath = $wgScriptPath;
            $wgLoadScript = "{$wgCdnScriptPath}/load.php";
            $wgStylePath = "{$wgCdnScriptPath}/skins";
            $wgExtensionAssetsPath = "{$wgCdnScriptPath}/extensions";
            $wgLogo = "{$wgCdnScriptPath}/resources/assets/huiji_white.png";
            $wgFavicon = "{$wgCdnScriptPath}/resources/assets/favicon.ico";
            $wgResourceBasePath = $wgCdnScriptPath;            
        }
        return true;

    }
 
    public static function wfEditSectionLinkTransform( &$parser, &$text )
    {
        global $wgUser;
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
                    .'<a href="$1" title="$2">'
                    .'<i class="fa fa-pencil"></i>'
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
                    .'<i class="fa fa-pencil"></i>'
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
                    .'<i class="fa fa-code"></i>'
                    .'</a>'
                    // .'<span class="mw-editsection-bracket">]</span>'
                    ;
            $text = preg_replace( $pattern, $replacement, $text ); 
        }             
        return true;
    }
}
?>
