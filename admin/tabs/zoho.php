<?php

class Super_Cat_Tab {

  public $option_prefix;
  public $plugin_name;
  private $zoho;

  public function __construct($plugin_name){
    $this->plugin_name = $plugin_name;
    $this->option_prefix = $plugin_name . "_zoho_";
    require_once(__DIR__ . '/../helpers/zoho-api.php');
    $this->zoho = new Zoho_Api_By_Gatto();
    add_action( 'admin_init', array( $this, 'setup_init' ) );

  }

  public function content(){

    ?>
    <br><br>
    <?php
    if($this->zoho->check_connection()){
      echo("<h3>Connessione a Zoho riuscita!</h3>");
    } else {
      ?>
      <p>|<?php echo($this->option_prefix); ?>|<br>
        Per collegarti alla API di Zoho vai a questo indirizzo: <a target="_blank" href="https://accounts.zoho.eu/developerconsole">https://accounts.zoho.eu/developerconsole</a>
        <br><br>
        Registra un utente usando:<br>
        <strong>Nome Client</strong>: a piacere<br>
        <strong>Dominio client</strong>: <?php echo($this->get_host()); ?><br>
        <strong>URI di reindirizzamento autorizzato</strong>: <?php echo($this->zoho->get_redirect_uri()); ?>
      </p>
      <p>
        Poi inserisci
      </p>
    <?php } ?>
    <form method="post" action="options.php">
      <?php
      settings_fields($this->plugin_name);
      do_settings_sections($this->plugin_name);
      submit_button();
      ?>
    </form>
    <?php
    if(!$this->zoho->check_connection() && get_option($this->option_prefix . "client_id") && get_option($this->option_prefix . "client_id") != ""){
      $link = 'https://accounts.zoho.eu/oauth/v2/auth?scope=ZohoCRM.users.ALL,ZohoCRM.modules.ALL,ZohoCRM.org.ALL,ZohoCRM.bulk.ALL&client_id='.get_option($this->option_prefix . "client_id").'&response_type=code&access_type=offline&redirect_uri='.$this->zoho->get_redirect_uri();
      ?>
      <p>
        Clicca questo link per procedere con l'attivazione:<br>
        <a href="<?php echo($link); ?>"><?php echo($link); ?></a>
      </p>
      <?php
    }
    //$this->zoho->list_module_fields();

  }

  public function setup_init(){
    add_settings_section(
      "sezione-zoho",
      "",
      null,
      $this->plugin_name
    );

    register_setting($this->plugin_name, $this->option_prefix . 'client_id');
    add_settings_field(
      $this->option_prefix . 'client_id',
      'ID client: ',
      array( $this, 'field_callback' ),
      $this->plugin_name, "sezione-zoho",
      array("id" => $this->option_prefix . 'client_id')
    );

    register_setting($this->plugin_name, $this->option_prefix . 'client_secret');
    add_settings_field(
      $this->option_prefix . 'client_secret',
      'Segreto client: ',
      array( $this, 'field_callback' ),
      $this->plugin_name, "sezione-zoho",
      array("id" => $this->option_prefix . 'client_secret')
    );

    register_setting($this->plugin_name, $this->option_prefix . 'client_email');
    add_settings_field(
      $this->option_prefix . 'client_email',
      'Email Client: ',
      array( $this, 'field_callback' ),
      $this->plugin_name, "sezione-zoho",
      array("id" => $this->option_prefix . 'client_email', "p" => "Questa è la mail che usi per accedere a Zoho CRM")
    );
  }


  public function field_callback ( $arguments ) {
    $id = $arguments["id"];
    echo '<input name="' . $id . '" id="' . $id . '" type="text" value="' .get_option($id). '" />';
    if(isset($arguments["p"])){
      echo('<p>' . $arguments["p"] . '</p>');
    }
  }

  private function get_host(){
    $url_parts = parse_url( get_site_url() );
    if ( $url_parts && isset( $url_parts['host'] ) ) {
      return $url_parts['host'];
    }
    return null;
  }

}
