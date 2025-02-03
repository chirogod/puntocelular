<?php include "./app/views/includes/admin_security.php"; ?>
<div class="container is-fluid mb-4">
	<h1 class="title">Rubros</h1>
</div>

<div class="container pb-6 is-max-desktop">
    <h2 class="subtitle">Nuevo rubro</h2>    
	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/rubroAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

		<input type="hidden" name="modulo_rubro" value="registrar">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Rubro<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="rubro_descripcion" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
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
        use app\controllers\rubroController;

        $insRubro = new rubroController();

        echo $insRubro->listarRubroControlador($url[1],15,$url[0],"");
    ?>
</div>