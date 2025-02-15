<?php
    $datos_sucursal = $insLogin->seleccionarDatos("Unico", "sucursal", "id_sucursal", $_SESSION['id_sucursal']);
    $datos_sucursal = $datos_sucursal->fetch();
?>
<div class="container is-fluid mb-4">
	<h1 class="title">Valores</h1>
	<h2 class="subtitle"><i class="fas fa-dollar-sign fa-fw"></i> &nbsp; Actualizar valores de la sucursal</h2>
</div>

<div class="container pb-6 is-max-desktop">
    <div class="box">
        <h2 class="subtitle">USD PUNTO CELULAR</h2>
        <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/sucursalAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data">
            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="hidden" name="modulo_sucursal" value="usd_pc">
                <input class="input" type="number" name="sucursal_usd" required value="<?php echo $datos_sucursal['sucursal_usd'] ?>">
                <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Actualizar</button>
            </div>

            <p class="has-text-centered pt-1">
                <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
            </p>
        </form>
    </div>

    <div class="box">
        <h2 class="subtitle">COSTO OPERATIVO POR HORA</h2>
        <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/sucursalAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data">
            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="hidden" name="modulo_sucursal" value="costo_operativo">
                <input class="input" type="number" name="sucursal_costo_operativo_hora" required value="<?php echo $datos_sucursal['sucursal_costo_operativo_hora'] ?>">
                <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Actualizar</button>
            </div>

            <p class="has-text-centered pt-1">
                <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
            </p>
        </form>
    </div>
	
</div>