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

        if ($_POST['modulo_cliente'] == "eliminar") {
            echo $insCliente->eliminarClienteControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }