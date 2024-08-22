<?php
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\userController;

    if(isset($_POST['modulo_usuario'])){
        $insUsuario = new userController();

        if ($_POST['modulo_usuario'] == "registrar") {
            echo $insUsuario->registrarUsuarioControlador();
        }

        if ($_POST['modulo_usuario'] == "actualizar") {
            echo $insUsuario->actualizarUsuarioControlador();
        }

        if ($_POST['modulo_usuario'] == "eliminar") {
            echo $insUsuario->eliminarUsuarioControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }