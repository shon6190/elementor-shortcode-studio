<?php

namespace EssElementorAddon;

// Prevent direct access
defined('ABSPATH') or exit('Restricted access!');

// Constants
defined('SC_CORE_PATH') or define('SC_CORE_PATH', plugin_dir_path(__FILE__));

// Load shortcode JSON files
class ShortcodeLoader
{
    private $shortcode_json_dir;
    private $shortcode_json_files;
    private $full_data_array = [];

    public function __construct()
    {
        $this->shortcode_json_dir = SC_CORE_PATH . 'shortcodes-extend/wac-shortcode-jsons';
        $this->load_files();
    }

    private function load_files()
    {
        $this->shortcode_json_files = scandir($this->shortcode_json_dir);
        foreach ($this->shortcode_json_files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $file_path = $this->shortcode_json_dir . '/' . $file;
            if (is_file($file_path) && pathinfo($file_path, PATHINFO_EXTENSION) === 'json') {
                $file_content = file_get_contents($file_path);
                $data_array = json_decode($file_content, true);
                if ($data_array) {
                    $this->full_data_array = array_merge($this->full_data_array, $data_array);
                }
            }
        }
    }

    public function get_full_data_array()
    {
        return $this->full_data_array;
    }
}

class ElemAddonEss
{
    private $full_data_array;

    public function __construct($full_data_array)
    {
        $this->full_data_array = $full_data_array;
        $this->add_actions();
        add_action('elementor/editor/before_enqueue_styles', [$this, 'enqueue_preview_style']);
    }

    private function add_actions()
    {
        add_action('elementor/init', [$this, 'elementor_helper_init']);
        add_action('elementor/widgets/register', [$this, 'on_widgets_registered']);
    }

    public function elementor_helper_init()
    {
        \Elementor\Plugin::instance()->elements_manager->add_category(
            'wac-shortcodes',
            [
                'title' => 'WAC',
                'icon' => 'font'
            ],
            1
        );
    }

    public function enqueue_preview_style()
    {
        wp_enqueue_style('wac_core_shortcode_icon_css', plugins_url('widget-icons/icon.css', __FILE__));
    }

    public function on_widgets_registered()
    {
        $this->include_files();
        $this->register_widgets();
    }

    private function include_files()
    {
        foreach ($this->full_data_array as $data) {
            $file_path = SC_CORE_PATH . $data['url'];
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }

    private function register_widgets()
    {
        foreach ($this->full_data_array as $data) {
            $widget_class = "EssElementorAddon\\Widgets\\" . $data['handle'];
            \Elementor\Plugin::instance()->widgets_manager->register(new $widget_class());
        }
    }
}

// Initialize the plugin
$shortcode_loader = new ShortcodeLoader();
new ElemAddonEss($shortcode_loader->get_full_data_array());
