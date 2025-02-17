<?php
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\repuestoController;

    if(isset($_POST['modulo_repuesto'])){
        $insRepuesto = new repuestoController();

        if ($_POST['modulo_repuesto'] == "registrar_pedido") {
            echo $insRepuesto->registrarPedidoControlador();
        }

        if ($_POST['modulo_repuesto'] == "ingreso_pedido") {
            echo $insRepuesto->ingresoPedidoControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }