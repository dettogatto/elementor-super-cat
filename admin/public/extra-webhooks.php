<?php

class Elementor_Super_Cat_Extra_Webhooks {
    function hooks(){
        //Add our additional webhook right here
        add_action( 'elementor_pro/forms/new_record', array( $this, 'manipulate_form_submission' ), 10, 2 );
    }
    function manipulate_form_submission( $record, $ajax_handler ) {

        $option_prefix = "elementor_super_cat_ewh_";
        $howmany = get_option( $option_prefix . "howmany" );
        $form_name = $record->get_form_settings( 'form_name' );

        for($i = 0; $i < $howmany; $i++){

            if($form_name != get_option( $option_prefix . "form_name_" . $i )){
                break;
            }

            $form_data = $record->get_formatted_data();
            //change the names of fields before we send them somewhere

            $raw_fields = $record->get( 'fields' );
            $fields = [];
            foreach ( $raw_fields as $id => $field ) {
                $fields[ $id ] = $field['value'];
            }

            $response = wp_remote_post( get_option( $option_prefix . "wh_url_" . $i ), array( 'body' => $fields ) );
            //if the failure of our additional webhook should prevent the form from submitting...
            if( is_wp_error( $response ) ) {
                $msg = 'There was a problem launching the rocket. Please check with mission control.';
                $ajax_handler->add_error( $field['id'], $msg );
                $ajax_handler->add_error_message( $msg );
                $ajax_handler->is_success = false;
            }
        }
    }
}
$elementor_webhooks = new Elementor_Super_Cat_Extra_Webhooks();
$elementor_webhooks->hooks();
