<?php

/**
* Class sendinblue_Action_After_Submit
* @see https://developers.elementor.com/custom-form-action/
* Custom elementor form action after submit to add a subsciber to
* sendinblue list via API
*/

class Sendinblue_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {
  /**
  * Get Name
  *
  * Return the action name
  *
  * @access public
  * @return string
  */
  public function get_name() {
    return 'sendinblue';
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
    return __( 'Sendinblue', 'text-domain' );
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

    require_once( __DIR__ . '/sendinblue-api-v3.php' );
    $sendinblue = new Sendinblue_API_Gatto(get_option( "elementor_super_cat_sib_api_key" ));


    $settings = $record->get( 'form_settings' );

    $body = [];
    // Get summitted Form data
    $raw_fields = $record->get( 'fields' );

    $body["email"] = $raw_fields[$settings['sendinblue_email_field']]['value'];
    $list_ids = $settings['sendinblue_list_ids'];

    $attributes = [];

    for($i = 1; $i<6; $i++){
      if(!empty($settings['sendinblue_field_'.$i]) && !empty($settings['elem_field_'.$i])){
        $attributes[$settings['sendinblue_field_'.$i]] = $raw_fields[$settings['elem_field_'.$i]]['value'];
      }
    }

    $body['attributes'] = $attributes;
    $body['listIds'] = array_map('intval', explode(',', $list_ids));

    $sendinblue->sync_contact($body);
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
      'section_sendinblue',
      [
        'label' => __( 'Sendinblue', 'text-domain' ),
        'condition' => [
          'submit_actions' => $this->get_name(),
        ],
      ]
    );

    $widget->add_control(
      'sendinblue_email_field',
      [
        'label' => __( 'Email Field', 'text-domain' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'separator' => 'before',
        'description' => __( 'The Form field id that corresponds to the user\'s email', 'text-domain' ),
      ]
    );

    $widget->add_control(
      'sendinblue_list_ids',
      [
        'label' => __( 'List IDs', 'text-domain' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'separator' => 'after',
        'description' => __( 'Comma-separated list ids to which subscribe the user', 'text-domain' ),
      ]
    );

    for($i = 1; $i<6; $i++){

      $widget->add_control(
        'sendinblue_field_'.$i,
        [
          'label' => __( 'Sendinblue Field '.$i, 'text-domain' ),
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
      $element['sendinblue_url'],
      $element['sendinblue_list'],
      $element['sendinblue_name_field'],
      $element['sendinblue_email_field']
    );
  }
}
