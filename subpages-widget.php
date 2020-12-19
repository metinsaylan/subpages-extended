<?php
/**
 * Plugin Name: Subpages Extended
 * Plugin URI: https://wpassist.me/plugins/subpages-widget/
 * Description: Display a list of sub pages using widget, shortcode or auto-insert functionality.
 * Version: 1.6.6
 * Author: WPAssist
 * Author URI: https://wpassist.me/
 * Text Domain: subpages-extended
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

define( 'MS_SE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'MS_SE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

global $subpages_indexes;
global $subpages_extended;

include_once( 'subpages-extended-core.php' );

// Utilities
include_once( 'class-shailan-walker-page.php' );

// Widget & shortcode
include_once( 'subpages-extended.php' ); // the widget
include_once( 'subpages-extended-shortcode.php' ); // the shortcode

// Filters
include_once( 'subpages-extended-filter-auto-insert.php' );
include_once( 'subpages-extended-filter-page-title.php' );

if( is_admin() ){
  // Pages dropdown on widget options
  include_once( 'subpages-extended-util-dropdown-pages.php' );

  // Options & meta boxes
  include_once( 'subpages-menu-label-metabox.php' );

  // Settings links
  add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpa_sp_add_settings_link' );
  function wpa_sp_add_settings_link( $links ) {
    $links[] = '<a href="options-general.php?page=subpages-extended">Settings</a>';
    return $links;
  }
}

/* Thin wrap for wpa_plugin */
function get_subpages_extended_option( $key, $default ){
    global $subpages_extended;
    return $subpages_extended->get_setting( $key, $default );
}