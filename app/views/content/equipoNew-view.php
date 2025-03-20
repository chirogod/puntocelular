<div class="container is-fluid is-max-desktop">
	<h1 class="title">Equipos</h1>
	<h2 class="subtitle"><i class="fas fa-mobile fa-fw"></i> &nbsp; Nuevo equipo</h2>
</div>

<div class="container pb-6 pt-6 is-max-desktop">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/equipoAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_equipo" value="registrar">

		<div class="box">
			<h2 class="subtitle">Datos del equipo</h2>
			<div class="columns">
				<div class="column">
					<label for="">Modulo <?php echo CAMPO_OBLIGATORIO; ?></label>
					<div class="select">
						<select name="equipo_modulo" id="" required>
							<option>Seleccione una opción</option>
							<option value="android_nuevo">Stock Android Nuevo</option>
                            <option value="android_reac">Stock Android Reac.</option>
							<option value="iphone">Stock iPhone</option>
							<option value="android_prev">Android Preventa</option>
                            <option value="apple_nuevo_prev">Apple Nuevo Preventa</option>
                            <option value="apple_reac_prev">Apple Reac. Preventa</option>
						</select>
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Marca <?php echo CAMPO_OBLIGATORIO; ?></label><br>
						<div class="select">
							<select name="id_marca" id="select_marca" onchange="cargarModelos(this.value)" required>
								<option value="" selected="">Seleccione una opción</option>
								<?php
									// Obtener las marcas de la base de datos
									$datos_marca = $insLogin->seleccionarDatos("Normal", "marca", "*", 0);
									while ($campos_marca = $datos_marca->fetch()) {
										echo '<option value="' . $campos_marca['id_marca'] . '">' . $campos_marca['marca_descripcion'] . '</option>';
									}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Modelo <?php echo CAMPO_OBLIGATORIO; ?></label><br>
						<div class="select">
							<select name="id_modelo" id="select_modelo" required>
								<option value="" selected="">Seleccione una opción</option>
								<!-- Los modelos se llenarán aquí -->
							</select>
						</div>
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Almacenamiento <?php echo CAMPO_OBLIGATORIO; ?></label><br>
						<div class="select">
							<select name="equipo_almacenamiento" required>
								<option value="" selected="" >Seleccione una opción</option>
								<?php
									echo $insLogin->generarSelect(ALMACENAMIENTO,"VACIO");
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="columns">
				<div class="column">
					<div class="control">
						<label>Ram <?php echo CAMPO_OBLIGATORIO; ?></label><br>
						<div class="select">
							<select name="equipo_ram">
								<option value="" selected="" >Seleccione una opción</option>
								<?php
									echo $insLogin->generarSelect(RAM,"VACIO");
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="column">
					<label for="">Batería</label>
					<input class="input" type="text" name="equipo_bateria" id="">
				</div>
				<div class="column">
					<label for="">Color</label>
					<input class="input" type="text" name="equipo_color" id="">
				</div>
				<div class="column">
					<label for="">IMEI <?php echo CAMPO_OBLIGATORIO; ?></label>
					<input class="input" type="text" name="equipo_imei" id="" required>
				</div>
				<div class="column">
					<label for="">Costo <?php echo CAMPO_OBLIGATORIO; ?></label>
					<input class="input" type="number" name="equipo_costo" id="" required>
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
	document.addEventListener('DOMContentLoaded', () => {
        const moduloSelect = document.querySelector('[name="equipo_modulo"]');
        const ramField = document.querySelector('[name="equipo_ram"]').closest('.column');
        const bateriaField = document.querySelector('[name="equipo_bateria"]').closest('.column');

        const toggleFields = () => {
            const selectedValue = moduloSelect.value;

            // Ocultar/Mosstrar campos según el módulo seleccionado
            if (selectedValue === 'iphone_reac') {
                ramField.style.display = 'none';
                bateriaField.style.display = '';
            } else if (selectedValue === 'iphone' || selectedValue === 'iphone_nuevo') {
                ramField.style.display = 'none';
                bateriaField.style.display = 'none';
            } else if (
                selectedValue === 'android_prev' ||
                selectedValue === 'android_nuevo' ||
                selectedValue === 'android_reac'
            ) {
                ramField.style.display = '';
                bateriaField.style.display = 'none';
            } else {
                // Por defecto, mostrar ambos
                ramField.style.display = '';
                bateriaField.style.display = '';
            }
        };

        // Ejecutar al cargar y al cambiar el módulo
        moduloSelect.addEventListener('change', toggleFields);
        toggleFields();
    });
	function cargarModelos(marcaId) {
        const modeloSelect = document.getElementById('select_modelo');
        modeloSelect.innerHTML = '<option value="" selected="">Seleccione una opción</option>'; // Resetea el select de modelos

        if (marcaId) {
            let datos = new FormData();
            datos.append("marca_id", marcaId);
            datos.append("modulo_orden", "cargar_modelos");

            fetch('<?php echo APP_URL; ?>app/ajax/ordenAjax.php', {
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(modelos => {
                modelos.forEach(modelo => {
                    modeloSelect.innerHTML += `<option value="${modelo.id_modelo}">${modelo.modelo_descripcion}</option>`;
                });
            })
            .catch(error => {
                console.error('Error al cargar los modelos:', error);
            });
        }
    }
</script>