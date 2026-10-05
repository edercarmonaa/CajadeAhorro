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
$ejercicio = new ejercicio($db);

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(15, 12, 15);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetFont('Helvetica', '', 9);
$pdf->AddPage();

$nom_ejercicio = $ejercicio->EjercicioActivo();
$html = '<table border="1" cellspacing="1" cellpadding="1" style="font-size: small; border: 1px solid black;">';
$html.= '<tr align="center" style="font-size: small; font-weight: bold;">
				<th colspan="3"> SINDICATO ÚNICO DE TRABAJADORES DE LA INDUSTRIA LÁCTEA ALIMENTICIA,<br>
													SIMILARES Y CONEXOS DE LA REPÚBLICA MEXICANA    SECCIÓN COATEPEC <br>
													Tarjetas de Caja de Ahorro y Préstamos<br>
													Ejercicio '.$nom_ejercicio.'
				</th>
				</tr>
				<tr >
				<th bgcolor="#00CCFF" align="center" width="10%">'. $nom_ejercicio.'</th>
				<th rowspan="2" align="center" width="75%">NOMBRE DEL TRABAJADOR</th>
				<th bgcolor="#CCFFFF" rowspan="2" align="center" width="15%">VALOR DE ACCION</th>
				</tr>
				<tr>
				<th bgcolor="#CCCCFF" align="center" >NUM</th>
				</tr>';
$stmt_principal = $empleado->leeEmpleados();
$num = $stmt_principal->rowCount();
$total_saldo=0;
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
$ahorro->id_empleado =$id_empleado;

$stmt = $ahorro->leeAhorrosEmpleado();
$num = $stmt->rowCount();
$data_ahorro=0;
if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_ahorro+=$monto;
        }
}

//OBTIENE DATOS DE LOS ABONOS
$abono->id_empleado = $id_empleado;
$stmt = $abono->leeAbonosEmpleado();
$num = $stmt->rowCount();
$data_abono="";
if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $data_ahorro+=$monto;
    }
}

//OBTIENE DATOS DE LOS PRESTAMOS
$prestamo->id_empleado = $id_empleado;
$stmt = $prestamo->leePrestamosEmpleado();
$num = $stmt->rowCount();
$data_prestamo=0;
$data_recibos=0;
$data_interes=0;
if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
				$data_prestamo+=$monto;
				$data_recibos+=$recibos;
				$data_interes+=$interes;
    }
}



//ACTUALIZA DATOS DEL ENCABEZADO
$saldo_final=$data_ahorro+$data_abono-$data_prestamo-$data_recibos-$data_interes;
if($saldo_final > 0){
$html.='<tr >
<th align="center" width="10%">'.$empleado_arr['id_empleado'].'</th>
<th align="left" width="75%">'.$empleado_arr['nombre'].'</th>
<th align="right" width="15%">'.$saldo_final.'</th>
</tr>';
$total_saldo+=$saldo_final;
}

}
}
$html.='<tr >
<th colspan="2" rowspan="2" align="center">TOTALES</th>
<th align="right" width="15%" bgcolor="#FFFF99">'.$total_saldo.'</th>
</tr>
<tr >
<th align="right" width="15%" bgcolor="#FFFF00">Saldo A Favor</th>
</tr>';
$html.='</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->lastPage();
$pdf->Output('SaldoFavor.pdf', 'D');
?>
