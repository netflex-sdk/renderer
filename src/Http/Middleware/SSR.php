<?php

namespace Netflex\Render\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
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
    public function handle($request, Closure $next)
    {
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
    }
}
