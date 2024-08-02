<?php 

function buildPhpFile($filePath , $shortcode_handle , $shortcode_slug ,$name ){

    // create a .php file for the Ui team to edit 
    $htmlContent = '<h2>'. $name .'</h2>
    <!--    Please add the HTML here and do not remove the clearfix Tag.    -->';
    $htmlfile = file_put_contents( $filePath.'/render-'. $name.'.php', $htmlContent);
    $htmlfileUrl = 'render-'.$name.'.php';

    // create a php file for rentering the shortcode 
$phpContent = '<?php /* PHP file content */';    
$phpContent .= '
namespace EssElementorAddon\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

defined("ABSPATH") || exit;

class '. $shortcode_handle .' extends Widget_Base {

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        wp_register_style( "'. $shortcode_slug .'-style-handle", plugins_url("/'. $name .'.css", __FILE__));
        wp_register_script( "'. $shortcode_slug .'-script-handle", plugins_url("/'. $name .'.js", __FILE__), [ "elementor-frontend" ], "1.0.0", true );
    }


    public function get_style_depends() {
        return [ "'. $shortcode_slug .'-style-handle" ];
    }


    public function get_script_depends() {
        return [ "'. $shortcode_slug .'-script-handle" ];
    }

    public function get_name() {
        return "'. $name .'";
    }

    public function get_title() {
        return esc_html__( "'. $name .'", "elementskit-lite" );
    }

    public function get_categories() {
        return ["wac-shortcodes"];
    }

    public function get_icon() {
        return "qd-icon qd-icon-'. $name .'";
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
            "6566_button_text",
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
        require("'. $htmlfileUrl .'");
        $output = ob_get_contents();
        ob_end_clean();
        echo $output;
        wp_enqueue_script("'. $name .'-js");
    }
}';
    $renderFile = file_put_contents( $filePath.'/'. $name.'.php', $phpContent);
    if($renderFile){
        return $filePath.'/'. $name.'.php';
    } else{
        return false;
    }    
    return $phpFile;
}

?>