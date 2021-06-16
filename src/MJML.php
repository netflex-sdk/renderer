<?php

namespace Netflex\Render;

use Exception;
use Throwable;
use ErrorException;
use InvalidArgumentException;

use Illuminate\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class MJML extends Renderer
{
    /** @var string Format */
    protected $format = 'mjml';

    /** @var string File extension */
    protected $extension = 'html';

    /**
     * Render from a view
     *
     * @param View|string $view
     * @param \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param array $mergeData
     * @return static
     */
    public static function view($view, $data = [], $mergeData = [])
    {
        if (is_string($view)) {
            $path = resource_path(implode(DIRECTORY_SEPARATOR, ['views', str_replace('.', DIRECTORY_SEPARATOR, $view) . '.mjml']));

            if (file_exists($path)) {
                $hash = hash_file('md5', $path);

                $php = Cache::rememberForever($hash, function () use ($path) {
                    $content = file_get_contents($path);
                    return Blade::compileString(static::from($content)->blob());
                });

                $data = array_merge($data, $mergeData);

                $obLevel = ob_get_level();

                ob_start();
                extract($data, EXTR_SKIP);

                try {
                    eval('?' . '>' . $php);
                } catch (Exception $e) {
                    while (ob_get_level() > $obLevel) ob_end_clean();
                    throw $e;
                } catch (Throwable $e) {
                    while (ob_get_level() > $obLevel) ob_end_clean();
                    throw new ErrorException($e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine(), $e->getPrevious());
                }

                return ob_get_clean();
            }

            throw new InvalidArgumentException('MJML View [' . $view . '] not found.');
        }

        /** @var View $view */
        return static::from($view->render());
    }

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
