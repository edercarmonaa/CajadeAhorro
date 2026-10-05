<?php 
include_once 'config/database.php'; 
include_once 'objects/ahorro.php';  
$database = new Database(); 
$db = $database->Coneccion();
$ahorro = new Ahorro($db);
$data = json_decode(file_get_contents("php://input"));     
$ahorro->id_empleado = $data->id_empleado;
$ahorro->fecha = $data->fecha;
$ahorro->monto = $data->monto;
 
// delete the product
if($ahorro->borraAhorro()){
    echo "Ahorro borrado con éxito.";
}
 
// if unable to delete the product
else{
    echo "Imposible borrar el Ahorro.";
}
?>