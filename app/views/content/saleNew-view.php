<div class="container is-fluid mb-1">
	<h1 class="title">Ventas</h1>
	<h2 class="subtitle"><i class="fas fa-cart-plus fa-fw"></i> &nbsp; Nueva venta</h2>
</div>

<div class="container pb-6 is-max-desktop">
    <?php
        $check_empresa=$insLogin->seleccionarDatos("Normal","sucursal LIMIT 1","*",0);

        if($check_empresa->rowCount()==1){
            $check_empresa=$check_empresa->fetch();
    ?>

    <div class="container">
        <div class="column pb-6">
            <form class="FormularioAjax pt-6 pb-6" id="sale-barcode-form" autocomplete="off">
                <div class="columns">
                    <div class="column is-one-quarter">
                        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-product" ><i class="fas fa-search"></i> &nbsp; Buscar producto</button>
                    </div>
                    <div class="column">
                        <div class="field is-grouped">
                            <p class="control is-expanded">
                                <input class="input" type="text" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70"  autofocus="autofocus" placeholder="Código de barras" id="sale-barcode-input" >
                            </p>
                            <a class="control">
                                <button type="submit" class="button is-info">
                                    <i class="far fa-check-circle"></i> &nbsp; Agregar producto
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            <?php
                if(isset($_SESSION['alerta_producto_agregado']) && $_SESSION['alerta_producto_agregado']!=""){
                    echo '
                    <div class="notification is-success is-light">
                      '.$_SESSION['alerta_producto_agregado'].'
                    </div>
                    ';
                    unset($_SESSION['alerta_producto_agregado']);
                }

                if(isset($_SESSION['venta_codigo_factura']) && $_SESSION['venta_codigo_factura']!=""){
            ?>
            <div class="notification is-info is-light mb-2 mt-2">
                <h4 class="has-text-centered has-text-weight-bold">Venta realizada</h4>
                <p class="has-text-centered mb-2">La venta se realizó con éxito. A continuacion el comprobante de venta. </p>
                <br>
                <div class="container">
                    <div class="columns">
                        <div class="column has-text-centered">
                            <button type="button" class="button is-link is-light" onclick="print_invoice('<?php echo APP_URL."app/pdf/invoice.php?code=".$_SESSION['venta_codigo_factura']; ?>')" >
                                <i class="fas fa-file-invoice-dollar fa-2x"></i> &nbsp;
                                Comprobante de venta
                            </button>
                            <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-pay" >
                                Registrar pago
                            </button>
                            <!-- Modal registrar pago -->
                            <div class="modal" id="modal-js-pay">
                                <div class="modal-background"></div>
                                <div class="modal-card">
                                    <header class="modal-card-head">
                                    <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Pagos de la venta: </p>
                                    <button class="delete" aria-label="close"></button>
                                    </header>
                                    <section class="modal-card-body">
                                        <form class="" action="<?php echo APP_URL; ?>app/ajax/pagoAjax.php" method="POST" autocomplete="off" name="formsale" >
                                            <input type="hidden" name="modulo_pago" value="registrar_pago_venta">
                                            <input type="hidden" name="venta_codigo" id="venta_codigo">
                                            <div class="columns">
                                                <div class="column">
                                                    <label for="" class="label">Venta codigo: </label>
                                                    <input name="venta_codigo" readonly class="input" type="text" value="<?php echo $_SESSION['venta_codigo_factura']?>">
                                                </div>
                                                <div class="column">
                                                    <label for="" class="label">Fecha: </label>
                                                    <input name="venta_pago_fecha" class="input" name="" type="date" value="<?php echo date("Y-m-d"); ?>" >
                                                </div>
                                            </div>
                                            <div class="columns">
                                                <div class="column">
                                                    <label for="" class="label">Forma de pago: </label>
                                                    <select name="venta_pago_forma" id="" class="select">
                                                        <option value="Efectivo">Efectivo</option>
                                                        <option value="Transferencia">Transferencia</option>
                                                    </select>
                                                </div>
                                                <div class="column">
                                                    <label for="" class="label">Importe: </label>
                                                    <input class="input" type="number" name="venta_pago_importe">
                                                </div>
                                                <div class="column">
                                                    <label for="" class="label">Detalle: </label>
                                                    <input class="input" type="text" name="venta_pago_detalle">
                                                </div>
                                            </div>
                                            <div class="columns">
                                                    <div class="column">Total de la venta: <?php echo MONEDA_SIMBOLO.number_format($_SESSION['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?></div>
                                                    <div class="column">
                                                        <?php
                                                        $suma_pagos = $insLogin->seleccionarDatos("Normal", "pago_venta WHERE venta_codigo = '".$_SESSION['venta_codigo_factura']."'","SUM(venta_pago_importe) as suma_pagos",0);
                                                        if($suma_pagos->rowCount() >= 1){
                                                            $suma_pagos = $suma_pagos->fetch();
                                                            if($suma_pagos['suma_pagos'] !== NULL){
                                                                echo "Suma de sus pagos: ".MONEDA_SIMBOLO.number_format($suma_pagos['suma_pagos'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
                                                            }else{
                                                                echo "Suma de sus pagos: 0.00";
                                                            }
                                                        }else{
                                                            echo "Suma de sus pagos: 0.00";
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="column">
                                                        <?php
                                                        $suma_pagos = $insLogin->seleccionarDatos("Normal", "pago_venta WHERE venta_codigo = '".$_SESSION['venta_codigo_factura']."'","SUM(venta_pago_importe) as suma_pagos",0);
                                                        if($suma_pagos->rowCount() >= 1){
                                                            $suma_pagos = $suma_pagos->fetch();
                                                            $saldo = $_SESSION['venta_importe'] - $suma_pagos['suma_pagos'];
                                                            echo "Saldo: ".MONEDA_SIMBOLO.number_format($saldo,MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
                                                        }else{
                                                            echo "Saldo: ".MONEDA_SIMBOLO.number_format($_SESSION['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            <div class="container" id="resultado-busqueda"></div>
                                            <p class="has-text-centered">
                                                <button type="submit" class="button is-link is-light">Registrar pago</button>
                                            </p>
                                        </form>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php
                    unset($_SESSION['venta_codigo_factura']);
                }
            ?>
            <div class="table-container">
                <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th class="has-text-centered">Codigo</th>
                            <th class="has-text-centered">Articulo</th>
                            <th class="has-text-centered">Cant.</th>
                            <th class="has-text-centered">P. Lista</th>
                            <th class="has-text-centered">Financiacion</th>
                            <th class="has-text-centered">P. Efectivo</th>
                            <th class="has-text-centered">Remover</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($_SESSION['datos_producto_venta']) && count($_SESSION['datos_producto_venta'])>=1){

                                $_SESSION['venta_importe']=0;
                                $cc=1;

                                foreach($_SESSION['datos_producto_venta'] as $productos){
                        ?>
                        <tr class="has-text-centered" >
                            <td><?php echo $productos['articulo_codigo']; ?></td>
                            <td><?php echo $productos['venta_detalle_descripcion_producto']; ?></td>
                            <td>
                                <div class="control">
                                    <input class="input sale_input-cant has-text-centered" value="<?php echo $productos['venta_detalle_cantidad_producto']; ?>" id="sale_input_<?php echo str_replace(" ", "_", $productos['articulo_codigo']); ?>" type="text" style="max-width: 80px;">
                                </div>
                            </td>
                            <td><?php echo MONEDA_SIMBOLO.number_format($productos['venta_detalle_precio_venta_producto'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?></td>
                            <td><?php echo MONEDA_SIMBOLO.number_format($productos['venta_detalle_precio_venta_producto'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?></td>
                            <td><?php echo MONEDA_SIMBOLO.number_format($productos['venta_detalle_total'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?></td>
                            <td>
                                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ventaAjax.php" method="POST" autocomplete="off">

                                    <input type="hidden" name="articulo_codigo" value="<?php echo $productos['articulo_codigo']; ?>">
                                    <input type="hidden" name="modulo_venta" value="remover_producto">

                                    <button type="submit" class="button is-danger is-rounded is-small" title="Remover producto">
                                        <i class="fas fa-trash-restore fa-fw"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                                $cc++;
                                $_SESSION['venta_importe']+=$productos['venta_detalle_total'];
                            }
                        ?>
                        <tr class="has-text-centered" >
                            <td colspan="4"></td>
                            <td class="has-text-weight-bold">
                                TOTAL
                            </td>
                            <td class="has-text-weight-bold">
                                <?php echo MONEDA_SIMBOLO.number_format($_SESSION['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        <?php
                            }else{
                                    $_SESSION['venta_importe']=0;
                        ?>
                        <tr class="has-text-centered" >
                            <td colspan="8">
                                No hay productos agregados
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    
                </table>

            </div>
        </div>
    </div>

    <div class="container">
        <h2 class="title has-text-centered">Datos de la venta</h2>
        <hr>

        <?php if($_SESSION['venta_importe']>0){ ?>
        <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ventaAjax.php" method="POST" autocomplete="off" name="formsale" >
            <input type="hidden" name="modulo_venta" value="registrar_venta">
                <?php }else { ?>
            <form name="formsale">
                <?php } ?>
                <div class="columns">
                    <div class="column">
                        <label>Observaciones</label>
                        <textarea class="textarea" type="text" name="venta_observaciones" placeholder="Observaciones"></textarea>
                    </div>
                    <br>
                    <div class="column">
                        <label>Fecha <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="date" value="<?php echo date("Y-m-d"); ?>" >
                    </div>
                    <br>
                    <div class="column">
                        <label>Vendedor <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                        <input class="input" type="text" name="venta_vendedor" id="">
                    </div>
                    <br>
                    <div class="column"></div>
                </div>
                

                <label>Cliente <?php echo CAMPO_OBLIGATORIO; ?></label>
                <?php
                    if(isset($_SESSION['datos_cliente_venta']) && count($_SESSION['datos_cliente_venta'])>=1 && $_SESSION['datos_cliente_venta']['id_cliente']!=1){
                ?>
                <div class="field has-addons mb-5">
                    <div class="control">
                        <input class="input" type="text" readonly id="venta_cliente" value="<?php echo $_SESSION['datos_cliente_venta']['cliente_nombre_completo']; ?>" >
                    </div>
                    <div class="control">
                        <a class="button is-danger" title="Remove cliente" id="btn_remove_client" onclick="remover_cliente(<?php echo $_SESSION['datos_cliente_venta']['id_cliente']; ?>)">
                            <i class="fas fa-user-times fa-fw"></i>
                        </a>
                    </div>
                </div>
                    <?php 
                        }else{
                            $datos_cliente=$insLogin->seleccionarDatos("Normal","cliente WHERE id_cliente='1'","*",0);
                            if($datos_cliente->rowCount()==1){
                                $datos_cliente=$datos_cliente->fetch();

                                $_SESSION['datos_cliente_venta']=[
                                    "id_cliente"=>$datos_cliente['id_cliente'],
                                    "cliente_tipo_doc"=>$datos_cliente['cliente_tipo_doc'],
                                    "cliente_documento"=>$datos_cliente['cliente_documento'],
                                    "cliente_nombre_completo"=>$datos_cliente['cliente_nombre_completo']
                                ];

                            }else{
                                $_SESSION['datos_cliente_venta']=[
                                    "id_cliente"=>1,
                                    "cliente_tipo_doc"=>"N/A",
                                    "cliente_documento"=>"N/A",
                                    "cliente_nombre_completo"=>"Publico General",
                                ];
                            }
                    ?>
                <div class="field has-addons mb-5">
                    <div class="control">
                        <input class="input" type="text" readonly id="venta_cliente" value="<?php echo $_SESSION['datos_cliente_venta']['cliente_nombre_completo']; ?>" >
                    </div>
                    <div class="control">
                        <a class="button is-info js-modal-trigger" data-target="modal-js-client" title="Agregar cliente" id="btn_add_client" >
                            <i class="fas fa-user-plus fa-fw"></i>
                        </a>
                    </div>
                </div>
                    <?php } ?>

                    <h4 class="subtitle is-5 has-text-centered has-text-weight-bold mb-5"><small>TOTAL A PAGAR: <?php echo MONEDA_SIMBOLO.number_format($_SESSION['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?></small></h4>

                    <?php if($_SESSION['venta_importe']>0){ ?>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar venta</button>
                    </p>
                    <?php } ?>
                    <p class="has-text-centered pt-6">
                        <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
                    </p>
                    <input type="hidden" value="<?php echo number_format($_SESSION['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,""); ?>" id="venta_importe_hidden">
        </form>

        
    </div>
    <?php }else{ ?>
        <article class="message is-warning">
             <div class="message-header">
                <p>¡Ocurrio un error inesperado!</p>
             </div>
            <div class="message-body has-text-centered"><i class="fas fa-exclamation-triangle fa-2x"></i><br>No hemos podio seleccionar algunos datos sobre la sucursal<?php if($_SESSION['rol']=="Administrador"){ ?>, por favor <a href="<?php echo APP_URL; ?>sucursalNew/" >verifique aquí los datos de la empresa<?php } ?></div>
        </article>
    <?php } ?>
</div>

<!-- Modal buscar producto -->
<div class="modal" id="modal-js-product">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Buscar producto</p>
          <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <div class="field mt-6 mb-6">
                <label class="label">Codigo, nombre, marca, modelo.</label>
                <div class="control">
                    <input class="input" type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" name="input_codigo" id="input_codigo" maxlength="30" >
                </div>
            </div>
            <div class="container" id="resultado-busqueda"></div>
        </section>
    </div>
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

        </section>
    </div>
</div>

<!-- Modal actualizar precio -->
<div class="modal" id="modal-js-price">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title is-uppercase"><i class="fas fa-dollar-sign"></i> &nbsp; Actualizar precio de producto</p>
          <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <div class="notification is-info is-light pb-3 pt-3">
                *** El precio del producto se modificará solo para realizar la venta actual, NO se modificará su precio normal de inventario (Stock) ***
            </div>
            <div class="field mt-6 mb-6">
                <label class="label">Nuevo precio de producto (<span id="modal_precio_nombre"></span>)</label>
                <div class="control">
                    <input class="input" type="text" name="input_precio" id="input_precio" pattern="[0-9.]{1,25}" maxlength="25" value="0.00" >

                    <input type="hidden" name="input_precio_codigo" id="input_precio_codigo">
                </div>
            </div>
            <div class="container" id="tabla_clientes"></div>
            <p class="has-text-centered">
                <button type="button" class="button is-success is-light" onclick="actualizar_precio()" ><i class="fas fa-sync-alt"></i> &nbsp; Actualizar</button>
            </p>
        </section>
    </div>
</div>





<script>
    /* Detectar cuando se envia el formulario para agregar producto */
    let sale_form_barcode = document.querySelector("#sale-barcode-form");
    sale_form_barcode.addEventListener('submit', function(event){
        event.preventDefault();
        setTimeout('agregar_producto()',100);
    });


    /* Detectar cuando escanea un codigo en formulario para agregar producto */
    let sale_input_barcode = document.querySelector("#sale-barcode-input");
    sale_input_barcode.addEventListener('paste',function(){
        setTimeout('agregar_producto()',100);
    });


    /* Agregar producto */
    function agregar_producto(){
        let codigo_producto=document.querySelector('#sale-barcode-input').value;

        codigo_producto=codigo_producto.trim();

        if(codigo_producto!=""){
            let datos = new FormData();
            datos.append("articulo_codigo", codigo_producto);
            datos.append("modulo_venta", "agregar_producto");

            fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(respuesta =>{
                return alertas_ajax(respuesta);
            });

        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error inesperado',
                text: 'Debes de introducir el código del producto',
                confirmButtonText: 'Aceptar'
            });
        }
    }


    /*----------  Buscar codigo  ----------*/
    function buscar_codigo(){
        let input_codigo=document.querySelector('#input_codigo').value;

        input_codigo=input_codigo.trim();

        if(input_codigo!=""){

            let datos = new FormData();
            datos.append("buscar_codigo", input_codigo);
            datos.append("modulo_venta", "buscar_codigo");

            fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.text())
            .then(respuesta =>{
                let resultado_busqueda=document.querySelector('#resultado-busqueda');
                resultado_busqueda.innerHTML=respuesta;
            });

        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error inesperado',
                text: 'Debes de introducir el Nombre, Marca o Modelo del producto',
                confirmButtonText: 'Aceptar'
            });
        }
    }

    // Agregar evento de búsqueda en tiempo real prodyctos
    document.querySelector('#input_codigo').addEventListener('input', function(){
        let input_codigo=document.querySelector('#input_codigo').value;

        input_codigo=input_codigo.trim();

        if(input_codigo!=""){

            let datos = new FormData();
            datos.append("buscar_codigo", input_codigo);
            datos.append("modulo_venta", "buscar_codigo");

            fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.text())
            .then(respuesta =>{
                let resultado_busqueda=document.querySelector('#resultado-busqueda');
                resultado_busqueda.innerHTML=respuesta;
            });

        }else{
            let resultado_busqueda=document.querySelector('#resultado-busqueda');
            resultado_busqueda.innerHTML='';
        }
    });

    // Agregar evento de búsqueda en tiempo real clientes
    document.querySelector('#input_cliente').addEventListener('input', function(){
        let input_cliente=document.querySelector('#input_cliente').value;

        input_cliente=input_cliente.trim();

        if(input_cliente!=""){

            let datos = new FormData();
            datos.append("buscar_cliente", input_cliente);
            datos.append("modulo_venta", "buscar_cliente");

            fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
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


    /*----------  Agregar codigo  ----------*/
    function agregar_codigo($codigo){
        document.querySelector('#sale-barcode-input').value=$codigo;
        setTimeout('agregar_producto()',100);
    }


    /* Actualizar cantidad de producto */
    function actualizar_cantidad(id,codigo){
        let cantidad=document.querySelector(id).value;

        cantidad=cantidad.trim();
        codigo.trim();

        if(cantidad>0){

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Desea actualizar la cantidad de articulos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, actualizar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed){

                    let datos = new FormData();
                    datos.append("articulo_codigo", codigo);
                    datos.append("articulo_cantidad", cantidad);
                    datos.append("modulo_venta", "actualizar_producto");

                    fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                        method: 'POST',
                        body: datos
                    })
                    .then(respuesta => respuesta.json())
                    .then(respuesta =>{
                        return alertas_ajax(respuesta);
                    });
                }
            });
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error inesperado',
                text: 'Debes de introducir una cantidad mayor a 0',
                confirmButtonText: 'Aceptar'
            });
        }
    }


    /*----------  Buscar cliente  ----------*/
    function buscar_cliente(){
        let input_cliente=document.querySelector('#input_cliente').value;

        input_cliente=input_cliente.trim();

        if(input_cliente!=""){

            let datos = new FormData();
            datos.append("buscar_cliente", input_cliente);
            datos.append("modulo_venta", "buscar_cliente");

            fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
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
            text: "Se va a agregar este cliente para realizar una venta",
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
                datos.append("modulo_venta", "agregar_cliente");

                fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
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

                fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
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


    function modal_actualizar_precio(nombre,codigo,precio){
        document.querySelector('#modal_precio_nombre').innerHTML=nombre;
        document.querySelector('#input_precio_codigo').value=codigo;
        document.querySelector('#input_precio').value=precio;
    }

    function actualizar_precio(){

        let codigo=document.querySelector('#input_precio_codigo').value;

        let precio=document.querySelector('#input_precio').value;
        precio=precio.trim();
        precio=parseFloat(precio);

        if(codigo!=""){
            if(precio>0){

                let datos = new FormData();
                datos.append("articulo_codigo", codigo);
                datos.append("articulo_precio", precio);
                datos.append("modulo_venta", "actualizar_precio_producto");

                fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                    method: 'POST',
                    body: datos
                })
                .then(respuesta => respuesta.json())
                .then(respuesta =>{
                    return alertas_ajax(respuesta);
                });

            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Ocurrió un error inesperado',
                    text: 'Debe de introducir un precio mayor a cero',
                    confirmButtonText: 'Aceptar'
                });
            }
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error inesperado',
                text: 'Ha ocurrido un error al procesar la solicitud por favor recargue nuevamente la pagina',
                confirmButtonText: 'Aceptar'
            });
        }
    }

</script>

<?php
    include "./app/views/includes/print_invoice_script.php";
?>