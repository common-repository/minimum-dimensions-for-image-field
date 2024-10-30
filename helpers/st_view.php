<?php


/**
 * This view class provides a render function
 * to overcome the limitation of wordpress core function get_template_part
 */
class st_view{

  public static function render( $template, $data = array(), $print = true, $debug = false) {

    if( file_exists( $template ) ) {
      $filepath = $template;
    }else{
      $filepath = ST_THEME_PATH . 'templates/' . $template ;
    }

    extract( $data );
    if(isset($atts)){
      extract($atts);
    }

    if( file_exists( $filepath ) ) {
      if($debug) {
        echo '<div style="background:gray;color:white;text-align:center;"><b>Template</b> : ' . $template. '</div>';
      }

      $view = new stdClass();
      $view->path = $filepath;

      ob_start();
      include(  $view->path );
      $html = ob_get_contents();
      ob_end_clean();

      if( !$print ) {
        return $html;
      } else {
        print $html;
      }
    } else {
      echo '<pre>' . ST_THEME_PATH . $template . ' not exist.</pre>';
    }
  }
}
