<?php
/**
 * Plugin Name: WP Owl Carousel
 * Description: Owl Carousel integration for Wordpress
 * Version: 1.1.0
 * Author: Tanel Kollamaa
 * Text Domain: wp_owl
 * License: GPL2
 */
defined('ABSPATH') or die('No script kiddies please!');

/*  Copyright 2015  Tanel Kollamaa  (email : tanelkollamaa@gmail.com)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if (is_admin()) {
	if (file_exists(__DIR__ . '/cmb2/init.php')) {
		require_once __DIR__ . '/cmb2/init.php';
	} elseif (file_exists(__DIR__ . '/CMB2/init.php')) {
		require_once __DIR__ . '/CMB2/init.php';
	}
}

include_once 'owl_settings.php';

class crystalskull_Wp_Owl_Carousel {
	protected $dir;
	protected $url;
	const prefix = 'wp_owl_';

	function __construct() {
		$this -> url = get_template_directory_uri() . '/addons/wp-owl-carousel';
		$this -> dir = get_template_directory_uri() . '/addons/wp-owl-carousel';

		add_action('edit_form_after_title', array($this, 'render_shortcode_helper'));

		add_action('cmb2_init', array($this, 'create_metaboxes'));


	}

	function create_metaboxes() {
		global $owl_settings;

		$carousel_metabox = new_cmb2_box(array('id' => 'wp_owl_metabox', 'title' => esc_html__('Owl Carousel', 'crystalskull'), 'object_types' => array('wp_owl', ), // Post type
		'context' => 'normal', 'priority' => 'high', 'show_names' => true, 'closed' => false));

		$categories = get_categories(array('type' => 'post', 'child_of' => 0, 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => 1, 'hierarchical' => 1, 'taxonomy' => 'category', 'pad_counts' => false));
		$cats[0] = esc_html__('None', 'crystalskull');
		$cats[999] = esc_html__('All posts', 'crystalskull');
		foreach ($categories as $cat) {
			$cats[$cat -> cat_ID] = $cat -> cat_name;
		}
		if (!isset($cats))
			$cats = '';
		$carousel_metabox -> add_field(array('name' => esc_html__('Posts', 'crystalskull'), 'desc' => esc_html__('Choose post category you want to display', 'crystalskull'), 'id' => self::prefix . 'post_cat', 'type' => 'select', 'default' => 'none', 'options' => $cats));

		$carousel_metabox -> add_field(array('name' => esc_html__('Images', 'crystalskull'), 'desc' => esc_html__('Images to use', 'crystalskull'), 'id' => self::prefix . 'images', 'type' => 'file_list'));

		$image_sizes = get_intermediate_image_sizes();
		$carousel_metabox -> add_field(array('name' => esc_html__('Select size', 'crystalskull'), 'desc' => esc_html__('Select image size to use', 'crystalskull'), 'id' => self::prefix . 'image_size', 'type' => 'select', 'show_option_none' => false, 'default' => 'custom', 'options' => $image_sizes));

		$carousel_metabox -> add_field(array('name' => esc_html__('Rel attribute', 'crystalskull'), 'desc' => esc_html__('Used to open images in a lightbox, see the documentation of your lightbox plugin for this value', 'crystalskull'), 'default' => 'lightbox', 'type' => 'text', 'id' => self::prefix . 'rel'));

		$carousel_metabox -> add_field(array('name' => esc_html__('Link to image size', 'crystalskull'), 'desc' => esc_html__('Generates link to specified image size', 'crystalskull'), 'type' => 'select', 'id' => self::prefix . 'link_to_size', 'options' => array_merge(array('none'), $image_sizes)));

		foreach ($owl_settings as $id => $setting) {
			if ($setting['cmb_type'] == 'checkbox') {
				$def = $this -> set_checkbox_default($setting['default']);
			} else {
				$def = $setting['default'];
			}
			$carousel_metabox -> add_field(array('name' => $setting['name'], 'description' => $setting['desc'], 'id' => self::prefix . $id, 'type' => $setting['cmb_type'], 'default' => $def));
		}
	}

	function render_shortcode_helper() {
		global $post;
		if ($post -> post_type != 'wp_owl')
			return;

		echo '<p>' . esc_html__('Paste this shortcode into a post or a page: ', 'crystalskull');
		echo ' <b> [wp_owl id="' . $post -> ID . '"] </b>';
		echo '</p>';
	}

	function shortcode($atts, $content = null) {
		$attributes = shortcode_atts(array('id' => ""), $atts);

		$html = $this -> generate_owl_html(esc_attr($attributes['id']));

		return $html;
	}

	public static function generate_owl_html($id) {
		$owl = new crystalskull_Wp_Owl_Carousel;
		$files = $owl -> get_owl_items($id);
		$category = get_post_meta($id, self::prefix . 'post_cat', 1);

		if ($category == 0) {

			if (empty($files))
				return;

			$size_id = get_post_meta($id, self::prefix . 'image_size', true);
			$sizes = get_intermediate_image_sizes();

			$settings = $this -> generate_settings_array($id);
			$settings = json_encode($settings);
			$lazyLoad = get_post_meta($id, self::prefix . 'lazyLoad', true);
			$link_to_size = get_post_meta($id, self::prefix . 'link_to_size', true);
			$rel = get_post_meta($id, self::prefix . 'rel', true);
			$html = '<div id="owl-carousel-' . $id . '" class="owl-carousel" data-owloptions=\'' . $settings . '\'>';
			foreach ($files as $id => $url) {
				$html .= '<div>';
				$img = wp_get_attachment_image_src($id, $sizes[$size_id]);

				if ($link_to_size != 0) {
					$img_link = wp_get_attachment_image_src($id, $sizes[$link_to_size]);

					$html .= '<a href="' . esc_url($img_link[0]) . '"';
					$html .= (!empty($rel)) ? ' rel="' . $rel . '"' : '';
					$html .= ' >';
				}

				$html .= '<img alt="" src="' . $img[0] . '" ';

				if ($lazyLoad == 'on') {
					$html .= 'class="lazyOwl" ';
					$html .= 'data-src="' . $img[0] . '" ';
				}
				$html .= '/>';

				$html .= ($link_to_size != 0) ? ' </a>' : '';

				$html .= '</div>';
			}

			$html .= '</div>';

			return $html;

		} else {

			$settings = $owl -> generate_settings_array($id);
			$settings = json_encode($settings);
			$lazyLoad = get_post_meta($id, self::prefix . 'lazyLoad', true);
			$link_to_size = get_post_meta($id, self::prefix . 'link_to_size', true);
			$rel = get_post_meta($id, self::prefix . 'rel', true);
			$html = '<div id="owl-carousel-' . $id . '" class="owl-carousel" data-owloptions=\'' . $settings . '\'>';

			if ($category == 999) {$args = array('orderby' => 'rand', 'posts_per_page' => -1);
			} else {$args = array('category' => $category, 'posts_per_page' => -1);
			}

			$myposts = get_posts($args);
			foreach ($myposts as $post) {
				$categories = wp_get_post_categories($post -> ID);
				$cat_data = get_option("category_$categories[0]");
				$html .= '<div>';
				if (get_post_meta($post -> ID, 'overall_rating', true) != 0) {
					$html .= '<div class="carousel_rating" style="color: ' . esc_attr($cat_data["catBG"]) . '">';
					// overall stars
					$overall_rating = get_post_meta($post -> ID, 'overall_rating', true);


					if ($overall_rating != "0" && $overall_rating == "0.5") {
						$html .= '
                     <i class="fa fa-star-half-o"></i>
                     <i class="fa fa-star-o"></i>
                     <i class="fa fa-star-o"></i>
                     <i class="fa fa-star-o"></i>
                     <i class="fa fa-star-o"></i>
                     ';
					}

					if ($overall_rating != "0" && $overall_rating == "1") {
						$html .= '
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star-o"></i>
                     <i class="fa fa-star-o"></i>
                     <i class="fa fa-star-o"></i>
                     <i class="fa fa-star-o"></i>
                    ';
					}

					if ($overall_rating != "0" && $overall_rating == "1.5") {
						$html .= '
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star-half-o"></i>
                     <i class="fa fa-star-o"></i>
                     <i class="fa fa-star-o"></i>
                     <i class="fa fa-star-o"></i>
                    ';
					}

					if ($overall_rating != "0" && $overall_rating == "2") {
						$html .= '
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star-o"></i>
                     <i class="fa fa-star-o"></i>
                     <i class="fa fa-star-o"></i>
                    ';
					}

					if ($overall_rating != "0" && $overall_rating == "2.5") {
						$html .= '
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star-half-o"></i>
                     <i class="fa fa-star-o"></i>
                     <i class="fa fa-star-o"></i>
                    ';
					}

					if ($overall_rating != "0" && $overall_rating == "3") {
						$html .= '
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star-o"></i>
                     <i class="fa fa-star-o"></i>
                    ';
					}

					if ($overall_rating != "0" && $overall_rating == "3.5") {
						$html .= '
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star-half-o"></i>
                     <i class="fa fa-star-o"></i>
                    ';
					}

					if ($overall_rating != "0" && $overall_rating == "4") {
						$html .= '
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star-o"></i>
                    ';
					}

					if ($overall_rating != "0" && $overall_rating == "4.5") {
						$html .= '
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star-half-o"></i>
                    ';
					}

					if ($overall_rating != "0" && $overall_rating == "5") {
						$html .= '
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                     <i class="fa fa-star"></i>
                    ';
					}
					$html .= '</div>';
				}
				$post_thumbnail_id = get_post_thumbnail_id($post -> ID);
				if (!isset($size_id))
					$size_id = '';
				if (!isset($sizes[$size_id]))
					$sizes[$size_id] = '';
				$img = wp_get_attachment_image_src($post_thumbnail_id, $sizes[$size_id]);

				if ($link_to_size != 0) {
					$img_link = wp_get_attachment_image_src($post_thumbnail_id, $sizes[$link_to_size]);

					$html .= '<a href="' . esc_url($img_link[0]) . '"';
					$html .= (!empty($rel)) ? ' rel="' . $rel . '"' : '';
					$html .= ' >';
				}

				$thumb = get_post_thumbnail_id($post -> ID);
				$img_url = wp_get_attachment_url($thumb, 'full');
				//get img URL
				$image = crystalskull_aq_resize($img_url, 230, 325, true, '', true);
				//resize & crop img
				if(empty($image[0]))$image[0]= get_template_directory_uri().'/img/defaults/default-carousel.jpg';
				$html .= '<a class="car_image" href="' . get_the_permalink($post -> ID) . '"><img alt="" src="' . esc_url($image[0]) . '" ';

				if ($lazyLoad == 'on') {
					$html .= 'class="lazyOwl" ';
					$html .= 'data-src="' . $image[0] . '" ';
				}
				$html .= '/></a>';

				$html .= ($link_to_size != 0) ? ' </a>' : '';

				$html .= '<div class="car_title"><a href="' . esc_url(get_category_link($categories[0])) . '" class="ncategory" style="background-color: ' . esc_attr($cat_data["catBG"]) . ' !important" >';
				$html .= get_cat_name($categories[0]);
				$html .= '</a>';

				$html .= '<a class="car_inner_title" href="' . get_the_permalink($post -> ID) . '">' . get_the_title($post -> ID) . '</a>';
				$author_id=$post->post_author;
				$html .= esc_html__('by ', 'crystalskull');
				$html .= '<a data-original-title="' . esc_html__("View all posts by ", 'crystalskull') . get_the_author_meta( 'user_nicename' , $author_id ) . '" href="' . esc_url(get_author_posts_url($author_id) ) . '">' . get_the_author_meta( 'user_nicename' , $author_id ) . '</a></div>';

				$html .= '</div>';
			}

			$html .= '</div>';

			return $html;

		}
	}

	function get_owl_items($id) {
		$files = get_post_meta($id, self::prefix . 'images', 1);
		return $files;
	}

	function generate_settings_array($id) {
		global $owl_settings;
		$new_settings = array();

		foreach ($owl_settings as $k => $v) {
			$saved = get_post_meta($id, self::prefix . $k, true);

			if ($owl_settings[$k]['cmb_type'] == 'checkbox') {
				if ($saved == 'on') {
					$new_settings[$k] = true;
				} else {
					$new_settings[$k] = false;
				}
			} else {
				if ($owl_settings[$k]['type'] == 'number') {
					$saved = (int)$saved;
				}
				$new_settings[$k] = $saved;
			}
		}
		return $new_settings;
	}

	function set_checkbox_default($default) {
		return isset($_GET['post']) ? '' : ($default ? (string)$default : '');
	}

}

new crystalskull_Wp_Owl_Carousel();
