<?php include "app/views/includes/admin_security.php"; ?>
<div class="container is-fluid mb-2">
    <h1 class="title">Articulo</h1>
    <h2 class="subtitle"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar articulo</h2>
</div>
<div class="container is-max-desktop mt-6 ">
    <div class="columns">
        <div class="column">
        <div class="field  mt-6 mb-1">
        <label class="label">Nombre, descripcion, codigo, marca, modelo.</label>
        <div class="control">
            <input class="input" type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" name="input_codigo" id="input_codigo" maxlength="30" >
        </div>
    </div>
    <div class="container" id="tabla_articulos"></div>
        </div>
    </div>
</div>


<script>
    // Seleccionar el campo de entrada
    const inputCodigo = document.querySelector('#input_codigo');
    
    // Función para manejar la búsqueda en tiempo real
    const buscarCodigo = () => {
        let input_codigo = inputCodigo.value;
        input_codigo = input_codigo.trim();
            let datos = new FormData();
            datos.append("buscar_articulo", input_codigo);
            datos.append("modulo_articulo", "buscar_articulo");

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

    // Agregar evento al input
    inputCodigo.addEventListener('input', buscarCodigo);

    // Comprobar estado inicial al cargar la página
    buscarCodigo();
    
</script>