<?php
	
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\ordenController;

	if(isset($_POST['modulo_orden'])){

		$insOrden = new ordenController();

		/*--------- Buscar cliente ---------*/
		if($_POST['modulo_orden']=="buscar_cliente"){
			echo $insOrden->buscarClienteOrdenControlador();
		}

		/*--------- Agregar cliente a la orden ---------*/
		if($_POST['modulo_orden']=="agregar_cliente"){
			echo $insOrden->agregarClienteOrdenControlador();
		}

		/*--------- Remover cliente de la orden ---------*/
		if($_POST['modulo_orden']=="remover_cliente"){
			echo $insOrden->removerClienteOrdenControlador();
		}

		/*--------- Registrar orden ---------*/
		if($_POST['modulo_orden']=="registrar_orden"){
			echo $insOrden->registrarOrdenControlador();
		}

		/*--------- Registrar orden ---------*/
		if($_POST['modulo_orden']=="actualizar_orden"){
			echo $insOrden->actualizarOrdenControlador();
		}

		/*--------- Registrar orden ---------*/
		if($_POST['modulo_orden']=="registrar_informe_tecnico"){
			echo $insOrden->registrarInformeTecnicoOrdenControlador();
		}

		/*--------- Boton aceptar orden ---------*/
		if($_POST['modulo_orden']=="aceptar_orden"){
			echo $insOrden->aceptarOrdenControlador();
		}

		/*--------- Boton rechazar orden ---------*/
		if($_POST['modulo_orden']=="cambiar_estado_orden"){
			echo $insOrden->cambiarEstadoOrdenControlador();
		}

		/*--------- Boton entregar orden ---------*/
		if($_POST['modulo_orden']=="entregar_orden"){
			echo $insOrden->entregarOrdenControlador();
		}

		/*--------- Buscar producto por codigo ---------*/
		if($_POST['modulo_orden']=="buscar_codigo"){
			echo $insOrden->buscarCodigoOrdenControlador();
		}

		/*--------- Agregar producto a carrito ---------*/
		if($_POST['modulo_orden']=="agregar_producto"){
			echo $insOrden->agregarProductoCarritoControlador();
		}

		/*--------- Remover producto de carrito ---------*/
		if($_POST['modulo_orden']=="remover_producto"){
			echo $insOrden->removerProductoCarritoControlador();
		}
		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}