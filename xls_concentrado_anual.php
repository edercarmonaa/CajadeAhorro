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
$ejercicio=0;
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
$stmt = $ahorro->leeAhorros();
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
$stmt = $abono->leeAbonos();
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
$stmt = $prestamo->leePrestamos();
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
$objPHPExcel = $objPHPExcel->load('templates/concentrado_anual.xlsx');
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->insertNewRowBefore(4,$filas-1); 
 


//ACTUALIZA DATOS DEL EMPLEADO
$x=3;
 $objPHPExcel->getActiveSheet()->setCellValue('B1', $nom_ejercicio);

foreach ($data_empleado as $valor){
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$x, $valor['id_empleado']);
	$objPHPExcel->getActiveSheet()->setCellValue('Q'.$x, '=B'.$x);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$x, $valor['nombre']);
	$objPHPExcel->getActiveSheet()->setCellValue('R'.$x, '=C'.$x);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$x, $valor['accion']);
	
    if(isset($data_ahorro[$valor['id_empleado']])){
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$x, $data_ahorro[$valor['id_empleado']]);
    }else{
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$x,0);
    }

    if(isset($data_abono[$valor['id_empleado']])){
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$x, $data_abono[$valor['id_empleado']]);
    }else{
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$x,0);
    }
    
    if(isset($data_prestamo[$valor['id_empleado']])){
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$x, $data_prestamo[$valor['id_empleado']]['monto']);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$x, $data_prestamo[$valor['id_empleado']]['interes']);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$x, $data_prestamo[$valor['id_empleado']]['recibos']);
    }else{
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$x,0);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$x,0);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$x,0);
    }
    $saldo=str_replace("?",$x,"=D?+E?+F?-G?");
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$x, $saldo);
	$saldo_cargo=str_replace("?",$x,'=IF(H?<0,H?,0)');
    $saldo_favor=str_replace("?",$x,'=IF(H?>=0,H?,0)');
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, $saldo_cargo);
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$x, $saldo_favor);
	
	$saldo_fin=str_replace("?",$x,"=E?+F?-G?-K?-L?");
	$objPHPExcel->getActiveSheet()->setCellValue('M'.$x, $saldo_fin);
	$saldo_cargo=str_replace("?",$x,'=IF(M?<0,M?,0)');
    $saldo_favor=str_replace("?",$x,'=IF(M?>=0,M?,0)');
	$objPHPExcel->getActiveSheet()->setCellValue('N'.$x, $saldo_cargo);
    $objPHPExcel->getActiveSheet()->setCellValue('O'.$x, $saldo_favor);
	$impt_repartir=str_replace("?",$x,'=(D?+E?)*S3?');
	$objPHPExcel->getActiveSheet()->setCellValue('T'.$x, $impt_repartir);
	$impt_repartir_favor=str_replace("?",$x,'=IF(O?<1,0,T?)');
	$objPHPExcel->getActiveSheet()->setCellValue('U'.$x, $impt_repartir);
	$objPHPExcel->getActiveSheet()->setCellValue('V'.$x, '=M'.$x);
	$ade_final=str_replace("?",$x,'=IF((T?+V?)>0,0,(T?+V3?))');
	$objPHPExcel->getActiveSheet()->setCellValue('W'.$x, $ade_final);
	$total_final=str_replace("?",$x,'=IF((T?+V?)<0,0,(T?+V?))');
	$objPHPExcel->getActiveSheet()->setCellValue('X'.$x, $total_final);
	$ade_final_deudores=str_replace("?",$x,'=IF(V?<1,N?,0)');
	$objPHPExcel->getActiveSheet()->setCellValue('Y'.$x, $ade_final_deudores);
    $x++;
}

$objPHPExcel->getActiveSheet()->setCellValue('D'.($filas+3), "=SUM(D3:D".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('E'.($filas+3), "=SUM(E3:E".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('F'.($filas+3), "=SUM(F3:F".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('G'.($filas+3), "=SUM(G3:G".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('H'.($filas+3), "=SUM(H3:H".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('I'.($filas+3), "=SUM(I3:I".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('J'.($filas+3), "=SUM(J3:J".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('K'.($filas+3), "=SUM(K3:K".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('L'.($filas+3), "=SUM(L3:L".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('M'.($filas+3), "=SUM(M3:M".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('N'.($filas+3), "=SUM(N3:N".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('O'.($filas+3), "=SUM(O3:O".($filas+2).")");

$objPHPExcel->getActiveSheet()->setCellValue('T'.($filas+3), "=SUM(T3:T".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('U'.($filas+3), "=SUM(U3:U".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('V'.($filas+3), "=SUM(V3:V".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('W'.($filas+3), "=SUM(W3:W".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('X'.($filas+3), "=SUM(X3:X".($filas+2).")");
$objPHPExcel->getActiveSheet()->setCellValue('Y'.($filas+3), "=SUM(Y3:Y".($filas+2).")");


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objPHPExcel->getActiveSheet()->setTitle("Ejercicio ".$nom_ejercicio);
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Ejercicio: '.$nom_ejercicio.'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>