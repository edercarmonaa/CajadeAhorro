<?php
require_once("libs/PHPExcel/IOFactory.php");
// get database connection
include_once 'config/database.php';
$database = new Database();
$db = $database->Coneccion();

// instantiate product object
include_once 'objects/abono.php';
include_once 'objects/ejercicio.php';
include_once 'objects/empleado.php';
$abono = new Abono($db);
$ejercicio= new Ejercicio($db);
$empleado=new Empleado($db);
// get posted data
$data = json_decode(file_get_contents("php://input"));

$bandera= true;
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $temp = explode(".", $_FILES["file"]["name"]);
    $newfilename = substr(md5(time()), 0, 10) . '.' . end($temp);
    move_uploaded_file($_FILES['file']['tmp_name'], 'archivos/' . $newfilename);
    $objPHPExcel = PHPExcel_IOFactory::load('archivos/' . $newfilename);
    $objPHPExcel->setActiveSheetIndex(0);
    $numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
    for ($i = 1; $i <= $numRows; $i++) {
        $monto2=0;
        $abono->id_empleado = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
        $empleado->id_empleado=$abono->id_empleado;
        $abono->monto = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
        $abono->fecha = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
        if(PHPExcel_Shared_Date::isDateTime($objPHPExcel->getActiveSheet()->getCell('C'.$i))) {
             $abono->fecha = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($abono->fecha));
        }
        $abono->id_ejercicio=$ejercicio->calculaEjercicio($abono->fecha);
        $empleado->saldoNegativo();
        if ($empleado->saldo < 0){
        	$empleado->saldo= $empleado->saldo+$abono->monto;
          if ($empleado->saldo > 0){
            $monto2=$abono->monto-$empleado->saldo;
            $abono->monto=$empleado->saldo;
            $empleado->saldo=0;
            $empleado->actSaldo();
            $abono->deuda_ant=0;
            if(!$abono->guardaAbono()){
                 $bandera=false;
            }
            $abono->monto=$monto2;
            $abono->deuda_ant=1;
            if(!$abono->guardaAbono()){
                 $bandera=false;
            }
          }else{
            $empleado->actSaldo();
            $abono->deuda_ant=1;
            if(!$abono->guardaAbono()){
                 $bandera=false;
            }
          }
        }else{
          $abono->deuda_ant=0;
          if(!$abono->guardaAbono()){
               $bandera=false;
          }
        }
    }
    if($bandera==true){
        echo "Archivo Importado con exito";
    }else{
        echo "El archivo contiene errores";
    }
    unlink('archivos/' . $newfilename);
}

?>
