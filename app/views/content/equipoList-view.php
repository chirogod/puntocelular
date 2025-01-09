<?php include "app/views/includes/admin_security.php"; ?>


<div class="container is-fluid">
    <h1 class="title is-3">Equipos</h1>
    <h2 class="subtitle is-5">
        <i class="fas fa-list fa-fw"></i> Lista de equipos
    </h2>
</div>

<div class="container p-6">
    <div class="columns is-vcentered is-multiline">
        <!-- Filtros -->
        <div class="column is-narrow">
            <div class="field">
                <label class="label">Lista:</label>
                <div class="control">
                    <div class="select is-small">
                        <select id="filter_modulo">
                            <option value="android_nuevo" selected>Android Nuevo</option>
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
        </div>
        <div class="column is-narrow">
            <div class="field">
                <label class="label">Estado:</label>
                <div class="control">
                    <div class="select is-small">
                        <select id="filter_estado">
                            <option value="Todos" selected>Todos</option>
                            <option value="Disponible">Disponible</option>
                            <option value="Reservado">Reservado</option>
                            <option value="Vendido">Vendido</option>
                            <option value="Pausado">Pausado</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-narrow">
            <div class="field">
                <label class="label">Precio:</label>
                <div class="control">
                    <div class="select is-small">
                        <select id="filter_precio">
                            <option value="todos" selected>Todos</option>
                            <option value="menor2mayor">Menor a mayor</option>
                            <option value="mayor2menor">Mayor a menor</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de artículos -->
    <div class="box">
        <div id="tabla_equipos" class="content has-text-centered">
            <p class="has-text-grey-light">Cargando equipos...</p>
        </div>
    </div>
</div>

<script>
    const filterEstado = document.querySelector('#filter_estado');
    const filterModulo = document.querySelector('#filter_modulo');
    const filterPrecio = document.querySelector('#filter_precio');

    const buscarCodigo = () => {
        let estado = filterEstado.value;
        let modulo = filterModulo.value;
        let precio = filterPrecio.value;

        let datos = new FormData();
        datos.append("modulo_equipo", "buscar_equipo");
        datos.append("estado", estado);
        datos.append("modulo", modulo);
        datos.append("precio", precio);

        fetch('<?php echo APP_URL; ?>app/ajax/equipoAjax.php', {
            method: 'POST',
            body: datos
        })
        .then(respuesta => respuesta.text())
        .then(respuesta => {
            let tabla_equipos = document.querySelector('#tabla_equipos');
            tabla_equipos.innerHTML = respuesta;
        });
    };

    // Agregar eventos a los elementos de entrada
    filterEstado.addEventListener('change', buscarCodigo);
    filterModulo.addEventListener('change', buscarCodigo);
    filterPrecio.addEventListener('change', buscarCodigo);

    // Cargar artículos al iniciar
    buscarCodigo();
</script>
