<?php
include "connectionToQueue/connect.php";

$data = isset($_GET['data']) ? json_decode($_GET['data'], true) : exit('Not found get parameters');


function verification(array $data) {
    if(count(array_intersect_key(array_flip(['x', 'y', 'width', 'height', 'return_url','path']), $data)) === 6) {
        foreach ($data as $key => $value) {
            if($key !== "return_url" && $key !== "path") {
                if(!is_int($value))
                    return "Crop parameters type is not integer";
            } else if($key === "path") {
                if(!is_string($value)) {
                    return "Image path is not string";
                } else {
                    $returnes = @get_headers($value);
                    if(!strpos($returnes[0], '200')) {
                        return "Broken path";
                    } else {
                        if(!strpos($value, '.jpg') && !strpos($value, '.png') && !strpos($value, '.jpeg')) {
                            return "Image not found";
                        }
                    }
                }
            } else {
                if(!is_string($value))
                    return "Url is not string";
            }
        }
        return true;
    } else {
        return "Error in parameters";
    }
}

if(is_array($data)) {
   if(is_string($res = verification($data))) {
       echo $res;
   } else {
       $queue->publish('task', $_GET['data']);
       echo "OK!";
   }
} else {
    echo "Error in parameters";
}



