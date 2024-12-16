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
					<label for="">Equipo</label>
					<input class="input" type="text" name="equipo_descripcion" id="">
				</div>
				<div class="column">
					<label for="">Almacenamiento</label>
					<input class="input" type="text" name="equipo_almacenamiento" id="">
				</div>
				<div class="column">
					<label for="">Ram</label>
					<input class="input" type="text" name="equipo_ram" id="">
				</div>
				<div class="column">
					<label for="">Modulo</label>
					<div class="select">
						<select name="equipo_modulo" id="">
							<option>Seleccione una opción</option>
							<option value="android_nuevo">Android Nuevo</option>
                            <option value="iphone_nuevo">Iphone nuevo</option>
                            <option value="android_reac">Android reac</option>
                            <option value="iphone_reac">Iphone reac</option>
                            <option value="android">Android</option>
                            <option value="iphone">Iphone</option>
                            <option value="Prestamo">Prestamo</option>
						</select>
					</div>
				</div>
			</div>
			<div class="columns">
				
				<div class="column">
					<label for="">Color</label>
					<input class="input" type="text" name="equipo_color" id="">
				</div>
				<div class="column">
					<label for="">IMEI</label>
					<input class="input" type="text" name="equipo_imei" id="">
				</div>
				<div class="column">
					<label for="">Costo</label>
					<input class="input" type="number" name="equipo_costo" id="">
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
