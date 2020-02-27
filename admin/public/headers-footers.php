<?php

class Elementor_Super_Cat_Headers_Footers {

  private $ab;

  public function __construct(){
    $this->set_ab();
    add_action( 'wp_head', array( $this, 'print_head' ) );
    add_action( 'wp_footer', array( $this, 'print_footer' ) );
  }

  private function set_ab(){
    if(get_option("elementor_super_cat_hf_ab_test") == "on"){
      if(
        get_option("elementor_super_cat_hf_remember") == "on" &&
        isset($_COOKIE["esc_hf_ab"]) &&
        ($_COOKIE["esc_hf_ab"] == "a" || $_COOKIE["esc_hf_ab"] == "b")
      ){
        $this->ab = $_COOKIE["esc_hf_ab"];
      } else {
        $this->ab = array("a", "b")[rand(0, 1)];
        if(get_option("elementor_super_cat_hf_remember") == "on"){
          setcookie("esc_hf_ab", $this->ab, time()+7*24*60*60); // one week memory
        }
      }
    } else {
      $this->ab = "a";
    }
  }

  public function print_head(){
    echo(get_option("elementor_super_cat_hf_header_" . $this->ab));
  }


  public function print_footer(){
    echo(get_option("elementor_super_cat_hf_footer_" . $this->ab));
  }
}
$elementor_super_cat_hf = new Elementor_Super_Cat_Headers_Footers();
