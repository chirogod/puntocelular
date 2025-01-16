<?php
	$peticion_ajax=true;
	$code=(isset($_GET['code'])) ? $_GET['code'] : 0;

	/*---------- Incluyendo configuraciones ----------*/
	require_once "../../config/app.php";
    require_once "../../autoload.php";

	/*---------- Instancia al controlador sena ----------*/
	use app\controllers\senaController;
	$ins_sena = new senaController();

	$datos_sena=$ins_sena->seleccionarDatos("Normal","sena INNER JOIN equipo ON sena.id_equipo=equipo.id_equipo INNER JOIN cliente ON sena.id_cliente=cliente.id_cliente INNER JOIN caja ON sena.id_caja=caja.id_caja WHERE (id_sena='$code')","*",0);

    
	if($datos_sena->rowCount()==1){

		/*---------- Datos de la sena ----------*/
		$datos_sena=$datos_sena->fetch();

        $efectivo_usd = $datos_sena['equipo_costo'] * 1.4;
        $efectivo_ars = ($efectivo_usd * USD_PC);   
        $precio = $efectivo_ars * 1.4;

        $sin_int_3 = $precio / 3;
        $sin_int_6 = $precio / 6; 
        $fijas_9 = ($efectivo_ars * 1.5) / 9;
        $fijas_12 = ($efectivo_ars * 1.6) / 12;
        $pago1 = $efectivo_ars * 1.1;

        $sena = 0;
        $sena += $datos_sena['sena_ars'] / USD_PC;
        $sena += $datos_sena['sena_pcp'] / USD_PC;
        $sena_usd_ars = $datos_sena['sena_usd'];
        $sena_pcu_ars = $datos_sena['sena_pcu'];
        $sena += $sena_usd_ars;
        $sena += $sena_pcu_ars;

        $restante_usd = $efectivo_usd - $sena;
        $restante_efectivo_ars = ($restante_usd * USD_PC);   
        $restante_precio = $restante_efectivo_ars * 1.4;

        $restante_sin_int_3 = $restante_precio / 3;
        $restante_sin_int_6 = $restante_precio / 6; 
        $restante_fijas_9 = ($restante_efectivo_ars * 1.5) / 9;
        $restante_fijas_12 = ($restante_efectivo_ars * 1.6) / 12;
        $restante_pago1 = $restante_efectivo_ars * 1.1;



		/*---------- Seleccion de datos de la empresa ----------*/
		$datos_empresa=$ins_sena->seleccionarDatos("Normal","sucursal WHERE id_sucursal = '$datos_sena[id_sucursal]' LIMIT 1","*",0);
		$datos_empresa=$datos_empresa->fetch();


		require "./code128.php";

		$pdf = new PDF_Code128('P','mm','Letter');
		$pdf->SetMargins(17,1,17);
		$pdf->AddPage();
		$pdf->Ln(9);
		
		// ANCHO DE LA PAGINA
		$pageWidth = $pdf->GetPageWidth();

        // Dimensiones del rectángulo principal
		$rectX = 10;
		$rectY = 10;
		$rectWidth = $pageWidth - 20;
		$rectHeight = 250;
        
		$pdf->Rect($rectX, $rectY, $rectWidth, $rectHeight);

        // Dimensiones del rectángulo del titulo
		$rectX = 15;
		$rectY = 15;
		$rectWidth = $pageWidth - 30;
		$rectHeight = 10;
        $pdf->SetFillColor(0, 0, 0);
		$pdf->Rect($rectX, $rectY, $rectWidth, $rectHeight, 'F');
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 12);
        // Agregamos el texto dentro del rectángulo
		$pdf->SetXY($rectWidth/3, $rectY + 0.5); // Ajustamos la posición del texto dentro del rectángulo
		$pdf->MultiCell($rectWidth - 10, 10, "PUNTO CELULAR   -  COMPROBANTE DE SENA" ); // Usamos MultiCell para texto que se ajusta a varias líneas

        // Dimensiones del rectángulo del detalle
        $rectX = 15;
		$rectY = 30;
		$rectWidth = $pageWidth - 30;
		$rectHeight = 30;
		$pdf->Rect($rectX, $rectY, $rectWidth, $rectHeight,);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        // TEXTO DEL LADO IZQUIERDO DEL RECTANGULO PRINCIPAL

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(100,15,"RAZON SOCIAL: " ,0,0,'L');
        $pdf->Cell(50,25,"FECHA: " ,0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(50,34,"Nahuel Antonio Pirroncello" ,0,0,'L');
        $pdf->Text(140,39,$datos_sena['sena_fecha'] ,0,0,'L');
        $pdf->Ln(5);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(100,15,"CUIT: " ,0,0,'L');
        $pdf->Cell(50,30,"N DE SENA: " ,0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(50,39,"20-33904263-3" ,0,0,'L');
        $pdf->Text(149,47,$datos_sena['id_sena'] ,0,0,'L');
        $pdf->Ln(5);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(100,15,"TELEFONO: " ,0,0,'L');
        $pdf->Cell(50,35,"VENDEDOR: " ,0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(50,44,"54 2323 348545" ,0,0,'L');
        $pdf->Text(145,54,$datos_sena['sena_vendedor'] ,0,0,'L');
        $pdf->Image('../views/img/logo_pc.png', 170, 32, 23);
        $pdf->Ln(5);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(150,15,"DIRECCION: " ,0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(50,49,"Av. Lorenzo Casey 920" ,0,0,'L');
        $pdf->Ln(5);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(150,15,"COD. POSTAL: " ,0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(50,54,"6700" ,0,0,'L');
        $pdf->Ln(5);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(150,15,"LOCALIDAD: " ,0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(50,59,"Lujan" ,0,0,'L');
        $pdf->Ln(5);

        $yNewRect = 62 + 5; 

        // RECTANGULO DATOS DEL CLIENTE
		$pdf->Rect(15, $yNewRect, 32, 5); 
		$pdf->SetFont('Arial','b',10);
		$pdf->Text(18, $yNewRect+4, "Datos del cliente");
		$pdf->Line(15, $yNewRect+5, $pageWidth-30, $yNewRect+5);
		$pdf->Ln(10);

		if($datos_sena['id_cliente']==1){
            $pdf->SetFont('Arial','B',10);
			$pdf->Text(18, $yNewRect +10, 'Numero: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(34, $yNewRect +10, $datos_sena['id_cliente']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(18, $yNewRect +15, 'Nombre: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(34, $yNewRect +15, $datos_sena['cliente_nombre_completo']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(18, $yNewRect +20, 'Direccion: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(36, $yNewRect +20, "N/A");
			
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+20, $yNewRect +10, strtoupper($datos_sena['cliente_tipo_doc']).": ");
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 28, $yNewRect +10, "N/A");
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+20, $yNewRect +15, 'Telefono: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 37, $yNewRect +15, "N/A");
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+20 , $yNewRect +20, 'Email: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 32, $yNewRect +20, "N/A");
		}else{
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(18, $yNewRect +10, 'Numero: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(34, $yNewRect +10, $datos_sena['id_cliente']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(18, $yNewRect +15, 'Nombre: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(34, $yNewRect +15, $datos_sena['cliente_nombre_completo']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text(18, $yNewRect +20, 'Direccion: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text(36, $yNewRect +20, $datos_sena['cliente_domicilio'].", ".$datos_sena['cliente_localidad'].", ".$datos_sena['cliente_provincia'].", ".$datos_sena['cliente_pais']);
			
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+20, $yNewRect +10, strtoupper($datos_sena['cliente_tipo_doc']).": ");
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 28, $yNewRect +10, $datos_sena['cliente_documento']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+20, $yNewRect +15, 'Telefono: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 37, $yNewRect +15, $datos_sena['cliente_telefono_1']." / ". $datos_sena['cliente_telefono_2']);
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Text($pageWidth/2+20 , $yNewRect +20, 'Email: ');
			$pdf->SetFont('Arial','',10);
			$pdf->Text($pageWidth/2 + 32, $yNewRect +20, $datos_sena['cliente_email']);
		}
        $pdf->Ln(18);

        $yNewRect = 90 + 5; 

        // RECTANGULO DATOS DEL equipo
		$pdf->Rect(15, $yNewRect, 17, 5); 
		$pdf->SetFont('Arial','b',10);
		$pdf->Text(18, $yNewRect+4, "Equipo");
		$pdf->Line(15, $yNewRect+5, $pageWidth-30, $yNewRect+5);
		$pdf->Ln(10);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(17,$yNewRect+10,$datos_sena['equipo_marca']." ".$datos_sena['equipo_modelo']. " (".$datos_sena['equipo_almacenamiento'].")" . " (".$datos_sena['equipo_color'].")",0,0,'L');

        $pdf->Ln(20);
        
        // Posición inicial
        $x_start = 15; // Margen izquierdo
        $y_start = $pdf->GetY(); // Coordenada vertical actual

        // TABLA 1: Información de pagos
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY($x_start, $y_start); 
        $pdf->Cell(90, 6, 'VALOR DEL EQUIPO', 1, 0, 'C');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start);
        $pdf->Cell(60, 5, 'PRECIO DE LISTA', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($precio, 2) , 1, 1, 'R');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start);
        $pdf->Cell(60, 5, '3 CUOTAS S/I DE:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($sin_int_3, 2) , 1, 1, 'R');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start); 
        $pdf->Cell(60, 5, '6 CUOTAS S/I DE:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($sin_int_6, 2) , 1, 1, 'R');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start); 
        $pdf->Cell(60, 5, '9 CUOTAS FIJAS DE:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($fijas_9, 2) , 1, 1, 'R');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start);
        $pdf->Cell(60, 5, '12 CUOTAS FIJAS DE:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($fijas_12, 2) , 1, 1, 'R');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start); 
        $pdf->Cell(60, 5, '1 PAGO CRED/DEB/TRANSF:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($pago1, 2) , 1, 1, 'R');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start); 
        $pdf->Cell(60, 5, 'PRECIO EN EFECTIVO:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($efectivo_ars, 2) , 1, 1, 'R');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start); 
        $pdf->Cell(60, 5, 'PRECIO EN EFECTIVO USD', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($efectivo_usd, 2) , 1, 1, 'R');

        // TABLA 2: Detalles adicionales
        $x_offset = $x_start + 95; // Separación entre tablas
        $pdf->SetXY($x_offset, $y_start); // Posición inicial para la segunda tabla

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(90, 6, 'RESTANTE A ABONAR', 1, 0, 'C');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_offset);
        $pdf->Cell(60, 5, 'PRECIO DE LISTA:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($restante_precio, 2), 1, 1, 'R');

        $pdf->SetX($x_offset);
        $pdf->Cell(60, 5, '3 CUOTAS S/I DE:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($restante_sin_int_3, 2) , 1, 1, 'R');

        $pdf->SetX($x_offset);
        $pdf->Cell(60, 5, '6 CUOTAS S/I DE:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($restante_sin_int_6, 2) , 1, 1, 'R');

        $pdf->SetX($x_offset);
        $pdf->Cell(60, 5, '9 CUOTAS FIJAS DE:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($restante_fijas_9, 2) , 1, 1, 'R');

        $pdf->SetX($x_offset);
        $pdf->Cell(60, 5, '12 CUOTAS FIJAS DE:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($restante_fijas_12, 2) , 1, 1, 'R');

        $pdf->SetX($x_offset);
        $pdf->Cell(60, 5, '1 PAGO CRED/DEB/TRANSF:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($restante_pago1, 2) , 1, 1, 'R');

        $pdf->SetX($x_offset);
        $pdf->Cell(60, 5, 'PRECIO EN EFECTIVO:', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($restante_efectivo_ars, 2) , 1, 1, 'R');

        $pdf->SetX($x_offset);
        $pdf->Cell(60, 5, 'PRECIO EN EFECTIVO USD', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($restante_usd, 2) , 1, 1, 'R');


        $x_start = 15; // Margen izquierdo
        $y_start = $pdf->GetY(); // Coordenada vertical actual
        // TABLA 1: Información de pagos
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY($x_start, $y_start + 10); 
        $pdf->Cell(90, 6, 'SENA', 1, 0, 'C');
        $pdf->Ln();

        

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start);
        $pdf->Cell(60, 5, 'SENA PESOS', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($datos_sena['sena_ars'], 2) , 1, 1, 'R');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start);
        $pdf->Cell(60, 5, 'SENA USD', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($datos_sena['sena_usd'], 2) , 1, 1, 'R');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start);
        $pdf->Cell(60, 5, 'PLAN CANJE PESOS', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($datos_sena['sena_pcp'], 2) , 1, 1, 'R');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start);
        $pdf->Cell(60, 5, 'PLAN CANJE USD', 1, 0, 'L');
        $pdf->Cell(30, 5, "$".number_format($datos_sena['sena_pcu'], 2) , 1, 1, 'R');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX($x_start);
        $pdf->Cell(60, 5, 'FECHA ENTREGA', 1, 0, 'L');
        $pdf->Cell(30, 5, "fecha" , 1, 1, 'R');

        //textos
        $pdf->Text(106,179,"*Valor en PESOS de la sena abonada",0,0,'L');
        $pdf->Text(106,184,"*Valor en DOLARES de la sena abonada",0,0,'L');
        $pdf->Text(106,189,"*Valor en PESOS del equipo tomado en Plan Canje",0,0,'L');
        $pdf->Text(106,194,"*Valor en DOLARES del equipo tomado en Plan Canje",0,0,'L');

        //firmas
        $pdf->Line(40, 220, 90, 220);
        $pdf->Text(53,225,"FIRMA CLIENTE",0,0,'L');

        $pdf->Line(40, 250, 90, 250);
        $pdf->Text(128,225,"ACLARACION CLIENTE",0,0,'L');

        $pdf->Line(120, 220, 170, 220);
        $pdf->Text(50,255,"FIRMA VENDEDOR",0,0,'L');

        $pdf->Line(120, 250, 170, 250);
        $pdf->Text(125,255,"ACLARACION VENDEDOR",0,0,'L');

		
        $pdf->Output();
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