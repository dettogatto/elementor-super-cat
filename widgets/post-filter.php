<?php
namespace ElementorSuperCat\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Elementor Post Filter
*
* Elementor widget for Post Filter.
*/
class Post_Filter extends \Elementor\Widget_Base {

    /**
    * Retrieve the widget name.
    *
    * @access public
    *
    * @return string Widget name.
    */
    public function get_name() {
        return 'post-filter';
    }

    /**
    * Retrieve the widget title.
    *
    * @access public
    *
    * @return string Widget title.
    */
    public function get_title() {
        return __( 'Post Filter Bar', 'elementor-super-cat' );
    }

    /**
    * Retrieve the widget icon.
    *
    * @access public
    *
    * @return string Widget icon.
    */
    public function get_icon() {
        return 'fa fa-filter';
    }

    /**
    * Retrieve the list of categories the widget belongs to.
    *
    * Used to determine where to display the widget in the editor.
    *
    * Note that currently Elementor supports only one category.
    * When multiple categories passed, Elementor uses the first one.
    *
    * @access public
    *
    * @return array Widget categories.
    */
    public function get_categories() {
        return [ 'super-cat' ];
    }

    /**
    * Retrieve the list of scripts the widget depended on.
    *
    * Used to set scripts dependencies required to run the widget.
    *
    * @access public
    *
    * @return array Widget scripts dependencies.
    */
    public function get_script_depends() {
        return [ 'elementor-super-cat' ];
    }

    /**
    * Register the widget controls.
    *
    * Adds different input fields to allow the user to change and customize the widget settings.
    *
    * @access protected
    */
    protected function _register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'elementor-super-cat' ),
            ]
        );

        $this->add_control(
            'taxonomy',
            [
                'label' => __( 'Name of taxonomy to filter', 'elementor-super-cat' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => $this->get_taxonomies(),
                // 'default' => $this->get_taxonomies()[0]
            ]
        );

        $this->add_control(
            'post_id',
            [
                'label' => __( 'CSS ID of the post widget', 'elementor-super-cat' ),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => __( 'Order By', 'elementor-super-cat' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'name',
                'options' => [
                    'name'  => __( 'Name', 'elementor-super-cat' ),
                    'slug' => __( 'Slug', 'elementor-super-cat' ),
                ],
            ]
        );

        $this->add_control(
            'all_text',
            [
                'label' => __( 'Text to show for <b>Show All</b>', 'elementor-super-cat' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => "all"
            ]
        );

        $this->add_control(
            'hide_empty',
            [
                'label' => __( 'Hide empty', 'elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => __( 'If ON empty filters will be hidden.', 'elementor' ),
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'elementor-super-cat' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color_filter',
            [
                'label' => __( 'Color', 'elementor-super-cat' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-portfolio__filter' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'color_filter_active',
            [
                'label' => __( 'Active Color', 'elementor-super-cat' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-portfolio__filter.elementor-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            'typography',
            [
                'name' => 'typography_filter',
                'selector' => '{{WRAPPER}} .elementor-portfolio__filter',
            ]
        );

        $this->add_control(
            'filter_item_spacing',
            [
                'label' => __( 'Space Between', 'elementor-super-cat' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-portfolio__filter:not(:last-child)' => 'margin-right: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-portfolio__filter:not(:first-child)' => 'margin-left: calc({{SIZE}}{{UNIT}}/2)',
                ],
            ]
        );

        $this->add_control(
            'filter_spacing',
            [
                'label' => __( 'Spacing', 'elementor-super-cat' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-portfolio__filters' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();


    }


    protected function get_taxonomies() {
        $taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

        $options = [ '' => '' ];

        foreach ( $taxonomies as $taxonomy ) {
            $options[ $taxonomy->name ] = $taxonomy->label;
        }

        return $options;
    }



    /**
    * Render the widget output on the frontend.
    *
    * Written in PHP and used to generate the final HTML.
    *
    * @access protected
    */
    protected function render() {
        wp_enqueue_script('post-filter-js');

        $settings = $this->get_settings_for_display();
        $filtererId = 'filter-' . $settings['taxonomy'] . "-" . $this->get_id();


        $phpTax = $settings['taxonomy'];
        $jsTax = $settings['taxonomy'];
        if($jsTax == "post_tag"){
            $jsTax = "tag";
        }


        $terms = get_terms( $phpTax, array( 'hide_empty' => true ) );

        if($settings['order_by'] == "slug"){
            usort($terms, function($a, $b){
                return $a->slug <=> $b->slug;
            });
        }

        $li = [];

        $placeholder = '<li
        class="super-cat-post-filter elementor-portfolio__filter elementor-active"
        data-term=""
        data-container="'.$filtererId.'"
        data-posts="'.$settings['post_id'].'">
        '. __($settings['all_text'], 'elementor-super-cat').'
        </li>';
        foreach ($terms as $k => $v) {

          	$slug = (preg_match("/\p{Hebrew}/u", urldecode($v->slug))?$v->term_id : $v->slug);
            $li[] = '<li
            class="super-cat-post-filter elementor-portfolio__filter"
            data-term="'.$jsTax."-".$slug .'"
            data-container="'.$filtererId.'"
            data-posts="'.$settings['post_id'].'">
            '.$v->name.'
            </li>';
        }

        ?>

        <div>
            <ul class="elementor-portfolio__filters cat-filter-for-<?php echo $settings['post_id']; ?>" id="<?php echo $filtererId; ?>" data-hide-empty="<?php echo($settings["hide_empty"]); ?>">
                <?php echo $placeholder; ?>
                <?php echo(implode($li)); ?>
            </ul>
        </div>

        <?php

    }

    /**
    * Render the widget output in the editor.
    *
    * Written as a Backbone JavaScript template and used to generate the live preview.
    *
    * @access protected
    */
    protected function _content_template() {
        ?>
        <div>
            <ul class="elementor-portfolio__filters cat-filter-for-{{ settings.post_id }}">
                <li class="elementor-portfolio__filter elementor-active">{{{ settings.all_text }}}</li>
                <li class="elementor-portfolio__filter">{{{ settings.taxonomy }}} 1</li>
                <li class="elementor-portfolio__filter">{{{ settings.taxonomy }}} 2</li>
            </ul>
        </div>
        <?php
    }
}
