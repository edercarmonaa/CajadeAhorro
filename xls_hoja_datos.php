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
$saldo_ini=0;
$nom_ejer="";
$sem_ini="";

//ABRE LA PLANTILLA
$objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objPHPExcel->load('templates/hoja_datos.xlsx');
$objPHPExcel->setActiveSheetIndex(0);


//OBTIENE DATOS DEL EJERCICIO
$semanas = array(51,52,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,
22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,
49,50);

//OBTIENE EL SALDO Y SEMANA INICIAL Y NOMBRE DE EJERCICIO
$stmt = $ejercicio->EjercicioActivoCampos();
$num = $stmt->rowCount();
$saldo_ini=0;
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $saldo_ini+=$saldo_inicial;
            $nom_ejer=$nom_ejercicio;
            $sem_ini=$semana_ini;
       }
}
//TOTAL DE ACCION
$stmt = $empleado->leeTotalAccion();
$num = $stmt->rowCount();
$total_accion=0;
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $total_accion+=$accion;
       }
}

$objPHPExcel->getActiveSheet()->setCellValue('D1', $nom_ejer);
$objPHPExcel->getActiveSheet()->setCellValue('J1', $sem_ini);
$objPHPExcel->getActiveSheet()->setCellValue('L1', $total_accion);

$x=5;
foreach ($semanas as &$semana){

  //OBTIENE DATOS DE LA SEMANA
  $semana_info->no_semana=$semana;
  $stmt = $semana_info->leeSemana();
  $num = $stmt->rowCount();
  if($num>0){
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          extract($row);
          $objPHPExcel->getActiveSheet()->setCellValue('D'.$x, $fecha_ini);
          $objPHPExcel->getActiveSheet()->setCellValue('F'.$x, $fecha_fin);
          $objPHPExcel->getActiveSheet()->setCellValue('H'.$x, $saldo_inicial);
      }
  }


//OBTIENE DATOS DEL AHORRO
$stmt = $ahorro->leeAhorrosSemana($semana);
$num = $stmt->rowCount();
$data_ahorro=0;
if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
          $data_ahorro+=$monto;
        }
}
  $objPHPExcel->getActiveSheet()->setCellValue('J'.$x, $data_ahorro);

//OBTIENE DATOS DE LOS ABONOS
$stmt = $abono->leeAbonosSemana($semana);
$num = $stmt->rowCount();
$data_abono=0;
if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
          $data_abono+=$monto;
    }
}
$objPHPExcel->getActiveSheet()->setCellValue('K'.$x, $data_abono);

//OBTIENE DATOS DE LOS ABONOS EFECT
$stmt = $abono_efec->leeAbonosSemana($semana);
$num = $stmt->rowCount();
$data_abono_efec=0;
if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
         $data_abono_efec+=$monto;
    }
}
$objPHPExcel->getActiveSheet()->setCellValue('L'.$x, $data_abono_efec);
//OBTIENE DATOS DE LOS PRESTAMOS
$stmt = $prestamo->leePrestamosSemana($semana);
$num = $stmt->rowCount();
$data_prestamo=0;
if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_prestamo+=$monto;
    }
}
  $objPHPExcel->getActiveSheet()->setCellValue('S'.$x, $data_prestamo);

//OBTIENE DATOS DE LOS MOVIMIENTOS
$stmt = $ent_sal->leeMovimientosSemana($semana);
$num = $stmt->rowCount();
$data_entradas=0;
$data_salidas=0;
$data_gastos=0;
if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_entradas+= $entradas;
        $data_salidas+= $salidas;
        $data_gastos+= $gastos;
    }
}
$objPHPExcel->getActiveSheet()->setCellValue('N'.$x, $data_entradas);
$objPHPExcel->getActiveSheet()->setCellValue('W'.$x, $data_salidas);
$objPHPExcel->getActiveSheet()->setCellValue('U'.$x, $data_gastos);
$x++;
}


/*
$objPHPExcel->getActiveSheet()->setCellValue('C14', $saldo_ini);
$objPHPExcel->getActiveSheet()->setCellValue('C16', $data_ahorro);
$objPHPExcel->getActiveSheet()->setCellValue('B40', $data_ahorro);
$objPHPExcel->getActiveSheet()->setCellValue('C17', $data_abono);
$objPHPExcel->getActiveSheet()->setCellValue('E40', $data_abono);
$objPHPExcel->getActiveSheet()->setCellValue('C26', $data_prestamo);
$objPHPExcel->getActiveSheet()->setCellValue('C40', $data_prestamo);
$objPHPExcel->getActiveSheet()->setCellValue('C21', $data_movimientos['abonos_efect']);
$objPHPExcel->getActiveSheet()->setCellValue('C22', $data_movimientos['entradas']);
$objPHPExcel->getActiveSheet()->setCellValue('L16', $data_movimientos['ahorro_teso']);
$objPHPExcel->getActiveSheet()->setCellValue('L17', $data_movimientos['abonos_teso']);
$objPHPExcel->getActiveSheet()->setCellValue('C28', $data_movimientos['gastos']);
$objPHPExcel->getActiveSheet()->setCellValue('C30', $data_movimientos['salidas']);*/

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objPHPExcel->getActiveSheet()->setTitle("Semana ".$semana);
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="HojadeDatos'.$nom_ejer.'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>
