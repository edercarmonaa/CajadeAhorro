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
$semana=0;
if (isset($_GET["semana"])) {
    $semana=$_GET["semana"];
}

//OBTIENE DATOS DEL EMPELADO
$stmt = $empleado->leeEmpleados();
$num = $stmt->rowCount();
$data_empleado="";
$x=0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    extract($row);
        $data_empleado[$x]=array(
            "id_empleado"=>$id_empleado,
            "nombre"=>$nombre,
            "accion"=>$accion
        );
        $x++; 
} 
$filas=count($data_empleado);

//OBTIENE DATOS DEL AHORRO
$stmt = $ahorro->leeAhorrosSemana($semana);
$num = $stmt->rowCount();
$data_ahorro="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data_ahorro[$id_empleado]=$monto;
        } 
} 



//OBTIENE DATOS DE LOS ABONOS 
$stmt = $abono->leeAbonosSemana($semana);
$num = $stmt->rowCount();
$data_abono="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
         $data_abono[$id_empleado]=$monto;
    } 
} 


//OBTIENE DATOS DE LOS PRESTAMOS
$stmt = $prestamo->leePrestamosSemana($semana);
$num = $stmt->rowCount();
$data_prestamo="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_prestamo[$id_empleado]=array(
                "monto" => $monto,
                "recibos" => $recibos,
                "interes" => $interes,
        ); 
    } 
}


//ABRE LA PLANTILLA
$objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objPHPExcel->load('templates/concentrado semanal.xlsx');
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->insertNewRowBefore(4,$filas-1); 
 


//ACTUALIZA DATOS DEL EMPLEADO
$x=3;
 $objPHPExcel->getActiveSheet()->setCellValue('F1', $semana);
foreach ($data_empleado as $valor){
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$x, $valor['id_empleado']);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$x, $valor['nombre']);
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$x, $valor['accion']);
    if(isset($data_ahorro[$valor['id_empleado']])){
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$x, $data_ahorro[$valor['id_empleado']]);
    }else{
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$x,0);
    }
    if(isset($data_abono[$valor['id_empleado']])){
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$x, $data_abono[$valor['id_empleado']]);
    }else{
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$x,0);
    }
    if(isset($data_prestamo[$valor['id_empleado']])){
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$x, $data_prestamo[$valor['id_empleado']]['monto']);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$x, $data_prestamo[$valor['id_empleado']]['interes']);
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$x, $data_prestamo[$valor['id_empleado']]['recibos']);
    }else{
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$x,0);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$x,0);
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$x,0);
    }
    $saldo=str_replace("?",$x,"=I?+J?-K?-L?-M?");
    $saldo_cargo=str_replace("?",$x,'=IF(N?<0,N?,0)');
    $saldo_favor=str_replace("?",$x,'=IF(N?>=0,N?,0)');
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$x, $saldo);
    $objPHPExcel->getActiveSheet()->setCellValue('O'.$x, $saldo_cargo);
    $objPHPExcel->getActiveSheet()->setCellValue('P'.$x, $saldo_favor);
    $x++;
}

$objPHPExcel->getActiveSheet()->setCellValue('H'.($filas+3), "=SUM(H3:H".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('I'.($filas+3), "=SUM(I3:I".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('J'.($filas+3), "=SUM(J3:J".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('K'.($filas+3), "=SUM(K3:K".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('L'.($filas+3), "=SUM(L3:L".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('M'.($filas+3), "=SUM(M3:M".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('N'.($filas+3), "=SUM(N3:N".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('O'.($filas+3), "=SUM(O3:O".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('P'.($filas+3), "=SUM(P3:P".($filas+2).")");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objPHPExcel->getActiveSheet()->setTitle("Semana ".$semana);
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Semana'.$semana.'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>