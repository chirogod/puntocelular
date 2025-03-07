<?php
$disabled = "";
if ($_SESSION['usuario_rol'] != "Administrador") {
    $disabled = "disabled";
}

$estadoDisabled = "";
$estadoSeleccionado = "";
if ($_SESSION['usuario_rol'] == "Verificacion") {
    $estadoDisabled = "disabled";
    $estadoSeleccionado = "aceptada";
}
?>

<div class="container is-fluid">
    <h1 class="title is-3">Control</h1>
    <h2 class="subtitle is-5">
        <i class="fas fa-list fa-fw"></i> Control de equipos
    </h2>
</div>

<div class="container is-fluid mt-1">
    <div class="columns is-vcentered is-multiline">
        <div class="column is-narrow">
            <div class="field">
                <label class="label">Sucursal:</label>
                <div class="control">
                    <div class="select is-small">
                        <select name="id_sucursal" id="filter_sucursal" required>
                            <option value="" selected>General</option>
                            <?php
                            $datos_sucursal = $insLogin->seleccionarDatos("Normal", "sucursal", "*", 0);
                            while ($campos_sucursal = $datos_sucursal->fetch()) {
                                echo '<option value="' . $campos_sucursal['id_sucursal'] . '">' . $campos_sucursal['sucursal_descripcion'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="column is-narrow">
            <div class="field">
                <label class="label">Técnico:</label>
                <div class="control">
                    <div class="select is-small">
                        <select name="id_usuario" id="filter_tecnico" <?php echo $disabled; ?> required>
                            <option value="" selected>General</option>
                            <?php
                            $datos_tecnico = $insLogin->seleccionarDatos("Unico", "usuario", "usuario_rol", "Tecnico");
                            while ($campos_tecnico = $datos_tecnico->fetch()) {
                                $selected = ($campos_tecnico['usuario_rol'] == $_SESSION['usuario_rol']) ? 'selected' : '';
                                echo '<option value="' . $campos_tecnico['id_usuario'] . '" ' . $selected . '>' . $campos_tecnico['usuario_nombre_completo'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        

        <?php
        $estados = ['aceptada', 'no-aceptada', 'en-espera', 'sin-reparacion', 'reparada', 'entregada'];
        ?>

        <div class="column is-narrow">
            <div class="field">
                <label class="label">Estado:</label>
                <div class="control">
                    <div class="select is-small">
                        <select name="orden_estado" id="filter_estado" required <?php echo $estadoDisabled; ?>>
                            <option value="" <?php echo empty($estadoSeleccionado) ? 'selected' : ''; ?>>Todos</option>
                            <?php foreach ($estados as $estado): ?>
                                <option value="<?php echo $estado; ?>" <?php echo ($estado == $estadoSeleccionado) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst(str_replace('-', ' ', $estado)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="column is-narrow">
            <div class="field">
                <label class="label">Fecha:</label>
                <div class="control">
                    <div class="is-small">
                        Desde:<input type="date" id="filter_fechDes"> Hasta:<input type="date" id="filter_fechHas">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <div id="tabla_ordenes" class="content has-text-centered">
            <p class="has-text-grey-light">Cargando órdenes...</p>
        </div>
    </div>
</div>

<script>
    const filterSucursal = document.querySelector('#filter_sucursal');
    const filterTecnico = document.querySelector('#filter_tecnico');
    const filterEstado = document.querySelector('#filter_estado');
    const filterFechaDesde = document.querySelector('#filter_fechDes');
    const filterFechaHasta = document.querySelector('#filter_fechHas');

    const buscarCodigo = () => {
        let sucursal = filterSucursal.value;
        let tecnico = filterTecnico.value;
        let estado = filterEstado.value;
        let desde = filterFechaDesde.value;
        let hasta =filterFechaHasta.value;

        let datos = new FormData();
        datos.append("modulo_orden", "buscar_orden");
        datos.append("sucursal", sucursal);
        datos.append("tecnico", tecnico);
        datos.append("estado", estado);
        datos.append("fecha_inicio", desde);
        datos.append("fecha_fin", hasta);

        fetch('<?php echo APP_URL; ?>app/ajax/ordenAjax.php', {
            method: 'POST',
            body: datos
        })
        .then(respuesta => respuesta.text())
        .then(respuesta => {
            document.querySelector('#tabla_ordenes').innerHTML = respuesta;
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('#tabla_ordenes').innerHTML = '<p class="has-text-danger">Error al cargar las órdenes.</p>';
        });
    };

    filterSucursal.addEventListener('change', buscarCodigo);
    filterTecnico.addEventListener('change', buscarCodigo);
    filterEstado.addEventListener('change', buscarCodigo);
    filterFechaDesde.addEventListener('change', buscarCodigo);
    filterFechaHasta.addEventListener('change', buscarCodigo);

    buscarCodigo();
</script>
