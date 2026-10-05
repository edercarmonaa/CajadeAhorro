<?php 
// get database connection 
include_once 'config/database.php'; 
$database = new Database(); 
$db = $database->Coneccion();
 
// instantiate product object
include_once 'objects/ent_sal.php';
include_once 'objects/ejercicio.php'; 

$ent_sal = new Ent_Sal($db);
$ejercicio= new Ejercicio($db);

 
// get posted data
$data = json_decode(file_get_contents("php://input")); 
 
// set product property values
$ent_sal->fecha = $data->fecha;
$ent_sal->importe = $data->importe;
$ent_sal->concepto = $data->concepto;
$ent_sal->id_tipo = $data->tipo;
$ent_sal->id_ejercicio=$ejercicio->calculaEjercicio($data->fecha);
     
// create the product
if($ent_sal->creaMovimiento()){
    echo "Movimiento Agregado con Exito.";
}
// if unable to create the product, tell the user
else{
    echo "No se Pudo Agregar el Movimiento";
}
?>