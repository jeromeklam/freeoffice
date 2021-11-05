<?php
namespace FreeOffice\Model;

/**
 *
 * @author jerome.klam
 *
 */
class SpreadSheet
{

    /**
     * Filename
     * @var string
     */
    protected $file_name = '';

    /**
     * Empty ??
     * @var boolean
     */
    protected $empty = true;

    /**
     *
     * @var \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    protected $spreadsheet = null;

    /**
     *
     * @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     */
    protected $sheet = null;

    /**
     *
     * @var array
     */
    protected $coordinates = [];

    /**
     * Current line
     * @var integer
     */
    protected $current_line = 0;

    /**
     *
     * @var array
     */
    protected $sizes = [];

    /**
     * Constructeur
     *
     * @param string $p_file_name
     */
    public function __construct($p_file_name)
    {
        $this->file_name = $p_file_name;
        $this->empty = true;
        for ($i=1; $i<512; $i++) {
            $this->coordinates[$i] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
        }
    }

    /**
     * Add new line
     *
     * @param \FreeFW\Model\MergeModel $p_data
     *
     * @return \FreeOffice\Model\Sheet
     */
    public function addLine(\FreeFW\Model\MergeModel $p_data)
    {
        if ($this->empty) {
            $this->spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $this->sheet = $this->spreadsheet->getActiveSheet();
            $col = 1;
            $this->current_line++;
            foreach ($p_data->getBlocks() as $oneBlock) {
                $datas = $p_data->getDatas($oneBlock);
                foreach ($datas as $name => $content) {
                    $title = $p_data->getTitle($name, $oneBlock);
                    if ($title && !is_array($content)) {
                        $this->sheet->setCellValue($this->coordinates[$col] . $this->current_line, $title);
                        if (! in_array($p_data->getType($name), [\FreeFW\Constants::TYPE_HTML])) {
                            $this->sizes[$this->coordinates[$col]] = 'auto';
                        } else {
                            $this->sizes[$this->coordinates[$col]] = 50;
                        }
                        $col++;
                    }
                }
            }
            $styleArray = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $this->sheet->getStyle('A1:ZZ1')->applyFromArray($styleArray);
            $this->empty = false;
        }
        $this->current_line++;
        $col = 1;
        foreach ($p_data->getBlocks() as $oneBlock) {
            $datas = $p_data->getDatas($oneBlock);
            foreach ($datas as $name => $content) {
                $title = $p_data->getTitle($name, $oneBlock);
                $type  = $p_data->getType($name, $oneBlock);
                if ($title && !is_array($content)) {
                    switch ($type) {
                        case \FreeFW\Constants::TYPE_IMAGE:
                            $tmpFile = '/tmp/' . uniqid(true) . '.png';
                            if (!is_file($content)) {
                                file_put_contents($tmpFile, $content);
                            }
                            if (is_file($tmpFile)) {
                                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                                $drawing->setName($name);
                                $drawing->setPath($tmpFile);
                                $drawing->setHeight(200);
                                $drawing->setCoordinates($this->coordinates[$col] . $this->current_line);
                                $drawing->setWorksheet($this->sheet);
                            }
                            break;
                        default:
                            $this->sheet->setCellValue($this->coordinates[$col] . $this->current_line, $content);
                            break;
                    }
                    $col++;
                } else {
                    //var_dump("--------", $name, $content, $title);
                }
            }
        }
    }

    /**
     * Close
     */
    public function close()
    {
        if ($this->spreadsheet) {
            foreach ($this->sizes as $col => $size) {
                if ($size == 'auto') {
                    $this->sheet->getColumnDimension($col)->setAutoSize(true);
                } else {
                    $this->sheet->getColumnDimension($col)->setWidth(size);
                }
            }
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->spreadsheet);
            $writer->save($this->file_name);
        }
    }
}
