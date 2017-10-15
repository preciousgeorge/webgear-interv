<?php
// src/app/Utils/LoggerUtils.php

namespace App\Utils;

class LoggerUtils
{

    private $error_log;

    private $success_log;

    public function __construct()
    {
        $this->error_log = __DIR__.'../../../logs/error.log';
        $this->$success_log = __DIR__.'../../../logs/success.log';
    }
    public static function err($data)
    {
        file_put_contents($this->error_log, print_r($array, true), FILE_APPEND);
    }

    public static function success($data)
    {
        file_put_contents($this->$success_log, print_r($array, true), FILE_APPEND);
    }
}

