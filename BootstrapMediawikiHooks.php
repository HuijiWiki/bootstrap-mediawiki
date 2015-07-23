<?php 
Class BootstrapMediawikiHooks {
	/**
     * Update page's cache when someone edit the page(Admin,subnav,footer)
     */
    public static function onNewRevisionFromEditComplete( $article, Revision $rev, $baseID, User $user ){
        global $wgMemc, $wgParser;
        if ( $article->getTitle()->exists() && $article->getTitle()->getFullText() === '首页/Admin' 
            || $article->getTitle()->getFullText() === 'Bootstrap:TitleBar'         
            || $article->getTitle()->getFullText() === 'Bootstrap:Footer' 
            || $article->getTitle()->getFullText() === 'Bootstrap:Subnav' ){
            $option = new ParserOptions($user);
            $key = wfMemcKey( 'page', 'getPageRaw', 'all', $article->getTitle()->getFullText() );
            $output = $wgParser->preprocess($article->getRawText(), $article->getTitle(), $option );
            $wgMemc->set( $key, $output );
        }
        return true;
        
    }
}
?>