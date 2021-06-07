<?php
namespace FreeOffice\Model;

use \Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use \Box\Spout\Common\Entity\Row;
use \Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use \Box\Spout\Common\Entity\Style\CellAlignment;
use \Box\Spout\Common\Entity\Style\Color;

/**
 *
 * @author jerome.klam
 *
 */
class Sheet
{

    /**
     * Filename
     * @var string
     */
    protected $file_name = '';

    /**
     * Is empty ?
     * @var boolean
     */
    protected $empty = true;

    /**
     * Cells
     * @var array
     */
    protected $cells = [];

    /**
     * Writter
     * @var object
     */
    protected $writer = null;

    /**
     * Constructeur
     *
     * @param string $p_file_name
     */
    public function __construct($p_file_name)
    {
        $this->file_name = $p_file_name;
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
            $style = (new StyleBuilder())
                ->setFontBold()
                ->setFontSize(15)
                ->setFontColor(Color::BLACK)
                ->setShouldWrapText()
                ->setCellAlignment(CellAlignment::CENTER)
                ->build()
            ;
            $this->writer = WriterEntityFactory::createODSWriter();
            $this->writer->openToFile($this->file_name);
            foreach ($p_data->getTitles() as $title) {
                $this->cells[] = WriterEntityFactory::createCell($title);
            }
            $singleRow = WriterEntityFactory::createRow($this->cells);
            $singleRow->setStyle($style);
            $this->writer->addRow($singleRow);
        }
        $values = [];
        foreach ($p_data->getBlocks() as $oneBlock) {
            $datas = $p_data->getDatas($oneBlock);
            foreach ($datas as $name => $content) {
                $values[] = $content;
            }
        }
        $rowFromValues = WriterEntityFactory::createRowFromArray($values);
        $this->writer->addRow($rowFromValues);
        $this->empty = false;
        return $this;
    }

    /**
     * Close
     */
    public function close()
    {
        if ($this->writer) {
            $this->writer->close();
        }
    }
}
