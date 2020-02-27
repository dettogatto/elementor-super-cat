<?php

/**
* Plugin Name: Example Settings
* Description: Example Settings
* Version: 1.0
**/

class Elementor_Super_Cat_Admin {

    public $tabs = array(
        "welcome" => "Welcome",
        "headers-footers" => "H & F",
        "extra-webhooks" => "WebHooks",
        "woocommerce-ac" => "WooComm - AC"
    );
    public $default_tab = "welcome";
    public $current_tab;
    public $tab_handler;


    public function __construct() {
        //$this = [];

        if(is_admin()){
            // Hook into the admin menu
            $this->current_tab = (isset($_GET["tab"]) && isset($this->tabs[$_GET["tab"]])) ? $_GET["tab"] : $this->default_tab;
            $this->current_tab = (isset($_POST["super_cat_current_tab"]) && isset($this->tabs[$_POST["super_cat_current_tab"]])) ? $_POST["super_cat_current_tab"] : $this->current_tab;
            require_once(__DIR__ . '/tabs/' . $this->current_tab .'.php');
            $this->tab_handler = new Super_Cat_Tab("elementor_super_cat");
            add_action( 'admin_menu', array( $this, 'settings_page' ) );
            add_action( 'admin_enqueue_scripts', array($this , 'enqueue_cat_admin_css') );
        }
    }

    public function enqueue_cat_admin_css(){
        wp_enqueue_style( "elementor_super_cat", plugin_dir_url( __FILE__ ) . '../assets/css/admin.css', array(), time(), 'all' );
    }

    public function settings_page() {
        //Create the menu item and page
        $page_title = "Elementor Super Cat";
        $menu_title = "Super Cat";
        $capability = "manage_options";
        $slug = "elementor-super-cat";
        $callback = array( $this, 'settings_page_content' );
        add_menu_page( $page_title, $menu_title, $capability, $slug, $callback );
    }
    /* Create the page*/
    public function settings_page_content() { ?>
        <div class="wrap">
            <h1> Elementor Super Cat </h1>
            <div class="nav-tab-wrapper" style="margin-bottom: 30px;">
                <?php
                foreach($this->tabs as $k => $v){
                    ?>

                    <a class="nav-tab <?php echo $this->current_tab == $k || '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=elementor-super-cat&tab='.$k ); ?>"><?php _e( $v, 'elementor-super-cat' ); ?> </a>

                    <?php
                }
                ?>
            </div>
            <form method="post" action="options.php">
                <input type="hidden" name="super_cat_current_tab" value="<?php echo($this->current_tab) ?>">
                <?php
                settings_fields("elementor_super_cat");
                $this->tab_handler->content();
                ?>
            </form>
        </div>
        <?php
    }


}
