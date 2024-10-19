<?php
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\equipoController;

    if(isset($_POST['modulo_equipo'])){
        $insEquipo = new equipoController();

        if ($_POST['modulo_equipo'] == "registrar") {
            echo $insEquipo->registrarEquipoControlador();
        }

        if ($_POST['modulo_equipo'] == "actualizar") {
            echo $insEquipo->actualizarEquipoControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }