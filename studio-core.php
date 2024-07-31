<?php
/*
Plugin Name: Elementor Shortcode Studio
Plugin URI: 
Description: Addon Plugin devloped by Webandcrafts
Author: 
Author URI: 
Version: 1.0
*/

// include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

defined('ABSPATH') or die("Restricted access!");

/**
 * Define constants
 *
 * @since 1.0
 */

defined('SC_CORE_DIR') or define('SC_CORE_DIR', dirname(plugin_basename(__FILE__)));  // elementor-scaffolder
defined('SC_CORE_BASE') or define('SC_CORE_BASE', plugin_basename(__FILE__));  // elementor-scaffolder/studio-core.php
defined('SC_CORE_URL') or define('SC_CORE_URL', plugin_dir_url(__FILE__));  // http://test-elementor.local/wp-content/plugins/elementor-scaffolder/
defined('SC_CORE_PATH') or define('SC_CORE_PATH', plugin_dir_path(__FILE__));  // C:\Users\Wac\Local Sites\test-elementor\app\public\wp-content\plugins\elementor-scaffolder/

defined('SC_CORE_VERSION') or define('SC_CORE_VERSION', '1.0');


add_action('admin_enqueue_scripts', 'wac_addon_admin_styles');
function wac_addon_admin_styles()
{
    // wp_enqueue_style("wac-admin-style", plugins_url('/wac-addon/css/main.css'));

}

function wac_addon_plugin_active()
{
    return true;
}


require_once SC_CORE_PATH . 'elementor-addon.php';
require_once SC_CORE_PATH . 'shortcodes-extend.php';

