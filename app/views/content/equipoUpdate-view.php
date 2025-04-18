<?php 
    $id = $insLogin->limpiarCadena($url[1]);
		$datos = $insLogin->seleccionarDatos("Unico","equipo","id_equipo",$id);
        if($datos->rowCount()==1){
			$datos=$datos->fetch();

?>
<div class="container is-fluid is-max-desktop">
<?php include "./app/views/includes/btn_back.php"; ?>

    
	<h1 class="title">Equipos</h1>
	<h2 class="subtitle"><i class="fas fa-mobile fa-fw"></i> &nbsp; Nuevo equipo</h2>
</div>

<div class="container pb-6 pt-6 is-max-desktop">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/equipoAjax.php" method="POST" autocomplete="off" >
        

		<input type="hidden" name="modulo_equipo" value="actualizar">
        <input type="hidden" name="equipo_codigo" value="<?php echo $datos['equipo_codigo']?>">

		<div class="box">
			<h2 class="subtitle">Datos del equipo</h2>
			<div class="columns">
				<div class="column">
					<label for="">Marca</label>
					<input readonly class="input" type="text" name="equipo_marca" value="<?php echo $datos['equipo_marca']?>">
				</div>
				<div class="column">
					<label for="">Modelo</label>
					<input readonly class="input" type="text" name="equipo_modelo" value="<?php echo $datos['equipo_modelo']?>">
				</div>
				<div class="column">
					<label for="">Almacenamiento</label>
					<input class="input" type="text" name="equipo_almacenamiento" value="<?php echo $datos['equipo_almacenamiento']?>">
				</div>
				<div class="column">
					<label for="">Ram</label>
					<input class="input" type="text" name="equipo_ram" value="<?php echo $datos['equipo_ram']?>">
				</div>
				<div class="column">
					<label for="">Modulo</label>
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
			</div>
			<div class="columns">
				
				<div class="column">
					<label for="">Color</label>
					<input class="input" type="text" name="equipo_color" value="<?php echo $datos['equipo_color']?>">
				</div>
				<div class="column">
					<label for="">IMEI</label>
					<input class="input" type="text" name="equipo_imei" value="<?php echo $datos['equipo_imei']?>">
				</div>
				<div class="column">
					<label for="">Costo</label>
					<input class="input" type="number" name="equipo_costo" value="<?php echo $datos['equipo_costo']?>">
				</div>
                <div class="column">
                    <label for="">Estado</label>
                    <div class="select">
                        <select name="equipo_estado" id="">
                            <option>Seleccione una opción</option>
                            <option value="Disponible" <?php if($datos['equipo_estado'] == "Disponible"){?>selected<?php } ?>>Disponible</option>
                            <option value="Reservado" <?php if($datos['equipo_estado'] == "Reservado"){?>selected<?php } ?>>Reservado</option>
                            <option value="Vendido" <?php if($datos['equipo_estado'] == "Vendido"){?>selected<?php } ?>>Vendido</option>
							<option value="Pausado" <?php if($datos['equipo_estado'] == "Pausado"){?>selected<?php } ?>>Pausado</option>
                        </select>
                    </div>
					
				</div>
			</div>
		</div>
		
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">
				<i class="far fa-save"></i> &nbsp; Actualizar
			</button>
			<?php if ($datos['equipo_estado'] == "Vendido") { ?>
				<button type="button" class="button is-link is-rounded" 
						onclick="window.location.href='<?php echo APP_URL . 'saleEquipoDetail/' . $datos['id_equipo']; ?>'">
					<i class="fas fa-shopping-cart"></i> Detalle venta
				</button>
			<?php } ?>
		</p>
		<p class="has-text-centered pt-1">
			<small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
		</p>

	</form>
</div>
<?php
	}else{
		include "./app/views/includes/error_alert.php";
	}
?>

