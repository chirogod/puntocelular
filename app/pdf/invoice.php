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
		$pdf->Text($pageWidth / 2 + 40, 37,date("d/m/Y", strtotime($datos_venta['venta_fecha'])));

		$pdf->SetFont('Arial','B',12);
		$pdf->Text($pageWidth / 2 + 25, 44, 'Vendedor: ');
		$pdf->SetFont('Arial','',12);
		$pdf->Text($pageWidth / 2 + 47, 44, $datos_venta['venta_vendedor']);

		$pdf->Ln(5);

		

		// COORDENADAS PARA DSP DEL RECTANGULO PRINCIPAL
		$yNewRect = 62 + 5; 

		// RECTANGULO DATOS DEL CLIENTE
		$pdf->Rect(10, $yNewRect, 32, 5); 
		$pdf->SetFont('Arial','b',10);
		$pdf->Text(12, $yNewRect+4, "Datos del cliente");
		$pdf->Line(10, $yNewRect+5, $pageWidth-10, $yNewRect+5);
		$pdf->Ln(10);

		if($datos_venta['id_cliente']==1){
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(12, $yNewRect +10, 'Numero: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(28, $yNewRect +10, "1");
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(12, $yNewRect +15, 'Nombre: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(28, $yNewRect +15, $datos_venta['cliente_nombre_completo']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(12, $yNewRect +20, 'Direccion: ');
			
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+30, $yNewRect +10, strtoupper($datos_venta['cliente_tipo_doc']).": ");
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+30, $yNewRect +15, 'Telefono: ');
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+30 , $yNewRect +20, 'Email: ');
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
			$pdf->Text($pageWidth/2+30, $yNewRect +10, strtoupper($datos_venta['cliente_tipo_doc']).": ");
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 39, $yNewRect +10, $datos_venta['cliente_documento']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+30, $yNewRect +15, 'Telefono: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 48, $yNewRect +15, $datos_venta['cliente_telefono_1']." / ". $datos_venta['cliente_telefono_2']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+30 , $yNewRect +20, 'Email: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 42, $yNewRect +20, $datos_venta['cliente_email']);
		}

		$pdf->Ln(27);

		// RECTANGULO DETALLE DE ARTICULOS
		$pdf->Rect(10, $yNewRect +30, 36, 5); 
		$pdf->SetFont('Arial','b',10);
		$pdf->SetTextColor(0,0,0);
		$pdf->Text(12, $yNewRect+34, "Detalle de articulos");
		$pdf->Line(10, $yNewRect+35, $pageWidth-10, $yNewRect+35);
		$pdf->Ln(2);
		
		$pdf->SetX(10); // Ajusta la posición X para mover toda la tabla hacia la izquierda
		//LISTA ARTICULOS
		$pdf->SetFillColor(0,0,0);
		$pdf->SetDrawColor(255,255,255);
		$pdf->SetTextColor(255,255,255);
		$pdf->Cell(30,8,iconv("UTF-8", "ISO-8859-1",'Codigo'),1,0,'C',true);
		$pdf->Cell(63,8,iconv("UTF-8", "ISO-8859-1",'Articulo'),1,0,'C',true);
		$pdf->Cell(13,8,iconv("UTF-8", "ISO-8859-1",'Cant.'),1,0,'C',true);
		$pdf->Cell(30,8,iconv("UTF-8", "ISO-8859-1",'Garantia'),1,0,'C',true);
		$pdf->Cell(25,8,iconv("UTF-8", "ISO-8859-1",'FINANC'),1,0,'C',true);
		$pdf->Cell(35,8,iconv("UTF-8", "ISO-8859-1",'P. Final'),1,0,'C',true);

		$pdf->Ln(8);

		$pdf->SetFont('Arial','',9);
		$pdf->SetTextColor(39,39,51);

		/*----------  Seleccionando detalles de la venta  ----------*/
		$venta_detalle=$ins_venta->seleccionarDatos("Normal","venta_detalle WHERE venta_codigo='".$datos_venta['venta_codigo']."'","*",0);
		$venta_detalle=$venta_detalle->fetchAll();

		foreach($venta_detalle as $detalle){
			$datos_articulo = $ins_venta->seleccionarDatos("Normal", "articulo WHERE id_articulo = '".$detalle['id_articulo']."'","*",0);
    		$datos_articulo=$datos_articulo->fetch();

    		$pdf->SetX(10);// Asegúrate de ajustar la posición X aquí también
			$pdf->Cell(30,7,iconv("UTF-8", "ISO-8859-1",$ins_venta->limitarCadena($datos_articulo['articulo_codigo'],80,"...")),'L',0,'C');
			$pdf->Cell(63,7,iconv("UTF-8", "ISO-8859-1",$ins_venta->limitarCadena($detalle['venta_detalle_descripcion_producto'],80,"...")),'L',0,'C');
			$pdf->Cell(13,7,iconv("UTF-8", "ISO-8859-1",$detalle['venta_detalle_cantidad_producto']),'L',0,'C');
			
			$fecha_venta = strtotime($datos_venta['venta_fecha']);
			$garantia_dias = $datos_articulo['articulo_garantia'];
			$fecha_vencimiento = strtotime("+$garantia_dias days", $fecha_venta);
			$pdf->Cell(30,7,iconv("UTF-8", "ISO-8859-1",date("d-m-Y", $fecha_vencimiento)),'L',0,'C');
			
			$pdf->Cell(25,7,iconv("UTF-8", "ISO-8859-1",$detalle['venta_detalle_financiacion_producto']),'L',0,'C');
			$pdf->Cell(35,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($detalle['venta_detalle_total'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)),'LR',0,'C');
			$pdf->Ln(5);
		}
		$pdf->Ln(52);
		// RECTANGULO DETALLE DE SUS PAGOS
		$pdf->SetDrawColor(0,0,0);
		$pdf->Rect(10, $yNewRect +100, 39, 5); 
		$pdf->SetFont('Arial','b',10);
		
		$pdf->SetTextColor(0,0,0);
		$pdf->Text(12, $yNewRect+104, "Detalle de sus pagos");
		$pdf->Line(10, $yNewRect+105, $pageWidth-10, $yNewRect+105);
		
		$pdf->SetX(10); 

		$pdf->SetFillColor(0,0,0);
		$pdf->SetDrawColor(255,255,255);
		$pdf->SetTextColor(255,255,255);
		$pdf->Cell(22,8,iconv("UTF-8", "ISO-8859-1",'Fecha'),1,0,'C',true);
		$pdf->Cell(22,8,iconv("UTF-8", "ISO-8859-1",'Importe'),1,0,'C',true);
		$pdf->Cell(30,8,iconv("UTF-8", "ISO-8859-1",'Forma de pago'),1,0,'C',true);
		$pdf->Cell(28,8,iconv("UTF-8", "ISO-8859-1",'Observaciones'),1,0,'C',true);

		$pdf->Ln(8);

		$pdf->SetFont('Arial','',9);
		$pdf->SetTextColor(39,39,51);

		/*----------  Seleccionando detalles de la venta  ----------*/
		$pagos_cliente=$ins_venta->seleccionarDatos("Normal","pago_venta WHERE venta_codigo='".$datos_venta['venta_codigo']."'","*",0);
		$pagos_cliente=$pagos_cliente->fetchAll();
		$total_de_todos_los_pagos_del_cliente =0;

		foreach($pagos_cliente as $detalle){
			$pdf->SetX(10); // Asegúrate de ajustar la posición X aquí también
			$pdf->Cell(22,7,iconv("UTF-8", "ISO-8859-1",$ins_venta->limitarCadena(date("d/m/Y", strtotime($detalle['venta_pago_fecha'])),80,"...")),'L',0,'C');
			$pdf->Cell(22,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($detalle['venta_pago_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)),'LR',0,'C');
			$pdf->Cell(30,7,iconv("UTF-8", "ISO-8859-1",$detalle['venta_pago_forma']),'L',0,'C');
			$pdf->Cell(28,7,iconv("UTF-8", "ISO-8859-1",$detalle['venta_pago_detalle']),'L',0,'C');
			$pdf->Ln(7);
			$total_de_todos_los_pagos_del_cliente += $detalle['venta_pago_importe'];
		}

		// RECTANGULO DETALLE DE OBSERVACIONES
		$pdf->SetDrawColor(0,0,0);
		$pdf->Rect(10, $yNewRect +140, 60, 5); 
		$pdf->SetFont('Arial','b',10);
		$pdf->SetTextColor(0,0,0);
		$pdf->Text(12, $yNewRect+144, "Observaciones");

		$pdf->SetXY(10, $yNewRect +145); 
		$pdf->SetFont('Arial','',9);
		$pdf->SetTextColor(39,39,51);
		$pdf->MultiCell(80, 20, iconv("UTF-8", "ISO-8859-1",$datos_venta['venta_observaciones']), 1, 'A');	

		// RECTANGULO TOTAL DE PAGOS Y SALDO A FAVOR
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetFont('Arial','b',10);
		$pdf->SetTextColor(0,0,0);
		$pdf->Text($pageWidth/2 +10, $yNewRect+150, "TOTAL");
		$pdf->Text($pageWidth/2 +10, $yNewRect+155, "SUS PAGOS");
		$pdf->Text($pageWidth/2 +10, $yNewRect+163, "SALDO");

		$pdf->Text($pageWidth -30, $yNewRect+150, iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($datos_venta['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)));
		$pdf->Text($pageWidth -30, $yNewRect+155, iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($total_de_todos_los_pagos_del_cliente,MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)));
		$saldo = $datos_venta['venta_importe'] - $total_de_todos_los_pagos_del_cliente;
		$pdf->Text($pageWidth -30, $yNewRect+163, iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($saldo,MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)));
		$pdf->SetLineWidth(0.1);
		$pdf->Line($pageWidth -100, 232, $pageWidth -10, 232);
		$pdf->SetXY(10, $yNewRect+174);
		$pdf->SetFont('Arial','B',8);
		$pdf->MultiCell($pageWidth-10, 5, "Recuerde guardar la caja completa del equipo por si necesita asistencia de garantia (RMA). ATENCION!: El producto perdera su 'Garantia' si presenta golpes, humedad, rotura de faja de seguridad o signos de haber sido manipulado por terceros.", 0, 'L');
		
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