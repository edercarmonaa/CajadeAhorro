<?php 
include_once 'config/database.php'; 
include_once 'objects/usuario.php'; 
  
$database = new Database(); 
$db = $database->Coneccion();
$usuario = new Usuario($db);
$data = json_decode(file_get_contents("php://input"));     

$usuario->id_empleado = $data->id_empleado;
$usuario->nom_usr = $data->nom_usr;
$usuario->nivel = $data->nivel;
$usuario->password = password_hash($data->password, PASSWORD_BCRYPT);
 
if($usuario->actualizaUsuario()){
    echo "Cambio realizado con exito.";
}
else{
    echo "Imposible realizar el cambio";
}
?>