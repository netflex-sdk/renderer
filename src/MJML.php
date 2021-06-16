<?php

namespace Netflex\Render;

use Illuminate\Support\HtmlString;

class MJML extends Renderer
{
    /** @var string Format */
    protected $format = 'mjml';

    /** @var string File extension */
    protected $extension = 'html';

    /**
     * Render from raw markup
     *
     * @param HtmlString|string $html
     * @return static
     */
    public static function from($html)
    {
        if ($html instanceof HtmlString) {
            $html = $html->__toString();
        }

        return static::url('data:text/mjml;base64,' . base64_encode($html));
    }
}
