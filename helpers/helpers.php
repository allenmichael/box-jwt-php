<?php

if (!function_exists('array_key_exists_and_not_null')) {
    /**
     * Returns trueif array key is defined and associated value is not null.  Otherwise, false.
     *
     * @param array  $array
     * @param string $key
     *
     * @return bool
     */
    function array_key_exists_and_not_null($array, $key)
    {
        return isset($array[$key]) && $array[$key] !== null;
    }
}

if (!function_exists('is_json')) {
    /**
     * Returns true if string is JSON.
     *
     * @param string $string
     *
     * @return bool
     */
    function is_json($string)
    {
        if (is_string($string)) {
            json_decode($string);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }
}

if (!function_exists('is_box_date_time')) {
    /**
     * @param string $date
     */
    function is_box_date_time($date)
    {

    }
}

if (!function_exists('file_hash')) {
    /**
     * @param string|resource|\Psr\Http\Message\StreamInterface $file       File path, file resource or StreamInterface
     *                                                                      instance.
     * @param bool                                              $raw_output When set to true, outputs raw binary data.
     *                                                                      False outputs lowercase hexits.
     *
     * @return null|string
     */
    function file_hash($file, $raw_output = false)
    {
        $hash = null;

        if (is_string($file) && file_exists($file)) {
            // hash string
            $hash = hash_file('sha1', $file, $raw_output);
        } elseif (is_resource($file)) {
            // hash file resource/stream
            rewind($file);
            $hashContext = hash_init('sha1');
            hash_update_stream($hashContext, $file);
            $hash = hash_final($hashContext, $raw_output);
            rewind($file);
        } elseif ($file instanceof \Psr\Http\Message\StreamInterface) {
            // hash StreamInterface
            $file->rewind();
            if ($file->getSize() < 65536) {
                $fileContents = $file->getContents();
                $hash         = hash('sha1', $fileContents, $raw_output);
            } else {
                $ctx = hash_init('sha1');
                while (!$file->eof()) {
                    $buffer = $file->read(65536);
                    hash_update($ctx, $buffer);
                }
                $hash = hash_final($ctx, $raw_output);
            }
            $file->rewind();
        }

        return $hash;
    }
}
