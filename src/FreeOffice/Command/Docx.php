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
        $tbs->LoadTemplate(APP_ROOT . '/datas/FicheParrainageV3.odt', \OPENTBS_ALREADY_UTF8);
        $data = [
            [
                'rank' => 'CAUSE',
                'cau_name' => 'William',
                'cau_sex' => 'Femelle',
                'cau_born' => '2010',
                'cau_from' => '2015',
                'cau_center' => 'Sumatra',
                'cau_text' => 'dsfsd fsd fsdfs sd dks hkd hkdhfkdhsfkhskfhksd fhsfsq fdhksdfhk hsk',
                'subspecies' => 'Gibbon',
                'picture1' => APP_ROOT . '/datas/683788533-2048x2048.jpg'
            ]
        ];
        $tbs->MergeBlock('cause', $data);
        $tbs->Show(\OPENTBS_STRING);
        file_put_contents('/var/www/html/jk.odt', $tbs->Source);
        $p_output->write("Fin Docx", true);
    }
}
