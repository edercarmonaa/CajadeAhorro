<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/ejercicio.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$ejercicio = new Ejercicio($db);
$data = json_decode(file_get_contents("php://input"));     
$ejercicio->id_ejercicio = $data->id_ejercicio;
$stmt = $ejercicio->leeEjercicio();
$num = $stmt->rowCount();
$data="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
		$prestamo_arr = array(
			'id_ejercicio'=> $id_ejercicio,
           	'nom_ejercicio'=>   $nom_ejercicio,
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
            'saldo_inicial' => $saldo_inicial,
            'semana_ini' => $semana_ini,
            'estatus' => $activo ); 
} 
}
print_r(json_encode($prestamo_arr));
?>