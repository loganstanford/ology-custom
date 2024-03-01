<?php
namespace Elementor;

function ology_trainer_elements_elementor_init(){
    Plugin::instance()->elements_manager->add_category(
        'ology-addons-cat',
        [
            'title'  => 'Ology Addons',
            'icon' => 'font'
        ]
    );
}
add_action('elementor/init','Elementor\ology_trainer_elements_elementor_init');


//Query Categories List
function ology_elements_post_type_categories(){
	//https://developer.wordpress.org/reference/functions/get_terms/
	$terms = get_terms( array( 
		'taxonomy' => 'spirit-category',
		'hide_empty' => true,
	));
	
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
	foreach ( $terms as $term ) {
		$options[ $term->slug ] = $term->name;
	}
	return $options;
	}
}

//Query Locations List
function ology_elements_post_type_locations(){
	//https://developer.wordpress.org/reference/functions/get_terms/
	$terms = get_terms( array( 
		'taxonomy' => 'locations',
		'hide_empty' => true,
	));
	
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
	foreach ( $terms as $term ) {
		$options[ $term->slug ] = $term->name;
	}
	return $options;
	}
}