<?php

class Super_Cat_Tab {

    public $option_prefix;
    public $plugin_name;

    public function __construct($plugin_name){

        $this->option_prefix = $plugin_name . "_";
        $this->plugin_name = $plugin_name;
        add_action( 'admin_init', array( $this, 'setup_init' ) );

    }

    public function content(){

        do_settings_sections($this->plugin_name);

    }

    public function setup_init() {

        add_settings_section(
            "sezione",
            "Sezione Bella",
            array($this, 'section_callback'),
            $this->plugin_name
        );

        register_setting($this->plugin_name, $this->option_prefix . "opzionebella");

        add_settings_field(
            $this->option_prefix . "opzionebella",
            'Opzione Bella: ',
            array( $this, 'field_callback' ),
            $this->plugin_name, "sezione",
            array("id" => $this->option_prefix . "opzionebella")
        );

    }

    public function section_callback( $arguments ) {

        echo "Ciao gattino :3";

    }

    public function field_callback ( $arguments ) {

        $id = $arguments["id"];
        echo '<input name="' . $id . '" id="' . $id . '" type="text" class="gatto-input" value="' .get_option($id). '" />';

    }



}
