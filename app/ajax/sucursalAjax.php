<?php
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\sucursalController;

    if(isset($_POST['modulo_sucursal'])){
        $insSucursal = new sucursalController();

        if ($_POST['modulo_sucursal'] == "registrar") {
            echo $insSucursal->registrarSucursalControlador();
        }

        if ($_POST['modulo_sucursal'] == "actualizar_taller") {
            echo $insSucursal->actualizarTallerControlador();
        }

        if ($_POST['modulo_sucursal'] == "usd_pc") {
            echo $insSucursal->actualizarUsdControlador();
        }

        if ($_POST['modulo_sucursal'] == "costo_operativo") {
            echo $insSucursal->actualizarCostoOperativoControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }