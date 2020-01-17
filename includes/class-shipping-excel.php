<?php


class SHExcel
{
    public function __construct($data)
    {
        $this->document = $this->get_document();
        $this->list = $this->get_list();
        $this->view($data);
    }
    public function view($data)
    {
        $this->get_header();
        $this->get_table($data);

    }
    public function get_document()
    {
        $xls = new PHPExcel();
        $xls->getProperties()->setTitle("Название");
        $xls->setActiveSheetIndex(0);
        return $xls;

    }

    public function get_list()
    {
        $sheet = $this->document->getActiveSheet();
        $sheet->setTitle('Название листа');
        return $sheet;

    }

    public function get_logo($path="", $coord)
    {
        if($path !== "" && file_exists($path)){
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('Название картинки');
            $objDrawing->setDescription('Описание картинки');

            $objDrawing->setPath($path);
            $objDrawing->setCoordinates($coord);
            $objDrawing->setHeight(120);
            $objDrawing->setWorksheet($this->list);
        }
    }

    private function set_style($col_count)
    {
        //Автоматическая ширина колонок
        foreach (range('A', 'F') as $columnID) {
            $this->document->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $this->list->getColumnDimension("B")->setAutoSize(false);
        $this->list->getColumnDimension("B")->setWidth(40);
        $this->list->getColumnDimension("A")->setWidth(5);
        $this->list->mergeCells("B6:F6");

        $this->list->getStyle("C9:E{$col_count}")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->list->getStyle("B9:B{$col_count}")->getFont()->setSize(10);
        $this->list->getStyle("C9:C{$col_count}")
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
    }

    public function get_table($data)
    {
        $current = 9;
        $last_count = $current;
        $col_count = 1;
        foreach ($data as $row) {

            $this->list->setCellValue("A{$last_count}", $col_count);
            $this->list->setCellValue("B{$last_count}", $row->product_name);
            $this->list->setCellValue("C{$last_count}", $row->quantity);
            $this->list->setCellValue("D{$last_count}", $row->productunit);
            $this->list->setCellValue("E{$last_count}", "{$row->price} тг");
            $this->list->setCellValue("F{$last_count}", "");

            $last_count++;
            $col_count++;
        }
        $this->set_style($last_count);
    }

    public function save($path)
    {
        $objWriter = new PHPExcel_Writer_Excel5($this->document);
        $objWriter->save($path); //plugin_dir_path(dirname(__FILE__)) . "/admin/partials/file.xlsx"
    }

    public function get_header()
    {
        $this->get_logo(plugin_dir_path(dirname(__FILE__)) . "/admin/partials/logo.png", "A1");
        $this->list->mergeCells("B6:F6");
        $number = 5;
        $date = "06.11.2019";
        $this->list->setCellValue("B6", "БЛАНК ЗАКУПА №{$number}-{$date}");
        $this->list->getStyle("B6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->list->setCellValue("A8", "№");
        $this->list->setCellValue("B8", "Наименование");
        $this->list->setCellValue("C8", "Кол-во");
        $this->list->setCellValue("D8", "ед");
        $this->list->setCellValue("E8", "цена по сайту");
        $this->list->setCellValue("F8", "Примеч");
    }
}