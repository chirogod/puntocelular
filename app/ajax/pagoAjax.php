<?php
	
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\pagoController;

	if(isset($_POST['modulo_pago'])){

		$insPago = new pagoController();

		/*--------- Registrar pago ---------*/
		if ($_POST['modulo_pago'] == "registrar_pago_venta") {
			echo $insPago->registrarPagoVentaControlador();	
		}

		/*--------- Registrar pago ---------*/
		if ($_POST['modulo_pago'] == "registrar_pago_mixto_venta") {
			echo $insPago->registrarPagoMixtoVentaControlador();	
		}

		/*--------- Registrar pago ---------*/
		if ($_POST['modulo_pago'] == "registrar_pago_venta_equipo") {
			echo $insPago->registrarPagoVentaEquipoControlador();	
		}

		/*--------- Registrar pago ---------*/
		if ($_POST['modulo_pago'] == "registrar_pago_sena_equipo") {
			echo $insPago->registrarPagoSenaEquipoControlador();	
		}

		/*--------- Registrar pago orden ---------*/
		if ($_POST['modulo_pago'] == "registrar_pago_orden") {
			echo $insPago->registrarPagoOrdenControlador();
		}
		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}