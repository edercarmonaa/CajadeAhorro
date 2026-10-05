<?php
require_once("libs/PHPExcel/IOFactory.php");
// get database connection 
include_once 'config/database.php'; 
$database = new Database(); 
$db = $database->Coneccion();
 
// instantiate product object
include_once 'objects/empleado.php';
$empleado = new Empleado($db);
 
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
        $empleado->id_empleado = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
        $empleado->nombre = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
        if (strtoupper ($objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue())=='EVENTUAL'){
            $empleado->categoria = 1;
        }else if (strtoupper ($objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue())=='PLANTA'){
            $empleado->categoria = 2; 
        }
        $empleado->valor_accion = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
		$empleado->saldo = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
        if(!$empleado->creaEmpleado()){
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