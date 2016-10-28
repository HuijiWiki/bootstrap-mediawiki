/* fix element position and size in this file */
window.is_mobile_device = function() {
    var check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
}
$(function() {
    $('html').removeClass('client-nojs');
    setInterval(function(){
        if(window.innerWidth>=1366){
            $('#wrapper').removeClass('smtoggled');
        }
    },500);
    $('.subnav .nav .dropdown-menu:first').parents('.dropdown').addClass('phone-active');
    $('.subnav .nav .dropdown-menu:first').addClass('phone-active');
    // bell animation
    if($('#pt-notifications-alert .mw-echo-notifications-badge').text()!=0){
        $('#pt-notifications-alert .mw-echo-notifications-badge').css({
            'color':'#fff',
            'background':'rgba(248,172,5,1)',
            // 'animation':"buzz-out 2s infinite", 
            // '-webkit-animation':"buzz-out 2s infinite"
        });
    }
    if($('#pt-notifications-message .mw-echo-notifications-badge').text()!=0){
        $('#pt-notifications-message .mw-echo-notifications-badge').css({
            'color':'#fff',
            'background':'rgba(220, 0, 0, 1)',
            // 'animation':"buzz-out 2s infinite", 
            // '-webkit-animation':"buzz-out 2s infinite"            
        });
    }
    $('.navbar-user').on('click','.mw-echo-notifications-badge',function(){
        if(window.innerWidth>=768){
            $(this).css({'color':'#fff !important','background':'#fff !important'});
        }else{
            $(this).css({'color':'#333 !important','background':'#333 !important'});
        }
    });
    // $('table.article-table')
    //     .each(function() {
    //         var $el = $(this);

    //         if( $el.closest('form').length == 0 ) {
    //             $el.addClass('table')
    //                  .addClass('table-striped')
    //                  .addClass('table-bordered');
    //         }//end if
    //     });

    $('pre:not(.CodeMirror-line)').addClass('prettyprint linenums');
    // $('.jumbotron pre').removeClass('prettyprint linenums');

    $('input[type=submit]:not(".keep"),input[type=button]:not(".keep"),input[type=reset]:not(".keep")').addClass('mw-ui-button');
    $('input[type=submit]:not(".keep")').addClass('mw-ui-progressive');

    // $('input[type=checkbox],input[type=radio]').each(function() {
    //  var $el = $(this);

    //  var id = $el.attr('id');
    //  $( 'label[for=' + id + ']' ).each(function() {
    //      var $label = $(this);
    //      if( $.trim( $label.text() ) != '' ) {
    //          $el.prependTo( $label );
    //      }//end if

    //      $label.wrap( '<div class="checkbox"/>' );
    //  });

    //  $el.closest('label').addClass($el.attr('type'));
    // });

    // //Temperory fix
    // $('.mw-ui-vform-field div').removeClass('mw-ui-checkbox');
    // $('#wpRemember').css('margin-right','5px');
    if ( 0 === $('#toc').length ) {
        $('.toc-sidebar').remove();

    } else {
	    if ( !window.is_mobile_device() ) {
	
	        $('.wiki-body-section').removeClass('col-md-12').addClass('col-md-10');
	        $('.toc-sidebar').removeClass('hidden-md').removeClass('transition-start-toc').addClass('col-md-2');
            $( '.toc-sidebar .toc-ul-wrap' ).removeClass('transition-start-ul')
	        // $('.toc-sidebar').append('<h3>摘要</h3>');
	        $('#toc').each(function() {
	            $(this).find('ul:first').appendTo( '.toc-sidebar .toc-ul-wrap' );
	            $(this).remove();
	        });
	        $('aside.toc-sidebar .toc-ul-wrap> ul').append('<li><a href="#firstHeading">回到顶部</a></li>');
	        $('aside.toc-sidebar .toc-ul-wrap> ul').addClass('hidden-sm hidden-xs hidden-print').attr('data-spy','affix');
	        $('aside.toc-sidebar .toc-ul-wrap> ul').affix({
	          offset: {
	            top: function(){
                    return ((mw.loader.getState('skins.bootstrapmediawiki.header') == 'ready')?350:0);
                },
	            bottom: function () {
	              return (this.bottom = $('.bottom').outerHeight(true));
	            }
	          }
	        });
	        $('aside.toc-sidebar ul').addClass('nav nav-list');
	        $('.toc-sidebar').attr('id', 'toc');
	        $('body').scrollspy({target: '#toc .toc-ul-wrap', offset:60});
	
	    } else {
            //TODO add mobile handlers
        }
    }
    if ($('pre:not(.CodeMirror-line)').length > 0){
        mw.loader.using('skins.bootstrapmediawiki.prettify', function(){
            prettyPrint();
        });
    }

    /* add missing icons caused by visual editor */
    // $('#ca-edit.collapsible > a:nth-child(1)').prepend('<i class="fa fa-file-code-o"></i> ');

    //fix thumbinner
    $('.thumbinner').each(function(){
        $(this).width($(this).width()+6);
    });
    $('.internal').each(function(){
	   $(this).html('<i class="fa fa-file-image-o"></i>');
    });

//    need login
    $('body').on('click','.need-login',function(e){
        if(!mw.config.get("wgUserName")){
            e.preventDefault();
            e.stopPropagation();
            window.location=$('.login').attr('href');
        }
    });

    $('#wpRemember').attr('value', 1);
    // // done for preload. Let's show the page.
    // $('#wiki-outer-body').show();
});
