<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/empleado.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$empleado = new Empleado($db);
$stmt = $empleado->leeEmpleados();
$num = $stmt->rowCount();
$data="";
 

if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data .= '{';
            $data .= '"id_empleado":"'  . $id_empleado . '",';
            $data .= '"nombre":"' . $nombre . '",';
            $data .= '"categoria":"' . $categoria . '",';
            $data .= '"accion":"' . $accion . '",';
            $data .= '"interes":"' . $interes . '",';
			$data .= '"saldo":"' . $saldo . '"';
        $data .= '}'; 
        $data .= $x<$num ? ',' : ''; $x++; } 
} 
echo '{"records":[' . $data . ']}'; 
?>