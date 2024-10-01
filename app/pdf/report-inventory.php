<?php

    $orden=(isset($_GET['order'])) ? $_GET['order'] : "";

	/*---------- Incluyendo configuraciones ----------*/
	require_once "../../config/app.php";
    require_once "../../autoload.php";

	/*---------- Instancia al controlador producto ----------*/
	use app\controllers\productController;
	$ins_producto = new productController();


	if($orden!="" && (in_array($orden,["nasc","ndesc","sasc","sdesc","pasc","pdesc"]))){

        if($orden=="nasc"){
            $orden="producto_nombre ASC";
        }elseif($orden=="ndesc"){
            $orden="producto_nombre DESC";
        }elseif($orden=="sasc"){
            $orden="producto_stock_total ASC";
        }elseif($orden=="sdesc"){
            $orden="producto_stock_total DESC";
        }elseif($orden=="pasc"){
        	$orden="producto_precio_venta ASC";
        }elseif($orden=="pdesc"){
        	$orden="producto_precio_venta DESC";
        }else{
            $orden="producto_nombre ASC";
        }

		/*---------- Seleccion de datos de la empresa ----------*/
		$datos_empresa=$ins_producto->seleccionarDatos("Normal","empresa LIMIT 1","*",0);
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
        
        $pdf->MultiCell(0,9,iconv("UTF-8", "ISO-8859-1",strtoupper("Reporte de inventario general (Fecha:".date("d-m-Y")."  Hora:".date("h:i a").")")),0,'C',false);

        $pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(23,83,201);
		$pdf->SetDrawColor(23,83,201);
		$pdf->SetTextColor(255,255,255);
		$pdf->Cell(30,8,iconv("UTF-8", "ISO-8859-1",'Codigo'),1,0,'C',true);
		$pdf->Cell(80,8,iconv("UTF-8", "ISO-8859-1",'Nombre'),1,0,'C',true);
		$pdf->Cell(34,8,iconv("UTF-8", "ISO-8859-1",'Precio venta'),1,0,'C',true);
		$pdf->Cell(14,8,iconv("UTF-8", "ISO-8859-1",'Stock'),1,0,'C',true);
		$pdf->Cell(23,8,iconv("UTF-8", "ISO-8859-1",'Unidad'),1,0,'C',true);

		$pdf->Ln(8);

		$pdf->SetFont('Arial','',8);
		$pdf->SetTextColor(39,39,51);

		/*----------  Seleccionando datos de productos  ----------*/
        $campos_productos="producto_codigo,producto_nombre,producto_stock_total,producto_precio_venta,producto_tipo_unidad";

        $check_producto=$ins_producto->seleccionarDatos("Normal","producto WHERE (producto_estado = 'Habilitado' AND producto_stock_total >= 1) ORDER BY $orden",$campos_productos,0);

        if($check_producto->rowCount()>=1){
            $datos_productos=$check_producto->fetchAll();

			foreach($datos_productos as $row){
				$pdf->Cell(30,7,iconv("UTF-8", "ISO-8859-1",$row['producto_codigo']),'LB',0,'C');
				$pdf->Cell(80,7,iconv("UTF-8", "ISO-8859-1",$ins_producto->limitarCadena($row['producto_nombre'],63,"...")),'LB',0,'C');
				$pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.$row['producto_precio_venta']),'LB',0,'C');
				$pdf->Cell(14,7,iconv("UTF-8", "ISO-8859-1",$row['producto_stock_total']),'LB',0,'C');
				$pdf->Cell(23,7,iconv("UTF-8", "ISO-8859-1",$row['producto_tipo_unidad']),'LRB',0,'C');
				$pdf->Ln(7);
			}

        }else{
            $pdf->Cell(181,7,iconv("UTF-8", "ISO-8859-1","No hay datos de productos para mostrar"),'LBR',0,'C');
        }
		$pdf->Output("I","Reporte inventario ".date("d")."-".date("m")."-".date("Y").".pdf",true);

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
                    <i class="far fa-thumbs-down fa-10x"></i>
                </p>
                <p class="title has-text-white">¡Ocurrió un error!</p>
                <p class="subtitle has-text-white">No ha seleccionado un orden valido</p>
            </div>
        </section>
    </div>
	<?php include '../views/inc/script.php'; ?>
</body>
</html>
<?php } ?>