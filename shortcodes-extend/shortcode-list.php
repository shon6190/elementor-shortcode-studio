<?php

class ShortCodeListpage {

    public function __construct() {
        $this->admin_includes_files();
        $this->registrants_header();
        $this->list_filter_user_details();
        $this->registrants_footer();
    }

    /**
     * Registering the scripts and styles for the events section
     */
    public function admin_includes_files() {
        wp_enqueue_script('shortcode-js', SC_CORE_URL .'assets/js/shortcode.js' );
        wp_localize_script('shortcode-js', 'WACObj', array( 'loader_url' => SC_CORE_URL .'/shortcodes-extend/ajax-loader.svg' , 'ajaxurl' => admin_url('admin-ajax.php'), ));
    }

    /**
     * Displaying the header section of the Events page
     */
    public function registrants_header() { ?>
    <style>
        .d-none{
            display:none;
        }
        .margin-t-30{
            margin-top:30px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-group {
        margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 12px;
            font-weight: 500;
            font-size: 16px;
        }
        .form-group input[type="text"] {
            width: 400px;
            padding: 5px 10px ;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }
        .shortcodefrm{
            margin-top: 15px;
        }
        .shortcodefrm .radio-input-wrap{
            /* margin-top: 15px; */
            display: flex;
        }
        .shortcodefrm .radio-input-wrap > div {
            display: flex;
            align-items: center;
        }
        .shortcodefrm .radio-input-wrap > div:not(:first-child){
            margin-left: 15px;
        }
        .shortcodefrm .radio-input-wrap > div input {
            margin-right : 10px;
        }
        .shortcodefrm .radio-input-wrap > div label{
            margin-bottom: 5px;
        }
        .shortcodefrm input[type="submit"]{
            padding: 4px 8px;
            text-decoration: none;
            border: 1px solid #2271b1;
            border-radius: 2px;
            text-shadow: none;
            font-weight: 600;
            font-size: 13px;
            line-height: normal;
            color: #2271b1;
            background: #f6f7f7;
        }
    </style>
    <?php
    }
    /**
     * Displaying the Events list
     */
    public function list_filter_user_details() { ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"> All Shortcodes</h1>
        <a href="javascript:void(0);" class="page-title-action">Add New</a>
        <hr class="wp-header-end">
            <form method="post" id="wac-shortcodeFrm" class="shortcodefrm d-none">
                <div class="form-group">
                    <label for="wac_shortcode_name">ShortCode Name: (No special Characters)</label>
                    <input type="text" id="wac_shortcode_name" class="alphabetic_only" name="wac_shortcode_name" required>
                </div>
                 <div class="form-group file-upload">
                    <label for="file">Select Icon:</label>
                    <input type="file" id="shortcode_file" name="file" accept=".png">
                 </div>
                 <input type="hidden"  name="action" value="wac_create_shortcode">
                <input type="submit" id="submitShortcode" value="Create">
                <p class="response-msg"><p>
            </form>               

            
            <?php 
            //$filename = SC_CORE_PATH . 'shortcodes-extend/wac-shortcode.json';

            $shortcode_json_dir = SC_CORE_PATH . 'shortcodes-extend/wac-shortcode-jsons';
            $shortcode_json_fiels = scandir($shortcode_json_dir);
            $TableHtml = '';
            $tableContents = '';
            $Count = 1;
            foreach ($shortcode_json_fiels as $file) {
                // Skip "." and ".." entries, which represent the current and parent directories
                if ($file === '.' || $file === '..') {
                    continue;
                }
                
                // Check if the current entry is a file (not a directory)
                if (is_file($shortcode_json_dir . '/' . $file)) {

                  // check the file is a json
                  if( 'json' !== pathinfo($shortcode_json_dir . '/' . $file, PATHINFO_EXTENSION))
                  {
                    continue;
                  }
                    
                    $file_name = basename($shortcode_json_dir . '/' . $file);
                    $jsonData = file_get_contents($shortcode_json_dir . '/' . $file);
                    $dataArray = json_decode($jsonData, true);

                    if ($dataArray !== null) {
                      foreach ($dataArray as $data) {
                        if($data['icon']){
                            $file_path = SC_CORE_PATH.'widget-icons/'.$data['icon'];
                            if (file_exists($file_path)) {
                                $file_url = plugin_dir_url(__DIR__).'widget-icons/'.$data['icon'];
                                $iconImg = '<img src="'. $file_url .'" height="150" >';
                            } else {
                                $iconImg = '';
                            }
                        } else {
                          $iconImg = '';
                        }
                          $tableContents .= ' <tr>
                                              <td>'. $Count .'</td>
                                              <td>'. $data['name'] .'</td>
                                              <td>' . $iconImg . '</td>
                                              <td>' . $data['url'] .'</td>                                            
                                              <td>' . $data['handle'] .'</td>                                            
                                              <td>' .  str_replace('.json', '', $file_name).'</td>                                            
                                          </tr>';
                      $Count++;
                      }

                    }
                    else{
                      $tableContents .= '<tr>
                      <td colspan="5" >Invalid json data at'  . $file_name .'</td>
                      </tr>';
                    }
                    
                }
            }
            // build table
            $TableHtml .='<table border="1">
                                    <thead>
                                    <tr>
                                        <th>SI No.</th>
                                        <th>Name</th>
                                        <th>Icon</th>
                                        <th>File Path</th>
                                        <th>handle</th>
                                        <th>Developer</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    '. $tableContents .'
                                    </tbody>
                                </table> ';
     
            ?> 
        <div class="table-wrapper margin-t-30">
             <?php echo $TableHtml; ?>   
        </div>
        <div id="ajax-response"></div>
        <div class="clear"></div>
    </div>
    <?php
    }

    /**
     * Displaying the header section of the Events page
     */
    public function registrants_footer() {
        ?>
        <!-- ---------- Add footer Contents ------------ -->
        <?php
    }

}
