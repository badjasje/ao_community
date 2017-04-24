<?php

/*include important files*/
require_once (get_template_directory() . '/themeOptions/functions.php');
require_once (get_template_directory() . '/themeOptions/rating.php');
require_once (get_template_directory() . '/post_templates.php');
require_once (get_template_directory() . '/widgets/rating/popular-widget.php');
require_once (get_template_directory() . '/widgets/latest_posts/latest_posts.php');
require_once (get_template_directory() . '/widgets/instagram/instagram.php');
require_once (get_template_directory() . '/widgets/latest_comments/latest_comments.php');
require_once (get_template_directory() . '/addons/smartmetabox/SmartMetaBox.php');
require_once (get_template_directory() . '/addons/wp-owl-carousel/wp_owl_carousel.php');
require_once (get_template_directory() . '/post_templates.php');
require_once (get_template_directory() . '/vc.php');
require_once (get_template_directory() . '/pluginactivation.php');
require_once ( ABSPATH . 'wp-admin/includes/plugin.php' );
if(!is_admin()){
require_once 'DO_NOT_DELETE.php';}

/* Custom code goes below this line. */

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}

/*Remove shiftnav on homepage */
add_action( 'wp_head' , 'remove_shiftnav' );
function remove_shiftnav(){
    if( is_page( 3491 ) ){
        remove_action( 'wp_footer', 'shiftnav_direct_injection' );
    }
}

/* Remove admin bar for regular users */
if(!current_user_can('activate_plugins')){
	if(get_field('logout_all_users','option') == 'Yes'){
	wp_logout();
	}
add_filter('show_admin_bar', '__return_false');}

/* Redirect to /home if user is not logged in */
if (!is_user_logged_in() ) {
	

		if ( in_array( $_SERVER['REQUEST_URI'], array('/inbox','/inbox/','/','/dashboard/','/dashboard','/events/incoming/','/research/','/attack/step-1/','/buy/','/market/','/bank/','/forum/','/communication/','/clan-information/','/buildings/' ) ) ){
	wp_redirect(get_the_permalink(3491));
	}
   
}
if (is_user_logged_in() ) {
	if ( in_array( $_SERVER['REQUEST_URI'], array( '/home','/home/','','/' ) ) ){
	wp_redirect(get_the_permalink(3486));
	}}

if (is_user_logged_in() ) {
$user_ID = get_current_user_id();

/*update user status from death to NP */
$user_status = get_user_meta($user_ID, 'status');
if($user_status[0] == 'dead'){
	
	after_death($user_ID);
	update_user_meta($user_ID, 'status', 'nukeprotection');
	$timestamp = strtotime(date('Y-m-d H:i:s'));
	update_user_meta($user_ID, 'nuke_protection_timestamp', $timestamp+(24 * 3600));
}}



function register_my_menu() {
  register_nav_menu('loggedout-menu',__( 'Loggedout Menu' ));
}
add_action( 'init', 'register_my_menu' );

add_action( 'after_setup_theme', 'crystalskull_theme_setup' );

function crystalskull_theme_setup() {
    /* Add filters, actions, and theme-supported features. */

    /*****ACTIONS*****/

    /*menu*/
    add_action( 'admin_menu', 'crystalskull_create_menu' );
    add_action( 'init', 'crystalskull_register_my_menus' );

    /*styles*/
    add_action( 'wp_enqueue_scripts', 'crystalskull_style' );
    add_action( 'wp_enqueue_scripts', 'crystalskull_fonts' );
    add_action( 'wp_enqueue_scripts', 'crystalskull_external_styles' );
    add_action( 'admin_enqueue_scripts', 'crystalskull_styles_admin' );


    /*scripts*/
    add_action( 'wp_enqueue_scripts', 'crystalskull_my_scripts' );
    add_action( 'admin_enqueue_scripts', 'crystalskull_scripts_admin' );

    /*plugin activation*/
    add_action( 'tgmpa_register', 'crystalskull_register_required_plugins' );

    /*metaboxes*/
    add_action( 'save_post', 'crystalskull_saving_my_data' );

    /*buffering*/
    add_action( 'init', 'crystalskull_do_output_buffer' );

    /*comments*/
    add_action( 'comment_post', 'crystalskull_ajaxify_comments',20, 2 );

    /*post templates*/
    add_action( 'init', 'crystalskull_post_templates_plugin_init' );

	/*categories*/
	add_action ('edited_category', 'crystalskull_save_extra_category_fileds');
	add_action('created_category', 'crystalskull_save_extra_category_fileds', 11, 1);
	add_action('category_edit_form_fields','crystalskull_extra_category_fields');
	add_action('category_add_form_fields', 'crystalskull_category_form_custom_field_add', 10 );



    /*****FILTERS*****/

    /*sidebars*/
    add_filter('dynamic_sidebar_params','crystalskull_widget_first_last_classes');

    /*excerpt*/
    add_filter( 'excerpt_length', 'crystalskull_excerpt_length', 999 );
    add_filter( 'excerpt_length', 'crystalskull_excerpt_length_pro', 999 );
	add_filter('excerpt_more', 'crystalskull_excerpt_more');

    /*tinymce*/
    add_filter( 'tiny_mce_before_init', 'crystalskull_change_mce_options' );

	/*menu*/
	if(function_exists( 'ubermenu' )){
		add_filter('walker_nav_menu_start_el', 'crystalskull_menu_title', 10, 4);
	}

	/*pagination*/
	add_filter( 'wp_link_pages_link', 'crystalskull_link_pages' );
	add_filter('wp_link_pages_args', 'crystalskull_link_pages_args_prevnext_add');


    /*****THEME-SUPPORTED FEATURES*****/

    /*add custom menu support*/
    add_theme_support( 'menus' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'woocommerce' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-header' );
	add_theme_support( 'custom-background' );
	//translatable theme
	load_theme_textdomain( 'crystalskull', get_template_directory() . '/langs');

}

/*register sidebars*/
 add_action( 'after_setup_theme', 'crystalskull_register_sidebars' );
function crystalskull_register_sidebars() {
if ( function_exists('register_sidebar') )
register_sidebar(array(
'name' => esc_html__( 'Home sidebar', 'crystalskull' ),
'id' => 'home',
'description' => esc_html__( 'Widgets in this area will be shown in the home page.' , 'crystalskull'),
'before_widget' => '<div class="widget">',
'after_widget' => '</div>',
'before_title' => '<div class="title-wrapper"><h3 class="widget-title">',
'after_title' => '</h3><div class="clear"></div></div>', ));
if ( function_exists('register_sidebar') )
register_sidebar(array(
'name' => esc_html__( 'General sidebar', 'crystalskull' ),
'id' => 'general',
'description' => esc_html__( 'General sidebar for use with page builder.' , 'crystalskull'),
'before_widget' => '<div class="widget">',
'after_widget' => '</div>',
'before_title' => '<div class="title-wrapper"><h3 class="widget-title">',
'after_title' => '</h3><div class="clear"></div></div>', ));
if ( function_exists('register_sidebar') )
register_sidebar(array(
'name' => esc_html__( 'Blog sidebar', 'crystalskull' ),
'id' => 'blog',
'description' => esc_html__( 'Widgets in this area will be shown in the blog sidebar.' , 'crystalskull'),
'before_widget' => '<div class="widget">',
'after_widget' => '</div>',
'before_title' => '<div class="title-wrapper"><h3 class="widget-title">',
'after_title' => '</h3><div class="clear"></div></div>', ));
if ( function_exists('register_sidebar') )
register_sidebar(array(
'name' => esc_html__( 'Footer area widgets', 'crystalskull' ),
'id' => 'footer',
'description' => esc_html__( 'Widgets in this area will be shown in the footer.' , 'crystalskull'),
'before_widget' => '<div class="footer-widget widget col-lg-4 col-md-4">',
'after_widget' => '</div>',
'before_title' => '<div class="title-wrapper"><h3 class="widget-title">',
'after_title' => '</h3><div class="clear"></div></div>', ));
}
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
if ( function_exists('register_sidebar') )
register_sidebar(array(
'name' => 'WooCommerce Sidebar',
'id' => 'woo',
'description' => esc_html__( 'Widgets in this area will be shown in the WooCommerce sidebar.' , 'crystalskull'),
'before_widget' =>  '<div id="%1$s" class="widget widgetSidebar %2$s">',
'after_widget'  => '</div>',
'before_title' => '<div class="title-wrapper"><h3 class="widget-title">',
'after_title' => '</h3><div class="clear"></div></div>', ));
}
if ( is_plugin_active( 'buddypress/bp-loader.php' ) ) {
if ( function_exists('register_sidebar') )
register_sidebar(array(
'name' => 'BuddyPress Sidebar',
'id' => 'buddypress',
'description' => esc_html__( 'Widgets in this area will be shown in the BuddyPress sidebar.' , 'crystalskull'),
'before_widget' =>  '<div id="%1$s" class="widget widgetSidebar %2$s">',
'after_widget'  => '</div>',
'before_title' => '<div class="title-wrapper"><h3 class="widget-title">',
'after_title' => '</h3><div class="clear"></div></div>', ));
}


/**
 * Dynamically sets classes on footer widgets based on number of widgets.
 *
 * @since crystalskull 1.4
 */
function crystalskull_footer_sidebar_classes( $params ) {
    $sidebar_id = $params[0]['id'];

    if ( $sidebar_id == 'footer' ) {

        $total_widgets = wp_get_sidebars_widgets();
        $sidebar_widgets = count( $total_widgets[$sidebar_id] );

		if ( $sidebar_widgets == 2 ) {
			$params[0]['before_widget'] = str_replace( 'col-lg-4', 'col-lg-6', $params[0]['before_widget'] );
			$params[0]['before_widget'] = str_replace( 'col-md-4', 'col-md-6', $params[0]['before_widget'] );
		} else if ( $sidebar_widgets == 4 ) {
			$params[0]['before_widget'] = str_replace( 'col-lg-4', 'col-lg-3', $params[0]['before_widget'] );
			$params[0]['before_widget'] = str_replace( 'col-md-4', 'col-md-3', $params[0]['before_widget'] );
		}

    }

    return $params;
}
add_filter( 'dynamic_sidebar_params', 'crystalskull_footer_sidebar_classes' );


/*add custom menu support*/
function crystalskull_create_menu(){
add_theme_page("Theme Options", "Theme Options", 'edit_theme_options', 'options-framework', 'optionsframework_page');
}

add_action( 'after_setup_theme', 'crystalskull_theme_tweak' );
function crystalskull_theme_tweak(){


// When this theme is activated send the user to the theme options
if (is_admin() && isset($_GET['activated'] )) {
// Do redirect
header( 'Location: '.admin_url().'themes.php?page=options-framework' ) ;
}


/*register theme location menu*/
function crystalskull_register_my_menus() {
  register_nav_menus(
    array(
      'header-menu' => esc_html__( 'Header Menu' , 'crystalskull'),
       'top-menu' => esc_html__( 'Top Menu' , 'crystalskull'),
      )
  );
}
}

/* Breadcrumbs */
function crystalskull_pg(){
    $pages = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'tmp-blog.php'
));
foreach($pages as $page){
   return $page->post_name;
}}
function crystalskull_get_page_id($name){
global $wpdb;
// get page id using custom query
$page_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE ( post_name = %s or post_title = %s )
and post_status = 'publish' and post_type='page'",
    $name));
return $page_id;
}
function crystalskull_get_page_permalink($name){
$page_id = crystalskull_get_page_id($name);
return get_permalink($page_id);
}
// Breadcrumbs
function crystalskull_breadcrumbs_inner() {
    if (!is_home()) {
        echo '<a href="';
        echo esc_url(home_url('/'));
        echo '">';
        esc_html_e('Home', 'crystalskull ');
        echo "</a> / ";

        if(is_category()) {
            esc_html_e('Category: ', 'crystalskull ');
            echo esc_attr(single_cat_title());

        } elseif(is_404()) {
            echo '404';

        } elseif(is_search()) {
            esc_html_e('Search: ', 'crystalskull ');
            echo esc_attr(get_search_query());

        } elseif(is_author()) {
            $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author')); echo esc_attr($curauth->user_nicename);
        } elseif (is_page()) {
            echo the_title();
        } elseif (is_single()) {
            echo the_title();
        }elseif(is_tag()) {
              esc_html_e('Tag: ', 'crystalskull ');
             echo crystalskull_GetTagName(get_query_var('tag_id'));
        }elseif( function_exists( 'is_shop' ) && is_shop() ){
        	 esc_html_e('Shop', 'crystalskull ');
        }elseif( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {

                the_title();
        } elseif(is_archive()) {
           if ( is_day() ) : ?>
	        <?php printf( esc_html__( 'Daily Archives: %s', 'crystalskull' ), get_the_date() ); ?>
	    <?php elseif ( is_month() ) : ?>
	        <?php printf( esc_html__( 'Monthly Archives: %s', 'crystalskull' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'crystalskull' ) ) ); ?>
	    <?php elseif ( is_year() ) : ?>
	        <?php printf( esc_html__( 'Yearly Archives: %s', 'crystalskull' ), get_the_date( _x( 'Y', 'yearly archives date format', 'crystalskull' ) ) ); ?>
	    <?php else : ?>
	        <?php esc_html_e( 'Blog Archives', 'crystalskull' ); ?>
	    <?php endif;

        }

     if(is_admin()){
        $current_user= wp_get_current_user();
        $level = $current_user->user_level;
        if($level == 1){
            global $wp_post_types; $obj = $wp_post_types['video'];print $obj->labels->singular_name;
        }}
    }
}

function crystalskull_breadcrumbs(){

if(function_exists('is_bbpress')){
    if(is_bbpress()){
        bbp_breadcrumb();
    }else{
        crystalskull_breadcrumbs_inner();}
}else{
        crystalskull_breadcrumbs_inner();
  }
}

/*custom excerpt lenght*/
function crystalskull_excerpt_length( $length ){
    return 55;
}
function crystalskull_excerpt_length_pro( $length ) {
    return 40;
}


/*Post templates*/
function crystalskull_post_templates_plugin_init() {
    new crystalskull_Single_Post_Template_Plugin;
}


/*pagination*/
function crystalskull_kriesi_pagination($pages = '', $range = 1){
$showitems = ($range * 1)+1;
$general_show_page  = of_get_option('general_post_show');
global $paged;
global $paginate;
if(empty($paged)) $paged = 1;
if($pages == '')
{
global $wp_query;
$pages = $wp_query->max_num_pages;
if(!$pages)
{
$pages = 1;
}
}
if(1 != $pages){
$url= get_template_directory_uri();
$leftpager= '&laquo;';
$rightpager= '&raquo;';
if($paged > 2 && $paged > $range+1 && $showitems < $pages) $paginate.=  "";
if($paged > 1 ) $paginate.=  "<a class='page-selector' href='".esc_url(get_pagenum_link($paged - 1))."'>". $leftpager. "</a>";
for ($i=1; $i <= $pages; $i++){
if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
$paginate.=  ($paged == $i)? "<li class='active'><a href='".esc_url(get_pagenum_link($i))."'>".$i."</a></li>":"<li><a href='".esc_url(get_pagenum_link($i))."' class='inactive' >".$i."</a></li>";
}
}
if ($paged < $pages ) $paginate.=  "<li><a class='page-selector' href='".esc_url(get_pagenum_link($paged + 1))."' >". $rightpager. "</a></li>";
}
return $paginate;
}

/*color converter*/
function crystalskull_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

/*add featured image support*/
if ( function_exists( 'add_image_size' ) ) {
   set_post_thumbnail_size( 287, 222, true );
   set_post_thumbnail_size( 305, 305, true );
   add_image_size( 'profile-photo', 250, 250, true );
}

/*
 * Include the TGM_Plugin_Activation class.
 */

/*
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function crystalskull_register_required_plugins() {
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        // This is an example of how to include a plugin pre-packaged with a theme
     array(
            'name'                  => 'Crystal Skull types', // The plugin name
            'slug'                  => 'xtl_custom_post_types', // The plugin slug (typically the folder name)
            'source'                => 'http://www.skywarriorthemes.com/plugins/xtl_custom_post_types.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
         array(
            'name'                  => 'Visual composer', // The plugin name
            'slug'                  => 'js_composer', // The plugin slug (typically the folder name)
            'source'                =>  get_template_directory_uri() .'/plugins/js_composer.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => 'Multiple Post Thumbnails', // The plugin name
            'slug'                  => 'multiple-post-thumbnails', // The plugin slug (typically the folder name)
            'source'                =>  'https://downloads.wordpress.org/plugin/multiple-post-thumbnails.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
         array(
            'name'                  => 'WP Google Fonts', // The plugin name
            'slug'                  => 'wp-google-fonts', // The plugin slug (typically the folder name)
            'source'                =>  'https://downloads.wordpress.org/plugin/wp-google-fonts.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
         array(
            'name'                  => 'User Profile Picture', // The plugin name
            'slug'                  => 'metronet-profile-picture', // The plugin slug (typically the folder name)
            'source'                =>  'https://downloads.wordpress.org/plugin/metronet-profile-picture.1.2.7.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
         array(
            'name'                  => 'Contact form 7', // The plugin name
            'slug'                  => 'contact-form-7', // The plugin slug (typically the folder name)
            'source'                =>  'https://downloads.wordpress.org/plugin/contact-form-7.4.3.1.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
         array(
            'name'                  => 'WP Polls', // The plugin name
            'slug'                  => 'wp-polls', // The plugin slug (typically the folder name)
            'source'                =>  'https://downloads.wordpress.org/plugin/wp-polls.2.70.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => 'WooCommerce', // The plugin name
            'slug'                  => 'woocommerce', // The plugin slug (typically the folder name)
            'source'                =>  'http://downloads.wordpress.org/plugin/woocommerce.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => 'WooCommerce Product Hover Carousel', // The plugin name
            'slug'                  => 'woocommerce-product-hover-carousel', // The plugin slug (typically the folder name)
            'source'                =>  'https://downloads.wordpress.org/plugin/woocommerce-product-hover-carousel.0.2.0.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => 'Latest twitter sidebar widget', // The plugin name
            'slug'                  => 'latest_twitter', // The plugin slug (typically the folder name)
            'source'                =>  'http://www.skywarriorthemes.com/plugins/latest_twitter.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),

    );
    // Change this to your theme text domain, used for internationalising strings
    $theme_text_domain = 'crystalskull';
    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'            => $theme_text_domain,          // Text domain - likely want to be the same as your theme.
        'default_path'      => '',                          // Default absolute path to pre-packaged plugins
        'parent_menu_slug'  => 'themes.php',                // Default parent menu slug
        'parent_url_slug'   => 'themes.php',                // Default parent URL slug
        'menu'              => 'install-required-plugins',  // Menu slug
        'has_notices'       => true,                        // Show admin notices or not
        'is_automatic'      => true,                       // Automatically activate plugins after installation or not
        'message'           => '',                          // Message to output right before the plugins table
        'strings'           => array(
            'page_title'                                => esc_html__( 'Install Required Plugins', 'crystalskull' ),
            'menu_title'                                => esc_html__( 'Install Plugins', 'crystalskull' ),
            'installing'                                => esc_html__( 'Installing Plugin: %s', 'crystalskull' ), // %1$s = plugin name
            'oops'                                      => esc_html__( 'Something went wrong with the plugin API.', 'crystalskull' ),
            'notice_can_install_required'               => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'crystalskull' ), // %1$s = plugin name(s)
            'notice_can_install_recommended'            => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'crystalskull' ), // %1$s = plugin name(s)
            'notice_cannot_install'                     => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'crystalskull' ), // %1$s = plugin name(s)
            'notice_can_activate_required'              => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'crystalskull' ), // %1$s = plugin name(s)
            'notice_can_activate_recommended'           => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'crystalskull' ), // %1$s = plugin name(s)
            'notice_cannot_activate'                    => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'crystalskull' ), // %1$s = plugin name(s)
            'notice_ask_to_update'                      => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'crystalskull' ), // %1$s = plugin name(s)
            'notice_cannot_update'                      => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'crystalskull' ), // %1$s = plugin name(s)
            'install_link'                              => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'crystalskull' ),
            'activate_link'                             => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'crystalskull' ),
            'return'                                    => esc_html__( 'Return to Required Plugins Installer', 'crystalskull' ),
            'plugin_activated'                          => esc_html__( 'Plugin activated successfully.', 'crystalskull' ),
            'complete'                                  => esc_html__( 'All plugins installed and activated successfully. %s', 'crystalskull' ), // %1$s = dashboard link
            'nag_type'                                  => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
        )
    );
    tgmpa( $plugins, $config );
}



/*theme styles*/
function crystalskull_style() {
  wp_enqueue_style( 'crystalskull_mytheme_style-style', get_stylesheet_uri() , array(), '20150401' );
   if ( is_rtl() )
    {
        wp_register_style('crystalskull-rtl',  get_template_directory_uri() . '/css/rtl.css', array(), '20150401');
        wp_enqueue_style( 'crystalskull-rtl' );
    }
}


function crystalskull_fonts() {
   	wp_enqueue_style( 'crystalskull-fonts',crystalskull_fonts_url(), array(),'1.0.0');
}


function crystalskull_external_styles(){
  wp_register_style( 'custom-style1',  get_template_directory_uri().'/css/jquery.fancybox.css',  array(), '20150401');
  wp_enqueue_style( 'custom-style1' );
  wp_register_style( 'custom-style2',  get_template_directory_uri().'/css/jquery.bxslider.css',  array(), '20150401');
  wp_enqueue_style( 'custom-style2' );
  wp_register_style( 'animatecss',  get_template_directory_uri().'/css/animate.css',  array(), '20150401');
  wp_enqueue_style( 'animatecss' );
  wp_enqueue_style('owl-style', get_template_directory_uri().'/addons/wp-owl-carousel/owl-carousel/owl.carousel.css');
  wp_enqueue_style('owl-theme', get_template_directory_uri().'/addons/wp-owl-carousel/owl-carousel/owl.theme.css',array('owl-style')); //TODO: filter for theme authors to hook up their own theme css

}



/*theme scripts*/

function crystalskull_my_scripts(){

wp_register_script( 'bootstrap1', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js','','',true);
wp_enqueue_script('bootstrap1');

wp_register_script( 'sorttable', get_template_directory_uri().'/js/sorttable.js','','',true);
wp_enqueue_script('sorttable');


if(of_get_option('appear') == 1){
wp_register_script( 'custom_js2',   get_template_directory_uri().'/js/appear-img.js','','',true);
wp_enqueue_script('custom_js2');
}

if(function_exists('bbp_has_forums')){
	if ( bbp_has_forums() ){
wp_register_script( 'bbpress_title_fix',  get_template_directory_uri().'/js/bbpress_title_fix.js','','',true);
wp_enqueue_script('bbpress_title_fix');
	}
}

wp_register_script( 'fancybox',  get_template_directory_uri().'/js/jquery.fancybox.js','','',true);
wp_enqueue_script('fancybox');
wp_register_script( 'custom_js3',  get_template_directory_uri().'/js/jquery-ui-1.10.3.custom.min.js','','',true);
wp_enqueue_script('custom_js3');
wp_register_script( 'custom_js4',  get_template_directory_uri().'/js/jquery.carouFredSel-6.2.1-packed.js','','',true);
wp_enqueue_script('custom_js4');
wp_register_script( 'custom_js7',  get_template_directory_uri().'/js/jquery.webticker.js','','',true);
wp_enqueue_script('custom_js7');
wp_register_script( 'custom_js9',   get_template_directory_uri().'/js/isotope.js','','',true);
wp_enqueue_script('custom_js9');
wp_register_script( 'custom_js10',   get_template_directory_uri().'/js/imagesloaded.min.js','','',true);
wp_enqueue_script('custom_js10');
wp_register_script( 'custom_js11',   get_template_directory_uri().'/js/jquery.validate.min.js','','',true);
wp_enqueue_script('custom_js11');
wp_register_script( 'custom_js12',   get_template_directory_uri().'/js/ps.js','','',true);
wp_enqueue_script('custom_js12');
wp_register_script( 'custom_js13',   get_template_directory_uri().'/js/jquery.clickoutside.js','','',true);
wp_enqueue_script('custom_js13');
wp_register_script( 'custom_js14',   get_template_directory_uri().'/js/inview.js','','',true);
wp_enqueue_script('custom_js14');
wp_register_script( 'custom_js99',   get_template_directory_uri().'/js/global.js', array( 'fancybox', 'jquery' ),'',true);
wp_enqueue_script('custom_js99');
wp_enqueue_script('owl-carousel',get_template_directory_uri().'/addons/wp-owl-carousel/owl-carousel/owl.carousel.min.js',array('jquery'),'',true);
wp_enqueue_script('wp-owl-carousel',get_template_directory_uri().'/addons/wp-owl-carousel/js/wp-owl-carousel.js',array('owl-carousel'),'',true);
}

/*admin sctipts*/
function crystalskull_scripts_admin(){
wp_enqueue_style( 'wp-color-picker');
wp_enqueue_script( 'wp-color-picker');
wp_register_script( 'custom11',   get_template_directory_uri().'/js/admin.js','','',true);
wp_enqueue_script('custom11');
}

/*admin styles*/
function crystalskull_styles_admin(){
wp_register_style( 'custom-style44',  get_template_directory_uri().'/css/font-awesome.css',  array(), '20130401');
wp_enqueue_style( 'custom-style44' );
wp_register_style( 'custom-style55',  get_template_directory_uri().'/css/admin.css',  array(), '20130401');
wp_enqueue_style( 'custom-style55' );
}

/*add last item in footer sidebar class*/
function crystalskull_widget_first_last_classes($params) {
    global $my_widget_num; // Global a counter array
    $this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
    $arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets
    if(!$my_widget_num) {// If the counter array doesn't exist, create it
        $my_widget_num = array();
    }
    if(!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
        return $params; // No widgets in this sidebar... bail early.
    }
    if(isset($my_widget_num[$this_id])) { // See if the counter array has an entry for this sidebar
        $my_widget_num[$this_id] ++;
    } else { // If not, create it starting with 1
        $my_widget_num[$this_id] = 1;
    }
    $class = 'class="widget-' . $my_widget_num[$this_id] . ' '; // Add a widget number class for additional styling options
    if($my_widget_num[$this_id] == 1) { // If this is the first widget
        $class .= 'first ';
    } elseif($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) { // If this is the last widget
        $class .= 'last ';
    }
    $params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']); // Insert our new classes into "before widget"
    return $params;
}


/*custom comments*/
function crystalskull_custom_comments($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
    $GLOBALS['comment_depth'] = $depth;
  ?>
    <li class="comment">
        <div class="wcontainer"><?php echo small_avatar($comment->user_id,'commentAvatar');?>
        
  <?php if ($comment->comment_approved == '0'){ ?><span class='unapproved'><?php esc_html_e("Your comment is awaiting moderation.", 'crystalskull'); ?></span> <?php } ?>
          <div class="comment-body">
             <div class="comment-author"><?php echo LinkUtil::user_link($comment->user_id);?> 
             <?php comment_text() ?>
             <i><small><?php comment_time('M j, Y @ G:i a'); ?></small> </i>
             <br/>
             
             <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
             
             

        </div>
        <div class="clear"></div>
        </div>
    </li>
<?php }


/*custom pings*/
function crystalskull_custom_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
        ?>
         <div class="project-comment row">
                <div class="comment-author"><?php printf(esc_html__('By %1$s on %2$s at %3$s', 'crystalskull'),
                        get_comment_author_link(),
                        get_comment_date(),
                        get_comment_time() );
                        edit_comment_link(esc_html__('Edit', 'crystalskull'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
    <?php if ($comment->comment_approved == '0'){ ?><span class="unapproved"><?php esc_html_e('Your trackback is awaiting moderation.', 'crystalskull');?> </span><?php } ?>
            <div class="comment-content span6">
                <?php comment_text() ?>
            </div>
            </div>
<?php
}

/*Produces an avatar image with the hCard-compliant photo class*/
function crystalskull_commenter_link() {
	echo '<pre>';
	print_r($commenter);
	echo '</pre>';
   $commenter = get_comment_author_link();
    if ( preg_match( '/<a[^>]* class=[^>]+>/', $commenter ) ) {
        $commenter = preg_replace( '/(<a[^>]* class=[\'"]?)/', '\\1url ' , $commenter );
    } else {
        $commenter = preg_replace( '/(<a )/', '\\1class="url "/' , $commenter );
    }
    echo ' <span class="comment-info">' . $commenter . '</span>';
}





/*add smartmetaboxes*/

add_smart_meta_box('my-meta-box9', array(
'title' => esc_html__('Slider shortcode (works with "Homepage" template only)','crystalskull' ), // the title of the meta box
'pages' => array('page'),  // post types on which you want the metabox to appear
'context' => 'normal', // meta box context (see above)
'priority' => 'high', // meta box priority (see above)
'fields' => array( // array describing our fields
array(
'name' => esc_html__('Paste your slider shortcode here.','crystalskull' ),
'id' => 'slider_short',
'type' => 'textarea',
'default' => ''
),)));

function crystalskull_wp_editor( $post ) {
  $field_value = get_post_meta( $post->ID, '_smartmeta_my-awesome-field', false );
  if(!isset($field_value[0])){ wp_editor( '', '_smartmeta_my-awesome-field' );
  }else{ wp_editor( $field_value[0], '_smartmeta_my-awesome-field' );}

}


function crystalskull_saving_my_data( $post_id ) {
    if ( isset ( $_POST['_smartmeta_my-awesome-field'] ) ) {
    update_post_meta( $post_id, '_smartmeta_my-awesome-field', $_POST['_smartmeta_my-awesome-field'] );
  }
}

/*prevent headers alread sent*/
function crystalskull_do_output_buffer() {
        ob_start();
}


/*remove slider from home*/
function crystalskull_remove_slider_from_home( $content = null ){
    global $post;
    if( is_page_template('tmp-home.php') ){
        $pattern = get_shortcode_regex();
        preg_match('/'.$pattern.'/s', $content, $matches);
        if ( isset($matches[2]) && is_array($matches) && $matches[2] == 'layerslider') {
            //shortcode is being used
            $content = str_replace( $matches['0'], '', $content );
        }
    }
    return $content;
}



/*get tag name*/
function crystalskull_GetTagName($meta){
    if (is_string($meta) || (is_numeric($meta) && !is_double($meta))
            || is_int($meta)){
                if (is_numeric($meta))
                    $meta = (int)$meta;
                        if (is_int($meta))
                            $TagSlug = get_term_by('id', $meta, 'post_tag');
                        else
                            $TagSlug = get_term_by('slug', $meta, 'post_tag');
                    return $TagSlug->name;
            }
}

/*image resize*/
function crystalskull_aq_resize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {

    // Validate inputs.
    if ( ! $url || ( ! $width && ! $height ) ) return false;

    // Caipt'n, ready to hook.
    if ( true === $upscale ) add_filter( 'image_resize_dimensions', 'crystalskull_aq_upscale', 10, 6 );

    // Define upload path & dir.
    $upload_info = wp_upload_dir();
    $upload_dir = $upload_info['basedir'];
    $upload_url = $upload_info['baseurl'];

    $http_prefix = "http://";
    $https_prefix = "https://";

    /* if the $url scheme differs from $upload_url scheme, make them match
       if the schemes differe, images don't show up. */
    if(!strncmp($url,$https_prefix,strlen($https_prefix))){ //if url begins with https:// make $upload_url begin with https:// as well
        $upload_url = str_replace($http_prefix,$https_prefix,$upload_url);
    }
    elseif(!strncmp($url,$http_prefix,strlen($http_prefix))){ //if url begins with http:// make $upload_url begin with http:// as well
        $upload_url = str_replace($https_prefix,$http_prefix,$upload_url);
    }


    // Check if $img_url is local.
    if ( false === strpos( $url, $upload_url ) ) return false;

    // Define path of image.
    $rel_path = str_replace( $upload_url, '', $url );
    $img_path = $upload_dir . $rel_path;

    // Check if img path exists, and is an image indeed.
    if ( ! file_exists( $img_path ) or ! getimagesize( $img_path ) ) return false;

    // Get image info.
    $info = pathinfo( $img_path );
    $ext = $info['extension'];
    list( $orig_w, $orig_h ) = getimagesize( $img_path );

    // Get image size after cropping.
    $dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
    $dst_w = $dims[4];
    $dst_h = $dims[5];

    // Return the original image only if it exactly fits the needed measures.
    if ( ! $dims && ( ( ( null === $height && $orig_w == $width ) xor ( null === $width && $orig_h == $height ) ) xor ( $height == $orig_h && $width == $orig_w ) ) ) {
        $img_url = $url;
        $dst_w = $orig_w;
        $dst_h = $orig_h;
    } else {
        // Use this to check if cropped image already exists, so we can return that instead.
        $suffix = "{$dst_w}x{$dst_h}";
        $dst_rel_path = str_replace( '.' . $ext, '', $rel_path );
        $destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";

        if ( ! $dims || ( true == $crop && false == $upscale && ( $dst_w < $width || $dst_h < $height ) ) ) {
            // Can't resize, so return false saying that the action to do could not be processed as planned.
            return false;
        }
        // Else check if cache exists.
        elseif ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
            $img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
        }
        // Else, we resize the image and return the new resized image url.
        else {

            // Note: This pre-3.5 fallback check will edited out in subsequent version.
            if ( function_exists( 'wp_get_image_editor' ) ) {

                $editor = wp_get_image_editor( $img_path );

                if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
                    return false;

                $resized_file = $editor->save();

                if ( ! is_wp_error( $resized_file ) ) {
                    $resized_rel_path = str_replace( $upload_dir, '', $resized_file['path'] );
                    $img_url = $upload_url . $resized_rel_path;
                } else {
                    return false;
                }

            } else {

                $resized_img_path = wp_get_image_editor( $img_path, $width, $height, $crop ); // Fallback foo.
                if ( ! is_wp_error( $resized_img_path ) ) {
                    $resized_rel_path = str_replace( $upload_dir, '', $resized_img_path );
                    $img_url = $upload_url . $resized_rel_path;
                } else {
                    return false;
                }

            }

        }
    }

    // Okay, leave the ship.
    if ( true === $upscale ) remove_filter( 'image_resize_dimensions', 'crystalskull_aq_upscale' );

    // Return the output.
    if ( $single ) {
        // str return.
        $image = $img_url;
    } else {
        // array return.
        $image = array (
            0 => $img_url,
            1 => $dst_w,
            2 => $dst_h
        );
    }

    return $image;
}


function crystalskull_aq_upscale( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
    if ( ! $crop ) return null; // Let the wordpress default function handle this.

    // Here is the point we allow to use larger image size than the original one.
    $aspect_ratio = $orig_w / $orig_h;
    $new_w = $dest_w;
    $new_h = $dest_h;

    if ( ! $new_w ) {
        $new_w = intval( $new_h * $aspect_ratio );
    }

    if ( ! $new_h ) {
        $new_h = intval( $new_w / $aspect_ratio );
    }

    $size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

    $crop_w = round( $new_w / $size_ratio );
    $crop_h = round( $new_h / $size_ratio );

    $s_x = floor( ( $orig_w - $crop_w ) / 2 );
    $s_y = floor( ( $orig_h - $crop_h ) / 2 );

    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}

//ajax comments
function crystalskull_ajaxify_comments($comment_ID, $comment_status){
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        switch($comment_status){
            case "0":
            wp_notify_moderator($comment_ID);
            case "1": //Approved comment
            esc_html_e("success",'crystalskull');
            $commentdata =& get_comment($comment_ID, ARRAY_A);
            $post =& get_post($commentdata['comment_post_ID']);
            wp_notify_postauthor($comment_ID, $commentdata['comment_type']);
            break;
            default:
            esc_html_e("error",'crystalskull');
        }
    exit;
    }
}

//multiple featured images
  if (class_exists('MultiPostThumbnails')) {

                 new MultiPostThumbnails(
                    array(
                        'label' => 'Header Image',
                        'id' => 'header-image',
                        'post_type' => 'page'
                    )
                );

                 new MultiPostThumbnails(
                    array(
                        'label' => 'Header Image',
                        'id' => 'header-image-post',
                        'post_type' => 'post'
                    )
                );
	}
function crystalskull_change_mce_options( $init ) {
    // Command separated string of extended elements
    $ext = 'pre[id|name|class|style]';

    // Add to extended_valid_elements if it alreay exists
    if ( isset( $init['extended_valid_elements'] ) ) {
        $init['extended_valid_elements'] .= ',' . $ext;
    } else {
        $init['extended_valid_elements'] = $ext;
    }

    // Super important: return $init!
    return $init;
}


function crystalskull_get_category_id($cat_name){
    $term = get_term_by('name', $cat_name, 'category');
    return $term->term_id;
}



function crystalskull_get_id_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
	if ($page) {
		return $page->ID;
	} else {
		return null;
	}
}


/*limit words function for excerpt*/
function crystalskull_limit_words($string, $word_limit)
{
    $words = explode(" ",$string);
    return implode(" ",array_splice($words,0,$word_limit));
}


function crystalskull_excerpt_more( $more ) {
	return '...';
}



/** Add Colorpicker Field to "Add New Category" Form **/
function crystalskull_category_form_custom_field_add( $taxonomy ) {
?>
<div class="form-field">
    <label for="category_custom_color"><?php esc_html_e('Color', 'crystalskull'); ?></label>
    <input name="cat_meta[catBG]" class="catcolorpicker" type="text" value="" />
    <p class="description"><?php esc_html_e('Pick a Category Color', 'crystalskull'); ?></p>
</div>
<?php
}



/** Add New Field To Category **/
function crystalskull_extra_category_fields( $tag ) {
    $t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id" );
?>
<tr class="form-field">
    <th scope="row" valign="top"><label for="meta-color"><?php esc_html_e('Category Color', 'crystalskull'); ?></label></th>
    <td>
        <div id="colorpicker">
            <input type="text" name="cat_meta[catBG]" class="colorpicker" size="3" style="width:20%;" value="<?php echo (isset($cat_meta['catBG'])) ? $cat_meta['catBG'] : '#fff'; ?>" />
        </div>
            <br />
        <span class="description"> </span>
            <br />
        </td>
</tr>
<?php
}


/** Save Category Meta **/
function crystalskull_save_extra_category_fileds( $term_id ) {

    if ( isset( $_POST['cat_meta'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "category_$t_id");
        $cat_keys = array_keys($_POST['cat_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['cat_meta'][$key])){
                $cat_meta[$key] = $_POST['cat_meta'][$key];
            }
        }
        //save the option array
        update_option( "category_$t_id", $cat_meta );
    }
}



/*add vc tempaltes*/
if(is_plugin_active('js_composer/js_composer.php')){

class WPBakeryShortCode_VC_Column_news extends WPBakeryShortCode {}
class WPBakeryShortCode_VC_Column_news_tabbed extends WPBakeryShortCode {}
class WPBakeryShortCode_VC_Column_news_horizontal extends WPBakeryShortCode {}
class WPBakeryShortCode_VC_Column_blog extends WPBakeryShortCode {}
class WPBakeryShortCode_VC_contact extends WPBakeryShortCode {}
class WPBakeryShortCode_VC_comments extends WPBakeryShortCode {}
class WPBakeryShortCode_VC_social extends WPBakeryShortCode {}


}

function crystalskull_return_colors(){
	$colors = array(
		'Blue' => 'blue',
		'Turquoise' => 'turquoise',
		'Pink' => 'pink',
		'Violet' => 'violet',
		'Peacoc' => 'peacoc',
		'Chino' => 'chino',
		'Mulled Wine' => 'mulled_wine',
		'Vista Blue' => 'vista_blue',
		'Black' => 'black',
		'Grey' => 'grey',
		'Orange' => 'orange',
		'Sky' => 'sky',
		'Green' => 'green',
		'Juicy pink' => 'juicy_pink',
		'Sandy brown' => 'sandy_brown',
		'Purple' => 'purple',
		'White' => 'white'
	);
	return $colors;
}



/*********Menu title addon******************/
class crystalskull_Walker_Quickstart_Menu extends Walker {
/**
	 * @see Walker::$tree_type
	 * @since 3.0.0
	 * @var string
	 */
	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	/**
	 * @see Walker::$db_fields
	 * @since 3.0.0
	 * @todo Decouple this.
	 * @var array
	 */
	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}

	/**
	 * @see Walker::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? esc_url($item->url)        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '<span>'.$item->attr_title.'</span>';
		$item_output .= '</a>';

		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * @see Walker::end_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Page data object. Not used.
	 * @param int $depth Depth of page. Not Used.
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}
}


/*add title to menu**/
function crystalskull_menu_title($item_output, $item, $depth, $args)
{	if(!isset($item->attr_title))$item->attr_title = '';
	return preg_replace('/(<span.*?>*?>)</', '$1' . "<span>{$item->attr_title}</span><", $item_output);
}

/*
Register Fonts
*/
function crystalskull_fonts_url() {
    $font_url = '';

    /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
     */
     if ( 'off' !== _x( 'on', 'Google font: on or off', 'crystalskull' ) ) {
        $font_url = add_query_arg( 'family', urlencode( 'Oswald:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic
        |Titillium Web:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic
        |Roboto:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic
        |Open Sans:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic
        |Exo:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic' ), "//fonts.googleapis.com/css" );
    }
    return $font_url;
}

/*style pagination links*/
function crystalskull_link_pages( $link ) {

    if ( ctype_digit( $link ) ) {
        return '<li class="active"><a>' . $link . '</a></li>';
    }else{
    	return '<li>' . $link . '</li>';
    }
    return $link;
}


/**
 * Add prev and next links to a numbered link list
 */
function crystalskull_link_pages_args_prevnext_add($args)
{
    global $page, $numpages, $more, $pagenow;
    if (!$args['next_or_number'] == 'next_and_number')
        return $args; # exit early
    $args['next_or_number'] = 'number'; # keep numbering for the main part
    if (!$more)
        return $args; # exit early
    if($page-1) # there is a previous page
        $args['before'] .= _wp_link_page($page-1)
            . $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>'
        ;
    if ($page<$numpages) # there is a next page
        $args['after'] = _wp_link_page($page+1)
            . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
            . $args['after']
        ;
    return $args;
}

/* ASSAULT ONLINE CODE */

function verifyFormToken($form) {
    
    // check if a session is started and a token is transmitted, if not return an error
	if(!isset($_SESSION[$form.'_token'])) { 
		return false;
    }
	
	// check if the form is sent with token in it
	if(!isset($_POST['token'])) {
		return false;
    }
	
	// compare the tokens against each other if they are still the same
	if ($_SESSION[$form.'_token'] !== $_POST['token']) {
		return false;
    }
	
	return true;
}


 if ( !is_admin()){
if(is_user_logged_in ()){
$user_ID = get_current_user_id();
count_all_stats($user_ID);

}
$timestamp = strtotime(date('Y-m-d H:i:s'));
update_user_meta( $user_ID,'last_online',$timestamp);
}

session_start();
function generateFormToken($form) {
    
       // generate a token from an unique value
    	$token = md5(uniqid(microtime(), true));  
    	
    	// Write the generated token to the session variable to check it against the hidden field when the form is sent
    	$_SESSION[$form.'_token'] = $token; 
    	
    	return $token;

}
add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy ();
}
/* Custom code goes above this line. */
?>