<?php

class Elementor_Super_Cat_Woocomm_AC {
    public $api_url;
    public $api_key;
    public $option_prefix;
    private $ac;

    public function __construct(){
        add_action( 'plugins_loaded', array($this, 'init') );
    }
    public function init(){
        require_once(__DIR__ . '/../helpers/activecampaign-api-v3.php');
        $this->option_prefix = "elementor_super_cat_wooac_";
        $this->ac = $this->get_api_data();
        if($this->ac){
            $this->hooks();
        }
        if($this->cf_ac_exists()){
            add_action( 'admin_post_gatto_abandoned_to_ac', array($this, 'send_abandoned_to_ac') );
        }
    }

    function send_abandoned_to_ac() {
        // Handle request then generate response using echo or leaving PHP and using HTML
        $terms = ["first_name", "last_name", "email", "order_status", "checkout_url", "coupon_code", "product_names", "cart_total"];
        $terms = array_flip($terms);
        array_walk($terms, function(&$a, $b) {
            $a = $_POST[$b];
        });
        $ac_terms = [
            "firstName" => $terms["first_name"],
            "lastName" => $terms["last_name"],
            "email" => $terms["email"]
        ];
        $contact = $this->ac->sync_contact($ac_terms);

        $this->set_the_tag($contact->id, "abandoned_wh");

        $field_id = get_option($this->option_prefix . "custom_field_list");
        if(intval($field_id) > -1){
            $this->ac->update_contact_field($contact->id, $field_id, str_replace(', ', "\n", str_replace(' &', ',', $terms["product_names"])));
        }

        header('Content-Type: application/json');
        echo(json_encode(array("status" => "success")));

    }

    private function get_api_data(){
        $api_url = get_option($this->option_prefix . "api_url");
        if(!$api_url && $this->ac4woo_exists() && $opt = get_option("activecampaign_for_woocommerce_settings")){
            $api_url = $opt["api_url"];
        }
        $api_key = get_option($this->option_prefix . "api_key");
        if(!$api_url && $this->ac4woo_exists() && $opt = get_option("activecampaign_for_woocommerce_settings")){
            $api_url = $opt["api_key"];
        }
        return new ActiveCampaign_API_Gatto($api_url, $api_key);
    }

    private function ac4woo_exists(){
        // Check if plugin Active Campaign for WooCommerce is active
        return class_exists('Activecampaign_For_Woocommerce');
    }

    private function cf_ac_exists(){
        // Check if plugin WooCommerce Cart Abandonment Recovery is active
        return class_exists('CARTFLOWS_CA_Loader') || class_exists('CARTFLOWS_CA_Settings');
    }

    public function hooks(){
        // Filter to bear with slow AC response times
        add_filter( 'http_request_args', array( $this, 'bear_with_slow_ac' ), 10, 2 );
        // Append actions to WooCommerce orders status change
        // add_action( 'elementor_pro/forms/new_record', array( $this, 'manipulate_form_submission' ), 10, 2 );
        add_action( 'woocommerce_order_status_completed', array( $this, 'on_order_completed' ), 10, 1 );
        add_action( 'woocommerce_order_status_failed', array( $this, 'on_order_failed' ), 10, 1 );
        add_action( 'woocommerce_order_status_cancelled', array( $this, 'on_order_cancelled' ), 10, 1 );
        add_action( 'woocommerce_order_status_refunded', array( $this, 'on_order_refunded' ), 10, 1 );
        add_action( 'woocommerce_order_status_processing', array( $this, 'on_order_processing' ), 10, 1 );
        add_action( 'woocommerce_order_status_on-hold', array( $this, 'on_order_on_hold' ), 10, 1 );
    }

    public function on_order_completed($order_id){
        $this->the_real_thing($order_id, "completed");
    }
    public function on_order_on_hold($order_id){
        $this->the_real_thing($order_id, "on_hold");
    }
    public function on_order_failed($order_id){
        $this->remove_order_from_ac($order_id);
        $this->the_real_thing($order_id, "failed");
    }
    public function on_order_cancelled($order_id){
        $this->remove_order_from_ac($order_id);
        $this->the_real_thing($order_id, "cancelled");
    }
    public function on_order_refunded($order_id){
        $this->remove_order_from_ac($order_id);
        $this->the_real_thing($order_id, "refunded");
    }
    public function on_order_processing($order_id){
        $this->the_real_thing($order_id, "processing");
    }

    public function set_the_tag($contact_id, $the_action){
        $all_actions = array(
            "completed",
            "on_hold",
            "failed",
            "cancelled",
            "refunded",
            "processing",
            "abandoned_wh"
        );
        foreach ($all_actions as $act) {
            $tag_id = get_option($this->option_prefix . "tag_on_" . $act);
            if(intval($tag_id) > -1){
                if($the_action == $act){
                    $this->ac->add_tag_to_contact($contact_id, $tag_id);
                }elseif(get_option($this->option_prefix . "tag_on_" . $act) != get_option($this->option_prefix . "tag_on_" . $the_action)){
                    $this->ac->remove_tag_from_contact($contact_id, $tag_id);
                }
            }
        }
    }

    public function the_real_thing($order_id, $the_action){
        $contact = $this->ac->sync_contact($this->get_contact_data_from_order($order_id));
        $this->set_the_tag($contact->id, $the_action);
        $this->ac->update_contact_field($contact->id, get_option($this->option_prefix . "custom_field_list"), $this->get_order_items_string($order_id));
    }





    public function remove_order_from_ac($order_id){
        if( !$this->ac4woo_exists() || !get_option($this->option_prefix . "delete_order") ){ return false; }
        $order = $this->ac->get_ecom_order_by_ext($order_id);
        if(!$order){
            return false; // Bail early
        }
        $this->ac->delete_ecom_order($order->id);
    }

    public function get_email_from_order($order_id){
        // try to get the mail from AC
        $order = $this->ac->get_ecom_order_by_ext($order_id);
        if($order){
            return $order->email;
        }

        // try to get the mail from WP user
        $order = wc_get_order( $order_id );
        $user = $order->get_user();
        if(isset($user->data->user_email)){
            return $user->data->user_email;
        }


        return $order->get_billing_email();
    }

    public function get_contact_data_from_order($order_id){
        // try to get the mail from AC
        $result = ["firstName" => "", "lastName" => "", "email" => ""];
        $order = $this->ac->get_ecom_order_by_ext($order_id);
        if($order){
            $result["email"] = $order->email;
        }

        // try to get the mail from WP user
        $order = wc_get_order( $order_id );
        if($order){
            $result["firstName"] = $order->get_billing_first_name();
            $result["lastName"] = $order->get_billing_last_name();
        }
        $user = $order->get_user();
        if(isset($user->data->user_email)){
            $result["email"] = $result["email"] ? $result["email"] : $user->data->user_email;
        }
        $result["email"] = $result["email"] ? $result["email"] : $order->get_billing_email();
        return $result;
    }

    public function get_order_items_string($order_id){
        $order = wc_get_order( $order_id );
        $items = $order->get_items();
        $res = array();
        foreach($items as $item => $values) {
            $res[] = $values['quantity'] . " x " . $values['name'];
        }
        $list = implode("\n", $res);
        return $list;
    }

    public function bear_with_slow_ac($r, $url){
        $r["timeout"] = 30;
        return $r;
    }


}
$elementor_webhooks = new Elementor_Super_Cat_Woocomm_AC();
