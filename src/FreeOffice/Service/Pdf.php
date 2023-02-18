<?php

namespace FreeOffice\Service;

/**
 *
 * @author jerome.klam
 *
 */
class Pdf extends \FreeFW\Core\Service
{

    protected function createXFDF($p_datas, $p_enc = 'UTF-8')
    {
        $data = '<?xml version="1.0" encoding="' . $p_enc . '"?>' . "\n" .
            '<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">' . "\n" .
            '<fields>' . "\n";
        foreach ($p_datas as $field => $val) {
            $data .= '<field name="' . $field . '">' . "\n";
            if (is_array($val)) {
                foreach ($val as $opt)
                    $data .= '<value>' . htmlentities($opt) . '</value>' . "\n";
            } else {
                $data .= '<value>' . htmlentities($val) . '</value>' . "\n";
            }
            $data .= '</field>' . "\n";
        }
        $data .= '</fields>' . "\n" .
            '</xfdf>' . "\n";
        return $data;
    }

    /**
     * Merge pdf
     * 
     * @param string $p_source
     * @param string $p_destination
     * @param array  $p_datas
     */
    public function merge($p_source, $p_destination, $p_datas)
    {
        $try = 5;
        $exitcode = 255;
        while ($try > 0 && $exitcode > 0) {
            // mahe fdf from datas
            $xfdf = '/tmp/xfdf_' . uniqid() . '.xfdf';
            $datas = $this->createXFDF($p_datas);
            file_put_contents($xfdf, $datas);
            $cmd = 'pdftk A=' . $p_source . ' fill_form ' . $xfdf . ' output ' . $p_destination . ' drop_xfa flatten 2>&1';
            sleep(1);
            $this->logger->debug($cmd);
            $exitcode = 255;
            $result = \FreeFW\Tools\Shell::exec_timeout($cmd, 60);
            if (is_array($result) && isset($result['exitcode'])) {
                $exitcode = $result['exitcode'];
            }
            if ($exitcode > 0) {
                var_dump('retry');
            }
            @unlink($xfdf);
            $try--;
        }
        return $exitcode === 0;
    }
}
