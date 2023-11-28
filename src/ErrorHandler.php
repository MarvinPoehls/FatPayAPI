<?php

namespace Src;

use Throwable;

class ErrorHandler
{
    public static function handleException(Throwable $exception)
    {
        echo json_encode([
            "status" => "ERROR",
            "errormessage" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine()
        ]);
    }
}