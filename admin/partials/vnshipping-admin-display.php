<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://plugin.com/
 * @since      1.0.0
 *
 * @package    Vnshipping
 * @subpackage Vnshipping/admin/partials
 */
?>

    <!-- This file should primarily consist of HTML with a little bit of PHP. -->

    <div>
        <form action="" method="post">
            <div>
                <input type="submit" name="submit">
                <input type="hidden" name="action" value="shipping">
            </div>
        </form>
    </div>
<?php
//$a = wp_query("SELECT * FROM 'wp_woocommerce_order_items'");
//var_dump($a);
global $wpdb;

function vardump($var)
{
    echo "<hr>";
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    echo "<hr>";
}

$a = new WPShippingCustom();

//vardump($a ->get_orders($wpdb, '2020-01-17 00:00:00', '2020-01-17 23:59:59'));

$data = $a->get_orders($wpdb, '2020-01-17 00:00:00', '2020-01-17 23:59:59');
//vardump($data );


$xls = new PHPExcel();
$xls->getProperties()->setTitle("Название");
$xls->setActiveSheetIndex(0);
$sheet = $xls->getActiveSheet();
$sheet->setTitle('Название листа');



$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setResizeProportional(false);
$objDrawing->setName('Название картинки');
$objDrawing->setDescription('Описание картинки');
$objDrawing->setPath(__DIR__ . '/logo.png');
$objDrawing->setCoordinates('A1');
//$objDrawing->setOffsetX(10);
//$objDrawing->setOffsetY(10);
//$objDrawing->setWidth(163);
//$objDrawing->setHeight(50);
$objDrawing->setWorksheet($sheet);

//Автоматическая ширина колонок
foreach (range('A', 'F') as $columnID) {
    $xls->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}
$sheet->getColumnDimension("B")->setAutoSize(false);
$sheet->getColumnDimension("B")->setWidth(40);
$sheet->getColumnDimension("A")->setWidth(5);

$sheet->mergeCells("B6:F6");
$number = 5;
$date = "06.11.2019";
$sheet->setCellValue("B6", "БЛАНК ЗАКУПА №{$number}-{$date}");
$sheet->getStyle("B6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue("A8", "№");
$sheet->setCellValue("B8", "Наименование");
$sheet->setCellValue("C8", "Кол-во");
$sheet->setCellValue("D8", "ед");
$sheet->setCellValue("E8", "цена по сайту");
$sheet->setCellValue("F8", "Примеч");

$current = 9;
$number = 1;
foreach ($data as $row) {
    $sheet->setCellValue("A{$current}", $number);
    $sheet->setCellValue("B{$current}", $row->product_name);
    if ($row->productunit === "кг") {
        $quantity = (float)$row->quantity;
        $one_weight = (float)$row->weight;
        $weight =  $quantity * $one_weight;
        $sheet->setCellValue("C{$current}", $weight);
    } else {
        $sheet->setCellValue("C{$current}", $row->quantity);
    }



    $sheet->setCellValue("D{$current}", $row->productunit);
    $sheet->setCellValue("E{$current}", "{$row->price} тг");
    $sheet->setCellValue("F{$current}", "Примеч");
    $current++;
    $number++;
}
$sheet->getStyle("C9:E{$current}")
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("B9:B{$current}")->getFont()->setSize(10);
$sheet->getStyle("C9:C{$current}")
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

$objWriter = new PHPExcel_Writer_Excel2007($xls);
echo __DIR__;
$objWriter->save(__DIR__ . '/file.xlsx');

/**
 * object(stdClass)#15932 (6) {
 * ["product_name"] =>  string(12) "Бананы"
 * ["product_id"]   =>  string(3) "272"
 * ["price"]        =>  string(3) "160"
 * ["productunit"]  =>  string(4) "шт"
 * ["weight"]       =>  string(3) "0.2"
 * ["quantity"]     =>  string(1) "3"
 * }
 *
 */
?>