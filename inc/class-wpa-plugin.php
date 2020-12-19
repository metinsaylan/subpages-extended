<?php

if( ! class_exists( 'WPA_Plugin' ) ){
    class WPA_Plugin{
        function __construct( $name, $id, $dir ){
            $this->name = $name;
            $this->id = $id;
            $this->plugin_dir = $dir;

            $this->options_page = $id;
            $this->settings_key = $id;

            $this->settings = array(); // current settings
            $this->options = array(); // array for settings page
            $this->options_nav = array();

            add_action('admin_menu', array( &$this, 'admin_header') );
        }

        function admin_header(){

            if ( @$_GET['page'] == $this->id ) {
        
                // Options page styles
                wp_enqueue_style( "wpa-plugin" . $this->id, plugins_url( '/css/wpa-plugin.css' , __FILE__ ) , false, "1.0", "all");
        
                if ( @$_REQUEST['action'] && 'save' == $_REQUEST['action'] ) {
        
                    // Save settings
                    $settings = $this->get_settings();
        
                    // Set updated values
                    foreach( $this->options as $option ){
                        if( array_key_exists( 'id', $option ) ){
                            if( $option['type'] == 'checkbox' && empty( $_REQUEST[ $option['id'] ] ) ) {
                                $settings[ $option['id'] ] = 'off';
                            } else {
                                $settings[ $option['id'] ] = $_REQUEST[ $option['id'] ];
                            }
                        }
                    }
        
                    // Save the settings
                    update_option( $this->settings_key, $settings );

                    header("Location: admin.php?page=" . $this->options_page . "&saved=true&message=1");
                    die;

                } else if( @$_REQUEST['action'] && 'reset' == $_REQUEST['action'] ) {
        
                    // Start a new settings array
                    $settings = array();
                    delete_option( $this->settings_key );
        
                    header("Location: admin.php?page=" . $this->options_page . "&reset=true&message=2");
                    die;
                }
        
            }

            $page = add_options_page(
                'Settings for ' . $this->name,
                $this->name,
                'manage_options',
                $this->options_page,
                array( &$this, 'options_page')
            );
        
            add_action( 'admin_print_styles-' . $page, array( &$this, 'header' ) );
        }

        /*  */
        function get_settings(){
            $settings = get_option( $this->settings_key );
        
            if( false === $settings ){
                // Options doesn't exist, install standard settings
                return $this->install_default_settings();
            } else { // Options exist, update if necessary
                return $settings;
            }
        }

        /* Updates a single option key */
        function update_setting( $key, $value ){
            $settings = $this->get_settings();
            $settings[$key] = $value;
            update_option( $this->settings_key, $settings );
        }

        /* Retrieves a single option */
        function get_setting( $key, $default = '' ) {
            $settings = $this->get_settings();
            if( array_key_exists( $key, $settings ) ){
                return $settings[ $key ];
            } else {
                return $default;
            }

            return false;
        }

        function install_default_settings(){
            // Create settings array
            $settings = array();
        
            // Set default values
            foreach( $this->options as $option ){
                if( array_key_exists( 'id', $option ) && array_key_exists( 'std', $option ) ) {
                    $settings[ $option[ 'id' ] ] = $option[ 'std' ];
                }
            }

            // Save the settings
            update_option( $this->settings_key, $settings );
            return $settings;
        }

        function update_settings( $current_settings ){
            //Add missing keys
            foreach( $this->options as $option ){
                if( array_key_exists ( 'id' , $option ) ){
                    // If this option has a default value
                    if( array_key_exists( 'std', $option ) && !array_key_exists( $option[ 'id' ], $current_settings ) ){
                        $current_settings[ $option['id'] ] = $option['std'];
                    }
                }
            }
        
            update_option( $this->settings_key, $current_settings );
            return $current_settings;
        }

        function options_page(){
            global $options, $current, $wpa_sidebar_template;
        
            $title = $this->name . " Options";
        
            $options = $this->options;
            $current = $this->get_settings();
        
            $messages = array(
                "1" => __( "Settings are saved.", "wpa-share" ),
                "2" => __( "Settings are reset.", "wpa-share" )
            );
        
            $options_nav = $this->options_nav;
        
            $wpa_sidebar_template = $this->plugin_dir . 'wpa-sidebar.php';
            include_once( $this->plugin_dir . "inc/wpa-options-template.php" );

        }

        function header(){

        }

    }
}