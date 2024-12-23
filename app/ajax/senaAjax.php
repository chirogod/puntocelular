<?php
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\senaController;

    if(isset($_POST['modulo_sena'])){
        $insSena = new senaController();

        if ($_POST['modulo_sena'] == "registrar") {
            echo $insSena->registrarSenaControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }