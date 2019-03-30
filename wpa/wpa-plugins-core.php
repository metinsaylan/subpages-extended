<?php

/* WPA Core Functions */

add_action( 'wpa_plugins_after_options', 'wpa_recommendations' );
function wpa_recommendations(){
  include_once( plugin_dir_path( __FILE__ ) . 'wpa-recommendations.php' );
}

/* Outputs Install/activate button for plugin */
function wpa_plugin_button( $slug, $plugin ){
  if ( ! function_exists('is_plugin_inactive')) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
  }

  $status = validate_plugin( $plugin );
  if( is_wp_error( $status ) ){
    if( array_key_exists( 'plugin_not_found', $status->errors ) ){

      $action = __( '🔰 Install' );
      $url = wp_nonce_url(
        add_query_arg(
          array(
            'action' => 'install-plugin',
            'plugin' => $slug
          ),
          self_admin_url( 'update.php' )
        ),
        'install-plugin_' . $slug
      );

      echo '<a class="fr button-secondary" target="_blank" href="' . $url . '">' . $action . '</a>';

    } else {
      $action = __( '❌ Error' );
    }
  } elseif( is_plugin_inactive( $plugin ) ){

    $action = __( '💎 Activate' ); 
    $url = wp_nonce_url(
      add_query_arg(
        array(
          'action' => 'activate',
          'plugin' => urlencode( $plugin )
        ),
        self_admin_url( 'plugins.php' )
      ),
      'activate-plugin_' . $plugin
    );
    
    echo '<a class="fr button button-secondary" target="_blank" href="' . $url . '" target="_parent">' . $action . '</a>';

  } else {
    $action = __( '✔️ Installed' );
    echo '<a class="fr button button-disabled">' . $action . '</a>';
  }
}