<?php

use Config\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

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

if (!function_exists('sendEmail')) {
    function sendEmail($to, $toName, $subject, $message, $attachmentPath = null, $fromEmail = null, $fromName = null) {
        $email = \Config\Services::email();

        if ($fromEmail && $fromName) {
            $email->setFrom($fromEmail, $fromName);
        } else {
            $email->setFrom(FROM_EMAIL, FROME_NAME);
        }

        if ($attachmentPath) {
            $email->attach($attachmentPath);
        }

        $email->setTo($to, $toName);
        $email->setSubject($subject);
        $email->setMessage($message);
        $email->setMailType('html');

        if ($email->send()) {
            return true;
        } else {
            return $email->printDebugger(['headers']);
        }
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

if (!function_exists('getDiscountedAmount')) {
    function getDiscountedAmount($price, $discountPercent) {
        if ($discountPercent > 0) {
            return $price - ($price * ($discountPercent / 100));
        } else {
            return $price;
        }
    }
}

if (!function_exists('calculate_end_date')) {
    /**
     * Calculate end date based on the given duration.
     *
     * @param string $duration Duration value ('Monthly' or 'Yearly')
     * @return string Calculated end date in 'Y-m-d H:i:s' format
     */
    function calculate_end_date(string $duration): string {
        $startDate = new DateTime();

        if ($duration === 'Month') {
            $startDate->modify('+1 month');
        } elseif ($duration === 'Year') {
            $startDate->modify('+1 year');
        }

        return $startDate->format('Y-m-d H:i:s');
    }
}

if (!function_exists('generateUniqueNumericId')) {
    function generateUniqueNumericId($length, $prefix = null) {
        /* // Generate a unique hexadecimal ID */
        $uniqueHex = uniqid();

        /* // Convert the hexadecimal ID to a decimal number */
        $uniqueDecimal = hexdec($uniqueHex);

        /* // Convert the decimal number to a string */
        $uniqueString = (string) $uniqueDecimal;

        /* // Calculate the total length needed for the unique numeric part */
        $numericLength = $prefix ? $length - strlen($prefix) : $length;

        /* // Ensure the numeric part is the desired length */
        if (strlen($uniqueString) > $numericLength) {
            /* // If the string is too long, truncate it */
            $uniqueString = substr($uniqueString, 0, $numericLength);
        } elseif (strlen($uniqueString) < $numericLength) {
            /* // If the string is too short, pad it with zeros */
            $uniqueString = str_pad($uniqueString, $numericLength, '0', STR_PAD_LEFT);
        }

        /* // Add the prefix to the unique numeric string if the prefix is provided */
        $uniqueId = $prefix ? $prefix . $uniqueString : $uniqueString;

        return $uniqueId;
    }
}

if (!function_exists('generatePDF')) {
    function generatePDF($html, $filename = '', $stream = TRUE, $paper = 'A4', $orientation = 'portrait') {
        // Configure Dompdf according to your needs
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($options);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // Setup the paper size and orientation
        $dompdf->setPaper($paper, $orientation);

        // Render the PDF
        $dompdf->render();

        if ($stream) {
            // Stream the PDF to the browser
            $dompdf->stream($filename, array("Attachment" => 1));
        } else {
            // Ensure the filename is provided and valid
            if (empty($filename)) {
                $filename = 'document.pdf';
            }

            // Ensure the uploads/invoice directory exists

            $outputDir = WRITEPATH . 'uploads/invoice'; // Use a local path
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0777, true);
            }

            // Set the full path for the output file
            $filePath = $outputDir . '/' . $filename;

            // Save the PDF to the file
            file_put_contents($filePath, $dompdf->output());

            // Return the path to the saved file
            return $filePath;
        }
    }
}

if (!function_exists('imageToBase64')) {
    /**
     * Converts an image to a Base64 encoded string.
     *
     * @param string $imagePath The path to the image file.
     * @return string Base64 encoded string.
     */
    function imageToBase64($imagePath) {
        if (file_exists($imagePath)) {
            $imageData = file_get_contents($imagePath);
            return base64_encode($imageData);
        }
        return '';
    }
}
