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

<div class="container is-fluid">
    <h1 class="title is-3">Repuestos</h1>
    <h2 class="subtitle is-5">
        <i class="fas fa-list fa-fw"></i> Lista de pedidos de repuesto
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

        <?php
        $estados = ['ingreso', 'espera', 'eliminado'];
        ?>

        <div class="column is-narrow">
            <div class="field">
                <label class="label">Estado:</label>
                <div class="control">
                    <div class="select is-small">
                        <select name="pedido_estado" id="filter_estado">
                            <option value="">Todos</option>
                            <?php foreach ($estados as $estado): ?>
                                <option value="<?php echo $estado; ?>">
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
        <div id="tabla_pedidos" class="content has-text-centered">
            <p class="has-text-grey-light">Cargando pedidos...</p>
        </div>
    </div>
</div>

<script>
    const filterSucursal = document.querySelector('#filter_sucursal');
    const filterEstado = document.querySelector('#filter_estado');
    const filterFechaDesde = document.querySelector('#filter_fechDes');
    const filterFechaHasta = document.querySelector('#filter_fechHas');

    const buscarCodigo = () => {
        let sucursal = filterSucursal.value;
        let estado = filterEstado.value;
        let desde = filterFechaDesde.value;
        let hasta =filterFechaHasta.value;

        let datos = new FormData();
        datos.append("modulo_repuesto", "buscar_pedido");
        datos.append("sucursal", sucursal);
        datos.append("estado", estado);
        datos.append("fecha_inicio", desde);
        datos.append("fecha_fin", hasta);

        fetch('<?php echo APP_URL; ?>app/ajax/repuestoAjax.php', {
            method: 'POST',
            body: datos
        })
        .then(respuesta => respuesta.text())
        .then(respuesta => {
            document.querySelector('#tabla_pedidos').innerHTML = respuesta;
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('#tabla_pedidos').innerHTML = '<p class="has-text-danger">Error al cargar las órdenes.</p>';
        });
    };

    filterSucursal.addEventListener('change', buscarCodigo);
    filterEstado.addEventListener('change', buscarCodigo);
    filterFechaDesde.addEventListener('change', buscarCodigo);
    filterFechaHasta.addEventListener('change', buscarCodigo);

    buscarCodigo();
</script>
