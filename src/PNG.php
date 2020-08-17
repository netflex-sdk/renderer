<?php

namespace Netflex\Render;

use Netflex\Render\Contracts\ImageRenderer;

class PNG extends ImageRenderer
{
    protected $format = 'png';

    public function transparent(bool $transparent = true)
    {
        return $this->setOption('omitBackground', $transparent);
    }
}
