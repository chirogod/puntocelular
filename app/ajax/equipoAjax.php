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

        if ($_POST['modulo_equipo'] == "buscar_equipo") {
            echo $insEquipo->buscarEquipoControlador();
        }

        if ($_POST['modulo_equipo'] == "registrar_marca") {
            echo $insEquipo->registrarMarcaControlador();
        }

        if ($_POST['modulo_equipo'] == "registrar_modelo") {
            echo $insEquipo->registrarModeloControlador();
        }

        if ($_POST['modulo_equipo'] == "registrar_pedido_equipo") {
            echo $insEquipo->registrarPedidoEquipoControlador();
        }

        if ($_POST['modulo_equipo'] == "ingreso_pedido") {
            echo $insEquipo->ingresoPedidoControlador();
        }

        if ($_POST['modulo_equipo'] == "eliminar_pedido") {
            echo $insEquipo->eliminarPedidoControlador();
        }

        if ($_POST['modulo_equipo'] == "buscar_pedido_equipo") {
            echo $insEquipo->buscarPedidoControlador();
        }
    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }