<?php
include_once 'config/database.php';
include_once 'objects/ahorro.php';
include_once 'objects/ejercicio.php';
include_once 'objects/empleado.php';

$database = new Database();
$db = $database->Coneccion();
$ahorro = new Ahorro($db);
$ejercicio= new Ejercicio($db);
$empleado=new Empleado($db);
$data = json_decode(file_get_contents("php://input"));

$empleado->id_empleado=$data->id_empleado;
$ahorro->id_empleado = $data->id_empleado;
$ahorro->nombre = $data->nombre;
$ahorro->monto = $data->monto;
$ahorro->fecha = $data->fecha;
$ahorro->id_ejercicio=$ejercicio->calculaEjercicio($data->fecha);
$bandera = false;
$monto2=0;
$empleado->saldoNegativo();

if ($empleado->saldo < 0){
	$empleado->saldo= $empleado->saldo+$ahorro->monto;
  if ($empleado->saldo > 0){
    $monto2=$ahorro->monto-$empleado->saldo;
    $ahorro->monto=$empleado->saldo;
    $empleado->saldo=0;
    $empleado->actSaldo();
    $ahorro->deuda_ant=0;
    $bandera=$ahorro->guardaAhorro();
    $ahorro->monto=$monto2;
    $ahorro->deuda_ant=1;
    $bandera=$ahorro->guardaAhorro();
  }else{
    $empleado->actSaldo();
    $ahorro->deuda_ant=1;
    $bandera=$ahorro->guardaAhorro();
  }
}else{
  $ahorro->deuda_ant=0;
  $bandera=$ahorro->guardaAhorro();
}

if($bandera){
    echo "Cambio realizado con exito.";
}
else{
    echo "Imposible realizar el cambio";
}

?>
