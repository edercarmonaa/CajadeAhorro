<?php 
include_once 'config/database.php'; 
include_once 'objects/empleado.php'; 
$database = new Database(); 
$db = $database->Coneccion();
$empleado = new Empleado($db);
$data = json_decode(file_get_contents("php://input"));     
$empleado->id_empleado = $data->id_empleado;
$empleado->leeEmpleado();
$empleado_arr[] = array(
    "id_empleado" =>  $empleado->id_empleado,
    "nombre" => $empleado->nombre,
    "categoria" => $empleado->categoria,
    "accion" => $empleado->accion,
    "saldo" => $empleado->saldo
);
print_r(json_encode($empleado_arr));
?>