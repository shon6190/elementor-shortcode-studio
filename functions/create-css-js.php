<?php
/**
 * Shortcode files generate  function
 *
 * @return void
 */

function createCssAndJs($filePath, $name, $wac_shortcode_files)
{
    $createFlag = false;

    foreach ($wac_shortcode_files as $shortcode_files) {
        if (in_array($shortcode_files, ['css', 'scss', 'js'])) {
            $fileExtension = $shortcode_files;
            $shortcode_name = str_replace('-', '_', $name);
            // Create file based on the specified extension
            $fileContent = '/* ' . strtoupper($fileExtension) . ' file content */';
            if ($fileExtension == 'js') {
                $fileContent .= '
(function ($) {
    var ' . $shortcode_name . ' = function ($scope, $) {
        /* Add the script here */
    }
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/' . $name . '.default", ' . $shortcode_name . ');
    });
})(jQuery);';
            }
            $file = file_put_contents($filePath . '/' . $name . '.' . $fileExtension, $fileContent);
            $createFlag = $file !== false;
        }
        if ($shortcode_files == 'img') {
            $folderPath = $filePath . '/images';
            mkdir($folderPath, 0755, true);

            $createFlag = $file !== false;
        }
    }
    return $createFlag;
}



?>