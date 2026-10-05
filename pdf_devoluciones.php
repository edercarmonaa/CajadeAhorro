<?php
ini_set('memory_limit', '-1');
require_once('libs/tcpdf/tcpdf.php');
include_once 'config/database.php';
include_once 'objects/empleado.php';
include_once 'objects/ejercicio.php';
include_once 'objects/devolucion.php';

$database = new Database();
$db = $database->Coneccion();
$devolucion = new Devolucion($db);
$ejercicio = new ejercicio($db);
$stmt = $devolucion->leeDevoluciones();
$num = $stmt->rowCount();
$total_saldo=0;
$total_ahorros=0;
$total_accion=0;
$data="";



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
				<th colspan="5"> SINDICATO ÚNICO DE TRABAJADORES DE LA INDUSTRIA LÁCTEA ALIMENTICIA,<br>
													SIMILARES Y CONEXOS DE LA REPÚBLICA MEXICANA    SECCIÓN COATEPEC <br>
													Tarjetas de Caja de Ahorro y Préstamos<br>
													Ejercicio '.$nom_ejercicio.'
				</th>
				</tr>
				<tr >
				<th bgcolor="#00CCFF" align="center" width="10%">'. $nom_ejercicio.'</th>
				<th rowspan="2" align="center" width="45%">NOMBRE DEL TRABAJADOR</th>
				<th bgcolor="#CCFFFF" rowspan="2" align="center" width="15%">Devolucion</th>
				<th bgcolor="#CCFFFF" rowspan="2" align="center" width="15%">Devolucion ACCION</th>
				<th bgcolor="#CCFFFF" rowspan="2" align="center" width="15%">Devolucion Ahorro</th>
				</tr>
				<tr>
				<th bgcolor="#CCCCFF" align="center" >NUM</th>
				</tr>';
$total_saldo=0;
$total_ahorros=0;
$total_accion=0;

if($num>0){
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	  extract($row);
$html.='<tr >
<th align="center" width="10%">'.$id_empleado.'</th>
<th align="left" width="45%">'.$nombre.'</th>
<th align="right" width="15%">'.$saldo.'</th>
<th align="right" width="15%">'.$valor_accion.'</th>
<th align="right" width="15%">'.$ahorros.'</th>
</tr>';
$total_saldo+=$saldo;
$total_accion+=$valor_accion;
$total_ahorros+=$ahorros;
}
}
$html.='<tr >
<th colspan="2" align="center">TOTALES</th>
<th align="right" width="15%" bgcolor="#FFFF99">'.$total_saldo.'</th>
<th align="right" width="15%" bgcolor="#FFFF99">'.$total_accion.'</th>
<th align="right" width="15%" bgcolor="#FFFF99">'.$total_ahorros.'</th>
</tr>';
$html.='</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->lastPage();
$pdf->Output('Devoluciones.pdf', 'D');
?>
