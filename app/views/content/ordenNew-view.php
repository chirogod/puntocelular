<div class="container is-fluid is-max-desktop">
	<h1 class="title">Nueva orden</h1>
</div>

<div class="container pb-6 pt-1 is-max-desktop">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_orden" value="registrar_orden">
		<div class="box">
            <h3 class="subtitle">Datos de la orden</h3>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Cliente <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <?php
                            if(isset($_SESSION['datos_cliente_orden']) && count($_SESSION['datos_cliente_orden'])>=1 && $_SESSION['datos_cliente_orden']['id_cliente']!=1){
                        ?>
                            <div class="field has-addons mb-5">
                                <div class="control">
                                    <input class="input" type="text" readonly id="orden_cliente" value="<?php echo $_SESSION['datos_cliente_orden']['cliente_nombre_completo']; ?>" >
                                </div>
                                <div class="control">
                                    <a class="button is-danger" title="Remove cliente" id="btn_remove_client" onclick="remover_cliente(<?php echo $_SESSION['datos_cliente_orden']['id_cliente']; ?>)">
                                        <i class="fas fa-user-times fa-fw"></i>
                                    </a>
                                </div>
                            </div>
                            <?php 
                                }else{
                                    $datos_cliente=$insLogin->seleccionarDatos("Normal","cliente WHERE id_cliente='1'","*",0);
                                    if($datos_cliente->rowCount()==1){
                                        $datos_cliente=$datos_cliente->fetch();

                                        $_SESSION['datos_cliente_orden']=[
                                            "id_cliente"=>$datos_cliente['id_cliente'],
                                            "cliente_tipo_doc"=>$datos_cliente['cliente_tipo_doc'],
                                            "cliente_documento"=>$datos_cliente['cliente_documento'],
                                            "cliente_nombre_completo"=>""
                                        ];

                                    }else{
                                        $_SESSION['datos_cliente_orden']=[
                                            "id_cliente"=>"",
                                            "cliente_tipo_doc"=>"N/A",
                                            "cliente_documento"=>"N/A",
                                            "cliente_nombre_completo"=>"Publico General",
                                        ];
                                    }
                            ?>
                            <div class="field has-addons mb-1">
                                <div class="control">
                                    <input class="input" type="text" readonly id="orden_cliente" value="<?php echo $_SESSION['datos_cliente_orden']['cliente_nombre_completo']; ?>" >
                                </div>
                                <div class="control">
                                    <a class="button is-info js-modal-trigger" data-target="modal-js-client" title="Agregar cliente" id="btn_add_client" >
                                        <i class="fas fa-user-plus fa-fw"></i>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                
                    <div class="control mb-1">
                        <label>Fecha <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="date" value="<?php echo date("Y-m-d"); ?>" >
                    </div>

                    <div class="control">
                        <label>Se presupuesta <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                        <div class="select">
                            <select name="orden_descripcion" onchange="ordenDescripcion()">
                                <option value="" selected="">Seleccione una opción</option>
                                <option value="Cambio modulo">Cambio modulo</option>
                                <option value="Cambio bateria">Cambio bateria</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        
                    </div>
                </div>

                <div class="column">
                    <h3>Observaciones</h3>
                    <textarea class="textarea" name="orden_observaciones" id="orden_observaciones"></textarea>
                </div>

            </div>
        </div>
        
        
        <div class="box">
            <h2 class="subtitle">Agregar equipo</h2>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Marca <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                        <div class="select">
                            <select name="id_marca" id="select_marca" onchange="cargarModelos(this.value); mostrarInputIcloud(this)">
                                <option value="" selected="">Seleccione una opción</option>
                                <?php
                                    // Obtener las marcas de la base de datos
                                    $datos_marca = $insLogin->seleccionarDatos("Normal", "marca", "*", 0);
                                    while ($campos_marca = $datos_marca->fetch()) {
                                        echo '<option value="' . $campos_marca['id_marca'] . '" data-marca="' . $campos_marca['marca_descripcion'] . '">' . $campos_marca['marca_descripcion'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control">
                        <label>Modelo <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                        <div class="select">
                            <select name="id_modelo" id="select_modelo">
                                <option value="" selected="">Seleccione una opción</option>
                                <!-- Los modelos se llenarán aquí -->
                            </select>
                        </div>
                    </div>

                    <!-- Campo oculto para la contraseña de iCloud -->
                    <div id="icloudContainer" style="display: none; margin-top: 10px;">
                        <div class="control">
                            <label for="icloudEmail">Email de iCloud:</label>
                            <input class="input" type="email" name="orden_equipo_email" id="icloudEmail">
                        </div>
                        <div class="control">
                            <label for="icloudPassword">Contrasena de iCloud:</label>
                            <input class="input" type="text" name="orden_equipo_pass" id="icloudPassword">
                        </div>
                    </div>

                    <div class="field">
                        <label class="radio">
                            <input type="radio" name="orden_equipo_otro" value="Otro" onclick="toggleOtroEquipo(this)">
                            Otro
                        </label>
                        <div class="field" id="otro_equipo_field" style="display: none;">
                            <label class="label">Marca</label>
                            <input class="input" type="text" name="orden_otra_marca">
                            <label class="label">Modelo</label>
                            <input class="input" type="text" name="orden_otro_modelo">
                        </div>
                    </div>

                    <div class="control">
                        <label>Contrasena<?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="orden_equipo_contrasena" required>
                    </div>

                    <div class="control">
                        <label>Ingreso<?php echo CAMPO_OBLIGATORIO; ?></label>
                            <div class="field">
                                <label class="radio">
                                    <input type="radio" name="orden_equipo_ingresa_encendido" value="Encendido">
                                    Encendido
                                </label>
                                <label class="radio">
                                    <input type="radio" name="orden_equipo_ingresa_encendido" value="Apagado">
                                    Apagado
                                </label>
                            </div>
                        </label>
                    </div>

                </div>
                
                

                <div class="column">
                    <div class="control">
                        <label>Detalles fisicos
                            <div class="field">
                                <textarea name="orden_equipo_detalles_fisicos" id="orden_equipo_detalles_fisicos" class="textarea"></textarea>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="column">
                    <h3>Falla/Problema <?php echo CAMPO_OBLIGATORIO; ?></h3>
                    <textarea class="textarea"  name="orden_falla" required id="orden_falla"></textarea>
                </div>

                <!-- Estilo del textarea -->
                <style>
                    .column .textarea {
                        height: 170px; /* Ajusta la altura según la altura combinada de los inputs a la izquierda */
                    }
                </style>

            </div>
        </div>
        
        <div class="box">
            <h2 class="subtitle">Detalles</h2>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <h3>Accesorios incluidos</h3>
                        <textarea class="textarea" name="orden_accesorios" id="orden_accesorios"></textarea>
                    </div>
                </div>   
                <div class="column">
                    <div class="control">
                        <label>Telefonista/Operador<?php echo CAMPO_OBLIGATORIO; ?></label><br>
                        <div class="select">
                            <select name="orden_telefonista" >
                                <option value="" selected="" >Seleccione una opción</option>
                                <?php
                                    $datos_usuario=$insLogin->seleccionarDatos("Unico","usuario","usuario_rol","Vendedor");

                                    $cc=1;
                                    while($campos_usuario=$datos_usuario->fetch()){
                                        if ($campos_usuario['usuario_rol']!="Administrador") {
                                            if($campos_usuario['id_usuario'] == $_SESSION['id_usuario']){
                                                echo '<option value="'.$campos_usuario['id_usuario'].'" selected>'.$campos_usuario['usuario_nombre_completo'].'</option>';
                                            }else{
                                                echo '<option value="'.$campos_usuario['id_usuario'].'">'.$campos_usuario['usuario_nombre_completo'].'</option>';
                                            }
                                        }
                                        
                                        $cc++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="control">
                        <label>Ubicacion fisica</label><br>
                        <div class="select">
                            <select name="orden_ubicacion_fisica" >
                                <option value="Sin Asignar" selected>Sin asignar</option>
                                <option value="Mesa Luka">Mesa Luka</option>
                                <option value="Mesa Sebastian">Mesa Sebastian</option>
                                <option value="Mesa Tomas">Mesa Tomas</option>
                                <option value="Mesa Nahuel">Mesa Nahuel</option>
                                <option value="Mesa Anabela">Mesa Anabela</option>
                                <option value="Mesa Augusto">Mesa Augusto</option>
                                <option value="Mesa PC">Mesa PC</option>
                                <option value="Verificacion">Verificacion</option>
                                <option value="Reparar">Reparar</option>
                                <option value="Esperando Rptos">Esperando Repuestos</option>
                                <option value="Presupuestado Central">Presupuestado Central</option>
                                <option value="Presupuestado San Martin">Presupuestado San Martin</option>
                                <option value="Presupuestado Chang Mas">Presupuestado Chang Mas</option>
                                <option value="Equipos Nuestros">Equipos Nuestros</option>
                                <option value="Equipos para Prestar">Equipos para Prestar</option>
                                <option value="Reparado">Presupuestado Central</option>
                                <option value="Derivar Chango Mas">Derivar Chango Mas</option>
                                <option value="Derivar San Martin">Derivar San Martin</option>
                                <option value="No Va">No Va</option>
                                <option value="No Va Tablet">No Va Tablet</option>
                                <option value="A Reparar PC">A Reparar PC</option>
                                <option value="Presupuestado PC">Presupuestado PC</option>
                                <option value="Esperando repuestos PC">Esperando repuestos PC</option>
                                <option value="Verificacion PC">Verificacion PC</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label class="label">Tipo de orden <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <div class="field">
                                <label class="radio">
                                    <input type="radio" name="orden_tipo" value="Presupuestar" onclick="toggleDateInput(true)">
                                    A presupuestar
                                </label>
                            </div>
                            
                            <div class="field">
                                <label class="radio">
                                    <input type="radio" name="orden_tipo" value="Garantia" onclick="toggleDateInput(true)">
                                    Garantia
                                </label>
                            </div>
                            
                            <div class="field">
                                <label class="radio">
                                    <input type="radio" name="orden_tipo" value="Prometida" onclick="toggleDateInput(true)">
                                    Prometida
                                </label>
                                <!-- Input de  prometida que estará oculto inicialmente -->
                                <div class="field" id="fecha_reparacion_field" style="display: none;">
                                    <label class="label">Prometido para</label>
                                    <input class="input" type="date" name="orden_fecha_prometida">
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        
        
        <div class="box">
            <h2 class="subtitle">Importes</h2>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <h3>Importe</h3>
                        <input type="number" class="input" name="orden_total_lista" id="orden_total_lista" value="0" onkeyup="calcularDctoEfectivo()">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <h3>Dcto efectivo</h3>
                        <input type="number" class="input" name="orden_total_efectivo" id="orden_total_efectivo" value="0"> 
                    </div>
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


<!-- Modal buscar cliente -->
<div class="modal" id="modal-js-client">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Buscar y agregar cliente</p>
          <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <div class="field mt-6 mb-6">
                <label class="label">Documento, Nombre, Apellido, Teléfono</label>
                <div class="control">
                    <input class="input" type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" name="input_cliente" id="input_cliente" maxlength="30" >
                </div>
            </div>
            <div class="container" id="tabla_clientes"></div>
            <div class="control">
                <a class="button is-info js-modal-trigger" data-target="modal-js-new-client" title="Agregar nuevo cliente" id="btn_add_new_client" >
                    Nuevo Cliente <i class="fas fa-user-plus fa-fw"></i>
                </a>
            </div>
        </section>
        
    </div>
</div>

<!-- Modal buscar cliente -->
<div class="modal" id="modal-js-new-client">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Agregar  nuevo cliente</p>
          <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/clienteAjax.php" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_cliente" value="registrarGuardarSesion">
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <label>Tipo de documento <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                            <div class="select">
                                <select name="cliente_tipo_doc">
                                    <option value="" selected="" >Seleccione una opción</option>
                                    <?php
                                        echo $insLogin->generarSelect(DOCUMENTOS,"VACIO");
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <label>Numero de documento <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input class="input" type="number" name="cliente_documento" pattern="[0-9]{7,30}" maxlength="30" required >
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <label>Nombre completo <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input class="input" type="text" name="cliente_nombre_completo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <label>Email</label>
                            <input class="input" type="email" name="cliente_email" maxlength="70" >
                        </div>
                    </div>
                </div>
                <div class="columns">
                        <div class="column">
                            <div class="control">
                                <label>Provincia</label><br>
                                <div class="select">
                                    <select name="cliente_provincia">
                                        <option value="" selected="" >Seleccione una opción</option>
                                        <?php
                                            echo $insLogin->generarSelect(PROVINCIAS,"VACIO");
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="control">
                                <label>Localidad</label><br>
                                <div class="select">
                                    <select name="cliente_localidad">
                                        <option value="" selected="" >Seleccione una opción</option>
                                        <?php
                                            echo $insLogin->generarSelect(LOCALIDADES,"VACIO");
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="control">
                                <label>Domicilio</label>
                                <input class="input" type="text" name="cliente_domicilio" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}" maxlength="70">
                            </div>
                        </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <label>Teléfono 1 <?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input class="input" type="text" name="cliente_telefono_1" pattern="[\d\+\(\)]{8,20}" maxlength="20" >
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <label>Teléfono 2</label>
                            <input class="input" type="text" name="cliente_telefono_2" pattern="[\d\+\(\)]{8,20}" maxlength="20" >
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <label>Pais</label>
                            <input class="input" type="text" name="cliente_pais" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,30}" maxlength="20" >
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <label>Nacimiento</label>
                            <input class="input" type="date" name="cliente_nacimiento">
                        </div>
                    </div>
                </div>
                <p class="has-text-centered">
                    <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
                    <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
                </p>
                <p class="has-text-centered">
                    <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
                </p>
            </form>
        </section>
        
    </div>
</div>

<script>

    // Agregar evento de búsqueda en tiempo real clientes
    document.querySelector('#input_cliente').addEventListener('input', function(){
        let input_cliente=document.querySelector('#input_cliente').value;

        input_cliente=input_cliente.trim();

        if(input_cliente!=""){

            let datos = new FormData();
            datos.append("buscar_cliente", input_cliente);
            datos.append("modulo_orden", "buscar_cliente");

            fetch('<?php echo APP_URL; ?>app/ajax/ordenAjax.php',{
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.text())
            .then(respuesta =>{
                let tabla_clientes=document.querySelector('#tabla_clientes');
                tabla_clientes.innerHTML=respuesta;
            });

        }else{
            let tabla_clientes=document.querySelector('#tabla_clientes');
            tabla_clientes.innerHTML='';
        }
    });

    /*----------  Buscar cliente  ----------*/
    function buscar_cliente(){
        let input_cliente=document.querySelector('#input_cliente').value;

        input_cliente=input_cliente.trim();

        if(input_cliente!=""){

            let datos = new FormData();
            datos.append("buscar_cliente", input_cliente);
            datos.append("modulo_orden", "buscar_cliente");

            fetch('<?php echo APP_URL; ?>app/ajax/ordenAjax.php',{
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.text())
            .then(respuesta =>{
                let tabla_clientes=document.querySelector('#tabla_clientes');
                tabla_clientes.innerHTML=respuesta;
            });

        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error inesperado',
                text: 'Debes de introducir el Numero de documento, Nombre, Apellido o Teléfono del cliente',
                confirmButtonText: 'Aceptar'
            });
        }
    }


    /*----------  Agregar cliente  ----------*/
    function agregar_cliente(id){

        Swal.fire({
            title: '¿Quieres agregar este cliente?',
            text: "Se va a agregar este cliente para realizar una orden",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, agregar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed){

                let datos = new FormData();
                datos.append("id_cliente", id);
                datos.append("modulo_orden", "agregar_cliente");

                fetch('<?php echo APP_URL; ?>app/ajax/ordenAjax.php',{
                    method: 'POST',
                    body: datos
                })
                .then(respuesta => respuesta.json())
                .then(respuesta =>{
                    return alertas_ajax(respuesta);
                });

            }
        });
    }


    /*----------  Remover cliente  ----------*/
    function remover_cliente(id){

        Swal.fire({
            title: '¿Quieres remover este cliente?',
            text: "Se va a quitar el cliente seleccionado de la orden",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, remover',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed){

                let datos = new FormData();
                datos.append("id_cliente", id);
                datos.append("modulo_orden", "remover_cliente");

                fetch('<?php echo APP_URL; ?>app/ajax/ordenAjax.php',{
                    method: 'POST',
                    body: datos
                })
                .then(respuesta => respuesta.json())
                .then(respuesta =>{
                    return alertas_ajax(respuesta);
                });

            }
        });
    }

    //si se marca prometido para en la fecha se activa un input date
    function toggleDateInput(show) {
        const dateField = document.getElementById('fecha_reparacion_field');
        if (show) {
            dateField.style.display = 'block';
        } else {
            dateField.style.display = 'none';
        }
    }

    function toggleOtroEquipo(radio) {
        const field = document.getElementById('otro_equipo_field');
        
        if (radio.dataset.selected === "true") {
            radio.checked = false;
            radio.dataset.selected = "false";
            field.style.display = 'none';
        } else {
            radio.dataset.selected = "true";
            field.style.display = 'block';
        }
    }

    //si se marca prometido para en la fecha se activa un input date
    function agregarRepuesto(show) {
        const dateField = document.getElementById('repuestos_field');
        if (show) {
            dateField.style.display = 'block';
        } else {
            dateField.style.display = 'none';
        }
    }


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
    function mostrarInputIcloud(select) {
        let selectedOption = select.options[select.selectedIndex];
        let marca = selectedOption.getAttribute("data-marca");
        let icloudContainer = document.getElementById("icloudContainer");

        if (marca === "Apple") {
            icloudContainer.style.display = "block";
        } else {
            icloudContainer.style.display = "none";
        }
    }

    /* CALCULAR AUTOMATICAMENTE IMPORTE EN EFECTIVO */
    function calcularDctoEfectivo(){
        const importeLista = document.getElementById('orden_total_lista').value;
        const dctoEfectivo = importeLista * 0.2;
        const finalEfectivo = importeLista - dctoEfectivo;
        document.getElementById('orden_total_efectivo').value = finalEfectivo.toFixed(2);
    }

    function ordenDescripcion(){
        const select = document.querySelector('select[name="orden_descripcion"]');
        const selectedOption = select.options[select.selectedIndex];
        const selectedValue = selectedOption.value;
        if(selectedValue === "otro"){
            const input = document.createElement("input");
            input.type = "text";
            input.name = "orden_descripcion_otro";
            input.placeholder = "Especificar otro";
            input.className = "input";
            select.parentNode.appendChild(input);
        }else{
            const input = select.parentNode.querySelector('input[name="orden_descripcion_otro"]');
            if(input){
                select.parentNode.removeChild(input);
            }
        }
    }
</script>