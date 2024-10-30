<?php

/**
* Social Settings
*/
class media_dimensions {

  public static function init() {
    // create custom plugin settings menu
    add_action( 'admin_menu', array('media_dimensions', 'st_create_social_menu') );
    //call register settings function
    add_action( 'admin_init', array('media_dimensions', 'st_register_social_settings') );
    //add_action( 'wp_handle_upload_prefilter', array('media_dimensions', 'check_img_size') );
    add_filter('wp_handle_upload_prefilter',array('media_dimensions', 'check_img_size'));
    //add_action('wp_handle_upload', array('media_dimensions', 'filetype_check'));
    add_action( 'admin_enqueue_scripts', array('media_dimensions', 'media_dimensions_attach_js') );

    add_action( 'wp_ajax_update_user_mod_field', array('media_dimensions', 'update_user_mod_field') );
  }

  public static function media_dimensions_attach_js(){
    wp_enqueue_script( "media_dimensions", ST_MEDIA_URL . 'js/media_dimensions.js', array('jquery'), '', true  );
    wp_localize_script( 'media_dimensions', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
  }
  public static function st_create_social_menu() {
    //create new top-level menu
    add_submenu_page('themes.php', 'Media Settings', 'Media Settings', 'administrator', 'st-media-settings', array('media_dimensions', 'st_social_settings_page'),'');
  }


  public static function st_register_social_settings() {
    //register our settings
    $post_types = get_post_types();
    foreach($post_types as $type){
      register_setting( 'st-media-settings-group', 'post_types'.$type.'width' );
      register_setting( 'st-media-settings-group', 'post_types'.$type.'height' );
    }
    if( class_exists('acf') ):
      global $wpdb;
      $query = "SELECT DISTINCT * FROM $wpdb->postmeta WHERE meta_key not LIKE '\_%' AND meta_key LIKE 'field_%'";
      $keys = $wpdb->get_results($query);
      foreach($keys as $custom_type){
        register_setting( 'st-media-settings-group', 'custom_types'.$custom_type->meta_key.'width' );
        register_setting( 'st-media-settings-group', 'custom_types'.$custom_type->meta_key.'height' );
      }
    endif;
  }

  public static function st_social_settings_page() {
    // Render social settings template
    st_view::render( ST_MEDIA_PATH . 'templates/media.tpl.php' );
  }

  public static function check_img_size($file){
    $currentScreen = get_current_screen();
    $post_type = get_post_type($_REQUEST['post_id']);
    $meta_key = get_user_meta(get_current_user_id(),  'last_user_field', true );
    global $wpdb;
    if($meta_key['acf'] == 'true'){
      $field_name = $meta_key['field_type'];
      // $field_query = "SELECT  meta_value
      // FROM $wpdb->postmeta WHERE meta_key = '$field_name'";
      // $field_result = $wpdb->get_var($field_query);
      // $custom_field = unserialize($field_result);
      // $field_key = $custom_field['name'];

      $typewidth = 'custom_types'.$field_name.'width';
      $typeheight = 'custom_types'.$field_name.'height';

      $query = "SELECT  option_value
      FROM $wpdb->options WHERE option_name LIKE '$typewidth'";
      $typeminwidth = $wpdb->get_var($query);

      $query1 = "SELECT  option_value
      FROM $wpdb->options WHERE option_name LIKE '$typeheight'";
      $typeminheight = $wpdb->get_var($query1);

    }
    elseif($meta_key['field_key'] == 'featured_image'){
      $typewidth = 'post_types' . $post_type . 'width';
      $typeheight = 'post_types' . $post_type . 'height';

      $query = "SELECT  option_value
      FROM $wpdb->options WHERE option_name LIKE '$typewidth'";
      $typeminwidth = $wpdb->get_var($query);

      $query1 = "SELECT  option_value
      FROM $wpdb->options WHERE option_name LIKE '$typeheight'";
      $typeminheight = $wpdb->get_var($query1);
    }

    $img=getimagesize($file['tmp_name']);
    $minimum = array('width' => $typeminwidth, 'height' => $typeminheight);
    $width= $img[0];
    $height =$img[1];
    $validationFailed = false;
    $errorMessage = '';

    if ($width < $minimum['width'] ){
      $validationFailed = true;
      $errorMessage = "Minimum width is {$minimum['width']}px. Uploaded image width is $width px.";
    }
    if ($height <  $minimum['height']){
      if($validationFailed) {
        $errorMessage .= "Minimum height is {$minimum['height']}px. Uploaded image height is $height px.";
      } else {
        $validationFailed = true;
        $errorMessage = "Minimum height is {$minimum['height']}px. Uploaded image height is $height px.";
      }
    }
    if(!$validationFailed) {
      return $file;
    }
    else {
      return array("error"=>"Image dimensions are too small." . $errorMessage);
    }

  }

  public  static function update_user_mod_field() {
    global $post;
    $user_ID = get_current_user_id();
    if(isset($_POST['fieldKey'])){
      $fieldKey = $_POST['fieldKey'];
      $acf = $_POST['acf'];
      $field_type = $_POST['field_type'];
    }else{
      $fieldKey = false;
      $acf = false;
      $field_type = false;
    }
    // Save a Record of what field we are editing.
    // We'll check this in the check image size function later and restrict img uploads accordingly.
    update_user_meta( $user_ID, 'last_user_field', array( 'field_key' => $fieldKey,'acf' => $acf , 'field_type'=> $field_type ) );

    die();
  }

}
