<?php

include_once 'config/database.php';
include_once 'objects/prestamo.php';
include_once 'objects/abono.php';
include_once 'objects/ahorro.php';
include_once 'objects/empleado.php';
include_once 'objects/ejercicio.php';
include_once 'objects/ent_sal.php';
include_once 'objects/semana.php';
include_once 'objects/abono_efec.php';
$database = new Database();
$db = $database->Coneccion();
$semana_info = new Semana($db);
$prestamo = new Prestamo($db);
$ahorro = new Ahorro($db);
$abono = new Abono($db);
$empleado = new Empleado($db);
$ejercicio = new ejercicio($db);
$ent_sal= new Ent_sal($db);
$abono_efec= new AbonoEfectivo($db);

//OBTIENE DATOS DEL EJERCICIO
$nom_ejercicio = $ejercicio->EjercicioActivo();
echo $nom_ejercicio;
$semana=0;
$data = json_decode(file_get_contents("php://input"));
$semana=$data->semana;
$semana_info->no_semana=$data->semana;
$semana_info->fecha_ini=$data->fecha_ini;
$semana_info->fecha_fin=$data->fecha_fin;
$semana_info->id_ejercicio=$ejercicio->calculaEjercicio($data->fecha_ini);

//OBTIENE SALDO_INICIAL
$stmt = $semana_info->leeSemana();
$num = $stmt->rowCount();
$saldo_ini=0;
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $saldo_ini+=$saldo_inicial;
        }
}

//OBTIENE DATOS DEL AHORRO
$stmt = $ahorro->leeAhorrosSemana($semana);
$num = $stmt->rowCount();
$data_ahorro="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data_ahorro+=$monto;
        }
}

//OBTIENE DATOS DE LOS ABONOS
$stmt = $abono->leeAbonosSemana($semana);
$num = $stmt->rowCount();
$data_abono="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
         $data_abono+=$monto;
    }
}

//OBTIENE DATOS DE LOS ABONOS EFECT
$stmt = $abono_efec->leeAbonosSemana($semana);
$num = $stmt->rowCount();
$data_abono_efec="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
         $data_abono_efec+=$monto;
    }
}


//OBTIENE DATOS DE LOS PRESTAMOS
$stmt = $prestamo->leePrestamosSemana($semana);
$num = $stmt->rowCount();
$data_prestamo="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_prestamo+=$monto;
    }
}


//OBTIENE DATOS DE LOS MOVIMIENTOS
$stmt = $ent_sal->leeMovimientosSemana($semana);
$num = $stmt->rowCount();
$data_movimientos="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_movimientos=array(
                "entradas" => $entradas,
                "salidas" => $salidas,
                "abonos_teso" => $abonos_teso,
                "ahorro_teso" => $ahorro_teso,
                "gastos" => $gastos,

        );
    }
}
$semana_info->saldo_final=$saldo_ini+$data_ahorro+$data_abono-$data_prestamo+$data_abono_efec+$data_movimientos['entradas']-$data_movimientos['gastos']-$data_movimientos['salidas'];
if($semana_info->corteSemanal()){
	$stmt = $semana_info->nextSemana();
	$num = $stmt->rowCount();
	if($num>0){
    	$x=1;
    	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $semana_info->saldo_inicial=$saldo_inicial;
				$semana_info->id_ejercicio=$id_ejercicio;
				$semana_info->no_semana=$no_semana;
				$semana_info->inicial=0;
        }
		$semana_info->creaSemana();
	}
    echo "Corte  Realizado con Exito.";
}
else{
    echo "No se Pudo Realizar el Corte";
}
?>
