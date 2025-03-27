

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
        <div class="column is-4">
            <div class="field">
                <label class="label is-small">Buscar:</label>
                <div class="control has-icons-left">
                    <input 
                        class="input is-small" 
                        type="text" 
                        placeholder="Nombre, código, marca..."  
                        name="input_codigo" 
                        id="input_codigo">
                    <span class="icon is-small is-left">
                        <i class="fas fa-keyboard"></i>
                    </span>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="column is-2">
            <div class="field">
                <label class="label is-small">Estado:</label>
                <div class="control">
                    <div class="select is-small is-fullwidth">
                        <select id="filter_estado">
                            <option value="">Todos</option>
                            <option value="activo" selected>Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="column is-2">
            <div class="field">
                <label class="label is-small">Ordenar:</label>
                <div class="control">
                    <div class="select is-small is-fullwidth">
                        <select id="sort_by">
                            <option value="nombre_asc">A-Z</option>
                            <option value="nombre_desc">Z-A</option>
                            <option value="stock_asc">Stock ↑</option>
                            <option value="stock_desc">Stock ↓</option>
                            <option value="precio_asc">Precio ↑</option>
                            <option value="precio_desc">Precio ↓</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="column is-2">
            <div class="field">
                <label class="label is-small">Sucursal:</label>
                <div class="control">
                    <div class="select is-small is-fullwidth">
                        <select name="id_sucursal" id="filter_sucursal" required>
                            <?php
                            $datos_sucursal = $insLogin->seleccionarDatos("Normal", "sucursal", "*", 0);

                            while ($campos_sucursal = $datos_sucursal->fetch()) {
                                $selected = ($campos_sucursal['id_sucursal'] == $_SESSION['id_sucursal']) ? "selected" : "";
                                echo '<option ' . $selected . ' value="' . $campos_sucursal['id_sucursal'] . '">' . $campos_sucursal['sucursal_descripcion'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="column is-2">
            <div class="field">
                <label class="label is-small">Stock:</label>
                <div class="control">
                    <div class="select is-small is-fullwidth">
                        <select name="stock" id="filter_stock" required>
                            <option value="normal">Normal</option>
                            <option value="critico">Critico</option>
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
    document.addEventListener("DOMContentLoaded", () => {
        const inputCodigo = document.querySelector("#input_codigo");
        const filterEstado = document.querySelector("#filter_estado");
        const sortBy = document.querySelector("#sort_by");
        const filterSucursal = document.querySelector("#filter_sucursal");
        const filterStock = document.querySelector("#filter_stock");
        const tablaArticulos = document.querySelector("#tabla_articulos");

        let timeout = null; // Para evitar múltiples peticiones al escribir

        const buscarCodigo = () => {
            clearTimeout(timeout); // Reinicia el temporizador
            timeout = setTimeout(() => {
                let datos = new FormData();
                datos.append("buscar_articulo", inputCodigo.value.trim());
                datos.append("modulo_articulo", "buscar_articulo");
                datos.append("estado", filterEstado.value);
                datos.append("orden", sortBy.value);
                datos.append("sucursal", filterSucursal.value);
                datos.append("stock", filterStock.value);

                fetch("<?php echo APP_URL; ?>app/ajax/articuloAjax.php", {
                    method: "POST",
                    body: datos
                })
                .then(respuesta => {
                    if (!respuesta.ok) {
                        throw new Error("Error en la petición");
                    }
                    return respuesta.text();
                })
                .then(html => {
                    tablaArticulos.innerHTML = html;
                })
                .catch(error => {
                    tablaArticulos.innerHTML = `<p class="has-text-danger">Error al cargar los artículos.</p>`;
                    console.error("Error en la búsqueda:", error);
                });
            }, 300); // Retraso de 300ms
        };

        // Agregar eventos a los elementos de entrada
        filterEstado.addEventListener("change", buscarCodigo);
        sortBy.addEventListener("change", buscarCodigo);
        inputCodigo.addEventListener("input", buscarCodigo);
        filterSucursal.addEventListener("change", buscarCodigo);
        filterStock.addEventListener("change", buscarCodigo);

        // Cargar artículos al iniciar
        buscarCodigo();
    });

</script>
