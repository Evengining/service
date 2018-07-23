<?php
include "connectionToQueue/connect.php";


function crop($reid, $channel, $task) {
    $data = json_decode($task, true);

    $aNewImageWidth = $data['x'];
    $aNewImageHeight = $data['y'];
    $lAllowedExtensions = [1 => "gif", 2 => "jpeg", 3 => "png", 4 => "jpeg"];

    // Получаем размеры и тип изображения в виде числа
    list($lInitialImageWidth, $lInitialImageHeight, $lImageExtensionId) = getimagesize($data['path']);

    $lImageExtension = $lAllowedExtensions[$lImageExtensionId];

    // Получаем название функции, соответствующую типу, для создания изображения
    $func = 'imagecreatefrom' . $lImageExtension;

    // Создаём дескриптор исходного изображения
    $lInitialImageDescriptor = $func($data['path']);

    // Определяем отображаемую область
    $lInitialImageCroppingX = 0;
    $lInitialImageCroppingY = 0;
    if ($aNewImageWidth / $aNewImageHeight > $lInitialImageWidth / $lInitialImageHeight) {
        $lCroppedImageWidth = (int)floor($lInitialImageWidth);
        $lCroppedImageHeight = (int)floor($lInitialImageWidth * $aNewImageHeight / $aNewImageWidth);
        $lInitialImageCroppingY = (int)floor(($lInitialImageHeight - $lCroppedImageHeight) / 2);
    } else {
        $lCroppedImageWidth = (int)floor($lInitialImageHeight * $aNewImageWidth / $aNewImageHeight);
        $lCroppedImageHeight = (int)floor($lInitialImageHeight);
        $lInitialImageCroppingX = (int)floor(($lInitialImageWidth - $lCroppedImageWidth) / 2);
    }
    // Создаём дескриптор для выходного изображения
    $lNewImageDescriptor = imagecreatetruecolor($data['width'], $data['height']);
    imagecopyresampled($lNewImageDescriptor, $lInitialImageDescriptor, 0, 0, $lInitialImageCroppingX, $lInitialImageCroppingY, $aNewImageWidth, $aNewImageHeight, $lCroppedImageWidth, $lCroppedImageHeight);
    $func = 'image' . $lImageExtension;

    $nameNewImg = uniqid() . ".png";
    $newImgPath = '../images/' . $nameNewImg;
    $func($lNewImageDescriptor, $newImgPath);
     
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'config/config.json');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  
    
    $dns = json_decode(curl_exec(), true)['dns'];
   
    
    curl_setopt($curl, CURLOPT_URL, $data['return_url'] . '?url=' . $dns . '/' . $nameNewImg);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    curl_exec();
    curl_close($curl);
}

$queue->subscribe(['task'], 'crop');
