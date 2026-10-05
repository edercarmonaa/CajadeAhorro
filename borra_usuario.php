<?php 
include_once 'config/database.php'; 
include_once 'objects/usuario.php';  
$database = new Database(); 
$db = $database->Coneccion();
$usuario = new Usuario($db);
$data = json_decode(file_get_contents("php://input"));     
$usuario->id_empleado = $data->id_empleado;
 
// delete the product
if($usuario->borraUsuario()){
    echo "Usuario borrado con éxito.";
}
 
// if unable to delete the product
else{
    echo "Imposible borrar Usuario.";
}
?>