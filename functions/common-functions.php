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


function wac_shortode_list_callback() {
    require_once( SC_CORE_PATH . 'shortcodes-extend/shortcode-list.php');
    $ShortCodeListpage = new ShortCodeListpage();
}

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