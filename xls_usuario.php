<?php
require_once("libs/PHPExcel/IOFactory.php");
require_once('libs/PHPExcel/Writer/Excel2007.php');
include_once 'config/database.php';
include_once 'objects/usuario.php';

$database = new Database();
$db = $database->Coneccion();
$usuario = new Usuario($db);
$stmt = $usuario->leeUsuarios();
$num = $stmt->rowCount();
$data="";


$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'No de Empleado');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Nombre');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Nivel');
if($num>0){
    $x=2;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $x, $id_empleado);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $x, $nom_usr);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $x, $nivel);
        $x++;
    }
}
$objPHPExcel->getActiveSheet()->setTitle('Usuarios');
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Usuarios.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>
