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
include_once 'objects/ent_sal.php';
include_once 'objects/semana.php';
include_once 'objects/abono_efec.php';

$database = new Database();
$db = $database->Coneccion();
$prestamo = new Prestamo($db);
$ahorro = new Ahorro($db);
$abono = new Abono($db);
$empleado = new Empleado($db);
$ejercicio = new ejercicio($db);
$ent_sal= new Ent_sal($db);
$semana_info = new Semana($db);
$abono_efec= new AbonoEfectivo($db);



//OBTIENE DATOS DEL EJERCICIO
$nom_ejercicio = $ejercicio->EjercicioActivo();
$semana=0;
if (isset($_GET["semana"])) {
    $semana=$_GET["semana"];
}

//OBTIENE EL SALDO INICIAL DE LA SEMANA
$semana_info->no_semana=$semana;
$stmt = $semana_info->leeSemana();
$num = $stmt->rowCount();
$saldo_ini=0;
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $saldo_ini+=$saldo_inicial;
       }
}


//OBTIENE DATOS DEL AHORRO
$stmt = $ahorro->leeAhorrosSemana($semana);
$num = $stmt->rowCount();
$data_ahorro="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data_ahorro+=$monto;
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
         $data_abono+=$monto;
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
        $data_prestamo+=$monto;
    }
}

//OBTIENE DATOS DE LOS ABONOS EFECT
$stmt = $abono_efec->leeAbonosSemana($semana);
$num = $stmt->rowCount();
$data_abono_efec="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
         $data_abono_efec+=$monto;
    }
}

//OBTIENE DATOS DE LOS MOVIMIENTOS
$stmt = $ent_sal->leeMovimientosSemana($semana);
$num = $stmt->rowCount();
$data_movimientos="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_movimientos=array(
                "entradas" => $entradas,
                "salidas" => $salidas,
                "abonos_teso" => $abonos_teso,
                "ahorro_teso" => $ahorro_teso,
                "gastos" => $gastos,

        );
    }
}


//ABRE LA PLANTILLA
$objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objPHPExcel->load('templates/reporte semanal.xlsx');
$objPHPExcel->setActiveSheetIndex(0);

//ACTUALIZA DATOS DEL ENCABEZADO
$x=3;
$year = date('Y');
$fechaInicioSemana  = date('d/m/Y', strtotime($year . 'W' . str_pad($semana , 2, '0', STR_PAD_LEFT)));
$fechaFindeSemana = date('d/m/Y', strtotime(str_replace('/', '-', $fechaInicioSemana).' +6 day'));

$objPHPExcel->getActiveSheet()->setCellValue('D8', $semana);
$objPHPExcel->getActiveSheet()->setCellValue('E9', $fechaInicioSemana);
$objPHPExcel->getActiveSheet()->setCellValue('I9', $fechaFindeSemana);
$objPHPExcel->getActiveSheet()->setCellValue('C14', $saldo_ini);
$objPHPExcel->getActiveSheet()->setCellValue('C16', $data_ahorro);
$objPHPExcel->getActiveSheet()->setCellValue('B40', $data_ahorro);
$objPHPExcel->getActiveSheet()->setCellValue('C17', $data_abono);
$objPHPExcel->getActiveSheet()->setCellValue('E40', $data_abono);
$objPHPExcel->getActiveSheet()->setCellValue('C26', $data_prestamo);
$objPHPExcel->getActiveSheet()->setCellValue('C40', $data_prestamo);
$objPHPExcel->getActiveSheet()->setCellValue('C21',$data_abono_efec);
$objPHPExcel->getActiveSheet()->setCellValue('C22', $data_movimientos['entradas']);
$objPHPExcel->getActiveSheet()->setCellValue('L16', $data_movimientos['ahorro_teso']);
$objPHPExcel->getActiveSheet()->setCellValue('L17', $data_movimientos['abonos_teso']);
$objPHPExcel->getActiveSheet()->setCellValue('C28', $data_movimientos['gastos']);
$objPHPExcel->getActiveSheet()->setCellValue('C30', $data_movimientos['salidas']);

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
