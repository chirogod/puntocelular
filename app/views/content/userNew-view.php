<div class="container is-fluid mb-4">
	<h1 class="title">Usuarios</h1>
	<h2 class="subtitle"><i class="fas fa-user-tie fa-fw"></i> &nbsp; Nuevo usuario</h2>
</div>

<div class="container pb-6 is-max-desktop">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

		<input type="hidden" name="modulo_usuario" value="registrar">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>NOMBRE COMPLETO<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="usuario_nombre_completo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
			  <div class="column">
		    	<div class="control">
					<label>USUARIO<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>DNI</label>
				  	<input class="input" type="number" name="usuario_dni" pattern="[0-9]{5,15}" maxlength="40" >
				</div>
		  	</div>
			  <div class="column">
		    	<div class="control">
					<label>NACIMIENTO</label>
				  	<input class="input" type="date" name="usuario_nacimiento" maxlength="20" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>TELEFONO</label>
				  	<input class="input" type="tel" name="usuario_telefono" pattern="[0-9]{7,15}" maxlength="20">
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>EMAIL</label>
				  	<input class="input" type="email" name="usuario_email" maxlength="70" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>CLAVE<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\\s\/\(\)%\/\-\.]{3,100}" maxlength="100" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>REPETIR CLAVE<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\\s\/\(\)%\/\-\.]{3,100}" maxlength="100" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		  		<label>CARGO <?php echo CAMPO_OBLIGATORIO; ?></label><br>
				<div class="select">
				  	<select name="usuario_rol">
				    	<option value="" selected="" >Seleccione una opción</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Empleado">Empleado</option>
						<option value="Tecnico">Tecnico</option>
                        <option value="Vendedor">Vendedor</option>
				  	</select>
				</div>
		  	</div>
		  	
		</div>
		<p class="has-text-centered">
			<button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
			<button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
		</p>
		<p class="has-text-centered pt-6">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
	</form>
</div>