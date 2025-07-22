<?php
function icon_upload($file, $name)
{
    $tempFile = $file['tmp_name'];
    $fileName = $file['name'];
    $targetPath = 'uploads/';
    $targetPath = SC_CORE_PATH . 'widget-icons/icons/';
    if (!file_exists($targetPath)) {
        mkdir($targetPath, 0777, true);
    }
    $new_file_name = $name . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
    // $targetFile = $targetPath . $fileName;
    if (move_uploaded_file($tempFile, $targetPath . $new_file_name)) {

        return $fileName;
    } else {
        $error = error_get_last();
        echo "Error: " . $error['message'];

        return false;
    }

}



function create_font_css($imgName, $name)
{
    $targetPath = SC_CORE_PATH . 'widget-icons/icon.css';
    $css_content = file_get_contents($targetPath);
    $css_content .= '
.qd-icon-' . $name . ' {
    background-image: url("../widget-icons/icons/' . $name . '.png") !important;
}';
    $updated = file_put_contents($targetPath, $css_content);
    if ($updated) {
        return true;
    } else {
        return false;
    }
}
?>
