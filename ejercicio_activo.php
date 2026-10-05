<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
 
include_once 'config/database.php'; 
include_once 'objects/ejercicio.php'; 
 
$database = new Database(); 
$db = $database->Coneccion();
$ejercicio = new Ejercicio($db);
$ejer = $ejercicio->EjercicioActivo();
$data="";
$data .= '{';
$data .= '"nom_ejercicio":"'  . $ejer . '"';
$data .= '}'; 
echo '{"records":[' . $data . ']}'; 
?>