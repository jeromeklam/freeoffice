<?php
namespace FreeOffice\Service;
require_once(__DIR__ . '/../../tinybutstrong/tinybutstrong/tbs_class.php');
require_once(__DIR__ . '/../../tinybutstrong/opentbs/tbs_plugin_opentbs.php');

/**
 *
 * @author jerome.klam
 *
 */
class Merge
{

    /**
     *
     * @param unknown $p_src_filename
     * @param unknown $p_dest_filename
     * @param unknown $p_merge_model
     */
    public function merge($p_src_filename, $p_dest_filename, \FreeFW\Model\MergeModel $p_merge_model)
    {
        ob_start();
        $done = true;
        try {
            $tbs = new \clsTinyButStrong; // new instance of TBS
            $tbs->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
            $tbs->LoadTemplate($p_src_filename, \OPENTBS_ALREADY_UTF8);
            $data = $p_merge_model->getDatasAsArray();
            $tbs->MergeBlock($p_merge_model->getBlocksAsString(), $data);
            $tbs->Show(\OPENTBS_STRING);
            file_put_contents($p_dest_filename, $tbs->Source);
        } catch (\Exception $ex) {
            $done = false;
        }
        ob_end_clean();
        return $done;
    }
}
