<?php 
include_once 'config/database.php'; 
include_once 'objects/ent_sal.php';  
$database = new Database(); 
$db = $database->Coneccion();
$ent_sal = new Ent_sal($db);
$data = json_decode(file_get_contents("php://input"));     
$ent_sal->id_ent_sal = $data->id_ent_sal;
 
// delete the product
if($ent_sal->borraMovimiento()){
    echo "Movimiento Borrado con exito.";
}
 
// if unable to delete the product
else{
    echo "Imposible borrar el Movimiento.";
}
?>