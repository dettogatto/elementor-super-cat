<?php
namespace ElementorSuperCat;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 0.1
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 0.1
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
	 * @since 0.1
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
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 0.1
	 * @access public
	 */
	public function widget_scripts() {
		//wp_register_script( 'elementor-super-cat', plugins_url( '/assets/js/form-poster.js', __FILE__ ), [ 'jquery' ], false, true );
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 0.1
	 * @access private
	 */
	private function include_widgets_files() {
        require_once( __DIR__ . '/widgets/form-poster.php' );
        require_once( __DIR__ . '/widgets/post-filter.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 0.1
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Form_Poster() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_Filter() );
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 0.1
	 * @access public
	 */
	public function __construct() {

		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
	}
}

// Instantiate Plugin Class
Plugin::instance();
