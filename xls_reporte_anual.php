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
include_once 'objects/ejercicio.php';
include_once 'objects/ent_sal.php';
include_once 'objects/empleado.php';
include_once 'objects/abono_efec.php';
$database = new Database();
$db = $database->Coneccion();
$prestamo = new Prestamo($db);
$ahorro = new Ahorro($db);
$abono = new Abono($db);
$ejercicio = new ejercicio($db);
$ent_sal= new Ent_sal($db);
$empleado = new Empleado($db);
$abono_efec= new AbonoEfectivo($db);

//OBTIENE DATOS DEL EMPLEADO
$stmt = $empleado->leeEmpleados();
$num = $stmt->rowCount();
$data_empleado="";
$x=0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    extract($row);
    $data_empleado[$x]=$id_empleado;
	$x++;
}


//OBTIENE DATOS DEL EJERCICIO
$nom_ejercicio = $ejercicio->EjercicioActivo();
$stmt = $ejercicio->EjercicioActivoCampos();
$num = $stmt->rowCount();
$saldo_ini=0;
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $saldo_ini=$saldo_inicial;
        }
}

//OBTIENE DATOS DEL AHORRO
$stmt = $ahorro->leeAhorros();
$num = $stmt->rowCount();
$data_ahorro=0;
$data_ahorro_emp="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data_ahorro+=$monto;
			$data_ahorro_emp[$id_empleado]=$monto;
        }
}


//OBTIENE DATOS DE LOS ABONOS
$stmt = $abono->leeAbonos();
$num = $stmt->rowCount();
$data_abono=0;
$data_abono_emp="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
         $data_abono+=$monto;
		 $data_abono_emp[$id_empleado]=$monto;
    }
}

//OBTIENE DATOS DE LOS ABONOS EFECTIVO
$stmt = $abono_efec->leeAbonos();
$num = $stmt->rowCount();
$data_abono_efec=0;
$data_abono_emp="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
         $data_abono_efec+=$monto;
		     $data_abono_emp[$id_empleado]=$monto;
    }
}


//OBTIENE DATOS DE LOS PRESTAMOS
$stmt = $prestamo->leePrestamos();
$num = $stmt->rowCount();
$data_prestamo=0;
$data_interes=0;
$data_recibos=0;
$data_prestamo_emp="";
$data_interes_emp="";
$data_recibos_emp="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_prestamo+=$monto;
        $data_recibos+=$recibos;
        $data_interes+=$interes;
		$data_prestamo_emp[$id_empleado]=$monto;
		$data_interes_emp[$id_empleado]=$recibos;
		$data_recibos_emp[$id_empleado]=$interes;
    }
}


//OBTIENE DATOS DE LOS MOVIMIENTOS
$stmt = $ent_sal->leeMovimientosEjercicio();
$num = $stmt->rowCount();
$data_entradas=0;
$data_salidas=0;
$data_abonos_teso=0;
$data_ahorros_teso=0;
$data_gastos=0;
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data_entradas += $entradas;
            $data_salidas += $salidas;
            $data_abonos_teso += $abonos_teso;
            $data_ahorros_teso += $ahorro_teso;
            $data_gastos += $gastos;
    }
}

//CALCULA SALDO CARGO Y SALDO FAVOR
$saldo_cargo=0;
$saldo_favor=0;
$saldo_emp=0;
foreach ($data_empleado as $id_empleado){
	$saldo_emp+=$data_ahorro_emp[$id_empleado]-$data_prestamo_emp[$id_empleado]-$data_interes_emp[$id_empleado]-$data_recibos_emp[$id_empleado];
	if(isset($data_abono_emp[$id_empleado])){
		$saldo_emp+=$data_abono_emp[$id_empleado];
	}
	if($saldo_emp > 0){
		$saldo_favor+=$saldo_emp;
	}else{
		$saldo_cargo-=$saldo_emp;
	}
	$saldo_emp=0;
}

//ABRE LA PLANTILLA
$objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objPHPExcel->load('templates/reporte_anual.xlsx');
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('D8', $nom_ejercicio);
$objPHPExcel->getActiveSheet()->setCellValue('C14', $saldo_ini);
$objPHPExcel->getActiveSheet()->setCellValue('C17', $data_ahorro);
$objPHPExcel->getActiveSheet()->setCellValue('C18', $data_abono);
$objPHPExcel->getActiveSheet()->setCellValue('C26', $data_prestamo);
$objPHPExcel->getActiveSheet()->setCellValue('C38', $data_interes);
$objPHPExcel->getActiveSheet()->setCellValue('E38', $data_recibos);
$saldo_interes=$data_ahorro+$data_abono-$data_prestamo;
$objPHPExcel->getActiveSheet()->setCellValue('B38', $saldo_interes);

$objPHPExcel->getActiveSheet()->setCellValue('L17', $data_ahorros_teso);
$objPHPExcel->getActiveSheet()->setCellValue('L18', $data_abonos_teso);
$objPHPExcel->getActiveSheet()->setCellValue('C28', $data_gastos);
$objPHPExcel->getActiveSheet()->setCellValue('C30', $data_salidas);
$objPHPExcel->getActiveSheet()->setCellValue('C22', $data_entradas);
$objPHPExcel->getActiveSheet()->setCellValue('C21', $data_abono_efec);
$objPHPExcel->getActiveSheet()->setCellValue('J38', $saldo_cargo*-1);
$objPHPExcel->getActiveSheet()->setCellValue('L38', $saldo_favor);



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objPHPExcel->getActiveSheet()->setTitle("Ejercicio ".$nom_ejercicio);
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Ejercicio '.$nom_ejercicio.'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>
