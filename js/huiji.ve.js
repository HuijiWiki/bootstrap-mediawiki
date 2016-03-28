mw.hook( 've.activationComplete' ).add( function() {
	ga('send', 'event', 'VisualEditor', 'activate', 'article', 1);
	// SOME CODE TO RUN WHEN EDITOR IS READY
} );