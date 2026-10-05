<?php
require_once("libs/PHPExcel/IOFactory.php");
// get database connection
include_once 'config/database.php';
$database = new Database();
$db = $database->Coneccion();

// instantiate product object
include_once 'objects/ahorro.php';
include_once 'objects/ejercicio.php';
include_once 'objects/empleado.php';
$empleado=new Empleado($db);
$ahorro = new Ahorro($db);
 $ejercicio= new Ejercicio($db);
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
        $ahorro->id_empleado = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
        $empleado->id_empleado=$ahorro->id_empleado;
        $ahorro->monto = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
        $ahorro->fecha = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
        if(PHPExcel_Shared_Date::isDateTime($objPHPExcel->getActiveSheet()->getCell('C'.$i))) {
             $ahorro->fecha = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($ahorro->fecha));
        }
        $ahorro->id_ejercicio=$ejercicio->calculaEjercicio($ahorro->fecha);
        $monto2=0;
        $empleado->saldoNegativo();
        if ($empleado->saldo < 0){
        	$empleado->saldo= $empleado->saldo+$ahorro->monto;
          if ($empleado->saldo > 0){
            $monto2=$ahorro->monto-$empleado->saldo;
            $ahorro->monto=$empleado->saldo;
            $empleado->saldo=0;
            $empleado->actSaldo();
            $ahorro->deuda_ant=0;
            if(!$ahorro->guardaAhorro()){
                 $bandera=false;
            }
            $ahorro->monto=$monto2;
            $ahorro->deuda_ant=1;
            if(!$ahorro->guardaAhorro()){
                 $bandera=false;
            }
          }else{
            $empleado->actSaldo();
            $ahorro->deuda_ant=1;
            if(!$ahorro->guardaAhorro()){
                 $bandera=false;
            }
          }
        }else{
          $ahorro->deuda_ant=0;
          if(!$ahorro->guardaAhorro()){
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
