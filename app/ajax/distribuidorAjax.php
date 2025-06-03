<?php
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\distribuidorController;

    if(isset($_POST['modulo_distribuidor'])){
        $insDistribuidor = new distribuidorController();

        if ($_POST['modulo_distribuidor'] == "registrar") {
            echo $insDistribuidor->registrarDistribuidorControlador();
        }

        if ($_POST['modulo_distribuidor'] == "editar") {
            echo $insDistribuidor->editarDistribuidorControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }