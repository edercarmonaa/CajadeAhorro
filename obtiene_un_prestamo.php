<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/prestamo.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$prestamo = new Prestamo($db);
$data = json_decode(file_get_contents("php://input"));     
$prestamo->id_prestamo = $data->id_prestamo;
$stmt = $prestamo->leePrestamo();
$num = $stmt->rowCount();
$data="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
		$prestamo_arr = array(
			'id_prestamo'=> $id_prestamo,
           	'id_empleado'=>   $id_empleado,
            'nombre' => $nombre,
            'monto' => $monto,
            'fecha' => $fecha,
            'plazo' => $plazo ); 
} 
}
print_r(json_encode($prestamo_arr));
?>