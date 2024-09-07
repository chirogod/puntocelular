<div class="">
	<?php 
		$id = $insLogin->limpiarCadena($url[1]);
	?>

</div>

<div class="container is-max-desktop" >
	<?php
	
		include "./app/views/includes/btn_back.php";

		$datos = $insLogin->seleccionarDatos("Unico","cliente","id_cliente",$id);

		if($datos->rowCount()==1){
			$datos=$datos->fetch();
	?>

	<h2 class="title has-text-centered"><?php echo $datos['cliente_nombre_completo'] ; ?></h2>
	<p class="has-text-centered">
		SI desea actualizar informacion del cliente, solo modifique los campos y pulse en actualizar, SI NO dejar los campos como estan.
	</p>

    <br>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/clienteAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_cliente" value="actualizar">
		<input type="hidden" name="id_cliente" value="<?php echo $datos['id_cliente']; ?>">

		<div class="columns ">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre completo</label>
				  	<input class="input" type="text" name="cliente_nombre_completo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" value="<?php echo $datos['cliente_nombre_completo']; ?>"  >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Email</label>
				  	<input class="input" type="text" name="cliente_email" value="<?php echo $datos['cliente_email']; ?>"  >
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
		    	<div class="control">
					<label>Telefono 1</label>
				  	<input class="input" type="number" name="cliente_telefono_1" pattern="[0-9]{4,20}" maxlength="20" value="<?php echo $datos['cliente_telefono_1']; ?>"  >
				</div>
		  	</div>
            <div class="column">
		    	<div class="control">
					<label>Telefono 2</label>
				  	<input class="input" type="number" name="cliente_telefono_2" pattern="[0-9]{4,20}" maxlength="20" value="<?php echo $datos['cliente_telefono_2']; ?>"  >
				</div>
		  	</div>
		</div>

		<div class="columns">
            <div class="column">
		    	<div class="control">
					<label>Domicilio</label>
				  	<input class="input" type="text" name="cliente_domicilio" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}" maxlength="100" value="<?php echo $datos['cliente_domicilio']; ?>"  >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Nacimiento</label>
				  	<input class="input" type="date" name="cliente_nacimiento" maxlength="100" value="<?php echo $datos['cliente_nacimiento']; ?>"  >
				</div>
		  	</div>
		</div>

        <div class="columns centered">
            <div class="column">
		  		<label>Localidad</label><br>
				<div class="select">
				  	<select name="cliente_localidad">
                        <option value="Lujan" <?php if($datos['cliente_localidad']=="Lujan"){ echo 'selected=""'; } ?> >Lujan <?php if($datos['cliente_localidad']=="Lujan"){ echo '(Actual)'; } ?></option>
                        <option value="Rodriguez" <?php if($datos['cliente_localidad']=="Rodriguez"){ echo 'selected=""'; } ?> >Rodriguez <?php if($datos['cliente_localidad']=="Rodriguez"){ echo '(Actual)'; } ?></option>
                        <option value="Moron" <?php if($datos['cliente_localidad']=="Moron"){ echo 'selected=""'; } ?> >Moron <?php if($datos['cliente_localidad']=="Moron"){ echo '(Actual)'; } ?></option>
						<option value="Moreno" <?php if($datos['cliente_localidad']=="Moreno"){ echo 'selected=""'; } ?> >Moreno <?php if($datos['cliente_localidad']=="Moreno"){ echo '(Actual)'; } ?></option>
                        <option value="Otro" <?php if($datos['cliente_localidad']=="Otro"){ echo 'selected=""'; } ?> >Otro <?php if($datos['cliente_localidad']=="Otro"){ echo '(Actual)'; } ?></option>
                    </select>
				</div>
		  	</div>
            <div class="column">
		  		<label>Provincia</label><br>
				<div class="select">
				  	<select name="cliente_provincia">
                        <option value="Buenos Aires" <?php if($datos['cliente_provincia']=="Buenos Aires"){ echo 'selected=""'; } ?> >Buenos Aires <?php if($datos['cliente_provincia']=="Buenos Aires"){ echo '(Actual)'; } ?></option>
                        <option value="Cordoba" <?php if($datos['cliente_provincia']=="Cordoba"){ echo 'selected=""'; } ?> >Cordoba <?php if($datos['cliente_provincia']=="Cordoba"){ echo '(Actual)'; } ?></option>
                        <option value="Otra" <?php if($datos['cliente_provincia']=="Otra"){ echo 'selected=""'; } ?> >Otra <?php if($datos['cliente_provincia']=="Otra"){ echo '(Actual)'; } ?></option>
                    </select>
				</div>
		  	</div>
		  	<div class="column">
		  		<label>Pais</label><br>
				<div class="select">
				  	<select name="cliente_pais">
                        <option value="Argentina" <?php if($datos['cliente_pais']=="Argentina"){ echo 'selected=""'; } ?> >Argentina <?php if($datos['cliente_pais']=="Argentina"){ echo '(Actual)'; } ?></option>
                        <option value="Bolivia" <?php if($datos['cliente_pais']=="Bolivia"){ echo 'selected=""'; } ?> >Bolivia <?php if($datos['cliente_pais']=="Bolivia"){ echo '(Actual)'; } ?></option>
                        <option value="Chile" <?php if($datos['cliente_pais']=="Chile"){ echo 'selected=""'; } ?> >Chile <?php if($datos['cliente_pais']=="Chile"){ echo '(Actual)'; } ?></option>
                        <option value="Venezuela" <?php if($datos['cliente_pais']=="Venezuela"){ echo 'selected=""'; } ?> >Venezuela <?php if($datos['cliente_pais']=="Venezuela"){ echo '(Actual)'; } ?></option>
                        <option value="Otro" <?php if($datos['cliente_pais']=="Otro"){ echo 'selected=""'; } ?> >Otro <?php if($datos['cliente_pais']=="Otro"){ echo '(Actual)'; } ?></option>
                    </select>
				</div>
		  	</div>
		</div>

        <div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Tipo de documento <?php echo CAMPO_OBLIGATORIO; ?></label><br>
				  	<div class="select">
                        <select name="cliente_tipo_doc">
                            <option value="DNI" <?php if($datos['cliente_tipo_doc']=="DNI"){ echo 'selected=""'; } ?> >DNI <?php if($datos['cliente_tipo_doc']=="DNI"){ echo '(Actual)'; } ?></option>
                            <option value="Cedula" <?php if($datos['cliente_tipo_doc']=="Cedula"){ echo 'selected=""'; } ?> >Cedula <?php if($datos['cliente_tipo_doc']=="Cedula"){ echo '(Actual)'; } ?></option>
                            <option value="Licencia" <?php if($datos['cliente_tipo_doc']=="Licencia"){ echo 'selected=""'; } ?> >Licencia <?php if($datos['cliente_tipo_doc']=="Licencia"){ echo '(Actual)'; } ?></option>
                            <option value="Pasaporte" <?php if($datos['cliente_tipo_doc']=="Pasaporte"){ echo 'selected=""'; } ?> >Pasaporte <?php if($datos['cliente_tipo_doc']=="Pasaporte"){ echo '(Actual)'; } ?></option>
                            <option value="Otro" <?php if($datos['cliente_tipo_doc']=="Otro"){ echo 'selected=""'; } ?> >Otro <?php if($datos['cliente_tipo_doc']=="Otro"){ echo '(Actual)'; } ?></option>
                        </select>
					</div>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Numero de documento <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="number" name="cliente_documento" pattern="[0-9]{7,30}" maxlength="30"  value="<?php echo $datos['cliente_documento']?>" >
				</div>
		  	</div>
		</div>
		<br>

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