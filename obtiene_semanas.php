<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/semana.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$semana = new Semana($db);
$stmt = $semana->leeSemanas();
$num = $stmt->rowCount();
$data="";

if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data .= '{';
            $data .= '"id_semana":"'  . $id_semana . '",';
            $data .= '"no_semana":"' . $no_semana . '",';
			$data .= '"fecha_inicial":"' . $fecha_ini . '",';
			$data .= '"fecha_final":"' . $fecha_fin . '",';
			$data .= '"saldo_inicial":"' . $saldo_inicial . '",';
			$data .= '"saldo_final":"' . $saldo_final . '"';
        $data .= '}'; 
        $data .= $x<$num ? ',' : ''; $x++; } 
} 
echo '{"records":[' . $data . ']}'; 
?>