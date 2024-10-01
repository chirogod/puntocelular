<?php
	
    $fecha_inicio=(isset($_GET['fi'])) ? $_GET['fi'] : "";
    $fecha_final=(isset($_GET['ff'])) ? $_GET['ff'] : "";
    $texto_error="";

	/*---------- Incluyendo configuraciones ----------*/
	require_once "../../config/app.php";
    require_once "../../autoload.php";

    /*---------- Instancia al controlador venta ----------*/
	use app\controllers\saleController;
	$ins_venta = new saleController();

    $fecha_inicio=$ins_venta->limpiarCadena($fecha_inicio);
    $fecha_final=$ins_venta->limpiarCadena($fecha_final);
    
    if($ins_venta->verificarFecha($fecha_inicio) || $ins_venta->verificarFecha($fecha_final)){
        $texto_error.="Ha introducido fechas que no son correctas. ";
    }

    if($fecha_inicio>$fecha_final){
        $texto_error.="La fecha de inicio no puede ser mayor que la fecha final. ";
    }


	if($texto_error==""){

		/*---------- Seleccion de datos de la empresa ----------*/
		$datos_empresa=$ins_venta->seleccionarDatos("Normal","empresa LIMIT 1","*",0);
		$datos_empresa=$datos_empresa->fetch();


		require "./code128.php";

		$pdf = new PDF_Code128('P','mm','Letter');
		$pdf->SetMargins(17,17,17);
		$pdf->AddPage();
		if(is_file("../views/img/".$datos_empresa['empresa_foto'])){
			$pdf->Image(APP_URL.'app/views/img/'.$datos_empresa['empresa_foto'],165,12,35,35,'PNG');
		}else{
			$pdf->Image(APP_URL.'app/views/img/logo.png',165,12,35,35,'PNG');
		}

		$pdf->SetFont('Arial','B',16);
		$pdf->SetTextColor(32,100,210);
		$pdf->Cell(150,10,iconv("UTF-8", "ISO-8859-1",strtoupper($datos_empresa['empresa_nombre'])),0,0,'L');

		$pdf->Ln(9);

		$pdf->SetFont('Arial','',10);
		$pdf->SetTextColor(39,39,51);
		$pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1",""),0,0,'L');

		$pdf->Ln(5);

		$pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1",$datos_empresa['empresa_direccion']),0,0,'L');

		$pdf->Ln(5);

		$pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","Teléfono: ".$datos_empresa['empresa_telefono']),0,0,'L');

		$pdf->Ln(5);

		$pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","Email: ".$datos_empresa['empresa_email']),0,0,'L');

        $pdf->Ln(15);
        
        $pdf->MultiCell(0,9,iconv("UTF-8", "ISO-8859-1",strtoupper("Reporte general de ventas de ".date("d-m-Y",strtotime($fecha_inicio))." a ".date("d-m-Y",strtotime($fecha_final))."  Hora:".date("h:i a"))),0,'C',false);

		$pdf->SetFillColor(23,83,201);
		$pdf->SetDrawColor(23,83,201);
		$pdf->SetTextColor(255,255,255);
		$pdf->Cell(91,8,iconv("UTF-8", "ISO-8859-1",'Ventas realizadas'),1,0,'C',true);
		$pdf->Cell(90,8,iconv("UTF-8", "ISO-8859-1",'Total en ventas'),1,0,'C',true);

		$pdf->Ln(8);

		$pdf->SetFont('Arial','',9);
		$pdf->SetTextColor(39,39,51);

		/*----------  Seleccionando datos de las ventas  ----------*/
        $check_ventas=$ins_venta->seleccionarDatos("Normal","venta WHERE venta_fecha BETWEEN '$fecha_inicio' AND '$fecha_final'","*",0);
        if($check_ventas->rowCount()>=1){
            $datos_ventas=$check_ventas->fetchAll();

            $ventas_totales=0;
            $total_ventas=0;
            foreach($datos_ventas as $ventas){
                $ventas_totales++;
                $total_ventas+=$ventas['venta_total'];
            }

            $pdf->Cell(91,7,iconv("UTF-8", "ISO-8859-1",$ventas_totales),'LB',0,'C');
            $pdf->Cell(90,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($total_ventas,MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE),'LRB',0,'C');
        }else{
            $pdf->Cell(181,7,iconv("UTF-8", "ISO-8859-1","No hay datos de ventas para mostrar"),'LBR',0,'C');
        }
		$pdf->Output("I","Reporte general de ventas ".$fecha_inicio." a ".$fecha_final.".pdf",true);

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
                <p class="subtitle has-text-white"><?php echo $texto_error; ?></p>
            </div>
        </section>
    </div>
	<?php include '../views/inc/script.php'; ?>
</body>
</html>
<?php } ?>