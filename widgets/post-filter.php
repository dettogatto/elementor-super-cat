<?php
namespace ElementorSuperCat\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Elementor Post Filter
*
* Elementor widget for Post Filter.
*
* @since 0.2
*/
class Post_Filter extends \Elementor\Widget_Base {

    /**
    * Retrieve the widget name.
    *
    * @since 0.2
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
    * @since 0.2
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
    * @since 0.2
    *
    * @access public
    *
    * @return string Widget icon.
    */
    public function get_icon() {
        return 'eicon-coding';
    }

    /**
    * Retrieve the list of categories the widget belongs to.
    *
    * Used to determine where to display the widget in the editor.
    *
    * Note that currently Elementor supports only one category.
    * When multiple categories passed, Elementor uses the first one.
    *
    * @since 0.2
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
    * @since 0.2
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
    * @since 0.2
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
                'default' => $this->get_taxonomies()[0]
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
            'all_text',
            [
                'label' => __( 'Text to show for <b>Show All</b>', 'elementor-super-cat' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => "all"
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
    * @since 0.2
    *
    * @access protected
    */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = 'filter-' . $settings['taxonomy_name'] . "-";
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $phpTax = $settings['taxonomy'];
        $jsTax = $settings['taxonomy'];
        if($jsTax == "post_tag"){
            $jsTax = "tag";
        }


        $terms = [];
        foreach(get_terms( $phpTax, array( 'hide_empty' => true ) ) as $k => $v){
            $terms[] = [$v->slug, $v->name];
        }

        ?>

        <script type='text/javascript'>
        document.addEventListener("DOMContentLoaded", function(event){
            var $jq = jQuery.noConflict();

            var tax = "<?php echo $jsTax; ?>";
            var allTxt = "<?php echo $settings['all_text']; ?>";
            var postId = "#<?php echo $settings['post_id']; ?>";
            var theFiltererId = "#<?php echo $randomString; ?>";

            var found = <?php echo(json_encode($terms)); ?>;
            alert

            newli = $jq("<li></li>");
            newli.text(allTxt);
            newli.attr("class", "elementor-portfolio__filter elementor-active");
            newli.click(function(){
                $jq(postId).find('article').hide();
                $jq(postId).find('article').fadeIn(400);
                $jq(".cat-filter-for-<?php echo $settings['post_id']; ?>").each(function(){
                    $jq(this).find('li').removeClass("elementor-active");
                    $jq(this).find('li').first().addClass("elementor-active");
                });
                history.replaceState(null, null, ' ');
            });
            $jq(theFiltererId).append(newli);

            for(var i = 0; i < found.length; i++){
                var newli = $jq("<li></li>");
                newli.text(found[i][1]);
                newli.attr("class", "elementor-portfolio__filter");
                newli.attr("data-filter", tax + "-" + found[i][0]);
                newli.click(function(){
                    $jq(postId).find('article').hide();
                    var theFilter = $jq(this).attr("data-filter");
                    $jq(postId).find('article').each(function(){
                        var classes = $jq(this).attr("class");
                        if(classes.split(" ").includes(theFilter)){
                            $jq(this).fadeIn(400);
                        }
                    });
                    $jq(".cat-filter-for-<?php echo $settings['post_id']; ?>").find('li').removeClass("elementor-active");
                    $jq(this).addClass("elementor-active");
                    window.location.hash = "#"+$jq(this).attr("data-filter");
                });
                $jq(theFiltererId).append(newli);
            }

            if(window.location.hash){
                let hhh = window.location.hash.replace("#", "");
                $jq( 'li.elementor-portfolio__filter[data-filter='+hhh+']' ).trigger("click");
            }

        });
        </script>
        <div>
            <ul class="elementor-portfolio__filters cat-filter-for-<?php echo $settings['post_id']; ?>" id="<?php echo $randomString; ?>">
            </ul>
        </div>

        <?php

    }

    /**
    * Render the widget output in the editor.
    *
    * Written as a Backbone JavaScript template and used to generate the live preview.
    *
    * @since 0.2
    *
    * @access protected
    */
    protected function _content_template() {
        ?>
        <div>
            <ul class="elementor-portfolio__filters cat-filter-for-<?php echo $settings['post_id']; ?>" id="<?php echo $randomString; ?>">
                <#
                var allTxt = settings.all_text;
                var tax = settings.taxonomy;
                print('<li class="elementor-portfolio__filter elementor-active">'+allTxt+'</li>');
                print('<li class="elementor-portfolio__filter">'+tax+' 1</li>');
                print('<li class="elementor-portfolio__filter">'+tax+' 2</li>');
                #>
            </ul>
        </div>
        <?php
    }
}
