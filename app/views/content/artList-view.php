

<div class="container is-fluid mb-5">
    <h1 class="title is-3">Gestión de Artículos</h1>
    <h2 class="subtitle is-5">
        <i class="fas fa-search fa-fw"></i> Buscar artículo
    </h2>
</div>

<div class="container is-max-desktop">
    <!-- Fila de búsqueda y filtros -->
    <div class="columns is-vcentered box is-multiline">
        <!-- Buscador -->
        <div class="column is-7">
            <div class="field">
                <label class="label">Buscar por nombre, descripción, código, marca o modelo:</label>
                <div class="control has-icons-left">
                    <input 
                        class="input is-medium" 
                        type="text" 
                        placeholder="Escriba aquí..."  
                        name="input_codigo" 
                        id="input_codigo">
                    <span class="icon is-left">
                        <i class="fas fa-keyboard"></i>
                    </span>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="column is-2">
            <div class="field">
                <label class="label">Estado:</label>
                <div class="control">
                    <div class="select is-medium is-fullwidth">
                        <select id="filter_estado">
                            <option value="">Todos</option>
                            <option value="activo" selected>Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-3">
            <div class="field">
                <label class="label">Ordenar por:</label>
                <div class="control">
                    <div class="select is-medium is-fullwidth">
                        <select id="sort_by">
                            <option value="nombre_asc">Nombre A-Z</option>
                            <option value="nombre_desc">Nombre Z-A</option>
                            <option value="stock_asc">Stock menor a mayor</option>
                            <option value="stock_desc">Stock mayor a menor</option>
                            <option value="precio_asc">Precio menor a mayor</option>
                            <option value="precio_desc">Precio mayor a menor</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de artículos -->
    <div class="box">
        <div id="tabla_articulos" class="content has-text-centered">
            <p class="has-text-grey-light">Cargando artículos...</p>
        </div>
    </div>
</div>

<script>
    const inputCodigo = document.querySelector('#input_codigo');
    const filterEstado = document.querySelector('#filter_estado');
    const sortBy = document.querySelector('#sort_by');

    const buscarCodigo = () => {
        let input_codigo = inputCodigo.value.trim();
        let estado = filterEstado.value;
        let orden = sortBy.value;

        let datos = new FormData();
        datos.append("buscar_articulo", input_codigo);
        datos.append("modulo_articulo", "buscar_articulo");
        datos.append("estado", estado);
        datos.append("orden", orden);

        fetch('<?php echo APP_URL; ?>app/ajax/articuloAjax.php', {
            method: 'POST',
            body: datos
        })
        .then(respuesta => respuesta.text())
        .then(respuesta => {
            let tabla_articulos = document.querySelector('#tabla_articulos');
            tabla_articulos.innerHTML = respuesta;
        });
    };

    // Agregar eventos a los elementos de entrada
    filterEstado.addEventListener('change', buscarCodigo);
    sortBy.addEventListener('change', buscarCodigo);
    inputCodigo.addEventListener('input', buscarCodigo);

    // Cargar artículos al iniciar
    buscarCodigo();
</script>
