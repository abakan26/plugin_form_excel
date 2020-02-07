<?php


class SHExcel
{
    public function __construct($data)
    {
        $this->objActiveSheet = null;
        $this->document = $this->get_document();
        $this->list = $this->get_list();
        $this->data = $data;
        
    }
    public function view_all()
    {
        $this->get_header(5, date("d.m.Y"), "all");
        $this->get_table($this->data, "all");

    }
    public function view_one()
    {
        $this->get_header(5, date("d.m.Y"), "one");
        $this->get_table($this->data["products"], "one");

    }
    public function get_document()
    {
        $xls = new PHPExcel();
        $xls->getProperties()->setTitle("Название");
        
        $this->objActiveSheet = $xls->setActiveSheetIndex(0);
        return $xls;

    }

    public function get_list()
    {
        $sheet = $this->document->getActiveSheet();
        $sheet->setTitle('Название листа');
        return $sheet;

    }

    public function get_logo($path="", $coord, $height)
    {
        if($path !== "" && file_exists($path)){
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('Название картинки');
            $objDrawing->setDescription('Описание картинки');

            $objDrawing->setPath($path);
            $objDrawing->setCoordinates($coord);
            $objDrawing->setHeight($height);
            $objDrawing->setWorksheet($this->list);
        }
    }

    private function set_style($col_count, $context )
    {
        if ($context == "all") {
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
        } elseif ($context == "one") {
             foreach (range('A', 'D') as $columnID) {
                $this->document->getActiveSheet()->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            $this->list->getColumnDimension("A")->setAutoSize(false);
            $this->list->getColumnDimension("A")->setWidth(40);
            $this->list->getColumnDimension("C")->setWidth(5);
            $this->list->getStyle("B14:E{$col_count}")
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->list->getStyle("A14:B{$col_count}")->getFont()->setSize(12);
            $this->list->getStyle("B14:C{$col_count}")
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
            $dop =  $col_count;
            $dopdop = $dop + 1;
            $this->list->mergeCells("A{$dop}:D{$dop}");
            $this->list->setCellValue("A{$dop}", $this->data["dop"]);
            $this->list->setCellValue("A{$dopdop}","Посылку получил, претензий не имею ________________");

            $this->objActiveSheet->getStyle("A{$dop}:D{$dop}")->getAlignment()->setWrapText(true);
            $this->list->getStyle("A{$dop}:D{$dop}")
                ->getAlignment()
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            
            $this->objActiveSheet->getStyle("A{$dop}:D{$dop}")->getFont()->setSize(10);
            $this->objActiveSheet->getRowDimension($dop)->setRowHeight(60);

            # массив с параметрами
            $arHeadStyle = array(
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => '000000'),
                    'size'  => 10,
                    'name'  => 'Arial'
                ));
             
            # применение стилей к ячейкам
            $this->objActiveSheet->getStyle('A7')->applyFromArray($arHeadStyle);
            $this->objActiveSheet->getStyle('A8')->applyFromArray($arHeadStyle);
            $this->objActiveSheet->getStyle('A9')->applyFromArray($arHeadStyle);
            $this->objActiveSheet->getStyle('A10')->applyFromArray($arHeadStyle);
            $this->objActiveSheet->getStyle('A11')->applyFromArray($arHeadStyle);
            $this->objActiveSheet->getStyle('A12')->applyFromArray($arHeadStyle);

            $this->objActiveSheet->getStyle('A13')->applyFromArray($arHeadStyle);
            $this->objActiveSheet->getStyle('B13')->applyFromArray($arHeadStyle);
            $this->objActiveSheet->getStyle('C13')->applyFromArray($arHeadStyle);
            $this->objActiveSheet->getStyle('D13')->applyFromArray($arHeadStyle);
            $this->list->getStyle("A13:D13")
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            foreach (range(1, $col_count-1) as $val) 
            {
            	$this->objActiveSheet->getRowDimension($val)->setRowHeight(18);
            }
            $this->list->getStyle("B7:B13")
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        }



	
			//массив стилей
			$style_wrap = array(
				 //рамки
			 	'borders'=>array(
					 //внутренняя
					 'allborders'=>array(
					 'style'=>PHPExcel_Style_Border::BORDER_THIN,
					 'color' => array(
						 'rgb'=>'000000'
						 )
					 )
				)
			);
		$border_c = $col_count - 1;
        $this->objActiveSheet->getStyle("A13:D{$border_c}")->applyFromArray($style_wrap);

    }

    public function get_table($data, $context)
    {

        if ($context == "all") {
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
                $this->set_style($last_count, $context);
        } elseif ($context == "one") {
            $current = 14;
            $last_count = $current;
            $col_count = 1;
                foreach ($data as $row) {

                    $this->list->setCellValue("A{$last_count}", $row["product_name"]);
                    $this->list->setCellValue("B{$last_count}", $row["quantity"]);
                    $this->list->setCellValue("C{$last_count}", $row["productunit"]);

                    $last_count++;
                    $col_count++;

                }
            $this->set_style($last_count, $context);
        }
        
    }

    public function save($path)
    {
        $objWriter = new PHPExcel_Writer_Excel5($this->document);
        $objWriter->save($path); //plugin_dir_path(dirname(__FILE__)) . "/admin/partials/file.xlsx"
    }

    public function get_header($number, $date, $context)
    {
        
        if ($context == "all")
        {
            $this->get_logo(plugin_dir_path(dirname(__FILE__)) . "/admin/partials/logo.png", "A1", 120);
           $this->list->mergeCells("B6:F6");
            
            $this->list->setCellValue("B6", "БЛАНК ЗАКУПА №{$number}-{$date}");
            $this->list->getStyle("B6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $this->list->setCellValue("A8", "№");
            $this->list->setCellValue("B8", "Наименование");
            $this->list->setCellValue("C8", "Кол-во");
            $this->list->setCellValue("D8", "ед");
            $this->list->setCellValue("E8", "цена по сайту");
            $this->list->setCellValue("F8", "Примеч"); 
        } elseif ($context == "one") {
            $this->get_logo(plugin_dir_path(dirname(__FILE__)) . "/admin/partials/logo.png", "A1", 80);
            $this->list->mergeCells("A5:D5");
            $this->list->setCellValue("A5", "БЛАНК ЗАКАЗА №{$number}-{$date}");
            $this->list->getStyle("A5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            $this->list->setCellValue("A7", "Дата заказа:");
            $this->list->setCellValue("A8", "ФИО Получателя/Заказчика:");
            $this->list->setCellValue("A9", "Телефон:");
            $this->list->setCellValue("A10", "ФИО Получателя:");
            $this->list->setCellValue("A11", "Куда:");
            $this->list->setCellValue("A12", "Заказ:");


            $this->list->setCellValue("B7", $this->data["date"]);
            $this->list->setCellValue("B8", $this->data["otpravitel"]);
            $this->list->setCellValue("B9", $this->data["phone"]);
            $this->list->setCellValue("B10", $this->data["poluchatel"]);
            $this->list->setCellValue("B11", $this->data["adress"]);
            


            $this->list->setCellValue("A13", "Наименование");
            $this->list->setCellValue("B13", "кол-во");
            $this->list->setCellValue("C13", "ед");
            $this->list->setCellValue("D13", "Примеч"); 
        }
        
    }
}