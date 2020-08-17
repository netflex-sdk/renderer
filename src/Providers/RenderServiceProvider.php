<?php

namespace Netflex\Render\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Netflex\Render\HTML;
use Netflex\Render\JPG;
use Netflex\Render\PDF;
use Netflex\Render\PNG;

use Netflex\Render\View\Directives;

class RenderServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->registerMacros();
        $this->registerDirectives();
    }

    public function registerMacros()
    {
        $renderers = [
            HTML::class,
            JPG::class,
            PDF::class,
            PNG::class
        ];

        foreach ($renderers as $renderer) {
            $type = basename(str_replace('\\', '/', $renderer));

            View::macro('render' . $type, function () use ($renderer) {
                /** @var \Netflex\Render\Renderer $renderer */
                return $renderer::view($this);
            });
        }
    }

    public function registerDirectives()
    {
        Directives::register();
    }
}
