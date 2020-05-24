<?php
namespace FreeOffice\Command;

/**
 * Docx commands
 *
 * @author jeromeklam
 */
class Docx
{

    /**
     * Test
     *
     * @param \FreeFW\Console\Input\AbstractInput $p_input
     * @param \FreeFW\Console\Output\AbstractOutput $p_output
     */
    public function test(
        \FreeFW\Console\Input\AbstractInput $p_input,
        \FreeFW\Console\Output\AbstractOutput $p_output
    ) {
        $p_output->write("Docx test", true);
        $tbs = new \clsTinyButStrong();
        $tbs->LoadTemplate(APP_ROOT . '/datas/kalaweit/test.docx', OPENTBS_ALREADY_UTF8);
        $tbs->Show('dummy');
        file_put_contents('/var/www/html/jk.docx', $tbs->Source);
        //$doc = new \FreeOffice\Docx();
        //$doc->setFilename(APP_ROOT . '/datas/kalaweit/test.docx');
        //$doc->exportToPdf();
        $p_output->write("Fin Docx", true);
    }
}
