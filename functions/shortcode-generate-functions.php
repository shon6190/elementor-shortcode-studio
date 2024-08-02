<?php
function ESSCreateShortcode()
{
    /**
     * FILTER_SANITIZE_STRING
     */
    // foreach ($_POST as $key => $value) {
    //     $_POST[$key] = filter_var($value, FILTER_SANITIZE_STRING);
    // }
    $shortcode_name = $_POST['ess_shortcode_name'];
    $ess_shortcode_files = ['css', 'scss', 'js', 'img'];
    $name = ess_format_text($shortcode_name);
    $ess_shortcode_icon = $_FILES['file'];
    // $icon_upload = icon_upload($ess_shortcode_icon ,  $name);
    $icon_upload = icon_upload($ess_shortcode_icon, $name);
    if ($icon_upload) {
        create_font_css($icon_upload, $name);
    }
    $plugin_dir = SC_CORE_PATH;
    $file_path = $plugin_dir . 'widgets/' . $name;
    if (file_exists($file_path)) {
        wp_send_json_error(
            array(
                'status' => false,
                'msg' => 'already a shortcode with same name.',
            )
        );
    } else {
        if (mkdir($file_path, 0777, true)) {
            // Create CSS and Js file
            $createFilesAndFolder = createCssAndJs($file_path, $name, $ess_shortcode_files);
            // Create PHP file
            $shortcode_handle = str_replace("-", "_", $name);

            $shortcode_slug = $name;
            $createphpFile = buildPhpFile($file_path, $shortcode_handle, $shortcode_slug, $name);

            if ($createFilesAndFolder && $createphpFile) {
                $include_shortcode = addfiletojson($name);
                if ($include_shortcode) {
                    wp_send_json_success(
                        array(
                            'status' => true,
                            'msg' => 'Shortcode added',
                        ), 200);
                } else {
                    wp_send_json_error(
                        array(
                            'status' => false,
                            'msg' => 'issue in including the shortcode .',
                        )
                    );
                }
            } else {
                wp_send_json_error(
                    array(
                        'status' => false,
                        'msg' => 'issue in creating the file and folders.',
                    )
                );
            }
        } else {
            wp_send_json_error(
                array(
                    'status' => false,
                    'msg' => 'Issue with file premission. please check permission are enabled.',
                )
            );
        }
    }
}
add_action('wp_ajax_ess_create_shortcode', 'ESSCreateShortcode');
add_action('wp_ajax_nopriv_ess_create_shortcode', 'ESSCreateShortcode');

