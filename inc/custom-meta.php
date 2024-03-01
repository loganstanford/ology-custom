<?php

// In another file somewhere
function ology_html_allow($original_value, $args, $cmb2_field)
{
	return $original_value; // Unsanitized value.
}

add_action('cmb2_admin_init', 'ology_beer_ontap_meta');
function ology_beer_ontap_meta()
{
	$prefix = 'ology_';

	$ology_beer_cmb = new_cmb2_box(
		array(
			'id' => $prefix . 'beer_details',
			'title' => esc_html__('Beer Details', 'progression-elements-ontap'),
			'object_types' => array('beer_ontap'), // Post type,

		)
	);

	$ology_beer_cmb->add_field(
		array(
			'name' => esc_html__('Untappd ID', 'progression-elements-ontap'),
			'id' => $prefix . 'untappd_id',
			'type' => 'text',
		)
	);

	$ology_beer_cmb->add_field(
		array(
			'name' => esc_html__('Flavor/Hops', 'progression-elements-ontap'),
			'id' => $prefix . 'hops',
			'type' => 'text',
		)
	);

	$ology_beer_cmb->add_field(
		array(
			'name' => esc_html__('ABV', 'progression-elements-ontap'),
			'id' => $prefix . 'abv',
			'type' => 'text',
		)
	);

	$ology_beer_cmb->add_field(
		array(
			'name' => esc_html__('Custom Sort Order', 'progression-elements-ontap'),
			'desc' => esc_html__('Where to display when custom sort order is selected. Must be a number.', 'progression-elements-ontap'),
			'id' => $prefix . 'custom_order',
			'type' => 'text',
		)
	);

	$locations = getOlogyLocations();
	$containers = getOlogyContainers();

	foreach ($locations as $loc_key => $loc_value) {
		$ology_loc_cmb = new_cmb2_box(
			array(
				'id' => $prefix . 'beer_location_details-' . $loc_key,
				'title' => esc_html__($loc_value, 'progression-elements-ontap'),
				'object_types' => array('beer_ontap'), // Post type,
			)
		);

		$ology_loc_cmb->add_field(
			array(
				'name' => 'Availability',
				'desc' => 'Container availability for ' . $loc_value,
				'type' => 'multicheck',
				'id' => $prefix . $loc_key . '_availability',
				'options' => getOlogyContainers()
			)
		);

		$ology_loc_cmb->add_field(
			array(
				'name' => 'Tap #',
				'desc' => 'Tap # where keg is tapped at ' . $loc_value,
				'type' => 'text_small',
				'id' => $prefix . $loc_key . '_tap-number'
			)
		);

		foreach ($containers as $con_key => $con_value) {
			$ology_loc_cmb->add_field(
				array(
					'name' => $con_value,
					'type' => 'text_money',
					'id' => $prefix . $loc_key . '_' . $con_key . '_price'
				)
			);
		}
		;
	}
	;
}

add_action('cmb2_admin_init', 'ology_page_meta_box');
function ology_page_meta_box()
{

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'ology_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$ology_cmb = new_cmb2_box(
		array(
			'id' => $prefix . 'metabox_page_settings',
			'title' => esc_html__('Page Settings', 'progression-elements-ontap'),
			'object_types' => array('page'), // Post type,
		)
	);


	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Disable header', 'progression-elements-ontap'),
			'id' => $prefix . 'disable_header',
			'type' => 'checkbox',
		)
	);

	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Disable footer', 'progression-elements-ontap'),
			'id' => $prefix . 'disable_footer',
			'type' => 'checkbox',
		)
	);

	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Disable Page Title', 'progression-elements-ontap'),
			'id' => $prefix . 'disable_page_title',
			'type' => 'checkbox',
		)
	);
}

add_action('cmb2_admin_init', 'ology_index_post_meta_box');
function ology_index_post_meta_box()
{

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'ology_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$ology_cmb = new_cmb2_box(
		array(
			'id' => $prefix . 'metabox_post',
			'title' => esc_html__('Post Settings', 'progression-elements-ontap'),
			'object_types' => array('post'), // Post type
		)
	);

	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Featured Image Link', 'progression-elements-ontap'),
			'id' => $prefix . 'blog_featured_image_link',
			'type' => 'select',
			'options' => array(
				'progression_link_default' => esc_html__('Link to post', 'progression-elements-ontap'), // {#} gets replaced by row number
				'progression_link_lightbox' => esc_html__('Link to image in lightbox pop-up', 'progression-elements-ontap'),
				'progression_link_url' => esc_html__('Link to URL', 'progression-elements-ontap'),
				'progression_link_url_new_window' => esc_html__('Link to URL (New Window)', 'progression-elements-ontap'),
			),

		)
	);


	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Optional Link', 'progression-elements-ontap'),
			'desc' => esc_html__('Make your post link to another page', 'progression-elements-ontap'),
			'id' => $prefix . 'external_link',
			'type' => 'text',
		)
	);


	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Video/Audio', 'progression-elements-ontap'),
			'desc' => esc_html__('Paste in your video url or embed code', 'progression-elements-ontap'),
			'id' => $prefix . 'video_post',
			'type' => 'textarea_code',
			'options' => array('disable_codemirror' => true)
		)
	);
}


add_action('cmb2_admin_init', 'ology_portfolio_meta_box');
function ology_portfolio_meta_box()
{

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'ology_spirit_';

	$ology_details_cmb = new_cmb2_box(
		array(
			'id' => $prefix . 'metabox_portfolio',
			'title' => esc_html__('Spirit Details', 'progression-elements-ontap'),
			'object_types' => array('spirit_ology'), // Post type
		)
	);

	$ology_details_cmb->add_field(
		array(
			'name' => esc_html__('ABV', 'progression-elements-ontap'),
			'desc' => esc_html__('Spirit ABV', 'progression-elements-ontap'),
			'id' => $prefix . 'abv',
			'type' => 'text',
		)
	);

	$ology_details_cmb->add_field(
		array(
			'name' => esc_html__('Proof', 'progression-elements-ontap'),
			'desc' => esc_html__('Spirit Proof', 'progression-elements-ontap'),
			'id' => $prefix . 'proof',
			'type' => 'text',
		)
	);

	$ology_cmb = new_cmb2_box(
		array(
			'id' => $prefix . 'metabox_portfolio',
			'title' => esc_html__('Post Settings', 'progression-elements-ontap'),
			'object_types' => array('spirit_ology'), // Post type
		)
	);

	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Featured Image/Button Link', 'progression-elements-ontap'),
			'id' => $prefix . 'blog_featured_image_link',
			'type' => 'select',
			'options' => array(
				'progression_link_none' => esc_html__('No Image Link', 'progression-elements-ontap'),
				'progression_link_default' => esc_html__('Default link to post', 'progression-elements-ontap'), // {#} gets replaced by row number
				'progression_link_url' => esc_html__('Link to URL', 'progression-elements-ontap'),
				'progression_link_url_new_window' => esc_html__('Link to URL (New Window)', 'progression-elements-ontap'),
			),

		)
	);

	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Custom Sort Order', 'progression-elements-ontap'),
			'desc' => esc_html__('Where to display when custom sort order is selected. Must be a number.', 'progression-elements-ontap'),
			'id' => $prefix . 'custom_order',
			'type' => 'text',
		)
	);

	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Optional Link', 'progression-elements-ontap'),
			'desc' => esc_html__('Make your post link to another page', 'progression-elements-ontap'),
			'id' => $prefix . 'external_link',
			'type' => 'text',
		)
	);


	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Button Text', 'progression-elements-ontap'),
			'id' => $prefix . 'button_text',
			'type' => 'text',
		)
	);
}

add_action('cmb2_admin_init', 'ology_cocktails_meta_box');
function ology_cocktails_meta_box()
{

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'ology_cocktail_';

	$ology_details_cmb = new_cmb2_box(
		array(
			'id' => $prefix . 'metabox_portfolio',
			'title' => esc_html__('Cocktail Details', 'progression-elements-ontap'),
			'object_types' => array('cocktail_ology'), // Post type
		)
	);

	$ology_cmb = new_cmb2_box(
		array(
			'id' => $prefix . 'metabox_portfolio',
			'title' => esc_html__('Post Settings', 'progression-elements-ontap'),
			'object_types' => array('cocktail_ology'), // Post type
		)
	);

	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Featured Image/Button Link', 'progression-elements-ontap'),
			'id' => $prefix . 'blog_featured_image_link',
			'type' => 'select',
			'options' => array(
				'progression_link_none' => esc_html__('No Image Link', 'progression-elements-ontap'),
				'progression_link_default' => esc_html__('Default link to post', 'progression-elements-ontap'), // {#} gets replaced by row number
				'progression_link_url' => esc_html__('Link to URL', 'progression-elements-ontap'),
				'progression_link_url_new_window' => esc_html__('Link to URL (New Window)', 'progression-elements-ontap'),
			),

		)
	);

	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Optional Link', 'progression-elements-ontap'),
			'desc' => esc_html__('Make your post link to another page', 'progression-elements-ontap'),
			'id' => $prefix . 'external_link',
			'type' => 'text',
		)
	);


	$ology_cmb->add_field(
		array(
			'name' => esc_html__('Button Text', 'progression-elements-ontap'),
			'id' => $prefix . 'button_text',
			'type' => 'text',
		)
	);
}


add_action('cmb2_admin_init', 'ology_portfolio_meta_box_repeat');
function ology_portfolio_meta_box_repeat()
{

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'ology_';

	$ology_cmb = new_cmb2_box(
		array(
			'id' => $prefix . 'metabox_portfolio_features',
			'title' => esc_html__('Beer Features', 'progression-elements-ontap'),
			'object_types' => array('spirit_ology', 'product'), // Post type
			'priority' => 'low',
		)
	);



	$group_field_id = $ology_cmb->add_field(
		array(
			'id' => $prefix . 'display_season',
			'type' => 'group',
			'options' => array(
				'group_title' => esc_html__('Feature {#}', 'progression-elements-vayvo'), // {#} gets replaced by row number
				'add_button' => esc_html__('Add Another Feature', 'progression-elements-vayvo'),
				'remove_button' => esc_html__('Remove Feature', 'progression-elements-vayvo'),
				'sortable' => true, // beta
				'closed' => true, // true to have the groups closed by default
			),

		)
	);


	/**
	 * Group fields works the same, except ids only need
	 * to be unique to the group. Prefix is not needed.
	 *
	 * The parent field's id needs to be passed as the first argument.
	 */


	$ology_cmb->add_group_field(
		$group_field_id,
		array(
			'name' => esc_html__('Feature Description', 'progression-elements-vayvo'),
			'id' => $prefix . 'description',
			'type' => 'textarea_small',
			'attributes' => array(
				'rows' => 3,
			),
			'sanitization_cb' => 'ology_html_allow', // function should return a sanitized value
		)
	);
}

add_action('cmb2_admin_init', 'ology_featured_item_meta_box');
function ology_featured_item_meta_box()
{
	$prefix = 'ology_';

	$locations = getOlogyLocations();

	$ology_details_cmb = new_cmb2_box(
		array(
			'id' => $prefix . 'metabox_portfolio',
			'title' => esc_html__('Item Details', 'progression-elements-ontap'),
			'object_types' => array('food_ology', 'coffee_ology', 'beers_ontap'), // Post type
		)
	);

	$ology_details_cmb->add_field(
		array(
			'name' => 'Description',
			'type' => 'textarea',
			'id' => $prefix . '_description'
		)
	);

	foreach ($locations as $loc_key => $loc_value) {
		$ology_loc_cmb = new_cmb2_box(
			array(
				'id' => $prefix . 'featuredLocation_details-' . $loc_key,
				'title' => esc_html__($loc_value, 'progression-elements-ontap'),
				'object_types' => array('food_ology', 'coffee_ology'), // Post type,
			)
		);

		$ology_loc_cmb->add_field(
			array(
				'name' => 'Price',
				'type' => 'text_money',
				'id' => $prefix . $loc_key . '_price'
			)
		);

		$ology_loc_cmb->add_field(
			array(
				'name' => 'Featured',
				'type' => 'checkbox',
				'id' => $prefix . $loc_key . '_isFeatured'
			)
		);


	}
}

add_action('rest_api_init', 'register_food_meta_rest');
function register_food_meta_rest()
{

	register_rest_field(
		array('food_ology', 'coffee_ology'),
		'locations',
		array(
			'get_callback' => 'get_item_featured_location_meta',
			'show_in_rest' => true
		)
	);

	register_rest_field(
		array('food_ology', 'coffee_ology'),
		'fimg_base64',
		array(
			'get_callback' => 'get_rest_featured_image',
			'show_in_rest' => true
		)
	);
}

function get_item_featured_location_meta($post, $field_name, $request)
{
	$locations = getOlogyLocations();

	$meta = array(
	);

	foreach ($locations as $loc_key => $loc_value) {
		$loc_price = get_post_meta($post['id'], 'ology_' . $loc_key . '_price');
		$loc_feat = get_post_meta($post['id'], 'ology_' . $loc_key . '_isFeatured');
		$meta += array(
			$loc_value => array(
				'Price' => $loc_price,
				'Featured' => $loc_feat
			)
		);
	}
	return $meta;
}

function get_rest_featured_image($object, $field_name, $request)
{
	// Get the featured image ID
	$featured_image_id = get_post_thumbnail_id($object['id']);

	// Get the full-sized image URL
	$image_data = wp_get_attachment_image_src($featured_image_id, 'full');

	if ($image_data) {
		$image_url = $image_data[0];

		// Fetch and encode the image as Base64
		$image_data = file_get_contents($image_url);
		$base64_image = base64_encode($image_data);

		return $base64_image;
	}

	return '';
}

add_action('cmb2_admin_init', 'register_file_list_metabox');
function register_file_list_metabox() {
    $prefix = 'ology_';

    $cmb = new_cmb2_box(array(
        'id'            => $prefix . 'file_list_metabox',
        'title'         => __('Filler images', 'cmb2'),
        'object_types'  => array('menu_ology'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ));

    $cmb->add_field(array(
        'name' => __('Images', 'cmb2'),
        'desc' => __('Upload or add multiple images/attachments.', 'cmb2'),
        'id'   => $prefix . 'file_list',
        'type' => 'file_list',
        'preview_size' => array(100, 100), // Default: array(50, 50)
        // Optional, override default CMB2 styles for repeatable rows
        'options' => array(
            'add_upload_files_text' => __('Add or Upload Files', 'cmb2'),
        ),
    ));
}

