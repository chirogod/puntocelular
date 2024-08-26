<div class="container is-fluid is-max-desktop">
	<h1 class="title">Clientes</h1>
	<h2 class="subtitle"><i class="fas fa-male fa-fw"></i> &nbsp; Nuevo cliente</h2>
</div>

<div class="container pb-6 pt-6 is-max-desktop">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/clienteAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_cliente" value="registrar">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Tipo de documento <?php echo CAMPO_OBLIGATORIO; ?></label><br>
				  	<div class="select">
					  	<select name="cliente_tipo_doc">
					    	<option value="" selected="" >Seleccione una opción</option>
	                        <?php
	                        	echo $insLogin->generarSelect(DOCUMENTOS,"VACIO");
	                        ?>
					  	</select>
					</div>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Numero de documento <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="number" name="cliente_documento" pattern="[0-9]{7,30}" maxlength="30" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre completo <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="cliente_nombre_completo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
            <div class="column">
		    	<div class="control">
					<label>Email</label>
				  	<input class="input" type="email" name="cliente_email" maxlength="70" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
                <div class="column">
                    <div class="control">
                        <label>Provincia</label><br>
                        <div class="select">
                            <select name="cliente_provincia">
                                <option value="" selected="" >Seleccione una opción</option>
                                <?php
                                    echo $insLogin->generarSelect(PROVINCIAS,"VACIO");
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
		  	</div>
		  	<div class="column">
                <div class="column">
                    <div class="control">
                        <label>Localidad</label><br>
                        <div class="select">
                            <select name="cliente_localidad">
                                <option value="" selected="" >Seleccione una opción</option>
                                <?php
                                    echo $insLogin->generarSelect(LOCALIDADES,"VACIO");
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Domicilio</label>
				  	<input class="input" type="text" name="cliente_domicilio" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}" maxlength="70">
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Teléfono 1 <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="cliente_telefono_1" pattern="[0-9()+]{8,20}" maxlength="20" >
				</div>
		  	</div>
              <div class="column">
		    	<div class="control">
					<label>Teléfono 2</label>
				  	<input class="input" type="text" name="cliente_telefono_2" pattern="[0-9()+]{8,20}" maxlength="20" >
				</div>
		  	</div>
		</div>
        <div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Pais</label>
				  	<input class="input" type="text" name="cliente_pais" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,30}" maxlength="20" >
				</div>
		  	</div>
              <div class="column">
		    	<div class="control">
					<label>Nacimiento</label>
				  	<input class="input" type="date" name="cliente_nacimiento">
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