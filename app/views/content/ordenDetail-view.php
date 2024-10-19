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
            $id_cliente = $datos['id_cliente'];
            $datos_cliente = $insLogin->seleccionarDatos("Unico","cliente","id_cliente",$id_cliente);
            $datos_cliente = $datos_cliente->fetch();
	?>

	<h2 class="title has-text-centered">INFORMACION ORDEN  <?php echo $datos['orden_codigo'] ; ?></h2>
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
                        <input type="text" readonly class="input" name="orden_telefonista" value="<?php echo $datos['orden_telefonista'] ?>">
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
                                <p>No</p>
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
            <h3 class="title is-4">Detalles del quipo</h3>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Marca</label><br>
                        <div class="select">
                            <select name="id_marca" >
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
                </div>

                <div class="column">
                    <div class="control">
                    <label>Marca</label><br>
                        <div class="select">
                            <select name="id_modelo" >
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
                </div>
                    
                <div class="column">            
                    <div class="control">
                        <label>N° Serie</label>
                        <input class="input" type="text" value="<?php echo $datos['orden_serie_equipo']; ?>" name="orden_modelo_equipo">
                    </div>
                </div>
            </div>
            <h3>Accesorios</h3>
            <textarea class="textarea"  name="orden_accesorios" ><?php echo $datos['orden_accesorios']; ?></textarea>
        </div>
        

        <!-- ESTADO DE LA ORDEN -->
        <h2 class="subtitle">Estado de la orden</h2>
        <div class="box">
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
                        <p class="has-text-weight-semibold" style="margin-top: 0; font-size: 1.2rem;"><?php echo $datos['orden_total']; ?></p>
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




        <div class="columns">
            
        </div>
        
		<p class="has-text-centered">
			<button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
			<button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
		</p>
		<p class="has-text-centered pt-1">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
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
                    <input type="hidden" name="orden_codigo" id="orden_codigo" value="<?php echo $datos['orden_codigo']; ?>">
                    <h2 class="subtitle">Orden numero: <?php echo $datos['orden_codigo']; ?></h2>
                    <div class="columns">
                        <div class="column">
                            <label for="" class="label">Informe tecnico: </label>
                            <textarea class="input" style="height: 200px;" name="orden_informe_tecnico" id=""><?php echo $datos['orden_informe_tecnico']; ?></textarea>
                        </div>
                    </div>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-link is-light">Registrar pago</button>
                    </p>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- Modal registrar pago -->
<div class="modal" id="modal-js-pay">
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Pagos de la orden: </p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/pagoAjax.php" method="POST" autocomplete="off" name="formsale" >
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
                        <select name="orden_pago_forma" id="" class="select">
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia</option>
                        </select>
                    </div>
                    <div class="column">
                        <label for="" class="label">Importe: </label>
                        <input class="input" type="number" name="orden_pago_importe">
                    </div>
                    <div class="column">
                        <label for="" class="label">Detalle: </label>
                        <input class="input" type="text" name="orden_pago_detalle">
                    </div>
                </div>
                <div class="columns">
                    <div class="column">Total de la orden: <?php echo MONEDA_SIMBOLO.number_format($datos['orden_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?> </div>
                    <div class="column">
                        <?php
                        $suma_pagos = $insLogin->seleccionarDatos("Normal", "pago_orden WHERE orden_codigo = '".$datos['orden_codigo']."'","SUM(orden_pago_importe) as suma_pagos",0);
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
                                $suma_pagos = $insLogin->seleccionarDatos("Normal", "pago_orden WHERE orden_codigo = '".$datos['orden_codigo']."'","SUM(orden_pago_importe) as suma_pagos",0);
                                if($suma_pagos->rowCount() >= 1){
                                    $suma_pagos = $suma_pagos->fetch();
                                    $saldo = $datos['orden_importe'] - $suma_pagos['suma_pagos'];
                                    echo "Saldo: ".MONEDA_SIMBOLO.number_format($saldo,MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
                                }else{
                                    echo "Saldo: ".MONEDA_SIMBOLO.number_format($datos['orden_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
                                }
                            ?>
                        </div>
                    </div>
                    <div class="container" id="resultado-busqueda"></div>
                    <p class="has-text-centered">
                        <button type="submit" class="button is-link is-light">Registrar pago</button>
                    </p>
                </div>
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



<script>
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