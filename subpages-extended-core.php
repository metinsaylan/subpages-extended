<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

    global $subpages_extended;
    require_once( MS_SE_PLUGIN_PATH . 'inc/class-wpa-plugin.php' );

    $subpages_extended = new WPA_Plugin(
        'Subpages Extended',
        'subpages-extended',
        MS_SE_PLUGIN_PATH
    );

    $subpages_extended_options = array(

        array(
            "name" => "General",
            "label" => __("General"),
            "type" => "section"
        ),
        
            array(  "name" => "Auto Insert",
            "desc" => "Enables automatic insert of subpage indexes on blank pages",
            "id" => "auto-insert",
            "std" => "on",
            "type" => "checkbox"),
        
        array( "type" => "close" )
    
    );

    $subpages_extended->options = $subpages_extended_options;
    $subpages_extended->options_nav = array( 
        array(
            'label' => 'Plugin Page',
            'link' => 'https://wpassist.me/plugins/subpages-widget/'
        ),
        array(
            'label' => 'Usage',
            'link' => 'https://wpassist.me/docs/subpages-extended-help/'
        ),
        array(
            'label' => 'Shortcode',
            'link' => 'https://wpassist.me/docs/subpages-extended-shortcode/'
        ),
        array(
            'label' => 'Donate',
            'link' => 'https://wpassist.me/donate/'
        )
    );




