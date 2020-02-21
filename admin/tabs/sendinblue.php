<?php

class Super_Cat_Tab {

  public $option_prefix;
  public $plugin_name;
  private $sendinblue;

  public function __construct($plugin_name){
    $this->plugin_name = $plugin_name;
    $this->option_prefix = $plugin_name . "_sib_";
    require_once(__DIR__ . '/../helpers/sendinblue-api-v3.php');
    $this->sendinblue = new Sendinblue_API_Gatto(get_option( $this->option_prefix . 'api_key' ));
    add_action( 'admin_init', array( $this, 'setup_init' ) );

  }

  public function content(){

    echo(
      '<p>
      Set Api credentials for Sendinblue Elementor form action
      </p>'
    );
    do_settings_sections($this->plugin_name);
    submit_button();

  }

  public function setup_init() {

    add_settings_section(
      "sezione",
      " ",
      array($this, 'section_callback'),
      $this->plugin_name
    );


    register_setting( $this->plugin_name, $this->option_prefix . 'api_key');

    add_settings_field(
      $this->option_prefix . 'api_key',
      __( 'API Key', 'elementor-super-cat' ),
      array( $this, 'field_wh_key_callback' ),
      $this->plugin_name,
      "sezione",
      array("id" => $this->option_prefix . 'api_key' )
    );

  }

  public function section_callback( $args ) {

    return NULL;

  }


  public function field_wh_key_callback( $args ) {
    $id = $args["id"];
    $key = get_option( $id );
    echo '<input style="width: 100%;" name="' . $id . '" id="' . $id. '" value="' . $key . '" />';
    echo('<p>');
    if($this->sendinblue->check_connection()){
      echo("Sendinblue is connected!");
    } else {
      echo("Sendinblue is not connected");
    }
    echo('</p>');
  }

  public function sanitize_howmany( $howmany ) {
    return intval($howmany);
  }

}
