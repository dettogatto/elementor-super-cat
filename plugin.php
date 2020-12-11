<?php
namespace ElementorSuperCat;

/**
* Class Plugin
*
* Main Plugin class
*/
class Plugin {

  /**
  * Instance
  *
  * @access private
  * @static
  *
  * @var Plugin The single instance of the class.
  */
  private static $_instance = null;

  /**
  * Instance
  *
  * Ensures only one instance of the class is loaded or can be loaded.
  *
  * @access public
  *
  * @return Plugin An instance of the class.
  */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
  * Include Widgets files
  *
  * Load widgets files
  *
  * @access private
  */
  private function include_widgets_files() {
    require_once( __DIR__ . '/widgets/form-poster.php' );
    require_once( __DIR__ . '/widgets/post-filter.php' );
    require_once( __DIR__ . '/widgets/param-button.php' );
    require_once( __DIR__ . '/widgets/checkbox-filter.php' );
    require_once( __DIR__ . '/widgets/dropdown-filter.php' );
    require_once( __DIR__ . '/widgets/autostop-video.php' );
  }

  /**
  * Register Widgets
  *
  * Register new Elementor widgets.
  *
  * @access public
  */
  public function register_widgets() {
    // Its is now safe to include Widgets files
    $this->include_widgets_files();

    // Register Widgets
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Form_Poster() );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_Filter() );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Param_Button() );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Checkbox_Filter() );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Dropdown_Filter() );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Autostop_Video() );
  }

  /**
  *  Plugin class constructor
  *
  * Register plugin action hooks and filters
  *
  * @access public
  */
  public function __construct() {
    // Register widgets
    add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

    add_action("wp_enqueue_scripts", function(){
      wp_register_script( 'gatto-generic-js', plugins_url( '/assets/js/generic.js', __FILE__ ), array('jquery'));
      wp_enqueue_script('gatto-generic-js');
    });
  }
}

// Instantiate Plugin Class
Plugin::instance();
