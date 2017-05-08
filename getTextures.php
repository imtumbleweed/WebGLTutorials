<?php

    $dir = "textures/";

    $return_array = array();

    if(is_dir($dir)) {
        if($dh = opendir($dir)) {
            while(($file = readdir($dh)) != false) {
                if($file == "." or $file == "..") {

                    // Skip upper directories
                } else {

                    // Add the file to the array
                    $return_array[] = $file;
                }
            }
        }
        // Return list of files in JSON format
        echo json_encode($return_array);
    }

?>