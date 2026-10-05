<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/prestamo.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$ahorro = new Prestamo($db);
$stmt = $ahorro->leePrestamos();
$num = $stmt->rowCount();
$data="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data .= '{';
            $data .= '"id_empleado":"'  . $id_empleado . '",';
            $data .= '"nombre":"' . $nombre . '",';
            $data .= '"monto":"' . $monto . '",';
             $data .= '"interes":"' . $interes . '",';
              $data .= '"recibos":"' . $recibos . '",';
              $data .= '"total":"' . $total . '"';
        $data .= '}'; 
        $data .= $x<$num ? ',' : ''; $x++; } 
} 
echo '{"records":[' . $data . ']}'; 
?>