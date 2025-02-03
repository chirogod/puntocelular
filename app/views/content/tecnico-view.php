<?php
    use app\controllers\userController;
    $insUsuario = new userController();
?>   

<div class="container is-fluid mb-4">
	<h1 class="title">Tecnicos</h1>
</div>

<div class="container pb-2 is-max-desktop">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

		<input type="hidden" name="modulo_usuario" value="registrar_tecnico">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>NOMBRE COMPLETO<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="tecnico_descripcion" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" maxlength="40" required >
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
			<button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
		</p>
	</form>
</div>

<div class="container is-fluid mb-4">
    <h2 class="subtitle">Listado de tecnicos</h2>
    <?php
        echo $insUsuario->listarTecnicosControlador();
    ?>
</div>

    
