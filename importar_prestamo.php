<?php
require_once("libs/PHPExcel/IOFactory.php");
// get database connection 
include_once 'config/database.php'; 
$database = new Database(); 
$db = $database->Coneccion();
 
// instantiate product object
include_once 'objects/prestamo.php';
include_once 'objects/ejercicio.php'; 
$prestamo = new Prestamo($db);
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
        $prestamo->id_empleado = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
        $prestamo->monto = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
        $prestamo->fecha = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
        $prestamo->plazo = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
        if(PHPExcel_Shared_Date::isDateTime($objPHPExcel->getActiveSheet()->getCell('C'.$i))) {
             $prestamo->fecha = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($prestamo->fecha)); 
        }
        $prestamo->id_ejercicio=$ejercicio->calculaEjercicio($prestamo->fecha);
        if(!$prestamo->guardaPrestamo()){
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