<div class="container is-fluid mb-4">
	<h1 class="title">Sucursales</h1>
</div>

<div class="container pb-6 is-max-desktop">
    <h2 class="subtitle">Nueva sucursal</h2>    
	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/sucursalAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

		<input type="hidden" name="modulo_sucursal" value="registrar">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Sucursal<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="sucursal_descripcion" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,100}" maxlength="100" required >
				</div>
		  	</div>
            <div class="column">
		    	<div class="control">
					<label>Teléfono<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="number" name="sucursal_telefono" pattern="[0-9()+]{8,20}">
				</div>
		  	</div>
		</div>
        
        <div class="columns">
            <div class="column">
				<div class="control">
					<label>Domicilio<?php echo CAMPO_OBLIGATORIO; ?></label>
					<input class="input" type="text" name="sucursal_direccion" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}" maxlength="70">
				</div>
			</div>
            <div class="column">
                    <div class="control">
                        <label>Localidad</label><br>
                        <div class="select">
                            <select name="sucursal_localidad">
                                <option value="" selected="" >Seleccione una opción</option>
                                <?php
                                    echo $insLogin->generarSelect(LOCALIDADES,"VACIO");
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            <div class="column">
		    	<div class="control">
					<label>Email<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="email" name="sucursal_email" >
				</div>
		  	</div>
		</div>

        <div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Pie de nota<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="sucursal_pie_nota" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]" required >
				</div>
		  	</div>
            <div class="column">
		    	<div class="control">
					<label>Pie de comprobante<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="sucursal_pie_comprobante" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]" require >
				</div>
		  	</div>
              <div class="column">
		    	<div class="control">
					<label>Firma email<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="sucursal_firma_email" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]"  require>
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
		use app\controllers\sucursalController;

		$insSucursal = new sucursalController();

		echo $insSucursal->listarSucursalControlador($url[1],15,$url[0],"");
	?>
    
</div>


