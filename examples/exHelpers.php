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