<div class="">
	<?php 
		$id = $insLogin->limpiarCadena($url[1]);
        
	?>

</div>

<div class="container is-max-desktop" >
	<?php
	
		include "./app/views/includes/btn_back.php";

		$datos = $insLogin->seleccionarDatos("Unico","orden","id_orden",$id);
        
		if($datos->rowCount()==1){
			$datos=$datos->fetch();
            $_SESSION['orden'] = $datos['orden_codigo'];
            $orden_codigo = $_SESSION['orden'];
            $id_cliente = $datos['id_cliente'];
            $datos_cliente = $insLogin->seleccionarDatos("Unico","cliente","id_cliente",$id_cliente);
            $datos_cliente = $datos_cliente->fetch();
            $datos_telefonista = $insLogin->seleccionarDatosEspecificos("usuario", "id_usuario", $datos['orden_telefonista']);
            $datos_telefonista = $datos_telefonista->fetch();
	?>

	<h2 class="title has-text-centered">INFORMACION ORDEN  <?php echo $datos['orden_codigo'] ; ?></h2>
    <button type="button" class="button is-link is-light" onclick="print_orden('<?php echo APP_URL."app/pdf/comprobanteOrden.php?code=".$datos['orden_codigo']; ?>')" >
        <i class="fas fa-file-invoice-dollar fa-2x"></i> &nbsp;
        Comprobante de orden
    </button>
    <br>

    <div class="box has-text-centered">
        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-cotizada" >
            COTIZADA
        </button>
        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-aceptada" >
            ACEPTADA
        </button>
        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-no-aceptada" >
            NO ACEPTADA
        </button>
        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-en-espera" >
            EN ESPERA
        </button>
        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-sin-reparacion" >
            SIN REPARACION
        </button>
        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-reparada" >
            REPARADA
        </button>
        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-entregada" >
            ENTREGADA
        </button>
    </div>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_orden" value="actualizar_orden">
		<input type="hidden" name="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
        <!-- DATOS DE LA ORDEN -->
        <div class="box">
            <h3 class="title is-4">Datos</h3>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Cliente</label>
                        <input class="input" type="text" readonly value="<?php echo $datos_cliente['cliente_nombre_completo'] ?>">
                    </div>
                    <div class="control">
                        <label>Domicilio</label>
                        <input class="input" type="text" readonly value="<?php echo $datos_cliente['cliente_domicilio'] ?>">
                    </div>
                    <div class="control">
                        <label>Telefonos</label>
                        <input class="input" type="text" readonly value="<?php echo $datos_cliente['cliente_telefono_1'] ?> / <?php echo $datos_cliente['cliente_telefono_2'] ?>">
                    </div>
                    <div class="control">
                        <label>Fecha</label>
                        <input class="input" type="date" name="orden_fecha" value="<?php echo $datos['orden_fecha']; ?>" >
                    </div>
                    <div class="control">
                        <label>Tecnico asignado</label><br>
                        <div class="select">
                            <select name="id_tecnico" >
                                <option value="" selected="" >Seleccione una opción</option>
                                <?php
                                    $datos_tecnico=$insLogin->seleccionarDatos("Normal","tecnico","*",0);

                                    $cc=1;
                                    while($campos_tecnico=$datos_tecnico->fetch()){
                                        $selected = ($campos_tecnico['id_tecnico'] == $datos['id_tecnico']) ? 'selected' : '';
                                        echo '<option value="'.$campos_tecnico['id_tecnico'].'" '.$selected.'>'.$cc.' - '.$campos_tecnico['tecnico_descripcion'].'</option>';
                                        $cc++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control">
                        <h3>Telefonista/Operador</h3>
                        <input type="text" readonly class="input" name="orden_telefonista" value="<?php echo $datos_telefonista['usuario_nombre_completo'] ?>">
                    </div>
                </div>

                <!-- DATOS DE LA ORDEN -->                   
                <div class="column">
                    <div class="control">
                        <h3>Ingresa encendido: <?php echo $datos['orden_equipo_ingresa_encendido']; ?></h3>
                    </div>

                    <div class="control">
                        <h3>Contrasena: <?php echo $datos['orden_equipo_contrasena']; ?></h3>
                    </div>

                    <div class="control">
                        <h3>Detalles fiscos: </h3>
                        <?php if($datos['orden_equipo_detalles_fisicos'] != "") {?>
                                <textarea readonly class="textarea"  name="orden_equipo_detalles_fisicos" id=""><?php echo $datos['orden_equipo_detalles_fisicos']; ?></textarea>
                        <?php }else{?>
                                <p class="textarea">No</p>
                        <?php }?>
                    </div>
                    <div class="control">
                        <h3>Observaciones</h3>
                        <textarea class="textarea"  name="orden_observaciones" ><?php echo $datos['orden_observaciones']; ?></textarea>
                    </div>
                    
                    
                </div>
            </div>
            <div class="columns is-centered">
                <div class="column">
                    <h3>Falla/Problema</h3>
                    <textarea class="textarea"  name="orden_falla" id=""><?php echo $datos['orden_falla']; ?></textarea>
                </div>

                <div class="column">
                    <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-infTec" >
                        Informe tecnico
                    </button>
                    <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-pay" >
                        Registrar pago
                    </button>
                </div>
            </div>
            
        </div>
        
        <!-- DETALLES DEL EQUIPO -->
        <div class="box">
            <h3 class="title is-4">Detalles del equipo</h3>
            <div class="columns">
                <!-- Columna izquierda: Marca, Modelo -->
                <div class="column is-half">
                    <div class="control">
                        <label>Marca</label><br>
                        <div class="select is-fullwidth">
                            <select disabled name="id_marca">
                                <option value="" selected>Seleccione una opción</option>
                                <?php
                                    $datos_marca = $insLogin->seleccionarDatos("Normal", "marca", "*", 0);
                                    $cc = 1;
                                    while ($campos_marca = $datos_marca->fetch()) {
                                        $selected = ($campos_marca['id_marca'] == $datos['id_marca']) ? 'selected' : '';
                                        echo '<option value="' . $campos_marca['id_marca'] . '" ' . $selected . '>' . $cc . ' - ' . $campos_marca['marca_descripcion'] . '</option>';
                                        $cc++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control">
                        <label>Modelo</label><br>
                        <div class="select is-fullwidth">
                            <select disabled name="id_modelo">
                                <option value="" selected>Seleccione una opción</option>
                                <?php
                                    $datos_modelo = $insLogin->seleccionarDatos("Normal", "modelo", "*", 0);
                                    $cc = 1;
                                    while ($campos_modelo = $datos_modelo->fetch()) {
                                        $selected = ($campos_modelo['id_modelo'] == $datos['id_modelo']) ? 'selected' : '';
                                        echo '<option value="' . $campos_modelo['id_modelo'] . '" ' . $selected . '>' . $cc . ' - ' . $campos_modelo['modelo_descripcion'] . '</option>';
                                        $cc++;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Accesorios -->
                <div class="column is-half">
                    <div class="control">
                        <label>Accesorios</label>
                        <textarea class="textarea" name="orden_accesorios"><?php echo $datos['orden_accesorios']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ESTADO DE LA ORDEN -->
        <div class="box">
            <h3 class="title is-4">Estado de la orden</h3>
            <!-- Primera fila: Estado, Total y Tipo -->
            <div class="columns">
                <div class="column is-one-third">
                    <div class="control">
                        <h4 class="title is-5" style="margin-bottom: 5px;">Estado</h4>
                        <p class="has-text-weight-semibold" style="margin-top: 0; font-size: 1.2rem;"><?php echo $datos['orden_estado']; ?></p>
                    </div>
                </div>
                <div class="column is-one-third">
                    <div class="control">
                        <h4 class="title is-5" style="margin-bottom: 5px;">Total</h4>
                        <p class="has-text-weight-semibold" style="margin-top: 0; font-size: 1.2rem;"><?php echo $datos['orden_total_lista']; ?> / <?php echo $datos['orden_total_efectivo']; ?></p>
                    </div>
                </div>
                <div class="column is-one-third">
                    <div class="control">
                        <h4 class="title is-5" style="margin-bottom: 5px;">Tipo</h4>
                        <p class="has-text-weight-semibold" style="margin-top: 0; font-size: 1.2rem;"><?php echo $datos['orden_tipo']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Segunda fila: Fechas -->
            <div class="columns is-multiline">
                <div class="column is-one-quarter">
                    <div class="control">
                        <h4 class="title is-5" style="margin-bottom: 5px;">Aceptado el</h4>
                        <p class="has-text-weight-semibold" style="margin-top: 0; font-size: 1.2rem;"><?php echo $datos['orden_fecha_aceptada']; ?></p>
                    </div>
                </div>
                <div class="column is-one-quarter">
                    <div class="control">
                        <h4 class="title is-5" style="margin-bottom: 5px;">Prometido para</h4>
                        <p class="has-text-weight-semibold" style="margin-top: 0; font-size: 1.2rem;"><?php echo $datos['orden_fecha_prometida']; ?></p>
                    </div>
                </div>
                <div class="column is-one-quarter">
                    <div class="control">
                        <h4 class="title is-5" style="margin-bottom: 5px;">Entregada el</h4>
                        <p class="has-text-weight-semibold" style="margin-top: 0; font-size: 1.2rem;"><?php echo $datos['orden_fecha_entregada']; ?></p>
                    </div>
                </div>
                <div class="column is-one-quarter">
                    <div class="control">
                        <h4 class="title is-5" style="margin-bottom: 5px;">Garantía hasta</h4>
                        <p class="has-text-weight-semibold" style="margin-top: 0; font-size: 1.2rem;"><?php echo $datos['orden_fecha_garantia']; ?></p>
                    </div>
                </div>
            </div>
        </div>   
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
		</p>
	</form>

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
                                <input class="input"  type="hidden" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70"  autofocus="autofocus" placeholder="Código de barras" id="sale-barcode-input" >
                            </p>
                            <a class="control">
                                <button type="submit" hidden class="button is-info " style="visibility: hidden;">
                                    <i class="far fa-check-circle"></i> &nbsp; Agregar producto
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-container">
                <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th class="has-text-centered">Código</th>
                            <th class="has-text-centered">Artículo</th>
                            <th class="has-text-centered">Cant.</th>
                            <th class="has-text-centered">F. de pago</th>
                            <th class="has-text-centered">Subtotal</th>
                            <th class="has-text-centered">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($_SESSION['datos_producto_orden']) && count($_SESSION['datos_producto_orden']) >= 1) {
                                $_SESSION['orden_importe'] = 0;

                                foreach($_SESSION['datos_producto_orden'] as $productos) {
                                    $codigo = $productos['articulo_codigo'];
                                    $financiacion = isset($_SESSION['financiacion'][$codigo]) ? $_SESSION['financiacion'][$codigo]['orden_detalle_financiacion_producto'] : 'n/a';
                                    $subtotal = isset($_SESSION['financiacion'][$codigo]) ? $_SESSION['financiacion'][$codigo]['orden_detalle_total'] : '0';
                                    $_SESSION['orden_importe'] += $subtotal;
                        ?>
                        <tr class="has-text-centered">
                            <td><?php echo $productos['articulo_codigo']; ?></td>
                            <td><?php echo $productos['orden_detalle_descripcion_producto']; ?></td>
                            <td>
                                <div class="control">
                                    <input readonly class="input sale_input-cant has-text-centered" value="<?php echo $productos['orden_detalle_cantidad_producto']; ?>" id="sale_input_<?php echo str_replace(" ", "_", $productos['articulo_codigo']); ?>" type="text" style="max-width: 80px;">
                                </div>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off">
                                    <input type="hidden" name="articulo_codigo" value="<?php echo $productos['articulo_codigo']; ?>">
                                    <input type="hidden" name="modulo_orden" value="financiar_producto">
                                    <div class="select">
                                        <select name="financiacion" class="select" required onchange="financiarProducto('<?php echo $productos['articulo_codigo']; ?>', this.value)">
                                            <option value="">Seleccionar opción</option>
                                            <option value="Efectivo" <?php echo (isset($_SESSION['financiacion'][$codigo]) && $_SESSION['financiacion'][$codigo]['orden_detalle_financiacion_producto'] == 'Efectivo') ? 'selected' : ''; ?>>Efectivo</option>
                                            <option value="3cuotas" <?php echo (isset($_SESSION['financiacion'][$codigo]) && $_SESSION['financiacion'][$codigo]['orden_detalle_financiacion_producto'] == '3cuotas') ? 'selected' : ''; ?>>3 cuotas</option>
                                            <option value="6cuotas" <?php echo (isset($_SESSION['financiacion'][$codigo]) && $_SESSION['financiacion'][$codigo]['orden_detalle_financiacion_producto'] == '6cuotas') ? 'selected' : ''; ?>>6 cuotas</option>
                                            <option value="9cuotas" <?php echo (isset($_SESSION['financiacion'][$codigo]) && $_SESSION['financiacion'][$codigo]['orden_detalle_financiacion_producto'] == '9cuotas') ? 'selected' : ''; ?>>9 cuotas</option>
                                            <option value="12cuotas" <?php echo (isset($_SESSION['financiacion'][$codigo]) && $_SESSION['financiacion'][$codigo]['orden_detalle_financiacion_producto'] == '12cuotas') ? 'selected' : ''; ?>>12 cuotas</option>
                                        </select>
                                    </div>
                                    <!--<button type="submit" class="button is-link is-light is-rounded">Financiar</button>-->
                                </form>
                            </td>
                            <td>
                                <?php echo MONEDA_SIMBOLO . number_format($subtotal, MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR) . " " . MONEDA_NOMBRE; ?>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off">
                                    <input type="hidden" name="articulo_codigo" value="<?php echo $productos['articulo_codigo']; ?>">
                                    <input type="hidden" name="modulo_orden" value="remover_producto">
                                    <button type="submit" class="button is-danger is-rounded is-small" title="Remover producto">
                                        <i class="fas fa-trash-restore fa-fw"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                                }
                        ?>
                        <tr class="has-text-centered">
                            <td colspan="4"></td>
                            <td class="has-text-weight-bold">TOTAL</td>
                            <td class ="has-text-weight-bold">
                                <?php echo MONEDA_SIMBOLO . number_format($_SESSION['orden_importe'], MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR) . " " . MONEDA_NOMBRE; ?>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        <?php
                            } else {
                                $_SESSION['orden_importe'] = 0;
                        ?>
                        <tr class="has-text-centered">
                            <td colspan="8">No hay productos agregados</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($_SESSION['orden_importe']>0){ ?>
        <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale" >
            <input type="hidden" name="modulo_orden" value="registrar_productos_orden">
                <?php }else { ?>
            <form name="formsale">
                <?php } ?>


                <h4 class="subtitle is-5 has-text-centered has-text-weight-bold mb-5"><small>TOTAL PRODUCTOS: <?php echo MONEDA_SIMBOLO.number_format($_SESSION['orden_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?></small></h4>

                <?php if($_SESSION['orden_importe']>0){ ?>
                <p class="has-text-centered">
                    <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp;Agregar articulos a la orden</button>
                </p>
                <?php } ?>
                <input type="hidden" value="<?php echo number_format($_SESSION['orden_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,""); ?>" id="orden_importe_hidden">
                
                
            </form>

	<?php
		}else{
			include "./app/views/includes/error_alert.php";
		}
	?>
</div>

<!-- Modal registrar informe tecnico -->
<div class="modal" id="modal-js-infTec">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Informe tecnico</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale" >
                    <input type="hidden" name="modulo_orden" value="registrar_informe_tecnico">
                    <h2 class="subtitle">Datos:</h2>
                    <div class="columns">
                        <div class="column">
                            <div class="control">
                                <label for="">Numero de orden: </label>
                                <input class="input" type="text" name="orden_codigo" readonly value="<?php echo $datos['orden_codigo']; ?>">
                            </div>
                            <div class="control">
                                <label>Marca</label><br>
                                <div class="select">
                                    <select disabled name="id_marca" >
                                        <option value="" selected="" >Seleccione una opción</option>
                                        <?php
                                            $datos_marca=$insLogin->seleccionarDatos("Normal","marca","*",0);

                                            $cc=1;
                                            while($campos_marca=$datos_marca->fetch()){
                                                $selected = ($campos_marca['id_marca'] == $datos['id_marca']) ? 'selected' : '';
                                                echo '<option value="'.$campos_marca['id_marca'].'" '.$selected.'>'.$cc.' - '.$campos_marca['marca_descripcion'].'</option>';
                                                $cc++;
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control">
                                <label>Marca</label><br>
                                <div class="select">
                                    <select disabled name="id_modelo" >
                                        <option value="" selected="" >Seleccione una opción</option>
                                        <?php
                                            $datos_modelo=$insLogin->seleccionarDatos("Normal","modelo","*",0);

                                            $cc=1;
                                            while($campos_modelo=$datos_modelo->fetch()){
                                                $selected = ($campos_modelo['id_modelo'] == $datos['id_modelo']) ? 'selected' : '';
                                                echo '<option value="'.$campos_modelo['id_modelo'].'" '.$selected.'>'.$cc.' - '.$campos_modelo['modelo_descripcion'].'</option>';
                                                $cc++;
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control">
                                <label for="">Falla: </label>
                                <textarea class="textarea" name="" readonly id=""><?php echo $datos['orden_falla']; ?></textarea>
                            </div>
                            
                        </div>

                        <div class="column">
                            <div class="control">
                                <label for="" class="label">Informe tecnico: </label>
                                <textarea class="input" style="height: 200px;" name="orden_informe_tecnico" id=""><?php echo $datos['orden_informe_tecnico']; ?></textarea>
                            </div>
                            <div class="control">
                                <h3>TOTALES REPARACION</h3>
                                <label>P. Lista</label>
                                <input class="input" type="text" value="<?php echo $datos['orden_importe_lista']?>" name="orden_importe_lista">
                                
                                <label>P. Efectivo</label>
                                <input class="input" type="text" value="<?php echo $datos['orden_importe_efectivo'] ?>" name="orden_importe_efectivo">
                                
                            </div>
                        </div>
                    </div>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-link is-light">Guardar</button>
                    </p>
                </form>
                
            </section>
        </div>
    </div>
</div>


<!-- Modal registrar pago -->
<div class="modal" id="modal-js-pay">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Pagos de la orden: </p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/pagoAjax.php" method="POST" autocomplete="off" name="formorden" >
                <input type="hidden" name="modulo_pago" value="registrar_pago_orden">
                <input type="hidden" name="orden_codigo" id="orden_codigo">
                <div class="columns">
                    <div class="column">
                        <label for="" class="label">Orden codigo: </label>
                        <input name="orden_codigo" readonly class="input" type="text" value="<?php echo $datos['orden_codigo']?>">
                    </div>
                    <div class="column">
                        <label for="" class="label">Fecha: </label>
                        <input name="orden_pago_fecha" class="input" name="" type="date" value="<?php echo date("Y-m-d"); ?>" >
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <label for="" class="label">Forma de pago: </label>
                        <div class="select">
                            <select name="orden_pago_forma" id="orden_pago_forma" onchange="actualizarValores()">
                                <option value="" selected="">Seleccione una opción</option>
                                <?php
                                    echo $insLogin->generarSelect(FORMAS_PAGO, "VACIO");
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="column">
                        <label for="" class="label">Importe: </label>
                        <input class="input" type="number" name="orden_pago_importe" id="orden_pago_importe">
                    </div>
                    <div class="column">
                        <label for="" class="label">Detalle: </label>
                        <input class="input" type="text" name="orden_pago_detalle">
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        Total de la orden: <br>
                        <span id="orden_total"><?php echo MONEDA_SIMBOLO . number_format(0, MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR) . " " . MONEDA_NOMBRE; ?></span>
                    </div>
                    <div class="column">
                        Suma de sus pagos: <br>
                        <span id="suma_pagos">
                            <?php
                                $suma_pagos = $insLogin->seleccionarDatos("Normal", "pago_orden WHERE orden_codigo = '" . $datos['orden_codigo'] . "'", "SUM(orden_pago_importe) as suma_pagos", 0);
                                if ($suma_pagos->rowCount() >= 1) {
                                    $suma_pagos = $suma_pagos->fetch();
                                    echo $suma_pagos['suma_pagos'] !== NULL ? MONEDA_SIMBOLO . number_format($suma_pagos['suma_pagos'], MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR) . " " . MONEDA_NOMBRE : "0.00";
                                } else {
                                    echo "0.00";
                                }
                            ?>
                        </span>
                    </div>
                    <div class="column">
                        Saldo: <br>
                        <span id="saldo">
                            <?php
                                $saldo =  ($suma_pagos['suma_pagos'] ?? 0);
                                echo MONEDA_SIMBOLO . number_format($saldo, MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR) . " " . MONEDA_NOMBRE;
                            ?>
                        </span>
                        <input type="hidden" name="saldo" id="saldo_input" value="<?php echo $saldo; ?>">
                    </div>
                    
                </div><p class="has-text-centered">
                        <button type="submit" class="button is-link is-light" id="btnEnviar">Registrar pago</button>
                        <button type="button" class="button is-link is-light" id="btnSaldar">Saldar total</button>
                    </p>
            </form>
        </section>
    </div>
</div>

<!-- BOTONES -->

<!-- ORDEN ACEPTADA -->
<div class="modal" id="modal-js-aceptada">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Aceptar orden</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale" >
                    <input type="hidden" name="modulo_orden" value="aceptar_orden">
                    <input type="hidden" name="orden_codigo" id="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
                    <h2 class="subtitle">Orden numero: <?php echo $datos['orden_codigo']; ?></h2>
                    <div class="columns">
                        <div class="column">
                            <label for="" class="label">Fecha aceptada: </label>
                            <input class="input" type="date" name="orden_fecha_aceptada" id="" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="column">
                            <label for="" class="label">Fecha prometida: </label>
                            <input class="input" type="date" name="orden_fecha_prometida" id="">
                        </div>
                    </div>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-link is-light">Aceptar</button>
                    </p>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- ORDEN NO ACEPTADA -->
<div class="modal" id="modal-js-no-aceptada">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Rechazar orden</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale" >
                    <input type="hidden" name="modulo_orden" value="cambiar_estado_orden">
                    <input type="hidden" name="orden_codigo" id="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
                    <input type="hidden" name="orden_estado" value="NO ACEPTADA">
                    <p class="has-text-centered">
                        ESTAS SEGURO QUE QUIERES ESTABLECER LA ORDEN COMO 'NO ACEPTADO'?
                    </p>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-link is-light">Aceptar</button>
                    </p>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- ORDEN en espera -->
<div class="modal" id="modal-js-en-espera">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; En esper</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale" >
                    <input type="hidden" name="modulo_orden" value="cambiar_estado_orden">
                    <input type="hidden" name="orden_codigo" id="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
                    <input type="hidden" name="orden_estado" value="EN ESPERA">
                    <p class="has-text-centered">
                        ESTAS SEGURO QUE QUIERES ESTABLECER LA ORDEN COMO 'EN ESPERA'?
                    </p>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-link is-light">Aceptar</button>
                    </p>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- ORDEN en espera -->
<div class="modal" id="modal-js-sin-reparacion">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; En esper</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale" >
                    <input type="hidden" name="modulo_orden" value="cambiar_estado_orden">
                    <input type="hidden" name="orden_codigo" id="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
                    <input type="hidden" name="orden_estado" value="SIN REPARACION">
                    <p class="has-text-centered">
                        ESTAS SEGURO QUE QUIERES ESTABLECER LA ORDEN COMO 'SIN REPARACION'?
                    </p>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-link is-light">Aceptar</button>
                    </p>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- ORDEN reparada -->
<div class="modal" id="modal-js-reparada">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; En esper</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale" >
                    <input type="hidden" name="modulo_orden" value="cambiar_estado_orden">
                    <input type="hidden" name="orden_codigo" id="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
                    <input type="hidden" name="orden_estado" value="REPARADA">
                    <p class="has-text-centered">
                        ESTAS SEGURO QUE QUIERES ESTABLECER LA ORDEN COMO 'REPARADA'?
                    </p>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-link is-light">Aceptar</button>
                    </p>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- ORDEN ACEPTADA -->
<div class="modal" id="modal-js-entregada">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Entregar orden</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale" >
                    <input type="hidden" name="modulo_orden" value="entregar_orden">
                    <input type="hidden" name="orden_codigo" id="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
                    <div class="columns">
                        <div class="column">
                            <label for="" class="label">Fecha entregada: </label>
                            <input class="input" type="date" name="orden_fecha_entregada" id="" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="column">
                            <label for="" class="label">Garantia hasta: </label>
                            <input class="input" type="date" name="orden_fecha_garantia" value="<?php echo date('Y-m-d', strtotime('+4 months')); ?>">
                        </div>
                    </div>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-link is-light">Aceptar</button>
                    </p>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- MODALS PARA AGREGAR PRODUCTOS A LA ORDEN -->

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
            <p class="has-text-centered">
                <button type="button" class="button is-link is-light" onclick="buscar_codigo()" ><i class="fas fa-search"></i> &nbsp; Buscar</button>
            </p>
        </section>
    </div>
</div>

<?php
    include "./app/views/includes/print_invoice_script.php";
?>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        const btnSaldar = document.getElementById('btnSaldar');
        
        if (btnSaldar) {  // Verifica que el elemento exista
            btnSaldar.addEventListener('click', function (event) {
            event.preventDefault();  // Evita el envío inmediato del formulario

            // Obtener el saldo desde el campo oculto
            const saldo = document.querySelector('input[name="saldo"]').value;

            // Insertar el saldo en el campo "Importe"
            const inputImporte = document.getElementById('orden_pago_importe');
            inputImporte.value = saldo;

            // Simular clic en el botón "Registrar pago" (submit)
            document.getElementById('btnEnviar').click();
            });
        }
    });

    function financiarProducto(codigo, financiacion) {
        if (financiacion !== "") {
            let datos = new FormData();
            datos.append("articulo_codigo", codigo);
            datos.append("financiacion", financiacion);
            datos.append("modulo_orden", "financiar_producto");

            fetch('<?php echo APP_URL; ?>app/ajax/ordenAjax.php', {
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(respuesta => {
                return alertas_ajax(respuesta);
            });
        }
    }
    
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
            datos.append("modulo_orden", "agregar_producto");

            fetch('<?php echo APP_URL; ?>app/ajax/ordenAjax.php',{
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
            datos.append("modulo_orden", "buscar_codigo");

            fetch('<?php echo APP_URL; ?>app/ajax/ordenAjax.php',{
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

    // Agregar evento de búsqueda en tiempo real productos
    document.querySelector('#input_codigo').addEventListener('input', function(){
        let input_codigo=document.querySelector('#input_codigo').value;

        input_codigo=input_codigo.trim();

        if(input_codigo!=""){

            let datos = new FormData();
            datos.append("buscar_codigo", input_codigo);
            datos.append("modulo_orden", "buscar_codigo");

            fetch('<?php echo APP_URL; ?>app/ajax/ordenAjax.php',{
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.text())
            .then(respuesta =>{
                let resultado_busqueda=document.querySelector('#resultado-busqueda');
                resultado_busqueda.innerHTML=respuesta;
                console.log("Valor enviado: " + input_codigo);
            });

        }else{
            let resultado_busqueda=document.querySelector('#resultado-busqueda');
            resultado_busqueda.innerHTML='';
        }
    });

    /*----------  Agregar codigo  ----------*/
    function agregar_codigo($codigo){
        document.querySelector('#sale-barcode-input').value=$codigo;
        setTimeout('agregar_producto()',100);
    }

    function actualizarValores() {
        const formaPago = document.getElementById("orden_pago_forma").value;
        const ordenTotal = document.getElementById("orden_total");
        const saldo = document.getElementById("saldo");
        const saldoInput = document.getElementById("saldo_input");

        // Datos desde el servidor (puedes ajustar esto con AJAX si necesitas valores más dinámicos)
        const totalEfectivo = <?php echo $datos['orden_total_efectivo']; ?>;
        const totalLista = <?php echo $datos['orden_total_lista']; ?>;
        const sumaPagos = <?php echo $suma_pagos['suma_pagos'] ?? 0; ?>;

        let nuevoTotal;
        if (formaPago === "Efectivo") {
            nuevoTotal = totalEfectivo;
        } else {
            nuevoTotal = totalLista;
        }

        // Actualizar DOM
        ordenTotal.innerText = `${nuevoTotal.toFixed(2)} <?php echo MONEDA_NOMBRE; ?>`;
        const nuevoSaldo = nuevoTotal - sumaPagos;
        saldo.innerText = `${nuevoSaldo.toFixed(2)} <?php echo MONEDA_NOMBRE; ?>`;
        saldoInput.value = nuevoSaldo;
    }

</script>