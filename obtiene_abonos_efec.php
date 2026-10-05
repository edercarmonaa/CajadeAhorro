<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/abono_efec.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$abono = new Abonoefectivo($db);
$stmt = $abono->leeAbonos();
$num = $stmt->rowCount();
$data="";
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