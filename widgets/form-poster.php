<?php
namespace ElementorSuperCat\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Elementor Form Poster
*
* Elementor widget for Form Poster.
*
* @since 0.1
*/
class Form_Poster extends Widget_Base {

    /**
    * Retrieve the widget name.
    *
    * @since 0.1
    *
    * @access public
    *
    * @return string Widget name.
    */
    public function get_name() {
        return 'form-poster';
    }

    /**
    * Retrieve the widget title.
    *
    * @since 0.1
    *
    * @access public
    *
    * @return string Widget title.
    */
    public function get_title() {
        return __( 'Form Poster', 'elementor-super-cat' );
    }

    /**
    * Retrieve the widget icon.
    *
    * @since 0.1
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
    * @since 0.1
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
    * @since 0.1
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
    * @since 0.1
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
            'formid',
            [
                'label' => __( 'CSS ID of the form widget', 'elementor-super-cat' ),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => __( 'Action URL', 'elementor-super-cat' ),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'replace_underscores',
            [
                'label' => __( 'Replace _# with [#] in input names.<br><br>E.g.: <b>field_1_0</b> becomes <b>field[1][0]</b>', 'elementor-super-cat' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'yes' => [
                        'title' => __( 'Yes', 'elementor-super-cat' ),
                        'icon' => 'fa fa-check',
                    ],
                    'no' => [
                        'title' => __( 'No', 'elementor-super-cat' ),
                        'icon' => 'fa fa-times',
                    ]
                ],
                'default' => 'yes'
            ]
        );

        $this->end_controls_section();


    }

    /**
    * Render the widget output on the frontend.
    *
    * Written in PHP and used to generate the final HTML.
    *
    * @since 0.1
    *
    * @access protected
    */
    protected function render() {
        $settings = $this->get_settings_for_display();

        ?>

        <script type='text/javascript'>
        document.addEventListener("DOMContentLoaded", function(event) {

            /* The ID assigned to the form widget via Elementor */
            var formID = "#<?php echo $settings['formid']; ?>";
            /* The URL to which you want to send the data */
            var actionURL = "<?php echo $settings['url']; ?>";

            var $jq = jQuery.noConflict();
            $jq("#form-super-gatto").html($jq(formID).html());
            $jq("#form-super-gatto").attr("class", $jq(formID).attr("class"));
            $jq(formID).hide();
            $jq("#form-super-gatto form").attr("action", actionURL);
            $jq("#form-super-gatto form").find('input, textarea, select').each(function(){
                var matches = $jq(this).attr("name").match(/form_fields\[(.*?)\]/);
                if (matches) {
                    var submatch = matches[1];
                    <?php if($settings['replace_underscores'] == "yes"){ ?>
                        submatch = submatch.replace(/\_[0-9]+/g, function(x){return "["+x.replace("_", "")+"]";});
                        <?php } ?>
                        $jq(this).attr("name", submatch);
                    }else{
                        $jq(this).remove();
                    }
                });
            });
            </script>
            <div id="form-super-gatto"></div>
            <?php

        }

        /**
        * Render the widget output in the editor.
        *
        * Written as a Backbone JavaScript template and used to generate the live preview.
        *
        * @since 0.1
        *
        * @access protected
        *
        * protected function _content_template() {
        *     ?>
        *     <div style="background-color: #dee1e5; color: #b9bfc5; padding: 5px; font-size: 0.8em; text-align: center;">
        *         The form #{{{ settings.formid }}} will be here and will post to {{{ settings.url }}}
        *     </div>
        *     <?php
        * }
        */
    }
