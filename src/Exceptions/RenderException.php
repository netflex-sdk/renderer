<?php

namespace Netflex\Render\Exceptions;

use Exception;

use Illuminate\Support\Str;
use Netflex\API\Traits\ParsesResponse;
use Psr\Http\Message\ResponseInterface;

class RenderException extends Exception
{
    use ParsesResponse;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {   
        $message = $this->parseResponse($response);
        $error = json_decode('{' . Str::beforeLast(Str::after($message, '{'), '}') . '}');

        if (json_last_error() === JSON_ERROR_NONE && isset($error->message)) {
            $message = $error->message;
        }

        parent::__construct($message);
    }
}
