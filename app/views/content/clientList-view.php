<div class="container is-fluid is-max-desktop">
	<h1 class="title">Clientes</h1>
	<h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de clientes</h2>
</div>
<div class="container is-max-desktop mt-6">
    <?php
    
        use app\controllers\clientController;
        $insCliente = new clientController();

        if(!isset($_SESSION[$url[0]]) && empty($_SESSION[$url[0]])){
    ?>
    <div class="columns">
        <div class="column ">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/buscadorAjax.php" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="buscar">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="Buscar cliente" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\- ]{1,30}" maxlength="30" required >
                    </p>
                    <p class="control">
                        <button class="button is-info" type="submit" >Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <?php }else{ ?>
    <div class="columns">
        <div class="column">
            <form class="has-text-centered FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/buscadorAjax.php" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="eliminar">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
                <p><i class="fas fa-search fa-fw"></i> &nbsp; Estas buscando <strong>“<?php echo $_SESSION[$url[0]]; ?>”</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded"><i class="fas fa-trash-restore"></i> &nbsp; Eliminar busqueda</button>
            </form>
        </div>
    </div>
    <?php
            echo $insCliente->listarClienteControlador($url[1],15,$url[0],$_SESSION[$url[0]]);
        }
    ?>
</div>
<div class="container pb-4 is-max-desktop">

	<div class="form-rest mb-6"></div>

	<?php

		echo $insCliente->listarClienteControlador($url[1],15,$url[0],"");
	?>
</div>
