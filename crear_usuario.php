<?php 
// get database connection 
include_once 'config/database.php'; 
$database = new Database(); 
$db = $database->Coneccion();
 
// instantiate product object
include_once 'objects/usuario.php';
$usuario = new Usuario($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input")); 

// set product property values
$usuario->id_empleado = $data->id_empleado;
$usuario->nom_usr = $data->nom_usr;
$usuario->nivel = $data->nivel;
$usuario->password = password_hash($data->password, PASSWORD_BCRYPT);
     
// create the product
if($usuario->creaUsuario()){
    echo "Usuario creado con Exito.";
}
// if unable to create the product, tell the user
else{
    echo "No se Pudo crear el Usuario";
}
?>