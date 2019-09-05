<?php
/**
 * Plugin Name: Planet4 GPCH RaiseNow Lema Widget
 * Plugin URI: https://github.com/greenpeace/planet4-gpch-raisenow-lema-widget
 * Description: A WordPress Plugin to embed the RaiseNow Lema Widget with a Gutenberg Block.
 * Version: 0.1.0
 * Requires at least: 5.2.2
 * Requires PHP: 7.0.33
 * Author: Greenpeace Switzerland
 * Author URI: https://www.greenpeace.ch
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: planet4-gpch-raisenow-lema-widget
 */

// exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Planet4 = P4
// Greenpeace Switzerland = GPCH
// RaiseNow Lema Widget = RNLW

// constants
define( 'P4_GPCH_RNLW_BASE_PATH', plugin_dir_path( __FILE__ ) );
define( 'P4_GPCH_RNLW_PREFIX', 'planet4-gpch-raisenow-lema-widget' );

// include the composer autoload file
require_once P4_GPCH_RNLW_BASE_PATH . 'vendor/autoload.php';

// initialize the plugin
$planet4_gpch_raisenow_lema_widget = Greenpeace\Planet4GPCHRaiseNowLemaWidget\Planet4_GPCH_RaiseNow_Lema_Widget::get_instance();

// @todo implement hooks here (if possible)
// $planet4_gpch_raisenow_lema_widget->hook_into_wordpress();
