<?php
	require_once "../../config/app.php";
	require_once "../views/includes/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\senaController;

    if(isset($_POST['modulo_sena'])){
        $insSena = new senaController();

        /*--------- Buscar cliente ---------*/
		if($_POST['modulo_sena']=="buscar_cliente"){
			echo $insSena->buscarClienteVentaControlador();
		}

		/*--------- Agregar cliente a carrito ---------*/
		if($_POST['modulo_sena']=="agregar_cliente"){
			echo $insSena->agregarClienteVentaControlador();
		}

		/*--------- Remover cliente de carrito ---------*/
		if($_POST['modulo_sena']=="remover_cliente"){
			echo $insSena->removerClienteVentaControlador();
		}
        
        if ($_POST['modulo_sena'] == "registrar_sena") {
            echo $insSena->registrarSenaControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }