<?php

namespace Netflex\Render\Mail;

use Netflex\Render\MJML;

trait RendersMJML
{
    /**
     * Set the view and view data for the message.
     *
     * @param  string  $view
     * @param  array  $data
     * @return $this
     */
    public function mjml($view, array $data = [])
    {
        $mjml = MJML::view($view, $data)->blob();
        return $this->html($mjml);
    }
}
