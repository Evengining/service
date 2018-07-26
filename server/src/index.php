<?php
include "connectionToQueue/connect.php";

$data = isset($_GET['data']) ? json_decode($_GET['data'], true) : exit('Not found get parameters');

try {
    if(!is_array($data))
        throw new Exception('Error int parameters.');

    else if(count(array_intersect_key(array_flip(['x', 'y', 'width', 'height', 'return_url', 'path']), $data)) !== 6)
        throw new Exception('Error in parameters.');


    else if(!is_int($data['x']) || !is_int($data['y']) || !is_int($data['width']) || !is_int($data['height']))
        throw new Exception('Crop parameters type is not integer.');


    else if(!is_string($data['path']) || !is_string($data['return_url']))
        throw new Exception('Return_url or path parameters type is not string.');


    $returnes = @get_headers($data['path']);

    if(!strpos($returnes[0], '200'))
        throw new Exception('Broken path');


    else if(!strpos($data['path'], '.jpg') && !strpos($data['path'], '.png') && !strpos($data['path'], '.jpeg'))
        throw new Exception('Image not found');


    $queue->publish('task', $_GET['data']);
    echo "OK!";
} catch (Exception $e) {
    echo $e->getMessage();
}



