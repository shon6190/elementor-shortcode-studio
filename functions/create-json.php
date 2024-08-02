<?php
function addfiletojson($fileName)
{

    // get user email
    // check file exit for user email
    // append to the file

    $current_user = wp_get_current_user();

    if (!$current_user instanceof WP_User) {

        return false;
        // retun if user not found.
    }

    $user_email = $current_user->user_email;

    $Label = ucfirst(str_replace('-', ' ', $fileName));
    $shortcode_handle = str_replace("-", "_", $fileName);

    $data = '
    "' . $fileName . '": {
        "url": "widgets/' . $fileName . '/' . $fileName . '.php",
        "name": "' . $Label . '",
        "handle": "' . $shortcode_handle . '",
        "icon": "icons/' . $fileName . '.png"
    }';

    $jsonname = SC_CORE_PATH . 'shortcodes-extend/wac-shortcode-jsons/' . $user_email . '.json';
    if (file_exists($jsonname)) {
        $existingDataArray = '';
        $existingData = file_get_contents($jsonname);
        $existingData = rtrim($existingData, '}');
        $existingDataArray .=
            $existingData . ',
    ' . $data . '
}';

        file_put_contents($jsonname, $existingDataArray);
    } else {
        $data = '
{
    ' . $data . '
}';
        file_put_contents($jsonname, $data);
    }
    return true;
}
?>