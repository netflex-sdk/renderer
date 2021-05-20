<?php

namespace Netflex\Render\Exceptions;

use Exception;

use Illuminate\Support\Str;

use Netflex\API\Traits\ParsesResponse;
use Psr\Http\Message\ResponseInterface;

use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use Facade\IgnitionContracts\BaseSolution;

class RenderException extends Exception implements ProvidesSolution
{
    use ParsesResponse;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $message = $this->parseResponse($response);

        if (!is_object($message)) {
            $error = json_decode('{' . Str::beforeLast(Str::after($message, '{'), '}') . '}');

            if (json_last_error() === JSON_ERROR_NONE && isset($error->message)) {
                $message = $error->message;
            }
        } else {
            if (property_exists($message, 'message')) {
                $message = $message->message;
            }
        }

        if (!is_string($message)) {
            $message = json_encode($message, JSON_PRETTY_PRINT);
        }

        parent::__construct($message);
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create('RenderException')
            ->setSolutionDescription($this->getMessage())
            ->setDocumentationLinks([
                'Netflex Renderer documentation' => 'https://github.com/netflex-sdk/renderer#readme',
            ]);
    }
}
