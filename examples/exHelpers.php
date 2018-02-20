<?php

if (!function_exists('createTempFile')) {
    /**
     * Create a temp file path.  If $autoPopulate is true (default), the temp
     * file will be created with auto generated content.
     *
     * @param bool $autoPopulate
     *
     * @return bool|string
     */
    function createTempFile($autoPopulate = true)
    {
        $tempDir      = sys_get_temp_dir();
        $tempFileName = tempnam($tempDir, 'box');

        if ($autoPopulate) {
            file_put_contents($tempFileName, 'AUTO_GENERATED_CONTENT: ' . bin2hex(openssl_random_pseudo_bytes(8)));
        }

        return $tempFileName;
    }
}

if (!function_exists('createTempImage')) {
    function createTempImage()
    {
        // Create image
        $png = imagecreatetruecolor(800, 600);
        imagesavealpha($png, true);

        $trans_colour = imagecolorallocatealpha($png, 0, 0, 0, 127);
        imagefill($png, 0, 0, $trans_colour);

        $red = imagecolorallocate($png, 255, 0, 0);
        imagefilledellipse($png, 400, 300, 400, 300, $red);

        // Create temp filename
        $tempDir      = sys_get_temp_dir();
        $tempFileName = $tempDir . DIRECTORY_SEPARATOR . 'box' . bin2hex(random_bytes(4)) . '.png';

        // Save image
        imagepng($png, $tempFileName);

        return $tempFileName;
    }
}