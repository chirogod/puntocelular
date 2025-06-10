


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
                <label class="label">Sucursal:</label>
                <div class="control">
                    <div class="select is-small">
                        <select id="filter_sucursal">
							<option value="1">Central</option>
                            <option value="2">San Martin</option>
							<option value="3">Chango Mas</option>
						</select>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-narrow">
            <div class="field">
                <label class="label">Lista:</label>
                <div class="control">
                    <div class="select is-small">
                        <select id="filter_modulo">
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
    const filterSucursal = document.querySelector('#filter_sucursal');

    const buscarCodigo = () => {
        let estado = filterEstado.value;
        let modulo = filterModulo.value;
        let precio = filterPrecio.value;
        let sucursal = filterSucursal.value;

        let datos = new FormData();
        datos.append("modulo_equipo", "buscar_equipo");
        datos.append("estado", estado);
        datos.append("modulo", modulo);
        datos.append("precio", precio);
        datos.append("sucursal", sucursal);

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
    filterSucursal.addEventListener('change', buscarCodigo);

    // Cargar artículos al iniciar
    buscarCodigo();
</script>
