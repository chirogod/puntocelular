<?php 
    $id = $insLogin->limpiarCadena($url[1]);
		$datos = $insLogin->seleccionarDatos("Unico","equipo","id_equipo",$id);
        
        if($datos->rowCount()==1){
			$datos=$datos->fetch();
            $id_equipo = $datos['id_equipo'];
            $efectivo_usd = $datos['equipo_costo'] * 1.4;
            $precio = ($efectivo_usd * USD_PC) * 1.4;
            $efectivo = $efectivo_usd * USD_PC;
            $sin_int_3 = $precio / 3;
            $sin_int_6 = $precio / 6; 
            $fijas_9 = ($efectivo *1.5) / 9;
            $fijas_12 = ($efectivo * 1.6) / 12;
            $pago1 = $efectivo *1.1;
?>

<input type="hidden" id="usd_pc" value="<?php echo USD_PC?>">

<div class="container is-fluid mb-1">
	<h1 class="title">Sena de equipo</h1>
</div>

<div class="container pt-2 is-max-desktop">
    <div class="box">
        <h2 class="subtitle">Equipo</h2>
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr >
                    <th class="has-text-centered" style="border: 1px solid black;">Modelo</th>
                    <th class="has-text-centered" style="border: 1px solid black;">Almac.</th>
                    <th class="has-text-centered" style="border: 1px solid black;">RAM</th>
                    <th class="has-text-centered" style="border: 1px solid black;">Color</th>
                    <th class="has-text-centered" style="border: 1px solid black;">IMEI</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos['equipo_descripcion']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos['equipo_almacenamiento']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos['equipo_ram']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos['equipo_color']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos['equipo_imei']?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="box">
            <div class="columns">
                <div class="column">
                    <h2 class="subtitle">Valor del Equipo</h2>
                    <table class="table is-striped is-bordered">
                        <tbody>
                            <tr>
                                <td>Precio de Lista</td>
                                <td>$<?php echo number_format($precio)?></td>
                            </tr>
                            <tr>
                                <td>3 Cuotas s/interés</td>
                                <td>$<?php echo number_format($sin_int_3)?></td>
                            </tr>
                            <tr>
                                <td>6 Cuotas s/interés</td>
                                <td>$<?php echo number_format($sin_int_6)?></td>
                            </tr>
                            <tr>
                                <td>9 Cuotas Fijas</td>
                                <td>$<?php echo number_format($fijas_9)?></td>
                            </tr>
                            <tr>
                                <td>12 Cuotas Fijas</td>
                                <td>$<?php echo number_format($fijas_12)?></td>
                            </tr>
                            <tr>
                                <td>1 Pago Crédito/Débito/Transf. Pesos</td>
                                <td>$<?php echo number_format($pago1)?></td>
                            </tr>
                            <tr>
                                <td>Precio en Efectivo</td>
                                <td>$<?php echo number_format($efectivo)?></td>
                            </tr>
                            <tr>
                                <td>Precio en Efectivo USD</td>
                                <td id="efectivo_usd"><?php echo number_format($efectivo_usd)?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="column">
                    <h2 class="subtitle">Restante a Abonar</h2>
                    <table class="table is-striped is-bordered">
                        <tbody>
                            <tr>
                                <td>Precio de Lista</td>
                                <td id="restante_lista"></td>
                            </tr>
                            <tr>
                                <td>3 Cuotas s/interés</td>
                                <td id="restante_3"></td>
                            </tr>
                            <tr>
                                <td>6 Cuotas s/interés</td>
                                <td id="restante_6"></td>
                            </tr>
                            <tr>
                                <td>9 Cuotas Fijas</td>
                                <td id="restante_9"></td>
                            </tr>
                            <tr>
                                <td>12 Cuotas Fijas</td>
                                <td id="restante_12"></td>
                            </tr>
                            <tr>
                                <td>1 Pago Crédito/Débito/Transf. Pesos</td>
                                <td id="restante_1pago"></td>
                            </tr>
                            <tr>
                                <td>Precio en Efectivo</td>
                                <td id="restante_efectivo_ars"></td>
                            </tr>
                            <tr>
                                <td>Precio en Efectivo USD</td>
                                <td id="restante_efectivo_usd"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <h2 class="subtitle">Detalles de Pago</h2>
            <table class="table is-striped is-bordered">
                <tbody>
                <tr>
                    <td><label for="sena_ars">Seña (Pesos)</label></td>
                    <td><input type="number" name="sena_ars" id="sena_ars" value="0" step="0.01"></td>
                </tr>

                <!-- Campo para la seña en dólares (USD) -->
                <tr>
                    <td><label for="sena_usd">Seña (USD)</label></td>
                    <td><input type="number" name="sena_usd" id="sena_usd" value="0" step="0.01"></td>
                </tr>

                <!-- Campo para la seña en pesos del plan canje -->
                <tr>
                    <td><label for="sena_pcp">Seña Plan Canje (Pesos)</label></td>
                    <td><input type="number" name="sena_pcp" id="sena_pcp" value="0" step="0.01"></td>
                </tr>

                <!-- Campo para la seña en dólares del plan canje -->
                <tr>
                    <td><label for="sena_pcu">Seña Plan Canje (USD)</label></td>
                    <td><input type="number" name="sena_pcu" id="sena_pcu" value="0" step="0.01"></td>
                </tr>
                </tbody>
            </table>
        </div>
    <div class="box">
        <h2 class="subtitle">Detalle de la venta</h2>
        <div class="columns">
            <div class="column">
                <form class="" action="<?php echo APP_URL; ?>app/ajax/ventaEquipoAjax.php" method="POST" autocomplete="off" name="formsale" >
                    <input type="hidden" name="modulo_venta" value="registrar_venta">
                    <input type="hidden" name="id_equipo" id="id_equipo" value="<?php echo $datos['id_equipo']?>">
                    <div class="control">
                        <label>Cliente <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <?php
                            if(isset($_SESSION['datos_cliente_venta_equipo']) && count($_SESSION['datos_cliente_venta_equipo'])>=1 && $_SESSION['datos_cliente_venta_equipo']['id_cliente']!=1){
                        ?>
                        <div class="field has-addons mb-5">
                            <div class="control">
                                <input class="input" type="text" readonly id="venta_cliente" value="<?php echo $_SESSION['datos_cliente_venta_equipo']['cliente_nombre_completo']; ?>" >
                            </div>
                            <div class="control">
                                <a class="button is-danger" title="Remove cliente" id="btn_remove_client" onclick="remover_cliente(<?php echo $_SESSION['datos_cliente_venta_equipo']['id_cliente']; ?>)">
                                    <i class="fas fa-user-times fa-fw"></i>
                                </a>
                            </div>
                        </div>
                        <?php 
                            }else{
                                $datos_cliente=$insLogin->seleccionarDatos("Normal","cliente WHERE id_cliente='1'","*",0);
                                if($datos_cliente->rowCount()==1){
                                    $datos_cliente=$datos_cliente->fetch();

                                    $_SESSION['datos_cliente_venta_equipo']=[
                                        "id_cliente"=>$datos_cliente['id_cliente'],
                                        "cliente_tipo_doc"=>$datos_cliente['cliente_tipo_doc'],
                                        "cliente_documento"=>$datos_cliente['cliente_documento'],
                                        "cliente_nombre_completo"=>$datos_cliente['cliente_nombre_completo']
                                    ];

                                }else{
                                    $_SESSION['datos_cliente_venta_equipo']=[
                                        "id_cliente"=>1,
                                        "cliente_tipo_doc"=>"N/A",
                                        "cliente_documento"=>"N/A",
                                        "cliente_nombre_completo"=>"Publico General",
                                    ];
                                }
                        ?>
                        <div class="field has-addons mb-5">
                            <div class="control">
                                <input class="input" type="text" readonly id="venta_cliente" value="<?php echo $_SESSION['datos_cliente_venta_equipo']['cliente_nombre_completo']; ?>" >
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
                        <label for="">Fecha</label>
                        <input class="input" type="date" name="venta_fecha" value="<?php echo date("Y-m-d"); ?>" >
                    </div>
                    <div class="control">
                        <label for="">Vendedor</label>
                        <input class="input" type="text" name="venta_vendedor">
                    </div>
                    
                    <p class="has-text-centered pt-6">
                        <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
                    </p>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<?php
        }else{
            include "./app/views/includes/error_alert.php";
        }
?>


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
            datos.append("modulo_venta", "buscar_cliente");

            fetch('<?php echo APP_URL; ?>app/ajax/ventaEquipoAjax.php',{
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

    /*----------  Agregar cliente  ----------*/
    function agregar_cliente(id){
        let id_equipo = document.querySelector('#id_equipo').value;
        let datos = new FormData();
        datos.append("id_cliente", id);
        datos.append("id_equipo", id_equipo);
        datos.append("modulo_venta", "agregar_cliente");

        fetch('<?php echo APP_URL; ?>app/ajax/ventaEquipoAjax.php',{
            method: 'POST',
            body: datos
        })
        .then(respuesta => respuesta.json())
        .then(respuesta =>{
            return alertas_ajax(respuesta);
        });
    }   

    /*----------  Remover cliente  ----------*/
    function remover_cliente(id){
        Swal.fire({
            title: '¿Quieres remover este cliente?',
            text: "Se va a quitar el cliente seleccionado de la venta",
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
                datos.append("modulo_venta", "remover_cliente");

                fetch('<?php echo APP_URL; ?>app/ajax/ventaEquipoAjax.php',{
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

    const usd_pc = document.getElementById("usd_pc").value;

    // Función para actualizar el precio restante a abonar
    function actPrecioVenta() {
        // Obtener valores de las señas (en pesos y USD)
        let sena_ars = parseFloat(document.querySelector('#sena_ars').value) || 0;
        let sena_usd = parseFloat(document.querySelector('#sena_usd').value) || 0;
        let sena_pcp = parseFloat(document.querySelector('#sena_pcp').value) || 0;
        let sena_pcu = parseFloat(document.querySelector('#sena_pcu').value) || 0;

        let sena_ars_en_usd = sena_ars/usd_pc;

        let sena_pcp_en_usd = sena_pcp/usd_pc;

        // Obtener el precio en efectivo USD desde el PHP
        let efectivo_usd = parseFloat(document.querySelector('#efectivo_usd').textContent.replace(/,/g, '')) || 0;


        // Calcular el restante
        let restante = sena_ars_en_usd + sena_usd + sena_pcp_en_usd + sena_pcu;
        efectivo_usd -= restante;

        // Actualizar el campo "restante a abonar"
        document.querySelector('#restante_efectivo_usd').textContent = '$' + number_format(efectivo_usd.toFixed(2));



        let precio = efectivo_usd * 1.4 * usd_pc;
        let efectivo = precio * 0.75;
        let sin_int_3 = precio / 3;
        let sin_int_6 = precio / 6; 
        let fijas_9 = (efectivo_usd * 1.5 * usd_pc) / 9;
        let fijas_12 = (efectivo_usd * 1.6 * usd_pc) / 12;

        document.getElementById("restante_lista").textContent = '$' + number_format(precio.toFixed(2));
        document.getElementById("restante_3").textContent = '$' + number_format(sin_int_3.toFixed(2));
        document.getElementById("restante_6").textContent = '$' + number_format(sin_int_6.toFixed(2));
        document.getElementById("restante_9").textContent = '$' + number_format(fijas_9.toFixed(2));
        document.getElementById("restante_12").textContent = '$' + number_format(fijas_12.toFixed(2));
        document.getElementById("restante_1pago").textContent = '$' + number_format();
        document.getElementById("restante_efectivo_ars").textContent = '$' + number_format(efectivo);


    }

    // Función para formatear números con separadores de miles
    function number_format(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Evento que se dispara cada vez que el usuario escribe en los campos de seña
    document.querySelectorAll('input[name="sena_ars"], input[name="sena_usd"], input[name="sena_pcp"], input[name="sena_pcu"]').forEach(input => {
        input.addEventListener('input', actPrecioVenta);
    });
    
    
</script>