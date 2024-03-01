<?php
function ology_beers_post_type_categories()
{
	$terms = get_terms(array(
		'taxonomy' => 'beer-category',
		'hide_empty' => true,
	));

	if (!empty($terms) && !is_wp_error($terms)) {
		foreach ($terms as $term) {
			$options[$term->slug] = $term->name;
		}
		return $options;
	}
}

function ology_beers_post_type_styles()
{
	$terms = get_terms(array(
		'taxonomy' => 'beer-style',
		'hide_empty' => true,
	));

	if (!empty($terms) && !is_wp_error($terms)) {
		foreach ($terms as $term) {
			$options[$term->slug] = $term->name;
		}
		return $options;
	}
}

// Cocktail functions
function ology_cocktails_post_type_categories()
{
	$terms = get_terms(array(
		'taxonomy' => 'cocktail-category',
		'hide_empty' => true,
	));

	if (!empty($terms) && !is_wp_error($terms)) {
		foreach ($terms as $term) {
			$options[$term->slug] = $term->name;
		}
		return $options;
	}
}

function ology_cocktails_post_type_ingredients()
{
	$terms = get_terms(array(
		'taxonomy' => 'cocktail-ingredient',
		'hide_empty' => true,
	));

	if (!empty($terms) && !is_wp_error($terms)) {
		foreach ($terms as $term) {
			$options[$term->slug] = $term->name;
		}
		return $options;
	}
}