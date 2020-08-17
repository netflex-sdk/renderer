<?php

namespace Netflex\Render;

use Netflex\Render\Contracts\ImageRenderer;

class JPG extends ImageRenderer
{
    protected $format = 'jpg';

    /**
     * The quality of the image, between 0-100
     *
     * @param integer $quality
     * @return static
     */
    public function quality(int $quality)
    {
        $quality = max(0, min(100, $quality));

        return $this->setOption('quality', $quality);
    }
}
