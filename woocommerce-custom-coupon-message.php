<?php
/*
Plugin Name: Woocommerce Custom Coupon Message
Description: Adds a meta box to the coupon edit screen where you can enter your custom message which will be displayed when the coupon is applied.
Version: 0.2
Author: Paul
Author URI: http://profiles.wordpress.org/come-back-home
Plugin URI: http://wordpress.org/extend/plugins/woocommerce-custom-coupon-message/
License: GPL2+
Text Domain: wccm-plugin
Domain Path: /languages/
*/

/* Getting the translation files */

add_action('plugins_loaded', 'wccm_init');

function wccm_init() {
  load_plugin_textdomain( 'wccm-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}

/* Fire our meta box setup function on the post editor screen. */

add_action( 'load-post.php', 'wccm_meta_boxes_setup' );

add_action( 'load-post-new.php', 'wccm_meta_boxes_setup' );

/* Meta box setup function. */
function wccm_meta_boxes_setup() {

    /* Add meta boxes on the 'add_meta_boxes' hook. */
    add_action( 'add_meta_boxes', 'wccm_add_post_meta_boxes' );

    /* Save post meta on the 'save_post' hook. */
    add_action( 'save_post', 'wccm_save_post_class_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function wccm_add_post_meta_boxes() {

    add_meta_box(
        'wccm-meta-box', // Unique ID
        esc_html__( 'Coustom Coupon Message', 'wccm-plugin' ), // Title
        'wccm_class_meta_box', // Callback function
        'shop_coupon', // Admin page (or post type)
        'side',  // Context
        'default' // Priority
        );
}

/* Display the post meta box. */
function wccm_class_meta_box( $object, $box ) { ?>

<?php wp_nonce_field( basename( __FILE__ ), 'wccm_class_nonce' ); ?>

<p> <label for="wccm-custom-message"><?php _e( "Your custom coupon message", 'wccm-plugin' ); ?></label></p>

<textarea name="wccm-custom-message" id="wccm-custom-message" rows="4" style="width:97%"><?php echo get_post_meta( $object->ID, 'wccm_custom_message', true  ); ?></textarea>

<p><label for="wccm-hide-default-message"><?php _e( "Hide default message?", 'wccm-plugin' ); ?></label></p>

<input type="checkbox" name="wccm-hide-default-message" id="wccm-hide-default-message" value="1" <?php checked( '1', get_post_meta( $object->ID, 'wccm_hide_default_message', true ) ); ?> />
<span><?php _e( "Check box to hide default message", 'wccm-plugin' ); ?></span>

<?php }

/* Save the meta box's post metadata. */
function wccm_save_post_class_meta( $post_id, $post ) {

    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['wccm_class_nonce'] ) || !wp_verify_nonce( $_POST['wccm_class_nonce'], basename( __FILE__ ) ) )
        return $post_id;

    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;

    /* Get the posted data  */
    $new_meta_value = ( isset( $_POST['wccm-custom-message'] ) ? $_POST['wccm-custom-message']  : '' );

    /* Get the meta key. */
    $meta_key = 'wccm_custom_message';

    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    /* If a new meta value was added and there was no previous value, add it. */
    if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );

    /* If the new meta value does not match the old value, update it. */
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
        update_post_meta( $post_id, $meta_key, $new_meta_value );

    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( '' == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, $meta_key, $meta_value );


    /* Get the posted data  */
    $new_meta_value2 = ( isset( $_POST['wccm-hide-default-message'] ) ? $_POST['wccm-hide-default-message']  : '' );

    /* Get the meta key. */
    $meta_key2 = 'wccm_hide_default_message';

    /* Get the meta value of the custom field key. */
    $meta_value2 = get_post_meta( $post_id, $meta_key2, true );

    /* If a new meta value was added and there was no previous value, add it. */
    if ( $new_meta_value2 && '' == $meta_value2 )
        add_post_meta( $post_id, $meta_key2, $new_meta_value2, true );

    /* If the new meta value does not match the old value, update it. */
    elseif ( $new_meta_value2 && $new_meta_value2 != $meta_value2 )
        update_post_meta( $post_id, $meta_key2, $new_meta_value2 );

    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( '' == $new_meta_value2 && $meta_value2 )
        delete_post_meta( $post_id, $meta_key2, $meta_value2 );
}

function custom_coupon_message ($coupon_code) {

    $the_coupon_custom_message = new WC_Coupon($coupon_code);

    if ( isset($the_coupon_custom_message->coupon_custom_fields['wccm_custom_message'][0] )) {

        echo "<p class='woocommerce_message'>" . $the_coupon_custom_message->coupon_custom_fields['wccm_custom_message'][0] ."</p>";

    }

    if ( isset( $the_coupon_custom_message->coupon_custom_fields['wccm_hide_default_message'][0] ) && isset($the_coupon_custom_message->coupon_custom_fields['wccm_custom_message'][0] )) {

     echo "\n" . '<style>div.woocommerce_message{display:none;}</style>';

 }

}

add_action ('woocommerce_applied_coupon', 'custom_coupon_message'); 

?>