<?php
    if(!function_exists('print_data')){
        function print_data($data){
            echo "<pre>";
            print_r($data);
            echo "</pre>";
        }
    }