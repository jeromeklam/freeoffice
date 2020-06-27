<?php
namespace FreeOffice\Command;
include_once(__DIR__ . '/../../../vendor/tinybutstrong/tinybutstrong/tbs_class.php');
include_once(__DIR__ . '/../../../vendor/tinybutstrong/opentbs/tbs_plugin_opentbs.php');

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
        $tbs = new \clsTinyButStrong; // new instance of TBS
        $tbs->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
        $tbs->LoadTemplate(APP_ROOT . '/datas/kalaweit/certificat.odp', \OPENTBS_ALREADY_UTF8);
        $data = [
            [
                'rank' => 'CLIENT',
                'fullname' => 'William'
            ]
        ];
        $tbs->MergeBlock('client', $data);
        $tbs->Show(\OPENTBS_STRING);
        file_put_contents('/var/www/html/jk.odp', $tbs->Source);
        $p_output->write("Fin Docx", true);
    }
}
