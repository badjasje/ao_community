<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */
function optionsframework_option_name() {
    // This gets the theme name from the stylesheet (lowercase and without spaces)
    $themename = wp_get_theme();
    $themename = $themename['Name'];
    $themename = preg_replace("/\W/", "", strtolower($themename) );
    $optionsframework_settings = get_option('optionsframework');
    $optionsframework_settings['id'] = $themename;
    update_option('optionsframework', $optionsframework_settings);
}
function optionsframework_options() {
    // Slider Options
    $slider_choice_array = array("none" => "No Showcase", "accordion" => "Accordion", "wpheader" => "WordPress Header", "image" => "Your Image", "easing" => "Easing Slider", "custom" => "Custom Slider");
    // Pull all the categories into an array
    $options_categories = array();
    $options_categories_obj = get_categories();
    foreach ($options_categories_obj as $category) {
        $options_categories[$category->cat_ID] = $category->cat_name;
    }
    // Pull all the pages into an array
    $options_pages = array();
    $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
    $options_pages[''] = 'Select a page:';
    foreach ($options_pages_obj as $page) {
        $options_pages[$page->ID] = $page->post_title;
    }
    // If using image radio buttons, define a directory path
    $radioimagepath =  get_stylesheet_directory_uri() . '/themeOptions/images/';
    // define sample image directory path
    $imagepath =  get_template_directory_uri() . '/images/demo/';
    $options = array();
    $options[] = array( "name" => esc_html__("General Settings", 'crystalskull'),
                        "type" => "heading");
   $options[] = array( "name" => esc_html__("General Settings", 'crystalskull'),
                     "type" => "info");
    $options[] = array( "name" => esc_html__("Upload Your Logo", 'crystalskull'),
                        "desc" => esc_html__("Upload your logo. We recommend keeping it within reasonable size. Max 150px and minimum height of 90px but not more than 120px.", 'crystalskull'),
                        "id" => "logo",
                        "std" => get_template_directory_uri()."/img/logo.png",
                        "type" => "upload");
   $options[] = array( "name" => esc_html__("Login button in the menu", 'crystalskull'),
                        "desc" => esc_html__("Enable the login avatar in the menu", 'crystalskull'),
                        "id" => "login_menu",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Image appear effect", 'crystalskull'),
                        "desc" => esc_html__("Enable the image appearing effect when scrolling down.", 'crystalskull'),
                        "id" => "appear",
                        "std" => "1",
                        "type" => "jqueryselect");


   $options[] = array( "name" => esc_html__("Contact email", 'crystalskull'),
                        "desc" => esc_html__("Add your contact email here.", 'crystalskull'),
                        "id" => "email",
                        "std" => "",
                        "type" => "text");
    $options[] = array( "name" => esc_html__("News Ticker", 'crystalskull'),
                     "type" => "info");
    $options[] = array( "name" => esc_html__("Show news ticker", 'crystalskull'),
                        "desc" => esc_html__("Enable news ticker.", 'crystalskull'),
                        "id" => "newsticker",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Ticker title", 'crystalskull'),
                        "desc" => esc_html__("Add ticker title.", 'crystalskull'),
                        "id" => "tickertitle",
                        "std" => "",
                        "type" => "text");

    $options[] = array( "name" => esc_html__("Ticker items", 'crystalskull'),
                        "desc" => esc_html__("Add ticker items. Use || sign to separate items.", 'crystalskull'),
                        "id" => "tickeritems",
                        "std" => "",
                        "type" => "textarea");

$options_categories_obj = get_categories();
    foreach ($options_categories_obj as $category) {
        $options_categories[$category->cat_ID] = $category->cat_name;
    }
    $options_categories[0] = esc_html__('None', 'crystalskull');
	$options_categories[999] = esc_html__('All posts', 'crystalskull');
	$options_categories = array('0' => esc_html__('None', 'crystalskull'), '999' => esc_html__('All posts', 'crystalskull')) + $options_categories;
	$options[] = array(
		'name' => esc_html__( 'Select category', 'crystalskull' ),
		'desc' => esc_html__( 'It will display post titles from that category.', 'crystalskull' ),
		'id' => 'ticker_category',
		'std' => 0,
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $options_categories
	);

	$options[] = array( "name" => esc_html__("Archive page template", 'crystalskull'),
                     "type" => "info");

	$options[] = array(
		'name' => esc_html__( 'Select category/archive page template', 'crystalskull' ),
		'desc' => esc_html__( 'Choose template for your category/archive page.', 'crystalskull' ),
		'id' => 'archive_template',
		'std' => 'right',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => array(
				'full' => esc_html__( 'Full width', 'crystalskull' ),
				'right' => esc_html__( 'Right Sidebar', 'crystalskull' ),
				'left' => esc_html__( 'Left Sidebar - wplocker.com', 'crystalskull' )
		)
	);



//SEO
$options[] = array( "name" => esc_html__("SEO", 'crystalskull'),
                        "type" => "heading");
$options[] = array( "name" => esc_html__("SEO", 'crystalskull'),
                     "type" => "info");
$options[] = array( "name" => esc_html__("Home description", 'crystalskull'),
                        "desc" => esc_html__("Enter home description.", 'crystalskull'),
                        "id" => "metadesc",
                        "std" => "",
                        "type" => "textarea");
$options[] = array( "name" => esc_html__("Keywords", 'crystalskull'),
                        "desc" => esc_html__("Enter keywords comma separated.", 'crystalskull'),
                        "id" => "keywords",
                        "std" => "",
                        "type" => "text");



// Colour Settings
    $options[] = array( "name" => esc_html__("Customize", 'crystalskull'),
                        "type" => "heading");
	$options[] = array( "name" => "Fullwidth",
                	"desc" => "Enable fullwidth site layout.",
                	"id" => "fullwidth",
                	"std" => "1",
                 	"type" => "jqueryselect");
// Backgrounds
    $options[] = array( "name" => esc_html__("Backgrounds", 'crystalskull'),
                        "type" => "info");

    $options[] = array( "name" => esc_html__("Top background", 'crystalskull'),
                        "desc" => esc_html__("Background for the header of the site.", 'crystalskull'),
                        "id" => "header_bg",
                        "std" => get_template_directory_uri()."/img/header.jpg",
                        "type" => "upload");

    $options[] = array( "name" => esc_html__("Fixed background", 'crystalskull'),
                        "desc" => esc_html__("Set background to fixed position.", 'crystalskull'),
                        "id" => "background_fix",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Footer background", 'crystalskull'),
                        "desc" => esc_html__("Background for the footer of the site.", 'crystalskull'),
                        "id" => "footer_bg",
                        "std" => "",
                        "type" => "upload");
	$imagepath =  get_template_directory_uri() . '/themeOptions/images/repeat/';
    $options[] = array( "name" => esc_html__("Footer background repeat", 'crystalskull'),
                        "desc" => esc_html__("You could choose to repeat the background image if you want to use a pattern.", 'crystalskull'),
                        "id" => "repeat",
                        "std" =>"b1",
                        "type" => "images",
						"options" => array(
						'b1' => $imagepath . 'b1.jpg',
						'b2' => $imagepath . 'b2.jpg',
						'b3' => $imagepath . 'b3.jpg',
						'b4' => $imagepath . 'b4.jpg',
						));

    $options[] = array( "name" => esc_html__("Background colour", 'crystalskull'),
                    "desc" => esc_html__("Colour for the background.", 'crystalskull'),

                    "id" => "bg_color",

                    "std" => "#1d2031",

                    "type" => "color" );
	$options[] = array( "name" => esc_html__("Background tint", 'crystalskull'),
                        "desc" => esc_html__("Enable background overlay color.", 'crystalskull'),
                        "id" => "bg_tint",
                        "std" => "1",
                        "type" => "jqueryselect");

$options[] = array( "name" => esc_html__("Background tint color", 'crystalskull'),
                    "desc" => esc_html__("Give a tint colour to the background image.", 'crystalskull'),

                    "id" => "bg_tint_color",

                    "std" => "#009cff",

                    "type" => "color" );

// Colours

    $options[] = array( "name" => esc_html__("Colours", 'crystalskull'),
                        "type" => "info");

    $options[] = array( "name" => esc_html__("Primary Colour", 'crystalskull'),
                    "desc" => esc_html__("Affects different background and items of the site like links, some buttons, etc.", 'crystalskull'),

                    "id" => "primary_color",

                    "std" => "#25c2f5",

                    "type" => "color" );

		$options[] = array( "name" => esc_html__("Secondary Colour", 'crystalskull'),
					"desc" => esc_html__("Affects additional elements of the site.", 'crystalskull'),

					"id" => "secondary_color",

					"std" => "#ffad05",

					"type" => "color" );



// Footer section start
    $options[] = array( "name" => esc_html__("Footer", 'crystalskull'), "type" => "heading");
    $options[] = array( "name" => esc_html__("Footer", 'crystalskull'),
                     "type" => "info");
                $options[] = array( "name" => esc_html__("Copyright", 'crystalskull'),
                        "desc" => esc_html__("You can use HTML code in here.", 'crystalskull'),
                        "id" => "copyright",
                        "std" => "Made by Skywarrior Themes.",
                        "type" => "textarea");
	$options[] = array( "name" => esc_html__("Terms link name", 'hikari'),
                        "desc" => esc_html__("Enter your terms link name.", 'hikari'),
                        "id" => "termsname",
                        "std" => "Terms & Conditions",
                        "type" => "text");
	$options[] = array( "name" => esc_html__("Terms & Conditions", 'crystalskull'),
                        "desc" => esc_html__("Add link to your t&c file", 'crystalskull'),
                        "id" => "terms",
                        "std" => "",
                        "type" => "text");



// Social Media
    $options[] = array( "name" => esc_html__("Social Media", 'crystalskull'),
                        "type" => "heading");
 $options[] = array( "name" => esc_html__("Social Media", 'crystalskull'),
                     "type" => "info");
     $options[] = array( "name" => esc_html__("Enable Twitter", 'crystalskull'),
                        "desc" => esc_html__("Show or hide the Twitter icon that shows on the footer section.", 'crystalskull'),
                        "id" => "twitter",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Twitter Link", 'crystalskull'),
                        "desc" => esc_html__("Paste your twitter link here.", 'crystalskull'),
                        "id" => "twitter_link",
                        "std" => "#",
                        "type" => "text");
    $options[] = array( "name" => esc_html__("Enable Facebook", 'crystalskull'),
                        "desc" => esc_html__("Show or hide the Facebook icon that shows on the footer section.", 'crystalskull'),
                        "id" => "facebook",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Facebook Link", 'crystalskull'),
                        "desc" => esc_html__("Paste your facebook link here.", 'crystalskull'),
                        "id" => "facebook_link",
                        "std" => "#",
                        "type" => "text");
    $options[] = array( "name" => esc_html__("Enable Steam", 'crystalskull'),
                        "desc" => esc_html__("Show or hide the Steam icon that shows on the footer section.", 'crystalskull'),
                        "id" => "steam",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Steam Link", 'crystalskull'),
                        "desc" => esc_html__("Paste your Steam link here.", 'crystalskull'),
                        "id" => "steam_link",
                        "std" => "#",
                        "type" => "text");

    $options[] = array( "name" => esc_html__("Enable Pinterest", 'crystalskull'),
                        "desc" => esc_html__("Show or hide the Pinterest icon that shows on the footer section.", 'crystalskull'),
                        "id" => "pinterest",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Pinterest Link", 'crystalskull'),
                        "desc" => esc_html__("Paste your pinterest link here.", 'crystalskull'),
                        "id" => "pinterest_link",
                        "std" => "#",
                        "type" => "text");

    $options[] = array( "name" => esc_html__("Enable Google+", 'crystalskull'),
                        "desc" => esc_html__("Show or hide the Google+ icon that shows on the footer section.", 'crystalskull'),
                        "id" => "googleplus",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Google+ Link", 'crystalskull'),
                        "desc" => esc_html__("Paste your google+ link here.", 'crystalskull'),
                        "id" => "google_link",
                        "std" => "#",
                        "type" => "text");
    $options[] = array( "name" => esc_html__("Enable dribbble", 'crystalskull'),
                        "desc" => esc_html__("Show or hide the dribbble icon that shows on the footer section.", 'crystalskull'),
                        "id" => "dribbble",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Dribbble link", 'crystalskull'),
                        "desc" => esc_html__("Paste your dribbble link here.", 'crystalskull'),
                        "id" => "dribbble_link",
                        "std" => "#",
                        "type" => "text");
    $options[] = array( "name" => esc_html__("Enable vimeo", 'crystalskull'),
                        "desc" => esc_html__("Show or hide the vimeo icon that shows on the footer section.", 'crystalskull'),
                        "id" => "vimeo",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Vimeo link", 'crystalskull'),
                        "desc" => esc_html__("Paste your vimeo link here.", 'crystalskull'),
                        "id" => "vimeo_link",
                        "std" => "#",
                        "type" => "text");
      $options[] = array( "name" => esc_html__("Enable youtube", 'crystalskull'),
                        "desc" => esc_html__("Show or hide the youtube icon that shows on the footer section.", 'crystalskull'),
                        "id" => "youtube",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Youtube link", 'crystalskull'),
                        "desc" => esc_html__("Paste your youtube link here.", 'crystalskull'),
                        "id" => "youtube_link",
                        "std" => "#",
                        "type" => "text");
      $options[] = array( "name" => esc_html__("Enable twitch", 'crystalskull'),
                        "desc" => esc_html__("Show or hide the twitch icon that shows on the footer section.", 'crystalskull'),
                        "id" => "twitch",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("Twitch link", 'crystalskull'),
                        "desc" => esc_html__("Paste your twitch link here.", 'crystalskull'),
                        "id" => "twitch_link",
                        "std" => "#",
                        "type" => "text");

    $options[] = array( "name" => esc_html__("Enable RSS", 'crystalskull'),
                        "desc" => esc_html__("Show or hide the RSS icon that shows on the footer section.", 'crystalskull'),
                        "id" => "rss",
                        "std" => "1",
                        "type" => "jqueryselect");
    $options[] = array( "name" => esc_html__("RSS Link", 'crystalskull'),
                        "desc" => esc_html__("Paste your RSS link here.", 'crystalskull'),
                        "id" => "rss_link",
                        "std" => "#",
                        "type" => "text");
// WooCommerce

$options[] = array( "name" => esc_html__("WooCommerce", 'crystalskull'),
                        "type" => "heading");
$options[] = array( "name" => esc_html__("WooCommerce", 'crystalskull'),
                        "type" => "info");
$imagepath =  get_template_directory_uri() . '/themeOptions/images/sidebar/';
    $options[] = array( "name" => esc_html__("Main shop page sidebar", 'crystalskull'),
                        "desc" => esc_html__("Choose page layout that you want to use on main WooCommerce page.", 'crystalskull'),
                        "id" => "mainshop",
                        "std" =>"s3",
                        "type" => "images",
                        "options" => array(
                        's1' => $imagepath . 'full.png',
                        's2' => $imagepath . 'left.png',
                        's3' => $imagepath . 'right.png',

                        ));


    $options[] = array( "name" => esc_html__("Single product page sidebar", 'crystalskull'),
                        "desc" => esc_html__("Choose page layout that you want to use on single product WooCommerce page.", 'crystalskull'),
                        "id" => "singleprod",
                        "std" =>"s1",
                        "type" => "images",
                        "options" => array(
                        's1' => $imagepath . 'full.png',
                        's2' => $imagepath . 'left.png',
                        's3' => $imagepath . 'right.png',

                        ));

	//added by shark
	$options[] = array( "name" => esc_html__("One click install", 'crystalskull'),
                       "type" => "heading");
 	$options[] = array( "name" => esc_html__("One click install", 'crystalskull'),
                     "type" => "info");
	$options[] = array( "name" => esc_html__("demo install", 'crystalskull'),
                        "desc" => esc_html__("Click to install pre-inserted demo contents.", 'crystalskull'),
                        "id" => "demo_install",
                        "std" => "0",
                        "type" => "impbutton");



    return $options;
}
?>