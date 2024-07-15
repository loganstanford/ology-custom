<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

require_once 'widget-helper.php';
$prefix = "ology_beers_";

/**
 * Elementor Ology Beers Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Elementor_Ology_Beers_Widget extends \Elementor\Widget_Base
{

	/**
	 * Get widget name.
	 *
	 * Retrieve Ology Beers widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'Ology Beers';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Ology Beers widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return esc_html__('Ology Beers', 'elementor-oembed-widget');
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Ology Beers widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-post-list';
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url()
	{
		return 'https://developers.elementor.com/docs/widgets/';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Ology Beers widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return ['ology-addons-cat'];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the Ology Beers widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords()
	{
		return ['ology-beers', 'url', 'link'];
	}

	/**
	 * Register Ology Beers widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls()
	{
		$prefix = "ology_beers_";

		$this->start_controls_section(
			$prefix . 'content_section',
			[
				'label' => esc_html__('Post Settings', 'elementor-ology-beers-widget'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			$prefix . 'post_count',
			[
				'label' => esc_html__('Post Count', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 20,
			]
		);

		$this->add_responsive_control(
			$prefix . 'grid_column_count',
			[
				'label' => esc_html__('Columns', 'elementor-ology-beers-widget'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'desktop_default' => '33.330%',
				'tablet_default' => '50%',
				'mobile_default' => '100%',
				'options' => [
					'100%' => esc_html__('1 Column', 'elementor-ology-beers-widget'),
					'50%' => esc_html__('2 Column', 'elementor-ology-beers-widget'),
					'33.330%' => esc_html__('3 Columns', 'elementor-ology-beers-widget'),
					'25%' => esc_html__('4 Columns', 'elementor-ology-beers-widget'),
					'20%' => esc_html__('5 Columns', 'elementor-ology-beers-widget'),
					'16.67%' => esc_html__('6 Columns', 'elementor-ology-beers-widget'),
				],
				'selectors' => [
					'{{WRAPPER}} .progression-masonry-item' => 'width: {{VALUE}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			$prefix . 'grid_margin',
			[
				'label' => esc_html__('Margin', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::SLIDER,
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
			$prefix . 'boosted_post_list_masonry',
			[
				'label' => esc_html__('Masonry Layout', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);


		$this->add_control(
			$prefix . 'post_list_pagination',
			[
				'label' => esc_html__('Post Pagination', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'no-pagination',
				'options' => [
					'no-pagination' => esc_html__('No Pagination', 'elementor-ology-beers-widget'),
					'default' => esc_html__('Default Pagination', 'elementor-ology-beers-widget'),
					'load-more' => esc_html__('Load More Posts', 'elementor-ology-beers-widget'),
					'infinite-scroll' => esc_html__('Inifinite Scroll', 'elementor-ology-beers-widget'),
				],
			]
		);


		$this->add_control(
			$prefix . 'post_load_more',
			[
				'label' => esc_html__('Load More Text', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Load More',
				'condition' => [
					$prefix . 'post_list_pagination' => 'load-more',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			$prefix . 'section_title_options',
			[
				'label' => esc_html__('Post Elements', 'elementor-ology-beers-widget')
			]
		);

		$this->add_control(
			$prefix . 'image',
			[
				'label' => esc_html__('Display Image', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			$prefix . 'style',
			[
				'label' => esc_html__('Display Style', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			$prefix . 'features',
			[
				'label' => esc_html__('Display Features', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			$prefix . 'availability',
			[
				'label' => esc_html__('Display Availability', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			$prefix . 'excerpt',
			[
				'label' => esc_html__('Display Excerpt', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_secondary_options',
			[
				'label' => esc_html__('Post Query', 'elementor-ology-beers-widget')
			]
		);


		$this->add_control(
			$prefix . 'post_cats',
			[
				'label' => esc_html__('Narrow by Category', 'elementor-ology-beers-widget'),
				'description' => esc_html__('Choose a category to display beers', 'elementor-ology-beers-widget'),
				'label_block' => true,
				'multiple' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => ology_beers_post_type_categories(),
			]
		);

		$this->add_control(
			$prefix . 'post_styles',
			[
				'label' => esc_html__('Narrow by Style', 'elementor-ology-beers-widget'),
				'description' => esc_html__('Choose a style of beer to display', 'elementor-ology-beers-widget'),
				'label_block' => true,
				'multiple' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => ology_beers_post_type_styles(),
			]
		);
		$this->add_control(
			$prefix . 'post_locations',
			[
				'label' => esc_html__('Narrow by Location', 'elementor-ology-beers-widget'),
				'description' => esc_html__('Choose a location to display beers from', 'elementor-ology-beers-widget'),
				'label_block' => true,
				'multiple' => false,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => getOlogyLocations(),
			]
		);
		$this->add_control(
			$prefix . 'availability',
			[
				'label' => esc_html__('Narrow by Container Availability', 'elementor-ology-beers-widget'),
				'description' => esc_html__('Choose a container', 'elementor-ology-beers-widget'),
				'label_block' => true,
				'multiple' => false,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => array_combine(get_all_unique_containers(), get_all_unique_containers()),
			]
		);

		/*  $this->add_control(
			 $prefix . 'post_location',
			 [
				 'label' => esc_html__( 'Select Location', 'elementor-ology-beers-widget' ),
				 'description' => esc_html__( 'Choose a location to display beers from', 'elementor-ology-beers-widget' ),
				 'label_block' => true,
				 'multiple' => true,
				 'type' => \Elementor\Controls_Manager::SELECT,
				 'options' => getOlogyLocations(),
			 ]
		 ); */

		$this->add_control(
			$prefix . 'post_order_sorting',
			[
				'label' => esc_html__('Order By', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__('Default - Date', 'elementor-ology-beers-widget'),
					'title' => esc_html__('Post Title', 'elementor-ology-beers-widget'),
					'menu_order' => esc_html__('Menu Order', 'elementor-ology-beers-widget'),
					'modified' => esc_html__('Last Modified', 'elementor-ology-beers-widget'),
					'rand' => esc_html__('Random', 'elementor-ology-beers-widget'),
					'meta_value_num' => esc_html__('Custom Order', 'elementor-ology-beers-widget'),
				],
			]
		);


		$this->add_control(
			$prefix . 'post_order',
			[
				'label' => esc_html__('Order', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC' => esc_html__('Ascending', 'elementor-ology-beers-widget'),
					'DESC' => esc_html__('Descending', 'elementor-ology-beers-widget'),
				],
			]
		);

		$this->add_control(
			$prefix . 'offset_count',
			[
				'label' => esc_html__('Offset Count', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '0',
				'description' => esc_html__('Use this to skip over posts (Example: 3 would skip the first 3 posts.)', 'elementor-ology-beers-widget'),
			]
		);

		$this->add_control(
			$prefix . 'post_sorting',
			[
				'label' => esc_html__('Category Sorting', 'elementor-ology-beers-widget'),
				'description' => esc_html__('Sort by Post Categories', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			$prefix . 'post_filtering_text',
			[
				'label' => esc_html__('Sorting Text for "All Posts"', 'elementor-ology-beers-widget'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__('All', 'elementor-ology-beers-widget'),
				'conditions' => [
					'terms' => [
						[
							'name' => $prefix . 'post_sorting',
							'operator' => '==',
							'value' => 'yes',
						],
					],
				],
			]
		);


		$this->end_controls_section();
	}

	/**
	 * Render Ology Beers widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render()
	{
		$prefix = "ology_beers_";

		$settings = $this->get_settings_for_display();
		global $beersloop;
		global $post;

		if (get_query_var('paged')) {
			$paged = get_query_var('paged');
		} else if (get_query_var('page')) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}
		$posts_per_page = $settings[$prefix . 'post_count'];
		$offset = $settings[$prefix . 'offset_count'];
		$offset_new = $offset + (($paged - 1) * $posts_per_page);

		// Beers Categories
		if (!empty($settings[$prefix . 'post_cats'])) {
			$formatarray = $settings[$prefix . 'post_cats']; // get custom field value

			$catarray = $settings[$prefix . 'post_cats']; // get custom field value
			if ($catarray >= 1) {
				$catids = implode(', ', $catarray);
			} else {
				$catids = '';
			}

			if ($formatarray >= 1) {
				$formatids = implode(', ', $formatarray);
				$cat_formatidsexpand = explode(', ', $formatids);
				$formatoperator = 'IN';
			} else {
				$cat_formatidsexpand = '';
				$formatoperator = 'NOT IN';
			}
			$cat_operator = 'IN';
		} else {
			$cat_formatidsexpand = '';
			$cat_operator = 'NOT IN';
		}

		// Beers Styles
		if (!empty($settings[$prefix . 'post_styles'])) {
			$formatarray = $settings[$prefix . 'post_styles']; // get custom field value

			$stylearray = $settings[$prefix . 'post_styles']; // get custom field value
			if ($stylearray >= 1) {
				$styleids = implode(', ', $stylearray);
			} else {
				$styleids = '';
			}

			if ($formatarray >= 1) {
				$formatids = implode(', ', $formatarray);
				$style_formatidsexpand = explode(', ', $formatids);
				$formatoperator = 'IN';
			} else {
				$style_formatidsexpand = '';
				$formatoperator = 'NOT IN';
			}
			$style_operator = 'IN';
		} else {
			$style_formatidsexpand = '';
			$style_operator = 'NOT IN';
		}

		// Beers Locations
		if (!empty($settings[$prefix . 'post_locations'])) {
			$location_slug = $settings[$prefix . 'post_locations'];

			$location_operator = 'IN';
		} else {
			$location_slug = '';
			$locations_formatidsexpand = '';
			$location_operator = 'NOT IN';
		}

		if (!empty($settings[$prefix . 'availability'])) {
			$availability_meta = $settings[$prefix . 'availability'];

			$availability_operator = 'IN';
		} else {
			$availability_meta = '';
			$availability_formatidsexpand = '';
			$availability_operator = 'NOT IN';
		}

		$args = array(
			'post_type' => 'beer_ontap',
			'orderby' => array(
				'meta_value_num' => $settings[$prefix . 'post_order'],
				'date' => 'DESC'
			),
			'order' => $settings[$prefix . 'post_order'],
			'ignore_sticky_posts' => 1,
			'posts_per_page' => $posts_per_page,
			'paged' => $paged,
			'offset' => $offset_new,
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'ology_custom_order',
					'compare' => 'EXISTS'
				),
				array(
					'key' => 'ology_custom_order',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key' => 'ology_' . $location_slug . '_availability',
					'value' => '"' . $availability_meta . '"',
					'compare' => 'LIKE'
				)
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'beer-category',
					'field' => 'slug',
					'terms' => $cat_formatidsexpand,
					'operator' => $cat_operator
				),
				array(
					'taxonomy' => 'beer-style',
					'field' => 'slug',
					'terms' => $style_formatidsexpand,
					'operator' => $style_operator
				),
				array(
					'taxonomy' => 'ology-location',
					'field' => 'slug',
					'terms' => $location_slug,
					'operator' => $location_operator
				),
			),
		);


		$beersloop = new \WP_Query($args);

		if ($settings[$prefix . 'post_sorting'] == 'yes') : ?>
			<div class="progression-filter-button-break-wide">
				<ul class="progression-filter-button-group progression-filter-group-<?php echo esc_attr($this->get_id()); ?>">
					<?php if ($settings[$prefix . 'post_cats']) : ?>
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
							'hide_empty' => '1',
							'slug' => $arrayIds,
						);

						$terms = get_terms('beer-style', $args_cats);
						if (!empty($terms) && !is_wp_error($terms)) {
							echo '<li class="pro-checked" data-filter="*">' . $settings[$prefix . 'post_filtering_text'] . '</li> ';

							foreach ($terms as $term) {
								if ($term->parent != 0) {
									continue;
								}
								if ($i == 0) {
									echo '<li data-filter=".' . $term->slug . '">' . $term->name . '</li>';
								} else if ($i > 0) {
									echo '<li data-filter=".' . $term->slug . '">' . $term->name . '</li>';
								}
								$i++;
							}
						}
						?>
					<?php else : ?>
						<?php
						$i = 0;
						$terms = get_terms('beer-style', 'hide_empty=1');
						if (!empty($terms) && !is_wp_error($terms)) {
							echo '<li class="pro-checked" data-filter="*">' . $settings[$prefix . 'post_filtering_text'] . '</li> ';

							foreach ($terms as $term) {


								if ($term->parent != 0) {
									continue;
								}


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

		<div class="progression-studios-elementor-post-container">
			<div class="progression-masonry-margins">
				<div id="progression-beer-index-masonry-<?php echo esc_attr($this->get_id()); ?>">
					<?php while ($beersloop->have_posts()) :
						$beersloop->the_post(); ?>

						<div class="progression-masonry-item ><?php $terms = get_the_terms($post->ID, 'beer-style');
																if (!empty($terms)) :
																	foreach ($terms as $term) {
																		$term_link = get_term_link($term, 'beer-style');
																		if (is_wp_error($term_link))
																			continue;
																		echo " " . $term->slug;
																	}
																endif; ?>
				">
							<!-- .progression-masonry-item -->
							<div class="progression-masonry-padding-blog">
								<div class="progression-studios-isotope-animation">

									<?php
									$args_template = array(
										'location' => $location_slug,
										'prefix' => $prefix
									);
									include(locate_template('template-parts/elementor/content-beers-ology.php', false, true, $args_template)); ?>

								</div><!-- close .progression-studios-isotope-animation -->
							</div><!-- close .progression-masonry-padding-blog -->
						</div><!-- close .progression-masonry-item -->
					<?php endwhile; // end of the loop. 
					?>
				</div><!-- close #progression-beer-index-masonry-<?php echo esc_attr($this->get_id()); ?>  -->
			</div><!-- close .progression-masonry-margins -->

			<div class="clearfix-pro"></div>
			<div class="ontap-progression-pagination-elementor">
				<?php if ($settings[$prefix . 'post_list_pagination'] == 'default') : ?>
					<?php

					$page_tot = ceil(($beersloop->found_posts - $offset) / $post_per_page);

					if ($page_tot > 1) {
						$big = 999999999;
						echo paginate_links(
							array(
								'base' => str_replace($big, '%#%', get_pagenum_link(999999999, false)), // need an unlikely integer cause the url can contains a number
								'format' => '?paged=%#%',
								'current' => max(1, $paged),
								'total' => ceil(($beersloop->found_posts - $offset) / $post_per_page),
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

				<?php if ($settings[$prefix . 'post_list_pagination'] == 'load-more') : ?>

					<?php $page_tot = ceil(($beersloop->found_posts - $offset) / $post_per_page);
					if ($page_tot > 1) : ?>
						<div id="progression-load-more-manual">
							<div id="infinite-nav-pro-<?php echo esc_attr($this->get_id()); ?>" class="infinite-nav-pro">
								<div class="nav-previous">
									<?php next_posts_link($settings[$prefix . 'post_load_more']
										. '<span><i class="fas fa-chevron-circle-down"></i></span>', $beersloop->max_num_pages); ?>
								</div>
							</div>
						</div>
					<?php endif ?>
				<?php endif; ?>

				<?php if ($settings[$prefix . 'post_list_pagination'] == 'infinite-scroll') : ?>
					<?php $page_tot = ceil(($beersloop->found_posts - $offset) / $post_per_page);
					if ($page_tot > 1) : ?>
						<div id="infinite-nav-pro-<?php echo esc_attr($this->get_id()); ?>" class="infinite-nav-pro">
							<div class="nav-previous">
								<?php next_posts_link('Next', $beersloop->max_num_pages); ?>
							</div>
						</div>
					<?php endif ?>
				<?php endif; ?>

			</div>

		</div><!-- close .progression-studios-elementor-post-container -->

		<div class="clearfix-pro"></div>
		<script type="text/javascript" src=""></script>

		<script type="text/javascript">
			jQuery(document).ready(function($) {
				'use strict';

				/* Default Isotope Load Code */
				var $container<?php echo esc_attr($this->get_id()); ?> = $(
					"#progression-beer-index-masonry-<?php echo esc_attr($this->get_id()); ?>").isotope();
				$container<?php echo esc_attr($this->get_id()); ?>.imagesLoaded(function() {
					$(".progression-masonry-item").addClass("opacity-progression");
					$container<?php echo esc_attr($this->get_id()); ?>.isotope({
						itemSelector: "#progression-beer-index-masonry-<?php echo esc_attr($this->get_id()); ?> .progression-masonry-item",
						percentPosition: true,
						layoutMode: <?php if (!empty($settings[$prefix . 'boosted_post_list_masonry'])) : ?> "masonry"
					<?php else : ?> "fitRows"
					<?php endif; ?>
					});
				});
				/* END Default Isotope Code */


				<?php if ($settings[$prefix . 'post_sorting'] == 'yes') : ?>
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



				<?php if ($settings[$prefix . 'post_list_pagination'] == 'infinite-scroll' || $settings[$prefix . 'post_list_pagination'] == 'load-more') : ?>

					/* Begin Infinite Scroll */
					$container<?php echo esc_attr($this->get_id()); ?>.infinitescroll({
							errorCallback: function() {
								$("#infinite-nav-pro-<?php echo esc_attr($this->get_id()); ?>").delay(500).fadeOut(500,
									function() {
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
								$(".progression-masonry-item").addClass("opacity-progression");

							});

						}
					);
					/* END Infinite Scroll */
				<?php endif; ?>


				<?php if ($settings[$prefix . 'post_list_pagination'] == 'load-more') : ?>
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
}
