<?php
	/*
	Plugin Name: Colors plugin for AO
	Plugin URI: http://shortcut.nl/
	Description: 
	Version: 1.0
	Author: Kevin Bogaard
	Author URI: 
	*/

/*   NZ color scheme */


function hook_BW_css() {
$user_ID = get_current_user_id();
	
$nightmode = get_user_meta($user_ID, 'nightmode', true);
if($nightmode == 'blackwhite'){

    ?>
        <style>
          .logo img{
	filter: invert(100%);
}
.single_inbox_message {
    border: 1px solid #1f1f1f;
}
.toplist_block a, .statitem a,.media-heading,.media-heading a,.event-message a{
    color: #dadada;
}
.battleMessage, .battleMessage a{
	color:#333;
}

.eventsButtons {
    border: 1px solid #dadada;
    background-color: #1f1f1f;
    text-transform: uppercase;
    font-weight: bold;
    color: #dadada;
}
.blog-content {
    padding: 20px 25px 15px;
    line-height: 24px;
    background-color: #333333;
    border: 1px solid #1f1f1f;
    font-size: 14px;
}
.blog-ind .blog-info {
    border: 1px solid #1f1f1f;
    position: relative;
    border-bottom: 0px;
    background-color: #333333;
    color:#dadada;
}
.blog-ind .blog-info a,.comment-body p,h3.widget-title,.widget ul li a, footer ul li a{
	color:#dadada;
}
.wcontainer {
    padding: 20px;
    background-color: #333333;
    border: 1px solid #1f1f1f;
}
h3.widget-title{
	background-color: #333333;
}
.title-wrapper {
    z-index: 1;
    position: relative;
    border: 1px solid #1f1f1f;
    margin-bottom: 5px;
    background: none;
    background-color: #333333;
}
.blog-ind .blog-content {
    padding-bottom: 25px;
    background-color: #333333;
    border: 1px solid #1f1f1f;
    font-size: 14px;
}
.title-wrapper:after {
    background: #333333;
}
.widget ul {
    margin: 0;
    padding: 0;
    background-color: #333333;
    border: 1px solid #1f1f1f;
}
.blog-image {
    border: none;
}
.bgpattern, .cart-notification, .clan-members-mi, .match-page .mmaps ul li:nth-child(even), .nextmatch_wrap .clan12w, .nm-clans, .post-review, .widget ul li:nth-child(even), .widget.clanwarlist-page ul.clanwar-list li.clanwar-item, .widget_shopping_cart, .woocommerce .cart-notification, ul.about-profile li:nth-child(even) {
    background: #dadada;
    border-top: 1px solid #1f1f1f;
}
@media (min-width: 991px){
.nav-tabs.nav-justified>.active>a, .nav-tabs.nav-justified>.active>a:focus, .nav-tabs.nav-justified>.active>a:hover {
    border-bottom-color: rgba(221, 221, 221, 0);
}
.nav-tabs.nav-justified>li>a {
    border-bottom: 1px solid #dadada;
    border-radius: 4px 4px 0 0;
}
}

input[type=number]{
	background-color: #dadada;
	border: 1px solid #1f1f1f;
	color: #333 !important;
}
.profile_row,.clan_profile_row,.clan_profile_row2 {
    border-bottom: 1px solid #dadada;
}
.post-pinfo a {
    font-size: 12px;
    font-weight: normal;
    color: #333;
}
body .navbar-inverse,body .blog{
	background-color:#333;
}
.navbar-inverse .nav>li>a{
	color:#dadada;
}
.navbar-collapse:after{
	border-color: #333 transparent transparent transparent;
}
.navbar-collapse:before{
	border-color: transparent #333 transparent transparent;
}
.after-nav{
	background-color:#333;
	border-bottom:0px solid #1f1f1f;
}
.list-group-item{
	background-color:#1f1f1f;
	color:#dadada;
	border:0px;
}
.list-group-item:first-child {
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    border-top: 0px;
}
#progress {
    width: 100%;
    padding-top: 20px;
    color: #1f1f1f;
    background-color: #333333;
    text-align: center;
    margin-bottom: 10px;
    border: 4px solid #1f1f1f;
}
#bar {
    padding-top: 5px;
    font-weight: bold;
    background-color: #1f1f1f;
    color: #dadada;
}
.startinghead,.cmheader{
    color: #dadada;
}
.savedUsers{
	color:#dadada;
}
.edit_clan_first {
    background-color: #333333;
    margin-bottom: 30px;
    padding: 30px 15px;
    border: 1px solid #1f1f1f;
}
.nostitem{
	background-color:#333333;
}
a.list-group-item {
    color: #dadada;
    border-top: 1px dashed #dadada;
}
a.list-group-item .list-group-item-heading,.clanpageitem{
	color:#dadada;
}
.nostitem:hover{
	background-color:#1f1f1f !important;
}
.clan_buttons {
    text-align: center;
    border: 1px solid #1f1f1f;
}
body .normal-page{
	background-color:#000;
	padding-top: 0px;
	padding-bottom: 0px; 
}
.build_content,.select2-results{
	background-color:#333333;
}
.btn-general{
	background-color:#1f1f1f;
	color: #dadada;
}
.btn-general:hover{
	background-color:rgba(116, 116, 99, 0.5);
	color: #333;
}
.status_column {
    background-color: #333333;
    color: #dadada !important;
    border: 1px solid #1f1f1f;
}
.attackField input {
    font-size: 24px;
    text-align: center;
    font-weight: bold;
    background-color:rgb(32, 32, 32);
    margin-bottom: 20px !important;
    border: 1px solid #fff;
}
input[type="radio"]:checked + label {
    background-color: rgb(32, 32, 32);
}
.btn-attack {
    color: #fff;
    background-color: #1f1f1f;
    margin-bottom: 3px;
    text-transform: uppercase;
}
.respybutton{
	background-color: #1f1f1f !important;
	color: #dadada !important;
}
.nav-tabs.nav-justified>li>a{
	background-color: #1f1f1f !important;
	color:#fff;
}
.nav-tabs a{
	border:0px;
}
.status_column a{
	color: #dadada !important;
}
.status_header{
	background-color: #1f1f1f;
	color:#fff;
}
.responsive-table thead th {
    background-color: #1f1f1f;
    border: 1px solid #1f1f1f;
    color:#dadada !important;
    font-weight: normal;
    text-align: center;
    color: white;
}
.report_header {
    background-color: #1f1f1f !important;
    color: #dadada !important;
}
.tomahawkNotification {
    background-color: #333333;
    padding: 10px;
    border: 1px solid #1f1f1f;
    margin-top: 20px;
    margin-bottom: 10px;
    text-align: center;
}
#attackCanvas {
    padding-top: 10px;
    padding-bottom: 10px;
    background-color: #dadada;
}

@media (max-width: 768px){
.responsive-table tbody {
    background-color: #333333 !important;
}
}
table tbody tr:nth-child(even) {
    background: rgb(234, 232, 196);
}
.responsive-table tbody tr {
    border: 1px solid #1f1f1f;
}
.event-row{
	background-color: #333333;
}
.toplist_block,.single_inbox_message,.bankBlock,.target_info{
	background-color: #333333;
}
.target_info{
	border: 1px solid #1f1f1f;
}
.bankBlock{
	border: 1px solid #1f1f1f;
}
input[type=submit]{
	background-color: #1f1f1f;
    color: #dadada;
    border:none;
}
input[type=submit]:hover{
	background-color: rgba(115, 115, 98, 0.75);
    color: #dadada !important;
    border:none;
}

.maxDep {
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    font-weight: bold;
    border-bottom: 1px solid #1f1f1f;
    padding: 16px 0px;
    background-color: #1f1f1f;
    color: #fff;
}
.responsive-table {
    font-size: 1em;
    background-color: #333;
    color:#dadada;
}
.responsive-table tbody td{
	color:#dadada;
}
.toplist_block a,a{
	color:#dadada;
}
.attackSelect select {
    color: #fff;
    border: 1px solid #fff;
    background-color: #000;
}
#sform input[type=search], .ubermenu .wpcf7-submit:hover, body .ubermenu-skin-clean-white .ubermenu-item-level-0:hover > .ubermenu-target, body .ubermenu-skin-clean-white .ubermenu-item-level-0.ubermenu-active > .ubermenu-target, body .flex-control-paging li a.flex-active, body .flex-control-paging li a:hover, body .wpb_posts_slider .flex-caption h2 a, .navbar-inverse .nav>li.active>a, .navbar-inverse .nav>li.current-menu-item>a, .navbar-inverse .nav>li>a:hover, .navbar .nav li.current-menu-parent a, .navbar .nav li.current_page_item a, .button-big:hover, .button-medium:hover, .button-small:hover, button[type=submit]:hover, input[type=button]:hover, input[type=submit]:hover, .navbar-nav>li:after, .ticker-title, .after-nav .container:before, div.pagination a:focus, div.pagination a:hover, div.pagination span.current, .page-numbers:focus, .page-numbers:hover, .page-numbers.current, body.woocommerce nav.woocommerce-pagination ul li a:focus, body.woocommerce nav.woocommerce-pagination ul li a:hover, body.woocommerce nav.woocommerce-pagination ul li span.current, .widget .clanwar-list .tabs li:hover a, .widget .clanwar-list .tabs li.selected a, .bgpattern, .post-review, .widget_shopping_cart, .woocommerce .cart-notification, .cart-notification, .splitter li[class*="selected"] > a, .splitter li a:hover, .ls-wp-container .ls-nav-prev, .ls-wp-container .ls-nav-next, a.ui-accordion-header-active, .accordion-heading:hover, .block_accordion_wrapper .ui-state-hover, .cart-wrap, .clanwar-list li ul.tabs li:hover, .clanwar-list li ul.tabs li.selected a:hover, .clanwar-list li ul.tabs li.selected a, .dropdown .caret, .tagcloud a:hover, .progress-striped .bar, .bgpattern:hover > .icon, .progress-striped .bar, .member:hover > .bline, .blog-date span.date, .pbg, .pbg:hover, .pimage:hover > .pbg, ul.social-media li a:hover, .navigation a, .pagination ul > .active > a, .pagination ul > .active > span, .list_carousel a.prev:hover, .list_carousel a.next:hover, .pricetable .pricetable-col.featured .pt-price, .block_toggle .open, .pricetable .pricetable-featured .pt-price, .isotopeMenu, .bbp-topic-title h3, .modal-body .reg-btn, #LoginWithAjax_SubmitButton .reg-btn, .footer_widget h3, buddypress div.item-list-tabs ul li.selected a, .results-main-bg, .blog-date-noimg, .blog-date, .ticker-wrapper.has-js, .ticker-swipe {
    background-color: #1f1f1f;
}
.maxExplore {
    font-size: 14px;
    cursor: pointer;
    text-align: center;
    font-weight: bold;
    border-bottom: 1px solid #1f1f1f;
    padding: 0px 0px;
    background-color: #1f1f1f;
    color: #fff;
}
.positionNo {
    height: 25px;
    width: 25px;
    text-align: center;
    line-height: 24px;
    font-size: 14px;
    color: #fff;
    background-color: #333;
}
.depField input {
    font-size: 24px;
    text-align: center;
    font-weight: bold;
    padding-right: 0px !important;
    padding-left: 0px !important;
    margin-bottom: 20px !important;
    border: 1px solid #fff;
    color:#fff;
    background-color: #000;
}
.button_block,.textNotify,.profile_block {
    border: 1px solid #1f1f1f;
    padding: 10px 5px 8px 5px;
    background-color: #333333;
}
.textNotify,.profile_block,body{
	color:#dadada;
}
.profile_block{
	margin-top:20px;
}
.notice_message, .bonus_message{
	background-color: #333333;
	color: #dadada;
    border: 1px solid #1f1f1f;
}
.status_header{
	color:#dadada;
}
.medal_header{
	background-color:#1f1f1f;
	color: #dadada;
}
.medal_box{
	background-color: #333333;
	color:#dadada;
	border: 1px solid #1f1f1f;
}
.status_column,.textNotify{
	color:#1f1f1f;
}
.status_column a{
	color: #1f1f1f;
}
.market_block{
    padding: 10px 5px 8px 5px;
    background-color: #333333;
}
.spaceNotice,.totalsField{
	border: 1px solid #1f1f1f !important;
	background-color: #333333;
	border:none;
}
h1{
	color: #dadada;
}
h1 a,.title_wrapper .col-lg-12 h1{
	color: #dadada;
}
.current_but {
    background-color: #333 !important;
}
.title_wrapper .breadcrumbs {
    text-align: right;
    color: #dadada;
    margin-top: 27px;
    float: none;
    text-align: center;
    margin-bottom: 20px;
    font-size: 12px;
    text-shadow: 0px 1px 3px rgba(0,0,0,0.5);
}
.nostmessage{
	margin-bottom:20px;
}
.containerNZ{
	background-color: #dadada;
	border-left: 5px solid #272922;
    border-right: 5px solid #272922;
    padding: 20px 15px;
    min-height: 500px;
}
.status_header{
	margin-top:0px;
}
.battlereport-header {
    background-color: #1f1f1f;
    color: #dadada;
    }
.event-row {
    border-left: 1px solid #1f1f1f;
    border-right: 1px solid #1f1f1f;
    border-bottom: 1px solid #1f1f1f;
.title_wrapper .col-lg-12 h1,.title_wrapper .breadcrumbs,.navbar-inverse .nav>li.active>a, .navbar-inverse .nav>li.current-menu-item>a, .navbar-inverse .nav>li>a:hover, .navbar .nav li.current-menu-parent a, .navbar .nav li.current_page_item a{
	color:#dadada;
}
.current_but {
    background-color: #333 !important;
}
.eventsButtons {
    border: 1px solid #dadada;
    background-color: #1f1f1f;
    text-transform: uppercase;
    font-weight: bold;
    color: #dadada;
}

#sform input[type=search], .ubermenu .wpcf7-submit:hover, body .ubermenu-skin-clean-white .ubermenu-item-level-0:hover > .ubermenu-target, body .ubermenu-skin-clean-white .ubermenu-item-level-0.ubermenu-active > .ubermenu-target, body .flex-control-paging li a.flex-active, body .flex-control-paging li a:hover, body .wpb_posts_slider .flex-caption h2 a, .navbar-inverse .nav>li.active>a, .navbar-inverse .nav>li.current-menu-item>a, .navbar-inverse .nav>li>a:hover, .navbar .nav li.current-menu-parent a, .navbar .nav li.current_page_item a, .button-big:hover, .button-medium:hover, .button-small:hover, button[type=submit]:hover, input[type=button]:hover, input[type=submit]:hover, .navbar-nav>li:after, .ticker-title, .after-nav .container:before, div.pagination a:focus, div.pagination a:hover, div.pagination span.current, .page-numbers:focus, .page-numbers:hover, .page-numbers.current, body.woocommerce nav.woocommerce-pagination ul li a:focus, body.woocommerce nav.woocommerce-pagination ul li a:hover, body.woocommerce nav.woocommerce-pagination ul li span.current, .widget .clanwar-list .tabs li:hover a, .widget .clanwar-list .tabs li.selected a, .bgpattern, .post-review, .widget_shopping_cart, .woocommerce .cart-notification, .cart-notification, .splitter li[class*="selected"] > a, .splitter li a:hover, .ls-wp-container .ls-nav-prev, .ls-wp-container .ls-nav-next, a.ui-accordion-header-active, .accordion-heading:hover, .block_accordion_wrapper .ui-state-hover, .cart-wrap, .clanwar-list li ul.tabs li:hover, .clanwar-list li ul.tabs li.selected a:hover, .clanwar-list li ul.tabs li.selected a, .dropdown .caret, .tagcloud a:hover, .progress-striped .bar, .bgpattern:hover > .icon, .progress-striped .bar, .member:hover > .bline, .blog-date span.date, .pbg, .pbg:hover, .pimage:hover > .pbg, ul.social-media li a:hover, .navigation a, .pagination ul > .active > a, .pagination ul > .active > span, .list_carousel a.prev:hover, .list_carousel a.next:hover, .pricetable .pricetable-col.featured .pt-price, .block_toggle .open, .pricetable .pricetable-featured .pt-price, .isotopeMenu, .bbp-topic-title h3, .modal-body .reg-btn, #LoginWithAjax_SubmitButton .reg-btn, .footer_widget h3, buddypress div.item-list-tabs ul li.selected a, .results-main-bg, .blog-date-noimg, .blog-date, .ticker-wrapper.has-js, .ticker-swipe{
	background-color: #1f1f1f;
}

h2,h4{
	color:#e4e4e4;
}
.responsive-table tbody tr{
	border: 1px solid #dadada;
	background-color:#333333;
}
table tbody tr:nth-child(even){
	background-color: #333333;
}
.target_info, .single_inbox_message {
    padding: 20px;
    background-color: #333333;
    border: 1px solid #1f1f1f;
    margin-top: 11px;
}
.target_info a,.single_inbox_message a{
	color:#333;
}
table tbody tr td,.responsive-table tbody th[scope="row"],.responsive-table tbody td[data-title]:before{
	color:#333;
}
.inbox_title a,.responsive-table tbody td a,.h1, h1{
	color:#333;
}
.clan_column a,.clan_profile_row a,table tbody tr td a,.event-row a,.close{
	color:#333;
}
.close{
	opacity: 0.8;
}
.wp-editor-container textarea.wp-editor-area, input[type=file], input[type=password], input[type=password]:active, input[type=password]:focus, input[type=password]:hover, input[type=text], input[type=text]:active, input[type=text]:focus, input[type=text]:hover, select, select:active, select:focus, select:hover, textarea, textarea:active, textarea:focus, textarea:hover{
	background-color:#2f2f2f;
	color:#dadada;
}



::-webkit-input-placeholder { 
    color:    #fff;
}
:-moz-placeholder { 
    color:    #fff;
}
::-moz-placeholder { 
    color:    #fff;
}
:-ms-input-placeholder {
    color:    #fff;
}
#main_wrapper, .owl-item .car_image:after, .newsb-thumbnail a:after, .ins_widget ul li a:after, .blog-image a:after{
		background: url(<?php echo get_template_directory_uri(); ?>/img/pattern.png) top left repeat rgba(246, 255, 197, 0.4) !important;
	}
</style>
    <?php
}}
add_action('wp_head', 'hook_BW_css');



function hook_grayscale_css() {
$user_ID = get_current_user_id();
	
$nightmode = get_user_meta($user_ID, 'nightmode', true);
if($nightmode == 'grayscale'){

    ?>
        <style>
       #main_wrapper, .owl-item .car_image:after, .newsb-thumbnail a:after, .ins_widget ul li a:after, .blog-image a:after {
    background: url(http://intern.assault.online/wp-content/themes/crystalskull/img/pattern.png) top left repeat #00000070;
    filter: grayscale(100%);
    filter: saturate(10%)
}
.dead, .redNotify {
    color: #ff0000 !important;
    filter:initial !important;
}
       
       </style>
    <?php
}}
add_action('wp_head', 'hook_grayscale_css');