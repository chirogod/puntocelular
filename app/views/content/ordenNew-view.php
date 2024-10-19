<div class="container is-fluid is-max-desktop">
	<h1 class="title">Ordenes</h1>
	<h2 class="subtitle"><i class="fas fa-male fa-fw"></i> &nbsp; Nueva orden</h2>
</div>

<div class="container pb-6 pt-6 is-max-desktop">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_orden" value="registrar_orden">
		
        <h3 class="subtitle">Datos</h3>
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
                                        "cliente_nombre_completo"=>$datos_cliente['cliente_nombre_completo']
                                    ];

                                }else{
                                    $_SESSION['datos_cliente_orden']=[
                                        "id_cliente"=>1,
                                        "cliente_tipo_doc"=>"N/A",
                                        "cliente_documento"=>"N/A",
                                        "cliente_nombre_completo"=>"Publico General",
                                    ];
                                }
                        ?>
                        <div class="field has-addons mb-5">
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
            
                <div class="control">
                    <label>Fecha <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="date" value="<?php echo date("Y-m-d"); ?>" >
                </div>
            </div>

            <div class="column">
                <h3>Observaciones</h3>
                <textarea class="textarea"  name="orden_observaciones" id=""></textarea>
            </div>

        </div>

        <div class="columns">
            
        </div>
        
        <h2 class="subtitle">Agregar equipo</h2>
		<div class="columns">
            <div class="column">
                <div class="control">
                    <label>Marca <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <div class="select">
                        <select name="id_marca" >
                            <option value="" selected="" >Seleccione una opción</option>
                            <?php
                                $datos_marca=$insLogin->seleccionarDatos("Normal","marca","*",0);

                                $cc=1;
                                while($campos_marca=$datos_marca->fetch()){
                                    echo '<option value="'.$campos_marca['id_marca'].'">'.$cc.' - '.$campos_marca['marca_descripcion'].'</option>';
                                    $cc++;
                                }
                            ?>
                        </select>
                    </div>
                </div>
            
                <div class="control">
                    <label>Modelo <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <div class="select">
                        <select name="id_modelo" >
                            <option value="" selected="" >Seleccione una opción</option>
                            <?php
                                $datos_modelo=$insLogin->seleccionarDatos("Normal","modelo","*",0);

                                $cc=1;
                                while($campos_modelo=$datos_modelo->fetch()){
                                    echo '<option value="'.$campos_modelo['id_modelo'].'">'.$cc.' - '.$campos_modelo['modelo_descripcion'].'</option>';
                                    $cc++;
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="control">
                    <label>N° Serie</label>
                    <input class="input" type="text" name="orden_serie_equipo">
                </div>
		  	</div>
            
            

            <div class="column">
                <div class="control">
                    <label class="label">Equipo ingresa encendido</label>
                        <div class="field">
                            <label class="radio">
                                <input type="radio" name="orden_equipo_ingresa_encendido" value="Si">
                                Si
                            </label>
                            <label class="radio">
                                <input type="radio" name="orden_equipo_ingresa_encendido" value="No">
                                No
                            </label>
                        </div>
                    </label>
                    <label for="" class="label">El equipo tiene detalles fisicos?
                        <div class="field">
                            <textarea name="orden_equipo_detalles_fisicos" id=""></textarea>
                        </div>
                    </label>    
                    <label for="" class="label">Contrasena del equipo
                        <div class="field">
                            <input class="input" type="text" name="orden_equipo_contrasena" id="">
                        </div>
                    </label> 
                </div>
            </div>

            <div class="column">
                <h3>Falla/Problema</h3>
                <textarea class="textarea"  name="orden_falla" id=""></textarea>
            </div>

            <!-- Estilo del textarea -->
            <style>
                .column .textarea {
                    height: 170px; /* Ajusta la altura según la altura combinada de los inputs a la izquierda */
                }
            </style>

		</div>

        <h2 class="subtitle">Detalles</h2>
		<div class="columns">
			<div class="column">
                <div class="control">
                    <h3>Accesorios incluidos</h3>
                    <textarea class="input" name="orden_accesorios" id=""></textarea>
                </div>
                <div class="control">
                    <label>Tecnico asignado <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <div class="select">
                        <select name="id_tecnico" >
                            <option value="" selected="" >Seleccione una opción</option>
                            <?php
                                $datos_tecnico=$insLogin->seleccionarDatos("Normal","tecnico","*",0);

                                $cc=1;
                                while($campos_tecnico=$datos_tecnico->fetch()){
                                    echo '<option value="'.$campos_tecnico['id_tecnico'].'">'.$cc.' - '.$campos_tecnico['tecnico_descripcion'].'</option>';
                                    $cc++;
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="control">
                    <label>Telefonista <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                    <div class="select">
                        <select name="orden_telefonista" >
                            <option value="" selected="" >Seleccione una opción</option>
                            <?php
                                $datos_usuario=$insLogin->seleccionarDatos("Normal","usuario","*",0);

                                $cc=1;
                                while($campos_usuario=$datos_usuario->fetch()){
                                    echo '<option value="'.$campos_usuario['id_usuario'].'">'.$cc.' - '.$campos_usuario['usuario_nombre_completo'].'</option>';
                                    $cc++;
                                }
                            ?>
                        </select>
                    </div>
                </div>
                
            </div>
            <div class="column">
                <div class="control">
                    <label class="label">Tipo de orden</label>
                        <div class="field">
                            <label class="radio">
                                <input type="radio" name="orden_tipo" value="presupuestar">
                                A presupuestar
                            </label>
                        </div>
                        
                        <div class="field">
                            <label class="radio">
                                <input type="radio" name="orden_tipo" value="garantia">
                                Garantia
                            </label>
                        </div>
                        
                        <div class="field">
                            <label class="radio">
                                <input type="radio" name="orden_tipo" value="prometida" onclick="toggleDateInput(true)">
                                Reparación prometido para:
                            </label>
                            <!-- Input de  prometida que estará oculto inicialmente -->
                            <div class="field" id="fecha_reparacion_field" style="display: none;">
                                <label class="label">Fecha Prometida</label>
                                <input class="input" type="date" name="orden_fecha_prometida">
                            </div>
                        </div>
                    </label>
                </div>
            </div>
		</div>
        
        <div class="columns">
            <div class="column">
                <div class="control">
                    <h3>Importe lista</h3>
                    <input type="text" class="input" name="orden_importe_lista" id="">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <h3>Importe efectivo</h3>
                    <input type="text" class="input" name="orden_importe_efectivo" id="">
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
            <p class="has-text-centered">
                <button type="button" class="button is-link is-light" onclick="buscar_cliente()" ><i class="fas fa-search"></i> &nbsp; Buscar</button>
            </p>
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
</script>