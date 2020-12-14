<?php
/**
* Plugin Name: Elementor SuperCat
* Description: Elementor add-ons
* Plugin URI:  https://github.com/dettogatto/elementor-super-cat
* Version:     2.7.1
* Author:      Nicola Cavallazzi
* Author URI:  https://cosmo.cat/
* Text Domain: elementor-super-cat
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* UPDATE CHECKER */

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
  'https://github.com/dettogatto/elementor-super-cat/',
  __FILE__,
  'elementor-super-cat'
);

// Include the admin menu
require_once( __DIR__ . '/admin/loader.php' );
$admin_esc = new Elementor_Super_Cat_Admin();
foreach($admin_esc->tabs as $k => $v){
  $file = __DIR__ . '/admin/public/' . $k .'.php';
  if(file_exists($file)){
    require_once($file);
  }
}


//Optional: If you're using a private repository, specify the access token like this:
//$myUpdateChecker->setAuthentication('your-token-here');

//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');


/**
* Main Elementor Super Cat Class
*
* The init class that runs the Super Cat plugin.
* Intended To make sure that the plugin's minimum requirements are met.
*
* You should only modify the constants to match your plugin's needs.
*
* Any custom code should go inside Plugin Class in the plugin.php file.
*/
final class Elementor_Super_Cat {

  /**
  * Plugin Version
  *
  * @var string The plugin version.
  */
  const VERSION = '2.7.1';

  /**
  * Minimum Elementor Version
  *
  * @var string Minimum Elementor version required to run the plugin.
  */
  const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

  /**
  * Minimum PHP Version
  *
  * @var string Minimum PHP version required to run the plugin.
  */
  const MINIMUM_PHP_VERSION = '7.0';

  /**
  * Constructor
  *
  * @access public
  */
  public function __construct() {

    // Load translation
    add_action( 'init', array( $this, 'i18n' ) );

    // Init Plugin
    add_action( 'plugins_loaded', array( $this, 'init' ) );

    // Create custom category
    add_action( 'elementor/elements/categories_registered', array( $this, 'create_category' ) );
  }

  /**
  * Create widget category
  *
  * Creates the custom widget category.
  * Fired by `elementor/elements/categories_registered` action hook.
  *
  * @access public
  */
  public function create_category($elements_manager) {
    $elements_manager->add_category(
      'super-cat',
      [
        'title' => __( 'Super Cat', 'super-cat' ),
        'icon' => 'fa fa-plug',
      ]
    );
  }

  /**
  * Load Textdomain
  *
  * Load plugin localization files.
  * Fired by `init` action hook.
  *
  * @access public
  */
  public function i18n() {
    load_plugin_textdomain( 'elementor-super-cat' );
  }

  /**
  * Initialize the plugin
  *
  * Validates that Elementor is already loaded.
  * Checks for basic plugin requirements, if one check fail don't continue,
  * if all check have passed include the plugin class.
  *
  * Fired by `plugins_loaded` action hook.
  *
  * @access public
  */
  public function init() {

    // Check if Elementor installed and activated
    if ( ! did_action( 'elementor/loaded' ) ) {
      add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
      return;
    }

    // Check for required Elementor version
    if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
      add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
      return;
    }

    // Check for required PHP version
    if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
      add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
      return;
    }

    // Once we get here, We have passed all validation checks so we can safely include our plugin
    require_once( 'plugin.php' );
  }

  /**
  * Admin notice
  *
  * Warning when the site doesn't have Elementor installed or activated.
  *
  * @access public
  */
  public function admin_notice_missing_main_plugin() {
    if ( isset( $_GET['activate'] ) ) {
      unset( $_GET['activate'] );
    }

    $message = sprintf(
      /* translators: 1: Plugin name 2: Elementor */
      esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-super-cat' ),
      '<strong>' . esc_html__( 'Elementor Super Cat', 'elementor-super-cat' ) . '</strong>',
      '<strong>' . esc_html__( 'Elementor', 'elementor-super-cat' ) . '</strong>'
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
  }

  /**
  * Admin notice
  *
  * Warning when the site doesn't have a minimum required Elementor version.
  *
  * @access public
  */
  public function admin_notice_minimum_elementor_version() {
    if ( isset( $_GET['activate'] ) ) {
      unset( $_GET['activate'] );
    }

    $message = sprintf(
      /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
      esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-super-cat' ),
      '<strong>' . esc_html__( 'Elementor Super Cat', 'elementor-super-cat' ) . '</strong>',
      '<strong>' . esc_html__( 'Elementor', 'elementor-super-cat' ) . '</strong>',
      self::MINIMUM_ELEMENTOR_VERSION
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
  }

  /**
  * Admin notice
  *
  * Warning when the site doesn't have a minimum required PHP version.
  *
  * @access public
  */
  public function admin_notice_minimum_php_version() {
    if ( isset( $_GET['activate'] ) ) {
      unset( $_GET['activate'] );
    }

    $message = sprintf(
      /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
      esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-super-cat' ),
      '<strong>' . esc_html__( 'Elementor Super Cat', 'elementor-super-cat' ) . '</strong>',
      '<strong>' . esc_html__( 'PHP', 'elementor-super-cat' ) . '</strong>',
      self::MINIMUM_PHP_VERSION
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
  }
}

// Instantiate Elementor_Super_Cat.
new Elementor_Super_Cat();
