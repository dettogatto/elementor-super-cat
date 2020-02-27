<?php

class Super_Cat_Tab {

  public $option_prefix;
  public $plugin_name;

  public function __construct($plugin_name){

    $this->option_prefix = $plugin_name . "_hf_";
    $this->plugin_name = $plugin_name;
    add_action( 'admin_init', array( $this, 'setup_init' ) );

  }

  public function content(){

    do_settings_sections($this->plugin_name);
    submit_button();

  }

  public function setup_init() {

    add_settings_section(
      "sezione",
      "Headers and Footers",
      array($this, 'section_callback'),
      $this->plugin_name
    );

    register_setting($this->plugin_name, $this->option_prefix . "ab_test");

    add_settings_field(
      $this->option_prefix . "ab_test",
      'AB Test',
      array( $this, 'check_callback' ),
      $this->plugin_name, "sezione",
      array("id" => $this->option_prefix . "ab_test")
    );

    register_setting($this->plugin_name, $this->option_prefix . "remember");

    add_settings_field(
      $this->option_prefix . "remember",
      'Remember',
      array( $this, 'remember_callback' ),
      $this->plugin_name, "sezione",
      array("id" => $this->option_prefix . "remember", "class" => "b-section")
    );

    register_setting($this->plugin_name, $this->option_prefix . "header_a");

    add_settings_field(
      $this->option_prefix . "header_a",
      'Header A',
      array( $this, 'txta_callback' ),
      $this->plugin_name, "sezione",
      array("id" => $this->option_prefix . "header_a")
    );

    register_setting($this->plugin_name, $this->option_prefix . "header_b");

    add_settings_field(
      $this->option_prefix . "header_b",
      'Header B',
      array( $this, 'txta_callback' ),
      $this->plugin_name, "sezione",
      array("id" => $this->option_prefix . "header_b", "class" => "b-section")
    );

    register_setting($this->plugin_name, $this->option_prefix . "footer_a");

    add_settings_field(
      $this->option_prefix . "footer_a",
      'Footer A',
      array( $this, 'txta_callback' ),
      $this->plugin_name, "sezione",
      array("id" => $this->option_prefix . "footer_a")
    );

    register_setting($this->plugin_name, $this->option_prefix . "footer_b");

    add_settings_field(
      $this->option_prefix . "footer_b",
      'Footer B',
      array( $this, 'txta_callback' ),
      $this->plugin_name, "sezione",
      array("id" => $this->option_prefix . "footer_b", "class" => "b-section")
    );

  }

  public function section_callback( $arguments ) {
    ?>
    <p>
      <strong>Headers</strong> are injected in the <code>&lt;head&gt;</code> section
      <br>
      <strong>Footers</strong> are injected just before the <code>&lt;/body&gt;</code> tag
    </p>
    <?php
    $this->print_js();
  }

  public function txta_callback ( $arguments ) {

    $id = $arguments["id"];
    echo '<textarea name="' . $id . '" id="' . $id . '" class="gatto-input">'. get_option($id). '</textarea>';
  }

  public function check_callback ( $arguments ) {

    $id = $arguments["id"];
    $ca = '';
    $cb = 'checked="checked"';
    if(get_option($id) == "on"){
      $ca = 'checked="checked"';
      $cb = '';
    }
    ?>
    <input type="radio" name="<?php echo($id); ?>" id="radio-on" class="gatto-input" value="on" <?php echo($ca); ?> />
    <label for="radio-on">On</label>
    <br>
    <input type="radio" name="<?php echo($id); ?>" id="radio-off" class="gatto-input" value="off" <?php echo($cb); ?>>
    <label for="radio-off">Off</label>
    <p>
      If <strong>on</strong> it will choose randomly between A-codes and B-codes.<br>
    </p>
    <?php
    //echo '<textarea name="' . $id . '" id="' . $id . '" class="gatto-input">'. get_option($id). '</textarea>';
  }


  public function remember_callback ( $arguments ) {

    $id = $arguments["id"];
    $ca = 'checked="checked"';
    $cb = '';
    if(get_option($id) == "off"){
      $ca = '';
      $cb = 'checked="checked"';
    }
    ?>
    <input type="radio" name="<?php echo($id); ?>" id="remember-on" class="gatto-input" value="on" <?php echo($ca); ?> />
    <label for="remember-on">Remember</label>
    <br>
    <input type="radio" name="<?php echo($id); ?>" id="remember-off" class="gatto-input" value="off" <?php echo($cb); ?>>
    <label for="remember-off">Always random</label>
    <p>
      If set to <strong>remember</strong> a cookie will be used to show each client always the same code.
    </p>
    <?php
    //echo '<textarea name="' . $id . '" id="' . $id . '" class="gatto-input">'. get_option($id). '</textarea>';
  }

  private function print_js(){
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function(event){
      var $jq = jQuery.noConflict();

      function txta_showhide(){
        if($jq('#radio-on').is(":checked")){
          $jq('.b-section').fadeIn();
        } else {
          $jq('.b-section').fadeOut();
        }
      };

      $jq('#radio-on, #radio-off').on("change", txta_showhide);
      txta_showhide();
    });
    </script>
    <?php
  }



}
