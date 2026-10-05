<?php 
include_once 'config/database.php'; 
include_once 'objects/empleado.php';  
$database = new Database(); 
$db = $database->Coneccion();
$empleado = new Empleado($db);
$data = json_decode(file_get_contents("php://input"));     
$empleado->id_empleado = $data->id_empleado;
 
// delete the product
if($empleado->borraEmpleado()){
    echo "Empleado borrado con éxito.";
}
 
// if unable to delete the product
else{
    echo "Imposible borrar Empleado.";
}
?>