<div class="container is-fluid mb-6">
	<?php 

		$id = $insLogin->limpiarCadena($url[1]);

		if($_SESSION['usuario_rol']!="Administrador"){
			include "./app/views/content/logOut-view.php";
		}

		if($id == $_SESSION['id_usuario']){ 
	?>
	<h1 class="title">Mi cuenta</h1>
	<h2 class="subtitle"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar cuenta</h2>
	<?php }else{ ?>
	<h1 class="title">Usuarios</h1>
	<h2 class="subtitle"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar usuario</h2>
	<?php } ?>
</div>

<div class="container pb-6 pt-6">
	<?php
	
		include "./app/views/includes/btn_back.php";

		$datos = $insLogin->seleccionarDatos("Unico","usuario","id_usuario",$id);

		if($datos->rowCount()==1){
			$datos=$datos->fetch();
	?>

	<h2 class="title has-text-centered"><?php echo $datos['usuario_nombre_completo'] ; ?></h2>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_usuario" value="actualizar">
		<input type="hidden" name="id_usuario" value="<?php echo $datos['id_usuario']; ?>">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre completo</label>
				  	<input class="input" type="text" name="usuario_nombre_completo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" value="<?php echo $datos['usuario_nombre_completo']; ?>" required >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Usuario</label>
				  	<input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" value="<?php echo $datos['usuario_usuario']; ?>" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Email</label>
				  	<input class="input" type="email" name="usuario_email" maxlength="70" value="<?php echo $datos['usuario_email']; ?>" >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Telefono</label>
				  	<input class="input" type="number" name="usuario_telefono" pattern="[0-9]{4,20}" maxlength="20" value="<?php echo $datos['usuario_telefono']; ?>" required >
				</div>
		  	</div>
		</div>

		<div class="columns">
			<div class="column">
		  		<label>Cargo</label><br>
				<div class="select">
				  	<select name="usuario_rol">
                        <option value="Administrador" <?php if($datos['usuario_rol']=="Administrador"){ echo 'selected=""'; } ?> >Administrador <?php if($datos['usuario_rol']=="Administrador"){ echo '(Actual)'; } ?></option>
                        <option value="Empleado" <?php if($datos['usuario_rol']=="Empleado"){ echo 'selected=""'; } ?> >Empleado <?php if($datos['usuario_rol']=="Empleado"){ echo '(Actual)'; } ?></option>
                        <option value="Tecnico" <?php if($datos['usuario_rol']=="Tecnico"){ echo 'selected=""'; } ?> >Tecnico <?php if($datos['usuario_rol']=="Tecnico"){ echo '(Actual)'; } ?></option>
						<option value="Ventas" <?php if($datos['usuario_rol']=="Ventas"){ echo 'selected=""'; } ?> >Ventas <?php if($datos['usuario_rol']=="Ventas"){ echo '(Actual)'; } ?></option>
					</select>
				</div>
		  	</div>

		  	<div class="column">
		  		<label>Activo</label><br>
				<div class="select">
				  	<select name="usuario_activo">
                        <option value="SI">SI</option>
						<option value="NO">NO</option>t
				  	</select>
				</div>
		  	</div>
		</div>
		<br><br>
		<p class="has-text-centered">
			SI desea actualizar la clave de este usuario por favor llene los 2 campos. Si NO desea actualizar la clave deje los campos vacíos.
		</p>
		<br>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nueva clave</label>
				  	<input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Repetir nueva clave</label>
				  	<input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" >
				</div>
		  	</div>
		</div>
		<br><br><br>

		<p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar</button>
		</p>

	</form>
	<?php
		}else{
			include "./app/views/includes/error_alert.php";
		}
	?>
</div>