<div class="container is-fluid is-max-desktop">
	<h1 class="title">Articulos</h1>
	<h2 class="subtitle"><i class="fas fa-male fa-fw"></i> &nbsp; Nuevo articulo</h2>
</div>

<div class="container pb-6 pt-1 is-max-desktop">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/articuloAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_articulo" value="registrar">
		
		<div class="box">
			<h2 class="subtitle">Articulo</h2>
			<div class="columns">
				<div class="column">
					<div class="control">
						<label> Articulo <?php echo CAMPO_OBLIGATORIO; ?></label><br>
						<input class="input" type="text" name="articulo_descripcion"  maxlength="200" required >
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Codigo <small>(para generar automaticamente dejar vacio)</small></label>
						<input class="input" type="text" name="articulo_codigo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{3,200}" >
					</div>
				</div>
			</div>
			<div class="columns">
				<div class="column">
					<label>Rubro <?php echo CAMPO_OBLIGATORIO; ?></label><br>
					<div class="select">
						<select name="id_rubro" >
							<option value="" selected="" >Seleccione una opción</option>
							<?php
								$datos_categorias=$insLogin->seleccionarDatos("Normal","rubro","*",0);

								$cc=1;
								while($campos_categoria=$datos_categorias->fetch()){
									echo '<option value="'.$campos_categoria['id_rubro'].'">'.$cc.' - '.$campos_categoria['rubro_descripcion'].'</option>';
									$cc++;
								}
							?>
						</select>
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Marca</label>
						<input class="input" type="text" name="articulo_marca" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{1,30}">
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Modelo</label>
						<input class="input" type="text" name="articulo_modelo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{1,30}">
					</div>
				</div>
			</div>
		</div>

		<div class="box">
			<h2 class="subtitle">Precio</h2>
			<div class="columns">
				<div class="column">
					<div class="control">
						<label>MONEDA <?php echo CAMPO_OBLIGATORIO; ?></label><br>
						<div class="select">
							<select name="articulo_moneda">
								<option value="ARS" selected>ARS</option>
								<option value="USD">USD</option>
							</select>
						</div>
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label> Precio compra <?php echo CAMPO_OBLIGATORIO; ?></label><br>
						<input class="input" onkeyup="actPrecioVenta()" type="number" id="precio_compra" name="articulo_precio_compra" value="0.00">
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Porcentaje ganancia<?php echo CAMPO_OBLIGATORIO; ?></label>
						<input class="input" onkeyup="actPrecioVenta()" type="number" id="porcentaje" name="articulo_porcentaje_ganancia" value="0.00">
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Precio venta<?php echo CAMPO_OBLIGATORIO; ?></label>
						<input class="input" type="number" name="articulo_precio_venta" id="precio_venta" value="0.00">
					</div>
				</div>
			</div>
		</div>
		<div class="box">
			<h2 class="subtitle">Stock</h2>
			<div class="columns">
				<div class="column">
					<div class="control">
						<label>Stock <?php echo CAMPO_OBLIGATORIO; ?></label>
						<input class="input" type="number" name="articulo_stock" pattern="^[0-9]+$" required >
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Stock minimo</label>
						<input class="input" type="number" name="articulo_stock_min" pattern="^[0-9]+$" >
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Stock maximo</label>
						<input class="input" type="number" name="articulo_stock_max" pattern="^[0-9]+$" >
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Stock critico</label>
						<input class="input" type="number" name="articulo_stock_critico" pattern="^[0-9]+$" >
					</div>
				</div>
			</div>
		</div>
		<div class="box">
			<h2 class="subtitle">Detalles</h2>
			<div class="columns">
				<div class="column">
					<div class="control">
						<label>Garantia</label>
						<input class="input" type="text" name="articulo_garantia" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{1,50}" maxlength="50">
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Observaciones</label>
						<textarea class="textarea" name="articulo_observacion" id=""></textarea>
					</div>
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

<!--
parsefloat es para transformar los valores recibidos en numeros, sino funcionaban como strings
-->