<?php /* PHP file content */
namespace WacElementorAddon\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

defined("ABSPATH") || exit;

class wac_test extends Widget_Base {

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        wp_register_style( "wac-test-style-handle", plugins_url("/wac-test.css", __FILE__));
        wp_register_script( "wac-test-script-handle", plugins_url("/wac-test.js", __FILE__), [ "elementor-frontend" ], "1.0.0", true );
    }


    public function get_style_depends() {
        return [ "wac-test-style-handle" ];
    }


    public function get_script_depends() {
        return [ "wac-test-script-handle" ];
    }

    public function get_name() {
        return "wac-test";
    }

    public function get_title() {
        return esc_html__( "wac-test", "elementskit-lite" );
    }

    public function get_categories() {
        return ["wac-shortcodes"];
    }

    public function get_icon() {
        return "qd-icon qd-icon-wac-test";
    }
    
    protected function register_controls() {

        $this->start_controls_section(
            "content_section_28_0",
            array(
                "label" => esc_html__( "Title", "elementskit-lite" ),
                "tab"   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            "_button_text",
            array(
                "label" => esc_html__( "Button Text", "elementskit-lite" ),
                "type"  => Controls_Manager::TEXT,
                "default" =>  esc_html( "Some Text" ),
                "show_label" => true,
                "label_block" => false,
                "input_type" => "text",
            )
        );

        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        ob_start();
        require("render-wac-test.php");
        $output = ob_get_contents();
        ob_end_clean();
        echo $output;
        wp_enqueue_script("wac-test-js");
    }
}