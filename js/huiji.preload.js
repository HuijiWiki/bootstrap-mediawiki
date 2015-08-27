/* fix element position and size in this file */
$(function() {
    $('html').removeClass('client-nojs');
    setInterval(function(){
        if(window.innerWidth>=1366){
            $('#wrapper').removeClass('smtoggled');
        }
    },500);
    $('.subnav .nav .dropdown:first').addClass('phone-active');
    $('.subnav .nav .dropdown-menu:first').addClass('phone-active');
    $('#preftoc').addClass('nav nav-tabs');
    // bell animation
    if($('#pt-notifications span').text()!=0){
        $('#pt-notifications i').addClass('bell-animation');
        $('.mw-ui-quiet').click(function(){
            $('.badge').text('0').hide();
            $('#pt-notifications i').removeClass('bell-animation');
        });
    }else if($('#pt-notifications span').text()==0){
        $('.badge').hide();
    }

    $('table.article-table')
        .each(function() {
            var $el = $(this);

            if( $el.closest('form').length == 0 ) {
                $el.addClass('table')
                     .addClass('table-striped')
                     .addClass('table-bordered');
            }//end if
        });

    // $('pre:not([data-raw="true"])').addClass('prettyprint linenums');
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

    //Temperory fix
    $('.mw-ui-vform-field div').removeClass('mw-ui-checkbox');
    $('#wpRemember').css('margin-right','5px');

    if ( $('.toc-sidebar').length > 0 ) {
        if ( 0 === $('#toc').length ) {
            $('.toc-sidebar').remove();

        } else {
            $('.wiki-body-section').removeClass('col-md-12').addClass('col-md-10');
            $('.toc-sidebar').removeClass('hidden-md').addClass('col-md-2');
            // $('.toc-sidebar').append('<h3>摘要</h3>');
            $('#toc').each(function() {
                $(this).find('ul:first').appendTo( '.toc-sidebar' );
                $(this).remove();
            });
            $('nav.toc-sidebar > ul').append('<li><a href="#firstHeading">回到顶部</a></li>');
            $('nav.toc-sidebar > ul').addClass('hidden-sm hidden-xs hidden-print').attr('data-spy','affix');
            $('nav.toc-sidebar > ul').affix({
              offset: {
                top: 0,
                bottom: function () {
                  return (this.bottom = $('.bottom').outerHeight(true))
                }
              }
            })
            $('nav.toc-sidebar ul').addClass('nav nav-list');
            $('.toc-sidebar').attr('id', 'toc');
            $('body').scrollspy({target: '#toc', offset:230});
        }//end else
    } else {
        $('#toc').each(function() {
            var $toc = $(this);
            var $title = $toc.find('#toctitle');
            var $links = $title.siblings('ul');

            $('.page-header').prepend('<ul class="nav nav-pills pull-right"><li class="dropdown" id="page-contents"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list"></i> Contents <span class="caret"></span></a> <ul class="dropdown-menu"></ul></li></ul>');

            $('.page-header #page-contents').find('.dropdown-menu').html( $links.html() );
        });

        if( $('.page-header .nav').length === 0 ) {
            $('.page-header').prepend('<ul class="nav nav-pills pull-right"></li></ul>');
        }//end if

        var $header = $('.page-header');
        var $hero = $('.hero-unit');
        var $edit = $('.navbar .content-actions .edit');
        if( $edit.length > 0 ) {
            if( $hero.length ) {
                if( ! $hero.find('.nav-pills').length ) {
                    $hero.prepend('<ul class="nav nav-pills pull-right"></ul>');
                }//end if

                $edit.closest('li').clone().prependTo( $hero.find('.nav-pills') );
            } else {
                $edit.closest('li').clone().prependTo( $header.find('.nav-pills') );
            }//end else
        }//end if
    }//end if

    // prettyPrint();

    /* add missing icons caused by visual editor */
    // $('#ca-edit.collapsible > a:nth-child(1)').prepend('<i class="fa fa-file-code-o"></i> ');

    //parallax Jumbotron
    var jumboHeight = $('.parallax-jumbotron').outerHeight();
    if (jumboHeight > 0){
        $('#firstHeading > h1').hide();
        $('#firstHeading').css('border-bottom', 'none');
        var bg_image = $('.heading-hero-image a').attr('href');
        $('.parallax-bg').css('background', 'url(\"'+bg_image+'\") no-repeat center center');
        parallax();
    }
    function parallax(){
        var scrolled = $(window).scrollTop();
        $('.parallax-bg').css('height', (jumboHeight-scrolled+100) + 'px');
    }
    function is_touch_device() {
        return (('ontouchstart' in window)
            || (navigator.MaxTouchPoints > 0)
            || (navigator.msMaxTouchPoints > 0));
    }
    if (!is_touch_device()){
        $(window).scroll(function(e){
            parallax();
        });
    } else {
        $('.parallax-bg').css({'position': 'absolute','height':'300px','top':'-10px'});
        $('.parallax-jumbotron').css('height', '300px');
    }


    //fix thumbinner
    $('.thumbinner').each(function(){
        $(this).width($(this).width()+6);
    });
    $('.internal').each(function(){
	   $(this).html('<i class="fa fa-arrows-alt"></i>');
    });

    // done for preload. Let's show the page.
    $('body.mediawiki').show();
});
