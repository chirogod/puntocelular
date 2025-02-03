
<div class="container is-fluid is-max-desktop">
	<h1 class="title">Caja</h1>
	<h2 class="subtitle"><i class="fas fa-male fa-fw"></i> &nbsp; Ingreso de dinero</h2>
</div>

<div class="container pb-6 pt-6 is-max-desktop">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/cajaAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_caja" value="registrar">

		<div class="columns">
		  	<div class="column">
                <div class="control">
                    <label>Fecha</label>
                    <input class="input" name="fecha_caja_movimiento" type="date" value="<?php echo date("Y-m-d"); ?>" >
                </div>
		  	</div>
			<div class="column">
		  		<label>Accion</label><br>
				<div class="select">
				  	<select name="tipo_movimiento">
                        <option value="Ingreso" selected>Ingreso</option>
						<option value="Egreso">Egreso</option>
				  	</select>
				</div>
		  	</div>  
		  	
		</div>

        <div class="columns">
			<div class="column">
		    	<div class="control">
					<label> Importe <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <input class="input" type="number" name="importe_movimiento" pattern="[0-9.]{1,25}" maxlength="25" value="0.00">
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Detalle</label>
				  	<input class="input" type="text" name="detalle_movimiento">
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
		use app\controllers\cajaController;

		$insCaja = new cajaController();

		echo $insCaja->listarCajaControlador($url[1],15,$url[0],"");
	?>
</div>

<script>
    // Agregar un evento que escuche la respuesta del servidor
	document.querySelector('form.FormularioAjax').addEventListener('submit', function(event) {
		event.preventDefault();
		var datos = new FormData(this);
		fetch('<?php echo APP_URL; ?>app/ajax/cajaAjax.php', {
			method: 'POST',
			body: datos
		})
		.then(respuesta => respuesta.json())
		.then(respuesta => {
			// Mostrar la alerta de confirmación
			Swal.fire({
				title: '¿Estás seguro?',
				text: "¿Estás seguro de que deseas guardar el movimiento?",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Si, guardar',
				cancelButtonText: 'No, cancelar'
			}).then((result) => {
				// Recargar la página después de confirmar
				if (result.isConfirmed) {
					window.location.reload();
				}
			});
		});
	});
</script>