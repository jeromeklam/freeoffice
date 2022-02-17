<?php
namespace FreeOffice\Tools;

/**
 * Description of PdfObject
 * @author jeromeklam
 */
class PdfObject
{

    /**
     * Path to file
     * @var string
     */
    public $path = null;

    /**
     * Pages ??
     * @var string
     */
    public $pages = 'all';

    /**
     * Orientation
     * @var string
     */
    public $orientation = 'P';

    /**
     * Get portrait() code or landscape (L)
     * @return string
     */
    public function getOrientationCode()
    {
        return $this->orientation == 'horizontal' || $this->orientation == 'landscape' ? 'L' : 'P';
    }
}
