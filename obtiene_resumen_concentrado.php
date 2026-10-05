<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/prestamo.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$prestamo = new Prestamo($db);
$data = json_decode(file_get_contents("php://input"));     
$stmt = $prestamo->resumenPrestamosSemana($data->semana);
$num = $stmt->rowCount();
$data="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $saldo_favor=0;
            $saldo_cargo=0;
            $saldo= $abonos +$ahorros -$prestamo -$recibos - $interes;
            if($saldo >= 0){
                $saldo_favor=$saldo;
            }else{
                $saldo_cargo=$saldo;
            }
            $data .= '{';
            $data .= '"saldo":"'  . $saldo . '",';
            $data .= '"saldo_favor":"' . $saldo_favor . '",';
             $data .= '"saldo_cargo":"' . $saldo_cargo . '"';

        $data .= '}'; 
        $data .= $x<$num ? ',' : ''; $x++; } 
} 
echo '{"records":[' . $data . ']}'; 
?>