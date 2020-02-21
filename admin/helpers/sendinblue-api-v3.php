<?php

class Sendinblue_API_Gatto {
  private $api_url = "https://api.sendinblue.com/v3/";
  private $api_key = "";
  private $all_tags;
  private $all_lists;
  private $raw_fields;

  public function __construct($api_key){
    $this->api_key = $api_key;
  }

  public function prova(){
    return $this->api_key;
  }

  public function check_connection(){
    $request = $this->remote_post(
      $this->api_url."account",
      array('headers' => $this->get_headers(), "method" => "GET")
    );

    if($request){
      $rq = json_decode($request, true);
      if(isset($rq["code"])){
        return false;
      } else {
        return true;
      }
    }
    return false;
  }


  public function get_contact_by_email($email){
    $request = $this->remote_post(
      $this->api_url."contacts/".$email,
      array('headers' => $this->get_headers(), "method" => "GET")
    );
    if( !$request ) {
      return false; // Bail early
    }
    $body = json_decode($request, true);
    return $body;
  }

  public function sync_contact($data){

    /*
    * Data form:
    * [
    *   email: test@mirai.bay,
    *   listIds: [ 1, 2, 3 ],
    *   attributes: { NOME: foo, COGNOME: bar }
    * ]
    */

    $body = $data;
    $body["updateEnabled"] = true;

    $request = $this->remote_post(
      $this->api_url."contacts",
      array(
        'headers' => $this->get_headers(true),
        'method' => 'POST',
        'body' => json_encode($body)
      )
    );
    if( !$request ) {
      return false; // Bail early
    }
    $body = json_decode($request, true);
    return $body;
  }


  private function get_headers($with_json = false){
    $res = array('api-key: ' . $this->api_key);
    $res[] = "accept: application/json";
    if($with_json){
      $res[] = 'Content-Type: application/json; charset=utf-8';
    }
    return $res;
  }

  private function remote_post($url, $data){
    $body = isset($data["body"]) ? $data["body"] : [];
    $method = isset($data["method"]) ? $data["method"] : "POST";
    $headers = isset($data["headers"]) ? $data["headers"] : [];

    $ch = curl_init();

    curl_setopt_array($ch, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => $headers,
      CURLOPT_POSTFIELDS => $body
    ));

    $server_output = curl_exec($ch);
    curl_close ($ch);
    return $server_output;

  }
}
