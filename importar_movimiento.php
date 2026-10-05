<?php
require_once("libs/PHPExcel/IOFactory.php");
// get database connection 
include_once 'config/database.php'; 
$database = new Database(); 
$db = $database->Coneccion();
 
// instantiate product object
include_once 'objects/ent_sal.php';
include_once 'objects/ejercicio.php'; 
include_once 'objects/operacion.php'; 
$ent_sal = new Ent_Sal($db);
$ejercicio= new Ejercicio($db);
$operacion = new Operacion($db);
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
        $ent_sal->fecha = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
        $ent_sal->importe = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
        $ent_sal->concepto = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
        $ent_sal->id_tipo = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
        $ent_sal->id_tipo = $operacion->calculaOperacion($ent_sal->id_tipo);
        if(PHPExcel_Shared_Date::isDateTime($objPHPExcel->getActiveSheet()->getCell('A'.$i))) {
             $ent_sal->fecha = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($ent_sal->fecha)); 
        }
        $ent_sal->id_ejercicio=$ejercicio->calculaEjercicio($ent_sal->fecha);
        if(!$ent_sal->creaMovimiento()){
             $bandera=false;
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