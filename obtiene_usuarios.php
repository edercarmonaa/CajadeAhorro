<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/usuario.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$usuario = new Usuario($db);
$stmt = $usuario->leeUsuarios();
$num = $stmt->rowCount();
$data="";
 

 if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data .= '{';
            $data .= '"id_empleado":"'  . $id_empleado . '",';
            $data .= '"nom_usr":"' . $nom_usr . '",';
            $data .= '"nivel":"' . $nivel . '"';
        $data .= '}'; 
        $data .= $x<$num ? ',' : ''; $x++; } 
} 
echo '{"records":[' . $data . ']}'; 
?>