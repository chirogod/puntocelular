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

		$pdf = new PDF_Code128('P','mm','Letter');
		$pdf->SetMargins(17,17,17);
		$pdf->AddPage();
		$pdf->Ln(9);
		
		// ANCHO DE LA PAGINA
		$pageWidth = $pdf->GetPageWidth();

		// RECTANGULO PRINCIPAL
		$pdf->Rect(10, 10, $pageWidth - 20, 52);

		//CUADRADO DE COMPROBANTE NO VALIDO COMO FACTURA
		$pdf->Rect($pageWidth / 2 - 5 , 10, 10, 10);

		// LINEAS QUE FORMAN LA X DE DOCUMENTO NO VALIDO COMO FACTURA
		$pdf->SetLineWidth(0.8);
		$centerX = $pageWidth / 2 ;
		$centerY = 15;
		$pdf->Line($centerX - 3, $centerY - 3, $centerX + 3, $centerY + 3);
		$pdf->Line($centerX - 3, $centerY + 3, $centerX + 3, $centerY - 3);
		
		//TEXTO DOCUMENTO NO VALIDO COMO FACTURA
		$pdf->SetFont('Arial','',9);
		$pdf->Text($centerX-10, $centerY+8, 'Documento no');
		$pdf->Text($centerX-14, $centerY+11, 'valido como factura');

		// LINEA QUE DIVIDE EL RECTANGULO
		$pdf->SetLineWidth(0.1);
		$pdf->Line($pageWidth / 2, 28, $pageWidth / 2, 62);


		// TEXTO DEL LADO IZQUIERDO DEL RECTANGULO PRINCIPAL
		$pdf->SetFont('Arial','',10);
		$pdf->Ln(9);
		$pdf->Image('../views/img/logo.png', 16, 15, 48);

		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(150,9,"Reparaciones - Ventas - Accesorios" ,0,0,'L');
		$pdf->Ln(5);

		$pdf->SetFont('Arial','',10);
		$pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1",$datos_empresa['sucursal_direccion']) . " - " . $datos_empresa['sucursal_localidad'],0,0,'L');
		$pdf->Ln(5);

		$pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","Teléfono: ".$datos_empresa['sucursal_telefono']),0,0,'L');

		$pdf->Ln(5);

		$pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","Email: ".$datos_empresa['sucursal_email']),0,0,'L');

		$pdf->Ln(10);

		// TEXTO DEL LADO DERECHO DEL RECTANGULO PRINCIPAL
		$pdf->SetFont('Arial','B',12);
		$pdf->Text($pageWidth / 2 + 25, 30, 'Nro. de venta: ');
		$pdf->SetFont('Arial','',12);
		$pdf->Text($pageWidth / 2 + 55, 30, $datos_venta['id_venta']);

		$pdf->SetFont('Arial','B',12);
		$pdf->Text($pageWidth / 2 + 25, 37, 'Fecha: ');
		$pdf->SetFont('Arial','',12);
		$pdf->Text($pageWidth / 2 + 40, 37, $datos_venta['venta_fecha']);

		$pdf->SetFont('Arial','B',12);
		$pdf->Text($pageWidth / 2 + 25, 44, 'Vendedor: ');
		$pdf->SetFont('Arial','',12);
		$pdf->Text($pageWidth / 2 + 47, 44, $datos_venta['usuario_nombre_completo']);

		$pdf->Ln(5);

		// RECTANGULO DATOS DEL CLIENTE
		// COORDENADAS PARA DSP DEL RECTANGULO PRINCIPAL
		$yNewRect = 62 + 5; 

		$pdf->Rect(10, $yNewRect, 35, 5); 
		$pdf->SetFont('Arial','b',10);
		$pdf->Text(12, $yNewRect+4, "Datos del cliente");
		$pdf->Line(10, $yNewRect+5, 100, $yNewRect+5);
		$pdf->Ln(10);

		if($datos_venta['id_cliente']==1){
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(13,7,iconv("UTF-8", "ISO-8859-1",'Cliente:'),0,0);
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1","Consumidor Final"),0,0,'L');
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(8,7,iconv("UTF-8", "ISO-8859-1","Doc: "),0,0,'L');
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1","N/A"),0,0,'L');
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(7,7,iconv("UTF-8", "ISO-8859-1",'Tel:'),0,0,'L');
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(35,7,iconv("UTF-8", "ISO-8859-1","N/A"),0,0);
			$pdf->SetTextColor(39,39,51);

			$pdf->Ln(7);

			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(6,7,iconv("UTF-8", "ISO-8859-1",'Dir:'),0,0);
			$pdf->SetTextColor(97,97,97);
			$pdf->Cell(109,7,iconv("UTF-8", "ISO-8859-1","N/A"),0,0);
		}else{
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(12, $yNewRect +10, 'Numero: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(28, $yNewRect +10, $datos_venta['id_cliente']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(12, $yNewRect +15, 'Nombre: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(28, $yNewRect +15, $datos_venta['cliente_nombre_completo']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(12, $yNewRect +20, 'Direccion: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(31, $yNewRect +20, $datos_venta['cliente_domicilio'].", ".$datos_venta['cliente_localidad'].", ".$datos_venta['cliente_provincia'].", ".$datos_venta['cliente_pais']);
			
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2, $yNewRect +10, strtoupper($datos_venta['cliente_tipo_doc']).": ");
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 9, $yNewRect +10, $datos_venta['cliente_documento']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2, $yNewRect +15, 'Telefono: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 18, $yNewRect +15, $datos_venta['cliente_telefono_1']." / ". $datos_venta['cliente_telefono_2']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2 , $yNewRect +20, 'Nacimiento: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 22, $yNewRect +20, $datos_venta['cliente_nacimiento']);
		}

		$pdf->Ln(20);

		$pdf->SetFillColor(23,83,201);
		$pdf->SetDrawColor(23,83,201);
		$pdf->SetTextColor(255,255,255);
		$pdf->Cell(30,8,iconv("UTF-8", "ISO-8859-1",'Codigo'),1,0,'C',true);
		$pdf->Cell(50,8,iconv("UTF-8", "ISO-8859-1",'Articulo'),1,0,'C',true);
		$pdf->Cell(15,8,iconv("UTF-8", "ISO-8859-1",'Cant.'),1,0,'C',true);
		$pdf->Cell(15,8,iconv("UTF-8", "ISO-8859-1",'Garantia'),1,0,'C',true);
		$pdf->Cell(32,8,iconv("UTF-8", "ISO-8859-1",'Precio'),1,0,'C',true);
		$pdf->Cell(34,8,iconv("UTF-8", "ISO-8859-1",'Subtotal'),1,0,'C',true);

		$pdf->Ln(8);

		$pdf->SetFont('Arial','',9);
		$pdf->SetTextColor(39,39,51);

		/*----------  Seleccionando detalles de la venta  ----------*/
		$venta_detalle=$ins_venta->seleccionarDatos("Normal","venta_detalle WHERE venta_codigo='".$datos_venta['venta_codigo']."'","*",0);
		$venta_detalle=$venta_detalle->fetchAll();

		foreach($venta_detalle as $detalle){
			$pdf->Cell(30,7,iconv("UTF-8", "ISO-8859-1",$ins_venta->limitarCadena($detalle['venta_codigo'],80,"...")),'L',0,'C');
			$pdf->Cell(50,7,iconv("UTF-8", "ISO-8859-1",$ins_venta->limitarCadena($detalle['venta_detalle_descripcion_producto'],80,"...")),'L',0,'C');
			$pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",$detalle['venta_detalle_cantidad_producto']),'L',0,'C');
			$pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($detalle['venta_detalle_precio_venta_producto'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)),'L',0,'C');
			$pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($detalle['venta_detalle_total'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)),'LR',0,'C');
			$pdf->Ln(7);
		}

		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'T',0,'C');
			$pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'T',0,'C');

		$pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1",'TOTAL A PAGAR'),'T',0,'C');
		$pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($datos_venta['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE),'T',0,'C');

		$pdf->Ln(12);

		$pdf->SetFont('Arial','',9);

		$pdf->Ln(9);



		$pdf->Output("I","Factura_Nro".$datos_venta['id_venta'].".pdf",true);

	}else{
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?php echo APP_NAME; ?></title>
	<?php include '../views/inc/head.php'; ?>
</head>
<body>
	<div class="main-container">
        <section class="hero-body">
            <div class="hero-body">
                <p class="has-text-centered has-text-white pb-3">
                    <i class="fas fa-rocket fa-5x"></i>
                </p>
                <p class="title has-text-white">¡Ocurrió un error!</p>
                <p class="subtitle has-text-white">No hemos encontrado datos de la venta</p>
            </div>
        </section>
    </div>
	<?php include '../views/inc/script.php'; ?>
</body>
</html>
<?php } ?>