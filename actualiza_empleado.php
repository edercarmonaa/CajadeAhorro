<?php 
include_once 'config/database.php'; 
include_once 'objects/empleado.php'; 
  
$database = new Database(); 
$db = $database->Coneccion();
$empleado = new Empleado($db);
$data = json_decode(file_get_contents("php://input"));     

$empleado->id_empleado = $data->id_empleado;
$empleado->nombre = $data->nombre;
$empleado->categoria = $data->categoria;
$empleado->valor_accion = $data->accion;
$empleado->saldo = $data->saldo;
 
if($empleado->actualizaEmpleado()){
    echo "Cambio realizado con exito.";
}
else{
    echo "Imposible realizar el cambio";
}
?>