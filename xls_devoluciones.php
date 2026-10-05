<?php
require_once("libs/PHPExcel/IOFactory.php");
require_once('libs/PHPExcel/Writer/Excel2007.php');
include_once 'config/database.php';
$database = new Database();
$db = $database->Coneccion();

include_once 'objects/devolucion.php';
$empleado = new Devolucion($db);
$stmt = $empleado->leeDevoluciones();
$num = $stmt->rowCount();
$total_saldo=0;
$total_ahorros=0;
$total_accion=0;
$data="";

$data = json_decode(file_get_contents("php://input"));
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'No de Empleado');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Nombre');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Devolucion');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Devolucion Accion');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Devolucion Ahorro');
if($num>0){
    $x=2;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $x, $id_empleado);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $x, $nombre);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $x, $saldo);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $x, $valor_accion);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $x, $ahorros);
        $total_saldo+=$saldo;
        $total_accion+=$valor_accion;
        $total_ahorros+=$ahorros;
        $x++;
    }
}
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $x, 'TOTALES');
$objPHPExcel->getActiveSheet()->mergeCells('A'.$x.':B'.$x);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $x, $total_saldo);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $x, $total_accion);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $x, $total_ahorros);
$objPHPExcel->getActiveSheet()->setTitle('Empleados');
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Devoluciones.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>
