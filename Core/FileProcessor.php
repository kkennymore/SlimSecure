<?php

namespace SlimSecure\Core;
use SlimSecure\Configs\Env;

class FileProcessor
{
    /**
     * @var string  declaration of class properties
     */

    /**
     * @var array  loop through the uploaded files
     */
    public static function upload(string $uploadsDir, array $files)
    {
        $fileNames = [];

        if (!empty(array_filter($files['fileUpload']['name']))) {
            /**
             * @var string check if the directory exist, else create it
             */
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir);
            }
            /**
             * @var array  loop through the uploaded files
             */
            foreach ($files['fileUpload']['name'] as $id => $val) {
                // Get files upload path
                $fileName        = $files['fileUpload']['name'][$id];
                $tempLocation    = $files['fileUpload']['tmp_name'][$id];
                $targetFilePath  = $uploadsDir . $fileName;
                $fileType        = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                $uploadDate      = date('Y-m-d H:i:s');
                if (in_array($fileType, Env::SUPPORTED_FILE_TYPES)) {
                    if (move_uploaded_file($tempLocation, $targetFilePath)) {
                        array_push($fileNames, strtolower(Env::SYSTEM_NAME) . '_' . $uploadDate . $fileType);
                    }
                }
            }
        }
        return $fileNames;
    }

    public static function convertImage()
    {
    }

    public static function compressImage()
    {
    }

    public static function resizeImage()
    {
    }

    public static function exportPdf()
    {
    }

    public static function exportDocx()
    {
    }

    public static function enhanceImage()
    {
    }
}
