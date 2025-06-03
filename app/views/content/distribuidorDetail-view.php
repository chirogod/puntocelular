<?php 
    $id = $insLogin->limpiarCadena($url[1]);
		$datos = $insLogin->seleccionarDatos("Unico","distribuidor","id_distribuidor",$id);
        if($datos->rowCount()==1){
			$datos=$datos->fetch();

?>
<div class="container is-fluid is-max-desktop">
<?php include "./app/views/includes/btn_back.php"; ?>

    
	<h1 class="title">Distribuidor</h1>
	<h2 class="subtitle"><i class="fas fa-mobile fa-fw"></i> &nbsp; <?php echo $datos['distribuidor_descripcion']?></h2>
</div>

<div class="container pb-6 pt-6 is-max-desktop">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/distribuidorAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

		<input type="hidden" name="modulo_distribuidor" value="editar">
        <input type="hidden" name="id_distribuidor" value="<?php echo $datos['id_distribuidor']; ?>">

		<div class="columns">
		  	<div class="column">
		    	<div class="control pb-3">
					<label>Distribuidor (con este nombre se mostrara en tabla)<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="distribuidor_descripcion"  maxlength="100" required value="<?php echo $datos['distribuidor_descripcion']; ?>" >
				</div>
                <div class="control pb-3">
					<label>LINK<?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="distribuidor_link" required value="<?php echo $datos['distribuidor_link']; ?>" >
				</div>
                <div class="control pb-3">
					<label>MOSTRAR<?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <div class="select">
                        <select class="select" name="distribuidor_mostrar" id="">
                            <option value="SI" <?php if($datos['distribuidor_mostrar']=="SI"){ echo "selected"; } ?>>SI</option>
                            <option value="NO" <?php if($datos['distribuidor_mostrar']=="NO"){ echo "selected"; } ?>>NO</option>
                        </select>
                    </div>
				</div>
		  	</div>
		</div>

		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Actualizar</button>
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

