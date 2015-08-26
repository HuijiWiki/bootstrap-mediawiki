
var editFormSisyphus = $( "#editform" ).sisyphus( {
locationBased: true, 
timeout: 0,
autoRelease: true,
onBeforeRestore:function(){
    $('#autoRestoreModal').modal({
        keyboard: false,
		backdrop: 'static'
    }); 
    return false;
}
} ); 
$( "#autoRestoreModal .btn-default, #autoRestoreModal .close, #mw-editform-cancel, #editform > div.wikiEditor-ui > div.wikiEditor-ui-controls > div.wikiEditor-ui-buttons > button:nth-child(2)").click(function(){
    editFormSisyphus.manuallyReleaseData();
});
$( "#autoRestoreModal .btn-primary").click(function(){
    editFormSisyphus.restoreAllData();
    $('#autoRestoreModal').modal('hide');
});

var customizeToolbar = function() {
	/* Your code goes here */
	$('#wpTextbox1').wikiEditor('addToToolbar', {
		section: 'advanced',
		group: 'format',
		tools: {
			"strikethrough": {
				label: '删除线',
				type: 'button',
				icon: '//upload.wikimedia.org/wikipedia/commons/3/30/Btn_toolbar_rayer.png',
				action: {
					type: 'encapsulate',
					options: {
						pre: "<s>",
						post: "</s>"
					}
				}
			}
		}
	});
	$( '#wpTextbox1' ).wikiEditor( 'addToToolbar', {
		section: 'advanced',
		group: 'format',
		tools: {
			"hline": {
				label: '水平线',
				type: 'button',
				icon: '//upload.wikimedia.org/wikipedia/commons/6/66/Line_button.png',
				action: {
					type: 'encapsulate',
					options: {
						pre: "----",
						ownline: true
					}
				}
			}
		}
	} );
	$( '#wpTextbox1' ).wikiEditor( 'addToToolbar', {
		section: 'advanced',
		group: 'format',
		tools: {
			"comment": {
				label: '注释',
				type: 'button',
				icon: '//upload.wikimedia.org/wikipedia/commons/3/37/Btn_toolbar_commentaire.png',
				action: {
					type: 'encapsulate',
					options: {
						pre: "<!-- ",
						post: " -->"
					}
				}
			}
		}
	} );
	$( '#wpTextbox1' ).wikiEditor( 'addToToolbar', {
		section: 'advanced',
		group: 'format',
		tools: {
			"math": {
				label: '数学',
				type: 'button',
				icon: '//upload.wikimedia.org/wikipedia/commons/2/2e/Button_math.png',
				action: {
					type: 'encapsulate',
					options: {
						pre: "<math>",
						post: "</math>"
					}
				}
			}
		}
	} );
};

/* Check if view is in edit mode and that the required modules are available. Then, customize the toolbar … */
if ( $.inArray( mw.config.get( 'wgAction' ), [ 'edit', 'submit' ] ) !== -1 ) {
	mw.loader.using( 'user.options', function () {
		// This can be the string "0" if the user disabled the preference ([[phab:T54542#555387]])
		if ( mw.user.options.get( 'usebetatoolbar' ) == 1 ) {
			$.when(
				mw.loader.using( 'ext.wikiEditor.toolbar' ), $.ready
			).then( customizeToolbar );
		}
	} );
}