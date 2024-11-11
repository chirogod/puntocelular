<div class="">
	<?php 
		$id = $insLogin->limpiarCadena($url[1]);
	?>

</div>

<div class="container is-max-desktop" >
	<?php
	
		include "./app/views/includes/btn_back.php";

		$datos = $insLogin->seleccionarDatos("Unico","articulo","id_articulo",$id);

		if($datos->rowCount()==1){
			$datos=$datos->fetch();
	?>

	<h2 class="title has-text-centered"><?php echo $datos['articulo_descripcion'] ; ?></h2>
	<p class="has-text-centered">
		SI desea actualizar informacion del articulo, solo modifique los campos y pulse en actualizar, SI NO dejar los campos como estan.
	</p>

    <br>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/articuloAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_articulo" value="actualizar">
		<input type="hidden" name="id_articulo" value="<?php echo $datos['id_articulo']; ?>">
		
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label> Articulo <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <input class="input" type="text" name="articulo_descripcion"  maxlength="200" required value="<?php echo $datos['articulo_descripcion']; ?>" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Codigo <small>(para generar automaticamente dejar vacio)</small></label>
				  	<input class="input" type="text" name="articulo_codigo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{3,200}" value="<?php echo $datos['articulo_codigo']; ?>"  >
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
		    	<div class="control">
					<label>Stock <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="number" name="articulo_stock" pattern="^[0-9]+$" required value="<?php echo $datos['articulo_stock']; ?>" >
				</div>
		  	</div>
            <div class="column">
		    	<div class="control">
					<label>Stock minimo</label>
				  	<input class="input" type="number" name="articulo_stock_min" pattern="^[0-9]+$" value="<?php echo $datos['articulo_stock_min']; ?>" >
				</div>
		  	</div>
            <div class="column">
		    	<div class="control">
					<label>Stock maximo</label>
				  	<input class="input" type="number" name="articulo_stock_max" pattern="^[0-9]+$" value="<?php echo $datos['articulo_stock_max']; ?>">
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
		  		<label>Activo</label><br>
				<div class="select">
				  	<select name="articulo_activo">
                        <option value="SI" <?php if($datos['articulo_activo']=="SI"){ echo 'selected=""'; } ?> >SI <?php if($datos['articulo_activo']=="SI"){ echo '(Actual)'; } ?></option>>SI</option>
						<option value="NO" <?php if($datos['articulo_activo']=="NO"){ echo 'selected=""'; } ?> >NO <?php if($datos['articulo_activo']=="NO"){ echo '(Actual)'; } ?></option>>NO</option>
				  	</select>
				</div>
		  	</div>
			<div class="column">
				<label>Rubro <?php echo CAMPO_OBLIGATORIO; ?></label><br>
				<div class="select">
					<select name="id_rubro" >
						<option selected="" >Seleccione una opción</option>
						<?php
							$datos_categorias=$insLogin->seleccionarDatos("Normal","rubro","*",0);

							$cc=1;
							while($campos_categoria=$datos_categorias->fetch()){
								$selected = ($campos_categoria['id_rubro'] == $datos['id_rubro']) ? 'selected' : '';
								echo '<option value="'.$campos_categoria['id_rubro'].'" '.$selected.'>'.$cc.' - '.$campos_categoria['rubro_descripcion'].'</option>';
								$cc++;
							}
						?>
					</select>
				</div>
			</div>
			<div class="column">
				<label>Sucursal <?php echo CAMPO_OBLIGATORIO; ?></label><br>
				<div class="select">
					<select name="id_sucursal" >
						<option value="" selected="" >Seleccione una opción</option>
						<?php
							$datos_sucursal=$insLogin->seleccionarDatos("Normal","sucursal","*",0);

							$cc=1;
							while($campos_sucursal=$datos_sucursal->fetch()){
								$selected = ($campos_sucursal['id_sucursal'] == $datos['id_sucursal']) ? 'selected' : '';
								echo '<option value="'.$campos_sucursal['id_sucursal'].'" '.$selected.'>'.$cc.' - '.$campos_sucursal['sucursal_descripcion'].'</option>';
								$cc++;
							}
						?>
					</select>
				</div>
			</div>
		</div>
        <div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Garantia</label>
				  	<input class="input" type="text" name="articulo_garantia" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{1,50}" maxlength="50" value="<?php echo $datos['articulo_garantia']; ?>">
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Observaciones</label>
				  	<input class="input" type="text" name="articulo_observacion" value="<?php echo $datos['articulo_observacion']; ?>">
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
		  		<label>MONEDA <?php echo CAMPO_OBLIGATORIO; ?></label><br>
				<div class="select">
				  	<select name="articulo_moneda">
                        <option value="ARS" <?php if($datos['articulo_moneda']=="ARS"){ echo 'selected=""'; } ?> >ARS</option>
                        <option value="USD" <?php if($datos['articulo_moneda']=="USD"){ echo 'selected=""'; } ?>>USD</option>
				  	</select>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label> Precio compra <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <input class="input" type="number" name="articulo_precio_compra" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo $datos['articulo_precio_compra']; ?>">
				</div>
		  	</div>
			<div class="column">
				<div class="control">
					<label>Porcentaje ganancia<?php echo CAMPO_OBLIGATORIO; ?></label>
					<div class="select">
						<select name="articulo_porcentaje_ganancia">
							<option value="100" <?php echo ($datos['articulo_porcentaje_ganancia'] == 100) ? 'selected' : ''; ?>>100</option>
							<option value="95" <?php echo ($datos['articulo_porcentaje_ganancia'] == 95) ? 'selected' : ''; ?>>95</option>
							<option value="90" <?php echo ($datos['articulo_porcentaje_ganancia'] == 90) ? 'selected' : ''; ?>>90</option>
							<option value="85" <?php echo ($datos['articulo_porcentaje_ganancia'] == 85) ? 'selected' : ''; ?>>85</option>
							<option value="80" <?php echo ($datos['articulo_porcentaje_ganancia'] == 80) ? 'selected' : ''; ?>>80</option>
							<option value="75" <?php echo ($datos['articulo_porcentaje_ganancia'] == 75) ? 'selected' : ''; ?>>75</option>
							<option value="70" <?php echo ($datos['articulo_porcentaje_ganancia'] == 70) ? 'selected' : ''; ?>>70</option>
							<option value="65" <?php echo ($datos['articulo_porcentaje_ganancia'] == 65) ? 'selected' : ''; ?>>65</option>
							<option value="60" <?php echo ($datos['articulo_porcentaje_ganancia'] == 60) ? 'selected' : ''; ?>>60</option>
							<option value="55" <?php echo ($datos['articulo_porcentaje_ganancia'] == 55) ? 'selected' : ''; ?>>55</option>
							<option value="50" <?php echo ($datos['articulo_porcentaje_ganancia'] == 50) ? 'selected' : ''; ?>>50</option>
							<option value="45" <?php echo ($datos['articulo_porcentaje_ganancia'] == 45) ? 'selected' : ''; ?>>45</option>
							<option value="40" <?php echo ($datos['articulo_porcentaje_ganancia'] == 40) ? 'selected' : ''; ?>>40</option>
							<option value="35" <?php echo ($datos['articulo_porcentaje_ganancia'] == 35) ? 'selected' : ''; ?>>35</option>
							<option value="30" <?php echo ($datos['articulo_porcentaje_ganancia'] == 30) ? 'selected' : ''; ?>>30</option>
							<option value="25" <?php echo ($datos['articulo_porcentaje_ganancia'] == 25) ? 'selected' : ''; ?>>25</option>
							<option value="20" <?php echo ($datos['articulo_porcentaje_ganancia'] == 20) ? 'selected' : ''; ?>>20</option>
							<option value="15" <?php echo ($datos['articulo_porcentaje_ganancia'] == 15) ? 'selected' : ''; ?>>15</option>
							<option value="10" <?php echo ($datos['articulo_porcentaje_ganancia'] == 10) ? 'selected' : ''; ?>>10</option>
						</select>
					</div>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Precio venta></label>
					<input readonly class="input" type="number" name="articulo_precio_venta" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo $datos['articulo_precio_venta']; ?>">
				</div>
		  	</div>
		</div>
		
		<div class="columns">
			<div class="column">
		    	<div class="control">
					<label>Marca</label>
				  	<input class="input" type="text" name="articulo_marca" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{1,30}" value="<?php echo $datos['articulo_marca']; ?>">
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Modelo</label>
				  	<input class="input" type="text" name="articulo_modelo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{1,30}" value="<?php echo $datos['articulo_modelo']; ?>">
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
		}else{
			include "./app/views/includes/error_alert.php";
		}
	?>
</div>