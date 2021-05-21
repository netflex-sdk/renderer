<?php

namespace Netflex\Render\Concerns;

use Illuminate\Routing\Controller;
use Netflex\Render\Http\Middleware\SSR;

trait ServerSideRenderCached
{
    public static function bootServerSideRenderCached()
    {
        static::booted(function (Controller $controller) {
            $ttl = $controller->serverSideRenderCacheTTL ?? 0;
            $controller->middleware(SSR::class, ['cache', $ttl]);
        });
    }
}
