<?php

class Super_Cat_Tab {

    public $option_prefix;
    public $plugin_name;

    public function __construct($plugin_name){

        $this->option_prefix = $plugin_name . "_ewh_";
        $this->plugin_name = $plugin_name;
        add_action( 'admin_init', array( $this, 'setup_init' ) );

    }

    public function content(){

        echo(
            '<p>
            Here you can set extra WebHooks for Elementor Forms.<br>
            Choose how many Webhooks you want to add, set the Elementor Form Name and the destination URL.<br>
            Field IDs will be used as param names instead of labes, to ease customisation.
            </p>'
        );
        do_settings_sections($this->plugin_name);
        submit_button();

    }

    public function setup_init() {

        add_settings_section(
            "sezione",
            " ",
            array($this, 'section_callback'),
            $this->plugin_name
        );

        register_setting( $this->plugin_name, $this->option_prefix . 'howmany', array('type' => 'integer', 'sanitize_callback' => array($this, 'sanitize_howmany')));

        add_settings_field(
            $this->option_prefix . 'howmany',
            'How many',
            array( $this, "field_howmany_callback" ),
            $this->plugin_name,
            "sezione",
            array("id" => $this->option_prefix . "howmany")
        );

        $howmany = get_option($this->option_prefix . 'howmany');
        for($i=0; $i < $howmany; $i++){

            register_setting( $this->plugin_name, $this->option_prefix . 'form_name_'.$i);
            register_setting( $this->plugin_name, $this->option_prefix . 'wh_url_'.$i);

            add_settings_field(
                $this->option_prefix . 'form_name_'.$i,
                __( 'Elementor Form Name', 'elementor-super-cat' ),
                array( $this, 'field_form_name_callback' ),
                $this->plugin_name,
                "sezione",
                array("id" => $this->option_prefix . 'form_name_'.$i )
            );

            add_settings_field(
                $this->option_prefix . 'wh_url_'.$i,
                __( 'Webhook URL', 'elementor-super-cat' ),
                array( $this, 'field_wh_url_callback' ),
                $this->plugin_name,
                "sezione",
                array("id" => $this->option_prefix . 'wh_url_'.$i )
            );

        }

        // Clean unused settings
        $flag = true;
        $c = $howmany;
        while($flag && $c < 10000){
            $curr = get_option( $this->option_prefix . 'form_name_'.$c );
            if(!$curr){$flag = false; break;}
            delete_option($this->option_prefix . 'form_name_'.$c);
            $c++;
        }

        $flag = true;
        $c = $howmany;
        while($flag && $c < 10000){
            $curr = get_option( $this->option_prefix . 'wh_url_'.$c );
            if(!$curr){$flag = false; break;}
            delete_option($this->option_prefix . 'wh_url_'.$c);
            $c++;
        }

    }

    public function section_callback( $args ) {

        return NULL;

    }

    public function field_howmany_callback ( $args ) {

        $howmany = get_option( $this->option_prefix . 'howmany' );
        $id = $args["id"];
        echo '<input class="gatto-input" type="number" min="0" max="100" style="width: 100%;" name="' . $id . '" id="' . $id . '" value="' . $howmany . '" /> <p>How many Webhooks to add</p> ';
        echo('<br><br><br>');
    }

    public function field_form_name_callback( $args ) {
        $id = $args["id"];
        $name = get_option( $id );
        echo '<input class="gatto-input" style="width: 100%;" name="' . $id . '" id="' . $id . '" value="' . $name . '" />';
    }

    public function field_wh_url_callback( $args ) {
        $id = $args["id"];
        $url = get_option( $id );
        echo '<input class="gatto-input" style="width: 100%;" name="' . $id . '" id="' . $id. '" value="' . $url . '" />';
        echo('<br><br><br>');
    }

    public function sanitize_howmany( $howmany ) {
        return intval($howmany);
    }



}
