<?php

add_action( 'vc_before_init', 'crystalskull_integrateWithVC' );

function crystalskull_integrateWithVC() {


$categories = get_categories(

array(
        'type'          => 'post',
        'child_of'      => 0,
        'orderby'       => 'name',
        'order'         => 'ASC',
        'hide_empty'    => 1,
        'hierarchical'  => 1,
        'taxonomy'      => 'category',
        'pad_counts'    => false

) );

foreach ($categories as $cat) {
    $cats[$cat->cat_name] = $cat->cat_ID;
}
if(!isset($cats))$cats='';


/* News Block vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'News Block', 'crystalskull' ),
    'base' => 'vc_column_news',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'crystalskull' ),
    'description' => esc_html__( 'A block for news', 'crystalskull' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'crystalskull' ),
            'param_name' => 'el_news_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your news block.', 'crystalskull' )
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Categories', 'crystalskull' ),
            'param_name' => 'el_news_categories',
            'description' => esc_html__( 'Select categories you want to include.', 'crystalskull' ),
            'value' => $cats,
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Number of posts to show', 'crystalskull' ),
            'param_name' => 'el_news_number_posts',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Enter number of posts you wolud like to show in this block.', 'crystalskull' )
        ),
         array(
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Custom Border Color', 'crystalskull' ),
			'param_name' => 'border_color',
			'description' => esc_html__( 'Select border color for your element.', 'crystalskull' ),
		),


    )
) );


/* News Block - Horizontal vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'News Block - Horizontal', 'crystalskull' ),
    'base' => 'vc_column_news_horizontal',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'crystalskull' ),
    'description' => esc_html__( 'A block for horizontal news', 'crystalskull' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'crystalskull' ),
            'param_name' => 'el_news_horizontal_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your news block.', 'crystalskull' )
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Categories', 'crystalskull' ),
            'param_name' => 'el_news_horizontal_categories',
            'description' => esc_html__( 'Select categories you want to include.', 'crystalskull' ),
            'value' => $cats,
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Number of posts to show', 'crystalskull' ),
            'param_name' => 'el_news_horizontal_number_posts',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Enter number of posts you wolud like to show in this block.', 'crystalskull' )
        ),
       	array(
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Custom Border Color', 'crystalskull' ),
			'param_name' => 'border_color',
			'description' => esc_html__( 'Select border color for your element.', 'crystalskull' ),
		),

    )
) );


/* News Block - Tabbed vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'News Block - Tabbed', 'crystalskull' ),
    'base' => 'vc_column_news_tabbed',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'crystalskull' ),
    'description' => esc_html__( 'A block for horizontal news', 'crystalskull' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'crystalskull' ),
            'param_name' => 'el_news_tabbed_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your news block.', 'crystalskull' )
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Categories', 'crystalskull' ),
            'param_name' => 'el_news_tabbed_categories',
            'description' => esc_html__( 'Select categories you want to include.', 'crystalskull' ),
            'value' => $cats,
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Number of posts to show', 'crystalskull' ),
            'param_name' => 'el_news_tabbed_number_posts',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Enter number of posts you wolud like to show in this block.', 'crystalskull' )
        ),
        array(
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Custom Border Color', 'crystalskull' ),
			'param_name' => 'border_color',
			'description' => esc_html__( 'Select border color for your element.', 'crystalskull' ),
		),
             )
) );

/* Blog Block vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'Blog Block', 'crystalskull' ),
    'base' => 'vc_column_blog',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'crystalskull' ),
    'description' => esc_html__( 'A blog block', 'crystalskull' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'crystalskull' ),
            'param_name' => 'el_blog_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your news block.', 'crystalskull' )
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Categories', 'crystalskull' ),
            'param_name' => 'el_blog_categories',
            'description' => esc_html__( 'Select categories you want to include.', 'crystalskull' ),
            'value' => $cats,
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Number of posts to show', 'crystalskull' ),
            'param_name' => 'el_blog_number_posts',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Enter number of posts you wolud like to show in this block.', 'crystalskull' )
        ),
        array(
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Custom Border Color', 'crystalskull' ),
			'param_name' => 'border_color',
			'description' => esc_html__( 'Select border color for your element.', 'crystalskull' ),
		),
    )
) );


/* Contact Block vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'Contact Block', 'crystalskull' ),
    'base' => 'vc_contact',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'crystalskull' ),
    'description' => esc_html__( 'A block with contact form.', 'crystalskull' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'crystalskull' ),
            'param_name' => 'el_contact_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your contact block.', 'crystalskull' )
        )
    )
) );


/* Social Block vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'Social media Block', 'crystalskull' ),
    'base' => 'vc_social',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'crystalskull' ),
    'description' => esc_html__( 'Add social media links', 'crystalskull' ),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'crystalskull' ),
            'param_name' => 'el_social_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your social media block.', 'crystalskull' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Rss link', 'crystalskull' ),
            'param_name' => 'el_social_rss',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Rss feed.', 'crystalskull' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Dribbble link', 'crystalskull' ),
            'param_name' => 'el_social_dribbble',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Dribbble profile.', 'crystalskull' )
        ),
         array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Vimeo link', 'crystalskull' ),
            'param_name' => 'el_social_vimeo',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Vimeo profile.', 'crystalskull' )
        ),
         array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Youtube link', 'crystalskull' ),
            'param_name' => 'el_social_youtube',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Youtube profile.', 'crystalskull' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Twitch link', 'crystalskull' ),
            'param_name' => 'el_social_twitch',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Twitch profile.', 'crystalskull' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Steam link', 'crystalskull' ),
            'param_name' => 'el_social_steam',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Steam profile.', 'crystalskull' )
        ),
         array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Pinterest link', 'crystalskull' ),
            'param_name' => 'el_social_pinterest',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Pinterest profile.', 'crystalskull' )
        ),
         array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Google+ link', 'crystalskull' ),
            'param_name' => 'el_social_google',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Google+ profile.', 'crystalskull' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Twitter link', 'crystalskull' ),
            'param_name' => 'el_social_twitter',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Twitter profile.', 'crystalskull' )
        ),
         array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Facebook link', 'crystalskull' ),
            'param_name' => 'el_social_facebook',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your Facebook profile.', 'crystalskull' )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'DeviantArt link', 'crystalskull' ),
            'param_name' => 'el_social_envato',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add link to your DeviantArt profile.', 'crystalskull' )
        ),

    )
) );


/* Comments Block vojkan
---------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'Comments Block', 'crystalskull' ),
    'base' => 'vc_comments',
    'icon' => 'icon-wpb-layer-shape-text',
    'wrapper_class' => 'clearfix',
    'category' => esc_html__( 'Content', 'crystalskull' ),
    'description' => esc_html__( 'Add comments to your page.', 'crystalskull' ),
     'params' => array(
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Title (optional)', 'crystalskull' ),
            'param_name' => 'el_comments_title',
            'holder' => 'div',
            'value' => '',
            'description' => esc_html__( 'Add title to your comments block.', 'crystalskull' )
        ),
    )

) );
}
