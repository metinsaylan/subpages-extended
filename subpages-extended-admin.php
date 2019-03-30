<?php

/* Inserts Subpages Extended admin menu */
add_action('admin_menu', 'subpages_widget_adminMenu');
function subpages_widget_adminMenu(){
	global $subpages_admin_page;

	if( is_admin() ){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'wpa-core', WP_PLUGIN_URL . '/subpages-extended/wpa/wpa-base.css' );
		wp_enqueue_style( 'subpages-extended', WP_PLUGIN_URL . '/subpages-extended/css/subpages.css' );
	};

	if ( ! empty( $_POST ) && check_admin_referer( 'subpages_widget_adminMenu', 'update-subpages-options' ) ) {
		if ( @$_GET['page'] == 'subpages-extended' ) {
			if ( @$_REQUEST['action'] && 'save' == $_REQUEST['action'] ) {
				if( isset( $_REQUEST['auto_insert'] ) ) {
					update_option( 'subpages_extended_auto_insert', $_REQUEST['auto_insert'] );
				} else { 
					update_option( 'subpages_extended_auto_insert', false ); 
				}
			}
		}
	}
	
	if ( function_exists('add_options_page') ) {
		$subpages_admin_page = add_options_page(
			__('Subpages Extended Options', 'subpages-extended'),
			__('Subpages Extended', 'subpages-extended'),
			'edit_themes',
			'subpages-extended',
			'subpages_widget_options_page'
		);
	}
}

/* Disable emoji scripts on Subpages Extended options page */
function wpa_se_disable_emojis() {
	global $subpages_admin_page;
	$screen = get_current_screen();
	if ( $screen->id != $subpages_admin_page )
		return;

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
}
add_action( 'current_screen', 'wpa_se_disable_emojis' );

/* Subpages Extended options page output */
function subpages_widget_options_page(){

	$title = "Subpages Extended Options";
	?>

<div class="wrap">
<h1><?php echo esc_html( $title ); ?></h1>

<?php do_action( 'wpa_plugins_after_title' ); ?>

<div class="nav">
	<a class="nav-link" href="https://metinsaylan.com/wordpress/plugins/subpages-widget/">Plugin page</a>
	<a class="nav-link" href="https://metinsaylan.com/docs/subpages-extended-help/">Usage</a>
	<a class="nav-link" href="https://metinsaylan.com/docs/subpages-extended-shortcode/">Shortcode</a>
	<a class="nav-link" href="http://metinsaylan.com/donate/">Donate</a>
</div>

<?php if ( isset($_GET['message']) && isset($messages[$_GET['message']]) ) { ?>
<div id="message" class="updated"><p><?php echo $messages[$_GET['message']]; ?></p></div>
<?php } ?>
<?php if ( isset($_GET['error']) && isset($errors[$_GET['error']]) ) { ?>
<div id="message" class="error"><p><?php echo $errors[$_GET['error']]; ?></p></div>
<?php } ?>

<form id="frmShailanDm" name="frmShailanDm" method="post" action="">

<table class="form-table">
<tr valign="top">
	<th scope="row"><label for="auto_insert"><?php _e('Auto-insert:'); ?></label></th>
	<td>
		<?php if( get_option( 'subpages_extended_auto_insert' ) ){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
		<input type="checkbox" name="auto_insert" id="auto_insert" value="true" <?php echo $checked; ?> />
		<span class="description">Auto-insert subpages list on blank pages.</span>
	</td>
</tr>
</table>

<?php wp_nonce_field( 'subpages_widget_adminMenu', 'update-subpages-options' ); ?>
<input type="hidden" name="action" value="save" />

<p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="Save Changes" /> <a href="http://metinsaylan.com/donate/" target="_blank" class="button-secondary">❤️ <?php _e('Donate'); ?></a> 
</p>

</form>
<?php do_action( 'wpa_plugins_after_options' ); ?>
</div>

<?php
}
