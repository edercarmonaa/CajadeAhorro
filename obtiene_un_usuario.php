<?php 
include_once 'config/database.php'; 
include_once 'objects/usuario.php'; 
$database = new Database(); 
$db = $database->Coneccion();
$usuario = new Usuario($db);
$data = json_decode(file_get_contents("php://input"));     
$usuario->id_empleado = $data->id_empleado;
$usuario->leeUsuarioEdit();
$usuario_arr[] = array(
    "id_empleado" =>  $usuario->id_empleado,
    "nom_usr" => $usuario->nombre,
    "nivel" => $usuario->nivel
);
print_r(json_encode($usuario_arr));
?>