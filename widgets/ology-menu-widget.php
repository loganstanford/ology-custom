<?php
class Ology_Menu_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'ology_menu_widget';
    }

    public function get_title()
    {
        return esc_html__('Ology Menu Widget', 'textdomain');
    }

    public function get_icon()
    {
        return 'eicon-posts-grid';
    }

    public function get_custom_help_url()
    {
        return '#';
    }

    public function get_categories()
    {
        return ['ology-addons-cat'];
    }

    public function get_keywords()
    {
        return ['keyword', 'keyword'];
    }

    // Register controls
    protected function register_controls()
    {

        $prefix = 'ology_menu_';

        $this->start_controls_section(
            $prefix . 'content_section',
            [
                'label' => esc_html__('Content', 'default'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            $prefix . 'location',
            [
                'label' => esc_html__('Location', 'elementor-ology-cocktails-widget'),
                'description' => esc_html__('Choose a location', 'elementor-ology-cocktails-widget'),
                'label_block' => false,
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => getOlogyLocations(),
            ]
        );

        $this->add_control(
            $prefix . 'order_by',
            [
                'label' => esc_html__('Sort by', 'default'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'tap',
                'options' => [
                    '' => esc_html__('Default', 'default'),
                    'alpha' => esc_html__('Alphabetical', 'default'),
                    'style' => esc_html__('Style', 'default'),
                    'tap' => esc_html__('Tap #', 'default'),
                    'date' => esc_html__('Recently added', 'default'),
                ]
            ]
        );

        $this->add_control(
            $prefix . 'order',
            [
                'label' => esc_html__('Sort direction', 'default'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'ASC',
                'options' => [
                    'ASC' => esc_html__('Ascending', 'default'),
                    'DESC' => esc_html__('Descending', 'default')
                ]
            ]
        );

        $this->add_control(
            $prefix . 'feature-1',
            [
                'label' => esc_html__('Feature 1', 'default'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            $prefix . 'feature-1-location',
            [
                'label' => esc_html__('Feature 1 Location ', 'default'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top-right',
                'options' => [
                    'top-right' => esc_html__('Top Right', 'default'),
                    'top-left' => esc_html__('Top Left', 'default'),
                    'bottom-right' => esc_html__('Bottom Right', 'default'),
                    'bottom-left' => esc_html__('Bottom Left', 'default')
                ]
            ]
        );

        $this->add_control(
            $prefix . 'feature-images',
            [
                'label' => esc_html__('Add Feature Images', 'textdomain'),
                'type' => \Elementor\Controls_Manager::GALLERY,
                'show_label' => true,
                'default' => [],
            ]
        );

        $this->add_control(
            $prefix . 'feature-images-location',
            [
                'label' => esc_html__('Features Location ', 'default'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top-right',
                'options' => [
                    'top-right' => esc_html__('Top Right', 'default'),
                    'top-left' => esc_html__('Top Left', 'default'),
                    'bottom-right' => esc_html__('Bottom Right', 'default'),
                    'bottom-left' => esc_html__('Bottom Left', 'default')
                ]
            ]
        );

        $this->add_control(
            $prefix . 'feature-images-direction',
            [
                'label' => esc_html__('Features Direction ', 'default'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'default'),
                    'vertical' => esc_html__('Vertical', 'default')
                ]
            ]
        );

        $this->add_control(
            $prefix . 'filler-images',
            [
                'label' => esc_html__('Add Filler Images', 'textdomain'),
                'type' => \Elementor\Controls_Manager::GALLERY,
                'show_label' => true,
                'default' => [],
            ]
        );

        $this->add_control(
            $prefix . 'custom-text-item',
            [
                'label' => esc_html__('Custom Menu Item', 'textdomain'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => esc_html__('', 'textdomain'),
                'placeholder' => esc_html__('Type your custom menu item here', 'textdomain'),
            ]
        );

        $this->add_control(
            $prefix . 'custom-text-item-location',
            [
                'label' => esc_html__('Custom Menu Item Location ', 'default'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top-right',
                'options' => [
                    'top-right' => esc_html__('Top Right', 'default'),
                    'top-left' => esc_html__('Top Left', 'default'),
                    'bottom-right' => esc_html__('Bottom Right', 'default'),
                    'bottom-left' => esc_html__('Bottom Left', 'default')
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            $prefix . 'style_section',
            [
                'label' => esc_html__('Style', 'default'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            $prefix . 'background_color',
            [
                'label' => esc_html__('Menu background color', 'default'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(42,42, 42, 1)',
                'selectors' => [
                    '{{WRAPPER}} .ology-menu-wrapper' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            $prefix . 'menu-item-background-color',
            [
                'label' => esc_html__('Menu item background color', 'default'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ology-menu-item-container' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            $prefix . 'menu-item-text-color',
            [
                'label' => esc_html__('Font color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(42,42, 42, 1)',
                'selectors' => [
                    '{{WRAPPER}} .ology-menu-wrapper' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            $prefix . 'custom-menu-item-background_color',
            [
                'label' => esc_html__('Custom menu item background color', 'default'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ology-custom-menu-item-container' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_responsive_control(
            $prefix . 'grid_column_count',
            [
                'label' => esc_html__('Columns', 'default'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT,
                'desktop_default' => '33.330%',
                'tablet_default' => '50%',
                'mobile_default' => '100%',
                'options' => [
                    '1' => esc_html__('1 Column', 'default'),
                    '2' => esc_html__('2 Column', 'default'),
                    '3' => esc_html__('3 Columns', 'default'),
                    '4' => esc_html__('4 Columns', 'default'),
                    '5' => esc_html__('5 Columns', 'default'),
                    '6' => esc_html__('6 Columns', 'default'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .progression-masonry-item .ology-menu-item' => 'width: {{VALUE}};',
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            $prefix . 'ipa_color',
            [
                'label' => esc_html__('IPA Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgb(91, 177, 101)',
                'selectors' => [
                    '{{WRAPPER}} .ology-menu-style-ipa' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            $prefix . 'stout_color',
            [
                'label' => esc_html__('Stout Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgb(120, 35, 130)',
                'selectors' => [
                    '{{WRAPPER}} .ology-menu-style-stout' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            $prefix . 'sour_color',
            [
                'label' => esc_html__('Sour Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgb(219, 82, 58)',
                'selectors' => [
                    '{{WRAPPER}} .ology-menu-style-sour' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            $prefix . 'lager_color',
            [
                'label' => esc_html__('Lager Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgb(231, 165, 80)',
                'selectors' => [
                    '{{WRAPPER}} .ology-menu-style-lager' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            $prefix . 'other_color',
            [
                'label' => esc_html__('Other Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgb(62, 31, 206)',
                'selectors' => [
                    '{{WRAPPER}} .ology-menu-style-other' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        global $post;
        $prefix = 'ology_menu_';
        $settings = $this->get_settings_for_display();

        //wp_enqueue_style('ology_bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
        //wp_enqueue_script('ology_masonry', 'https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js');

        // WP Query
        $args = array(
            'post_type' => 'beer_ontap',
            'post_status' => array('publish', 'draft'),
            'tax_query' => array(
                array(
                    'taxonomy' => 'ology-location',
                    'field' => 'slug',
                    'terms' => $settings[$prefix . 'location'],
                ),
            ),
            'order' => $settings[$prefix . 'order'],
            'posts_per_page' => -1,
        );

        switch ($settings[$prefix . 'order_by']) {
            case 'tap':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'ology_' . $settings[$prefix . 'location'] . '_tap-number';
                break;
            case 'alpha':
                $args['orderby'] = 'title';
                break;
            case 'style':
                // WIP
                break;
            case 'date':
                $args['orderby'] = 'date';
                break;
            default:
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'ology_' . $settings[$prefix . 'location'] . '_tap-number';
                break;
        }

        $menuItems = new \WP_Query($args);
        $gridColumnCount = $settings[$prefix . 'grid_column_count'];

        // Start rendering HTML
        ?>

<div class="wrapper ology-menu-wrapper p-0">
    <div class="container-fluid ology-menu-container p-0">
        <div class="row row-cols-lg-<?php echo $settings[$prefix . 'grid_column_count'] . ' row-cols-md-' . ($settings[$prefix . 'grid_column_count'] - 1); ?> row-cols-1 menu-row justify-content-start p-2"
            id="ology-menu-main">
            <?php
                    // Initiate a var to keep track of how many instances have been returned to render at least 25
                    $i = 0;
                    $featuresRendered = 0;
                    // Loop through menu items
                    while ($menuItems->have_posts()):
                        $menuItems->the_post();
                        $title = null;
                        $title = get_the_title();
                        $titleSize = strlen($title) > 20 ? "long-name" : "short-name";
                        $excerpt = get_the_excerpt();
                        $excerptSize = strlen($excerpt) > 50 ? "long-excerpt" : "short-excerpt";
                        $style = getOlogyParentStyle($post->ID)->slug;
                        $tap = getOlogyTapNumber($post->ID, $settings[$prefix . 'location']);
                        $abv = get_post_meta($post->ID, 'ology_abv', true);
                        $i++;

                        // Determine which square goes with each feature location
                        $topLeftFeatureLocation = 1;
                        $topRightFeatureLocation = $gridColumnCount;
                        $bottomLeftFeatureLocation = floor($menuItems->found_posts / $gridColumnCount);
                        $bottomRightFeatureLocation = $menuItems->found_posts;

                        switch ($settings[$prefix . 'feature-images-location']) {
                            case 'top-right':
                                $featureLocation = $topRightFeatureLocation;
                                break;
                            case 'top-left':
                                $featureLocation = $topLeftFeatureLocation;
                                break;
                            case 'bottom-right':
                                $featureLocation = $bottomRightFeatureLocation;
                                break;
                            case 'bottom-left':
                                $featureLocation = $bottomLeftFeatureLocation;
                                break;
                            default:
                                $featureLocation = $topRightFeatureLocation;
                                break;
                        }

                        // The option to display features vertically despite items rendering horizontally
                        // AND have various column counts specified as an option requires us to multiply 
                        // the item count number each time one is rendered to calculate the next number
                        // a feature should be rendered.
            
                        // Display feature items
                        if (
                            $i == ($featureLocation * ($featuresRendered + 1)) &&
                            count($settings[$prefix . 'feature-images']) > 0 &&
                            $i < $menuItems->found_posts
                        ) {
                            $image = $settings[$prefix . 'feature-images'][$featuresRendered];
                            echo '<div class="ology-menu-item featured-menu-item col p-0">';
                            echo '<img src="' . $image['url'] . '"></div>';
                            $i++;
                            $featuresRendered++;
                        }


                        // TODO ADD IN REMAINING FEATURE ELEMENTS
            
                        // Run if sort is "tap" and next tap in squence is empty
                        /* while(isset($tap) && $tap != $i) {
                            $i++;
                            ?>

            <?php

                        } */

                        // HTML for individual menu items
                        ?>
            <div class="ology-menu-item col p-0">
                <div class="row ology-menu-item-row h-100 menu-row">
                    <div class="ology-menu-item-left d-flex align-items-end flex-column h-100 col-1 p-0">
                        <div class="ology-menu-style-<?php echo $style; ?> ology-menu-style"></div>
                        <div class="ology-tap-number-wrapper">
                            <div class="ology-tap-number mx-auto">
                                <?php echo $tap; ?>
                            </div>
                        </div>
                    </div>
                    <div class="ology-menu-item-center h-100 col-7 d-flex flex-column justify-content-between p-1">
                        <div class="ology-menu-item-title fw-bolder pt-2 <?php echo $titleSize; ?>">
                            <?php echo $title; ?>
                        </div>
                        <p class="ology-menu-item-excerpt <?php echo $excerptSize; ?>">
                            <?php echo $excerpt; ?>
                        </p>
                        <div class="ology-abv fw-medium">
                            <?php echo $abv . '% ABV'; ?>
                        </div>
                    </div>
                    <div class="ology-menu-item-right h-100 col-4 p-0">
                        <div class="d-flex flex-column justify-content-start py-1 h-100 ology-menu-item-containers">

                            <?php
                                        // Render containers
                                        $containers = getOlogyPostContainers($post->ID, $settings[$prefix . 'location']);
                                        foreach ($containers as $c) {
                                            ?>
                            <div class="ology-menu-item-container d-flex text-nowrap align-items-center">
                                <?php echo $c->name == '4 Pack' ? '4pk' : $c->name; ?><span class="ms-auto">
                                    <?php echo '$' . $c->price; ?>
                                </span>
                            </div>
                            <?php
                                        }
                                        ?>

                        </div>
                    </div>
                </div>
            </div>
            <?php
                        // Render features at end of menu
                        if ($i == $featureLocation) {
                            foreach ($settings[$prefix . 'feature-images'] as $image) {
                                echo '<div class="ology-menu-item featured-menu-item col p-0">';
                                echo '<img src="' . $image['url'] . '"></div>';
                                $i++;
                            }
                        }


                    endwhile;

                    // Determine how many empty items need to be filled
                    $emptyMenuSpots = ($gridColumnCount - ($i % $gridColumnCount)) % 5;

                    // Render fillers at end of menu to fill empty spots
                    for ($k = 0; $k < $emptyMenuSpots && $k < count($settings[$prefix . 'filler-images']); $k++) {
                        $image = $settings[$prefix . 'filler-images'][$k];
                        echo '<div class="ology-menu-item featured-menu-item filler-image col p-0">';
                        echo '<img src="' . $image['url'] . '"></div>';
                    }
                    ?>
        </div>
        <div
            class="ology-menu-style-footer position-fixed bottom-0 d-flex align-items-center justify-content-around px-1">
            <div class="beer-style-box flex-fill">Beer Styles:</div>
            <?php
                    // Render styles bar
                    $styles = getOlogyParentStyles();
                    foreach ($styles as $s) {
                        ?>
            <div
                class="beer-style-box flex-fill m-1 p-2 text-center align-middle  ology-menu-style-<?php echo $s->slug ?>">
                <?php echo $s->name; ?>
            </div>
            <?php
                    }
                    ?>


        </div>
    </div>

</div>
<?php
    }
}