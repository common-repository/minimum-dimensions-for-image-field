<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.faysalmahamud.com
 * @since             1.0.0
 * @package           st_midea
 *
 * @wordpress-plugin
 * Plugin Name:       Minimum Dimensions For Image Field
 * Plugin URI:        http://wordpress.org/extend/plugins/minimum-dimensions-for-image-field/
 * Description:       Provides Minimum or maximum Dimension of an image Developed by <a href="http://faysalmahamud.com" target="_blank">Faysal Mahamud</a>, <a href="http://mahfuzulhasan.com/" target="_blank">Mahfuzul Hasan</a> and sponsor by <a href="http://sourcetop.com" target="_blank">sourcetop.inc</a>.
 * Version:           1.0.2
 * Author:            Faysal Mahamud
 * Author URI:        http://www.faysalmahamud.com
 * Contributors:      Faysal Mahamud (@faysal.turjo / faysalmahamud.com)
 *                    Mahfuzul Hasan (@mahfuzul / mahfuzulhasan.com)
 *
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

define( 'ST_MEDIA_URL',        plugin_dir_url( __FILE__ ) );
define( 'ST_MEDIA_PATH',       dirname( __FILE__ ) . '/' );

function st_media_autoload($class_name) {

  // Check Libraries Folder
  $path = ST_MEDIA_PATH . 'libraries/' . $class_name . '.php';
  if( file_exists( $path ) )
    require_once( $path );
}

spl_autoload_register('st_media_autoload');


// Include all php files in the includes directory automatically
foreach ( glob( ST_MEDIA_PATH . "libraries/*.php" ) as $file ) {
  $path_segment = explode('/', $file);
  list( $name, $junk) = explode( '.', end($path_segment) );
  if( method_exists( $name, 'init') ) {
    $name::init();
  }
}

$acf_path = ST_MEDIA_PATH . 'includes/vendor/acf/acf.php';
if( file_exists( $acf_path ) )
  require_once( $acf_path );

// Load Helper PHP
// Include all php files in the includes directory automagically
foreach ( glob( ST_MEDIA_PATH . "helpers/*.php" ) as $file ) {
  include_once( $file );
}

