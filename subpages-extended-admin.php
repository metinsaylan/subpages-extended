<?php

function subpages_widget_adminMenu(){

	if(is_admin()){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'subpages-extended', WP_PLUGIN_URL . '/subpages-extended/css/subpages.css' );
	};

	if ( @$_GET['page'] == 'subpages-extended' ) {
		if ( @$_REQUEST['action'] && 'save' == $_REQUEST['action'] ) {
			if(isset($_REQUEST['auto_insert'])) {
				update_option( 'subpages_extended_auto_insert', $_REQUEST['auto_insert'] );
			} else { update_option( 'subpages_extended_auto_insert', false ); }
		}
	}

	if (function_exists('add_options_page')) {
			$page = add_options_page(
        __('Subpages Extended Options', 'subpages-extended'),
        __('Subpages Extended', 'subpages-extended'),
        'edit_themes',
        'subpages-extended',
        'subpages_widget_options_page'
      );
	}
  
}

add_action('admin_menu', 'subpages_widget_adminMenu');

function subpages_widget_options_page(){

	$title = "Subpages Extended Options";
	?>

<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo esc_html( $title ); ?></h2>

<div class="nav"><small><a href="http://metinsaylan.com/projects/wordpress/subpages-extended/">Plugin page</a> | <a href="http://metinsaylan.com/docs/subpages-extended-help/">Usage</a> | <a href="http://metinsaylan.com/docs/subpages-extended-shortcode/">Shortcode</a> | <a href="http://metinsaylan.com/donate/">Donate</a> | <a href="http://metinsaylan.com/wordpress/">Get more widgets..</a></small></div>

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
		<?php if(get_option('subpages_extended_auto_insert')){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
		<input type="checkbox" name="auto_insert" id="auto_insert" value="true" <?php echo $checked; ?> />
		<span class="description">Auto-insert subpages list on empty pages.</span>
	</td>
</tr>
</table>

<input type="hidden" name="action" value="save" />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
</p>

</form>

</div>

<?php
}
