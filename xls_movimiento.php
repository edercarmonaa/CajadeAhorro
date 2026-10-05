<?php
require_once("libs/PHPExcel/IOFactory.php");
require_once('libs/PHPExcel/Writer/Excel2007.php'); 
include_once 'config/database.php'; 
$database = new Database(); 
$db = $database->Coneccion();
 
include_once 'objects/ent_sal.php';
$ent_sal = new Ent_sal($db);
$stmt = $ent_sal->leemovimientos();
$num = $stmt->rowCount();
$data="";
 

$data = json_decode(file_get_contents("php://input")); 
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Semana');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Fecha');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Importe');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Concepto');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Tipo de Operacion');

if($num>0){
    $x=2;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $x, $semana);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $x, $fecha);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $x, $monto);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $x, $concepto);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $x, $tipo);
        $x++;
    }
} 
$objPHPExcel->getActiveSheet()->setTitle('Movimientos');
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Movimientos.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>