<?php

use Config\Services;

if (!function_exists('ordinal')) {
    function ordinal($number) {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            $abbreviation = 'th';
        } else {
            $abbreviation = $ends[$number % 10];
        }
        return $number . $abbreviation;
    }
}

// if (!function_exists('uploadImage')) {
//     function uploadImage($fieldId) {
//         $request = service('request');
//         $file = $request->getFile($fieldId);

//         $uploadFolderPath = realpath($_SERVER['DOCUMENT_ROOT'] . '/../../production/');
//         $destinationPath = $uploadFolderPath . '/public/uploads/vehicle_' . $fieldId . '/';
//         $newName = $file->getRandomName();
//         try {
//             $file->move($destinationPath, $newName);
//             return $newName;
//         } catch (\Exception $e) {
//             echo 'Error moving file: ' . $e->getMessage();
//             return false;
//         }
//     }
// }

if (!function_exists('sendEmail')) {
    function sendEmail($subject, $message, $sender, $receiver) {
        $email = \Config\Services::email();

        $email->setTo($receiver);
        $email->setFrom('Wheelpact', $sender);
        $email->setSubject($subject);
        $email->setMessage($message);

        return $email->send();
    }
}

function uploadImage($fieldId, $file, $newWidth = null, $newHeight = null) {
    $uploadFolderPath = realpath($_SERVER['DOCUMENT_ROOT'] . '/../../production/');
    $destinationPath = $uploadFolderPath . '/public/uploads/vehicle_' . $fieldId . '/';

    // Ensure the destination directory exists
    if (!is_dir($destinationPath)) {
        mkdir($destinationPath, 0777, true);
    }

    $newName = $file->getRandomName();

    try {
        // Move the file first
        $file->move($destinationPath, $newName);
        $fullPath = $destinationPath . $newName;

        // Compress and resize the image
        compressAndResizeImage($fullPath, $fullPath, 70, $newWidth, $newHeight);

        return $newName;
    } catch (\Exception $e) {
        echo 'Error moving or processing file: ' . $e->getMessage();
        return false;
    }
}


function compressAndResizeImage($source, $destination, $quality = 70, $newWidth = null, $newHeight = null) {
    $image = Services::image()->withFile($source);

    // Get original dimensions
    $origWidth = $image->getWidth();
    $origHeight = $image->getHeight();

    // If new width and height are not provided, use original dimensions
    $newWidth = $newWidth ?: $origWidth;
    $newHeight = $newHeight ?: $origHeight;

    // Resize and save the image
    $image->resize($newWidth, $newHeight, true, 'height')
        ->save($destination, $quality);
}

