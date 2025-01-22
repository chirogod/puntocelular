<?php
	$peticion_ajax=true;
	$code=(isset($_GET['code'])) ? $_GET['code'] : 0;

	/*---------- Incluyendo configuraciones ----------*/
	require_once "../../config/app.php";
    require_once "../../autoload.php";

	/*---------- Instancia al controlador orden ----------*/
	use app\controllers\ordenController;
	$ins_orden = new ordenController();

	$datos_orden=$ins_orden->seleccionarDatos("Normal","orden INNER JOIN cliente ON orden.id_cliente=cliente.id_cliente INNER JOIN marca ON orden.id_marca=marca.id_marca INNER JOIN modelo ON orden.id_modelo=modelo.id_modelo INNER JOIN usuario ON orden.id_usuario=usuario.id_usuario INNER JOIN caja ON orden.id_caja=caja.id_caja WHERE (orden_codigo='$code')","*",0);


	if($datos_orden->rowCount()==1){

		/*---------- Datos de la orden ----------*/
		$datos_orden=$datos_orden->fetch();

		/*---------- Seleccion de datos de la empresa ----------*/
		$datos_empresa=$ins_orden->seleccionarDatos("Normal","sucursal WHERE id_sucursal = 1 LIMIT 1","*",0);
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

		// TEXTO DEL LADO IZQUIERDO DEL RECTANGULO PRINCIPAL
		$pdf->SetFont('Arial','',10);
		$pdf->Ln(9);
		$pdf->Image('../views/img/logo.png', 16, 15, 48);
        $pdf->Image('../views/img/patron2.png', $pageWidth/2, 15, 32);
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
		$pdf->Text($pageWidth / 2 + 35, 23, 'Entrada: ');
		$pdf->SetFont('Arial','',12);
		$pdf->Text($pageWidth / 2 + 52, 23,date("d/m/Y", strtotime($datos_orden['orden_fecha']))." - ". $datos_orden['orden_hora']  );

        $pdf->SetFont('Arial','B',12);
		$pdf->Text($pageWidth / 2 + 40, 30, 'Orden de reparacion');
		$pdf->SetFont('Arial','',18);
		$pdf->Text($pageWidth / 2 + 59, 38, $datos_orden['id_orden']);
        $pdf->SetFont('Arial','b',12);
		$pdf->Text($pageWidth / 2 + 38, 58, 'Estado:');
		$pdf->SetFont('Arial','',18);
		$pdf->Text($pageWidth / 2 + 58, 58, $datos_orden['orden_estado']);

		$pdf->Ln(5);

		

		// COORDENADAS PARA DSP DEL RECTANGULO PRINCIPAL
		$yNewRect = 62 + 5; 

		// RECTANGULO DATOS DEL CLIENTE
		$pdf->Rect(10, $yNewRect, 32, 5); 
		$pdf->SetFont('Arial','b',10);
		$pdf->Text(12, $yNewRect+4, "Datos del cliente");
		$pdf->Line(10, $yNewRect+5, $pageWidth-10, $yNewRect+5);
		$pdf->Ln(10);

		if($datos_orden['id_cliente']==1){
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
			$pdf->Text(28, $yNewRect +10, $datos_orden['id_cliente']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(12, $yNewRect +15, 'Nombre: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(28, $yNewRect +15, $datos_orden['cliente_nombre_completo']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(12, $yNewRect +20, 'Direccion: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(31, $yNewRect +20, $datos_orden['cliente_domicilio'].", ".$datos_orden['cliente_localidad'].", ".$datos_orden['cliente_provincia'].", ".$datos_orden['cliente_pais']);
			
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+30, $yNewRect +10, strtoupper($datos_orden['cliente_tipo_doc']).": ");
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 39, $yNewRect +10, $datos_orden['cliente_documento']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+30, $yNewRect +15, 'Telefono: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 48, $yNewRect +15, $datos_orden['cliente_telefono_1']." / ". $datos_orden['cliente_telefono_2']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+30 , $yNewRect +20, 'Email: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 42, $yNewRect +20, $datos_orden['cliente_email']);
		}

		$pdf->Ln(27);

		// RECTANGULO DETALLE DE ARTICULOS
		$pdf->Rect(10, $yNewRect +30, 16, 5); 
		$pdf->SetFont('Arial','b',10);
		$pdf->SetTextColor(0,0,0);
		$pdf->Text(12, $yNewRect+34, "Equipo");
		$pdf->Line(10, $yNewRect+35, $pageWidth-10, $yNewRect+35);
		$pdf->Ln(2);
		
		$pdf->SetX(10); // Ajusta la posición X para mover toda la tabla hacia la izquierda
		//EQUIPO
        $pdf->Cell($pageWidth/3,7,"Marca: ".$datos_orden['marca_descripcion'],'',0,'L');
		$pdf->Cell($pageWidth/3,7,"Modelo: ".$datos_orden['modelo_descripcion'],'',0,'L');
		$pdf->Cell($pageWidth/3,7,"Contrasena: ".$datos_orden['orden_equipo_contrasena'],'',0,'L');
		
        $pdf->Ln(2);

        $pdf->Text(10, $yNewRect+50, "Falla declarada:");
        $pdf->SetFont('Arial','',10);
        $pdf->Text(10, $yNewRect+54, $datos_orden['orden_falla']);

        $yNewRect = $yNewRect+50;
        $pdf->Ln(2);
        $pdf->SetFont('Arial','b',10);
        $pdf->Text(10, $yNewRect+30, "Accesorios incluidos:");
        $pdf->SetFont('Arial','',10);
        $pdf->Text(10, $yNewRect+35, $datos_orden['orden_accesorios']);

        $pdf->Ln(2);
        $pdf->SetFont('Arial','b',10);
        $pdf->Text(10, $yNewRect+50, "Detalles fiscos:");
        $pdf->SetFont('Arial','',10);
        $pdf->Text(10, $yNewRect+55, $datos_orden['orden_equipo_detalles_fisicos']);
        $pdf->Ln(75);
        $pdf->Line(10, $yNewRect+65, $pageWidth-10, $yNewRect+65);
        $pdf->MultiCell($pageWidth-40, 5, "IMPORTANTE: Transcurridos los 15 dias a partir de la REPARACION y NOTIFICACION, la empresa cobrara al propietario del dispositivo un recargo sobre el presupuesto, en concepto de interes punitorio y/o moratorio. La tasa del mismo sera de un 10% quincenal. Asimismo, cumplidos los 90 dias posteriores a la REPARACION y NOTIFICACION del dipositivo, de no ser retirado, se considerara abandonado, facultando a PUNTO CELULAR a disponer del bien. En caso de no aceptar el presupuesto, luego de ingresado el dispositivo, se debera abonar $5.000 de servicio de presupuestao SIN EXCEPCION.", 0, 'L');

        $pdf->Ln(5);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(40,7,'Costo Reparacion',0,0,'C');
        $pdf->Cell(40,7,'Anticipo / Pago',0,0,'C');
        $pdf->Cell(40,7,'Saldo',0,0,'C');
        $pdf->Cell(40,7,'Firma cliente',0,0,'C');
        $pdf->Ln();
		$pdf->SetFont('Arial','',10);
		$detalle_pagos = $ins_orden->seleccionarDatos("Normal","pago_orden WHERE orden_codigo='".$datos_orden['orden_codigo']."'","*",0);
        // Verifica si se encontraron resultados
		if ($detalle_pagos->rowCount() > 0) {
			// Si hay resultados, obtenemos el primer registro
			$detalle_pagos = $detalle_pagos->fetch();
			$importe_pago = $detalle_pagos['orden_pago_importe'];
		} else {
			// Si no hay resultados, asignamos 0
			$importe_pago = 0;
		}
		$pdf->Cell(40,7,$datos_orden['orden_total_lista']. " / ". $datos_orden['orden_total_efectivo'],1,0,'C');
        $pdf->Cell(40,7,$importe_pago,1,0,'C');
        $pdf->Cell(40,7,$datos_orden['orden_total_lista'] - $importe_pago . " / ". $datos_orden['orden_total_efectivo'] - $importe_pago ,1,0,'C');
        $pdf->Cell(60,7,'',1,1,'C');
		


		$pdf->Output("I","Orden_NRO".$datos_orden['id_orden'].".pdf",true);

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