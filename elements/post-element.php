<?php

namespace Elementor;

if (!defined('ABSPATH'))
	exit; // If this file is called directly, abort.


class Widget_OlogySpiritsPostList extends Widget_Base
{


	public function get_name()
	{
		return 'ology-blog-post-list';
	}

	public function get_title()
	{
		return esc_html__('Spirit List - Ology', 'progression-elements-ontap');
	}

	public function get_icon()
	{
		return 'eicon-post-list progression-studios-ontap-pe';
	}

	public function get_categories()
	{
		return ['ology-addons-cat'];
	}

	public function get_script_depends()
	{
		return ['boosted_elements_progression_masonry_js'];
	}

	function Widget_OlogySpiritsPostList($widget_instance) {}

	protected function register_controls()
	{


		$this->start_controls_section(
			'section_title_global_options',
			[
				'label' => esc_html__('Post Settings', 'progression-elements-ontap')
			]
		);


		$this->add_control(
			'ology_main_post_count',
			[
				'label' => esc_html__('Post Count', 'progression-elements-ontap'),
				'type' => Controls_Manager::NUMBER,
				'default' => '20',
			]
		);

		$this->add_responsive_control(
			'ology_elements_image_grid_column_count',
			[
				'label' => esc_html__('Columns', 'progression-elements-ontap'),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'desktop_default' => '33.330%',
				'tablet_default' => '50%',
				'mobile_default' => '100%',
				'options' => [
					'100%' => esc_html__('1 Column', 'progression-elements-ontap'),
					'50%' => esc_html__('2 Column', 'progression-elements-ontap'),
					'33.330%' => esc_html__('3 Columns', 'progression-elements-ontap'),
					'25%' => esc_html__('4 Columns', 'progression-elements-ontap'),
					'20%' => esc_html__('5 Columns', 'progression-elements-ontap'),
					'16.67%' => esc_html__('6 Columns', 'progression-elements-ontap'),
				],
				'selectors' => [
					'{{WRAPPER}} .progression-masonry-item' => 'width: {{VALUE}};',
				],
				'render_type' => 'template'
			]
		);


		$this->add_responsive_control(
			'ology_elements_image_grid_margin',
			[
				'label' => esc_html__('Margin', 'progression-elements-ontap'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 120,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .progression-masonry-margins' => 'margin-left:-{{SIZE}}px; margin-right:-{{SIZE}}px;',
					'{{WRAPPER}} .progression-masonry-padding-blog' => 'padding: {{SIZE}}px;',
				],
				'render_type' => 'template'
			]
		);




		$this->add_control(
			'boosted_post_list_masonry',
			[
				'label' => esc_html__('Masonry Layout', 'progression-elements-progression'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);


		$this->add_control(
			'ology_elements_post_list_pagination',
			[
				'label' => esc_html__('Post Pagination', 'progression-elements-ontap'),
				'type' => Controls_Manager::SELECT,
				'default' => 'no-pagination',
				'options' => [
					'no-pagination' => esc_html__('No Pagination', 'progression-elements-ontap'),
					'default' => esc_html__('Default Pagination', 'progression-elements-ontap'),
					'load-more' => esc_html__('Load More Posts', 'progression-elements-ontap'),
					'infinite-scroll' => esc_html__('Inifinite Scroll', 'progression-elements-ontap'),
				],
			]
		);


		$this->add_control(
			'ology_main_post_load_more',
			[
				'label' => esc_html__('Load More Text', 'progression-elements-ontap'),
				'type' => Controls_Manager::TEXT,
				'default' => 'Load More',
				'condition' => [
					'progression_elements_post_list_pagination' => 'load-more',
				],
			]
		);




		$this->end_controls_section();


		$this->start_controls_section(
			'section_title_elements_options',
			[
				'label' => esc_html__('Post Elements', 'progression-elements-ontap')
			]
		);

		$this->add_control(
			'Spirits_elements_image',
			[
				'label' => esc_html__('Display Image', 'progression-elements-progression'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'Spirits_elements_style',
			[
				'label' => esc_html__('Display Style', 'progression-elements-progression'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'Spirits_elements_features',
			[
				'label' => esc_html__('Display Features', 'progression-elements-progression'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'Spirits_elements_excerpt',
			[
				'label' => esc_html__('Display Excerpt', 'progression-elements-progression'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);



		$this->end_controls_section();



		$this->start_controls_section(
			'section_title_secondary_options',
			[
				'label' => esc_html__('Post Query', 'progression-elements-ontap')
			]
		);



		$this->add_control(
			'ology_post_cats',
			[
				'label' => esc_html__('Narrow by Category', 'progression-elements-ontap'),
				'description' => esc_html__('Choose a category to display posts', 'progression-elements-ontap'),
				'label_block' => true,
				'multiple' => true,
				'type' => Controls_Manager::SELECT2,
				'options' => ology_elements_post_type_categories(),
			]
		);

		$this->add_control(
			'ology_elements_post_order_sorting',
			[
				'label' => esc_html__('Order By', 'progression-elements-ontap'),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__('Default - Date', 'progression-elements-ontap'),
					'title' => esc_html__('Post Title', 'progression-elements-ontap'),
					'menu_order' => esc_html__('Menu Order', 'progression-elements-ontap'),
					'modified' => esc_html__('Last Modified', 'progression-elements-ontap'),
					'rand' => esc_html__('Random', 'progression-elements-ontap'),
					'meta_value_num' => esc_html__('Custom', 'progression-elements-ontap'),
				],
			]
		);


		$this->add_control(
			'ology_elements_post_order',
			[
				'label' => esc_html__('Order', 'progression-elements-ontap'),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC' => esc_html__('Ascending', 'progression-elements-ontap'),
					'DESC' => esc_html__('Descending', 'progression-elements-ontap'),
				],
			]
		);

		$this->add_control(
			'ology_main_offset_count',
			[
				'label' => esc_html__('Offset Count', 'progression-elements-ontap'),
				'type' => Controls_Manager::NUMBER,
				'default' => '0',
				'description' => esc_html__('Use this to skip over posts (Example: 3 would skip the first 3 posts.)', 'progression-elements-ontap'),
			]
		);

		$this->add_control(
			'ology_elements_post_sorting',
			[
				'label' => esc_html__('Category Sorting', 'progression-elements-ontap'),
				'description' => esc_html__('Sort by Post Categories', 'progression-elements-ontap'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'ology_elements_post_filtering_text',
			[
				'label' => esc_html__('Sorting Text for "All Posts"', 'progression-elements-ontap'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__('All', 'progression-elements-ontap'),
				'conditions' => [
					'terms' => [
						[
							'name' => 'ology_elements_post_sorting',
							'operator' => '==',
							'value' => 'yes',
						],
					],
				],
			]
		);


		$this->end_controls_section();




		$this->start_controls_section(
			'ology_elements_section_main_styles',
			[
				'label' => esc_html__('Default Styles', 'progression-elements-ontap'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'ology_elements_heading_title',
			[
				'label' => esc_html__('Title', 'progression-elements-ontap'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'progression_elements_traditional_title_typography',
				'label' => esc_html__('Typography', 'progression-elements-ontap'),
				'selector' => '{{WRAPPER}} h2.progression-beers-title',
			]
		);

		$this->add_control(
			'ology_elements_traditional_title_color',
			[
				'label' => esc_html__('Title Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} h2.progression-beers-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ology_elements_traditional_title_color_hover',
			[
				'label' => esc_html__('Title Hover Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} h2.progression-beers-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'ology_elements_title_margin',
			[
				'label' => esc_html__('Title Margin', 'progression-elements-ontap'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} h2.progression-beers-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'ology_elements_heading_content',
			[
				'label' => esc_html__('Excerpt', 'progression-elements-ontap'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ology_elements_traditional_content_typography',
				'label' => esc_html__('Typography', 'progression-elements-ontap'),
				'selector' => '{{WRAPPER}} .progression-studios-beers-excerpt',
			]
		);

		$this->add_responsive_control(
			'ology_elements_content_margin',
			[
				'label' => esc_html__('Excerpt Margins', 'progression-elements-ontap'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .progression-studios-beers-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'ology_elements_traditional_border_color',
			[
				'label' => esc_html__('Container Border', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .progression-studios-beers-index' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ology_elements_traditional_content_color',
			[
				'label' => esc_html__('Content Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .progression-studios-beers-excerpt' => 'color: {{VALUE}}',
				],
			]
		);



		$this->add_control(
			'ology_elements_heading_meta',
			[
				'label' => esc_html__('Button', 'progression-elements-ontap'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ology_elements_traditional_meta_typography',
				'label' => esc_html__('Typography', 'progression-elements-ontap'),
				'selector' => '{{WRAPPER}} a.progression-beer-button',
			]
		);

		$this->add_responsive_control(
			'ology_elements_post_meta_margin',
			[
				'label' => esc_html__('Button Padding', 'progression-elements-ontap'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} a.progression-beer-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ology_elements_traditional_meta_color',
			[
				'label' => esc_html__('Button Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.progression-beer-button' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'ology_elements_traditional_meta_color_hover',
			[
				'label' => esc_html__('Button Hover Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.progression-beer-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ology_elements_traditional_meta_background',
			[
				'label' => esc_html__('Button Background', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.progression-beer-button' => 'background: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'ology_elements_traditional_metabutton_background_hover',
			[
				'label' => esc_html__('Button Background Hover', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.progression-beer-button:hover' => 'background: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();







		$this->start_controls_section(
			'section_styles_load_more_styles',
			[
				'label' => esc_html__('Load More Styles', 'progression-elements-ontap'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'section_styles_load_more_icon_indent',
			[
				'label' => esc_html__('Icon Spacing', 'ology-elements-ontap'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .infinite-nav-pro a span i' => 'margin-left: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'ology_elements_load_more_margin',
			[
				'label' => esc_html__('Margin', 'progression-elements-ontap'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .infinite-nav-pro a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'ology_elements_load_more_padding',
			[
				'label' => esc_html__('Padding', 'progression-elements-ontap'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .infinite-nav-pro a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ology_elements_load_moretypography',
				'label' => esc_html__('Typography', 'progression-elements-ontap'),
				'selector' => '{{WRAPPER}} .infinite-nav-pro a',
			]
		);




		$this->start_controls_tabs('boosted_elements_button_tabs');

		$this->start_controls_tab('normal', ['label' => esc_html__('Normal', 'progression-elements-ontap')]);

		$this->add_control(
			'boosted_elements_button_text_color',
			[
				'label' => esc_html__('Text Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .infinite-nav-pro a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'boosted_elements_button_background_color',
			[
				'label' => esc_html__('Background Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .infinite-nav-pro a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'boosted_elements_button_border_color',
			[
				'label' => esc_html__('Border Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .infinite-nav-pro a' => 'border-color: {{VALUE}};',
				],
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab('boosted_elements_hover', ['label' => esc_html__('Hover', 'progression-elements-ontap')]);

		$this->add_control(
			'boosted_elements_button_hover_text_color',
			[
				'label' => esc_html__('Text Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .infinite-nav-pro a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'boosted_elements_button_hover_background_color',
			[
				'label' => esc_html__('Background Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .infinite-nav-pro a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'boosted_elements_button_hover_border_color',
			[
				'label' => esc_html__('Border Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .infinite-nav-pro a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();



		$this->start_controls_section(
			'section_styles_filter_styles',
			[
				'label' => esc_html__('Filtering Styles', 'progression-elements-ontap'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_responsive_control(
			'boosted_elements_filtering_align',
			[
				'label' => esc_html__('Filtering Alignment', 'progression-elements-ontap'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'progression-elements-ontap'),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'progression-elements-ontap'),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'progression-elements-ontap'),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} ul.progression-filter-button-group' => 'text-align: {{VALUE}}',
				],
			]
		);



		$this->add_control(
			'ology_elements_filter_font_color',
			[
				'label' => esc_html__('Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.progression-filter-button-group li' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ology_elements_filter_font_selected_color',
			[
				'label' => esc_html__('Selected Color', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.progression-filter-button-group li.pro-checked' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ology_elements_filter_font_selected_background',
			[
				'label' => esc_html__('Selected Background', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.progression-filter-button-group li.pro-checked' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ology_elements_filter_border_color',
			[
				'label' => esc_html__('Default Border', 'progression-elements-ontap'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.progression-filter-button-group li:after' => 'background: {{VALUE}}',
				],
			]
		);




		$this->add_responsive_control(
			'ology_elements_fliltering_padding',
			[
				'label' => esc_html__('Padding', 'progression-elements-ontap'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} ul.progression-filter-button-group li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ology_elements_fliltering_margin',
			[
				'label' => esc_html__('Margin', 'progression-elements-ontap'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} ul.progression-filter-button-group li' => 'Margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ology_elements_filtering_typography',
				'label' => esc_html__('Typography', 'progression-elements-ontap'),
				'selector' => '{{WRAPPER}} ul.progression-filter-button-group li',
			]
		);

		$this->end_controls_section();
	}


	protected function render()
	{


		$settings = $this->get_settings();

		global $blogloop;
		global $post;

		if (get_query_var('paged')) {
			$paged = get_query_var('paged');
		} else if (get_query_var('page')) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}


		$post_per_page = $settings['ology_main_post_count'];
		$offset = $settings['ology_main_offset_count'];
		$offset_new = $offset + (($paged - 1) * $post_per_page);



		if (!empty($settings['ology_post_cats'])) {
			$formatarray = $settings['ology_post_cats']; // get custom field value

			$catarray = $settings['ology_post_cats']; // get custom field value
			if ($catarray >= 1) {
				$catids = implode(', ', $catarray);
			} else {
				$catids = '';
			}

			if ($formatarray >= 1) {
				$formatids = implode(', ', $formatarray);
				$formatidsexpand = explode(', ', $formatids);
				$formatoperator = 'IN';
			} else {
				$formatidsexpand = '';
				$formatoperator = 'NOT IN';
			}
			$operator = 'IN';
		} else {

			$formatidsexpand = '';
			$operator = 'NOT IN';
		}



		$args = array(
			'post_type' => 'spirit_ology',
			'orderby' => $settings['ology_elements_post_order_sorting'],
			'order' => $settings['ology_elements_post_order'],
			'ignore_sticky_posts' => 1,
			'posts_per_page' => $post_per_page,
			'paged' => $paged,
			'offset' => $offset_new,
			'tax_query' => array(
				array(
					'taxonomy' => 'spirit-category',
					'field' => 'slug',
					'terms' => $formatidsexpand,
					'operator' => $operator
				)
			),
		);

		$blogloop = new \WP_Query($args);
?>



		<?php if ($settings['ology_elements_post_sorting'] == 'yes'): ?>
			<div class="progression-filter-button-break-wide">
				<ul class="progression-filter-button-group ology-filter-group-<?php echo esc_attr($this->get_id()); ?>">
					<?php if ($settings['ology_post_cats']): ?>
						<?php
						$i = 0;

						$postIds = $catids; // get custom field value
						$arrayIds = explode(',', $postIds); // explode value into an array of ids
						if (count($arrayIds) <= 1) // if array contains one element or less, there's spaces after comma's, or you only entered one id
						{
							if (strpos($arrayIds[0], ',') !== false) // if the first array value has commas, there were spaces after ids entered
							{
								$arrayIds = array(); // reset array
								$arrayIds = explode(', ', $postIds); // explode ids with space after comma's
							}
						}

						$args_cats = array(
							'hide_empty' => '0',
							'slug' => $arrayIds,
						);


						print_r($args);
						$terms = get_terms('spirit-category', $args_cats);
						if (!empty($terms) && !is_wp_error($terms)) {
							echo '<li class="pro-checked" data-filter="*">' . $settings['ology_elements_post_filtering_text'] . '</li> ';

							foreach ($terms as $term) {
								if ($i == 0) {
									echo '<li data-filter=".' . $term->slug . '">' . $term->name . '</li>';
								} else if ($i > 0) {
									echo '<li data-filter=".' . $term->slug . '">' . $term->name . '</li>';
								}
								$i++;
							}
						}
						?>
					<?php else: ?>
						<?php
						$i = 0;
						$terms = get_terms('spirit-category', 'hide_empty=0');
						if (!empty($terms) && !is_wp_error($terms)) {
							echo '<li class="pro-checked" data-filter="*">' . $settings['ology_elements_post_filtering_text'] . '</li> ';

							foreach ($terms as $term) {
								if ($i == 0) {
									echo '<li data-filter=".' . $term->slug . '">' . $term->name . '</li>';
								} else if ($i > 0) {
									echo '<li data-filter=".' . $term->slug . '">' . $term->name . '</li>';
								}
								$i++;
							}
						}
						?>
					<?php endif ?>
				</ul>
				<div class="clearfix-pro"></div>
			</div>
		<?php endif ?>



		<div class="progression-studios-elementor-post-container ology-spirits-container">

			<div class="progression-masonry-margins">
				<div id="progression-beer-index-masonry-<?php echo esc_attr($this->get_id()); ?>">
					<?php while ($blogloop->have_posts()):
						$blogloop->the_post(); ?>

						<div class="progression-masonry-item ><?php $terms = get_the_terms($post->ID, 'spirit-category');
																if (!empty($terms)):
																	foreach ($terms as $term) {
																		$term_link = get_term_link($term, 'spirit-category');
																		if (is_wp_error($term_link))
																			continue;
																		echo " " . $term->slug;
																	}
																endif; ?>
				"><!-- .progression-masonry-item -->
							<div class="progression-masonry-padding-blog">
								<div class="progression-studios-isotope-animation">

									<?php include(locate_template('template-parts/elementor/content-spirits.php')); ?>

								</div><!-- close .progression-studios-isotope-animation -->
							</div><!-- close .progression-masonry-padding-blog -->
						</div><!-- close .progression-masonry-item -->
					<?php endwhile; // end of the loop.  
					?>
				</div><!-- close #progression-beer-index-masonry-<?php echo esc_attr($this->get_id()); ?>  -->
			</div><!-- close .progression-masonry-margins -->

			<div class="clearfix-pro"></div>

			<div class="ontap-progression-pagination-elementor">
				<?php if ($settings['ology_elements_post_list_pagination'] == 'default'): ?>
					<?php

					$page_tot = ceil(($blogloop->found_posts - $offset) / $post_per_page);

					if ($page_tot > 1) {
						$big = 999999999;
						echo paginate_links(
							array(
								'base' => str_replace($big, '%#%', get_pagenum_link(999999999, false)), // need an unlikely integer cause the url can contains a number
								'format' => '?paged=%#%',
								'current' => max(1, $paged),
								'total' => ceil(($blogloop->found_posts - $offset) / $post_per_page),
								'prev_next' => true,
								'prev_text' => esc_html__('&lsaquo; Previous', 'progression-elements-ontap'),
								'next_text' => esc_html__('Next &rsaquo;', 'progression-elements-ontap'),
								'end_size' => 1,
								'mid_size' => 2,
								'type' => 'list'
							)
						);
					}
					?>
				<?php endif; ?>

				<?php if ($settings['ology_elements_post_list_pagination'] == 'load-more'): ?>

					<?php $page_tot = ceil(($blogloop->found_posts - $offset) / $post_per_page);
					if ($page_tot > 1): ?>
						<div id="progression-load-more-manual">
							<div id="infinite-nav-pro-<?php echo esc_attr($this->get_id()); ?>" class="infinite-nav-pro">
								<div class="nav-previous">
									<?php next_posts_link($settings['ology_main_post_load_more']
										. '<span><i class="fas fa-chevron-circle-down"></i></span>', $blogloop->max_num_pages); ?>
								</div>
							</div>
						</div>
					<?php endif ?>
				<?php endif; ?>

				<?php if ($settings['ology_elements_post_list_pagination'] == 'infinite-scroll'): ?>
					<?php $page_tot = ceil(($blogloop->found_posts - $offset) / $post_per_page);
					if ($page_tot > 1): ?>
						<div id="infinite-nav-pro-<?php echo esc_attr($this->get_id()); ?>" class="infinite-nav-pro">
							<div class="nav-previous">
								<?php next_posts_link('Next', $blogloop->max_num_pages); ?>
							</div>
						</div>
					<?php endif ?>
				<?php endif; ?>

			</div>

		</div><!-- close .progression-studios-elementor-post-container -->

		<div class="clearfix-pro"></div>


		<script type="text/javascript">
			jQuery(document).ready(function($) {
				'use strict';

				/* Default Isotope Load Code */
				var $container<?php echo esc_attr($this->get_id()); ?> = $("#progression-beer-index-masonry-<?php echo esc_attr($this->get_id()); ?>").isotope();
				$container<?php echo esc_attr($this->get_id()); ?>.imagesLoaded(function() {
					$(".progression-masonry-item").addClass("opacity-progression");
					$container<?php echo esc_attr($this->get_id()); ?>.isotope({
						itemSelector: "#progression-beer-index-masonry-<?php echo esc_attr($this->get_id()); ?> .progression-masonry-item",
						percentPosition: true,
						layoutMode: <?php if (!empty($settings['boosted_post_list_masonry'])): ?> "masonry"
					<?php else: ?> "fitRows"
					<?php endif; ?>
					});
				});
				/* END Default Isotope Code */


				<?php if ($settings['ology_elements_post_sorting'] == 'yes'): ?>
					$('.progression-filter-group-<?php echo esc_attr($this->get_id()); ?>').on('click', 'li', function() {
						var filterValue = $(this).attr('data-filter');
						$container<?php echo esc_attr($this->get_id()); ?>.isotope({
							filter: filterValue
						});
					});

					$('.progression-filter-group-<?php echo esc_attr($this->get_id()); ?>').each(function(i, buttonGroup) {
						var $buttonGroup = $(buttonGroup);
						$buttonGroup.on('click', 'li', function() {
							$buttonGroup.find('.pro-checked').removeClass('pro-checked');
							$(this).addClass('pro-checked');
						});
					});
				<?php endif ?>



				<?php if ($settings['ology_elements_post_list_pagination'] == 'infinite-scroll' || $settings['ology_elements_post_list_pagination'] == 'load-more'): ?>

					/* Begin Infinite Scroll */
					$container<?php echo esc_attr($this->get_id()); ?>.infinitescroll({
							errorCallback: function() {
								$("#infinite-nav-pro-<?php echo esc_attr($this->get_id()); ?>").delay(500).fadeOut(500, function() {
									$(this).remove();
								});
							},
							navSelector: "#infinite-nav-pro-<?php echo esc_attr($this->get_id()); ?>",
							nextSelector: "#infinite-nav-pro-<?php echo esc_attr($this->get_id()); ?> .nav-previous a",
							itemSelector: "#progression-beer-index-masonry-<?php echo esc_attr($this->get_id()); ?> .progression-masonry-item",
							loading: {
								img: "<?php echo esc_url(get_template_directory_uri()); ?>/images/loader.gif",
								msgText: "",
								finishedMsg: "<div id='no-more-posts'></div>",
								speed: 0,
							}
						},
						// trigger Isotope as a callback
						function(newElements) {

							var $newElems = $(newElements);

							$newElems.imagesLoaded(function() {

								$container<?php echo esc_attr($this->get_id()); ?>.isotope("appended", $newElems);
								$(".progression-masonry-item").addClass("opacity-ology");

							});

						}
					);
					/* END Infinite Scroll */
				<?php endif; ?>


				<?php if ($settings['ology_elements_post_list_pagination'] == 'load-more'): ?>
					/* PAUSE FOR LOAD MORE */
					$(window).unbind(".infscr");
					// Resume Infinite Scroll
					$("#infinite-nav-pro-<?php echo esc_attr($this->get_id()); ?> .nav-previous a").click(function() {
						$container<?php echo esc_attr($this->get_id()); ?>.infinitescroll("retrieve");
						return false;
					});
					/* End Infinite Scroll */
				<?php endif; ?>

			});
		</script>



		<?php wp_reset_postdata(); ?>


<?php

	}

	protected function content_template() {}
}


Plugin::instance()->widgets_manager->register_widget_type(new Widget_OlogySpiritsPostList());
