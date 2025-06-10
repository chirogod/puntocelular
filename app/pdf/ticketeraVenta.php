<?php
$peticion_ajax=true;
$code=(isset($_GET['code'])) ? $_GET['code'] : 0;

/*---------- Incluyendo configuraciones ----------*/
require_once "../../config/app.php";
require_once "../../autoload.php";

/*---------- Instancia al controlador venta ----------*/
use app\controllers\saleController;
$ins_venta = new saleController();

$datos_venta=$ins_venta->seleccionarDatos("Normal","venta INNER JOIN cliente ON venta.id_cliente=cliente.id_cliente INNER JOIN usuario ON venta.id_usuario=usuario.id_usuario INNER JOIN caja ON venta.id_caja=caja.id_caja WHERE (venta_codigo='$code')","*",0);


if($datos_venta->rowCount()==1){

	/*---------- Datos de la venta ----------*/
	$datos_venta=$datos_venta->fetch();

	/*---------- Seleccion de datos de la empresa ----------*/
	$datos_empresa=$ins_venta->seleccionarDatos("Normal","sucursal WHERE id_sucursal = 1 LIMIT 1","*",0);
	$datos_empresa=$datos_empresa->fetch();

    require "./code128.php";

    // PDF para ticketera (ancho menor)
    $pdf = new PDF_Code128('P','mm',array(80,150)); // tamaño personalizado (80mm ancho)
    $pdf->SetMargins(5,5,5);
    $pdf->AddPage();

    // Logo arriba centrado
    $pdf->Image('../views/img/logo.png', 20, 5, 40);
    $pdf->Ln(25);

    // Datos Cliente: Nombre y DNI
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,6, "Cliente:", 0, 1);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(0,6, $datos_venta['cliente_nombre_completo'], 0, 1);
    
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,6, "DNI:", 0, 1);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(0,6, $datos_venta['cliente_documento'], 0, 1);

		$fecha_venta = strtotime($datos_venta['venta_fecha']);
    $pdf->Ln(5);

    // Tabla artículos: Cant x Producto x Precio Unit x Subtotal
    $pdf->SetFont('Arial','B',6);
    $pdf->Cell(10,6, 'Cant',1,0,'C');
    $pdf->Cell(30,6, 'Producto',1,0,'C');
    $pdf->Cell(15,6, 'Precio',1,0,'C');
    $pdf->Cell(15,6, 'Subtotal',1,1,'C');

			$pdf->SetFont('Arial','',4);
		$pdf->SetTextColor(39,39,51);
	/*----------  Seleccionando detalles de la venta  ----------*/
	$venta_detalle=$ins_venta->seleccionarDatos("Normal","venta_detalle WHERE venta_codigo='".$datos_venta['venta_codigo']."'","*",0);
	$venta_detalle=$venta_detalle->fetchAll();

	foreach($venta_detalle as $detalle){
		$datos_articulo = $ins_venta->seleccionarDatos("Normal", "articulo WHERE id_articulo = '".$detalle['id_articulo']."'","*",0);
		$datos_articulo=$datos_articulo->fetch();

		$pdf->Cell(10,7,iconv("UTF-8", "ISO-8859-1",$detalle['venta_detalle_cantidad_producto']),'L',0,'C');
		$pdf->Cell(30,7,iconv("UTF-8", "ISO-8859-1",$ins_venta->limitarCadena($detalle['venta_detalle_descripcion_producto'],80,"...")),'L',0,'C');

		$pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($detalle['venta_detalle_total'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)),'LR',0,'C');

		

		$pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($detalle['venta_detalle_total'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)),'LR',0,'C');
		
		
		$pdf->Ln(5);
	}
    
    $pdf->Output();

}else{
    echo "Código de venta inválido.";
}
?>
