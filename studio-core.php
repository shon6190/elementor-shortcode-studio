<?php
/*
Plugin Name: Elementor Shortcode Studio
Plugin URI: https://example.com/elementor-shortcode-studio
Description: Elementor Shortcode Studio is a supportive plugin designed to work with Elementor, helping developers create shortcodes using the admin backend 
Author: Shon
Author URI: https://example.com/author/shon
Version: 1.0
*/


defined('ABSPATH') or die("Restricted access!");

/**
 * Define constants
 *
 * @since 1.0
 */

defined('SC_CORE_DIR') or define('SC_CORE_DIR', dirname(plugin_basename(__FILE__)));  // elementor-scaffolder
defined('SC_CORE_BASE') or define('SC_CORE_BASE', plugin_basename(__FILE__));  // elementor-scaffolder/studio-core.php
defined('SC_CORE_URL') or define('SC_CORE_URL', plugin_dir_url(__FILE__));  // http://test-elementor.local/wp-content/plugins/elementor-scaffolder/
defined('SC_CORE_PATH') or define('SC_CORE_PATH', plugin_dir_path(__FILE__));  // C:\Users\local\Local Sites\test-elementor\app\public\wp-content\plugins\elementor-scaffolder/

defined('SC_CORE_VERSION') or define('SC_CORE_VERSION', '1.0');

class ElementorShortcodeStudio {
    
    public function __construct() {
        // Enqueue admin styles
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_styles']);
        // Load required files
        $this->load_required_files();
    }
    // Enqueue admin styles
    public function enqueue_admin_styles() {
        wp_enqueue_style('ess-style', SC_CORE_URL . 'assets/css/main.css');
    }

    private function load_required_files() {
        $required_files = [
            'elementor-addon.php',
            'functions/shortcode-generate-functions.php',
            'functions/common-functions.php',
            'functions/create-css-js.php',
            'functions/create-iconcss.php',
            'functions/create-renderfile.php',
            'functions/create-json.php'
        ];

        foreach ($required_files as $file) {
            $file_path = SC_CORE_PATH . $file;
            if (file_exists($file_path)) {
                require $file_path;
            }
        }
    }
}
new ElementorShortcodeStudio();
