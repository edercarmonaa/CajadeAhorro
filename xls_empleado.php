<?php
require_once("libs/PHPExcel/IOFactory.php");
require_once('libs/PHPExcel/Writer/Excel2007.php'); 
include_once 'config/database.php'; 
$database = new Database(); 
$db = $database->Coneccion();
 
include_once 'objects/empleado.php';
$empleado = new Empleado($db);
$stmt = $empleado->leeEmpleados();
$num = $stmt->rowCount();
$data="";
 

$data = json_decode(file_get_contents("php://input")); 
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'No de Empleado');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Nombre');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Categoria');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Valor de Accion');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Interes');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Saldo');
if($num>0){
    $x=2;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $x, $id_empleado);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $x, $nombre);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $x, $categoria);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $x, $accion);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $x, $interes);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $x, $saldo);
        $x++;
    }
} 
$objPHPExcel->getActiveSheet()->setTitle('Empleados');
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Empleados.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>