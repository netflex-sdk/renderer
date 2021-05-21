<?php

namespace Netflex\Render\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Netflex\Render\HTML;

class SSR
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     *
     * @throws RenderException
     */
    public function handle($request, Closure $next, $role = null)
    {
        $render = function (Request $request) use ($next) {
            /** @var Response */
            $response = $next($request);

            if ($content = $response->getContent()) {
                $request = new Request();

                $request->headers->replace(
                    $response->headers->all()
                );

                return HTML::from($content)->toResponse($request);
            }

            return $response;
        };

        if ($role === 'cache') {
            $key = request_ssr_key($request);

            return Cache::rememberForever($key, function () use ($render, $request) {
                return $render($request);
            });
        }

        return $next($request);
    }
}
