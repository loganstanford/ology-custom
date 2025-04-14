<?php

/**
 * Plugin Name: Ology Brewing Co
 * Description: Custom plugin for Ology Brewing Co.
 * Version: 0.2
 * Author: Logan Stanford | OnScript Tech, LLC
 **/

global $debug;
$debug = false;

define('OLOGY_ELEMENTS_URL', plugins_url('/', __FILE__));
define('OLOGY_ELEMENTS_PATH', plugin_dir_path(__FILE__));

/**
 * Enqueue or de-enqueue third party plugin scripts/styles
 */
function ology_custom_plugin_styles()
{
	wp_enqueue_style('ology_custom_plugin_style', plugin_dir_url(__FILE__) . 'css/style.css');
	wp_enqueue_style('ology_bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
}
add_action('wp_enqueue_scripts', 'ology_custom_plugin_styles');

function ology_theme_elements_styles_scripts()
{
	wp_register_script('boosted_elements_progression_masonry_js', OLOGY_ELEMENTS_URL . 'js/masonry.js', '', '1.0', true);
	wp_dequeue_style('boosted-elements-progression-prettyphoto-optional'); //Removing a script

	// Font awesome kit
	wp_enqueue_script('font-awesome-kit', 'https://kit.fontawesome.com/f8a171ab2f.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'ology_theme_elements_styles_scripts', 100);
//add_action( 'wp_enqueue_scripts', 'boosted_elements_progression_plugin_enqueuing', 20, 1 );
//wp_enqueue_script( 'boosted_elements_progression_masonry_js' );

if (!function_exists('write_log')) {
	function write_log($log)
	{
		if (is_array($log) || is_object($log)) {
			error_log(print_r($log, true));
		} else {
			error_log($log);
		}
	}
}

/**
 * Registering Custom Post Types
 */
add_action('init', 'ology_cocktail_post_type_init');
function ology_cocktail_post_type_init()
{

	$labels = array(
		'name' => _x('Cocktails', 'Post type general name', 'default'),
		'singular_name' => _x('Cocktail', 'Post type singular name', 'default'),
		'menu_name' => _x('Cocktails', 'Admin Menu text', 'default'),
		'name_admin_bar' => _x('Cocktail', 'Add New on Toolbar', 'default'),
		'add_new' => __('Add New', 'default'),
		'add_new_item' => __('Add New Cocktail', 'default'),
		'new_item' => __('New Cocktail', 'default'),
		'edit_item' => __('Edit Cocktail', 'default'),
		'view_item' => __('View Cocktail', 'default'),
		'all_items' => __('All Cocktails', 'default'),
		'search_items' => __('Search Cocktails', 'default'),
		'parent_item_colon' => __('Parent Cocktails:', 'default'),
		'not_found' => __('No Cocktails found.', 'default'),
		'not_found_in_trash' => __('No Cocktails found in Trash.', 'default'),
		'featured_image' => _x('Cocktail featured Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'default'),
		'set_featured_image' => _x('Set featured image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'default'),
		'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'default'),
		'use_featured_image' => _x('Use as featured image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'default'),
		'archives' => _x('Cocktail archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'default'),
		'insert_into_item' => _x('Insert into Cocktail', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'default'),
		'uploaded_to_this_item' => _x('Uploaded to this Cocktail', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'default'),
		'filter_items_list' => _x('Filter Cocktails list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/Filter pages list". Added in 4.4', 'default'),
		'items_list_navigation' => _x('Cocktails list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/Pages list navigation". Added in 4.4', 'default'),
		'items_list' => _x('Cocktails list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/Pages list". Added in 4.4', 'default'),
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'cocktail'),
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#9da1a7" d="M432 240c53 0 96-43 96-96s-43-96-96-96c-35.5 0-66.6 19.3-83.2 48H296.2C316 40.1 369.3 0 432 0c79.5 0 144 64.5 144 144s-64.5 144-144 144c-27.7 0-53.5-7.8-75.5-21.3l35.4-35.4c12.2 5.6 25.8 8.7 40.1 8.7zM1.8 142.8C5.5 133.8 14.3 128 24 128H392c9.7 0 18.5 5.8 22.2 14.8s1.7 19.3-5.2 26.2l-177 177V464h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H208 120c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V345.9L7 169c-6.9-6.9-8.9-17.2-5.2-26.2z"/></svg>'),
		'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
		'show_in_rest' => true,
		'description' => __('A custom post type for Ology Cocktails', 'default')
	);

	register_post_type('cocktail_ology', $args);

	register_taxonomy(
		'cocktail-ingredient',
		'cocktail_ology',
		array(
			'hierarchical' => false,
			'label' => esc_html__("Ingredients", "progression-elements-ontap"),
			'query_var' => true,
			'show_in_rest' => true,
			'rewrite' => array('slug' => 'cocktail-ingredient'),
		)
	);

	register_taxonomy(
		'cocktail-category',
		'cocktail_ology',
		array(
			'hierarchical' => true,
			'label' => esc_html__("Cocktail Categories", "progression-elements-ontap"),
			'query_var' => true,
			'show_in_rest' => true,
			'rewrite' => array('slug' => 'cocktail-category'),
		)
	);
}
add_action('init', 'ology_coffee_post_type_init');
function ology_coffee_post_type_init()
{
	$labels = array(
		'name' => _x('Coffee', 'Post type general name', 'default'),
		'singular_name' => _x('Coffee', 'Post type singular name', 'default'),
		'menu_name' => _x('Coffee', 'Admin Menu text', 'default'),
		'name_admin_bar' => _x('Coffee', 'Add New on Toolbar', 'default'),
		'add_new' => __('Add New', 'default'),
		'add_new_item' => __('Add New Coffee', 'default'),
		'new_item' => __('New Coffee', 'default'),
		'edit_item' => __('Edit Coffee', 'default'),
		'view_item' => __('View Coffee', 'default'),
		'all_items' => __('All Coffee', 'default'),
		'search_items' => __('Search Coffee', 'default'),
		'parent_item_colon' => __('Parent Coffee:', 'default'),
		'not_found' => __('No Coffee found.', 'default'),
		'not_found_in_trash' => __('No Coffee found in Trash.', 'default'),
		'featured_image' => _x('Coffee featured Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'default'),
		'set_featured_image' => _x('Set featured image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'default'),
		'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'default'),
		'use_featured_image' => _x('Use as featured image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'default'),
		'archives' => _x('Coffee archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'default'),
		'insert_into_item' => _x('Insert into Coffee', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'default'),
		'uploaded_to_this_item' => _x('Uploaded to this Coffee', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'default'),
		'filter_items_list' => _x('Filter Coffee list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/Filter pages list". Added in 4.4', 'default'),
		'items_list_navigation' => _x('Coffee list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/Pages list navigation". Added in 4.4', 'default'),
		'items_list' => _x('Coffee list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/Pages list". Added in 4.4', 'default'),
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'coffee'),
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'menu_icon' => 'dashicons-coffee',
		'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
		'show_in_rest' => true,
		'description' => __('A custom post type for Ology Coffee', 'default')
	);

	register_post_type('coffee_ology', $args);
}
add_action('init', 'ology_food_post_type_init');
function ology_food_post_type_init()
{

	$labels = array(
		'name' => _x('Food', 'Post type general name', 'default'),
		'singular_name' => _x('Food', 'Post type singular name', 'default'),
		'menu_name' => _x('Food', 'Admin Menu text', 'default'),
		'name_admin_bar' => _x('Food', 'Add New on Toolbar', 'default'),
		'add_new' => __('Add New', 'default'),
		'add_new_item' => __('Add New Food', 'default'),
		'new_item' => __('New Food', 'default'),
		'edit_item' => __('Edit Food', 'default'),
		'view_item' => __('View Food', 'default'),
		'all_items' => __('All Food', 'default'),
		'search_items' => __('Search Food', 'default'),
		'parent_item_colon' => __('Parent Food:', 'default'),
		'not_found' => __('No Food found.', 'default'),
		'not_found_in_trash' => __('No Food found in Trash.', 'default'),
		'featured_image' => _x('Food featured Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'default'),
		'set_featured_image' => _x('Set featured image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'default'),
		'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'default'),
		'use_featured_image' => _x('Use as featured image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'default'),
		'archives' => _x('Food archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'default'),
		'insert_into_item' => _x('Insert into Food', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'default'),
		'uploaded_to_this_item' => _x('Uploaded to this Food', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'default'),
		'filter_items_list' => _x('Filter Food list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/Filter pages list". Added in 4.4', 'default'),
		'items_list_navigation' => _x('Food list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/Pages list navigation". Added in 4.4', 'default'),
		'items_list' => _x('Food list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/Pages list". Added in 4.4', 'default'),
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'food'),
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'menu_icon' => 'dashicons-food',
		'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
		'show_in_rest' => true,
		'description' => __('A custom post type for Ology Food', 'default')
	);

	register_post_type('food_ology', $args);

	register_taxonomy(
		'food-diet',
		'food_ology',
		array(
			'hierarchical' => true,
			'label' => esc_html__("Food Diet", "progression-elements-ontap"),
			'query_var' => true,
			'show_in_rest' => true,
			'rewrite' => array('slug' => 'food-diet'),
		)
	);

	register_taxonomy(
		'food-category',
		'food_ology',
		array(
			'hierarchical' => true,
			'label' => esc_html__("Food Categories", "progression-elements-ontap"),
			'query_var' => true,
			'show_in_rest' => true,
			'rewrite' => array('slug' => 'food-category'),
		)
	);
}

add_action('init', 'ology_spirit_post_type_init');
function ology_spirit_post_type_init()
{

	$labels = array(
		'name' => _x('Spirits', 'Post type general name', 'default'),
		'singular_name' => _x('Spirit', 'Post type singular name', 'default'),
		'menu_name' => _x('Spirits', 'Admin Menu text', 'default'),
		'name_admin_bar' => _x('Spirit', 'Add New on Toolbar', 'default'),
		'add_new' => __('Add New', 'default'),
		'add_new_item' => __('Add New Spirit', 'default'),
		'new_item' => __('New Spirit', 'default'),
		'edit_item' => __('Edit Spirit', 'default'),
		'view_item' => __('View Spirit', 'default'),
		'all_items' => __('All Spirits', 'default'),
		'search_items' => __('Search Spirits', 'default'),
		'parent_item_colon' => __('Parent Spirits:', 'default'),
		'not_found' => __('No Spirits found.', 'default'),
		'not_found_in_trash' => __('No Spirits found in Trash.', 'default'),
		'featured_image' => _x('Spirit featured Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'default'),
		'set_featured_image' => _x('Set featured image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'default'),
		'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'default'),
		'use_featured_image' => _x('Use as featured image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'default'),
		'archives' => _x('Spirit archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'default'),
		'insert_into_item' => _x('Insert into Spirit', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'default'),
		'uploaded_to_this_item' => _x('Uploaded to this Spirit', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'default'),
		'filter_items_list' => _x('Filter Spirits list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/Filter pages list". Added in 4.4', 'default'),
		'items_list_navigation' => _x('Spirits list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/Pages list navigation". Added in 4.4', 'default'),
		'items_list' => _x('Spirits list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/Pages list". Added in 4.4', 'default'),
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'spirit'),
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#9da1a7" d="M393.4 9.4c12.5-12.5 32.8-12.5 45.3 0l64 64c12.5 12.5 12.5 32.8 0 45.3c-11.8 11.8-30.7 12.5-43.2 1.9l-9.5 9.5-48.8 48.8c-9.2 9.2-11.5 22.9-8.6 35.6c9.4 40.9-1.9 85.6-33.8 117.5L197.3 493.3c-25 25-65.5 25-90.5 0l-88-88c-25-25-25-65.5 0-90.5L180.2 153.3c31.9-31.9 76.6-43.1 117.5-33.8c12.6 2.9 26.4 .5 35.5-8.6l48.8-48.8 9.5-9.5c-10.6-12.6-10-31.4 1.9-43.2zM99.3 347.3l65.4 65.4c6.2 6.2 16.4 6.2 22.6 0l97.4-97.4c6.2-6.2 6.2-16.4 0-22.6l-65.4-65.4c-6.2-6.2-16.4-6.2-22.6 0L99.3 324.7c-6.2 6.2-6.2 16.4 0 22.6z"/></svg>'),
		'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
		'show_in_rest' => true,
		'description' => __('A custom post type for Ology Spirits', 'default')
	);

	register_post_type('spirit_ology', $args);

	register_taxonomy(
		'spirit-style',
		'spirit_ology',
		array(
			'hierarchical' => true,
			'label' => esc_html__("Spirit Style", "progression-elements-ontap"),
			'query_var' => true,
			'show_in_rest' => true,
			'rewrite' => array('slug' => 'spirit-style'),
		)
	);

	register_taxonomy(
		'spirit-category',
		'spirit_ology',
		array(
			'hierarchical' => true,
			'label' => esc_html__("Spirit Categories", "progression-elements-ontap"),
			'query_var' => true,
			'show_in_rest' => true,
			'rewrite' => array('slug' => 'spirit-category'),
		)
	);
}

add_action('init', 'ology_menu_post_type_init');
function ology_menu_post_type_init()
{

	$labels = array(
		'name' => _x('Menus', 'Post type general name', 'default'),
		'singular_name' => _x('Menu', 'Post type singular name', 'default'),
		'menu_name' => _x('Menus', 'Admin Menu text', 'default'),
		'name_admin_bar' => _x('Menu', 'Add New on Toolbar', 'default'),
		'add_new' => __('Add New', 'default'),
		'add_new_item' => __('Add New Menu', 'default'),
		'new_item' => __('New Menu', 'default'),
		'edit_item' => __('Edit Menu', 'default'),
		'view_item' => __('View Menu', 'default'),
		'all_items' => __('All Menus', 'default'),
		'search_items' => __('Search Menus', 'default'),
		'parent_item_colon' => __('Parent Menus:', 'default'),
		'not_found' => __('No Menus found.', 'default'),
		'not_found_in_trash' => __('No Menus found in Trash.', 'default'),
		'featured_image' => _x('Menu featured Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'default'),
		'set_featured_image' => _x('Set featured image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'default'),
		'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'default'),
		'use_featured_image' => _x('Use as featured image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'default'),
		'archives' => _x('Menu archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'default'),
		'insert_into_item' => _x('Insert into Menu', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'default'),
		'uploaded_to_this_item' => _x('Uploaded to this Menu', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'default'),
		'filter_items_list' => _x('Filter Menus list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/Filter pages list". Added in 4.4', 'default'),
		'items_list_navigation' => _x('Menus list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/Pages list navigation". Added in 4.4', 'default'),
		'items_list' => _x('Menus list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/Pages list". Added in 4.4', 'default'),
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'menu'),
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'menu_icon' => 'dashicons-welcome-widgets-menus',
		'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
		'show_in_rest' => true,
		'description' => __('A custom post type for Ology Menus', 'default')
	);

	register_post_type('menu_ology', $args);
}

add_action('init', 'ology_custom_containers');
function ology_custom_containers()
{
	register_taxonomy(
		'ology-container',
		array('spirit_ology', 'beer_ontap', 'coffee_ology', 'cocktail_ology'),
		array(
			'hierarchical' => false,
			'labels' => array(
				'name' => esc_html__("Containers", "progression-elements-ontap"),
				'singular_name' => esc_html__("Container", "progression-elements-ontap"),
				'menu_name' => _x('Containers', 'Admin Menu text', 'default'),
				'name_admin_bar' => _x('Container', 'Add New on Toolbar', 'default'),
				'add_new' => __('Add New', 'default'),
				'add_new_item' => __('Add New Container', 'default'),
				'new_item' => __('New Container', 'default'),
				'edit_item' => __('Edit Container', 'default'),
				'view_item' => __('View Container', 'default'),
				'all_items' => __('All Containers', 'default'),
				'search_items' => __('Search Containers', 'default'),
				'parent_item_colon' => __('Parent Containers:', 'default'),
				'not_found' => __('No Containers found.', 'default'),
				'not_found_in_trash' => __('No Containers found in Trash.', 'default'),
				'featured_image' => _x('Container featured Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'default'),
				'set_featured_image' => _x('Set featured image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'default'),
				'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'default'),
				'use_featured_image' => _x('Use as featured image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'default'),
				'archives' => _x('Container archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'default'),
				'insert_into_item' => _x('Insert into Container', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'default'),
				'uploaded_to_this_item' => _x('Uploaded to this Container', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'default'),
				'filter_items_list' => _x('Filter Containers list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/Filter pages list". Added in 4.4', 'default'),
				'items_list_navigation' => _x('Containers list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/Pages list navigation". Added in 4.4', 'default'),
				'items_list' => _x('Containers list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/Pages list". Added in 4.4', 'default'),
			),
			'description' => 'A list of Ology container sizes for',
			'show_ui' => true,
			'query_var' => true,
			'show_in_rest' => true,
			'rewrite' => array('slug' => 'container'),
		)
	);

	register_term_meta(
		'ology-container',
		'isToGo',
		array(
			'object_subtype' => 'beer_ontap',
			'type' => 'boolean',
			'single' => true,
			'default' => false
		)
	);
}

add_action('init', 'ology_custom_locations');
function ology_custom_locations()
{
	register_taxonomy(
		'ology-location',
		array('spirit_ology', 'beer_ontap', 'coffee_ology', 'cocktail_ology', 'menu_ology'),
		array(
			'hierarchical' => true,
			'labels' => array(
				'name' => esc_html__("Locations", "progression-elements-ontap"),
				'singular_name' => esc_html__("Location", "progression-elements-ontap"),
				'menu_name' => _x('Locations', 'Admin Menu text', 'default'),
				'name_admin_bar' => _x('Location', 'Add New on Toolbar', 'default'),
				'add_new' => __('Add New', 'default'),
				'add_new_item' => __('Add New Location', 'default'),
				'new_item' => __('New Location', 'default'),
				'edit_item' => __('Edit Location', 'default'),
				'view_item' => __('View Location', 'default'),
				'all_items' => __('All Locations', 'default'),
				'search_items' => __('Search Locations', 'default'),
				'parent_item_colon' => __('Parent Locations:', 'default'),
				'not_found' => __('No Locations found.', 'default'),
				'not_found_in_trash' => __('No Locations found in Trash.', 'default'),
				'featured_image' => _x('Location featured Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'default'),
				'set_featured_image' => _x('Set featured image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'default'),
				'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'default'),
				'use_featured_image' => _x('Use as featured image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'default'),
				'archives' => _x('Location archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'default'),
				'insert_into_item' => _x('Insert into Location', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'default'),
				'uploaded_to_this_item' => _x('Uploaded to this Location', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'default'),
				'filter_items_list' => _x('Filter Locations list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/Filter pages list". Added in 4.4', 'default'),
				'items_list_navigation' => _x('Locations list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/Pages list navigation". Added in 4.4', 'default'),
				'items_list' => _x('Locations list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/Pages list". Added in 4.4', 'default'),
			),
			'description' => 'A list of Ology Locations',
			'show_ui' => true,
			'query_var' => true,
			'show_in_rest' => true,
			'rewrite' => array('slug' => 'location'),
		)
	);
}

add_action('init', 'ology_pinball_leaderboard_post_type_init');
function ology_pinball_leaderboard_post_type_init()
{
	$labels = array(
		'name' => _x('Pinball Leaderboards', 'Post type general name', 'default'),
		'singular_name' => _x('Pinball Leaderboard', 'Post type singular name', 'default'),
		'menu_name' => _x('Pinball Leaderboards', 'Admin Menu text', 'default'),
		'name_admin_bar' => _x('Pinball Leaderboard', 'Add New on Toolbar', 'default'),
		'add_new' => __('Add New', 'default'),
		'add_new_item' => __('Add New Leaderboard', 'default'),
		'new_item' => __('New Leaderboard', 'default'),
		'edit_item' => __('Edit Leaderboard', 'default'),
		'view_item' => __('View Leaderboard', 'default'),
		'all_items' => __('All Leaderboards', 'default'),
		'search_items' => __('Search Leaderboards', 'default'),
		'parent_item_colon' => __('Parent Leaderboard:', 'default'),
		'not_found' => __('No leaderboards found.', 'default'),
		'not_found_in_trash' => __('No leaderboards found in Trash.', 'default'),
		'featured_image' => _x('Leaderboard featured Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'default'),
		'set_featured_image' => _x('Set featured image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'default'),
		'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'default'),
		'use_featured_image' => _x('Use as featured image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'default'),
		'archives' => _x('Leaderboard archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'default'),
		'insert_into_item' => _x('Insert into leaderboard', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'default'),
		'uploaded_to_this_item' => _x('Uploaded to this leaderboard', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'default'),
		'filter_items_list' => _x('Filter leaderboards list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/Filter pages list". Added in 4.4', 'default'),
		'items_list_navigation' => _x('Leaderboards list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/Pages list navigation". Added in 4.4', 'default'),
		'items_list' => _x('Leaderboards list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/Pages list". Added in 4.4', 'default'),
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'pinball-leaderboards'),
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'menu_icon' => 'dashicons-games',
		'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
		'show_in_rest' => true,
		'description' => __('A custom post type for pinball leaderboards', 'default')
	);

	register_post_type('pinball_leaderboard', $args);
}

function ology_check_page_login_requirement()
{
	if (is_page() && !is_user_logged_in()) {
		$require_login = get_post_meta(get_the_ID(), 'ology_require_login', true);
		if ($require_login) {
			$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$login_url = wp_login_url($current_url);
			wp_redirect($login_url);
			exit;
		}
	}
}
add_action('template_redirect', 'ology_check_page_login_requirement');


register_activation_hook(__FILE__, 'ology_activate_plugin');
function ology_activate_plugin()
{
	if (version_compare(get_bloginfo('version'), '6.0', '<')) {
		wp_die('This version of WordPress is not supported for this plugin. Update to at least 6.0 to activate.');
	}
	ology_coffee_post_type_init();
	ology_spirit_post_type_init();
	ology_custom_locations();
	ology_custom_containers();
	flush_rewrite_rules();
}

/**
 * Calling new Page Builder Elements
 */
require_once OLOGY_ELEMENTS_PATH . 'inc/elementor-helper.php';

/**
 * Custom Metabox Fields
 */
require OLOGY_ELEMENTS_PATH . 'inc/custom-meta.php';

/**
 * Custom Toast Integration
 */
require_once OLOGY_ELEMENTS_PATH . 'ology-toast.php';

function ology_trainer_elements_load_elements()
{
	require_once OLOGY_ELEMENTS_PATH . 'elements/post-element.php';
	//require_once OLOGY_ELEMENTS_PATH.'elements/post-beers-element.php';
}
add_action('elementor/widgets/widgets_registered', 'ology_trainer_elements_load_elements');

function register_ology_beers_widget($widgets_manager)
{

	require_once(__DIR__ . '/widgets/ology-beers-widget.php');

	$widgets_manager->register(new \Elementor_Ology_Beers_Widget());
}
add_action('elementor/widgets/register', 'register_ology_beers_widget');

function register_ology_cocktails_widget($widgets_manager)
{

	require_once(__DIR__ . '/widgets/ology-cocktails-widget.php');

	$widgets_manager->register(new \Elementor_Ology_Cocktails_Widget());
}
add_action('elementor/widgets/register', 'register_ology_cocktails_widget');

function register_ology_elementor_widgets($widgets_manager)
{

	require_once(__DIR__ . '/widgets/ology-menu-widget.php');

	$widgets_manager->register(new \Ology_Menu_Widget());
}
add_action('elementor/widgets/register', 'register_ology_elementor_widgets');

/**
 * Summary of getUntappd
 * @param string $locationName
 * @return array
 */
function getUntappdMenu($locationName)
{
	$ologyLocationId = 19772;
	$ologyMenusURL = "https://business.untappd.com/api/v1/locations/$ologyLocationId/menus";
	$headers = [
		'Authorization: Basic bmlja0BvbG9neWJyZXdpbmcuY29tOkJFalBEX0o2Q3U5NXhRR2tKeUFr',
		'Accept: */*'
	];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $ologyMenusURL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$menus = curl_exec($ch);
	if ($menus) {
		$menus = json_decode($menus, true);
		foreach ($menus['menus'] as $loc) {
			if (str_contains(strtolower($loc['name']), strtolower($locationName . " digital menu"))) {
				// If the item in the menus result matches the location parameter, get full menu
				$ologyMenuID = $loc['id'];
				$ologyFullMenuURL = "https://business.untappd.com/api/v1/menus/$ologyMenuID?full=true";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $ologyFullMenuURL);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$result = curl_exec($ch);
				$result = json_decode($result, true);
				return $result;
			}
		}
	}
	return [];
}
// Untappd functions
function ology_updateUntappdPost($item, $debug = false)
{
	$args = array(
		'post_type' => 'beer_ontap',
		'post_status' => array('publish', 'draft'),
		'meta_key' => 'ology_untappd_id',
		'meta_value' => $item['untappd_id']
	);

	// Create post if doesn't exist
	if (!$post = get_posts($args)) {
		$postarr = array(
			'post_title' => $item['name'],
			'post_content' => '',
			'post_excerpt' => $item['description'],
			'post_type' => 'beer_ontap',
			'post_status' => 'publish',
			'meta_input' => array(
				'ology_untappd_id' => $item['untappd_id']
			)
		);

		$debug == true ? printf("</br>Post doesn't exist. Creating.") : null;
		$post_id = wp_insert_post($postarr);
	} else {
		$debug == true ? printf("</br>Post exists. ID: " . $post[0]->ID) : null;
		$post_id = $post[0]->ID;
	}

	$debug == true ? printf("</br>Updating post ID $post_id ABV: " . $item['abv']) : null;

	// Update description and ABV
	wp_update_post(
		array(
			'ID' => $post_id,
			'post_excerpt' => $item['description']
		)
	);
	update_post_meta($post_id, 'ology_abv', $item['abv']);

	return $post_id;
}

function ology_updateUntappdStyleTerms($item, $post_id, $debug = false)
{

	$validStyles = array("Lager", "IPA", "Sour", "Stout", "Other");

	// Determine beer style
	if (str_contains($item['style'], '-')) {
		$a = explode('-', $item['style']);
		$parent_style = trim($a[0]);
		$child_style = trim($a[1]);
		$debug == true ? printf("</br>Parent style: " . $parent_style) : null;
		$debug == true ? printf("</br>Child style: " . $child_style) : null;
	} else {
		$parent_style = trim($item['style']);
	}


	// Compare the style to established valid styles.
	if (!in_array($parent_style, $validStyles)) {

		switch ($parent_style) {

				// If Pilsner, import as Lager
			case str_contains($parent_style, 'Pilsner'):
				$parent_style = "Lager";
				break;

			case str_contains($parent_style, 'Kellerbier'):
				$parent_style = "Lager";
				break;

			case str_contains($parent_style, 'Festbier'):
				$parent_style = "Lager";
				break;

				// If Pale Ale, import as IPA
			case str_contains($parent_style, 'Pale Ale'):
				$parent_style = "IPA";
				break;

			case str_contains($parent_style, 'Polotmavé'):
				$parent_style = "Lager";
				break;

				// If not in valid styles or match a condition above, set as Other.
			default:
				$parent_style = "Other";
				break;
		}
	}


	// If the parent category doesn't exist, create it, otherwise, update beer post with style
	$args = array(
		'taxonomy' => 'beer-style',
		'name' => $parent_style,
		'hide_empty' => false
	);
	if (!$parent_term = get_terms($args)) {
		$args = array(
			'slug' => strtolower(str_replace(' ', '-', $parent_style))
		);
		try {
			$debug == true ? printf("</br>Creating Parent Style: " . $parent_style) : null;
			$parent_term = wp_insert_term($parent_style, 'beer-style', $args);
			printf("This is the value of the var after creation");
			print_r($parent_term);
			printf("</br>[] " . $parent_term['term_id']);
			wp_set_post_terms($post_id, $parent_term['term_id'], 'beer-style');

			printf("</br>Setting post terms: " . $parent_term['term_id']);
		} catch (Exception $e) {
			print_r($e);
			return;
		}
		$parent_term_id = $parent_term['term_id'];
	} else {
		$parent_term_id = $parent_term[0]->term_id;
		printf("</br>Parent style was found.");
		try {
			$debug == true ? printf("</br>Setting Parent Style: " . $parent_style) : null;
			wp_set_post_terms($post_id, $parent_term_id, 'beer-style');
			printf("</br>Setting post terms: " . $parent_term_id);
		} catch (Exception $e) {
			print_r($e);
			return;
		}
	}

	if (isset($child_style)) {
		$args = array(
			'taxonomy' => 'beer-style',
			'name' => $child_style,
			'hide_empty' => false,
			'parent' => $parent_term_id
		);
		if (!$child_term = get_terms($args)) {
			$args = array(
				'slug' => strtolower(str_replace(' ', '-', $child_style)),
				'parent' => $parent_term_id
			);

			$debug == true ? printf("</br>Creating Child Style: " . $child_style . " with parent term id " . $parent_term_id) : null;
			$child_term = wp_insert_term($child_style, 'beer-style', $args);
			wp_set_post_terms($post_id, $child_term['term_id'], 'beer-style', true);
			printf("</br>Setting (child) post terms: " . $child_term['term_id']);
			print_r($child_term);
		} else {
			$debug == true ? printf("</br>Setting Child Style: " . $child_style) : null;
			printf("</br>Setting (child) post terms: " . $child_term[0]->term_id);
			wp_set_post_terms($post_id, $child_term[0]->term_id, 'beer-style', true);
			print_r($child_term);
		}

		$style = $item['style'];
		$args = array(
			'taxonomy' => 'beer-style',
			'name' => $style,
			'hide_empty' => false
		);
	}
}

function ology_updateUntappdContainers($item, $post_id, $location, $debug = false)
{

	$containers = array();
	// Update containers
	foreach ($item['containers'] as $container) {

		$name = trim(str_replace('Draft', '', $container['name']));
		// Container type logic
		$noMatch = false;
		$sizeName = null;
		$sizeSlug = null;
		$isToGo = false;
		switch ($name) {
			case "Taster":
				$sizeName = '3oz';
				$sizeSlug = '03oz';
				break;
			case "Half":
				$sizeName = '6oz';
				$sizeSlug = '06oz';
				break;
			case "Full":
				$sizeName = '12oz';
				$sizeSlug = '12oz';
				break;
			case str_ends_with($name, 'oz'):
				$sizeName = str_replace(' ', '', $name);
				// If container size is a single digit (e.g. 3, 5, 6 etc.) add 0 in front of slug for sorting purposes
				$sizeSlug = strlen($sizeName) === 3 ? "0" . $sizeName : $sizeName;
				break;
			case (str_ends_with($name, 'pk') || str_ends_with($name, 'pack') || str_ends_with($name, 'Pack')):
				$sizeName = '4 Pack';
				$sizeSlug = 'tg-04-pack';
				$isToGo = true;
				break;
			default:
				$noMatch = true;
				break;
		}

		// If container doesn't fit one of the conditions above, then its probably outdated. Skip it.
		if ($noMatch) {
			continue;
		}

		// Add container size if it doesn't exist
		$getTerms = get_terms(
			array(
				'taxonomy' => 'ology-container',
				'hide_empty' => 'false',
				'slug' => $sizeSlug
			)
		);
		if (!$getTerms) {
			$containerId = wp_insert_term(
				$sizeName,
				'ology-container',
				array(
					'slug' => $sizeSlug
				)
			);
			if ($isToGo && is_int($containerId)) {
				add_term_meta($containerId, 'isToGo', true);
			}
		}

		// Add container price
		update_post_meta($post_id, 'ology_' . $location->slug . '_' . $sizeSlug . '_price', $container['price']);

		// Store each available container in an array to add to the post meta
		array_push($containers, $sizeSlug);
	}

	// Update availability meta
	update_post_meta($post_id, 'ology_' . $location->slug . '_availability', $containers);

	// If there are containers, then beer is available, add the location and container availability
	if (count($containers) > 0) {

		wp_set_post_terms($post_id, $location->ID, 'ology-location', true);
		$debug == true ? write_log("</br>Setting location availability: PostID $post_id, " . 'ology_' . $location->slug . '_availability,' . print_r($containers)) : null;
	}
	// else, if containers is 0, then beer is not available. Remove location
	else {
		$debug == true ? write_log("</br>Removing location availability: PostID $post_id, " . 'ology_' . $location->slug . '_availability,' . print_r($containers)) : null;
		wp_remove_object_terms($post_id, $location->ID, 'ology-location');
	}
}

function ology_updateUntappdLocation($post_id, $location_slug)
{
	// Ensure the post ID is a valid number
	if (!is_numeric($post_id)) {
		error_log("Invalid post ID provided to ology_updateUntappdLocation function");
		return false;
	}

	// Check if the location term exists
	$term = get_term_by('slug', $location_slug, 'ology-location');
	if (!$term) {
		error_log("Location term with slug '{$location_slug}' does not exist");
		return false;
	}

	// Assign the location term to the post
	$result = wp_set_post_terms($post_id, array($term->term_id), 'ology-location', false);

	// Check for errors
	if (is_wp_error($result)) {
		error_log("Error updating location for post {$post_id}: " . $result->get_error_message());
		return false;
	}

	return true;
}

function ology_removeUntappdLocation($post_id, $location_slug)
{
	// Ensure the post ID is a valid number
	if (!is_numeric($post_id)) {
		error_log("Invalid post ID provided to ology_removeUntappdLocation function");
		return false;
	}

	// Check if the location term exists
	$term = get_term_by('slug', $location_slug, 'ology-location');
	if (!$term) {
		error_log("Location term with slug '{$location_slug}' does not exist");
		return false;
	}

	// Remove the location term from the post
	$result = wp_remove_object_terms($post_id, $term->term_id, 'ology-location');

	// Check for errors
	if (is_wp_error($result)) {
		error_log("Error removing location for post {$post_id}: " . $result->get_error_message());
		return false;
	}

	return true;
}


function print_var($var, $title = null)
{
	echo "<pre>";
	if (isset($title)) {
		echo "<h3>$title</h3>";
	}
	print_r($var);
	echo "</pre>";
}

// Function to recursively search for the item's ID within the 'containers' array
function search_item_id($array, $value)
{
	foreach ($array as $menu) {
		//print_var($menu, "Menu");
		foreach ($menu['sections'] as $section) {
			//print_var($section, "Section");
			foreach ($section['items'] as $item) {
				printf("Looking for $value</br>");
				//print_var($item, "Item");
				if ($item['untappd_id'] == $value) {
					print_var("Found it!");
					return true;
				}
			}
		}
	}
	printf("Didn't find it");
	return false;
}

function getUntappdItems($locationName = null, $debug = false)
{
	global $wpdb;
	if (!isset($locationName)) {
		$locations = ['midtown', 'power mill', 'northside', 'tampa'];
	} else {
		$locations = [$locationName];
	}

	foreach ($locations as $loc) {

		// Get location term
		$args = array(
			'taxonomy' => 'ology-location',
			'name' => $loc
		);
		$location_term = get_terms($args);

		$debug == true ? printf("</br>Location: $loc </br>") : null;
		$locMenus = getUntappdMenu($loc);
		//$debug == true ? print_r($locMenus) : null;

		// Delete entire availability for location
		//delete_post_meta_by_key('ology_' . $location_term[0]->slug . '_availability');
		// First get all posts with the location term
		// Prepare query arguments
		$args = array(
			'post_type' => 'beer_ontap',
			'post_status' => 'any',
			'nopaging' => true,
			'relation' => 'OR', // This sets the relation between the tax_query and meta_query
			'tax_query' => array(
				array(
					'taxonomy' => 'ology-location',
					'field' => 'slug',
					'terms' => str_replace(' ', '-', $loc),
				),
			),
			'meta_query' => array(
				array(
					'key' => 'ology_' . str_replace(' ', '-', $loc) . '_availability',
					'compare' => 'EXISTS',
				),
			),
		);



		// Execute query
		$locationItems = new WP_Query($args);
		if ($locationItems->have_posts()) {
			while ($locationItems->have_posts()) {
				$locationItems->the_post();
				$post_id = get_the_ID();
				$custom_meta_value = get_post_meta($post_id, 'ology_untappd_id', true);

				if ($debug) {
					printf("</br>Beer name: " . get_the_title());
				}

				// Check if the beer exists in the menu
				$existsInUntappd = search_item_id($locMenus, $custom_meta_value);
				if (!$existsInUntappd) {
					if ($debug) {
						printf("</br>Beer doesn't exist in Untappd. Deleting availability post meta for location: ology_" . $loc . "_availability");
					}
					wp_remove_object_terms($post_id, $loc, 'ology-location');
				}
			}
			wp_reset_postdata();
		} else {
			echo '<pre>';
			echo "No Posts Found for $loc";
			echo '</pre>';
			// No posts found
		}

		// Loop through menus
		foreach ($locMenus as $menu) {
			$debug == true ? printf("</br>Menu: " . $menu['name']) : null;

			foreach ($menu['sections'] as $section) {
				if (!str_contains($section['name'], 'Beer')) {
					continue;
				}
				$debug == true ? printf("</br>Section: " . $section['name']) : null;

				foreach ($section['items'] as $item) {
					$debug == true ? printf("</br>Item: " . $item['name']) : null;

					// Skip if the item is not from Ology
					if ($item['untappd_brewery_id'] != 328707) {
						continue;
					}

					// Create/Update post
					$post_id = ology_updateUntappdPost($item, $debug);

					wp_set_object_terms($post_id, $location_term[0]->slug, 'ology-location', true);

					// Create parent/child styles and as to post meta
					ology_updateUntappdStyleTerms($item, $post_id, $debug);

					// Update on-tap availablitiy/prices
					ology_updateUntappdContainers($item, $post_id, $location_term[0], $debug);

					// Update tap number
					update_post_meta($post_id, 'ology_' . $location_term[0]->slug . '_tap-number', $item['tap_number']);

					// Update Untappd rating and rating count
					if (isset($item['rating']) && isset($item['rating_count'])) {
						update_post_meta($post_id, 'ology_untappd_rating', $item['rating']);
						update_post_meta($post_id, 'ology_untappd_rating_count', $item['rating_count']);
					}
				}
			}
		}
	}
}

function getOlogyOnTapContainers()
{

	$terms = get_terms(
		array(
			'taxonomy' => 'ology-container',
			'hide_empty' => false,
			'orderby' => 'slug',
			'order' => 'ASC',
			'name__like' => 'oz'
		)
	);
	if (!empty($terms) && !is_wp_error($terms)) {
		foreach ($terms as $term) {
			$options[$term->slug] = $term->name;
		}
		return $options;
	}
}

function getOlogyToGoContainers()
{

	// First get All containers, then get OnTap containers, then remove OnTap from All. Should be left with To-go
	$all = getOlogyContainers();
	$onTap = getOlogyOnTapContainers();
	$toGo = array_diff_assoc($all, $onTap);

	foreach ($toGo as $slug => $name) {
		$options[$slug] = $name;
	}
	return $options;
}

function getOlogyContainers()
{

	$terms = get_terms(
		array(
			'taxonomy' => 'ology-container',
			'hide_empty' => false,
			'orderby' => 'slug'
		)
	);
	if (!empty($terms) && !is_wp_error($terms)) {
		foreach ($terms as $term) {
			$options[$term->slug] = $term->name;
		}
		return $options;
	}
}

function get_all_unique_containers()
{
	global $wpdb;

	// Query to get all meta values for keys that start with 'ology_' and end with '_availability'
	$results = $wpdb->get_col("
        SELECT DISTINCT meta_value 
        FROM $wpdb->postmeta 
        WHERE meta_key LIKE 'ology_%_availability'
    ");

	$unique_containers = array();

	// Loop through results and unserialize to get unique containers
	foreach ($results as $result) {
		$availability = maybe_unserialize($result);
		if (is_array($availability)) {
			foreach ($availability as $container) {
				if (!in_array($container, $unique_containers)) {
					$unique_containers[] = $container;
				}
			}
		}
	}

	return $unique_containers;
}

function getOlogyLocations()
{
	$terms = get_terms(
		array(
			'taxonomy' => 'ology-location',
			'hide_empty' => false,
		)
	);

	if (!empty($terms) && !is_wp_error($terms)) {
		foreach ($terms as $term) {
			$options[$term->slug] = $term->name;
		}
		return $options;
	}
}

function getOlogyParentStyle($post_id)
{
	$terms = wp_get_post_terms(
		$post_id,
		'beer-style',
		array(
			'parent' => '0'
		)
	);
	return $terms[0];
}

function getOlogyParentStyles()
{
	$terms = get_terms(
		array(
			'taxonomy' => 'beer-style',
			'parent' => '0',
			'hide_empty' => false
		)
	);

	return $terms;
}

function getOlogyTapNumber($post_id, $locationSlug)
{
	$tap = get_post_meta($post_id, 'ology_' . $locationSlug . '_tap-number', true);
	return $tap;
}

function getOlogyPostContainers($post_id, $locationSlug)
{
	$postContainers = get_post_meta($post_id, 'ology_' . $locationSlug . '_availability', true);
	$allContainers = getOlogyContainers();
	$containerObjects = array();
	foreach ($postContainers as $container) {
		$containerTermName = $allContainers[$container];
		$containerPrice = get_post_meta($post_id, 'ology_' . $locationSlug . '_' . $container . '_price', true);
		$obj = new stdClass();
		$obj->name = $containerTermName;
		$obj->price = $containerPrice;
		array_push($containerObjects, $obj);
	}
	return $containerObjects;
}

add_filter('cron_schedules', 'thirty_secs_cron_interval');
function thirty_secs_cron_interval($schedules)
{
	$schedules['thirty_seconds'] = array(
		'interval' => 30,
		'display' => esc_html__('Every 30 Seconds'),
	);
	return $schedules;
}

function ology_untappd_cron_exec()
{
	getUntappdItems(null, true);
}


add_action('ology_untappd_cron_hook', 'ology_untappd_cron_exec');
if (!wp_next_scheduled('ology_untappd_cron_hook')) {
	wp_schedule_event(time(), 'thirty_seconds', 'ology_untappd_cron_hook');
}

add_action('rest_api_init', function () {
	register_rest_route(
		'ology-custom/v1',
		'/beers/',
		array(
			'methods' => 'GET',
			'callback' => 'ology_get_beers_api',
			'permission_callback' => '__return_true'
		)
	);
});

function ology_get_beers_api(WP_REST_Request $request)
{
	// Prepare query arguments
	$args = array(
		'post_type' => 'beer_ontap',
		'post_status' => array('publish', 'draft'),
		'posts_per_page' => -1
	);

	// Execute the query
	$query = new WP_Query($args);

	// Check if there are posts
	if (!$query->have_posts()) {
		return new WP_REST_Response(array(), 200);
	}

	// Prepare and return the response
	$beers = array_map(function ($post) {
		// Fetch containers data for each post
		$containers = get_beer_containers_meta_for_custom_endpoint($post->ID);
		$rating = get_post_meta($post->ID, 'ology_untappd_rating', true);
		$rating_count = get_post_meta($post->ID, 'ology_untappd_rating_count', true);
		$untappd_id = get_post_meta($post->ID, 'ology_untappd_id', true);

		// Get featured image URL if it exists
		$featured_image_id = get_post_thumbnail_id($post->ID);
		$featured_image_url = $featured_image_id ? wp_get_attachment_url($featured_image_id) : null;

		return array(
			'ID' => $post->ID,
			'title' => $post->post_title,
			'status' => $post->post_status,
			'content' => $post->post_content,
			'containers' => $containers, // Add containers data to the response
			'rating' => $rating, // Add rating to the response
			'rating_count' => $rating_count, // Add rating count to the response
			'ology_untappd_id' => $untappd_id, // Include Untappd ID in the response
			'featured_image_url' => $featured_image_url // Include featured image URL in the response
		);
	}, $query->posts);

	return new WP_REST_Response($beers, 200);
}


function get_beer_containers_meta_for_custom_endpoint($post_id, $locations = null)
{
	// If no specific locations are provided, use all locations
	if ($locations === null) {
		$locations = getOlogyLocations();
	}
	$containers = getOlogyContainers(); // Assuming this returns an array of container types

	$meta = array();
	foreach ($locations as $loc_key => $loc_value) {
		foreach ($containers as $con_key => $con_value) {
			$price_meta_key = 'ology_' . $loc_key . '_' . $con_key . '_price';
			$price = get_post_meta($post_id, $price_meta_key, true);

			if (!empty($price)) {
				$meta[$loc_value][$con_value] = $price;
			}
		}
	}

	return $meta;
}

add_action('rest_api_init', 'register_menu_rest');

function register_menu_rest()
{
	register_rest_route(
		'ology-custom/v1',
		'/menus/(?P<location>[a-zA-Z0-9-]+)',
		array(
			'methods' => 'GET',
			'callback' => 'get_menus_for_location',
			'permission_callback' => '__return_true'
		)
	);
}

function get_menus_for_location($data)
{
	$location_slug = $data['location'];
	$response = array();

	// Query for menus at the location
	$menu_query = new WP_Query(
		array(
			'post_type' => 'menu_ology',
			'tax_query' => array(
				array(
					'taxonomy' => 'ology-location',
					'field' => 'slug',
					'terms' => $location_slug,
				),
			),
			'posts_per_page' => -1,
		)
	);

	// Process menu items
	$menus = array();
	if ($menu_query->have_posts()) {
		while ($menu_query->have_posts()) {
			$menu_query->the_post();

			// Get current timestamp for comparison
			$current_time = current_time('timestamp');
			
			// Initialize arrays for different image types
			$scheduledImages = array();
			$regularImages = array();
			
			// Process scheduled images first
			$scheduled_images = get_post_meta(get_the_ID(), 'ology_scheduled_images', true);
			if (is_array($scheduled_images)) {
				foreach ($scheduled_images as $scheduled_image) {
					// Check if the image should be displayed based on dates
					$start_date = !empty($scheduled_image['start_date']) ? intval($scheduled_image['start_date']) : 0;
					$end_date = !empty($scheduled_image['end_date']) ? intval($scheduled_image['end_date']) : PHP_INT_MAX;
					
					if ($current_time >= $start_date && $current_time <= $end_date) {
						// Image is within the scheduled period, add it to the array
						$image_id = ology_attachment_url_to_postid($scheduled_image['image']);
						$scheduledImages[] = array(
							'id' => $image_id,
							'url' => $scheduled_image['image'],
							'alt' => $scheduled_image['title'],
							'scheduled' => true,
							'start_date' => date('Y-m-d H:i:s', $start_date),
							'end_date' => date('Y-m-d H:i:s', $end_date)
						);
					}
				}
			}
			
			// Process regular images
			$images = get_post_meta(get_the_ID(), 'ology_file_list', true);
			if (is_array($images)) {
				foreach ($images as $id => $url) {
					// Fetch alt text for the image
					$alt_text = get_post_meta($id, '_wp_attachment_image_alt', true);

					// Include alt text in the image array
					$regularImages[] = array('id' => $id, 'url' => $url, 'alt' => $alt_text);
				}
			}
			
			// Combine images in the desired order: scheduled first, then regular
			$imageArray = array_merge($scheduledImages, $regularImages);

			// Add menu details to the response
			$menus[] = array(
				'title' => get_the_title(),
				'description' => get_the_content(),
				'images' => $imageArray,
			);
		}
	}

	// Query for beers at the location
	$beer_query = new WP_Query(
		array(
			'post_type' => 'beer_ontap',
			'post_status' => array('publish', 'draft'),
			'tax_query' => array(
				array(
					'taxonomy' => 'ology-location',
					'field' => 'slug',
					'terms' => $location_slug,
				),
			),
			'posts_per_page' => -1,
		)
	);

	// Process beers
	$beers = array();
	foreach ($beer_query->posts as $post) {
		setup_postdata($post);

		// Fetch beer categories
		$categories = get_the_terms($post->ID, 'beer-category');
		$category_names = array();
		if (!empty($categories) && !is_wp_error($categories)) {
			foreach ($categories as $category) {
				$category_names[] = $category->name;
			}
		}

		// Fetch Untappd rating and rating count from post meta
		$untappd_rating = get_post_meta($post->ID, 'ology_untappd_rating', true);
		$untappd_rating_count = get_post_meta($post->ID, 'ology_untappd_rating_count', true);

		$beers[] = array(
			'ID' => $post->ID,
			'title' => $post->post_title,
			'style' => getOlogyParentStyle($post->ID)->slug,
			'description' => $post->post_excerpt,
			'abv' => get_post_meta($post->ID, 'ology_abv', true),
			'containers' => getOlogyPostContainers($post->ID, $location_slug),
			'categories' => $category_names,
			'rating' => $untappd_rating, // Add rating from post meta
			'rating_count' => $untappd_rating_count, // Add rating count from post meta
		);
	}

	// Combine menus and beers in the response
	$response = array(
		'menus' => $menus,
		'beers' => $beers
	);

	return new WP_REST_Response($response, 200);
}


add_action('rest_api_init', function () {
	register_rest_route('pinball/v1', '/(?P<location>[a-zA-Z0-9-]+)', array(
		'methods' => 'GET',
		'callback' => 'get_pinball_slides',
		'permission_callback' => '__return_true',
		'args' => array(
			'location' => array(
				'validate_callback' => function ($param, $request, $key) {
					return preg_match('/^[a-zA-Z0-9-]+$/', $param); // Validates the location to only include alphanumeric characters and dashes
				}
			),
		),
	));
});

function get_pinball_slides($request)
{
	$location_key = $request->get_param('location');

	// Query the slides based on the location
	$args = array(
		'post_type'      => 'pinball_leaderboard',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_query'     => array(
			array(
				'key'     => 'ology_pinball_location',
				'value'   => $location_key,
				'compare' => '='
			),
		),
	);
	$posts = get_posts($args);
	$data = [];

	foreach ($posts as $post) {
		$serialized_slides = get_post_meta($post->ID, 'ology_pinball_slides', true);
		$slides = maybe_unserialize($serialized_slides);

		foreach ($slides as $slide) {
			$data[] = [
				'type'         => $slide['type'] ?? '',
				'url'          => $slide['type'] === 'webpage' ? ($slide['webpage_url'] ?? '') : ($slide['image-video'] ?? ''),
				'display_time' => $slide['display_time'] ?? '10',  // Defaulting to 10 seconds if not set
			];
		}
	}

	return new WP_REST_Response($data, 200);
}

/**
 * Customize modern dropdown search results
 *
 * @link https://wpforms.com/developers/how-to-customize-the-modern-dropdown-field/
 */

function wpf_dev_modern_dropdown_search_results($config, $forms)
{

	// Change 519 to an ID of your actual form or remove this condition to apply to all forms.
	if (!array_key_exists(519, $forms)) {
		return $config;
	}

	// Change 6 to a large number to show all the matching results for every search (might impact performance).
	$config['searchResultLimit'] = 6;

	return $config;
}
add_filter('wpforms_field_select_choicesjs_config', 'wpf_dev_modern_dropdown_search_results', 10, 2);

/**
 * Hide login URL
 */
function custom_login_url()
{
	return home_url('/login');
}
add_filter('login_url', 'custom_login_url', 10, 3);

function custom_login_page()
{
	if (strpos($_SERVER['REQUEST_URI'], '/login') !== false) {
		include ABSPATH . 'wp-login.php';
		exit;
	}
}
add_action('init', 'custom_login_page');

function redirect_login_page()
{
	$login_page = home_url('/login');
	$page_viewed = basename($_SERVER['REQUEST_URI']);

	if ($page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
		wp_redirect($login_page);
		exit;
	}
}
add_action('init', 'redirect_login_page');

/*
 * Update login logo
 */
function custom_login_logo()
{
	// Get the plugin directory URL
	$plugin_dir_url = plugin_dir_url(__FILE__);
?>
	<style type="text/css">
		#login h1 a,
		.login h1 a {
			background-image: url('<?php echo esc_url($plugin_dir_url . 'logo_ology_orange.png'); ?>');
			width: 320px;
			background-size: contain;
			background-repeat: no-repeat;
			padding-bottom: 0px;
		}
	</style>
<?php
}
add_action('login_enqueue_scripts', 'custom_login_logo');

function custom_login_logo_url()
{
	return home_url(); // Change this if you want to link to a different page
}
add_filter('login_headerurl', 'custom_login_logo_url');

function custom_login_logo_url_title()
{
	return get_bloginfo('name'); // Change this to the desired title
}
add_filter('login_headertext', 'custom_login_logo_url_title');

/**
 * Redirect production staff to the production page after login
 */
function production_staff_login_redirect($redirect_to, $request, $user)
{
	// Check if the user has the 'production-staff' role
	if (isset($user->roles) && is_array($user->roles) && in_array('um_production-staff', $user->roles)) {
		// Redirect to the desired URL
		return home_url('/production');
	}

	// Return the default redirect URL if not 'production-staff'
	return $redirect_to;
}
add_filter('login_redirect', 'production_staff_login_redirect', 10, 3);

/**
 * Redirect Bronwyn to the WPForms dashboard after login
 */
function bronwyn_login_redirect($redirect_to, $request, $user)
{

	// Check if the user has the 'bronwyn' role
	if (isset($user->roles) && is_array($user->roles) && in_array('bronwyn', $user->roles)) {
		// Redirect to the desired URL
		return admin_url('admin.php?page=wpforms-overview');
	}
}

function redirect_to_login_if_not_logged_in()
{
	// Check if the user is on the 'production' page and not logged in
	if (is_page('production') && !is_user_logged_in()) {
		// Redirect to the login page
		wp_redirect(wp_login_url(get_permalink()));
		exit();
	}
}
add_action('template_redirect', 'redirect_to_login_if_not_logged_in');

// Add top-level menu for Ology
add_action('admin_menu', 'ology_custom_admin_menu');

function ology_custom_admin_menu()
{
	add_menu_page(
		'Ology',
		'Ology',
		'manage_options',
		'ology-custom',
		'ology_custom_main_page',
		'dashicons-store',
		30
	);

	add_submenu_page(
		'ology-custom',
		'Toast',
		'Toast',
		'manage_options',
		'ology-toast',
		'ology_toast_admin_page'
	);
}

// Main page for Ology (can be empty or contain general information)
function ology_custom_main_page()
{
?>
	<div class="wrap">
		<h1>Ology</h1>
		<p>Welcome to the Ology plugin. Use the submenus to access different features.</p>
	</div>
<?php
}

/**
 * Get attachment ID from URL - custom implementation to avoid conflict with WP core
 */
function ology_attachment_url_to_postid($url) {
    // First try the WordPress core function if it exists
    if (function_exists('attachment_url_to_postid')) {
        $attachment_id = attachment_url_to_postid($url);
        if ($attachment_id) {
            return $attachment_id;
        }
    }
    
    // Fallback to custom implementation if core function fails or doesn't exist
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url));
    return isset($attachment[0]) ? $attachment[0] : 0;
}

// Add custom column to menu_ology post type
add_filter('manage_menu_ology_posts_columns', 'add_scheduled_images_column');
function add_scheduled_images_column($columns) {
    $columns['scheduled_images'] = __('Scheduled Images', 'ology-custom');
    return $columns;
}

// Populate the custom column
add_action('manage_menu_ology_posts_custom_column', 'populate_scheduled_images_column', 10, 2);
function populate_scheduled_images_column($column, $post_id) {
    if ($column === 'scheduled_images') {
        $scheduled_images = get_post_meta($post_id, 'ology_scheduled_images', true);
        if (!empty($scheduled_images) && is_array($scheduled_images)) {
            $active_count = 0;
            $current_time = current_time('timestamp');
            
            foreach ($scheduled_images as $image) {
                $start_date = !empty($image['start_date']) ? intval($image['start_date']) : 0;
                $end_date = !empty($image['end_date']) ? intval($image['end_date']) : PHP_INT_MAX;
                
                if ($current_time >= $start_date && $current_time <= $end_date) {
                    $active_count++;
                }
            }
            
            echo sprintf(
                __('%d active of %d total', 'ology-custom'),
                $active_count,
                count($scheduled_images)
            );
        } else {
            echo __('None', 'ology-custom');
        }
    }
}
