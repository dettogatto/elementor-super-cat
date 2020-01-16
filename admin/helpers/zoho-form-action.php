<?php

/**
* Class zoho_Action_After_Submit
* @see https://developers.elementor.com/custom-form-action/
* Custom elementor form action after submit to add a subsciber to
* zoho list via API
*/
class Zoho_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {
    /**
    * Get Name
    *
    * Return the action name
    *
    * @access public
    * @return string
    */
    public function get_name() {
        return 'zoho';
    }

    /**
    * Get Label
    *
    * Returns the action label
    *
    * @access public
    * @return string
    */
    public function get_label() {
        return __( 'Zoho', 'text-domain' );
    }

    /**
    * Run
    *
    * Runs the action after submit
    *
    * @access public
    * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
    * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
    */
    public function run( $record, $ajax_handler ) {

        require_once( __DIR__ . '/../../zoho-connection.php' );
        $zoho = new Zoho_Connection_By_Gatto();


        $settings = $record->get( 'form_settings' );

        $body = [];
        // Get sumitetd Form data
        $raw_fields = $record->get( 'fields' );

        for($i = 1; $i<6; $i++){
            if(!empty($settings['zoho_field_'.$i]) && !empty($settings['elem_field_'.$i])){
                $body[$settings['zoho_field_'.$i]] = $raw_fields[$settings['elem_field_'.$i]]['value'];
            }
        }

        $zoho->create_contact($body);

        // // Send the request
        // wp_remote_post( 'https://cosmo.cat/params', [
        //     'body' => $body
        // ]);
    }

    /**
    * Register Settings Section
    *
    * Registers the Action controls
    *
    * @access public
    * @param \Elementor\Widget_Base $widget
    */
    public function register_settings_section( $widget ) {
        $widget->start_controls_section(
            'section_zoho',
            [
                'label' => __( 'Zoho', 'text-domain' ),
                'condition' => [
                    'submit_actions' => $this->get_name(),
                ],
            ]
        );

        for($i = 1; $i<6; $i++){

            $widget->add_control(
                'zoho_field_'.$i,
                [
                    'label' => __( 'Zoho Field '.$i, 'text-domain' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'separator' => 'before',
                    'description' => __( '', 'text-domain' ),
                ]
            );

            $widget->add_control(
                'elem_field_'.$i,
                [
                    'label' => __( 'Elementor Field '.$i, 'text-domain' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'separator' => 'after',
                    'description' => __( '', 'text-domain' ),
                ]
            );

        }


        $widget->end_controls_section();

    }

    /**
    * On Export
    *
    * Clears form settings on export
    * @access Public
    * @param array $element
    */
    public function on_export( $element ) {
        unset(
            $element['zoho_url'],
            $element['zoho_list'],
            $element['zoho_name_field'],
            $element['zoho_email_field']
        );
    }
}
