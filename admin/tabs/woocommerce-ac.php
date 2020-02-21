<?php

class Super_Cat_Tab {

    public $option_prefix;
    public $plugin_name;
    private $api_url;
    private $api_key;
    private $ac;
    private $gatto_webhook_url;

    public function __construct($plugin_name){
        require_once(__DIR__ . '/../helpers/activecampaign-api-v3.php');
        $this->option_prefix = $plugin_name . "_wooac_";
        $this->plugin_name = $plugin_name;
        $this->gatto_webhook_url = admin_url( 'admin-post.php?action=gatto_abandoned_to_ac' );
        $this->ac = $this->get_api_data();
        add_action( 'admin_init', array( $this, 'setup_init' ) );
    }

    private function get_api_data(){
        $this->api_url = get_option($this->option_prefix . "api_url");
        if(!$this->api_url && $this->ac4woo_exists() && $opt = get_option("activecampaign_for_woocommerce_settings")){
            $this->api_url = $opt["api_url"];
        }
        $this->api_key = get_option($this->option_prefix . "api_key");
        if(!$this->api_key && $this->ac4woo_exists() && $opt = get_option("activecampaign_for_woocommerce_settings")){
            $this->api_key = $opt["api_key"];
        }
        return new ActiveCampaign_API_Gatto($this->api_url, $this->api_key);
    }

    private function ac4woo_exists(){
        return class_exists('Activecampaign_For_Woocommerce');
    }

    private function cf_ac_exists(){
        // Check if plugin WooCommerce Cart Abandonment Recovery is active
        return class_exists('CARTFLOWS_CA_Loader') || class_exists('CARTFLOWS_CA_Settings');
    }

    public function content(){
        do_settings_sections($this->plugin_name);
        submit_button();
    }

    public function setup_init() {

        add_settings_section(
            "sezione_api",
            "Api Data",
            array($this, 'section_api_callback'),
            $this->plugin_name
        );


        if($this->ac->get_all_tags()){
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

            if($this->ac4woo_exists()){
                add_settings_section(
                    "sezione_delete",
                    "Delete Order",
                    array($this, 'section_delete_callback'),
                    $this->plugin_name
                );
            } else {
                add_settings_section(
                    "sezione_delete_disabled",
                    "Delete Order",
                    array($this, 'section_delete_disabled'),
                    $this->plugin_name
                );
            }

            if($this->cf_ac_exists()){
                add_settings_section(
                    "sezione_abandoned",
                    "Abandoned Cart",
                    array($this, 'section_abandoned_callback'),
                    $this->plugin_name
                );
            } else {
                add_settings_section(
                    "sezione_abandoned_disabled",
                    "Abandoned Cart",
                    array($this, 'section_abandoned_disabled'),
                    $this->plugin_name
                );
            }
        }

        register_setting($this->plugin_name, $this->option_prefix . "api_url");
        add_settings_field(
            $this->option_prefix . "api_url",
            'API URL',
            array( $this, 'api_url_callback' ), $this->plugin_name, "sezione_api",
            array(
                "id" => $this->option_prefix . "api_url",
            )
        );

        register_setting($this->plugin_name, $this->option_prefix . "api_key");
        add_settings_field(
            $this->option_prefix . "api_key",
            'API Key',
            array( $this, 'api_key_callback' ), $this->plugin_name, "sezione_api",
            array(
                "id" => $this->option_prefix . "api_key",
            )
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

        register_setting($this->plugin_name, $this->option_prefix . "tag_on_on_hold");
        add_settings_field(
            $this->option_prefix . "tag_on_on_hold",
            'Tag for On-Hold',
            array( $this, 'tag_callback' ), $this->plugin_name, "sezione",
            array(
                "id" => $this->option_prefix . "tag_on_on_hold",
                "p" => "This tag will be added to the costumer when an order status is set to on-hold"
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

        register_setting($this->plugin_name, $this->option_prefix . "delete_order");
        add_settings_field(
            $this->option_prefix . "delete_order",
            'Activate',
            array( $this, 'delete_order_callback' ), $this->plugin_name, "sezione_delete",
            array(
                "id" => $this->option_prefix . "delete_order",
            )
        );

        register_setting($this->plugin_name, $this->option_prefix . "set_cf_webhook");
        register_setting($this->plugin_name, $this->option_prefix . "cf_webhook_backup");
        add_settings_field(
            "set_cf_webhook",
            'Take over WH',
            array( $this, 'set_cf_webhook_callback' ), $this->plugin_name, "sezione_abandoned",
            array(
                "id" => $this->option_prefix . "set_cf_webhook",
            )
        );

        register_setting($this->plugin_name, $this->option_prefix . "tag_on_abandoned_wh");
        add_settings_field(
            $this->option_prefix . "tag_on_abandoned_wh",
            'Tag on Abandoned Cart',
            array( $this, 'tag_abandoned_callback' ), $this->plugin_name, "sezione_abandoned",
            array(
                "id" => $this->option_prefix . "tag_on_abandoned_wh",
                "p" => "This tag will be added to the costumer when the order found to be abandoned by <strong>ActiveCampaign for WooCommerce</strong>"
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

    public function section_api_callback( $arguments ) {
        echo('<p>Fill in the ActiveCampaign API data.<br>
        You find them in Settings -> Developer -> API Access.</p>');

    }

    public function section_delete_callback( $arguments ) {
        echo('<p>If active it will remove the order from ActiveCampaign DeepData
        when order is failed/cancelled/refunded so you can get the correct LTV.</p>');
    }

    public function section_delete_disabled( $arguments ) {
        $this->section_delete_callback( $arguments );
        echo('This functionality needs the plugin <strong>ActiveCampaign for WooCommerce</strong> to work.</p>');

    }

    public function section_abandoned_callback( $arguments ) {
        echo('<p><strong>EXPERIMENTAL</strong>: This function does not seem to work right now, due to Wordpress limitations.</p>');
        echo('<p>Here you can set the data to send to ActiveCampaign upon cart abandonment.<br></p>');
    }

    public function section_abandoned_disabled( $arguments ) {
        $this->section_abandoned_callback( $arguments );
        echo('This functionality needs the plugin <strong>WooCommerce Cart Abandonment Recovery</strong> by CartFlows Inc. to work.</p>');

    }

    public function set_cf_webhook_callback ( $arguments ) {
        $id = $arguments["id"];
        $curr_val = get_option($id);
        $checked = ($curr_val ? 'checked' : '' );

        $p = "";


        if($curr_val){
            if(get_option( 'wcf_ca_zapier_cart_abandoned_webhook' ) != $this->gatto_webhook_url){
                update_option($this->option_prefix . "cf_webhook_backup", get_option( 'wcf_ca_zapier_cart_abandoned_webhook' ));
                update_option('wcf_ca_zapier_cart_abandoned_webhook', $this->gatto_webhook_url);
                $p = '<p>The value has been overwritten! You are now using <strong>SuperCat</strong> to send data to <strong>ActiveCampaign</strong>!</p>';
            } else {
                $p = '<p>You are using <strong>SuperCat</strong> to send data to <strong>ActiveCampaign</strong>!</p>';
            }
        } else {
            if(get_option( 'wcf_ca_zapier_cart_abandoned_webhook' ) == $this->gatto_webhook_url){
                update_option('wcf_ca_zapier_cart_abandoned_webhook', get_option($this->option_prefix . "cf_webhook_backup"));
                update_option($this->option_prefix . "cf_webhook_backup", "");
                $p = '<p>The value has been restored!</p>';
            } else {
                $p = '<p>Activate the checkbox to overwrite <strong>WooCommerce Cart Abandonment Recovery</strong> webhook url and use <strong>SuperCat</strong> instead.</p>';
            }
        }

        echo('<input class="gatto-input" type="checkbox" name="' . $id . '" id="' . $id . '" value="1" '.$checked.' />');
        echo('<input class="gatto-input" type="hidden" name="'.$this->option_prefix . "cf_webhook_backup".'" value="'.get_option($this->option_prefix . "cf_webhook_backup").'">');
        echo('&nbsp; &nbsp; &nbsp; Current webhook: <strong>'.(
            get_option( 'wcf_ca_zapier_cart_abandoned_webhook' ) == $this->gatto_webhook_url ? "SuperCat" : get_option( 'wcf_ca_zapier_cart_abandoned_webhook' )
            ).'</strong>');
            echo($p);
        }

        public function api_url_callback ( $arguments ) {
            $id = $arguments["id"];
            echo('<input class="gatto-input" type="text" name="' . $id . '" id="' . $id . '" value="'.$this->api_url.'" />');
            if(isset($arguments["p"])){
                echo('<p>'.$arguments["p"].'</p>');
            }
        }

        public function api_key_callback ( $arguments ) {
            $id = $arguments["id"];
            echo('<input class="gatto-input" type="text" name="' . $id . '" id="' . $id . '" value="'.$this->api_key.'" />');
            if(isset($arguments["p"])){
                echo('<p>'.$arguments["p"].'</p>');
            }
        }

        public function delete_order_callback( $arguments ){
            $id = $arguments["id"];
            $curr_val = get_option($id);
            $checked = ($curr_val ? 'checked' : '' );
            echo('<input class="gatto-input" type="checkbox" name="' . $id . '" id="' . $id . '" value="1" '.$checked.' />');
        }

        public function tag_callback ( $arguments ) {
            $id = $arguments["id"];
            $curr_val = get_option($id);

            echo('<select class="gatto-input" name="' . $id . '" id="' . $id . '">');
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

        public function tag_abandoned_callback ( $arguments ) {
            if(get_option( 'wcf_ca_zapier_cart_abandoned_webhook' ) == $this->gatto_webhook_url){
                $this->tag_callback($arguments);
            } else {
                echo('<p>This will be available if you overwrite the webhook address with <strong>SuperCat</strong></p>');
            }
        }

        public function custom_field_callback ( $arguments ) {
            $id = $arguments["id"];
            $curr_val = get_option($id);

            echo('<select class="gatto-input" name="' . $id . '" id="' . $id . '">');
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
