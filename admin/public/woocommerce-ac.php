<?php

class Elementor_Super_Cat_Woocomm_AC {
    public $api_url;
    public $api_key;
    public $option_prefix;
    private $ac;

    public function __construct(){
        require_once(__DIR__ . '/../helpers/activecampaign-api-v3.php');
        $apidata = get_option("activecampaign_for_woocommerce_settings");
        $this->ac = new ActiveCampaign_API_Gatto($apidata["api_url"], $apidata["api_key"]);
        $this->option_prefix = "elementor_super_cat_wooac_";
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
    }

    public function on_order_completed($order_id){
        $this->the_real_thing($order_id, "completed");
    }
    public function on_order_failed($order_id){
        //$this->remove_order_from_ac($order_id);
        $this->the_real_thing($order_id, "failed");
    }
    public function on_order_cancelled($order_id){
        //$this->remove_order_from_ac($order_id);
        $this->the_real_thing($order_id, "cancelled");
    }
    public function on_order_refunded($order_id){
        //$this->remove_order_from_ac($order_id);
        $this->the_real_thing($order_id, "refunded");
    }
    public function on_order_processing($order_id){
        $this->the_real_thing($order_id, "processing");
    }

    public function the_real_thing($order_id, $the_action){
        $contact = $this->ac->get_contact_by_email($this->get_email_from_order($order_id));

        $all_actions = array(
            "completed",
            "failed",
            "cancelled",
            "refunded",
            "processing"
        );

        foreach ($all_actions as $act) {
            $tag_id = get_option($this->option_prefix . "tag_on_" . $act);
            if(intval($tag_id) > -1){
                if($the_action == $act){
                    $this->ac->add_tag_to_contact($contact->id, $tag_id);
                }else{
                    $this->ac->remove_tag_from_contact($contact->id, $tag_id);
                }
            }
        }

        $this->ac->update_contact_field($contact->id, get_option($this->option_prefix . "custom_field_list"), $this->get_order_items_string($order_id));

    }

    public function remove_order_from_ac($order_id){
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
        if(isset($user["data"]->user_email)){
            return $user["data"]->user_email;
        }


        return $order->get_billing_email();
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
$elementor_webhooks->hooks();
