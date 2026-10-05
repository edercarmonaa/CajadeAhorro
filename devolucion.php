<?php
include_once 'config/database.php';
include_once 'objects/empleado.php';
include_once 'objects/devolucion.php';
include_once 'objects/prestamo.php';
include_once 'objects/abono.php';
include_once 'objects/ahorro.php';
include_once 'objects/abono_efec.php';
$database = new Database();
$db = $database->Coneccion();
$empleado = new Empleado($db);
$abono_efec = new AbonoEfectivo($db);
$prestamos = new Prestamo($db);
$ahorros = new Ahorro($db);
$abonos = new Abono($db);
$devolucion = new Devolucion($db);
$data = json_decode(file_get_contents("php://input"));
$empleado->id_empleado = $data->id_empleado;
$abonos->id_empleado = $data->id_empleado;
$abono_efec->id_empleado=$data->id_empleado;
$ahorros->id_empleado=$data->id_empleado;
$prestamos->id_empleado=$data->id_empleado;
$stmt = $empleado->EmpleadoDevolucion();
$num = $stmt->rowCount();
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
          $devolucion->id_empleado= $id_empleado;
          $devolucion->nombre=$nombre;
          $devolucion->prestamos=$prestamo;
          $devolucion->abonos=$abono;
          $devolucion->ahorros=$ahorro;
          $devolucion->valor_accion=$valor_accion;
          $devolucion->fecha=date('Y-m-d');
          $devolucion->saldo=$valor_accion+$abono+$ahorro-$prestamo;
          $devolucion->id_ejercicio=$id_ejercicio;
          $abono_efec->id_ejercicio=$id_ejercicio;
          $abonos->id_ejercicio=$id_ejercicio;
          $ahorros->id_ejercicio=$id_ejercicio;
          $prestamos->id_ejercicio=$id_ejercicio;
    }
    $abono_efec->borraAbonoDevolucion();
    $abonos->borraAbonoDevolucion();
    $ahorros->borraAhorroDevolucion();
    $prestamos->borraPrestamoDevolucion();
    $empleado->borraEmpleado();
    if ($devolucion->creaDevolucion()){
      echo "Devolucion Creada con exito!!!";
    }else{
      echo "Imposible realizar la devolucion";
    }
}

?>
