<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/ejercicio.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$ejercicio = new Ejercicio($db);
$stmt = $ejercicio->EjercicioActivoYears();
$num = $stmt->rowCount();
$data="";

if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data .= '{';
            $data .= '"year_ini":"'  . $year_ini . '",';
            $data .= '"year_fin":"' . $year_fin . '"';
        $data .= '}'; 
        $data .= $x<$num ? ',' : ''; $x++; } 
} 
echo '{"records":[' . $data . ']}'; 
?>