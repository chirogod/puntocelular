<?php
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\articuloController;

    if(isset($_POST['modulo_articulo'])){
        $insArticulo = new articuloController();

        if ($_POST['modulo_articulo'] == "registrar") {
            echo $insArticulo->registrarArticuloControlador();
        }

        if ($_POST['modulo_articulo'] == "actualizar") {
            echo $insArticulo->actualizarArticuloControlador();
        }

        /*--------- Buscar articuloArticulo ---------*/
		if($_POST['modulo_articulo']=="buscar_articulo"){
			echo $insArticulo->buscarArticuloControlador();
		}

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }