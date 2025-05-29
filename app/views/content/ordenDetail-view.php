<?php 
    $codigo = $insLogin->limpiarCadena($url[1]);
?>

<div class="container is-fluid" >
	<?php
	
		include "./app/views/includes/btn_back.php";

		$datos = $insLogin->seleccionarDatos("Unico","orden","orden_codigo",$codigo);
        
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
    <button type="button" class="button is-link is-light" onclick="print_orden('<?php echo APP_URL."app/pdf/comprobanteOrden.php?code=".$datos['orden_codigo']; ?>')" >
        <i class="fas fa-file-invoice-dollar fa-2x"></i> &nbsp;
        Comprobante de orden
    </button>
    <br>
    <?php
        $estado_actual = strtoupper($datos['orden_estado']);
        $estados = ['a-reparar', 'presupuestado', 'verificar' , 'sin-reparacion', 'reparada' ,'entregada'];
    ?>
    <div class="box has-text-centered">
        <?php foreach ($estados as $estado): ?>
            <button type="button" class="button is-link <?php echo ($estado_actual == strtoupper(str_replace('-', ' ', $estado))) ? 'is-primary' : 'is-light'; ?> js-modal-trigger" data-target="modal-js-<?php echo $estado; ?>">
                <?php echo ucfirst(str_replace('-', ' ', $estado)); ?>
            </button>
        <?php endforeach; ?>
    </div>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" >
		<input type="hidden" name="modulo_orden" value="actualizar_orden">
		<input type="hidden" name="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
        <!-- DATOS DE LA ORDEN -->
        <div class="box">
            <h3 class="title is-4">Datos de la orden <?php echo $datos['id_orden'] ?></h3>
            <div class="columns">
                <!-- Columna izquierda: Marca, Modelo -->
                <div class="column is-half">
                    <div class="control">
                        <div class="full-width sale-details text-condensedLight">
                            <div class="has-text-weight-bold">Fecha</div>
                            <span class="has-text-bold"><?php echo $datos['orden_fecha']; ?></span>
                        </div>
                    </div>
                    <div class="control">
                        <div class="full-width sale-details text-condensedLight">
                            <div class="has-text-weight-bold">Cliente</div>
                            <span class="has-text-bold"><?php echo $datos_cliente['cliente_nombre_completo']; ?></span>
                        </div>
                    </div>
                    <div class="control">
                        <div class="full-width sale-details text-condensedLight">
                            <div class="has-text-weight-bold">Telefonista/operador</div>
                            <span class="has-text-bold"><?php echo $datos_telefonista['usuario_nombre_completo']; ?></span>
                        </div>
                    </div>
                    <div class="control">
                        <div class="full-width sale-details text-condensedLight has-text-centered">
                            <label class="has-text-weight-bold">Ubicacion fisica</label><br>
                            <div class="select">
                                <select name="orden_ubicacion_fisica" >
                                    <option value="Sin Asignar" <?= $datos['orden_ubicacion_fisica'] == 'Sin Asignar' ? 'selected' : '' ?>>Sin asignar</option>
                                    <option value="Mesa Luka" <?= $datos['orden_ubicacion_fisica'] == 'Mesa Luka' ? 'selected' : '' ?>>Mesa Luka</option>
                                    <option value="Mesa Sebastian" <?= $datos['orden_ubicacion_fisica'] == 'Mesa Sebastian' ? 'selected' : '' ?>>Mesa Sebastian</option>
                                    <option value="Mesa Tomas" <?= $datos['orden_ubicacion_fisica'] == 'Mesa Tomas' ? 'selected' : '' ?>>Mesa Tomas</option>
                                    <option value="Mesa Nahuel" <?= $datos['orden_ubicacion_fisica'] == 'Mesa Nahuel' ? 'selected' : '' ?>>Mesa Nahuel</option>
                                    <option value="Mesa Anabela" <?= $datos['orden_ubicacion_fisica'] == 'Mesa Anabela' ? 'selected' : '' ?>>Mesa Anabela</option>
                                    <option value="Mesa Augusto" <?= $datos['orden_ubicacion_fisica'] == 'Mesa Augusto' ? 'selected' : '' ?>>Mesa Augusto</option>
                                    <option value="Mesa PC" <?= $datos['orden_ubicacion_fisica'] == 'Mesa PC' ? 'selected' : '' ?>>Mesa PC</option>
                                    <option value="Verificacion" <?= $datos['orden_ubicacion_fisica'] == 'Verificacion' ? 'selected' : '' ?>>Verificacion</option>
                                    <option value="Reparar" <?= $datos['orden_ubicacion_fisica'] == 'Reparar' ? 'selected' : '' ?>>Reparar</option>
                                    <option value="Esperando Rptos" <?= $datos['orden_ubicacion_fisica'] == 'Esperando Rptos' ? 'selected' : '' ?>>Esperando Repuestos</option>
                                    <option value="Presupuestado Central" <?= $datos['orden_ubicacion_fisica'] == 'Presupuestado Central' ? 'selected' : '' ?>>Presupuestado Central</option>
                                    <option value="Presupuestado San Martin" <?= $datos['orden_ubicacion_fisica'] == 'Presupuestado San Martin' ? 'selected' : '' ?>>Presupuestado San Martin</option>
                                    <option value="Presupuestado Chang Mas" <?= $datos['orden_ubicacion_fisica'] == 'Presupuestado Chang Mas' ? 'selected' : '' ?>>Presupuestado Chang Mas</option>
                                    <option value="Equipos Nuestros" <?= $datos['orden_ubicacion_fisica'] == 'Equipos Nuestros' ? 'selected' : '' ?>>Equipos Nuestros</option>
                                    <option value="Equipos para Prestar" <?= $datos['orden_ubicacion_fisica'] == 'Equipos para Prestar' ? 'selected' : '' ?>>Equipos para Prestar</option>
                                    <option value="Reparado" <?= $datos['orden_ubicacion_fisica'] == 'Reparado' ? 'selected' : '' ?>>Presupuestado Central</option>
                                    <option value="Derivar Chango Mas" <?= $datos['orden_ubicacion_fisica'] == 'Derivar Chango Mas' ? 'selected' : '' ?>>Derivar Chango Mas</option>
                                    <option value="Derivar San Martin" <?= $datos['orden_ubicacion_fisica'] == 'Derivar San Martin' ? 'selected' : '' ?>>Derivar San Martin</option>
                                    <option value="No Va" <?= $datos['orden_ubicacion_fisica'] == 'No Va' ? 'selected' : '' ?>>No Va</option>
                                    <option value="No Va Tablet" <?= $datos['orden_ubicacion_fisica'] == 'No Va Tablet' ? 'selected' : '' ?>>No Va Tablet</option>
                                    <option value="A Reparar PC" <?= $datos['orden_ubicacion_fisica'] == 'A Reparar PC' ? 'selected' : '' ?>>A Reparar PC</option>
                                    <option value="Presupuestado PC" <?= $datos['orden_ubicacion_fisica'] == 'Presupuestado PC' ? 'selected' : '' ?>>Presupuestado PC</option>
                                    <option value="Esperando repuestos PC" <?= $datos['orden_ubicacion_fisica'] == 'Esperando repuestos PC' ? 'selected' : '' ?>>Esperando repuestos PC</option>
                                    <option value="Verificacion PC" <?= $datos['orden_ubicacion_fisica'] == 'Verificacion PC' ? 'selected' : '' ?>>Verificacion PC</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Columna derecha: falla y observaciones -->
                <div class="column is-half">
                    <div class="control">
                        <h3>Observaciones</h3>
                        <textarea class="textarea"  name="orden_observaciones" ><?php echo $datos['orden_observaciones']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column has-text-centered">
                    <div class="control">
                        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-infTec" >
                            Informe tecnico
                        </button>
                        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-pay" >
                            Registrar pago
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- DETALLES DEL EQUIPO -->
        <div class="box">
            <h3 class="title is-4">Detalles del equipo</h3>
            <div class="columns">
                <!-- Columna izquierda: Marca, Modelo -->
                <div class="column is-one-third">
                    <div class="control">
                        <div class="full-width sale-details text-condensedLight">
                            <div class="has-text-weight-bold">Marca</div>
                            <span class="has-text-bold"><?php echo $datos['orden_equipo_marca']; ?></span>
                        </div>
                    </div>

                    <div class="control">
                        <div class="full-width sale-details text-condensedLight">
                            <div class="has-text-weight-bold">Modelo</div>
                            <span class="has-text-bold"><?php echo $datos['orden_equipo_modelo']; ?></span>
                        </div>
                    </div>

                   <?php
                   if($datos['orden_equipo_marca']=='Apple'){
                    ?>
                    <div class="control">
                        <div class="full-width sale-details text-condensedLight">
                            <div class="has-text-weight-bold">Email iCloud</div>
                            <span class="has-text-bold"><?php echo $datos['orden_equipo_email']; ?></span>
                        </div>
                    </div>
                    <div class="control">
                        <div class="full-width sale-details text-condensedLight">
                            <div class="has-text-weight-bold">Pass iCloud</div>
                            <span class="has-text-bold"><?php echo $datos['orden_equipo_pass']; ?></span>
                        </div>
                    </div>
                    
                    <?php
                   }
                   ?>

                    <div class="control">
                        <div class="full-width sale-details text-condensedLight">
                            <div class="has-text-weight-bold">Ingresa</div>
                            <span class="has-text-bold"><?php echo $datos['orden_equipo_ingresa_encendido']; ?></span>
                        </div>
                    </div>

                    <div class="control">
                        <div class="full-width sale-details text-condensedLight">
                            <div class="has-text-weight-bold mr-5">Contrasena</div>
                            <input class="has-text-bold input is-small" type="text" name="orden_equipo_contrasena" value="<?php echo $datos['orden_equipo_contrasena']; ?>">
                        </div>
                    </div>
                </div>

                <!-- Columna central: Accesorios -->
                <div class="column is-one-third">
                    <div class="control">
                        <label>Accesorios</label>
                        <textarea readonly class="textarea" name="orden_accesorios"><?php echo $datos['orden_accesorios']; ?></textarea>
                    </div>
                </div>

                <!-- Columna derecha: Detalles físicos -->
                <div class="column is-one-third">
                    <div class="control">
                        <label>Detalles físicos</label>
                        <?php if($datos['orden_equipo_detalles_fisicos'] != "") {?>
                            <textarea readonly class="textarea" name="orden_equipo_detalles_fisicos"><?php echo $datos['orden_equipo_detalles_fisicos']; ?></textarea>
                        <?php } else {?>
                            <p class="textarea">No</p>
                        <?php }?>
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

    <div class="container" id="productos">
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

            <div class="table-container" id="agregar-producto">
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
<div class="modal is-fullscreen" id="modal-js-infTec">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title is-uppercase has-text-weight-bold is-size-5">
                <i class="fas fa-search"></i> &nbsp; Informe técnico
            </p>
            <button id="cerrarModal" class="safedelete" type="button" aria-label="close"></button>
        </header>
        <section class="modal-card-body is-size-7">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale">
                <input type="hidden" name="modulo_orden" value="registrar_informe_tecnico">
                <input class="input" name="orden_codigo" type="hidden" readonly value="<?php echo $datos['orden_codigo'] ?>">

                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <div class="full-width sale-details text-condensedLight">
                                <div class="has-text-weight-bold">Marca</div>
                                <span class="has-text-bold"><?php echo $datos['orden_equipo_marca']; ?></span>
                            </div>
                        </div>

                        <div class="control">
                            <div class="full-width sale-details text-condensedLight">
                                <div class="has-text-weight-bold">Modelo</div>
                                <span class="has-text-bold"><?php echo $datos['orden_equipo_modelo']; ?></span>
                            </div>
                        </div>

                        <div class="control p-1">
                            <label for="" class="has-text-weight-bold">Falla: </label>
                            <textarea class="textarea is-small" name="" readonly><?php echo $datos['orden_falla']; ?></textarea>
                        </div>
                        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-repuesto" >
                            Pedir repuesto
                        </button>
                        <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-registrar-verificacion" >
                            Verificar
                        </button>
                    </div>

                    <?php
                    $id_usuario = $_SESSION['usuario_nombre'];
                    $fecha_actual = date('Y-m-d'); 
                    $hora_actual = date('H:i:s'); 
                    ?>
                    <!--
                    <div class="column"> 
                        <div class="control">
                            <label for="" class="label">Informe técnico: </label>
                            <textarea class="textarea is-small" spellcheck="true" style="height: 200px;" name="orden_informe_tecnico" id="orden_informe_tecnico"><?php echo $datos['orden_informe_tecnico']; ?></textarea>
                        </div>
                    </div>  -->
                    <div class="column"> 
                        <div class="control">
                            <label for="" class="label">Informe técnico: </label>
                            <textarea class="textarea is-small" spellcheck="true" style="height: 180px;" name="orden_informe_tecnico"></textarea>
                        </div>
                        <div class="control">
                            <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-infTecCompleto" >
                                Informe tecnico completo
                            </button>
                        </div>
                    </div>
                </div>

                <div class="columns is-vcentered is-centered">
                    <div class="column has-text-centered">
                        <h3 class="title is-5">TOTALES REPARACIÓN</h3>
                        <div class="columns is-centered is-mobile">
                            <div class="column is-half">
                                <div class="field">
                                    <label class="label has-text-centered is-size-7">Importe</label>
                                    <div class="control">
                                        <input class="input is-small" type="text" value="<?php echo $datos['orden_total_lista']; ?>" name="orden_total_lista" id="orden_total_lista" onkeyup="calcularDcto()">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-half">
                                <div class="field">
                                    <label class="label has-text-centered is-size-7">Dcto efectivo</label>
                                    <div class="control">
                                        <input class="input is-small" type="text" value="<?php echo $datos['orden_total_efectivo']; ?>" name="orden_total_efectivo" id="orden_total_efectivo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="columns is-vcentered is-centered">
                    <div class="column has-text-centered">
                        <div class="columns is-centered is-mobile">
                            <div class="column is-half">
                                <div class="field">
                                    <div class="control">
                                        <label for="" class="label">Fecha aceptada: </label>
                                        <input class="input" type="date" name="orden_fecha_aceptada" value="<?php echo $datos['orden_fecha_aceptada']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-half">
                                <div class="field">
                                    <label for="" class="label">Fecha prometida: </label>
                                    <input class="input" type="date" name="orden_fecha_prometida" value="<?php echo $datos['orden_fecha_prometida']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="has-text-centered">
                    <button type="submit" class="button is-link is-success">Guardar</button>
                </p>
            </form>
        </section>
    </div>
</div>

<!-- modal inf tec complet -->
<div class="modal" id="modal-js-infTecCompleto">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Rechazar orden</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <div class="control">
                    <label for="" class="label">Informe técnico: </label>
                    <textarea disabled class="textarea is-small" spellcheck="true" style="height: 200px;" name="orden_informe_tecnico" id="orden_informe_tecnico"><?php echo $datos['orden_informe_tecnico']; ?></textarea>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Modal registrar pedido repuesto -->
<div class="modal is-fullscreen" id="modal-js-repuesto">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title is-uppercase has-text-weight-bold is-size-5">
                <i class="fas fa-search"></i> &nbsp; Pedir repuesto
            </p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body is-size-7">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/repuestoAjax.php" method="POST" autocomplete="off" name="formsale">
                <input class="input" name="id_orden" type="hidden" readonly value="<?php echo $datos['id_orden'] ?>">
                <input type="hidden" name="modulo_repuesto" value="registrar_pedido">

                    <div class="columns">
                        <div class="column">

                            <label>Seccion <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                            <div class="select">
                                <select name="id_seccion" >
                                    <option value="" selected="" >Seleccione una opción</option>
                                    <?php
                                        $datos_seccion=$insLogin->seleccionarDatos("Normal","seccion_repuesto","*",0);

                                        $cc=1;
                                        while($campos_seccion=$datos_seccion->fetch()){
                                            echo '<option value="'.$campos_seccion['id_seccion_repuesto'].'">'.$campos_seccion['seccion_repuesto_descripcion'].'</option>';
                                            $cc++;
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                            // Obtener las marcas de la base de datos
                            $datos_marca = $insLogin->seleccionarDatos("Unico", "marca", "marca_descripcion", $datos['orden_equipo_marca']);
                            $datos_marca = $datos_marca->fetch();
                            $datos_modelo = $insLogin->seleccionarDatos("Unico", "modelo", "modelo_descripcion", $datos['orden_equipo_modelo']);
                            $datos_modelo = $datos_modelo->fetch();
                        ?>
                        <input type="hidden" name="id_marca" id="hidden_id_marca" value="<?php echo $datos_marca['id_marca']; ?>">
                        <input type="hidden" name="id_modelo" id="hidden_id_modelo" value="<?php echo $datos_modelo['id_modelo']; ?>">
                        <div class="column">
                            <label>Marca <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                            <div class="select">
                                <select disabled>
                                    <option value="" selected="">Seleccione una opción</option>
                                    <?php
                                        // Obtener las marcas de la base de datos
                                        $datos_marca = $insLogin->seleccionarDatos("Unico", "marca", "marca_descripcion", $datos['orden_equipo_marca']);
                                        while ($campos_marca = $datos_marca->fetch()) {
                                            echo '<option selected value="' . $campos_marca['id_marca'] . '">' . $campos_marca['marca_descripcion'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="column">
                            <label>Modelo <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                            <div class="select">
                                <select disabled>
                                    <option value="" selected="">Seleccione una opción</option>
                                    <?php
                                        // Obtener las marcas de la base de datos
                                        $datos_modelo = $insLogin->seleccionarDatos("Unico", "modelo", "modelo_descripcion", $datos['orden_equipo_modelo']);
                                        while ($campos_modelo = $datos_modelo->fetch()) {
                                            echo '<option selected value="' . $campos_modelo['id_modelo'] . '">' . $campos_modelo['modelo_descripcion'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="column">
                            <label>Color<?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input class="input" type="text" name="repuesto_color" maxlength="40" >
                        </div>
                    </div> 
                    
                    <input class="input" type="hidden" name="pedido_repuesto_responsable" value="<?php echo $_SESSION['usuario_nombre'] ?>">  
                            
                    <p class="has-text-centered">
                        <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
                        <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
                    </p>
                    <p class="has-text-centered pt-1">
                        <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
                    </p>
            </form>
        </section>
        <div class="table-container is-size-7">
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th class="has-text-centered">Repuesto</th>
                        <th class="has-text-centered">Fecha</th>
                        <th class="has-text-centered">Responsable</th>
                        <th class="has-text-centered">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $repuestos = $insLogin->seleccionarDatos("Unico", "pedido_repuesto", "id_orden", $datos['orden_codigo']);
                        foreach ($repuestos as $rows) {
                            ?>
                            <tr class="has-text-centered">
                                <td><?php echo $rows['pedido_repuesto_descripcion'].'-'. $rows['pedido_repuesto_descripcion']?></td>
                                <td><?php echo $rows['pedido_repuesto_fecha'].'-'. $rows['pedido_repuesto_hora']?></td>
                                <td><?php echo $rows['pedido_repuesto_responsable']?></td>
                                <td><?php echo $rows['pedido_estado']?></td>
                            </tr>
                            <?php
                        }
                    ?>
                    <tr class="has-text-centered">

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal registrar verificacion -->
<?php
    $consulta = "SELECT * FROM verificacion WHERE orden_codigo = $datos[orden_codigo] ORDER BY id_verificacion DESC LIMIT 1";
    $verificaciones = $insLogin->Consultar($consulta);
    $verificaciones = $verificaciones->fetch();
    if (!$verificaciones) {
        $verificaciones = [
            'verificacion_vida' => '', 
            'verificacion_fecha' => '', 
            'verificacion_hora_inicio' => '', 
            'verificacion_duracion' => '',
            'verificacion_hora_fin' => '', 
            'verificacion_estado' => '',
            'verificacion_estacion_sig' => '', 
            'verificacion_tecnico_asignado' => '',
            'verificacion_responsable' => '',
            'verificacion_detalles' => ''
        ];
    }
?>
<div class="modal is-fullscreen" id="modal-js-registrar-verificacion">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title is-uppercase has-text-weight-bold is-size-5">
                <i class="fas fa-search"></i> &nbsp; Verificacion
            </p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body is-size-7">
            <?php 
                if($verificaciones['verificacion_vida'] == "" || $verificaciones['verificacion_vida'] == "finalizo"){
            ?>
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale">
                <input class="input" name="orden_codigo" type="hidden" readonly value="<?php echo $datos['orden_codigo'] ?>">
                <input class="input" name="verificacion_responsable" type="hidden" readonly value="<?php echo $_SESSION['usuario_nombre'] ?>">
                <input type="hidden" name="modulo_orden" value="iniciar_verificacion">

                <div class="columns">
                    <div class="column">
                        <label class="has-text-weight-bold">Detalles</label><br>
                        <textarea class="textarea" name="verificacion_detalles" id="textarea-verificacion"></textarea>
                    </div>
                </div>
                     
                <p class="has-text-centered">
                    <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
                    <button type="submit" class="button is-info is-rounded"><i class="far fa-clock"></i> &nbsp; Iniciar</button>
                </p>
                <p class="has-text-centered pt-1">
                    <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
                </p>

            </form>
            <?php }else{?>
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale">
                <input class="input" name="orden_codigo" type="hidden" readonly value="<?php echo $datos['orden_codigo'] ?>">
                <input class="input" name="verificacion_responsable" type="hidden" readonly value="<?php echo $_SESSION['usuario_nombre'] ?>">
                <input type="hidden" name="modulo_orden" value="finalizar_verificacion">
                <input class="input" name="id_verificacion" type="hidden" readonly value="<?php echo $verificaciones['id_verificacion'] ?>">

                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <label class="has-text-weight-bold">Hora fin:<?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input required type="time" id="hora_inicio" class="input" name="verificacion_hora_fin">
                        </div>
                        <div class="control">
                            <label class="has-text-weight-bold">Estado de la verificacion <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                            <div class="select">
                                <select name="verificacion_estado" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="OK">OK</option>
                                    <option value="NO">NO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="column">
                        <div class="control">
                            <label class="has-text-weight-bold">Siguiente estacion <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                            <div class="select">
                                <select name="verificacion_estacion_sig" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="Reparar">Reparar</option>
                                    <option value="Ensamblar">Ensamblar</option>
                                    <option value="Reparada">Reparada</option>
                                </select>
                            </div>
                        </div>
                        <div class="control">
                            <label class="has-text-weight-bold">Tecnico asignado</label><br>
                            <div class="select">
                                <select name="verificacion_tecnico_asignado">
                                    <option value="" selected>Seleccione una opción</option>
                                    <?php
                                        // Obtener los técnicos
                                        $datos_tecnico = $insLogin->seleccionarDatos("Unico", "usuario", "usuario_rol", "Tecnico");
                                        while ($campos_tecnico = $datos_tecnico->fetch()) {
                                            // Comparar el nombre completo del técnico con el valor de verificacion_tecnico_asignado
                                            $selected = ($campos_tecnico['usuario_nombre_completo'] == $datos['verificacion_tecnico_asignado']) ? 'selected' : '';
                                            echo '<option value="' . $campos_tecnico['usuario_nombre_completo'] . '" ' . $selected . '>' . $campos_tecnico['usuario_nombre_completo'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="column">
                        <label class="has-text-weight-bold">Detalles</label><br>
                        <textarea class="textarea" name="verificacion_detalles" id="textarea-verificacion"></textarea>
                    </div>
                    
                </div>
                
                                        
                <p class="has-text-centered">
                    <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
                    <button type="submit" class="button is-info is-rounded"><i class="fa-solid fa-check"></i> &nbsp; Finalizar</button>                    
                </p>
                <p class="has-text-centered pt-1">
                    <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
                </p>
            </form>
            <?php } ?>
            <script>
                // Preseleccionar la hora actual en "Hora de inicio"
                document.addEventListener("DOMContentLoaded", () => {
                    const horaInicioInput = document.getElementById("hora_inicio");
                    if (horaInicioInput) {
                        const ahora = new Date();
                        const hora = ahora.getHours().toString().padStart(2, "0");
                        const minutos = ahora.getMinutes().toString().padStart(2, "0");
                        horaInicioInput.value = `${hora}:${minutos}`;
                    }
                });
            </script>
        </section>
        <div class="table-container is-size-7">
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th class="has-text-centered">Fecha</th>
                        <th class="has-text-centered">Inicio</th>
                        <th class="has-text-centered">Duracion</th>
                        <th class="has-text-centered">Fin</th>
                        <th class="has-text-centered">Estado</th>
                        <th class="has-text-centered">Estacion sig</th>
                        <th class="has-text-centered">Tecnico asig</th>
                        <th class="has-text-centered">Responsable</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $verificaciones = $insLogin->seleccionarDatos("Unico", "verificacion", "orden_codigo", $datos['orden_codigo']);
                        foreach ($verificaciones as $rows) {
                            ?>
                            <tr class="has-text-centered">
                                <td><?php echo $rows['verificacion_fecha']?></td>
                                <td><?php echo $rows['verificacion_hora_inicio']?></td>
                                <td><?php echo $rows['verificacion_duracion']?>min</td>
                                <td><?php echo $rows['verificacion_hora_fin']?></td>
                                <td><?php echo $rows['verificacion_estado']?></td>
                                <td><?php echo $rows['verificacion_estacion_sig']?></td>
                                <td><?php echo $rows['verificacion_tecnico_asignado']?></td>
                                <td><?php echo $rows['verificacion_responsable']?></td>
                            </tr>
                            <?php
                        }
                    ?>
                    <tr class="has-text-centered">

                    </tr>
                </tbody>
            </table>
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
                        <span id="orden_total_lista"><?php echo MONEDA_SIMBOLO . number_format($datos['orden_total_lista'], MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR) . " " . MONEDA_NOMBRE; ?></span><br>
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
                                $saldo_lista =  $datos['orden_total_lista']-$suma_pagos['suma_pagos'];
                                $saldo_efectivo =  $datos['orden_total_efectivo']-$suma_pagos['suma_pagos'];
                                echo MONEDA_SIMBOLO . number_format($saldo_lista, MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR) . " " . MONEDA_NOMBRE;
                            ?>
                        </span>
                        <input type="hidden" name="saldo" id="saldo_input" value="<?php echo $saldo_lista; ?>">
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
                            <input class="input" type="date" name="orden_fecha_aceptada" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="column">
                            <label for="" class="label">Fecha prometida: </label>
                            <input class="input" type="date" name="orden_fecha_prometida">
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

<!-- ORDEN sin reparacion-->
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

<!-- ORDEN reparar -->
<div class="modal" id="modal-js-a-reparar">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; A REPARAR</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale" >
                    <input type="hidden" name="modulo_orden" value="cambiar_estado_orden">
                    <input type="hidden" name="orden_codigo" id="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
                    <input type="hidden" name="orden_estado" value="A REPARAR">
                    <p class="has-text-centered">
                        ESTAS SEGURO QUE QUIERES ESTABLECER LA ORDEN COMO 'A REPARAR'?
                    </p>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-link is-light">Aceptar</button>
                    </p>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- ORDEN PRESUPUESTAR -->
<div class="modal" id="modal-js-presupuestado">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; PRESUPUESTADA</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale" >
                    <input type="hidden" name="modulo_orden" value="cambiar_estado_orden">
                    <input type="hidden" name="orden_codigo" id="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
                    <input type="hidden" name="orden_estado" value="PRESUPUESTADO">
                    <p class="has-text-centered">
                        ESTAS SEGURO QUE QUIERES ESTABLECER LA ORDEN COMO 'PRESUPUESTADA'?
                    </p>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-link is-light">Aceptar</button>
                    </p>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- ORDEN ensamblar -->
<div class="modal" id="modal-js-ensamblar">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; A ENSAMBLAR</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale" >
                    <input type="hidden" name="modulo_orden" value="cambiar_estado_orden">
                    <input type="hidden" name="orden_codigo" id="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
                    <input type="hidden" name="orden_estado" value="A ENSAMBLAR">
                    <p class="has-text-centered">
                        ESTAS SEGURO QUE QUIERES ESTABLECER LA ORDEN COMO 'A ENSAMBLAR'?
                    </p>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-link is-light">Aceptar</button>
                    </p>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- ORDEN verificar -->
<div class="modal" id="modal-js-verificar">
    <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; A VERIFICAR</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ordenAjax.php" method="POST" autocomplete="off" name="formsale" >
                    <input type="hidden" name="modulo_orden" value="cambiar_estado_orden">
                    <input type="hidden" name="orden_codigo" id="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
                    <input type="hidden" name="orden_estado" value="VERIFICAR">
                    <p class="has-text-centered">
                        ESTAS SEGURO QUE QUIERES ESTABLECER LA ORDEN COMO 'VERIFICAR'?
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
                            <input class="input" type="date" name="orden_fecha_entregada" value="<?php echo date("Y-m-d"); ?>">
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

        const textarea = document.getElementById("orden_informe_tecnico");
        textarea.addEventListener("keydown", function (event) {
            if (event.key.length === 1 && !textarea.dataset.prefixed) { // Solo cuando presiona una tecla que genera texto y no está prefijado
                const idUsuario = "<?php echo $id_usuario; ?>";
                const fecha = "<?php echo $fecha_actual; ?>";
                const hora = new Date().toLocaleTimeString(); // Captura la hora actual en JS

                // Formato que quieres insertar antes de escribir
                const prefix = `[${idUsuario} - ${fecha} ${hora}]: \n      `;

                // Obtener la posición actual del cursor
                const startPos = textarea.selectionStart;
                const endPos = textarea.selectionEnd;

                // Insertar el prefijo en la posición actual del cursor
                const textBefore = textarea.value.substring(0, startPos);
                const textAfter = textarea.value.substring(endPos, textarea.value.length);
                textarea.value = textBefore + prefix + textAfter;

                // Mover el cursor a la posición después del prefijo
                textarea.setSelectionRange(startPos + prefix.length, startPos + prefix.length);

                textarea.dataset.prefixed = true; // Marcar como prefijado
            }
        });

        textarea.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                event.preventDefault(); // Evita el comportamiento predeterminado de Enter

                // Obtener la posición actual del cursor
                const startPos = textarea.selectionStart;
                const endPos = textarea.selectionEnd;

                // Insertar un salto de línea seguido de seis espacios
                const textBefore = textarea.value.substring(0, startPos);
                const textAfter = textarea.value.substring(endPos, textarea.value.length);
                const newText = textBefore + "\n      " + textAfter;
                textarea.value = newText;

                // Mover el cursor a la posición después de los seis espacios
                textarea.setSelectionRange(startPos + 7, startPos + 7);
            }
        });
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
                // Desplazarse a la sección de productos después de agregar
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
                // Desplazarse a la sección de productos después de agregar
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

    //si se marca prometido para en la fecha se activa un input date
    function otroEquipo(show) {
        const dateField = document.getElementById('otro_equipo_field');
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

    function calcularDcto(){
        const importe = document.getElementById('orden_total_lista').value;
        const dcto = importe * 0.2;
        const final = importe - dcto;
        document.getElementById('orden_total_efectivo').value = final.toFixed(2);
    }
</script>