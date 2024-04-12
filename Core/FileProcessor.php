<?php

namespace SlimSecure\Core;

use SlimSecure\Configs\Env;

/**
 * Class FileProcessor
 *
 * Provides functionality to handle file processing tasks such as uploading, converting,
 * compressing, resizing, and exporting files in various formats. This class is designed
 * to work with file arrays provided by PHP's global $_FILES.
 */
class FileProcessor
{
    /**
     * Uploads files to the specified directory and returns an array of successfully uploaded file names.
     *
     * This method checks if the directory exists, creates it if it does not, and then
     * processes each uploaded file. Files are only uploaded if they are of supported types,
     * as defined in the application's environment settings.
     *
     * @param string $uploadsDir The directory where files should be uploaded.
     * @param array $files The array of files from a file upload input.
     * @return array An array of uploaded file names.
     */
    public static function upload(string $uploadsDir, array $files)
    {
        $fileNames = [];

        // Filter out any empty file name entries to handle form submissions without files
        if (!empty(array_filter($files['fileUpload']['name']))) {
            // Ensure the upload directory exists, create it if not
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);
            }

            // Process each file uploaded
            foreach ($files['fileUpload']['name'] as $id => $val) {
                $fileName        = $files['fileUpload']['name'][$id];
                $tempLocation    = $files['fileUpload']['tmp_name'][$id];
                $targetFilePath  = $uploadsDir . '/' . $fileName;
                $fileType        = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                $uploadDate      = date('Y-m-d H:i:s');

                // Validate file type against supported types
                if (in_array($fileType, Env::SUPPORTED_FILE_TYPES)) {
                    // Move the file to the target path
                    if (move_uploaded_file($tempLocation, $targetFilePath)) {
                        array_push($fileNames, strtolower(Env::SYSTEM_NAME) . '_' . $uploadDate . '.' . $fileType);
                    }
                }
            }
        }
        return $fileNames;
    }

    /**
     * Converts an image from one format to another.
     * 
     * @param string $sourcePath Path to the source image.
     * @param string $targetPath Path where the converted image should be saved.
     * @param string $targetType The desired image format (e.g., 'jpg', 'png', 'gif').
     * @return bool Returns true on success or false on failure.
     */
    public static function convertImage(string $sourcePath, string $targetPath, string $targetType): bool
    {
        list($width, $height, $type) = getimagesize($sourcePath);
        switch ($type) {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($sourcePath);
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($sourcePath);
                break;
            default:
                return false;
        }

        switch (strtolower($targetType)) {
            case 'jpg':
            case 'jpeg':
                return imagejpeg($image, $targetPath);
            case 'png':
                return imagepng($image, $targetPath);
            case 'gif':
                return imagegif($image, $targetPath);
            default:
                return false;
        }
    }

    /**
     * Compresses an image to reduce file size.
     *
     * @param string $sourcePath Path to the source image.
     * @param string $targetPath Path where the compressed image should be saved.
     * @param int $quality Compression quality (1-100, higher means better quality and bigger file).
     * @return bool Returns true on success or false on failure.
     */
    public static function compressImage(string $sourcePath, string $targetPath, int $quality = 75): bool
    {
        list($width, $height, $type) = getimagesize($sourcePath);
        $image = imagecreatefromjpeg($sourcePath); // This example assumes JPEG for simplicity
        return imagejpeg($image, $targetPath, $quality);
    }


    /**
     * Resizes an image to specified dimensions.
     *
     * @param string $sourcePath Path to the source image.
     * @param string $targetPath Path where the resized image should be saved.
     * @param int $newWidth The new width of the image.
     * @param int $newHeight The new height of the image.
     * @return bool Returns true on success or false on failure.
     */
    public static function resizeImage(string $sourcePath, string $targetPath, int $newWidth, int $newHeight): bool
    {
        list($width, $height) = getimagesize($sourcePath);
        $image_p = imagecreatetruecolor($newWidth, $newHeight);
        $image = imagecreatefromjpeg($sourcePath); // Assuming JPEG for simplicity
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        return imagejpeg($image_p, $targetPath, 100); // Save with best quality
    }


    /**
     * Exports content to a PDF file.
     *
     * @param string $htmlContent HTML content to be converted to PDF.
     * @param string $filePath Path where the PDF should be saved.
     * @return bool Returns true on success or false on failure.
     */
    public static function exportPdf(string $htmlContent, string $filePath): bool
    {
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($htmlContent);
        $output = $mpdf->Output($filePath, 'F');
        return $output ? true : false;
    }

    /**
     * Exports content to a Microsoft Word document (.docx).
     *
     * @param string $htmlContent HTML content to be converted to DOCX.
     * @param string $filePath Path where the DOCX should be saved.
     * @return bool Returns true on success or false on failure.
     */
    public static function exportDocx(string $htmlContent, string $filePath): bool
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlContent);
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filePath);
        return true;
    }

    /**
     * Enhances an image's quality and attributes (like brightness and contrast).
     *
     * @param string $sourcePath Path to the source image.
     * @param string $targetPath Path where the enhanced image should be saved.
     * @param int $brightness Brightness level (-255 to 255).
     * @param int $contrast Contrast level (-100 to 100).
     * @return bool Returns true on success or false on failure.
     */
    public static function enhanceImage(string $sourcePath, string $targetPath, int $brightness = 0, int $contrast = 0): bool
    {
        $image = imagecreatefromjpeg($sourcePath); // Assuming JPEG
        if ($brightness != 0) {
            imagefilter($image, IMG_FILTER_BRIGHTNESS, $brightness);
        }
        if ($contrast != 0) {
            imagefilter($image, IMG_FILTER_CONTRAST, $contrast);
        }
        return imagejpeg($image, $targetPath, 100);
    }
}
