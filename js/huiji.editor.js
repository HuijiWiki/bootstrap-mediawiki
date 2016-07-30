


window.customizeToolbar = function() {

	/* Your code goes here */
	//console.log('addToolbar'+$( '#wpTextbox1' ).length+'addToToolBar'+$( '#wpTextbox1' ).wikiEditor);

	//console.log('calling addToToolbar');
	$( '#wpTextbox1' ).wikiEditor( 'addToToolbar', {
		'sections': {
			'quick-insert': {
				'type': 'booklet',
				'label': '快速插入'
			}
		},
		'section': 'advanced',
		'group': 'insert',
		'tools': {
			'addVideo': {
				label: '添加视频',
				type: 'button',
				icon: 'http://cdn.huijiwiki.com/www/skins/bootstrap-mediawiki/img/addVideo.png',
				action: {
					type: 'callback',
					execute: function(){
                        window.caret = $('#wpTextbox1').caret();

                        // Example: Using getSetupProcess() to configure a window with data passed 
						// at the time the window is opened. 

							                        // Example: Using getSetupProcess() to configure a window with data passed 
						// at the time the window is opened. 

                        // Example: Using getSetupProcess() to configure a window with data passed 
						// at the time the window is opened. 

						// Make a subclass of ProcessDialog 
						function MyDialog( config ) {
						  MyDialog.super.call( this, config );
						}
						OO.inheritClass( MyDialog, OO.ui.ProcessDialog );

						// Specify the static configurations: title and action set
						MyDialog.static.title = '添加媒体文件';
						MyDialog.static.actions = [
						  { flags: 'primary', label: '添加', action: 'save', modes:'init' },
						  { flags: 'safe', label: '取消', modes:'init' },
						  { flags: 'safe', label: '继续添加', modes:'done', action:'back'},
						  { flags: 'primary', label: '好的', modes: 'done'},
						];

						// Customize the initialize() function to add content and layouts: 
						MyDialog.prototype.initialize = function () {
						  MyDialog.super.prototype.initialize.call( this );
						  this.panel = new OO.ui.PanelLayout( { $: this.$, padded: true, expanded: false } );
						  this.panel2 = new OO.ui.PanelLayout( { $: this.$, padded: true, expanded: false } );

						  this.content = new OO.ui.FieldsetLayout( { $: this.$ } );
						  this.content2 = new OO.ui.FieldsetLayout( { $: this.$ } );
						  this.urlInput = new OO.ui.TextInputWidget( { $: this.$ , placeholder: '支持youku、bilibili、网易云音乐', indicator: 'required'} );
						  this.label1 = new OO.ui.LabelWidget( {
								label: '媒体文件已录入，你可以复制文件链接，插入到条目中的适当位置。你还可以像处理图片一样为他添加属性和边框。'
						  } );
						  this.nameInput = new OO.ui.TextInputWidget( { $: this.$ } );
						  this.finalInput = new OO.ui.TextInputWidget( { $: this.$ } );

						  this.field = new OO.ui.FieldLayout( this.urlInput, { $: this.$, label: '媒体文件连接', align: 'top' } );
						  this.field2 = new OO.ui.FieldLayout( this.nameInput, { $: this.$, label: '媒体文件名称', align: 'top' } );
						  this.field3 = new OO.ui.FieldLayout( this.finalInput, { $: this.$, label: '文件链接', align: 'top' } );
						  this.button = new OO.ui.ButtonWidget( {
								label: '插入文章中',
								flags:'constructive'
							});

						  this.content.addItems([ this.field, this.field2, this.field3 ]);
						  this.content2.addItems( [this.field3, this.label1, this.button ] );
						  this.panel.$element.append( this.content.$element );
						  this.panel2.$element.append( this.content2.$element );
						  this.stack = new OO.ui.StackLayout( {
						  	items:[ this.panel, this.panel2 ]
						  } );							  
						  this.$body.append( this.stack.$element );

						  this.urlInput.connect( this, { 'change': 'onUrlInputChange' } );
						  this.button.connect( this, {'click': 'onButtonClick' });
						};
						MyDialog.prototype.onButtonClick = function (){
							var options = {};
							options.pre = this.finalInput.getValue();
						    options.post = "";
						    options.ownline = true;
							$( '#wpTextbox1' ).textSelection('encapsulateSelection', options);
							this.close();
						}

						// Specify any additional functionality required by the window (disable opening an empty URL, in this case)
						MyDialog.prototype.onUrlInputChange = function ( value ) {
							var regex = /\.(\w+)\.com/;
						    this.actions.setAbilities( {
						    	save: !!value.match(regex)
						  	} );
						};

						// Specify the dialog height (or don't to use the automatically generated height).
						MyDialog.prototype.getBodyHeight = function () {
						  return this.panel.$element.outerHeight( true );
						};

						// Use getSetupProcess() to set up the window with data passed to it at the time 
						// of opening (e.g., url: 'http://www.mediawiki.org', in this example). 
						MyDialog.prototype.getSetupProcess = function ( data ) {
						  data = data || {};
						  return MyDialog.super.prototype.getSetupProcess.call( this, data )
						  .next( function () {
						    // Set up contents based on data
						    this.urlInput.setValue( data.url );
						    this.actions.setMode( 'init' );
						    this.stack.setItem( this.panel );
						  }, this );
						};

						// Specify processes to handle the actions.
						MyDialog.prototype.getActionProcess = function ( action ) {
							console.log('debug1');
							return MyDialog.super.prototype.getActionProcess.call(this, action)
							.next(function(){
								if ( action === 'back' ){
									this.actions.setMode( 'init' );
									this.stack.setItem( this.panel );
								}
								if ( action === 'save' ) {
									console.log('debug2');
								  	if (!this.urlInput.getValue()){
								  		return new OO.ui.Error('URL不能为空');
								  	}
								  	var regex = /\.(\w+)\.com/;
	        						var match = this.urlInput.getValue().match(regex);
	        						if (!match){
	        							return new OO.ui.Error('暂不支持该URL');
	        						}
	        						var dialog = this;
	        						
        							console.log('debug4');
        							var url = dialog.urlInput.getValue();
	        						var video_name = dialog.nameInput.getValue();
	        						var dfd = $.Deferred();
	        						var uploadSuccess = function(filename){
	        							console.log('debug5');
	        							dialog.actions.setMode('done');
	        							var link;
	        							if (filename.indexOf('.audio') > -1){
	        								link = "[[File:" + filename;
	        								link += "]]";
	        							}else{
	        								link = "[[File:" + filename + "|thumb|300px|]]";
	        							}

	        							dialog.finalInput.setValue(link);
	        							dialog.stack.setItem( dialog.panel2 );
	        							dfd.resolve();
	        						}
	        						var error = function(){
	        							console.log('debug6');
	        							dfd.reject(new OO.ui.Error('添加媒体文件过程中出现了一个错误'));
	        							//dialog.close();
	        							//return new OO.ui.Error('暂不支持该URL');
	        						}
	        						switch(match[1]){
							            case 'youku':
							                mw.VideoHandler.queryYouku(url, video_name, uploadSuccess, error);
							                break;
							            case 'bilibili':
							                mw.VideoHandler.queryBilibili(url, video_name, uploadSuccess, error);
							                break;
							            case '163':
							                mw.VideoHandler.query163(url, video_name, uploadSuccess, error);
							                break; 
							            // case 'qq':
							            // default:
							        }
							        return dfd.promise();

								}
							}, this)
							.next(function(){
								
								//this.close();
							}, this);
			
							
						  	// Fallback to parent handler
						  	return MyDialog.super.prototype.getActionProcess.call( this, action );
						};

						// Use the getTeardownProcess() method to perform actions whenever the dialog is closed. 
						// This method provides access to data passed into the window's close() method 
						// or the window manager's closeWindow() method.
						MyDialog.prototype.getTeardownProcess = function ( data ) {
						  return MyDialog.super.prototype.getTeardownProcess.call( this, data )
						  .first( function () {
						  // Perform any cleanup as needed
						  }, this );
						};

						// Create and append a window manager.
						var windowManager = new OO.ui.WindowManager();
						$( 'body' ).append( windowManager.$element );

						// Create a new process dialog window.
						var myDialog = new MyDialog();

						// Add the window to window manager using the addWindows() method.
						windowManager.addWindows( [ myDialog ] );

						// Open the window!   
						windowManager.openWindow( myDialog, { url: '' } );
					}
				}
			},
		}
	} );
	$( '#wpTextbox1' ).wikiEditor( 'addToToolbar', {
		'section': 'advanced',
		'groups': {
			'translate': {
				'label': '翻译',
			}
		},
		'group':'translate',
		'tools': {
			'en-zh':{
				label: '翻译链接',
				type: 'button',
				icon: 'http://cdn.huijiwiki.com/www/skins/bootstrap-mediawiki/img/hanzi.png',
				action: {
					type: 'callback',
					execute: function(){
						var content = $('#wpTextbox1').val();
						var re = /\[\[(.*?)(\||\]\])/gi; 
						var m = [],matches = [];
						while( matches = re.exec(content)){
							m.push(matches[1]);
						}
						if (m == null){
							alert('没有发现可替换的链接');
						} else {
							jQuery.post(
					            mw.util.wikiScript(), {
					                action: 'ajax',
					                rs: 'wfGetEntry',
					                rsargs: [m.join('|'), 'en', mw.config.get('wgHuijiPrefix'), 0, 1]
					            },
					            function(json){
					            	var data = JSON.parse(json);
					            	var tran = [];
					            	for(i in data){
					            		t = JSON.parse(data[i]);
					            		if(t.status=='success' && t.result.hits>=1){
					            			tran.push(t.result.objects[0].entry);
					            		} else {
					            			tran.push(null);
					            		}
					            	}
					            	var i = 0;
					            	var result = content.replace(re, function(match, p1, p2, offset, string){
					            		if (tran[i++] == null){
					            			return '[['+p1+p2;
					            		}
					            		return '[['+tran[i-1]+p2;
					            	});
					            	$('#wpTextbox1').val(result);
					            }
					        );		
						}
				
					}
				}
			}
		}

	});
				
			
	//console.log('appending quick-insert');
    jQuery(".mw-editTools").detach().appendTo('#wikiEditor-section-quick-insert > div.pages');
    jQuery("#wikiEditor-section-quick-insert > div.index").remove();

};
// function insertTags(pre,post,sample){
// 	var options = {};
//     options.pre = pre;
//     options.post = post;
//     options.selectPeri = sample;
// 	$( '#wpTextbox1' ).textSelection('encapsulateSelection', options);
// }


/**
 * EditTools support: add a selector, change <a> into buttons.
 * The special characters to insert are defined at [[MediaWiki:Edittools]].
 *
 * @author Arnomane, 2006 (on the commons.wikimedia.org/wiki/MediaWiki:Edittools.js)
 * @author Kaganer, 2007 (adapting to www.mediawiki.org)
 * @author Krinkle, 2012
 * @source www.mediawiki.org/wiki/MediaWiki:Gadget-Edittools.js
 * @revision 2012-02-29
 */
/*jslint browser: true*/
/*global jQuery, mediaWiki*/
(function ($, mw) {
	"use strict";
		/* Check if view is in edit mode and that the required modules are available. Then, customize the toolbar … */
	if ( $.inArray( mw.config.get( 'wgAction' ), [ 'edit', 'submit' ] ) !== -1 ) {
		mw.loader.using( 'user.options', function () {
			//console.log('loaded user option');
			// This can be the string "0" if the user disabled the preference ([[phab:T54542#555387]])
			if ( mw.user.options.get( 'usebetatoolbar' ) == 1 ) {
				$.when(
					mw.loader.using( 'ext.wikiEditor.toolbar' ), $.ready, $( '#wpTextbox1' ).on( 'wikiEditor-toolbar-doneInitialSections' )
				).then( customizeToolbar );
			}
		} );
	}
	mw.loader.using("ext.wikieditor.huijiextra.sisyphus", function(){
		var editFormSisyphus = $( "#editform" ).sisyphus( {
			locationBased: true,
			timeout: 0,
			autoRelease: true,
			onBeforeRestore:function(){
				var that = this;
			  //   $('#autoRestoreModal').modal({
			  //       keyboard: false,
					// backdrop: 'static'
			  //   });
			  //   return false;
				// An example of a message dialog with three actions, displayed as rows because
				// the labels do not fit within the window width.
				var messageDialog = new OO.ui.MessageDialog();
				var windowManager = new OO.ui.WindowManager();
				$( 'body' ).append( windowManager.$element );
				windowManager.addWindows( [ messageDialog ] );

				// Configure the message dialog.
				windowManager.openWindow( messageDialog, {
				  title: '发现虫洞',
				  message: '灰机发现您在该页面有未保存的更改，是否现在恢复？',
				  actions: [
				    { label: '不恢复', action: 'nevermind' },
				    { label: '恢复', action: 'recover' }
				  ],
				} ).then( function ( opened ) {
				  opened.then( function ( closing, data ) {
				    if ( data && data.action == 'recover' ) {
				    	that.restoreAllData();
				    } else {
				    	that.manuallyReleaseData();
				    }
				  } );
				} );
			}
		} );
	});

	// $( "#autoRestoreModal .btn-default, #autoRestoreModal .close, #mw-editform-cancel, #editform > div.wikiEditor-ui > div.wikiEditor-ui-controls > div.wikiEditor-ui-buttons > button:nth-child(2)").click(function(){
	//     editFormSisyphus.manuallyReleaseData();
	// });
	// $( "#autoRestoreModal .btn-primary").click(function(){
	//     editFormSisyphus.restoreAllData();
	//     $('#autoRestoreModal').modal('hide');
	// });

	var conf, editTools, $sections;

	conf = {
		initialSubset: window.EditTools_initial_subset === undefined ? window.EditTools_initial_subset : 0
	};

	editTools = {

		/**
		 * Creates the selector
		 */
		setup: function () {
			var $container, $select, initial;

			$container = $('#mw-edittools-charinsert');
			if (!$container.length) {
				return;
			}
			$sections = $container.find('.mw-edittools-section');
			if ($sections.length <= 1) {
				// Only care if there is more than one
				return;
			}

			$select = $('<select>').css('display', 'inline');

			initial = conf.initialSubset;
			if (isNaN(initial) || initial < 0 || initial >= $select.length) {
				initial = 0;
			}

			$sections.each(function (i, el) {
				var $section, sectionTitle, $option;

				$section = $(el);
				sectionTitle = $section.data('sectionTitle');

				$option = $('<option>')
					.text(sectionTitle)
					.prop('value', i)
					.prop('selected', i === initial);

				$select.append($option);
			});

			$select.change(editTools.handleOnchange);
			$container.prepend($select);

			editTools.chooseSection(initial);

		},

		/**
		 * Handle onchange event of the <select>
		 *
		 * @context {Element}
		 * @param e {jQuery.Event}
		 */
		handleOnchange: function () {
			editTools.chooseSection(Number($(this).val()));

			return true;
		},

		/**
		 * Toggle the currently visible section
		 *
		 * @param sectionNr {Number}
		 * @param setFocus {Boolean}
		 */
		chooseSection: function (sectionNr) {
			var $choise = $sections.eq(sectionNr);
			if ($choise.length !== 1) {
				return;
			}

			// Making these buttons is a little slow,
			// If we made them all at once the browser would hang
			// for over 2 seconds, so instead we're doing it on-demand
			// for each section. No need to do it twice thoguh, so remember
			// in data whether it was done already
			if (!$choise.data('charInsert.buttonsMade')) {
				$choise.data('charInsert.buttonsMade', true);
				editTools.makeButtons($choise);
			}

			$choise.show();
			$sections.not($choise).hide();
		},

		/**
		 * Convert the <a onclick> links to buttons in a given section.
		 *
		 * @param $section {jQuery}
		 */
		makeButtons: function ($section) {
			var $links;

			if (!$section.length) {
				return;
			}

			$links = $section.find('a');
			$links.each(function (i, a) {
				var $a, button;
				$a = $(a);
				button = document.createElement('input');
				button.type = 'button';
				button.onclick = a.onclick;
				button.value = $a.text();
				button.className = 'keep';
				$a.replaceWith(button);
			});
		}

	};

	$(document).ready(editTools.setup);
	$(".se-pre-con").fadeOut("slow");

}(jQuery, mediaWiki));