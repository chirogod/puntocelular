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

        if ($_POST['modulo_sucursal'] == "actualizar") {
            echo $insSucursal->actualizarSucursalControlador();
        }

        if ($_POST['modulo_sucursal'] == "eliminar") {
            echo $insSucursal->eliminarSucursalControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }