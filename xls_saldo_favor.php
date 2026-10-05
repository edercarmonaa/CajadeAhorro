<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once 'libs/PHPExcel/IOFactory.php';
require_once 'libs/PHPExcel.php';

include_once 'config/database.php';
include_once 'objects/prestamo.php';
include_once 'objects/abono.php';
include_once 'objects/ahorro.php';
include_once 'objects/empleado.php';
include_once 'objects/ejercicio.php';
$database = new Database();
$db = $database->Coneccion();
$prestamo = new Prestamo($db);
$ahorro = new Ahorro($db);
$abono = new Abono($db);
$empleado = new Empleado($db);
$ejercicio = new ejercicio($db);

//ABRE LA PLANTILLA
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Num');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Nombre del Trabajador');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Saldo a Cargo');

//OBTIENE DATOS DEL EJERCICIO
$nom_ejercicio = $ejercicio->EjercicioActivo();

$stmt_principal = $empleado->leeEmpleados();
$num = $stmt_principal->rowCount();
if($num>0){
	$x=2;
while ($row_principal = $stmt_principal->fetch(PDO::FETCH_ASSOC)){
	  extract($row_principal);

//OBTIENE DATOS DEL EMPELADO
$empleado->id_empleado = $id_empleado;


$empleado->leeEmpleadoXls();
$empleado_arr = array(
    "id_empleado" =>  $empleado->id_empleado,
    "nombre" => $empleado->nombre,
    "categoria" => $empleado->categoria,
    "accion" => $empleado->accion,
    "interes" => $empleado->interes
);

//OBTIENE DATOS DEL AHORRO
$ahorro->id_empleado =$id_empleado;

$stmt = $ahorro->leeAhorrosEmpleado();
$num = $stmt->rowCount();
$data_ahorro=0;
if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_ahorro+=$monto;
        }
}

//OBTIENE DATOS DE LOS ABONOS
$abono->id_empleado = $id_empleado;
$stmt = $abono->leeAbonosEmpleado();
$num = $stmt->rowCount();
$data_abono="";
if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_ahorro+=$monto;
    }
}

//OBTIENE DATOS DE LOS PRESTAMOS
$prestamo->id_empleado = $id_empleado;
$stmt = $prestamo->leePrestamosEmpleado();
$num = $stmt->rowCount();
$data_prestamo=0;
$data_recibos=0;
$data_interes=0;
if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
				$data_prestamo+=$monto;
				$data_recibos+=$recibos;
				$data_interes+=$interes;
    }
}



//ACTUALIZA DATOS DEL ENCABEZADO
$saldo_final=$data_ahorro+$data_abono-$data_prestamo-$data_recibos-$data_interes;
if($saldo_final > 0){
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, $empleado_arr['id_empleado']);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, $empleado_arr['nombre']);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, $saldo_final);
	$x++;
}
}
}
$objPHPExcel->getActiveSheet()->setTitle('Saldo_cargo');
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="SaldoFavor.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>
