<div class="wpa_options wpa_options-page">
    <div class="wpa_options-c1">
        <div class="wpa_options wpa_options-wrapper">
<?php

/**
 * This template uses plugin options array to render options page.
 * - $options_nav   : Navigation menu array ( array( array( label, link ) ) )
 * - $options       : Array of options to be rendered
 */

global $wpa_sidebar_template;

/* Title */
echo '<h2>' . esc_html( $title ) . '</h2>'; 

/* Notifications */
if ( isset( $_GET['message'] ) && isset( $messages[ $_GET['message'] ] ) ) { 
    echo '<div id="message" class="updated fade"><p>' . $messages[$_GET['message']] . '</p></div>';
}

/* Errors */
if ( isset( $_GET['error'] ) && isset( $errors[$_GET['error']] ) ) { 
    echo '<div id="message" class="error fade"><p>' . $errors[$_GET['error']] . '</p></div>';
}

/* Nav Menu */
if( !empty( $options_nav ) ){ 
    echo '<nav class="wpa_options-nav">';
    foreach( $options_nav as $menu_item ){
        echo '<a class="nav-link" href="' . $menu_item[ 'link' ] . '">' . $menu_item[ 'label' ] . '</a>';
    }
    echo '</nav>';
} 

echo '<form method="post">';

/* Options */
foreach ( $options as $field ) {
    switch ( $field['type'] ) {

        case 'section': 
            echo '<h3 id="' . sanitize_title( $field['name'] ) . '" class="section-title">' . $field['label'] . '</h3>';

        case 'open': 
            echo '<div class="wpa_options-section cf">'; 
            break;

        case 'close': 
            echo '<div class="wpa_options-section-footer">';
            submit_button( 'Save Changes', 'primary', 'save', false );
            echo '<input type="hidden" name="action" value="save" />';
            echo '</div><!-- section-footer -->';
            echo '</div><!-- section -->';
            break;

        case 'paragraph': 
            echo '<div class="wpa_options-p cf">' . $field['desc'] . '</div>';
            break;

        case 'text': 
        
    ?><div class="wpa_options-input wpa_text cf">
        <label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label> <input name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" type="<?php echo $field['type']; ?>" value="<?php if ( isset($current[ $field['id'] ]) && $current[ $field['id'] ] != "") { echo esc_html(stripslashes($current[ $field['id'] ] ) ); } ?>" /> </div><?php

            break;

        case 'textarea':

    ?> <div class="wpa_options-input wpa_textarea cf">
        <label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>
        <textarea name="<?php echo $field['id']; ?>" type="<?php echo $field['type']; ?>" cols="" rows=""><?php if ( $current[ $field['id'] ] != "") { echo stripslashes( $current[ $field['id'] ] ); } else { echo $field['std']; } ?></textarea> <?php 
            
            break;

        case 'select':

    ?> <div class="wpa_options-input wpa_select cf">
        <label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>
    <select name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>">
    <?php foreach ($field['options'] as $key=>$name) { ?>
            <option <?php if ( isset($current[ $field['id'] ]) && $current[ $field['id'] ] == $key) { echo 'selected="selected"'; } ?> value="<?php echo $key;?>"><?php echo $name; ?></option><?php } ?>
    </select> </div> <?php

            break;

        case "checkbox":

            $current_value = array_key_exists( $field['id'], $current ) ? $current[ $field['id'] ] : 'off';

    ?> <div class="wpa_options-input wpa_checkbox cf">
        <label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>

        <input type="checkbox" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="on" <?php checked( $current_value, "on") ?> />

        <small><?php echo $field['desc']; ?></small> </div> <?php 
            break;

        case "divider":
            echo '<hr class="wpa_divider" />';
            break;
    }
}
?>
</form> 

<div class="dz">
<form method="post">
    <p><input type="hidden" name="action" value="reset" />
    Danger Zone: <?php submit_button( 'Reset Settings', 'secondary', 'save', false ); ?>
    <br /><small>You will lose all your current settings.</small>
</p>
</form>
</div>

<?php if(WP_DEBUG){ ?>
    <div class="debug">
    <h3>Debug information</h3>
    <p>Note: You are seeing this because your <code>WP_DEBUG</code> variable is set to <code>true</code>.</p>
    <pre><?php print_r( $current ) ?></pre>
    </div><!-- debug -->
<?php } ?>


</div><!-- wrapper -->
</div><!-- c1 -->
<div class="wpa_options-c2">
    <div class="sb"><?php 
        include_once( $wpa_sidebar_template ); 
    ?></div>
</div><!-- c2 -->
</div><!-- page -->
