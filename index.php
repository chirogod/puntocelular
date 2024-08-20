<?php

    require_once "config/app.php";
    require_once "autoload.php";
    require_once "app/views/includes/session_start.php";

    /* --- capturar y procesar vista solicitada por el usuario --- */
    //si en la superglobal get viene la variable views
    if (isset($_GET['views'])) {
        //dividir lo que viene en la variable views por /
        $url = explode("/", $_GET['views']);
    }else{
        //si no existe esa views te manda al login
        $url = ['login'];
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once "app/views/includes/head.php" ?>
</head>
<body>
    <?php
        use app\controllers\viewsController;
        use app\controllers\loginController;
        
        $insLogin = new loginController();
        $viewsController = new viewsController();
        $vista = $viewsController->obtenerVistasControlador($url[0]);

        if ($vista == 'login' || $vista == '404') {
            require_once "app/views/content/".$vista."-view.php";
        }else{
    ?>

    <main>
        <?php
            
            require_once "./app/views/includes/sidebar.php";
        ?> 
        <section class="full-width pageContent scroll" id="pageContent">
            <?php
                require_once "./app/views/includes/navbar.php";

                require_once $vista;
            ?>
        </section>
    </main>

    <?php
        }
        require_once "app/views/includes/script.php"; 
    ?>

    
</body>
</html>