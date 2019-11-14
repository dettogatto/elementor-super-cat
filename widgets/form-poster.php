<?php
namespace ElementorSuperCat\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Elementor Form Poster
*
* Elementor widget for Form Poster.
*/
class Form_Poster extends Widget_Base {

    /**
    * Retrieve the widget name.
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
    * @access public
    *
    * @return string Widget icon.
    */
    public function get_icon() {
        return 'eicon-code';
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
            var superGattoID = "#form-super-gatto-for-<?php echo $settings['formid']; ?>";

            var $jq = jQuery.noConflict();
            $jq(superGattoID).html($jq(formID).html());
            $jq(superGattoID).attr("class", $jq(formID).attr("class"));
            $jq(formID).hide();
            $jq(superGattoID + " form").attr("action", actionURL);
            $jq(superGattoID + " form").find('input, textarea, select').each(function(){
                var matches = $jq(this).attr("name").match(/form_fields\[(.*?)\]/);
                if (matches) {
                    var submatch = matches[1];
                    <?php if($settings['replace_underscores'] == "yes"){ ?> submatch = submatch.replace(/\_[0-9]+/g, function(x){return "["+x.replace("_", "")+"]";}); <?php } ?>
                    $jq(this).attr("name", submatch);
                }else{
                    $jq(this).remove();
                }
                var cls=$jq(this).attr("class");
                if ( cls && cls.match(/flatpickr/) ){
                    $jq(this).flatpickr();
                }
            });
            // $jq(superGattoID + " form").submit(function(){
            //     $jq(this).find('input, textarea, select').each(function(){
            //         /* SET THE COOKIE */
            //         var name = "supercat_form_" + $jq(this).attr("name");
            //         var value = $jq(this).val();
            //         var expires = "";
            //         document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
            //     });
            // });
        });
        </script>
        <div id="form-super-gatto-for-<?php echo $settings['formid']; ?>"></div>
        <?php

    }

}
