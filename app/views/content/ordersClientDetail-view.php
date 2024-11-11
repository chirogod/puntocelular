<div class="">
	<?php 
        use app\controllers\clientController;
		$id = $insLogin->limpiarCadena($url[1]);
        $insCliente = new clientController();
	?>

</div>

<div class="container is-max-desktop" >
	<?php
	
		include "./app/views/includes/btn_back.php";

		$datos_venta = $insLogin->seleccionarDatos("Unico","venta","id_cliente",$id);
        $datos_cliente = $insLogin->seleccionarDatos("Unico","cliente","id_cliente",$id)->fetch();
		if($datos_venta->rowCount()>=1){
			$datos_venta=$datos_venta->fetch();
	?>

	<h2 class="title has-text-centered"><?php echo $datos_cliente['cliente_nombre_completo'] ; ?></h2>
	
    <?php
        echo $insCliente->listarOrdenesClienteControlador($url[1],15,$url[0],"", $datos_cliente['id_cliente']);
    ?>
	
	<?php
		}else{
			include "./app/views/includes/error_alert.php";
		}
	?>
</div>