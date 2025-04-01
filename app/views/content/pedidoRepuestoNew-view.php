<style>
    summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        background-color: #f5f5f5;
        font-weight: bold;
        font-size: 1.1rem;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.2s;
    }
    summary:hover {
        background-color: #e3e3e3;
    }
    summary::before {
        content: "➤";
        margin-right: 10px;
        transition: transform 0.3s ease;
    }
    details[open] summary::before {
        transform: rotate(90deg);
    }
    .tachado{
        text-decoration: line-through;
        color: gray;
    }
</style>
<?php
    use app\controllers\repuestoController;

    $insRepuesto = new repuestoController();
?>

<div class="container is-fluid mb-4">
	<h1 class="title">Pedido de repuestos</h1>
</div>

<div class="container pb-6 is-max-desktop">
    <div class="columns">
        <div class="column">
            <div class="box">
                <h2 class="subtitle">Nuevo pedido</h2>    
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/repuestoAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

                    <input type="hidden" name="modulo_repuesto" value="registrar_pedido">
                    <input type="hidden" name="pedido_repuesto_responsable" value="<?php echo $_SESSION['usuario_nombre'] ?>">  
                    <div class="columns">
                        <div class="column">

                            <label>Seccion <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                            <div class="select">
                                <select name="id_seccion" >
                                    <option value="" selected="" >Seleccione una opción</option>
                                    <?php
                                        $datos_seccion=$insLogin->seleccionarDatos("Normal","seccion_repuesto","*",0);

                                        $cc=1;
                                        while($campos_seccion=$datos_seccion->fetch()){
                                            echo '<option value="'.$campos_seccion['id_seccion_repuesto'].'">'.$cc.' - '.$campos_seccion['seccion_repuesto_descripcion'].'</option>';
                                            $cc++;
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="column">
                            <label>Marca <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                            <div class="select">
                                <select name="id_marca" id="select_marca" onchange="cargarModelos(this.value)">
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
                        <div class="column">
                            <label>Modelo <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                            <div class="select">
                                <select name="id_modelo" id="select_modelo">
                                    <option value="" selected="">Seleccione una opción</option>
                                    <!-- Los modelos se llenarán aquí -->
                                </select>
                            </div>
                        </div>
                        <div class="column">
                            <label>Color<?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input class="input" type="text" name="repuesto_color" maxlength="40" >
                        </div>
                        <div class="column">
                            <label>Orden<?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input class="input" type="text" name="id_orden" maxlength="40" required >
                        </div>
                    </div>  
                    
                    <div class="columns">
                        <div class="column">
                            <label class="radio">
                                <input type="radio" name="orden_equipo_otro" value="Otro" onclick="otroEquipo(true)">
                                Otra marca/modelo
                            </label>
                            <!-- Input de  prometida que estará oculto inicialmente -->
                            <div class="field" id="otro_equipo_field" style="display: none;">
                                <label class="label">Marca</label>
                                <input class="input" type="text" name="repuesto_otra_marca">
                                <label class="label">Modelo</label>
                                <input class="input" type="text" name="repuesto_otro_modelo">
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
    </div>
</div>

<div class="container is-max-desktop pb-4">
    <?php
        echo $insRepuesto->listarPedidosControlador();
    ?>
</div>

<script>    
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
    //si se marca prometido para en la fecha se activa un input date
    function otroEquipo(show) {
        const dateField = document.getElementById('otro_equipo_field');
        if (show) {
            dateField.style.display = 'block';
        } else {
            dateField.style.display = 'none';
        }
    }
</script>