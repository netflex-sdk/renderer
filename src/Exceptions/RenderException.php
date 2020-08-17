<?php

namespace Netflex\Render\Exceptions;

use Exception;

use Illuminate\Support\Str;
use GuzzleHttp\Exception\GuzzleException;

class RenderException extends Exception
{
    public function __construct(GuzzleException $e)
    {
        $message = $e->getMessage();
        $error = json_decode('{' . Str::beforeLast(Str::after($message, '{'), '}') . '}');

        if (json_last_error() === JSON_ERROR_NONE && isset($error->message)) {
            $message = $error->message;
        }

        parent::__construct($message);
    }
}
