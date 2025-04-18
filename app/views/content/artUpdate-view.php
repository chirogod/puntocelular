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
		
		<div class="box">
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

				
				<div class="control">
					<label>Activo</label><br>
					<div class="select">
						<select name="articulo_activo">
							<option value="SI" <?php if($datos['articulo_activo']=="SI"){ echo 'selected=""'; } ?> >SI <?php if($datos['articulo_activo']=="SI"){ echo '(Actual)'; } ?></option>>SI</option>
							<option value="NO" <?php if($datos['articulo_activo']=="NO"){ echo 'selected=""'; } ?> >NO <?php if($datos['articulo_activo']=="NO"){ echo '(Actual)'; } ?></option>>NO</option>
						</select>
					</div>
				</div>
				
			</div>
			<div class="columns">
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
		</div>

		<div class="box">
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
						<input class="input" type="number" name="articulo_precio_compra" id="precio_compra" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo $datos['articulo_precio_compra']; ?>">
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Porcentaje ganancia<?php echo CAMPO_OBLIGATORIO; ?></label>
						<input class="input" onkeyup="actPrecioVenta()" onchange="actPrecioVenta()" type="number" id="porcentaje" name="articulo_porcentaje_ganancia" value="<?php echo $datos['articulo_porcentaje_ganancia']?>">
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Precio venta</label>
						<input readonly class="input" type="number" name="articulo_precio_venta" id="precio_venta" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo $datos['articulo_precio_venta']; ?>">
					</div>
				</div>
			</div>
		</div>

		<div class="box">
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
				<div class="column">
					<div class="control">
						<label>Stock critico</label>
						<input class="input" type="number" name="articulo_stock_critico" pattern="^[0-9]+$" value="<?php echo $datos['articulo_stock_critico']; ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="columns">
				<div class="column">
					<div class="control">
						<label>Garantia</label>
						<input class="input" type="text" name="articulo_garantia" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{1,50}" maxlength="50" value="<?php echo $datos['articulo_garantia'] ?>">
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Observaciones</label>
						<textarea class="textarea" name="articulo_observacion"><?php echo $datos['articulo_observacion'] ?></textarea>
					</div>
				</div>
			</div>	
		</div>
		

		<p class="has-text-centered">
			<button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
			<button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Actualizar</button>
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


<script>
	function actPrecioVenta(){
		var precioCompra = parseFloat(document.getElementById("precio_compra").value)||0;
		var porcentaje = parseFloat(document.getElementById("porcentaje").value)||0;
		var precioVenta = precioCompra + ((precioCompra * porcentaje)/100);
		if(porcentaje != 0){
			document.getElementById("precio_venta").value = precioVenta;
		}
		
	}
</script>