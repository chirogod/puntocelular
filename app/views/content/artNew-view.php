<div class="container is-fluid is-max-desktop">
	<h1 class="title">Articulos</h1>
	<h2 class="subtitle"><i class="fas fa-male fa-fw"></i> &nbsp; Nuevo articulo</h2>
</div>

<div class="container pb-6 pt-6 is-max-desktop">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/articuloAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_articulo" value="registrar">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label> Articulo <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <input class="input" type="text" name="articulo_descripcion" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{3,200}" maxlength="200" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Codigo <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="articulo_codigo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{3,200}" required >
				</div>
		  	</div>
		</div>
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
		</div>
		<div class="columns">
			<div class="column">
		  		<label>Activo</label><br>
				<div class="select">
				  	<select name="articulo_activo">
                        <option value="SI">SI</option>
						<option value="NO">NO</option>t
				  	</select>
				</div>
		  	</div>
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
				  	<input class="input" type="text" name="articulo_garantia" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{1,50}" maxlength="50">
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Observaciones</label>
				  	<input class="input" type="text" name="articulo_observacion" pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]*$">
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
		  		<label>MONEDA <?php echo CAMPO_OBLIGATORIO; ?></label><br>
				<div class="select">
				  	<select name="articulo_moneda">
                        <option value="ARS" selected>ARS</option>
                        <option value="USD">USD</option>
				  	</select>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label> Precio compra <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <input class="input" type="number" name="articulo_precio_compra" pattern="[0-9.]{1,25}" maxlength="25" value="0.00">
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Precio venta <?php echo CAMPO_OBLIGATORIO; ?></label>
					<input class="input" type="number" name="articulo_precio_venta" pattern="[0-9.]{1,25}" maxlength="25" value="0.00">
				</div>
		  	</div>
		</div>
		
		<div class="columns">
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
		<p class="has-text-centered">
			<button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
			<button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
		</p>
		<p class="has-text-centered pt-1">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
</div>


