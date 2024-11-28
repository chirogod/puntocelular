
<div class="container is-fluid mb-2">
    <h1 class="title">Cliente</h1>
    <h2 class="subtitle"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar cliente</h2>
</div>

<div class="container is-max-desktop mt-6 ">
    <div class="columns">
        <div class="column">
        <div class="field  mt-6 mb-1">
        <label class="label">Documento, Nombre, Apellido, Teléfono</label>
        <div class="control">
            <input class="input" type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" name="input_cliente" id="input_cliente" maxlength="30" >
        </div>
    </div>
    <div class="container" id="tabla_clientes"></div>
        </div>
    </div>
</div>
    
<script>
    // Seleccionar el campo de entrada
    const inputCliente = document.querySelector('#input_cliente');
    
    // Función para manejar la búsqueda en tiempo real
    const buscarCliente = () => {
        let input_cliente = inputCliente.value;
        input_cliente = input_cliente.trim();
            let datos = new FormData();
            datos.append("buscar_cliente", input_cliente);
            datos.append("modulo_cliente", "buscar_cliente");

            fetch('<?php echo APP_URL; ?>app/ajax/clienteAjax.php', {
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.text())
            .then(respuesta => {
                let tabla_clientes = document.querySelector('#tabla_clientes');
                tabla_clientes.innerHTML = respuesta;
            });
    };

    // Agregar evento al input
    inputCliente.addEventListener('input', buscarCliente);

    // Comprobar estado inicial al cargar la página
    buscarCliente();
</script>