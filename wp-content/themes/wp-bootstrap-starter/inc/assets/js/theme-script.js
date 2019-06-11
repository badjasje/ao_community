function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

jQuery(function($) {
    'use strict';

    // here for each comment reply link of wordpress
    $( '.comment-reply-link' ).addClass( 'btn btn-primary' );

    // here for the submit button of the comment reply form
    $( '#commentsubmit' ).addClass( 'btn btn-primary' );

    // The WordPress Default Widgets
    // Now we'll add some classes for the wordpress default widgets - let's go

    // the search widget
    $( '.widget_search input.search-field' ).addClass( 'form-control' );
    $( '.widget_search input.search-submit' ).addClass( 'btn btn-default' );
    $( '.variations_form .variations .value > select' ).addClass( 'form-control' );
    $( '.widget_rss ul' ).addClass( 'media-list' );

    $( '.widget_meta ul, .widget_recent_entries ul, .widget_archive ul, .widget_categories ul, .widget_nav_menu ul, .widget_pages ul, .widget_product_categories ul' ).addClass( 'nav flex-column' );
    $( '.widget_meta ul li, .widget_recent_entries ul li, .widget_archive ul li, .widget_categories ul li, .widget_nav_menu ul li, .widget_pages ul li, .widget_product_categories ul li' ).addClass( 'nav-item' );
    $( '.widget_meta ul li a, .widget_recent_entries ul li a, .widget_archive ul li a, .widget_categories ul li a, .widget_nav_menu ul li a, .widget_pages ul li a, .widget_product_categories ul li a' ).addClass( 'nav-link' );

    $( '.widget_recent_comments ul#recentcomments' ).css( 'list-style', 'none').css( 'padding-left', '0' );
    $( '.widget_recent_comments ul#recentcomments li' ).css( 'padding', '5px 15px');

    $( 'table#wp-calendar' ).addClass( 'table table-striped');

    // Adding Class to contact form 7 form
    $('.wpcf7-form-control').not(".wpcf7-submit, .wpcf7-acceptance, .wpcf7-file, .wpcf7-radio").addClass('form-control');
    $('.wpcf7-submit').addClass('btn btn-primary');

    // Adding Class to Woocommerce form
    $('.woocommerce-Input--text, .woocommerce-Input--email, .woocommerce-Input--password').addClass('form-control');
    $('.woocommerce-Button.button').addClass('btn btn-primary mt-2').removeClass('button');

    $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).parent().siblings().removeClass('open');
        $(this).parent().toggleClass('open');
    });

    // Add Option to add Fullwidth Section
    function fullWidthSection(){
        var screenWidth = $(window).width();
        if ($('.entry-content').length) {
            var leftoffset = $('.entry-content').offset().left;
        }else{
            var leftoffset = 0;
        }
        $('.full-bleed-section').css({
            'position': 'relative',
            'left': '-'+leftoffset+'px',
            'box-sizing': 'border-box',
            'width': screenWidth,
        });
    }

    fullWidthSection();
    $( window ).resize(function() {
        fullWidthSection();
    });

    // Allow smooth scroll
    $('.page-scroller').on('click', function (e) {
        e.preventDefault();
        var target = this.hash;
        var $target = $(target);
        $('html, body').animate({
            'scrollTop': $target.offset().top
        }, 1000, 'swing');
    });

    if(jQuery(window).height() <= 732) {
        var scrolledPos;
        $('#nextbt').on('click', function(e) {
            if($(this).hasClass('is-active') && !!scrolledPos) {
                $('html, body').animate({'scrollTop':scrolledPos}, 300, 'swing');
            }
            else {
                scrolledPos = jQuery('html').scrollTop();
                $('html, body').animate({'scrollTop':0}, 300, 'swing');
            }
        });
    }

    // Help in icon menu
    $('.menuRow').each(function(i1) {
        var t = $('.menuText>a',this).html();
        if(!!t) {
            $('.buttonItem>a', this).wrapInner('<div data-toggle="tooltip" data-html="true" data-placement="right" title="'+t.replace(/"/g, "'")+'"></div>');
        }
    });

    if(getCookie('menuOpen') === null) setCookie('menuOpen', 0, 256);
    else { setCookie('menuOpen', getCookie('menuOpen'), 256); } // Remember forever

    $("#nextbt, #nextbt2").on('click', function(e) {
        e.preventDefault();
        if($('body').hasClass('menuOpen')) {
            $('body').removeClass('menuOpen');
            $('.menuText').hide(500);
            $(".hamburger").removeClass("is-active");
            $('[data-toggle=tooltip]').tooltip('enable');
            setCookie('menuOpen', 0, 256);
        } else {
            $('body').addClass('menuOpen');
            $('.menuText').show(750);
            $(".hamburger").addClass("is-active");
            $('[data-toggle=tooltip]').tooltip('disable');
            if($(window).width() >= 1400) setCookie('menuOpen', 1, 256);
        }
    });

    $('[data-toggle="tooltip"]').tooltip();

    start_countdowns();
});

var countdown_int, countdown_objs;
function start_countdowns() {
    countdown_objs = jQuery('[data-countdown]');
    if(!!countdown_int) clearInterval(countdown_int);
    countdown_int = setInterval(update_countdowns, 1000);
    update_countdowns();
}
function update_countdowns() {
    countdown_objs.each(function() {
        var $this = jQuery(this), diff = (typeof $this.data('diff') != 'undefined' ? $this.data('diff') : parseInt($this.data('countdown'))*1000);
        if(diff < 0) { $this.html('<em>none</em>'); return; }
        var days = Math.floor( diff / (1000*60*60*48) ),
        hours = Math.floor( diff / (1000*60*60) ),
        mins = Math.floor( diff / (1000*60) ),
        secs = Math.floor( diff / 1000 ),
        dd = days,
        hh = hours - days * 24,
        mm = mins - hours * 60,
        ss = secs - mins * 60;
        var t = ''+(days > 0 ? days+' days and ' : '');
        t+=('00'+hh).slice(-2) + ':' + ('00'+mm).slice(-2) + ':' + ('00'+ss).slice(-2);
        $this.text(t).data('diff', (diff-1000));
    });
}