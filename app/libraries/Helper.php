<?php
class Helper
{
    // DUMP
    public static function dump($data)
    {
        echo '<pre><code>';
        print_r($data);
        echo '</code></pre>';
    }

    // ERRORLOG
    public static function log($type, $data)
    {
        if ($type == 'event') {
            error_log('Event: ' . date('Ymd h:i:s') . " - " . $data);
        } else if ($type == 'error') {
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            error_log('Error: ' . date('Ymd h:i:s') . " - " . $caller['line'] . ' ' . $caller['file'] . "\n" . $data);
        } else if ($type == 'debug') {
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            error_log("\n" . date('Ymd h:i:s') . "\n" . $caller['line'] . ' ' . $caller['file'] . "\n" . print_r($data, true) .
                "\n");
        }
    }

    // ENCRYPT DATA
    static function crypt($action, $string)
    {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'This is my secret key';
        $secret_iv = 'This is my secret iv';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}
