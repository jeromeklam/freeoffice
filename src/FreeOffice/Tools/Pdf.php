<?php
namespace FreeOffice\Tools;

/**
 *
 * @author jeromeklam
 *
 */
class Pdf
{

    /**
     * Get number of pages
     *
     * @param string $p_content
     * 
     * @return int
     */
    public static function countPages($p_content)
    {
        $num = preg_match_all("/\/Page\W/", $p_content, $dummy);
        return $num;
    }
}
