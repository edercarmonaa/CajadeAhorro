<?php 
// get database connection 
include_once 'config/database.php'; 
$database = new Database(); 
$db = $database->Coneccion();
 
// instantiate product object
include_once 'objects/empleado.php';
$empleado = new Empleado($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input")); 
 
// set product property values
$empleado->id_empleado = $data->id_empleado;
$empleado->nombre = $data->nombre;
$empleado->categoria = $data->categoria;
$empleado->valor_accion = $data->accion;
$empleado->saldo = $data->saldo;
     
// create the product
if($empleado->creaEmpleado()){
    echo "Empleado Agregado con Exito.";
}
// if unable to create the product, tell the user
else{
    echo "No se Pudo Agregar el Empleado";
}
?>