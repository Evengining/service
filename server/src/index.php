<?php
declare(strict_types=1);
include "connectionToQueue/connect.php";

$data = isset($_GET['data']) ? json_decode($_GET['data'], true) : exit('Not found get parameters');

function validate(int $x, int $y, int $width, int $height, string $return_url, string $path) {
    $returnes = @get_headers($path);

    if(!strpos($returnes[0] ?? 'false', '200'))
        throw new Exception('Broken path');


    if(!strpos($path, '.jpg') && !strpos($path, '.png') && !strpos($path, '.jpeg'))
        throw new Exception('Image not found');
}

try {
    validate($data['x'], $data['y'], $data['width'], $data['height'], $data['return_url'], $data['path']);
    $queue->publish('task', $_GET['data']);
    echo "OK!";
} catch (Exception $e) {
    echo $e->getMessage();
} catch (TypeError $e) {
    echo "Error in parameters.";
}
