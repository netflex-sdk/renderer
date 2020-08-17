<?php

namespace Netflex\Render\View;

use Closure;
use Illuminate\Support\Facades\Blade;

class Directives
{
    const PREFIX = 'pdf';

    protected static function date()
    {
        return function () {
            return '<span class="date"></span>';
        };
    }

    protected static function title()
    {
        return function () {
            return '<span class="title"></span>';
        };
    }

    protected static function url()
    {
        return function () {
            return '<span class="url"></span>';
        };
    }

    protected static function page_number()
    {
        return function () {
            return '<span class="pageNumber"></span>';
        };
    }

    protected static function total_pages()
    {
        return function () {
            return '<span class="totalPages"></span>';
        };
    }

    public static function register()
    {
        $directives = [
            'date',
            'title',
            'url',
            'page_number',
            'total_pages'
        ];

        foreach ($directives as $directive) {
            $name = implode('_', [static::PREFIX, $directive]);

            /** @var Closure */
            $handler = forward_static_call([static::class, $directive]);

            Blade::directive($name, $handler);
        }
    }
}
