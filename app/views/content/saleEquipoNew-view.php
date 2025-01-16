<?php 
    $id = $insLogin->limpiarCadena($url[1]);
		$datos = $insLogin->seleccionarDatos("Unico","equipo","id_equipo",$id);
        if($datos->rowCount()==1){
			$datos=$datos->fetch();
            $id_equipo = $datos['id_equipo'];
            $total = isset($_SESSION['financiacion_equipo'][$id_equipo]) ? $_SESSION['financiacion_equipo'][$id_equipo]['venta_equipo_total'] : '0';
?>

<div class="container is-fluid mb-1">
	<h1 class="title">Venta de equipo</h1>
	<h2 class="subtitle"><i class="fas fa-cart-plus fa-fw"></i> &nbsp; Nueva venta de equipo</h2>
</div>

<div class="container pt-2 is-max-desktop">
    <div class="box">
        <h2 class="subtitle">Equipo</h2>
        <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr >
                    <th class="has-text-centered" style="border: 1px solid black;">Equipo</th>
                    <th class="has-text-centered" style="border: 1px solid black;">Almac.</th>
                    <th class="has-text-centered" style="border: 1px solid black;">RAM</th>
                    <th class="has-text-centered" style="border: 1px solid black;">Color</th>
                    <th class="has-text-centered" style="border: 1px solid black;">IMEI</th>
                    <th class="has-text-centered" style="border: 1px solid black;">F. de pago</th>
                    <th class="has-text-centered" style="border: 1px solid black;">Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $_SESSION['venta_equipo_importe'] = 0;
                    $id_equipo = $datos['id_equipo'];
                    $financiacion = isset($_SESSION['financiacion_equipo'][$id_equipo]) ? $_SESSION['financiacion_equipo'][$id_equipo]['venta_equipo_financiacion'] : 'n/a';
                    $subtotal = isset($_SESSION['financiacion_equipo'][$id_equipo]) ? $_SESSION['financiacion_equipo'][$id_equipo]['venta_equipo_total'] : 0;
                    $_SESSION['venta_equipo_importe'] += $subtotal;
                ?>
                <tr>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos['equipo_marca']. " ".  $datos['equipo_modelo']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos['equipo_almacenamiento']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos['equipo_ram']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos['equipo_color']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos['equipo_imei']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"> 
                        <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ventaEquipoAjax.php" method="POST" autocomplete="off">
                            <input type="hidden" name="id_equipo" value="<?php echo $datos['id_equipo']; ?>">
                            <input type="hidden" name="modulo_venta" value="financiar_producto">
                            <div class="select">
                                <select name="financiacion_equipo" class="select" required onchange="financiarProducto('<?php echo $datos['id_equipo']; ?>', this.value)">
                                    <option value="">Seleccionar opción</option>_equipo
                                    <option value="3cuotas" <?php echo (isset($_SESSION['financiacion_equipo'][$id_equipo]) && $_SESSION['financiacion_equipo'][$id_equipo]['venta_equipo_financiacion'] == '3cuotas') ? 'selected' : ''; ?>>3 cuotas</option>
                                    <option value="6cuotas" <?php echo (isset($_SESSION['financiacion_equipo'][$id_equipo]) && $_SESSION['financiacion_equipo'][$id_equipo]['venta_equipo_financiacion'] == '6cuotas') ? 'selected' : ''; ?>>6 cuotas</option>
                                    <option value="9cuotas" <?php echo (isset($_SESSION['financiacion_equipo'][$id_equipo]) && $_SESSION['financiacion_equipo'][$id_equipo]['venta_equipo_financiacion'] == '9cuotas') ? 'selected' : ''; ?>>9 cuotas</option>
                                    <option value="12cuotas" <?php echo (isset($_SESSION['financiacion_equipo'][$id_equipo]) && $_SESSION['financiacion_equipo'][$id_equipo]['venta_equipo_financiacion'] == '12cuotas') ? 'selected' : ''; ?>>12 cuotas</option>
                                    <option value="Efectivo" <?php echo (isset($_SESSION['financiacion_equipo'][$id_equipo]) && $_SESSION['financiacion_equipo'][$id_equipo]['venta_equipo_financiacion'] == 'Efectivo') ? 'selected' : ''; ?>>Efectivo</option>
                                </select>
                            </div>
                        </form>
                    </td>
                    <td class="has-text-centered" style="border: 1px solid black;">
                        <?php echo MONEDA_SIMBOLO.number_format($total,MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="box">
        <h2 class="subtitle">Detalle de la venta</h2>
        <div class="columns">
            <div class="column">
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ventaEquipoAjax.php" method="POST" autocomplete="off" name="formsale" >
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
                        <label>Vendedor <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                        <div class="select">
                            <select name="venta_vendedor" >
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
                    <h4 class="subtitle is-5 has-text-centered has-text-weight-bold mb-5"><small>TOTAL A PAGAR: <?php echo MONEDA_SIMBOLO.number_format($_SESSION['venta_equipo_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?></small></h4>

                    <?php if($_SESSION['venta_equipo_importe']>0){ ?>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Registrar venta</button>
                    </p>
                    <?php } ?>
                    <p class="has-text-centered pt-6">
                        <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
                    </p>
                    <input type="hidden" value="<?php echo number_format($_SESSION['venta_equipo_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,""); ?>" id="venta_importe_hidden">                            
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

    function financiarProducto(id_equipo, financiacion) {
        if (financiacion !== "") {
            let datos = new FormData();
            datos.append("id_equipo", id_equipo);
            datos.append("financiacion_equipo", financiacion);
            datos.append("modulo_venta", "financiar_producto");

            fetch('<?php echo APP_URL; ?>app/ajax/ventaEquipoAjax.php', {
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(respuesta => {
                return alertas_ajax(respuesta);
            });
        }
    }
</script>