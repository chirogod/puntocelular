<?php
    use app\controllers\equipoController;

    $insConfig = new equipoController();

?>

<div class="container is-fluid mb-4">
	<h1 class="title">Marcas y modelos</h1>
</div>

<div class="container pb-6 is-max-desktop">
    <div class="columns">
        <div class="column">

        
            <div class="box">
                <h2 class="subtitle">Nueva marca</h2>    
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/equipoAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

                    <input type="hidden" name="modulo_equipo" value="registrar_marca">

                    <div class="columns">
                        <div class="column">
                            <div class="control">
                                <label>Marca<?php echo CAMPO_OBLIGATORIO; ?></label>
                                <input class="input" type="text" name="marca_descripcion" maxlength="40" required >
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

            <div class="box">
                <h2 class="subtitle">Nuevo modelo</h2>    
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/equipoAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

                    <input type="hidden" name="modulo_equipo" value="registrar_modelo">

                    <div class="columns">
                        <div class="column">
                            <div class="control">
                                <label>Marca a la que pertenece <?php echo CAMPO_OBLIGATORIO; ?></label><br>
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
                                <label>Modelo<?php echo CAMPO_OBLIGATORIO; ?></label>
                                <input class="input" type="text" name="modelo_descripcion" maxlength="40" required >
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
        </div>
        <div class="column">
            <?php
                echo $insConfig->listarMarcasModelosControlador();
            ?>
        </div>
    </div>
    

    
</div>