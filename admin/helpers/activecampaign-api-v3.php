<?php

class ActiveCampaign_API_Gatto {
    private $api_url;
    private $api_key;
    private $all_tags;
    private $raw_fields;

    public function __construct($url, $key){
        $this->api_url = $url;
        $this->api_key = $key;
    }

    public function add_tag_to_contact($contact_id, $tag_id){
        if(intval($tag_id) < 0){
            return false;
        }

        $body = array(
            "contactTag" => array(
                "contact" => $contact_id,
                "tag" => $tag_id
            )
        );

        $response = wp_remote_post(
            $this->api_url."/api/3/contactTags/",
            array(
                'headers' => $this->get_headers(true),
                'method' => 'POST',
                'body' => json_encode($body)
            )
        );
    }

    public function delete_ecom_order($id){
        $response = wp_remote_post(
            $this->api_url."/api/3/ecomOrders/".$id,
            array(
                'headers' => $this->get_headers(true),
                'method' => 'DELETE',
                'data_format' => 'body'
            )
        );
    }

    public function get_ecom_order_by_ext($external_id){
        $request = wp_remote_get(
            $this->api_url."/api/3/ecomOrders/?filters[externalid]=".$external_id,
            array('headers' => $this->get_headers())
        );

        if( is_wp_error( $request ) ) {
            return false; // Bail early
        }

        $body  = json_decode(wp_remote_retrieve_body($request));
        return $body->ecomOrders[0];

    }

    public function get_contact_by_email($email){
        $request = wp_remote_get(
            $this->api_url."/api/3/contacts/?email=".$email,
            array('headers' => $this->get_headers())
        );
        if( is_wp_error( $request ) ) {
            return false; // Bail early
        }
        $body  = json_decode(wp_remote_retrieve_body($request));
        return $body->contacts[0];
    }

    public function sync_contact($data){
        $body = array( "contact" => $data );
        $request = wp_remote_post(
            $this->api_url."/api/3/contact/sync",
            array(
                'headers' => $this->get_headers(true),
                'method' => 'POST',
                'body' => json_encode($body)
            )
        );
        if( is_wp_error( $request ) ) {
            return false; // Bail early
        }
        $body  = json_decode(wp_remote_retrieve_body($request));
        return $body->contact;
    }

    public function get_contact_tags($contact_id){
        $request = wp_remote_get(
            $this->api_url."/api/3/contacts/".$contact_id."/contactTags",
            array('headers' => $this->get_headers())
        );
        if( is_wp_error( $request ) ) {
            return false; // Bail early
        }
        $body = json_decode(wp_remote_retrieve_body($request));
        return $body->contactTags;
    }

    public function get_contact_fields($contact_id){
        $request = wp_remote_get(
            $this->api_url."/api/3/contacts/".$contact_id."/fieldValues",
            array('headers' => $this->get_headers())
        );
        if( is_wp_error( $request ) ) {
            return false; // Bail early
        }
        $body = json_decode(wp_remote_retrieve_body($request));

        return $body->fieldValues;
    }

    public function remove_tag_from_contact($contact_id, $tag_id){
        $tags = $this->get_contact_tags($contact_id);
        if(!$tags){
            return false;
        }
        $c_tag_id;
        foreach ($tags as $tkey => $tval) {
            if(intval($tval->tag) == intval($tag_id)){
                $c_tag_id = intval($tval->id);
                break;
            }
        }
        if(isset($c_tag_id)){
            $response = wp_remote_post(
                $this->api_url."/api/3/contactTags/".$c_tag_id,
                array(
                    'headers' => $this->get_headers(true),
                    'method' => 'DELETE'
                )
            );
        }
    }

    public function update_contact_field($contact_id, $field_id, $value){
        $body = array(
            "fieldValue" => array(
                "value" => $value,
                "contact" => $contact_id,
                "field" => $field_id
            )
        );


        $response = wp_remote_post(
            $this->api_url."/api/3/fieldValues/",
            array(
                'headers' => $this->get_headers(true),
                'method' => 'POST',
                'body' => json_encode($body)
            )
        );
    }


    public function get_all_tags() {
        if(isset($this->all_tags)){
            return $this->all_tags;
        }
        $request = wp_remote_get(
            $this->api_url."/api/3/tags?limit=100",
            array('headers' => $this->get_headers())
        );
        if( is_wp_error( $request ) ) {
            return false; // Bail early
        }

        $body  = json_decode(wp_remote_retrieve_body($request));
        if(!is_array($body->tags)){
            return false;
        }


        $result = [];
        foreach($body->tags as $tag){
            //if($tag->tagType == "contact"){
            $result[$tag->id] = $tag->tag;
            //}
        }
        asort($result);
        $this->all_tags = $result;
        return $this->all_tags;

    }

    public function get_fields($type = "") {
        if(!isset($this->raw_fields)){
            $request = wp_remote_get(
                $this->api_url."/api/3/fields?limit=100",
                array('headers' => $this->get_headers())
            );
            if( is_wp_error( $request ) ) {
                return false; // Bail early
            }
            $body  = json_decode(wp_remote_retrieve_body($request));
            if(!is_array($body->fields)){
                return false;
            }
            $this->raw_fields = $body->fields;
        }

        $result = [];
        foreach($this->raw_fields as $field){
            if($type == "" || $type == "all" || $type == $field->type){
                $result[$field->id] = $field->title;
            }
        }
        asort($result);
        return $result;

    }

    private function get_headers($with_json = false){
        $res = array('Api-Token' => $this->api_key);
        if($with_json){
            $res['Content-Type'] = 'application/json; charset=utf-8';
        }
        return $res;
    }



}
