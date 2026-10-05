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

//OBTIENE DATOS DEL EJERCICIO
$nom_ejercicio = $ejercicio->EjercicioActivo();

//OBTIENE DATOS DEL EMPELADO
if (isset($_GET["id_empleado"])){
	$empleado->id_empleado = $_GET["id_empleado"];	
}else{     
	$empleado->id_empleado = $_POST["id_empleado"];
}

$empleado->leeEmpleadoXls();
$empleado_arr[] = array( 
    "id_empleado" =>  $empleado->id_empleado,
    "nombre" => $empleado->nombre,
    "categoria" => $empleado->categoria,
    "accion" => $empleado->accion,
    "interes" => $empleado->interes
);

//OBTIENE DATOS DEL AHORRO
if (isset($_GET["id_empleado"])){
	$ahorro->id_empleado = $_GET["id_empleado"];	
}else{
	$ahorro->id_empleado =$_POST["id_empleado"];;   
}

$stmt = $ahorro->leeAhorrosEmpleado();
$num = $stmt->rowCount();
$data_ahorro="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data_ahorro[$semana]=array(
                 "monto" => $monto,
                "fecha" => $fecha
            );
        } 
} 

//OBTIENE DATOS DE LOS ABONOS 
if (isset($_GET["id_empleado"])){
	$abono->id_empleado = $_GET["id_empleado"];	
}else{
	$abono->id_empleado = $_POST["id_empleado"];;
}
$stmt = $abono->leeAbonosEmpleado();
$num = $stmt->rowCount();
$data_abono="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
         $data_abono[$semana]=array(
                 "monto" => $monto,
                "fecha" => $fecha
        );
    } 
} 

//OBTIENE DATOS DE LOS PRESTAMOS
if (isset($_GET["id_empleado"])){
	$prestamo->id_empleado = $_GET["id_empleado"];	
}else{
	$prestamo->id_empleado = $_POST["id_empleado"];;
}
$stmt = $prestamo->leePrestamosEmpleado();
$num = $stmt->rowCount();
$data_prestamo="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_prestamo[$semana]=array(
                "monto" => $monto,
                "plazo" => $plazo,
                "recibos" => $recibos,
                "interes" => $interes,
        ); 
    } 
}

//ABRE LA PLANTILLA
$objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objPHPExcel->load('templates/tarjeta.xlsx');
$objPHPExcel->setActiveSheetIndex(0);

//ACTUALIZA DATOS DEL ENCABEZADO 
$objPHPExcel->getActiveSheet()->setCellValue('G4', $nom_ejercicio);
$objPHPExcel->getActiveSheet()->setCellValue('B5', $empleado_arr[0]['id_empleado']);
$objPHPExcel->getActiveSheet()->setCellValue('F5', $empleado_arr[0]['categoria']);
$objPHPExcel->getActiveSheet()->setCellValue('J5', $empleado_arr[0]['accion']);
$objPHPExcel->getActiveSheet()->setCellValue('B7', $empleado_arr[0]['nombre']);
$objPHPExcel->getActiveSheet()->setCellValue('I7', $empleado_arr[0]['interes']/100);
//ACTUALIZA DATOS DEL CONTENIDO 
for ($i = 11; $i <= 62; $i++) {
    $semana = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
    if(isset($data_ahorro[$semana])){
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $data_ahorro[$semana]['fecha']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $data_ahorro[$semana]['monto']);
    }   else {
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$i,"-");
    }
    if(isset($data_abono[$semana])){
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $data_abono[$semana]['monto']);
    }   else {
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$i,"-");
    }

    if(isset($data_prestamo[$semana])){
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $data_prestamo[$semana]['monto']);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $data_prestamo[$semana]['plazo']);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $data_prestamo[$semana]['recibos']);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $data_prestamo[$semana]['interes']);
    }   else {
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$i,"-");
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$i,"-");
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$i,"-");
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$i,"-");
    }
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
if (isset($_GET["id_empleado"])){
	$objPHPExcel->getActiveSheet()->setTitle($_GET["id_empleado"]);	
}else{
	$objPHPExcel->getActiveSheet()->setTitle($_POST["id_empleado"]);
}


$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$_GET["id_empleado"].'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>