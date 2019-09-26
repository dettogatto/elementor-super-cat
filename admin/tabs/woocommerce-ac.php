<?php

class Super_Cat_Tab {

    public $option_prefix;
    public $plugin_name;
    public $api_url;
    public $api_key;
    private $ac;

    public function __construct($plugin_name){
        require_once(__DIR__ . '/../helpers/activecampaign-api-v3.php');
        $this->option_prefix = $plugin_name . "_wooac_";
        $this->plugin_name = $plugin_name;
        $apidata = get_option("activecampaign_for_woocommerce_settings");
        $this->ac = new ActiveCampaign_API_Gatto($apidata["api_url"], $apidata["api_key"]);
        add_action( 'admin_init', array( $this, 'setup_init' ) );
    }

    private function should_load(){
        return (class_exists('Activecampaign_For_Woocommerce'));
    }

    public function content(){
        if(!$this->should_load()){
            echo("You have to install and activate the plugin <strong>ActiveCampaign for WooCommerce</strong> for this tab to work.");
            return false;
        }elseif(!$this->ac->get_all_tags()){
            echo("Something went wrong calling the ActiveCampaign API. Check your settings in the <strong>ActiveCampaign for WooCommerce</strong> plugin.");
            return false;
        }

        do_settings_sections($this->plugin_name);
        submit_button();

    }

    public function setup_init() {

        add_settings_section(
            "sezione",
            "Tags",
            array($this, 'section_callback'),
            $this->plugin_name
        );

        add_settings_section(
            "sezione_field",
            "Custom Field",
            array($this, 'section_field_callback'),
            $this->plugin_name
        );

        register_setting($this->plugin_name, $this->option_prefix . "tag_on_completed");
        add_settings_field(
            $this->option_prefix . "tag_on_completed",
            'Tag on Completed',
            array( $this, 'tag_callback' ), $this->plugin_name, "sezione",
            array(
                "id" => $this->option_prefix . "tag_on_completed",
                "p" => "This tag will be added to the costumer when an order status is set to completed"
            )
        );

        register_setting($this->plugin_name, $this->option_prefix . "tag_on_processing");
        add_settings_field(
            $this->option_prefix . "tag_on_processing",
            'Tag on Processing',
            array( $this, 'tag_callback' ), $this->plugin_name, "sezione",
            array(
                "id" => $this->option_prefix . "tag_on_processing",
                "p" => "This tag will be added to the costumer when an order status is set to processing"
            )
        );

        register_setting($this->plugin_name, $this->option_prefix . "tag_on_failed");
        add_settings_field(
            $this->option_prefix . "tag_on_failed",
            'Tag on Failed',
            array( $this, 'tag_callback' ), $this->plugin_name, "sezione",
            array(
                "id" => $this->option_prefix . "tag_on_failed",
                "p" => "This tag will be added to the costumer when an order status is set to failed"
            )
        );

        register_setting($this->plugin_name, $this->option_prefix . "tag_on_cancelled");
        add_settings_field(
            $this->option_prefix . "tag_on_cancelled",
            'Tag on Cancelled',
            array( $this, 'tag_callback' ), $this->plugin_name, "sezione",
            array(
                "id" => $this->option_prefix . "tag_on_cancelled",
                "p" => "This tag will be added to the costumer when an order status is set to cancelled"
            )
        );

        register_setting($this->plugin_name, $this->option_prefix . "tag_on_refunded");
        add_settings_field(
            $this->option_prefix . "tag_on_refunded",
            'Tag on Refunded',
            array( $this, 'tag_callback' ), $this->plugin_name, "sezione",
            array(
                "id" => $this->option_prefix . "tag_on_refunded",
                "p" => "This tag will be added to the costumer when an order status is set to refunded"
            )
        );

        register_setting($this->plugin_name, $this->option_prefix . "custom_field_list");
        add_settings_field(
            $this->option_prefix . "custom_field_list",
            'Custom Field',
            array( $this, 'custom_field_callback' ), $this->plugin_name, "sezione_field",
            array(
                "id" => $this->option_prefix . "custom_field_list",
            )
        );




    }

    public function section_callback( $arguments ) {

        echo('<p>Choose which tags should be added when the status of the order changes.<br />');
        echo('Leave blank to add none.<br />');
        echo('Only one of this tags will be active at the same time on one AC contact.<br />');
        echo('Note that by default the plugin <strong>ActiveCampaign for WooCommerce</strong> adds a <strong>woocommerce-customer</strong> tag upon first order completition.</p>');

    }

    public function section_field_callback( $arguments ) {

        echo('<p>Choose what custom field should be updated with the order cart list.<br>
        The field needs to be of type <strong>textarea</strong>.</p>');

    }

    public function tag_callback ( $arguments ) {

        $id = $arguments["id"];
        $curr_val = get_option($id);

        echo('<select name="' . $id . '" id="' . $id . '">');
        echo('<option value="-1">&nbsp;</option>');
        foreach ($this->ac->get_all_tags() as $tid => $tag) {
            $chk = "";
            if($tid == $curr_val){
                $chk = 'selected="selected"';
            }
            echo('<option value="'.$tid.'" '.$chk.'>'.$tag.'</option>');
        }
        echo('</select>');
        if(isset($arguments["p"])){
            echo('<p>'.$arguments["p"].'</p>');
        }
    }

    public function custom_field_callback ( $arguments ) {

        $id = $arguments["id"];
        $curr_val = get_option($id);

        echo('<select name="' . $id . '" id="' . $id . '">');
        echo('<option value="-1">&nbsp;</option>');
        foreach ($this->ac->get_fields("textarea") as $tid => $tag) {
            $chk = "";
            if($tid == $curr_val){
                $chk = 'selected="selected"';
            }
            echo('<option value="'.$tid.'" '.$chk.'>'.$tag.'</option>');
        }
        echo('</select>');
        if(isset($arguments["p"])){
            echo('<p>'.$arguments["p"].'</p>');
        }
    }


}
