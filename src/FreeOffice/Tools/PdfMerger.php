<?php
namespace FreeOffice\Tools;

/**
 * 
 */
class PdfMerger
{

    /**
     * Files
     * @var [PdfObject]
     */
    private $_files;

    /**
     * Add a PDF file
     * 
     * @param string $filepath
     * @param string $pages
     * @param string 
     * 
     * @return self
     */
    public function addFile($p_filepath, $p_pages = 'all', $p_orientation = 'vertical')
    {
        if (file_exists($p_filepath)) {
            $file = new PdfObject;
            if (strtolower($p_pages) != 'all') {
                $file->pages = $this->_rewritepages($p_pages);
            }
            $file->orientation = $p_orientation;
            $file->path        = $p_filepath;
            $this->_files[]    = $file;
        } else {
            throw new \Exception('Could not locate PDF on ' . $p_filepath);
        }
        return $this;
    }

    /**
     * Merge
     * 
     * @param string  $p_outputmode
     * @param string  $p_outputname
     * @param boolean $p_peer
     * 
     * @return boolean
     */
    public function merge($p_outputmode = 'browser', $p_outputpath = 'output.pdf', $p_peer = false)
    {
        if (!isset($this->_files) || !is_array($this->_files)) {
            throw new \Exception("No PDFs to merge.");
        }
        $fpdi = new \setasign\Fpdi\Fpdi();
        // merger operations
        /* @var $file PdfObject */
        foreach ($this->_files as $file) {
            $filename  = $file->path;
            $filepages = $file->pages;
            $count     = $fpdi->setSourceFile($filename);
            //add the pages
            if ($filepages == 'all') {
                for ($i = 1; $i <= $count; $i++) {
                    $template = $fpdi->importPage($i);
                    $size = $fpdi->getTemplateSize($template);
                    $fpdi->AddPage(
                        $file->getOrientationCode(),
                        [$size['width'], $size['height']]
                    );
                    $fpdi->useTemplate($template);
                }
                if ($p_peer && $count % 2 == 1) {
                    $fpdi->AddPage();
                }
            } else {
                foreach ($filepages as $page) {
                    if (!$template = $fpdi->importPage($page)) {
                        throw new \Exception("Could not load page '$page' in PDF '$filename'. Check that the page exists.");
                    }
                    $size = $fpdi->getTemplateSize($template);
                    $fpdi->AddPage(
                        $file->getOrientationCode(),
                        [$size['w'], $size['h']]
                    );
                    $fpdi->useTemplate($template);
                }
            }
        }
        //output operations
        $mode = $this->_switchmode($p_outputmode);
        if ($mode == 'S') {
            return $fpdi->Output($p_outputpath, 'S');
        } else {
            if ($fpdi->Output($p_outputpath, $mode) == '') {
                return true;
            } else {
                throw new \Exception('Error outputting PDF to ' . $p_outputmode);
                return false;
            }
        }
    }

    /**
     * FPDI output location
     * 
     * @param string $p_mode
     * 
     * @return string
     */
    private function _switchmode($p_mode)
    {
        switch (strtolower($p_mode)) {
            case 'download':
                return 'D';
                break;
            case 'browser':
                return 'I';
                break;
            case 'file':
                return 'F';
                break;
            case 'string':
                return 'S';
                break;
            default:
                return 'I';
                break;
        }
    }

    /**
     * Takes our provided pages in the form of 1,3,4,16-50 and creates an array of all pages
     * 
     * @param string $p_pagesParam
     * 
     * @return []
     */
    private function _rewritepages($p_pagesParam)
    {
        $pages = str_replace(' ', '', $p_pagesParam);
        $part  = explode(',', $pages);
        //parse hyphens
        foreach ($part as $i) {
            $ind = explode('-', $i);
            if (count($ind) == 2) {
                $x = $ind[0]; //start page
                $y = $ind[1]; //end page
                if ($x > $y) {
                    throw new \Exception("Starting page, '$x' is greater than ending page '$y'.");
                    return false;
                }
                //add middle pages
                while ($x <= $y) {
                    $newpages[] = (int) $x;
                    $x++;
                }
            } else {
                $newpages[] = (int) $ind[0];
            }
        }
        return $newpages;
    }
}
