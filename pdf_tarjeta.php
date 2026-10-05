<?php
ini_set('memory_limit', '-1');
require_once('libs/tcpdf/tcpdf.php');
include_once 'config/database.php';
include_once 'objects/prestamo.php';
include_once 'objects/abono.php';
include_once 'objects/ahorro.php';
include_once 'objects/empleado.php';
include_once 'objects/ejercicio.php';

$database = new Database();
$db = $database->Coneccion();
$prestamo = new Prestamo($db);
$ahorro = new Ahorro($db);
$abono = new Abono($db);
$empleado = new Empleado($db);
$empleados=new Empleado($db);
$ejercicio = new ejercicio($db);

$semanas = array(50,51,52,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,
22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,
49);


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(15, 12, 0);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetFont('Helvetica', '', 9);

//OBTIENE DATOS DEL EJERCICIO
$nom_ejercicio = $ejercicio->EjercicioActivo();
$stmt_principal = $empleado->leeEmpleados();
$num = $stmt_principal->rowCount();
if($num>0){
while ($row_principal = $stmt_principal->fetch(PDO::FETCH_ASSOC)){
	  extract($row_principal);

//OBTIENE DATOS DEL EMPELADO
$empleado->id_empleado = $id_empleado;


$empleado->leeEmpleadoXls();
$empleado_arr = array(
    "id_empleado" =>  $empleado->id_empleado,
    "nombre" => $empleado->nombre,
    "categoria" => $empleado->categoria,
    "accion" => $empleado->accion,
    "interes" => $empleado->interes
);
//OBTIENE DATOS DEL AHORRO
$ahorro->id_empleado = $id_empleado;


$stmt = $ahorro->leeAhorrosEmpleado();
$num = $stmt->rowCount();
$data_ahorro="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            $data_ahorro[$semana]=array(
                 "monto" => $monto,
                "fecha" => $fecha
            );
        }
}

//OBTIENE DATOS DE LOS ABONOS
$abono->id_empleado = $id_empleado;

$stmt = $abono->leeAbonosEmpleado();
$num = $stmt->rowCount();
$data_abono="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
         $data_abono[$semana]=array(
                 "monto" => $monto,
                "fecha" => $fecha
        );
    }
}

//OBTIENE DATOS DE LOS PRESTAMOS
$prestamo->id_empleado = $id_empleado;

$stmt = $prestamo->leePrestamosEmpleado();
$num = $stmt->rowCount();
$data_prestamo="";
if($num>0){
    $x=1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_prestamo[$semana]=array(
                "monto" => $monto,
                "plazo" => $plazo,
                "recibos" => $recibos,
                "interes" => $interes,
        );
    }
}



$pdf->AddPage();


$html = '<table border="1" cellspacing="1" cellpadding="1" style="font-size: small;">';
$html.= '<tr align="center" style="font-size: small; font-weight: bold;">
				<th style="border: 0px solid white;" width="5%"></th>
				<th style="border: 0px solid white;" width="8%"></th>
				<th style="border: 0px solid white;" colspan="7"> SINDICATO ÚNICO DE TRABAJADORES DE LA INDUSTRIA LÁCTEA ALIMENTICIA,<br>
													SIMILARES Y CONEXOS DE LA REPÚBLICA MEXICANA    SECCIÓN COATEPEC <br>
													Tarjetas de Caja de Ahorro y Préstamos
				</th>
				<th style="border: 0px solid white;"></th>
				</tr>
				<tr >
				<th style="border: 0px solid white; border-bottom: 1px solid black;"  width="5%" ></th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;" width="8%" ></th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				<th align="right" style="border: 0px solid white; border-bottom: 1px solid black;" >Ejercicio</th>
				<th align="center" style="border: 0px solid white; border-bottom: 1px solid black;" bgcolor="#00FFFF">'. $nom_ejercicio.'</th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				</tr>
				<tr style="font-size: small; font-weight: bold;" >
				<th align="center" colspan="2" style="border: 0px solid white; " bgcolor="#FFFF99">'.$empleado_arr['id_empleado'].'</th>
				<th colspan="2"style="border: 0px solid white; "></th>
				<th align="center" colspan="3" style="border: 0px solid white; " bgcolor="#FFFF99">'.$empleado_arr['categoria'].'</th>
				<th style="border: 0px solid white; "></th>
				<th align="center" colspan="2" style="border: 0px solid white; " bgcolor="#FFFF99">'.$empleado_arr['accion'].'</th>
				</tr>
				<tr style="font-size: small; font-weight: bold;" >
				<th align="center" colspan="2" style="border: 0px solid white; ">NUMERO</th>
				<th colspan="2"style="border: 0px solid white; "></th>
				<th align="center" colspan="3" style="border: 0px solid white; ">CATEGORIA</th>
				<th style="border: 0px solid white; "></th>
				<th align="center" colspan="2" style="border: 0px solid white; ">VALOR DE ACCION</th>
				</tr>
				<tr style="font-size: small; font-weight: bold;" >
				<th align="center" colspan="6" style="border: 0px solid white; " bgcolor="#FFFF99">'.$empleado_arr['nombre'].'</th>
				<th style="border: 0px solid white; "></th>
				<th align="center" colspan="3" style="border: 0px solid white; ">'.$empleado_arr['interes'].'%</th>
				</tr>
				<tr style="font-size: small; font-weight: bold;" >
				<th align="center" colspan="6" style="border: 0px solid white; " >NOMBRE</th>
				<th style="border: 0px solid white; "></th>
				<th align="center" colspan="3" style="border: 0px solid white; ">INTERES MENSUAL</th>
				</tr>';
$html.='<tr  align="center" style="font-size: small; font-weight: bold;">
		<th width="5%" style="color: rgb(0, 128, 0);">SEM</th>
		<th width="8%" style="color: rgb(0, 128, 0);">FECHA</th>
		<th style="color: rgb(0, 0, 255);">AHORRO</th>
		<th style="color: rgb(0, 0, 255);">ABONO</th>
		<th style="color: rgb(153, 51, 102);">PRESTAMO</th>
		<th style="color: rgb(255, 0, 0);">MESES</th>
		<th style="color: rgb(0, 128, 0);">INTERES</th>
		<th style="color: rgb(153, 51, 102);">SALDO A CARGO</th>
		<th style="color: rgb(0, 0, 255);">SALDO A FAVOR</th>
		<th style="color: rgb(0, 128, 0);">COSTO RECIBOS</th>
</tr>';
$total_ahorro=0;
$total_abono=0;
$total_prestamo=0;
$total_interes=0;
$total_recibos=0;
$saldo_favor=0;
$saldo_cargo=0;
$saldo_final=0;
foreach ($semanas as &$semana_arr) {
	$html.='<tr  align="center" style="font-size: small;">
			<th width="5%" style="border: 0px solid white; color: rgb(0, 128, 0);" >'.$semana_arr.'</th>';
			if(isset($data_ahorro[$semana_arr])){
					$html.='<th style="border: 0px solid white;">'. $data_ahorro[$semana_arr]['fecha'].'</th>
					<th style="border: 0px solid white;">'.$data_ahorro[$semana_arr]['monto'].'</th>';
					$total_ahorro+=$data_ahorro[$semana_arr]['monto'];
	    } else {
				$html.='<th style="border: 0px solid white;" >--</th><th style="border: 0px solid white;">--</th>';
	    }

			if(isset($data_abono[$semana_arr])){
					$html.='<th style="border: 0px solid white;">'. $data_abono[$semana_arr]['monto'].'</th>';
					$total_abono+=$data_abono[$semana_arr]['monto'];
	    }   else {
	        $html.='<th style="border: 0px solid white;">--</th>';
	    }

			if(isset($data_prestamo[$semana_arr])){
	       	$html.='<th style="border: 0px solid white;">'. $data_prestamo[$semana_arr]['monto'].'</th>';
					$total_prestamo+=$data_prestamo[$semana_arr]['monto'];
	       	$html.='<th style=" border: 0px solid white; color: rgb(255, 0, 0);">'.$data_prestamo[$semana_arr]['plazo'].'</th>';
	       	$html.='<th style=" border: 0px solid white; color: rgb(0, 128, 0);">'.$data_prestamo[$semana_arr]['interes'].'</th>';
					$total_interes+=$data_prestamo[$semana_arr]['interes'];
					$html.='<th style="border: 0px solid white;"></th>';
					$html.='<th style="border: 0px solid white;"></th>';
					$html.='<th style="border: 0px solid white;">'. $data_prestamo[$semana_arr]['recibos'].'</th>';
					$total_recibos+=$data_prestamo[$semana_arr]['recibos'];
	    }   else {
	        $html.='<th style="border: 0px solid white;">--</th>';
	        $html.='<th style="border: 0px solid white;" >--</th>';
					$html.='<th style="border: 0px solid white;">--</th>';
	        $html.='<th style="border: 0px solid white;"></th>';
					$html.='<th style="border: 0px solid white;" ></th>';
					$html.='<th style="border: 0px solid white;">--</th>';
	    }
			$html.='</tr>';
}
$saldo_final=$total_ahorro+$total_abono-$total_prestamo-$total_interes-$total_recibos;

$html.='<tr  align="center" style="font-size: small;">
			<th width="5%" ></th>
			<th width="8%"></th>
			<th  bgcolor="#CCFFFF">'.$total_ahorro.'</th>
			<th  bgcolor="#CCFFFF">'.$total_abono.'</th>
			<th  bgcolor="#CCFFFF">'.$total_prestamo.'</th>
			<th  bgcolor="#CCFFFF"></th>
			<th  bgcolor="#CCFFFF">'.$total_interes.'</th>';
			if ($saldo_final < 0){
				$html.='<th  bgcolor="#CCFFFF">'.$saldo_final.'</th>
				<th  bgcolor="#CCFFFF"></th>';
			}else{
				$html.='<th  bgcolor="#CCFFFF"></th>
				<th  bgcolor="#CCFFFF">'.$saldo_final.'</th>';
			}

			$html.='<th  bgcolor="#CCFFFF">'.$total_recibos.'</th>
			</tr>
';
$html.='<tr><th style="border: 0px solid white;" colspan="10"></th></tr>
				<tr><th style="border: 0px solid white;" colspan="10"></th></tr>
				<tr><th style="border: 0px solid white;" colspan="10"></th></tr>
				<tr>
				<th style="border: 0px solid white; border-bottom: 1px solid black;" colspan="5"></th>
				<th style="border: 0px solid white;" colspan="5"></th>
				</tr>
				<tr>
				<th align="center" style="border: 0px solid white; font-weight: bold;" colspan="5">FIRMA DE CONFORMIDAD</th>
				<th style="border: 0px solid white;" colspan="5"></th>
				</tr>';
$html.='</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->lastPage();

$pdf->AddPage();
$html = '<table border="1" cellspacing="1" cellpadding="1" style="font-size: small;">';
$html.= '<tr align="center" style="font-size: small; font-weight: bold;">
				<th style="border: 0px solid white;" width="5%"></th>
				<th style="border: 0px solid white;" width="8%"></th>
				<th style="border: 0px solid white;" colspan="7"> SINDICATO ÚNICO DE TRABAJADORES DE LA INDUSTRIA LÁCTEA ALIMENTICIA,<br>
													SIMILARES Y CONEXOS DE LA REPÚBLICA MEXICANA    SECCIÓN COATEPEC <br>
													Tarjetas de Caja de Ahorro y Préstamos
				</th>
				<th style="border: 0px solid white;"></th>
				</tr>
				<tr >
				<th style="border: 0px solid white; border-bottom: 1px solid black;"  width="5%" ></th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;" width="8%" ></th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				<th align="right" style="border: 0px solid white; border-bottom: 1px solid black;" >Ejercicio</th>
				<th align="center" style="border: 0px solid white; border-bottom: 1px solid black;" bgcolor="#00FFFF">'. $nom_ejercicio.'</th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				</tr>
				<tr style="font-size: small; font-weight: bold;" >
				<th align="center" colspan="2" style="border: 0px solid white; " bgcolor="#FFFF99">'.$empleado_arr['id_empleado'].'</th>
				<th colspan="2"style="border: 0px solid white; "></th>
				<th align="center" colspan="3" style="border: 0px solid white; " bgcolor="#FFFF99">'.$empleado_arr['categoria'].'</th>
				<th style="border: 0px solid white; "></th>
				<th align="center" colspan="2" style="border: 0px solid white; " bgcolor="#FFFF99">'.$empleado_arr['accion'].'</th>
				</tr>
				<tr style="font-size: small; font-weight: bold;" >
				<th align="center" colspan="2" style="border: 0px solid white; ">NUMERO</th>
				<th colspan="2"style="border: 0px solid white; "></th>
				<th align="center" colspan="3" style="border: 0px solid white; ">CATEGORIA</th>
				<th style="border: 0px solid white; "></th>
				<th align="center" colspan="2" style="border: 0px solid white; ">VALOR DE ACCION</th>
				</tr>
				<tr style="font-size: small; font-weight: bold;" >
				<th align="center" colspan="6" style="border: 0px solid white; " bgcolor="#FFFF99">'.$empleado_arr['nombre'].'</th>
				<th style="border: 0px solid white; "></th>
				<th align="center" colspan="3" style="border: 0px solid white; ">'.$empleado_arr['interes'].'%</th>
				</tr>
				<tr style="font-size: small; font-weight: bold;" >
				<th align="center" colspan="6" style="border: 0px solid white; border-bottom: 1px solid black;" >NOMBRE</th>
				<th style="border: 0px solid white; border-bottom: 1px solid black;"></th>
				<th align="center" colspan="3" style="border: 0px solid white; border-bottom: 1px solid black;">INTERES MENSUAL</th>
				</tr>';
$saldo_favor=0;
$total_pagos=$total_abono+$total_ahorro+$empleado_arr['accion'];
$total_cobros=$total_prestamo+$total_interes+$total_recibos+$empleado_arr['accion'];
$saldo_favor=$total_pagos-$total_cobros;
$saldo_total=$saldo_favor;
for ($x=1; $x <= 6; $x++) {
	$html.='<tr  align="center" style="font-size: small;">
					<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
	$html.='</tr>';
}
$html.='<tr style="font-size: small;">
				<th colspan="2" style="border: 0px solid white;" ></th>
				<th  align="left" colspan="3" style="border: 0px solid white;" >Valor de Accion</th>
				<th colspan="2" style="border: 0px solid white;" ></th>
				<th align="center" colspan="2" style="border: 0px solid white;" bgcolor="#FFFF99">'.$empleado_arr['accion'].'</th>
				<th style="border: 0px solid white;" ></th>
				</tr>';
	for ($x=1; $x <= 2; $x++) {
		$html.='<tr  align="center" style="font-size: small;">
						<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
		$html.='</tr>';
	}

	$html.='<tr  style="font-size: small;">
					<th colspan="2" style="border: 0px solid white;" ></th>
					<th  align="left" colspan="3" style="border: 0px solid white;" >Ahorro</th>
					<th colspan="2" style="border: 0px solid white;" ></th>
					<th align="center" colspan="2" style="border: 0px solid white;" bgcolor="#FFFF99">'.$total_ahorro.'</th>
					<th style="border: 0px solid white;" ></th>
					</tr>';
	for ($x=1; $x <= 2; $x++) {
		$html.='<tr  align="center" style="font-size: small;">
						<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
		$html.='</tr>';
	}

	$html.='<tr style="font-size: small;">
					<th colspan="2" style="border: 0px solid white;" ></th>
					<th align="left" colspan="3" style="border: 0px solid white;" >Abono</th>
					<th colspan="2" style="border: 0px solid white;" ></th>
					<th align="center" colspan="2" style="border: 0px solid white;" bgcolor="#FFFF99">'.$total_abono.'</th>
					<th style="border: 0px solid white;" ></th>
					</tr>';

		for ($x=1; $x <= 2; $x++) {
			$html.='<tr  align="center" style="font-size: small;">
							<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
			$html.='</tr>';
		}

		$html.='<tr style="font-size: small;">
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="left" colspan="3" style="border: 0px solid white; font-weight: bold;" >TOTAL DE PAGOS</th>
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="center" colspan="2" style="border: 0px solid white; font-weight: bold;" bgcolor="#FFFF99">'.$total_pagos.'</th>
						<th style="border: 0px solid white;" ></th>
						</tr>';
		for ($x=1; $x <= 2; $x++) {
			$html.='<tr  align="center" style="font-size: small;">
							<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
			$html.='</tr>';
		}

		$html.='<tr style="font-size: small;">
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="left" colspan="3" style="border: 0px solid white;" >Prestamo</th>
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="center" colspan="2" style="border: 0px solid white;" bgcolor="#FFFF99">'.$total_prestamo.'</th>
						<th style="border: 0px solid white;" ></th>
						</tr>';
		for ($x=1; $x <= 2; $x++) {
			$html.='<tr  align="center" style="font-size: small;">
							<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
			$html.='</tr>';
		}

		$html.='<tr style="font-size: small;">
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="left" colspan="3" style="border: 0px solid white;" >Interes Cobrado</th>
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="center" colspan="2" style="border: 0px solid white;" bgcolor="#FFFF99">'.$total_interes.'</th>
						<th style="border: 0px solid white;" ></th>
						</tr>';

		for ($x=1; $x <= 2; $x++) {
			$html.='<tr  align="center" style="font-size: small;">
							<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
			$html.='</tr>';
		}

		$html.='<tr style="font-size: small;">
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="left" colspan="3" style="border: 0px solid white;" >Recibos</th>
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="center" colspan="2" style="border: 0px solid white;" bgcolor="#FFFF99">'.$total_recibos.'</th>
						<th style="border: 0px solid white;" ></th>
						</tr>';
		for ($x=1; $x <= 2; $x++) {
			$html.='<tr  align="center" style="font-size: small;">
							<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
			$html.='</tr>';
		}

		$html.='<tr style="font-size: small;">
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="left" colspan="3" style="border: 0px solid white;" >Cobro Valor de Accion</th>
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="center" colspan="2" style="border: 0px solid white;" bgcolor="#FFFF99">'.$empleado_arr['accion'].'</th>
						<th style="border: 0px solid white;" ></th>
						</tr>';
		for ($x=1; $x <= 2; $x++) {
			$html.='<tr  align="center" style="font-size: small;">
							<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
			$html.='</tr>';
		}

		$html.='<tr style="font-size: small;">
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="left" colspan="3" style="border: 0px solid white; font-weight: bold;" >TOTAL DE COBROS</th>
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="center" colspan="2" style="border: 0px solid white; font-weight: bold;" bgcolor="#FFFF99">'.$total_cobros.'</th>
						<th style="border: 0px solid white;" ></th>
						</tr>';

		for ($x=1; $x <= 3; $x++) {
			$html.='<tr  align="center" style="font-size: small;">
							<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
			$html.='</tr>';
		}

		$html.='<tr style="font-size: small;">
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="left" colspan="3" style="border: 0px solid white; font-weight: bold;" >SALDO A FAVOR</th>
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="center" colspan="2" style="border: 0px solid white; font-weight: bold;" bgcolor="#FFFF99">'.$saldo_favor.'</th>
						<th style="border: 0px solid white;" ></th>
						</tr>';
		for ($x=1; $x <= 3; $x++) {
			$html.='<tr  align="center" style="font-size: small;">
							<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
			$html.='</tr>';
		}

		$html.='<tr style="font-size: small;">
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="left" colspan="3" style="border: 0px solid white; font-weight: bold;" >INTERESES A REPARTIR</th>
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="center" colspan="2" style="border: 0px solid white; font-weight: bold;" bgcolor="#FFFF99">0</th>
						<th style="border: 0px solid white;" ></th>
						</tr>';
		for ($x=1; $x <= 3; $x++) {
			$html.='<tr  align="center" style="font-size: small;">
							<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
			$html.='</tr>';
		}

		$html.='<tr style="font-size: small;">
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="left" colspan="3" style="border: 0px solid white; font-weight: bold;" >SALDO TOTAL A FAVOR</th>
						<th colspan="2" style="border: 0px solid white;" ></th>
						<th align="center" colspan="2" style="border: 0px solid white; font-weight: bold;" bgcolor="#FFFF99">'.$saldo_total.'</th>
						<th style="border: 0px solid white;" ></th>
						</tr>';
	for ($x=1; $x <= 9; $x++) {
		$html.='<tr  align="center" style="font-size: small;">
						<th colspan="10" style="border: 0px solid white; color: rgb(0, 128, 0);" ></th>';
		$html.='</tr>';
	}
$html.='<tr><th style="border: 0px solid white;" colspan="10"></th></tr>
				<tr><th style="border: 0px solid white;" colspan="10"></th></tr>
				<tr><th style="border: 0px solid white;" colspan="10"></th></tr>
				<tr>
				<th style="border: 0px solid white; border-bottom: 1px solid black;" colspan="5"></th>
				<th style="border: 0px solid white;" colspan="5"></th>
				</tr>
				<tr>
				<th align="center" style="border: 0px solid white; font-weight: bold;" colspan="5">FIRMA DE CONFORMIDAD</th>
				<th style="border: 0px solid white;" colspan="5"></th>
				</tr>';
$html.='</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->lastPage();

}
}
$pdf->Output('Tarjetas.pdf', 'I');
?>
