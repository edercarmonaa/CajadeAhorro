<?php 
 
include_once 'config/database.php'; 

$database = new Database(); 
$db = $database->Coneccion();
 

include_once 'objects/ejercicio.php';
include_once 'objects/semana.php'; 
$ejercicio = new Ejercicio($db);
$semana_info = new Semana($db);

$data = json_decode(file_get_contents("php://input")); 
 

$ejercicio->fecha_ini = $data->fecha_ini;
$ejercicio->fecha_fin = $data->fecha_fin;
$ejercicio->id_ejercicio = $data->id_ejercicio;
$ejercicio->nom_ejercicio = $data->nom_ejercicio;
$ejercicio->saldo_ini = $data->saldo_ini;
$ejercicio->estatus = $data->estatus;
$ejercicio->semana_ini = $data->semana_ini;

if($ejercicio->actualizaEjercicio()){
    echo "Ejercicio actualizado con Exito.";
}
else{
    echo "No se Pudo actualizar el Ejercicio";
}
$semana_info->no_semana=$data->semana_ini;
$semana_info->saldo_inicial=$data->saldo_ini;
$semana_info->id_ejercicio=$ejercicio->calculaEjercicio($data->fecha_ini);
$semana_info->actualizaSemanaSaldo();
?>