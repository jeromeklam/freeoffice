<?php
namespace FreeOffice;

/**
 *
 * @author jeromeklam
 *
 */
class Docx {

    /**
     * Filename
     * @var string
     */
    protected $filename = null;

    /**
     * Set filename
     *
     * @param string $p_name
     *
     * @return \FreeOffice\Docx
     */
    public function setFilename($p_name)
    {
        $this->filename = $p_name;
        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    public function exportToPdf()
    {
        // Load temp file
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($this->filename);
        $orientation = 'portrait';
        $session = $phpWord->getSection(0);
        if ($session) {
            $style = $session->getStyle();
            if ($style) {
                $orientation = $style->getOrientation();
            }
        }
        // Save it
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'HTML');


        $xmlWriter->save('/tmp/result.html');

        $html = file_get_contents('/tmp/result.html');
        // instantiate and use the dompdf class
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', $orientation);

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $output = $dompdf->output();
        file_put_contents('/tmp/result.pdf', $output);
    }
}
