<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/ahorro.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$ahorro = new Ahorro($db);
$data = json_decode(file_get_contents("php://input"));     
$empleado->id_ejercicio = $data->id_ejercicio;
$stmt = $ahorro->leeAhorrosEjercicio();
$num = $stmt->rowCount();
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data .= '{';
            $data .= '"id_empleado":"'  . $id_empleado . '",';
            $data .= '"nombre":"' . $nombre . '",';
            $data .= '"monto":"' . $monto . '"';
        $data .= '}'; 
        $data .= $x<$num ? ',' : ''; $x++; } 
} 
echo '{"records":[' . $data . ']}'; 
?>