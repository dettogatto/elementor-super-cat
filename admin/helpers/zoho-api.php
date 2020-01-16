<?php

require_once( __DIR__ . '/../../vendor/autoload.php' );
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;
use zcrmsdk\crm\crud\ZCRMRecord;

class Zoho_Api_By_Gatto {

    private $ZOHOconfigarray;
    private $ZOHOuseremail;
    private $connectionStatus;

    function __construct(){
        $this->ZOHOuseremail = get_option("elementor_zoho_client_email");
        $this->ZOHOconfigarray = array(
            "client_id" => get_option("elementor_zoho_client_id"),
            "client_secret" => get_option("elementor_zoho_client_secret"),
            "redirect_uri" => $this->get_redirect_uri(),
            "currentUserEmail" => $this->ZOHOuseremail,
            "token_persistence_path" => __DIR__ . '/tokens'
        );
        try{
            ZCRMRestClient::initialize($this->ZOHOconfigarray);
        } catch(Exception $e){
            return false;
        }
    }

    public function check_connection(){
        if($this->connectionStatus == "ko"){
            return false;
        } elseif($this->connectionStatus == "ok"){
            return true;
        }
        $rest=ZCRMRestClient::getInstance();//to get the rest client
        try{
            $orgIns=$rest->getOrganizationDetails()->getData();
        } catch (Exception $e) {
            $this->connectionStatus = "ko";
            return false;
        }
        $this->connectionStatus = "ok";
        return true;
    }

    public function get_tokens_from_grant($grant_token){
        $response = wp_remote_post("https://accounts.zoho.eu/oauth/v2/token", array(
            'body' => array(
                'grant_type' => 'authorization_code',
                'client_id' => get_option("elementor_zoho_client_id"),
                'client_secret' => get_option("elementor_zoho_client_secret"),
                'redirect_uri' => $this->get_redirect_uri(),
                'code' => $grant_token
            )
        ));
        if(!is_wp_error($response) && isset($response["body"])){
            $body = json_decode($response["body"]);
            if(isset($body->error)){
                echo("Zoho returned the error \"".$body->error."\". Try to go back to the Elementor-Zoho settings page and click the link again.");
                return false;
            } elseif(isset($body->refresh_token)){
                $oAuthClient = ZohoOAuth::getClientInstance();
                $userIdentifier = $this->ZOHOuseremail;
                $oAuthTokens = $oAuthClient->generateAccessTokenFromRefreshToken($body->refresh_token, $userIdentifier);
                return true;
            }
        } else {
            echo("Something's wrong with the response");
            return false;
        }
    }

    public function list_module_fields($module = "Leads"){
        // $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Leads");
        // //$response = $moduleIns->getAllFields();
        // var_dump($moduleIns);
        // //$fields = $response->getData();

        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Leads"); // To get module instance
        $response = $moduleIns->getAllFields();
        //$fields = $response->getData();
        var_dump($response);

    }

    public function create_contact($field_values){
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Leads"); // To get module instance
        $records = array();
        $record=ZCRMRecord::getInstance("Leads", null);  //To get ZCRMRecord instance
        //$record->setFieldValue("Email",$email);
        foreach($field_values as $k => $v){
            $record->setFieldValue($k, $v);
        }
        array_push($records, $record); // pushing the record to the array.
        $responseIn = $moduleIns->upsertRecords($records, array(),NULL); // updating the records.$trigger,$lar_id are optional
    }

    public function get_redirect_uri(){
        global $wp;
        return get_site_url() . "/wp-admin/admin-ajax.php?action=setup_zoho";
    }

}
