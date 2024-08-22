<?php
    if ($_SESSION['usuario_rol'] != "Administrador") {
        include "app/views/content/logOut-view.php";
    }