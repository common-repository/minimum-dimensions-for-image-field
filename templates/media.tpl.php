<div class="wrap">
<h2>Media Settings</h2>

<form method="post" action="options.php">
  <?php settings_fields( 'st-media-settings-group' ); ?>
  <?php do_settings_sections( 'st-media-settings-group' ); ?>
  <table class="form-table">

    <tr valign="top">
      <td scope="row" colspan="3"><h2>Post Type image settings. Enter minimum dimensions in pixels:</h2></th>
    </tr>
    <?php
      $post_types = get_post_types();
      foreach($post_types as $type):
        $post_type = get_post_type_object( $type );
    ?>
    <tr valign="top">
      <td scope="row"><?php print $post_type->labels->name;?></th>
      <td scope="row">Width</th>
      <td><input type="text" name="<?php print 'post_types'.$type.'width';?>" value="<?php echo esc_attr( get_option('post_types'.$type.'width') ); ?>" size="40" /></td>
      <td scope="row">Height</th>
      <td><input type="text" name="<?php print 'post_types'.$type.'height';?>" value="<?php echo esc_attr( get_option('post_types'.$type.'height') ); ?>"size="40" /></td>
    </tr>
  <?php endforeach;?>
  <?php if( class_exists('acf') ):?>
    <tr valign="top">
      <td scope="row" colspan="3"><h2>Custom Field image settings. Enter minimum dimensions in pixels:</h2></th>
    </tr>
    <?php

        global $wpdb;
        //SELECT DISTINCT * FROM  $wpdb->postmeta WHERE  meta_key not LIKE '\_%' AND `meta_key` LIKE 'field_%'
        //$query = "SELECT DISTINCT * FROM $wpdb->postmeta WHERE meta_key not LIKE '\_%' AND meta_key NOT LIKE 'field_%'";
        $query = "SELECT DISTINCT * FROM $wpdb->postmeta WHERE meta_key not LIKE '\_%' AND meta_key LIKE 'field_%'";
        $keys = $wpdb->get_results($query);
        foreach($keys as $custom_type){
          $custom_field = unserialize($custom_type->meta_value);
    ?>
      <tr valign="top">
        <td scope="row"><?php print $custom_field['label'];?></th>
        <td scope="row">Width</th>
        <td><input type="text" name="<?php print 'custom_types'.$custom_type->meta_key.'width';?>" value="<?php echo esc_attr( get_option('custom_types'.$custom_type->meta_key.'width') ); ?>" size="40" /></td>
        <td scope="row">Height</th>
        <td><input type="text" name="<?php print 'custom_types'.$custom_type->meta_key.'height';?>" value="<?php echo esc_attr( get_option('custom_types'.$custom_type->meta_key.'height') ); ?>"size="40" /></td>
      </tr>

    <?php
      }
      endif;
    ?>
  </table>

  <?php submit_button(); ?>

</form>
</div>
