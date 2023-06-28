<?php
    if(!function_exits(print_data)){
        function print_data($date){
            echo "<pre>";
            print_r($data);
            echo "</pre>";
        }
    }