$(function(){

    // STICKY SIDEBAR MENU
    if($('#sidebar,#sub-menu').hasClass('scroll')){

        var y = $(document).scrollTop();
        var parent_max = $('#sidebar').parent().height();
        var initial_y = $("#sub-menu").offset().top - 30;

        if ($("#sidebar").outerHeight(true)<$(window).height() &&  y > initial_y && y < (initial_y+parent_max-$("#sidebar").outerHeight(true))) {
            $("#sidebar").removeClass('absolute');
            $("#sidebar").addClass('fixed');
        } else if ($("#sidebar").outerHeight(true)<$(window).height() &&  y >= (initial_y+parent_max-$("#sidebar").outerHeight(true))) {
            $("#sidebar").removeClass('fixed');
            $("#sidebar").addClass('absolute');
            $('#sidebar').css('top',(initial_y+parent_max-$("#sidebar").outerHeight(true))+'px');
        } else {
            $("#sidebar").removeClass('fixed absolute');
        }

        $(document).scroll(function () {
            var y = $(document).scrollTop();
            var parent_max = $('#sidebar').parent().height();

            if ($("#sidebar").outerHeight(true)<$(window).height() &&  y > initial_y && y < (initial_y+parent_max-$("#sidebar").outerHeight(true))) {
                $("#sidebar").removeClass('absolute');
                $("#sidebar").addClass('fixed');
            } else if ($("#sidebar").outerHeight(true)<$(window).height() &&  y >= (initial_y+parent_max-$("#sidebar").outerHeight(true))) {
                $("#sidebar").removeClass('fixed');
                $("#sidebar").addClass('absolute');
                $('#sidebar').css('top',(initial_y+parent_max-$("#sidebar").outerHeight(true))+'px');
            } else {
                $("#sidebar").removeClass('fixed absolute');
            }

        });

    }

    // FOOTER ITEMS
    $('.items .image').hover(function () {

        $(this).css('padding-bottom','5px').animate({
            'top' : '-5px',
        },100,function(){

            $(this).parent().find('.preview').css('display','block').animate({
                'opacity' : '1'
            },100);

        });

        $(this).parent().find('.shadow').animate({
            'width' : '70px',
            'left' : '5px'
        },100);

        $(this).parent().find('.shadow_creative_dreams').animate({
            'width' : '107px',
            'left' : '5px'
        },100);

    },function () {

        $(this).parent().find('.preview').animate({
            'opacity' : '0'
        },100,function(){
            $(this).css('display','none');
        });

        $(this).css('padding-bottom','0').animate({
            'top' : '0'
        },100);

        $(this).parent().find('.shadow_creative_dreams').animate({
            'width' : '117px',
            'left' : '0'
        },100);
    });

    // FOOTER SOCIAL
    $('.social .image').hover(function () {

        $(this).css('padding-bottom','5px').animate({
            'top' : '-5px',
        },100);

        $(this).parent().find('.shadow').animate({
            'width' : '22px',
            'left' : '5px'
        },100);

    },function () {

        $(this).css('padding-bottom','0').animate({
            'top' : '0'
        },100);

        $(this).parent().find('.shadow').animate({
            'width' : '32px',
            'left' : '0'
        },100);
    });

    // TWITTER
    function twitt(){

        $('#twitter').prepend('<div class="twitt">twitt</div>');

        var random_top=Math.ceil(Math.random()*50);
        var random_left=Math.ceil(Math.random()*100);

        $('#twitter .twitt:first-child').css({
            'top'   : '-15px',
            'left'  : '20px'
        }).animate({
            'top'   : '-'+(random_top+20)+'px',
            'left'  : (50-random_left)+'px',
            'font-size' : '12px',
            'opacity' : 0
        },2000,function(){
            $(this).remove();
        });

    }
    $('#twitter').hover(function(){ twitt(); },'');

});