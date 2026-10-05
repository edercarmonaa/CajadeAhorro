<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/prestamo.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$prestamo = new Prestamo($db);
$data = json_decode(file_get_contents("php://input"));     
$prestamo->id_empleado = $data->id_empleado;
$stmt = $prestamo->leePrestamosEmpleado();
$num = $stmt->rowCount();
$data="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data .= '{';
			 $data .= '"id_prestamo":"'  . $id_prestamo . '",';
            $data .= '"id_empleado":"'  . $id_empleado . '",';
            $data .= '"plazo":"' . $plazo . '",';
            $data .= '"monto":"' . $monto . '",';
            $data .= '"fecha":"' . $fecha . '",';
            $data .= '"recibos":"' . $recibos . '",';
            $data .= '"total":"' . $total . '",';
            $data .= '"semana":"' . $semana . '",';
             $data .= '"interes":"' . $interes . '"';
        $data .= '}'; 
        $data .= $x<$num ? ',' : ''; $x++; } 
} 
echo '{"records":[' . $data . ']}'; 
?>