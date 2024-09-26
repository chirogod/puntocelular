<?php
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\cajaController;

    if(isset($_POST['modulo_caja'])){
        $insCaja = new cajaController();

        if ($_POST['modulo_caja'] == "registrar") {
            echo $insCaja->registrarCajaControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }