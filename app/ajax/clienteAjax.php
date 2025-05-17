<?php
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\clientController;

    if(isset($_POST['modulo_cliente'])){
        $insCliente = new clientController();

        if ($_POST['modulo_cliente'] == "registrar") {
            echo $insCliente->registrarClienteControlador();
        }

        if ($_POST['modulo_cliente'] == "actualizar") {
            echo $insCliente->actualizarClienteControlador();
        }

        /*--------- Buscar cliente ---------*/
		if($_POST['modulo_cliente']=="buscar_cliente"){
			echo $insCliente->buscarClienteControlador();
		}

        /* registrar cliente y guardar en sesion para compra o orden */
        if ($_POST['modulo_cliente'] == "registrarGuardarSesion") {
            echo $insCliente->registrarClienteGuardarSesionControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }