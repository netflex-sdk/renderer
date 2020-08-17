<?php

namespace Netflex\Render;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

class PDF extends Renderer
{
    protected $format = 'pdf';

    // Units
    const UNIT_PX = 'px';
    const UNIT_IN = 'in';
    const UNIT_CM = 'cm';
    const UNIT_MM = 'mm';

    // Metric paper formats
    const FORMAT_A0 = 'A0';
    const FORMAT_A1 = 'A1';
    const FORMAT_A2 = 'A2';
    const FORMAT_A3 = 'A3';
    const FORMAT_A4 = 'A4';
    const FORMAT_A5 = 'A5';
    const FORMAT_A6 = 'A6';

    // US paper formats
    const FORMAT_LETTER = 'Letter';
    const FORMAT_LEGAL = 'Legal';
    const FORMAT_TABLOID = 'Tabloid';
    const FORMAT_LEDGER = 'Ledger';

    /**
     * @param string $url
     * @param array $options
     */
    protected function __construct(string $url, $options = [])
    {
        parent::__construct($url, $options);
        $this->emulatedMedia(null);
        $this->margin('1cm');
    }

    /**
     * Enable the display of header and footer
     *
     * @param boolean $displayHeaderFooter
     * @return static
     */
    public function displayHeaderFooter(bool $displayHeaderFooter = true)
    {
        return $this->setOption('displayHeaderFooter', $displayHeaderFooter);
    }

    /**
     * Sets the HTML template for the print header.
     *
     * @param HtmlString|string $template
     * @return static
     */
    public function headerTemplateFrom($template)
    {
        if ($template instanceof HtmlString) {
            $template = $template->__toString();
        }

        $this->displayHeaderFooter();

        return $this->setOption('headerTemplate', $template);
    }

    /**
     * Sets the HTML template for the print header from a blade view
     *
     * @param View|string $view
     * @param \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param array $mergeData
     * @return static
     */
    public function headerTemplate($view, $data = [], $mergeData = [])
    {
        if (is_string($view)) {
            $view = View::make($view, $data, $mergeData);
        }

        $content = $view->render();

        return $this->headerTemplateFrom($content);
    }

    /**
     * Sets the HTML template for the print footer.
     *
     * @param HtmlString|string $template
     * @return static
     */
    public function footerTemplateFrom($template)
    {
        if ($template instanceof HtmlString) {
            $template = $template->__toString();
        }

        $this->displayHeaderFooter();

        return $this->setOption('footerTemplate', $template);
    }

    /**
     * Sets the HTML template for the print footer from a blade view
     *
     * @param View|string $view
     * @param \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param array $mergeData
     * @return static
     */
    public function footerTemplate($view, $data = [], $mergeData = [])
    {
        if (is_string($view)) {
            $view = View::make($view, $data, $mergeData);
        }

        $content = $view->render();

        return $this->footerTemplateFrom($content);
    }

    /**
     * Paper ranges to print, e.g., [1, 5], 8, [11, 13]
     *
     * @param array|int ...$ranges
     * @return static
     */
    public function pageRanges(...$ranges)
    {
        $value = array_reduce($ranges, function ($values, $range) {
            if (is_array($range)) {
                $values[] = implode('-', $range);
                return $values;
            }

            $values[] = $range;

            return $values;
        }, []);

        return $this->setOption('pageRanges', implode(', ', $value));
    }

    /**
     * Print background graphics. Defaults to false
     *
     * @param boolean $printBackground
     * @return static
     */
    public function printBackground(bool $printBackground = true)
    {
        return $this->setOption('printBackground', $printBackground);
    }

    /**
     * Paper top margin
     *
     * @param int|string $marginTop
     * @param string $unit
     * @return static
     */
    public function marginTop($marginTop, $unit = PDF::UNIT_CM)
    {
        $margin = $this->getOption('margin', []);
        $margin['top'] = static::appendUnit($marginTop, $unit);
        return $this->setOption('margin', $margin);
    }

    /**
     * Paper right margin
     *
     * @param int|string $marginRight
     * @param string $unit
     * @return static
     */
    public function marginRight($marginRight, $unit = PDF::UNIT_CM)
    {
        $margin = $this->getOption('margin', []);
        $margin['right'] = static::appendUnit($marginRight, $unit);
        return $this->setOption('margin', $margin);
    }

    /**
     * Paper bottom margin
     *
     * @param int|string $marginBottom
     * @param string $unit
     * @return static
     */
    public function marginBottom($marginBottom, $unit = PDF::UNIT_CM)
    {
        $margin = $this->getOption('margin', []);
        $margin['bottom'] = static::appendUnit($marginBottom, $unit);
        return $this->setOption('margin', $margin);
    }

    /**
     * Paper left margin
     *
     * @param int|string $marginLeft
     * @param string $unit
     * @return static
     */
    public function marginLeft($marginLeft, $unit = PDF::UNIT_CM)
    {
        $margin = $this->getOption('margin', []);
        $margin['left'] = static::appendUnit($marginLeft, $unit);
        return $this->setOption('margin', $margin);
    }

    /**
     * Paper margins
     *
     * @param int|string ...$values
     * @return static
     */
    public function margin(...$values)
    {
        switch (count($values)) {
            case 1:
                $this->marginTop($values[0]);
                $this->marginRight($values[0]);
                $this->marginBottom($values[0]);
                return $this->marginLeft($values[0]);
            case 2:
                $this->marginTop($values[0]);
                $this->marginRight($values[1]);
                $this->marginBottom($values[0]);
                return $this->marginLeft($values[1]);
            case 3:
                $this->marginTop($values[0]);
                $this->marginRight($values[1]);
                return $this->marginBottom($values[2]);
            case 4:
                $this->marginTop($values[0]);
                $this->marginRight($values[1]);
                $this->marginBottom($values[2]);
                return $this->marginLeft($values[3]);
            default:
                return $this;
        }
    }

    /**
     * Paper format. If set, takes priority over width or height options. Defaults to 'A4'
     *
     * @param string $format
     * @return static
     */
    public function format(string $format = PDF::FORMAT_A4)
    {
        if (in_array($format, static::formats())) {
            if ($this->getOption('width') || $this->getOption('height')) {
                $this->setOption('width', null);
                $this->setOption('height', null);
            }

            return $this->setOption('format', $format);
        }

        return $this;
    }

    /**
     * Appends the given unit to value if the unit is valid
     *
     * @param int|string $value
     * @param string|null $unit
     * @return int|string
     */
    protected static function appendUnit($value, $unit = null)
    {
        if (is_string($value)) {
            foreach (static::units() as $validUnit) {
                if (Str::endsWith($value, $validUnit)) {
                    return $value;
                }
            }
        }

        if (in_array($unit, static::units())) {
            return ((string) $value) . $unit;
        }

        return $value;
    }

    /**
     * Sets the media emulation. Defaults to NULL
     *
     * @param string|null $media 'screen', 'print', or NULL
     * @return static
     */
    public function emulatedMedia($media = null)
    {
        if (is_null($media) || in_array($media, ['screen', 'print'])) {
            return $this->setOption('emulatedMedia', $media);
        }

        return $this;
    }

    /**
     * Paper width
     *
     * @param integer $width
     * @return static
     */
    public function width(int $width)
    {
        if ($this->getOption('format')) {
            $this->setOption('format', null);

            if (!$this->getOption('height')) {
                $this->setOption('height', $width);
            }
        }

        return $this->setOption('width', $width);
    }

    /**
     * Paper height
     *
     * @param integer $width
     * @return static
     */
    public function height(int $height)
    {
        if ($this->getOption('format')) {
            $this->setOption('format', null);

            if (!$this->getOption('width')) {
                $this->setOption('width', $height);
            }
        }

        return $this->setOption('height', $height);
    }

    /**
     * Whether or not to prefer page size as defined by css. Defaults to false, in which case the content will be scaled to fit the paper size.
     *
     * @param boolean $preferCSSPageSize
     * @return static
     */
    public function preferCSSPageSize(bool $preferCSSPageSize = true)
    {
        return $this->setOption('preferCSSPageSize', $preferCSSPageSize);
    }

    /**
     * Paper orientation
     *
     * @param boolean $landscape
     * @return static
     */
    public function landscape(bool $landscape = true)
    {
        return $this->setOption('landscape', $landscape);
    }

    /**
     * Sets the document scaling factor
     *
     * @param float $scale Between 0.1 - 2.0
     * @return static
     */
    public function scale(float $scale = 1.0)
    {
        return $this->setOption('scale', $scale);
    }

    /**
     * Get a list of supported units
     *
     * @return string[]
     */
    public static function units()
    {
        return [
            static::UNIT_PX,
            static::UNIT_MM,
            static::UNIT_CM,
            static::UNIT_IN,
        ];
    }

    /**
     * Get a list of supported paper formats
     *
     * @return string[]
     */
    public static function formats()
    {
        return [
            static::FORMAT_A0,
            static::FORMAT_A1,
            static::FORMAT_A2,
            static::FORMAT_A3,
            static::FORMAT_A4,
            static::FORMAT_A5,
            static::FORMAT_A6,
            static::FORMAT_LETTER,
            static::FORMAT_LEGAL,
            static::FORMAT_TABLOID,
            static::FORMAT_LEDGER,
        ];
    }
}
