<?php

namespace Netflex\Render\Concerns;

use Illuminate\Routing\Controller;
use Netflex\Render\Http\Middleware\SSR;

trait ServerSideRenderCached
{
    public static function bootRendersServerside()
    {
        static::booted(function (Controller $controller) {
            $controller->middleware(SSR::class, ['cache']);
        });
    }
}
