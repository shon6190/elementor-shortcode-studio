<?php

namespace WacElementorAddon;

$shortcode_json_dir = SC_CORE_PATH . 'shortcodes-extend/wac-shortcode-jsons';
$shortcode_json_fiels = scandir($shortcode_json_dir);

$FullDataArray = [];
foreach ($shortcode_json_fiels as $file) {
     if ($file === '.' || $file === '..') {
        continue;
    }
    if (is_file($shortcode_json_dir . '/' . $file)) {
        if( 'json' !== pathinfo($shortcode_json_dir . '/' . $file, PATHINFO_EXTENSION)) {
          continue;
        }
        $fileContent = file_get_contents($shortcode_json_dir . '/' . $file);
        $existingDataArray = json_decode($fileContent, true);
        if($existingDataArray){
            foreach($existingDataArray as $fileLink){
                 $FullDataArray[] = $fileLink;
            }
        }
    }
}
$HandleArray = [];
foreach($FullDataArray as $FullData){
     $HandleArray[] = "WacElementorAddon\\Widgets\\".$FullData['handle'];
}


if( ! defined('ABSPATH') ) exit;

class elemAddonWac{
    private $FullDataArray;

    public function __construct($FullDataArray) {

        $this->FullDataArray = $FullDataArray; // Assign the data to the property
        $this->add_actions();
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'glance_after_register_scripts' ]);
		add_action( 'elementor/editor/before_enqueue_styles', [ $this, 'glance_preview_style' ]);
    }


    public function add_actions() {
        add_action( 'elementor/init', [ $this, 'glance_elementor_helper_init' ] );
        add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_widget_styles' ] );
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_widget_styles' ] );
        add_action( 'elementor/widgets/register', [ $this, 'on_widgets_registered' ] );
    }
    public function glance_elementor_helper_init() {
        \Elementor\Plugin::instance()->elements_manager->add_category(
            'wac-shortcodes',
            [
                'title'  => 'WAC',
                'icon' => 'font'
            ],
            1
        );
    }
	public function enqueue_widget_styles() {
        // wp_register_style( 'glance-statistics', plugins_url(  '/assets/css/stat-card.css', __FILE__  ), array(), '1.0' );
        // wp_enqueue_style( 'custom-style-elem', plugins_url(  '/assets/css/custom-main.css', __FILE__  ), array(), '1.0' );
        
	}   

    public function glance_after_register_scripts() {
        // wp_register_script( 'glance-btn-anim', plugins_url(  '/assets/js/btn-anim.js', __FILE__  ), array('jquery'), null, true );    
    }
    public function glance_preview_style() {
        wp_enqueue_style('wac_core_shortcode_icon_css', plugins_url('widget-icons/icon.css', __FILE__));
    }

    public function on_widgets_registered() {
        $this->includes();
        $this->register_widget();
    }
    
    private function includes(){
         foreach ($this->FullDataArray as $FullDatarequire) {
            $filePath = SC_CORE_PATH.$FullDatarequire['url'];
            if (file_exists($filePath)) {
                require SC_CORE_PATH.$FullDatarequire['url'];
            }
        }
    }
    private function register_widget(){
        foreach ($this->FullDataArray as $FullData) {
            $widgetClass = "WacElementorAddon\\Widgets\\".$FullData['handle'];
            \Elementor\Plugin::instance()->widgets_manager->register(new $widgetClass());
        }    
    }
}


new elemAddonWac($FullDataArray);

