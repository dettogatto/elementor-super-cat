<?php

add_action( 'elementor_pro/init', function (){
  // Here its safe to include our action class file
  include_once( __DIR__ . '/../helpers/zoho-form-action.php' );

  // // Instantiate the action class
  $zoho_action = new Zoho_Action_After_Submit();

  // Register the action with form widget
  \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $zoho_action->get_name(), $zoho_action );
});


add_action( 'wp_ajax_setup_zoho', function(){
  $grant = $_GET["code"];
  require_once( __DIR__ . '/../helpers/zoho-api.php' );
  $zoho = new Zoho_Connection_By_Gatto();
  if($zoho->get_tokens_from_grant($grant)){
    echo("L'attivazione è andata a buon fine!<br><br>");
    echo('<a href="'.get_site_url().'/wp-admin/admin.php?page=elementor-super-cat&tab=zoho'.'">Torna alle impostazioni.</a>');
  }
  wp_die();
} );
