<?php
	
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\saleEquipoController;

	if(isset($_POST['modulo_venta'])){

		$insVenta = new saleEquipoController();

		/*--------- Buscar cliente ---------*/
		if($_POST['modulo_venta']=="buscar_cliente"){
			echo $insVenta->buscarClienteVentaControlador();
		}

		/*--------- Agregar cliente a carrito ---------*/
		if($_POST['modulo_venta']=="agregar_cliente"){
			echo $insVenta->agregarClienteVentaControlador();
		}

		/*--------- Remover cliente de carrito ---------*/
		if($_POST['modulo_venta']=="remover_cliente"){
			echo $insVenta->removerClienteVentaControlador();
		}

		/*--------- Registrar venta ---------*/
		if($_POST['modulo_venta']=="registrar_venta"){
			echo $insVenta->registrarVentaControlador();
		}

		/*--------- financiar precio de producto ---------*/
		if($_POST['modulo_venta']=="financiar_producto"){
			echo $insVenta->financiarProducto();
		}
		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}