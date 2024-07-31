<?php 

/**
 * wac_shortcode_list function
 * 
 * For creating the menu in the backend
 * 
 * @return void
 */
function wac_shortcode_list() {
    add_submenu_page(
        'elementor',  __('Shortodes Manager', 'waclang') ,  __('Shortodes Manager', 'waclang'), 'manage_options', 'wac-shortode-list', 'wac_shortode_list_callback' 
    );
}
add_action('admin_menu', 'wac_shortcode_list' , 100);

 
 
function custom_menu_callback() {
    // Your custom menu page content goes here
    echo '<div class="wrap">';
    echo '<h2>Custom Menu Page</h2>';
    echo '<p>This is your custom menu page content.</p>';
    echo '</div>';
}

function wac_shortode_list_callback() {
    require_once( SC_CORE_PATH . 'shortcodes-extend/shortcode-list.php');
    $ShortCodeListpage = new ShortCodeListpage();
}

function WACCreateShortcode() {
    /**
     * FILTER_SANITIZE_STRING
     */
    // foreach ($_POST as $key => $value) {
    //     $_POST[$key] = filter_var($value, FILTER_SANITIZE_STRING);
    // }
    $shortcode_name = $_POST['wac_shortcode_name'];
    $wac_shortcode_files = ['css', 'scss', 'js' , 'img']; 
    $name = wac_format_text($shortcode_name);
    $wac_shortcode_icon = $_FILES['file'];
    // $icon_upload = icon_upload($wac_shortcode_icon ,  $name);
    $icon_upload = icon_upload($wac_shortcode_icon , $name);
    if($icon_upload){
        create_font_css($icon_upload , $name);
    }
    $plugin_dir = plugin_dir_path(__FILE__);
    $file_path = $plugin_dir . 'widgets/'.$name;
    if (file_exists($file_path)) {
        wp_send_json_error(array(
            'status' => false,
            'msg' => 'already a shortcode with same name.',
         ));
    } else {
        if (mkdir($file_path, 0777, true)) {
            // Create CSS and Js file
            $createFilesAndFolder = createCssAndJs($file_path , $name , $wac_shortcode_files);
            // Create PHP file
            $shortcode_handle = str_replace("-", "_", $name);

            $shortcode_slug = $name;
            $createphpFile = buildPhpFile($file_path , $shortcode_handle , $shortcode_slug ,$name );

            if ($createFilesAndFolder && $createphpFile){
                $include_shortcode = addfiletojson( $name );
                if($include_shortcode){
                    wp_send_json_success(array(
                        'status' => true,
                        'msg' => 'Shortcode added',
                     ), 200); 
                } else {
                    wp_send_json_error(array(
                        'status' => false,
                        'msg' => 'issue in including the shortcode .',
                     ));
                }
            } else {
                wp_send_json_error(array(
                    'status' => false,
                    'msg' => 'issue in creating the file and folders.',
                 ));
            }
        } else {
            wp_send_json_error(array(
                'status' => false,
                'msg' => 'Issue with file premission. please check permission are enabled.',
             ));
        }
    }   
}
add_action('wp_ajax_wac_create_shortcode', 'WACCreateShortcode');
add_action('wp_ajax_nopriv_wac_create_shortcode', 'WACCreateShortcode');


/**
 *  1 Convert to lowercase
 *  2 Remove all special character from the shortcode name.
 *  3 Check if the prefix is present in the name, and remove prefix.
 *  4 Trim the space from start and end
 *  5 Convert space to '-'
 *  6 add prefix
 *  
 */
function wac_format_text( $codename ){
    $prefix_name = 'wac';
    // 1
    $codename = strtolower( $codename );
    // 2
    $shortcode_name = preg_replace('/[^a-zA-Z0-9\s]/', '', $codename);
    // 3
    if (strpos($shortcode_name, $prefix_name) !== false) {
        $shortcode_name = str_replace($prefix_name, "", $shortcode_name);
    }
    // 4
    $shortcode_name = trim($shortcode_name);
    // 5
    $shortcode_name = str_replace(' ', '-', $shortcode_name);
    //6
    $codename =  $prefix_name.'-'.$shortcode_name;

    return $codename;
}

/**
 * Shortcode files generate  function
 *
 * @return void
 */

 function createCssAndJs($filePath, $name, $wac_shortcode_files) {
    $createFlag = false;

    foreach($wac_shortcode_files as $shortcode_files){
        if (in_array($shortcode_files, ['css', 'scss', 'js'])) {
            $fileExtension = $shortcode_files;
            $shortcode_name = str_replace('-', '_', $name);
            // Create file based on the specified extension
            $fileContent = '/* ' . strtoupper($fileExtension) . ' file content */';
            if($fileExtension == 'js'){
$fileContent .= '
(function ($) {
    var '. $shortcode_name .' = function ($scope, $) {
        /* Add the script here */
    }
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/'. $name .'.default", '. $shortcode_name .');
    });
})(jQuery);';
            }
            $file = file_put_contents($filePath . '/' . $name . '.' . $fileExtension, $fileContent);
            $createFlag = $file !== false;
        }
        if( $shortcode_files == 'img' ){
            $folderPath = $filePath . '/images';
            mkdir($folderPath, 0755, true); 
            
            $createFlag = $file !== false;
        }
    }
    return $createFlag;
}


function icon_upload($file , $name){
    $tempFile = $file['tmp_name'];
    $fileName = $file['name'];
    $targetPath = 'uploads/'; 
    $targetPath = SC_CORE_PATH . 'widget-icons/icons/';
    $new_file_name = $name . '.' . pathinfo( $fileName , PATHINFO_EXTENSION);
    // $targetFile = $targetPath . $fileName;
    if (move_uploaded_file($tempFile, $targetPath.$new_file_name )) {
        
      return $fileName;
    } else {
        $error = error_get_last();
        echo "Error: " . $error['message'];

        return false;
    }
  
}

function create_font_css($imgName , $name){
    $targetPath = SC_CORE_PATH . 'widget-icons/icon.css';
    $css_content = file_get_contents($targetPath);
$css_content .= '
.qd-icon-'. $name .' {
    background-image: url("../widget-icons/icons/'. $name .'.png") !important;
}';
    $updated = file_put_contents($targetPath, $css_content);
    if($updated){
        return true;
    } else {
        return false;
    }
}
 


function buildPhpFile($filePath , $shortcode_handle , $shortcode_slug ,$name ){

    // create a .php file for the Ui team to edit 
    $htmlContent = '<h2>'. $name .'</h2>
    <!--    Please add the HTML here and do not remove the clearfix Tag.    -->';
    $htmlfile = file_put_contents( $filePath.'/render-'. $name.'.php', $htmlContent);
    $htmlfileUrl = 'render-'.$name.'.php';

    // create a php file for rentering the shortcode 
$phpContent = '<?php /* PHP file content */';    
$phpContent .= '
namespace WacElementorAddon\Widgets;
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
            "'. $shortcode_name .'_button_text",
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





function addfiletojson($fileName){

    // get user email
    // check file exit for user email
    // append to the file
  
    $current_user = wp_get_current_user();

    if ( !$current_user instanceof WP_User ) {
       
        return false;
        // retun if user not found.
    }

    $user_email = $current_user->user_email;

    $Label = ucfirst(str_replace('-', ' ', $fileName));
    $shortcode_handle = str_replace("-", "_", $fileName);

$data = '
    "'. $fileName .'": {
        "url": "widgets/'. $fileName.'/'. $fileName.'.php",
        "name": "'. $Label .'",
        "handle": "'. $shortcode_handle .'",
        "icon": "icons/'.$fileName .'.png"
    }';

    $jsonname = SC_CORE_PATH . 'shortcodes-extend/wac-shortcode-jsons/'.$user_email.'.json';
    if (file_exists($jsonname)) {
    $existingDataArray = '';
    $existingData = file_get_contents($jsonname);
    $existingData = rtrim($existingData, '}');
$existingDataArray .=
$existingData.',
    '.$data.'
}';

        file_put_contents($jsonname, $existingDataArray);
    } else {
$data = '
{
    '. $data .'
}';
        file_put_contents($jsonname, $data );
    }
    return true;
}