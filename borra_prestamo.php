<?php 
include_once 'config/database.php'; 
include_once 'objects/prestamo.php';  
$database = new Database(); 
$db = $database->Coneccion();
$prestamo = new Prestamo($db);
$data = json_decode(file_get_contents("php://input"));     
$prestamo->id_prestamo = $data->id_prestamo;

 
// delete the product
if($prestamo->borraPrestamo()){
    echo "Prestamo borrado con éxito.";
}
 
// if unable to delete the product
else{
    echo "Imposible borrar el prestamo.";
}
?>