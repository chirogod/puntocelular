<?php
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\rubroController;

    if(isset($_POST['modulo_rubro'])){
        $insRubro = new rubroController();

        if ($_POST['modulo_rubro'] == "registrar") {
            echo $insRubro->registrarRubroControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }