<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class UploadFile extends Model
{
    public static function uploadFile($destination, $image, $temperature, $options)
    {
        if ($image != null) {
            $logoPath = public_path('uploads') . $destination;
            $logoName = $image->getClientOriginalName();
            $unique_logoName = time() . "-" . $logoName . ($options['extension'] ?? '');
            $fileUrl = [
                'file_name' => $unique_logoName,
                'file_path' => $destination . '/' . $unique_logoName,
            ];
            try {
                $image->move($logoPath, $unique_logoName);
            } catch (Exception $error) {
                echo $error->getMessage();
            }

            if ($temperature != '') {
                $oldFilePath = public_path() . '/' . parse_url($temperature, PHP_URL_PATH);
                File::delete($oldFilePath);
            }
        } else {

            $fileUrl = $temperature;
        }

        return $fileUrl;
    }

    public static function uploadPhoto($destination, $image, $temperature, $getFileName = false)
    {
        if ($image != null) {
            $logoPath = public_path('uploads') . $destination;
            $logoName = $image->getClientOriginalName();
            $unique_logoName = time() . "-" . $logoName;
            $fileUrl = $getFileName ? '/'.$unique_logoName : $destination . '/' . $unique_logoName;
            try {
                $image->move($logoPath, $unique_logoName);
            } catch (Exception $error) {
                echo $error->getMessage();
            }

            if ($temperature != '') {
                $oldFilePath = public_path() . '/' . parse_url($temperature, PHP_URL_PATH);
                File::delete($oldFilePath);
            }
        } else {

            $fileUrl = $temperature;
        }

        return $fileUrl;
    }

    public static function deleteFile($destination, $temperature)
    {
        if ($temperature) {
            $filename = public_path() . '/uploads' . $destination . '/' . $temperature;
            File::delete($filename);
        }
    }
}