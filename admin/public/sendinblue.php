<?php

add_action( 'elementor_pro/init', function (){
  // Here its safe to include our action class file
  include_once( __DIR__ . '/../helpers/sendinblue-form-action.php' );

  // // Instantiate the action class
  $sib_action = new Sendinblue_Action_After_Submit();

  // Register the action with form widget
  \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $sib_action->get_name(), $sib_action );
});
