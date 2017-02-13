<style>

/* Customs colours for the site
 *
 * Include colours and backgrounds
 *
 * */

<?php
$hex = crystalskull_hex2rgb(of_get_option('bg_tint_color'));
$hex2 = crystalskull_hex2rgb(of_get_option('primary_color'));
$a = $hex[0];
$b = $hex[1];
$c = $hex[2];

$d = $hex[0];
$e = $hex[1];
$f = $hex[2];
$rgba = 'rgba('.$a.','.$b.','.$c.',0.4)';
$rgba2 = 'rgba('.$d.','.$e.','.$f.',0.3)';
$rgba3 = 'rgba('.$d.','.$e.','.$f.',0.1)';
?>





/* Primary color */

body.woocommerce div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover, body .woocommerce .price_slider_amount button.button, .latest-twitter-tweet .fa-twitter, .woocommerce .products .star-rating, .woocommerce ul.products li.product:hover h3, .ui-state-active a, .nav-tabs a:hover, .nav-tabs>li.active>a, .splitter li a:hover, .splitter li[class*=selected]>a, .post-pinfo a:hover,  .bbp-forum-content ul.sticky .fa-comment, .bbp-topics ul.sticky .fa-comment, .bbp-topics ul.super-sticky .fa-comment, .bbp-topics-front ul.super-sticky .fa-comment, #buddypress .activity-list li.load-more a, body .vc_carousel.vc_per-view-more .vc_carousel-slideline .vc_carousel-slideline-inner > .vc_item > .vc_inner h2 a:hover,
#bbpress-forums fieldset.bbp-form legend, .newsbv li:hover a, .cart-notification span.item-name, .woocommerce div.product p.price, .price span.amount,
.woocommerce .widget_shopping_cart .total span, .nm-date span, .cart-notification span.item-name, .woocommerce div.product p.price, .price span.amount,
.dropdown:hover .dropdown-menu li > a:hover, .clan-generali .clan-members-app > .fa,
.nextmatch_wrap:hover .nm-clans span, input[type="password"]:focus, input[type="datetime"]:focus, input[type="datetime-local"]:focus, input[type="date"]:focus,
input[type="month"]:focus, input[type="time"]:focus, input[type="week"]:focus, input[type="number"]:focus, input[type="email"]:focus,
input[type="url"]:focus, input[type="search"]:focus, input[type="tel"]:focus, input[type="color"]:focus, .uneditable-input:focus{
	color:<?php echo of_get_option('primary_color'); ?> !important;
}

.single-product.woocommerce .single_add_to_cart_button, .after-nav, .gallery-item a img, .match-map .map-image img, .nextmatch_wrap:hover img, .wrap:hover .clan1img, .matchimages img, .dropdown-menu, .widget .clanwar-list > li:first-child, .footer_widget .clanwar-list > li:first-child{
	border-color:<?php echo of_get_option('primary_color'); ?> !important;
}

.comment-wrap .com_details a, #buddypress div.item-list-tabs ul li a span, .widget.clanwarlist-page .clanwar-list .date strong,
 .footer_widget.clanwarlist-page .clanwar-list .date strong, #matches .mminfo span,
  .footer_widget .clanwar-list .home-team, .footer_widget .clanwar-list .vs,
   .footer_widget .clanwar-list .opponent-team, .widget .clanwar-list .home-team,
   .widget .clanwar-list .vs, .widget .clanwar-list .opponent-team, div.bbp-template-notice,
   div.indicator-hint, .social a , .widget-title i, .profile-clans a:hover,
   .friendswrapper .friends-count i, .slider_com_wrap, .portfolio .row .span8 .plove a:hover,
   .span3 .plove a:hover, .icons-block i:hover,
   .navbar-inverse .navbar-nav>.active>a, .navbar-inverse .navbar-nav>.active>a:focus, .navbar-inverse .navbar-nav>.active>a:focus>em, .navbar-inverse .navbar-nav>.active>a:hover, .navbar-inverse .navbar-nav>.active>a:hover>em, .navbar-inverse .navbar-nav>.active>a>em, .navbar-inverse .navbar-nav>li>a:focus,
   .navbar-inverse .navbar-nav>li>a:focus>em, .navbar-inverse .navbar-nav>li>a:hover, .navbar-inverse .navbar-nav>li>a:hover>em,
 .similar-projects ul li h3,
 .member h3, .main-colour,a, .dropdown-menu li > a:hover, .wallnav i,  div.rating:after, footer .copyright .social a:hover, .navbar-inverse .brand:hover, .member:hover > .member-social a, footer ul li a:hover, .widget ul li a:hover, .next_slide_text .fa-bolt ,
  .dropdown-menu li > a:focus, .dropdown-submenu:hover > a,
  .comment-body .comment-author,  .navigation a:hover, .cart-wrap a, .bx-next-out:hover .next-arrow:before, body .navbar-wrapper .login-info .login-btn{
    color:<?php echo of_get_option('primary_color'); ?>;
}

#sform input[type=search], .ubermenu .wpcf7-submit:hover, body .ubermenu-skin-clean-white .ubermenu-item-level-0:hover > .ubermenu-target, body .ubermenu-skin-clean-white .ubermenu-item-level-0.ubermenu-active > .ubermenu-target,
body .flex-control-paging li a.flex-active, body .flex-control-paging li a:hover, body .wpb_posts_slider .flex-caption h2 a, .navbar-inverse .nav>li.active>a, .navbar-inverse .nav>li.current-menu-item>a, .navbar-inverse .nav>li>a:hover, .navbar .nav li.current-menu-parent a, .navbar .nav li.current_page_item a,
.button-big:hover, .button-medium:hover, .button-small:hover, button[type=submit]:hover, input[type=button]:hover, input[type=submit]:hover, .navbar-nav>li:after, .ticker-title, .after-nav .container:before, div.pagination a:focus, div.pagination a:hover, div.pagination span.current, .page-numbers:focus, .page-numbers:hover, .page-numbers.current, body.woocommerce nav.woocommerce-pagination ul li a:focus, body.woocommerce nav.woocommerce-pagination ul li a:hover, body.woocommerce nav.woocommerce-pagination ul li span.current, .widget .clanwar-list .tabs li:hover a, .widget .clanwar-list .tabs li.selected a, .bgpattern, .post-review, .widget_shopping_cart, .woocommerce .cart-notification, .cart-notification, .splitter li[class*="selected"] > a, .splitter li a:hover, .ls-wp-container .ls-nav-prev, .ls-wp-container .ls-nav-next, a.ui-accordion-header-active, .accordion-heading:hover, .block_accordion_wrapper .ui-state-hover, .cart-wrap, .clanwar-list li ul.tabs li:hover, .clanwar-list li ul.tabs li.selected a:hover, .clanwar-list li ul.tabs li.selected a, .dropdown .caret,
.tagcloud a:hover, .progress-striped .bar ,  .bgpattern:hover > .icon, .progress-striped .bar, .member:hover > .bline, .blog-date span.date,
 .pbg, .pbg:hover, .pimage:hover > .pbg, ul.social-media li a:hover, .navigation a ,
 .pagination ul > .active > a, .pagination ul > .active > span, .list_carousel a.prev:hover, .list_carousel a.next:hover, .pricetable .pricetable-col.featured .pt-price, .block_toggle .open, .pricetable .pricetable-featured .pt-price, .isotopeMenu, .bbp-topic-title h3, .modal-body .reg-btn, #LoginWithAjax_SubmitButton .reg-btn, .footer_widget h3, buddypress div.item-list-tabs ul li.selected a, .results-main-bg, .blog-date-noimg, .blog-date, .ticker-wrapper.has-js, .ticker-swipe  {
    background-color:<?php echo of_get_option('primary_color'); ?>;
}


body .woocommerce .price_slider_amount button.button:hover{
	background-color:<?php echo of_get_option('primary_color'); ?> !important;
}
.button-medium, .button-small, .button-big, button[type="submit"], input[type="submit"]{
	color:<?php echo of_get_option('primary_color'); ?>;
}
.ticker-title:before{
	border-bottom: 38px solid <?php echo of_get_option('primary_color'); ?>;
}


.next-arrow{
	border-left: 30px solid <?php echo of_get_option('top_grad_color'); ?>;
}
body .woocommerce .price_slider_amount button.button, .single-product.woocommerce div.product form.cart .button.single_add_to_cart_button, input[type=password]:active, input[type=password]:focus, input[type=password]:hover, input[type=text]:active, input[type=text]:focus, input[type=text]:hover,  select:active, select:focus, select:hover, textarea:active, textarea:focus, textarea:hover,
.page-numbers,  div.bbp-template-notice, div.indicator-hint,  div.pagination a, div.pagination span,body.woocommerce nav.woocommerce-pagination ul li a, body.woocommerce nav.woocommerce-pagination ul li span{
	border: 1px solid <?php echo of_get_option('primary_color'); ?> !important;
}
.single-product.woocommerce div.product form.cart .button.single_add_to_cart_button, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button, .woocommerce div.product .woocommerce-tabs ul.tabs li a, .woocommerce #content div.product .woocommerce-tabs ul.tabs li a, .woocommerce-page div.product .woocommerce-tabs ul.tabs li a, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li a {
	background: <?php echo of_get_option('primary_color'); ?>  !important;
}
.woocommerce span.onsale, .woocommerce-page span.onsale, .woocommerce-message, .woocommerce-error, .woocommerce-info, .woocommerce .widget_price_filter .ui-slider .ui-slider-range, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-range{
	background:<?php echo of_get_option('primary_color'); ?> !important;
}
.button-medium, .button-small, .button-big, button[type="submit"], input[type="submit"],
textarea:focus,
input[type="text"]:focus,
input[type="password"]:focus,
input[type="datetime"]:focus,
input[type="datetime-local"]:focus,
input[type="date"]:focus,
input[type="month"]:focus,
input[type="time"]:focus,
input[type="week"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="url"]:focus,
input[type="search"]:focus,
input[type="tel"]:focus,
input[type="color"]:focus,
.uneditable-input:focus,
.gallery-item a img:hover,
h3.widget-title{
	border-color:<?php echo of_get_option('primary_color'); ?>;
}

/* Background Tint */

<?php
if(of_get_option('bg_tint') == 1){
    ?>

	#main_wrapper, .owl-item .car_image:after, .newsb-thumbnail a:after, .ins_widget ul li a:after, .blog-image a:after{
		background: url(<?php echo get_template_directory_uri(); ?>/img/pattern.png) top left repeat <?php echo esc_attr($rgba); ?>;
	}

<?php
}
?>

/* Secondary Color */

.wpb_wrapper > div:nth-child(2n) h3.widget-title {
    border-color: <?php echo of_get_option('secondary_color'); ?>;
}

.wpb_wrapper .widget:nth-child(2n) h3.widget-title {
    border-color: <?php echo of_get_option('secondary_color'); ?>;
}

body .ubermenu-skin-clean-white .ubermenu-item-level-0.ubermenu-current-menu-item > .ubermenu-target,
body .ubermenu-skin-clean-white .ubermenu-item-level-0.ubermenu-current-menu-parent > .ubermenu-target,
body .ubermenu-skin-clean-white .ubermenu-item-level-0.ubermenu-current-menu-ancestor > .ubermenu-target,
body .ubermenu-skin-clean-white .ubermenu-submenu .ubermenu-current-menu-item > .ubermenu-target,
body .ubermenu-skin-clean-white .ubermenu-item-level-0 > .ubermenu-target{
	color: <?php echo of_get_option('secondary_color'); ?>;
}

/* Other color fixes */


.navbar-wrapper .login-info .login-btn .fa,
.clanwar-list .upcoming, #matches ul.cmatchesw li .deletematch, .friendswrapper .add-friend, .msg_ntf,
.footer_widget .clanwar-list .scores, .widget .clanwar-list .scores, .user-avatar, .woocommerce-page .product-wrap a.button,
.footer_widget .clanwar-list .upcoming, .widget .clanwar-list .upcoming, .user-wrap a.btns, .cart-outer,
.footer_widget .clanwar-list .playing, .widget .clanwar-list .playing, .pricetable .pricetable-col.featured .pt-top, .pricetable .pricetable-featured .pt-top,
.after-nav .login-tag, .next-line, .bx-wrapper .bx-pager.bx-default-pager a:hover:before,
.bx-wrapper .bx-pager.bx-default-pager a.active:before, .after-nav .login-info a, .clan-page .clan-nav li, .wpb_tabs_nav li,
 #buddypress div.item-list-tabs ul li,
 .blog-date span.date, .blog-date-noimg span.date, .clanwar-list .draw, .carousel-indicators .active, .after-nav .login-info input[type="submit"], .after-nav .login-info a:hover{
	background-color:<?php echo of_get_option('primary_color'); ?>;
}

.slider_com_wrap *{
	color:<?php echo of_get_option('top_grad_color'); ?> !important;
}


.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce table.cart td.actions .button.checkout-button, .button-medium:after, .button-small:after, .button-big:after, button[type="submit"]:after, input[type="submit"]:after {
	opacity:0.8;
}


.woocommerce .product-wrap .add_to_cart_button.added, .woocommerce .product-wrap .add_to_cart_button.added:hover {
	opacity:0.8;
}

div.bbp-template-notice, div.indicator-hint{
	background:<?php echo esc_attr($rgba3); ?>;
}

#bbpress-forums li.bbp-header, #bbpress-forums fieldset.bbp-form legend, .bbp-topic-title h3, .bbp-topics-front ul.super-sticky i.icon-comment,
.bbp-topics ul.super-sticky i.icon-comment,
.bbp-topics ul.sticky i.icon-comment,
.bbp-forum-content ul.sticky i.icon-comment,
.header-colour{
/color:<?php echo of_get_option('header_font_color'); ?>;
}

/* Backgrounds */

html{
<?php
if(of_get_option('background_fix') != 1){

    ?>
	background:url(<?php echo of_get_option('footer_bg'); ?>) <?php if(of_get_option('repeat') == 'b1'){echo "no-repeat";}elseif(of_get_option('repeat') == 'b2'){echo "repeat-y";}elseif(of_get_option('repeat') == 'b3'){echo "repeat-x";}else{echo "repeat";} ?> center bottom <?php echo of_get_option('bg_color'); ?>;
<?php

}
?>
	background-color:<?php echo of_get_option('bg_color'); ?>;
}
body{
<?php
if(of_get_option('background_fix') == 1){

    ?>
        background-attachment: fixed !important;
<?php

}
?>
	background:url(<?php echo of_get_option('header_bg'); ?>) no-repeat center top;
}

</style>