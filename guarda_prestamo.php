<?php 
include_once 'config/database.php'; 
include_once 'objects/prestamo.php'; 
include_once 'objects/ejercicio.php'; 
  
$database = new Database(); 
$db = $database->Coneccion();
$prestamo = new Prestamo($db);
$ejercicio= new Ejercicio($db);
$data = json_decode(file_get_contents("php://input"));     
$prestamo->id_empleado = $data->id_empleado;
$prestamo->monto = $data->monto;
$prestamo->fecha = $data->fecha;
$prestamo->plazo = $data->plazo;
$prestamo->id_ejercicio=$ejercicio->calculaEjercicio($data->fecha);

if($prestamo->guardaPrestamo()){
    echo "Cambio realizado con exito.";
}
else{
    echo "Imposible realizar el cambio";
}
?>