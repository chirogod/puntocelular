<?php include "./app/views/includes/admin_security.php"; ?>
<div class="container is-fluid mb-4">
	<h1 class="title">Distribuidores</h1>
</div>

<div class="container pb-6 is-max-desktop"> 
	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/distribuidorAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

		<input type="hidden" name="modulo_distribuidor" value="registrar">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Distribuidor (con este nombre se mostrara en tabla)<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="distribuidor_descripcion"  maxlength="100" required >
				</div>
                <div class="control">
					<label>LINK<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="distribuidor_link" required >
				</div>
		  	</div>
		</div>

		<p class="has-text-centered">
			<button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
			<button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
		</p>
		<p class="has-text-centered pt-1">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
	</form>

    <?php
        use app\controllers\distribuidorController;

        $insDistribuidor = new distribuidorController();

        echo $insDistribuidor->listarDistribuidorControlador($url[1],15,$url[0],"");
    ?>
</div>