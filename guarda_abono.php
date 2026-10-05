<?php
include_once 'config/database.php';
include_once 'objects/abono.php';
include_once 'objects/ejercicio.php';
include_once 'objects/empleado.php';

$database = new Database();
$db = $database->Coneccion();
$abono = new Abono($db);
$ejercicio= new Ejercicio($db);
$empleado=new Empleado($db);
$data = json_decode(file_get_contents("php://input"));
$bandera = false;
$monto2=0;

$empleado->id_empleado=$data->id_empleado;
$abono->id_empleado = $data->id_empleado;
$abono->nombre = $data->nombre;
$abono->monto = $data->monto;
$abono->fecha = $data->fecha;
$abono->id_ejercicio=$ejercicio->calculaEjercicio($data->fecha);
$empleado->saldoNegativo();
if ($empleado->saldo < 0){
	$empleado->saldo= $empleado->saldo+$abono->monto;
  if ($empleado->saldo > 0){
    $monto2=$abono->monto-$empleado->saldo;
    $abono->monto=$empleado->saldo;
    $empleado->saldo=0;
    $empleado->actSaldo();
    $abono->deuda_ant=0;
    $bandera=$abono->guardaAbono();
    $abono->monto=$monto2;
    $abono->deuda_ant=1;
    $bandera=$abono->guardaAbono();
  }else{
    $empleado->actSaldo();
    $abono->deuda_ant=1;
    $bandera=$abono->guardaAbono();
  }
}else{
  $abono->deuda_ant=0;
  $bandera=$abono->guardaAbono();
}
if($bandera){
    echo "Cambio realizado con exito.";
}
else{
    echo "Imposible realizar el cambio";
}
?>
