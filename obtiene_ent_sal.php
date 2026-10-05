<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/ent_sal.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$movimientos = new Ent_Sal($db);
$stmt = $movimientos->leeMovimientos();
$num = $stmt->rowCount();
$data="";
 

if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data .= '{';
            $data .= '"id_ent_sal":"'  . $id_ent_sal . '",';
            $data .= '"fecha":"'  . $fecha . '",';
            $data .= '"semana":"' . $semana . '",';
            $data .= '"importe":"' . $monto . '",';
            $data .= '"concepto":"' . $concepto . '",';
            $data .= '"tipo":"' . $tipo . '"';
        $data .= '}'; 
        $data .= $x<$num ? ',' : ''; $x++; } 
} 
echo '{"records":[' . $data . ']}'; 
?>