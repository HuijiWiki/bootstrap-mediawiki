$(function() {
   //parallax Jumbotron
    var jumboHeight = $('.parallax-jumbotron').outerHeight();
    if (jumboHeight > 0){
        $('#firstHeading').hide();
        $('#firstHeading').css('border-bottom', 'none');
        if ($('.heading-hero-image a').length){
            var bg_image = $('.heading-hero-image a').attr('href');
            $('.parallax-bg').css('background', 'url(\"'+bg_image+'\") no-repeat center center');           
        }
        parallax();
    }
    function parallax(){
        var scrolled = $(window).scrollTop();
        $('.parallax-bg').css('height', (jumboHeight-scrolled+100) + 'px');
    }
    if (!window.is_mobile_device()){
        $(window).scroll(function(e){
            parallax();
        });
    } else {
        $('.parallax-bg').css({'position': 'absolute','height':'300px','top':'-10px'});
        $('.parallax-jumbotron').css('height', '300px');
    }
});