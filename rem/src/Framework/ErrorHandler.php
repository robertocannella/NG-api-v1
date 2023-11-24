<?php

declare(strict_types=1);

namespace Framework;
use ErrorException;
use Throwable;

class ErrorHandler{

    public static  function handleError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ): bool {
        throw new ErrorException($errstr,0, $errno, $errfile, $errline);
    }
    public static function handleException(Throwable $exception) {

        if ($exception instanceof  \Framework\Exceptions\PageNotFoundException){

            http_response_code(404);

            $template = '404.php';

        }else{

            http_response_code(500);

            $template = '500.php';
        }

        $show_errors = true;

        if ($_ENV["SHOW_ERRORS"]){
            ini_set("display_errors", "1");
        }  else {
            ini_set("display_errors", "0");

//    error_reporting(E_ALL);
//
//    ini_set('error_log', '/var/www/html/api/v1/error.log');
//
//    ini_set( "log_errors", "1");

            error_log("Test");
            require "views/$template";
        }

        throw $exception;

    }
}