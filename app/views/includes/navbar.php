<div class="full-width navBar">
    <div class="full-width navBar-options">
        <i class="fas fa-exchange-alt fa-fw" id="btn-menu"></i> 
        <nav class="navBar-options-list">
            <ul class="list-unstyle">
                <li class="text-condensedLight noLink" >
                    <a class="btn-exit" href="<?php echo APP_URL."logOut/"; ?>" >
                        <i class="fas fa-power-off"></i>
                    </a>
                </li>
                <li class="text-condensedLight noLink" >
                    <small><?php echo $_SESSION['usuario_usuario']; ?></small>
                </li>
                <li class="text-condensedLight noLink" >
                    <small><?php echo $_SESSION['sucursal_descripcion']; ?></small>
                </li>
                <li class="text-condensedLight noLink" >
                    <small><?php echo $_SESSION['caja']; ?></small>
                </li>
            </ul>
        </nav>
    </div>
</div>