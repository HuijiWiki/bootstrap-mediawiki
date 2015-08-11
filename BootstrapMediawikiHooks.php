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
}
?>