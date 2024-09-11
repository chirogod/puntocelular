<div class="container is-fluid is-max-desktop">
	<h1 class="title">Equipos</h1>
	<h2 class="subtitle"><i class="fas fa-male fa-fw"></i> &nbsp; Nuevo equipo</h2>
</div>

<div class="container pb-6 pt-6 is-max-desktop">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/equipoAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_equipo" value="registrar">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label> Articulo <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <input class="input" type="text" name="equipo_descripcion"  maxlength="200" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Codigo <small>(para generar automaticamente dejar vacio)</small></label>
				  	<input class="input" type="text" name="equipo_codigo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{3,200}" >
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
		    	<div class="control">
					<label>Stock <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="number" name="equipo_stock" pattern="^[0-9]+$" required >
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
				<label>Sucursal <?php echo CAMPO_OBLIGATORIO; ?></label><br>
		    	<div class="select">
				  	<select name="id_sucursal" >
				    	<option value="" selected="" >Seleccione una opción</option>
				    	<?php
                            $datos_sucursal=$insLogin->seleccionarDatos("Normal","sucursal","*",0);

                            $cc=1;
                            while($campos_sucursal=$datos_sucursal->fetch()){
                                echo '<option value="'.$campos_sucursal['id_sucursal'].'">'.$cc.' - '.$campos_sucursal['sucursal_descripcion'].'</option>';
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
				  	<input class="input" type="text" name="equipo_garantia" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{1,200}" maxlength="200">
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
		  		<label>MONEDA <?php echo CAMPO_OBLIGATORIO; ?></label><br>
				<div class="select">
				  	<select name="equipo_moneda">
                        <option value="ARS" >ARS</option>
                        <option value="USD"selected>USD</option>
				  	</select>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label> Precio compra <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <input class="input" type="number" name="equipo_precio_compra" pattern="[0-9.]{1,25}" maxlength="25" value="0.00">
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Precio venta <?php echo CAMPO_OBLIGATORIO; ?></label>
					<input class="input" type="number" name="equipo_precio_venta" pattern="[0-9.]{1,25}" maxlength="25" value="0.00">
				</div>
		  	</div>
		</div>
		
		<div class="columns">
			<div class="column">
		    	<div class="control">
					<label>Marca</label>
				  	<input class="input" type="text" name="equipo_marca" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{1,30}">
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Modelo</label>
				  	<input class="input" type="text" name="equipo_modelo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{1,30}">
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


