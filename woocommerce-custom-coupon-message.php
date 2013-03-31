<?php
/*
Plugin Name: Woocommerce Custom Coupon Message
Description: This plugin adds a meta box to the coupon edit screen where you can make your own customized coupon message.
Version: 0.4
Author: Paul
Author URI: http://profiles.wordpress.org/come-back-home
Plugin URI: http://wordpress.org/extend/plugins/woocommerce-custom-coupon-message/
License: GPL2+
Text Domain: wccm-plugin
Domain Path: /languages/
*/


/*----------------------------------------------------------
Getting the translation files
------------------------------------------------------------*/

add_action('plugins_loaded', 'wccm_init');

function wccm_init() {
  load_plugin_textdomain( 'wccm-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}


/*----------------------------------------------------------
Fire our meta box setup function on the post (coupon) editor screen
------------------------------------------------------------*/

add_action( 'load-post.php', 'wccm_meta_boxes_setup' );

add_action( 'load-post-new.php', 'wccm_meta_boxes_setup' );


/*----------------------------------------------------------
Meta box setup function
------------------------------------------------------------*/

function wccm_meta_boxes_setup() {

  /* Add meta boxes on the 'add_meta_boxes' hook */
  add_action( 'add_meta_boxes', 'wccm_add_post_meta_boxes' );

  /* Save post meta on the 'save_post' hook */
  add_action( 'save_post', 'wccm_save_post_class_meta', 10, 2 );
}


/*----------------------------------------------------------
Create meta box for the coupon edit screen
------------------------------------------------------------*/

function wccm_add_post_meta_boxes() {

  add_meta_box(
        'wccm-meta-box', // Unique ID
        esc_html__( 'Custom Coupon Message', 'wccm-plugin' ), // Title
        'wccm_class_meta_box', // Callback function
        'shop_coupon', // Post type (Coupon)
        'side',  // Context
        'default' // Priority
        );
}


/*----------------------------------------------------------
Display the post meta box in the coupon edit screen
------------------------------------------------------------*/

function wccm_class_meta_box( $object, $box ) { ?>

<?php wp_nonce_field( basename( __FILE__ ), 'wccm_class_nonce' ); ?>

<?php /* Custom coupon message textarea */ ?>
<p><label for="wccm-custom-message"><?php _e( "Your custom coupon message", 'wccm-plugin' ); ?></label></p>
<textarea name="wccm-custom-message" id="wccm-custom-message" rows="4" style="width:97%"><?php echo get_post_meta( $object->ID, 'wccm_custom_message', true  ); ?></textarea>

<?php /* Message style select */ ?>
<p><label for="wccm-custom-message-style"><?php _e( "Choose a message style (optional)", 'wccm-plugin' ); ?></label></p>
<select name ="wccm-custom-message-style" id="wccm-custom-message-style">
  <option value=""></option>
  <option value="wccm-blue" <?php selected( 'wccm-blue', get_post_meta( $object->ID, 'wccm_custom_message_style', true) ); ?> ><?php _e( "Blue", 'wccm-plugin' ); ?></option>
  <option value="wccm-lightblue" <?php selected( 'wccm-lightblue', get_post_meta( $object->ID, 'wccm_custom_message_style', true) ); ?> ><?php _e( "Light blue", 'wccm-plugin' ); ?></option>
  <option value="wccm-green" <?php selected( 'wccm-green', get_post_meta( $object->ID, 'wccm_custom_message_style', true) ); ?> ><?php _e( "Green", 'wccm-plugin' ); ?></option>
  <option value="wccm-lightgreen" <?php selected( 'wccm-lightgreen', get_post_meta( $object->ID, 'wccm_custom_message_style', true) ); ?> ><?php _e( "Light green", 'wccm-plugin' ); ?></option>
  <option value="wccm-grey" <?php selected( 'wccm-grey', get_post_meta( $object->ID, 'wccm_custom_message_style', true) ); ?> ><?php _e( "Grey", 'wccm-plugin' ); ?></option>
  <option value="wccm-orange" <?php selected( 'wccm-orange', get_post_meta( $object->ID, 'wccm_custom_message_style', true) ); ?> ><?php _e( "Orange", 'wccm-plugin' ); ?></option>
  <option value="wccm-pink" <?php selected( 'wccm-pink', get_post_meta( $object->ID, 'wccm_custom_message_style', true) ); ?> ><?php _e( "Pink", 'wccm-plugin' ); ?></option>
  <option value="wccm-purple" <?php selected( 'wccm-purple', get_post_meta( $object->ID, 'wccm_custom_message_style', true) ); ?> ><?php _e( "Purple", 'wccm-plugin' ); ?></option>
  <option value="wccm-lightpurple" <?php selected( 'wccm-lightpurple', get_post_meta( $object->ID, 'wccm_custom_message_style', true) ); ?> ><?php _e( "Light purple", 'wccm-plugin' ); ?></option>   
</select>

<?php /* CSS class textfield */ ?>
<p><label for="wccm-custom-message-class"><?php _e( "Or use your own CSS class (also optional)", 'wccm-plugin' ); ?></label></p>
<input type="text" name="wccm-custom-message-class" id="wccm-custom-message-class" value="<?php echo get_post_meta( $object->ID, 'wccm_custom_message_class', true  ); ?>" />

<?php /* Hide message checkbox */ ?>
<p><label for="wccm-hide-default-message"><?php _e( "Hide default message?", 'wccm-plugin' ); ?></label></p>
<input type="checkbox" name="wccm-hide-default-message" id="wccm-hide-default-message" value="1" <?php checked( '1', get_post_meta( $object->ID, 'wccm_hide_default_message', true ) ); ?> />
<span><?php _e( "Check box to hide default message", 'wccm-plugin' ); ?></span>

<?php }


/*----------------------------------------------------------
Save the meta box's post metadata
------------------------------------------------------------*/

function wccm_save_post_class_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding */
  if ( !isset( $_POST['wccm_class_nonce'] ) || !wp_verify_nonce( $_POST['wccm_class_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;


  /* Get the posted data for the custom coupon message */
  $new_meta_value_message = esc_textarea(( isset( $_POST['wccm-custom-message'] ) ? $_POST['wccm-custom-message']  : '' ));

  /* Get the meta key */
  $meta_key_message = 'wccm_custom_message';

  /* Get the meta value of the custom field key */
  $meta_value_message = get_post_meta( $post_id, $meta_key_message, true );

  /* If a new meta value was added and there was no previous value, add it */
  if ( $new_meta_value_message && '' == $meta_value_message )
    add_post_meta( $post_id, $meta_key_message, $new_meta_value_message, true );

  /* If the new meta value does not match the old value, update it */
  elseif ( $new_meta_value_message && $new_meta_value_message != $meta_value_message )
    update_post_meta( $post_id, $meta_key_message, $new_meta_value_message );

  /* If there is no new meta value but an old value exists, delete it */
  elseif ( '' == $new_meta_value_message && $meta_value_message )
    delete_post_meta( $post_id, $meta_key_message, $meta_value_message );


  /* Get the posted data for the selected style  */
  $new_meta_value_style = ( isset( $_POST['wccm-custom-message-style'] ) ? $_POST['wccm-custom-message-style']  : '' );

  /* Get the meta key */
  $meta_key_style = 'wccm_custom_message_style';

  /* Get the meta value of the custom field key */
  $meta_value_style = get_post_meta( $post_id, $meta_key_style, true );

  /* If a new meta value was added and there was no previous value, add it */
  if ( $new_meta_value_style && '' == $meta_value_style )
    add_post_meta( $post_id, $meta_key_style, $new_meta_value_style, true );

  /* If the new meta value does not match the old value, update it */
  elseif ( $new_meta_value_style && $new_meta_value_style != $meta_value_style )
    update_post_meta( $post_id, $meta_key_style, $new_meta_value_style);

  /* If there is no new meta value but an old value exists, delete it */
  elseif ( '' == $new_meta_value_style && $meta_value_style )
    delete_post_meta( $post_id, $meta_key_style, $meta_value_style );


  /* Get the posted data for the custom class  */
  $new_meta_value_class = sanitize_html_class( ( isset( $_POST['wccm-custom-message-class'] ) ? $_POST['wccm-custom-message-class']  : '' ) );

  /* Get the meta key. */
  $meta_key_class= 'wccm_custom_message_class';

  /* Get the meta value of the custom field key */
  $meta_value_class = get_post_meta( $post_id, $meta_key_class, true );

  /* If a new meta value was added and there was no previous value, add it */
  if ( $new_meta_value_class && '' == $meta_value_class )
    add_post_meta( $post_id, $meta_key_class, $new_meta_value_class, true );

  /* If the new meta value does not match the old value, update it */
  elseif ( $new_meta_value_class && $new_meta_value_class != $meta_value_class )
    update_post_meta( $post_id, $meta_key_class, $new_meta_value_class );

  /* If there is no new meta value but an old value exists, delete it */
  elseif ( '' == $new_meta_value_class && $meta_value_class )
    delete_post_meta( $post_id, $meta_key_class, $meta_value_class );


  /* Get the posted data value for hiding the default message  */
  $new_meta_value_hide = ( isset( $_POST['wccm-hide-default-message'] ) ? $_POST['wccm-hide-default-message']  : '' );

  /* Get the meta key */
  $meta_key_hide= 'wccm_hide_default_message';

  /* Get the meta value of the custom field key */
  $meta_value_hide = get_post_meta( $post_id, $meta_key_hide, true );

  /* If a new meta value was added and there was no previous value, add it */
  if ( $new_meta_value_hide && '' == $meta_value_hide )
    add_post_meta( $post_id, $meta_key_hide, $new_meta_value_hide, true );

  /* If the new meta value does not match the old value, update it */
  elseif ( $new_meta_value_hide && $new_meta_value_hide != $meta_value_hide )
    update_post_meta( $post_id, $meta_key_hide, $new_meta_value_hide );

  /* If there is no new meta value but an old value exists, delete it */
  elseif ( '' == $new_meta_value_hide && $meta_value_hide )
    delete_post_meta( $post_id, $meta_key_hide, $meta_value_hide );
}

/*----------------------------------------------------------
Display the custom message with/without styles
------------------------------------------------------------*/

function custom_coupon_message ($coupon_code) {

  /* Get the coupon object */
  $the_coupon_custom_message = new WC_Coupon($coupon_code);


  /* Checking which settings are applied to the coupon */

  /* Is there a custom coupon message? */
  if ( isset ( $the_coupon_custom_message->coupon_custom_fields['wccm_custom_message'][0] ) ) {
    $custom_message = html_entity_decode( $the_coupon_custom_message->coupon_custom_fields['wccm_custom_message'][0] );
  }

  /* Has a style been selected? */
  if ( isset ( $the_coupon_custom_message->coupon_custom_fields['wccm_custom_message_style'][0] ) ) {
    $message_style = $the_coupon_custom_message->coupon_custom_fields['wccm_custom_message_style'][0];
  }

  /* Has a CSS class been entered? */
  if ( isset ( $the_coupon_custom_message->coupon_custom_fields['wccm_custom_message_class'][0] ) ) {
    $message_css_class = $the_coupon_custom_message->coupon_custom_fields['wccm_custom_message_class'][0];
  }

  /* Should we hide the default message? */
  if ( isset ( $the_coupon_custom_message->coupon_custom_fields['wccm_hide_default_message'][0] ) ) {
    $hide_default_message = $the_coupon_custom_message->coupon_custom_fields['wccm_hide_default_message'][0];
  }


  /* Displaying the custom message according to the settings applied */


  /* Check if default message should be hidden */
  if ( isset ( $custom_message ) && isset( $hide_default_message ) ) {
    echo "\n" . "\n" . '<style>.woocommerce_message, .woocommerce-message { display: none; } body .wccm {display:block !important;} </style>' . "\n";
  }

  /* If there is a custom message and neither message style or CSS class is chosen, display message in default style */
  if ( isset ( $custom_message ) && !isset ( $message_style ) && !isset ( $message_css_class ) ) {
    echo "\n" . '<div class="woocommerce_message woocommerce-message wccm ">' . $custom_message . '</div>'; 
  } 

  /* Else if there is a custom message and both message style og CSS class is chosen, display message in default style (only one can be chosen) */
  else if ( isset ( $custom_message ) && isset ( $message_style ) && isset ( $message_css_class ) ) {
    echo "\n" . '<div class="woocommerce_message woocommerce-message wccm ">' . $custom_message . '</div>';
  } 

  /* Else if there is a custom message and only message style is chosen, display message in selected style */
  else if ( isset ( $custom_message ) && isset ( $message_style ) && !isset ( $message_css_class ) ) {

    ?>

    <style> 
    
    .wccm-blue, .wccm-lightblue, .wccm-green, .wccm-lightgreen, 
    .wccm-grey, .wccm-orange, .wccm-pink, .wccm-purple, .wccm-lightpurple {
      padding: 1em 1em 1em 4em;
      margin: 0 0 2em;
      position: relative;

      color:#ffffff;
      font-size:1em;
      font-weight:bold;
      text-decoration:none;
      -moz-border-radius:6px;
      -webkit-border-radius:6px;
      border-radius:6px; 
    }

    .wccm-blue:before, .wccm-lightblue:before, .wccm-green:before, .wccm-lightgreen:before, 
    .wccm-grey:before, .wccm-orange:before, .wccm-pink:before, .wccm-purple:before, .wccm-lightpurple:before {
      content: "\2713";
      margin: 0 5em 0 0;
      font-weight:normal;
      height: 1.5em;
      width: 1.5em;
      display: block;
      position: absolute;
      top: 0;
      left: .5em;
      font-size: 1.8em;
      line-height: 1.5;
      text-align: center;
      padding-top: .2em;
      text-shadow: 4px 4px 5px rgba(0, 0, 0, 0.1);
    }

    <?php 

    switch ( $message_style ) {

      case 'wccm-blue':

      echo "\n" .

      ".wccm-blue {

        -moz-box-shadow:inset 0px 1px 0px 0px #97c4fe;
        -webkit-box-shadow:inset 0px 1px 0px 0px #97c4fe;
        box-shadow:inset 0px 1px 0px 0px #97c4fe;

        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #3d94f6), color-stop(1, #1e62d0));
        background:-moz-linear-gradient(top, #3d94f6 5%, #1e62d0 100%);
        background:-webkit-linear-gradient(top, #3d94f6 5%, #1e62d0 100%);
        background:-o-linear-gradient(top, #3d94f6 5%, #1e62d0 100%);
        background:-ms-linear-gradient(top, #3d94f6 5%, #1e62d0 100%);
        background:linear-gradient(to bottom, #3d94f6 5%, #1e62d0 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#3d94f6', endColorstr='#1e62d0',GradientType=0);

        background-color:#3d94f6;

        border:1px solid #337fed;

        text-shadow:0px 1px 0px #1570cd;    
      }

      </style>" . "\n";

      break;

      case 'wccm-lightblue':

      echo "\n" . 

      ".wccm-lightblue {

        -moz-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
        -webkit-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
        box-shadow:inset 0px 1px 0px 0px #bbdaf7;

        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #79bbff), color-stop(1, #378de5));
        background:-moz-linear-gradient(top, #79bbff 5%, #378de5 100%);
        background:-webkit-linear-gradient(top, #79bbff 5%, #378de5 100%);
        background:-o-linear-gradient(top, #79bbff 5%, #378de5 100%);
        background:-ms-linear-gradient(top, #79bbff 5%, #378de5 100%);
        background:linear-gradient(to bottom, #79bbff 5%, #378de5 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#79bbff', endColorstr='#378de5',GradientType=0);

        background-color:#79bbff;

        border:1px solid #84bbf3;

        text-shadow:0px 1px 0px #528ecc;  
      }

      </style>" . "\n";

      break;

      case 'wccm-green':

      echo "\n" . 

      ".wccm-green {

        -moz-box-shadow:inset 0px 1px 0px 0px #a4e271;
        -webkit-box-shadow:inset 0px 1px 0px 0px #a4e271;
        box-shadow:inset 0px 1px 0px 0px #a4e271;

        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #89c403), color-stop(1, #77a809));
        background:-moz-linear-gradient(top, #89c403 5%, #77a809 100%);
        background:-webkit-linear-gradient(top, #89c403 5%, #77a809 100%);
        background:-o-linear-gradient(top, #89c403 5%, #77a809 100%);
        background:-ms-linear-gradient(top, #89c403 5%, #77a809 100%);
        background:linear-gradient(to bottom, #89c403 5%, #77a809 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#89c403', endColorstr='#77a809',GradientType=0);

        background-color:#89c403;

        border:1px solid #74b807;

        text-shadow:0px 1px 0px #528009;
      }

      </style>" . "\n";

      break;

      case 'wccm-lightgreen':

      echo "\n" . 

      ".wccm-lightgreen {

        -moz-box-shadow:inset 0px 1px 0px 0px #d9fbbe;
        -webkit-box-shadow:inset 0px 1px 0px 0px #d9fbbe;
        box-shadow:inset 0px 1px 0px 0px #d9fbbe;

        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #b8e356), color-stop(1, #a5cc52));
        background:-moz-linear-gradient(top, #b8e356 5%, #a5cc52 100%);
        background:-webkit-linear-gradient(top, #b8e356 5%, #a5cc52 100%);
        background:-o-linear-gradient(top, #b8e356 5%, #a5cc52 100%);
        background:-ms-linear-gradient(top, #b8e356 5%, #a5cc52 100%);
        background:linear-gradient(to bottom, #b8e356 5%, #a5cc52 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#b8e356', endColorstr='#a5cc52',GradientType=0);

        background-color:#b8e356;

        border:1px solid #83c41a;

        text-shadow:0px 1px 0px #86ae47;
      }

      </style>" . "\n";

      break;

      case 'wccm-grey':

      echo "\n" . 

      ".wccm-grey {

       -moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
       -webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
       box-shadow:inset 0px 1px 0px 0px #ffffff;

       background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9));
       background:-moz-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
       background:-webkit-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
       background:-o-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
       background:-ms-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
       background:linear-gradient(to bottom, #f9f9f9 5%, #e9e9e9 100%);
       filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9',GradientType=0);

       background-color:#f9f9f9;

       border:1px solid #dcdcdc;
       color:#666666;

       text-shadow:0px 1px 0px #ffffff;

       </style>" . "\n";

       break;

       case 'wccm-orange':

       echo "\n" . 

       ".wccm-orange {

         -moz-box-shadow:inset 0px 1px 0px 0px #fce2c1;
         -webkit-box-shadow:inset 0px 1px 0px 0px #fce2c1;
         box-shadow:inset 0px 1px 0px 0px #fce2c1;

         background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ffc477), color-stop(1, #fb9e25));
         background:-moz-linear-gradient(top, #ffc477 5%, #fb9e25 100%);
         background:-webkit-linear-gradient(top, #ffc477 5%, #fb9e25 100%);
         background:-o-linear-gradient(top, #ffc477 5%, #fb9e25 100%);
         background:-ms-linear-gradient(top, #ffc477 5%, #fb9e25 100%);
         background:linear-gradient(to bottom, #ffc477 5%, #fb9e25 100%);
         filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffc477', endColorstr='#fb9e25',GradientType=0);

         background-color:#ffc477;

         border:1px solid #eeb44f;

         text-shadow:0px 1px 0px #cc9f52;
       }

       </style>" . "\n";

       break;

       case 'wccm-pink':

       echo "\n" . 

       ".wccm-pink {

         -moz-box-shadow:inset 0px 1px 0px 0px #fbafe3;
         -webkit-box-shadow:inset 0px 1px 0px 0px #fbafe3;
         box-shadow:inset 0px 1px 0px 0px #fbafe3;

         background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ff5bb0), color-stop(1, #ef027d));
         background:-moz-linear-gradient(top, #ff5bb0 5%, #ef027d 100%);
         background:-webkit-linear-gradient(top, #ff5bb0 5%, #ef027d 100%);
         background:-o-linear-gradient(top, #ff5bb0 5%, #ef027d 100%);
         background:-ms-linear-gradient(top, #ff5bb0 5%, #ef027d 100%);
         background:linear-gradient(to bottom, #ff5bb0 5%, #ef027d 100%);
         filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bb0', endColorstr='#ef027d',GradientType=0);

         background-color:#ff5bb0;

         border:1px solid #ee1eb5;

         text-shadow:0px 1px 0px #c70067;
       }

       </style>" . "\n";

       break;

       case 'wccm-purple':

       echo "\n" . 

       ".wccm-purple {

        -moz-box-shadow:inset 0px 1px 0px 0px #e184f3;
        -webkit-box-shadow:inset 0px 1px 0px 0px #e184f3;
        box-shadow:inset 0px 1px 0px 0px #e184f3;

        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #c123de), color-stop(1, #a20dbd));
        background:-moz-linear-gradient(top, #c123de 5%, #a20dbd 100%);
        background:-webkit-linear-gradient(top, #c123de 5%, #a20dbd 100%);
        background:-o-linear-gradient(top, #c123de 5%, #a20dbd 100%);
        background:-ms-linear-gradient(top, #c123de 5%, #a20dbd 100%);
        background:linear-gradient(to bottom, #c123de 5%, #a20dbd 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#c123de', endColorstr='#a20dbd',GradientType=0);

        background-color:#c123de;

        border:1px solid #a511c0;

        text-shadow:0px 1px 0px #9b14b3; 
      }

      </style>" . "\n";

      break;

      case 'wccm-lightpurple':

      echo "\n" . 

      ".wccm-lightpurple {

        -moz-box-shadow:inset 0px 1px 0px 0px #efdcfb;
        -webkit-box-shadow:inset 0px 1px 0px 0px #efdcfb;
        box-shadow:inset 0px 1px 0px 0px #efdcfb;

        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #dfbdfa), color-stop(1, #bc80ea));
        background:-moz-linear-gradient(top, #dfbdfa 5%, #bc80ea 100%);
        background:-webkit-linear-gradient(top, #dfbdfa 5%, #bc80ea 100%);
        background:-o-linear-gradient(top, #dfbdfa 5%, #bc80ea 100%);
        background:-ms-linear-gradient(top, #dfbdfa 5%, #bc80ea 100%);
        background:linear-gradient(to bottom, #dfbdfa 5%, #bc80ea 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfbdfa', endColorstr='#bc80ea',GradientType=0);

        background-color:#dfbdfa;

        border:1px solid #c584f3;

        text-shadow:0px 1px 0px #9752cc;

      }

      </style>" . "\n";

      break;
    }

    echo "\n" . '<div class="wccm ' . $message_style . '">' . $custom_message . '</div>' . "\n";
  } 

  /* Else if there is a custom message and only a CSS class is entered, display message wrapped in entered CSS class */
  else if ( isset ( $custom_message ) && isset ( $message_css_class ) && !isset ( $message_style ) ) {

    echo "\n" . '<div class="' . $message_css_class  . ' wccm">' . $custom_message . '</div>' . "\n";
  }

}

add_action ('woocommerce_applied_coupon', 'custom_coupon_message'); 

?>