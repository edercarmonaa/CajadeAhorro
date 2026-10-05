<?php 
include_once 'config/database.php'; 
include_once 'objects/abono.php';  
$database = new Database(); 
$db = $database->Coneccion();
$abono = new Abono($db);
$data = json_decode(file_get_contents("php://input"));     
$abono->id_empleado = $data->id_empleado;
$abono->fecha = $data->fecha;
$abono->monto = $data->monto;
 
// delete the product
if($abono->borraAbono()){
    echo "Abono borrado con éxito.";
}
 
// if unable to delete the product
else{
    echo "Imposible borrar el Abono.";
}
?>