<?php
require "./function.php";
require_once __DIR__  .  "/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$id = $_GET["id"];
$namaEvent = $_GET["nama"];
$perintah = "SELECT * FROM `$namaEvent`";
$data = getDATA($perintah);
$arrayNumerikEmail = [];
foreach($data as $array){
    $arrayNumerikEmail[] = $array["email"];

}

$spreadSheet = new Spreadsheet();
$sheet = $spreadSheet->getActiveSheet();
$sheet->getColumnDimension('B')->setWidth(50);
$sheet->setCellValue("A1", "No.");
$sheet->setCellValue("B1", "Student email");
$index = 0;
foreach($arrayNumerikEmail as $item){
    $listNomor = $index + 2;
    $sheet->setCellValue("A$listNomor", $listNomor - 1);
    $sheet->setCellValue("B$listNomor", $item);
    $index++; 
}

// Define border style
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
            'color' => ['argb' => '#000000'],
        ],
    ],
];

// Apply border style to all cells
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
$sheet->getStyle("A1:$highestColumn$highestRow")->applyFromArray($styleArray);

$writer = new Xlsx($spreadSheet);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="history.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');

$index = 0;

?>