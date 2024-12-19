<div class="container is-fluid">
	<h1 class="title">Ventas</h1>
	<h2 class="subtitle"><i class="fas fa-shopping-bag fa-fw"></i> &nbsp; Información de venta</h2>
</div>

<div class="container pb-6 pt-2 is-max-desktop mb-2">
	<?php
	
		include "./app/views/includes/btn_back.php";

		$id=$insLogin->limpiarCadena($url[1]);

		$datos=$insLogin->seleccionarDatos("Unico","venta_equipo","id_equipo",$id);
		
		if($datos->rowCount()==1){
			$datos_venta=$datos->fetch();
            $id_cliente = $datos_venta['id_cliente'];
            $datos_cliente=$insLogin->seleccionarDatos("Unico","cliente","id_cliente",$id_cliente);
            $datos_cliente = $datos_cliente->fetch();

            $id_equipo = $datos_venta['id_equipo'];
            $datos_equipo=$insLogin->seleccionarDatos("Unico","equipo","id_equipo",$id_equipo);
            $datos_equipo = $datos_equipo->fetch();
	?>
	<h2 class="title has-text-centered ">Datos de la venta <?php echo " (".$id.")"; ?></h2>
	<div class="columns pb-1 pt-2">
		<div class="column">

			<div class="full-width sale-details text-condensedLight">
				<div class="has-text-weight-bold">Fecha</div>
				<span class="has-text-link"><?php echo date("d-m-Y", strtotime($datos_venta['venta_equipo_fecha']))." ".$datos_venta['venta_equipo_hora']; ?></span>
			</div>

			<div class="full-width sale-details text-condensedLight">
				<div class="has-text-weight-bold">Código de venta</div>
				<span class="has-text-link"><?php echo $datos_venta['venta_equipo_codigo']; ?></span>
			</div>

		</div>

		<div class="column">

			<div class="full-width sale-details text-condensedLight">
				<div class="has-text-weight-bold">Vendedor</div>
				<span class="has-text-link"><?php echo $datos_venta['venta_equipo_vendedor']; ?></span>
			</div>

			<div class="full-width sale-details text-condensedLight">
				<div class="has-text-weight-bold">Cliente</div>
				<span class="has-text-link"><?php echo $datos_cliente['cliente_nombre_completo']; ?></span>
			</div>

		</div>

		<div class="column">

			<div class="full-width sale-details text-condensedLight">
				<div class="has-text-weight-bold">Total</div>
				<span class="has-text-link"><?php echo MONEDA_SIMBOLO.number_format($datos_venta['venta_equipo_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE; ?></span>
			</div>

		</div>

	</div>

	<div class="columns pb-6 pt-6 is-max-desktop">
		<div class="column">
			<div class="table-container">
            <table class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th class="has-text-centered" style="border: 1px solid black;">Modelo</th>
                    <th class="has-text-centered" style="border: 1px solid black;">Almac.</th>
                    <th class="has-text-centered" style="border: 1px solid black;">RAM</th>
                    <th class="has-text-centered" style="border: 1px solid black;">Color</th>
                    <th class="has-text-centered" style="border: 1px solid black;">IMEI</th>
                    <th class="has-text-centered" style="border: 1px solid black;">F. de pago</th>
                    <th class="has-text-centered" style="border: 1px solid black;">Precio</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos_equipo['equipo_descripcion']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos_equipo['equipo_almacenamiento']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos_equipo['equipo_ram']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos_equipo['equipo_color']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos_equipo['equipo_imei']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;"><?php echo $datos_venta['venta_equipo_financiacion']?></td>
                    <td class="has-text-centered" style="border: 1px solid black;">
                        <?php echo MONEDA_SIMBOLO.number_format($datos_venta['venta_equipo_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE ?>
                    </td>
                </tr>
            </tbody>
        </table>
            </div>
		</div>
	</div>

	<div class="columns pb-6 is-max-desktop">
		<p class="has-text-centered full-width">
			<?php
			echo '<button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-pay" >
                                Registrar pago
                            </button> &nbsp;&nbsp;'
			?>
		</p>
	</div>
	<?php
			include "./app/views/includes/print_invoice_script.php";
		}else{
			include "./app/views/includes/error_alert.php";
		}
	?>
</div>

<!-- Modal registrar pago -->
<div class="modal" id="modal-js-pay">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Pagos de la venta: </p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/pagoAjax.php" method="POST" autocomplete="off" name="formsale">
                <input type="hidden" name="modulo_pago" value="registrar_pago_venta">
                <input type="hidden" name="venta_codigo" id="venta_codigo">
                <div class="columns">
                    <div class="column">
                        <label for="" class="label">Venta código: </label>
                        <input readonly name="venta_codigo" class="input" type="text" value="<?php echo $datos_venta['venta_codigo']; ?>">
                    </div>
                    <div class="column">
                        <label for="" class="label">Fecha: </label>
                        <input name="venta_pago_fecha" class="input" type="date" value="<?php echo date("Y-m-d"); ?>">
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <label for="" class="label">Forma de pago: </label>
                        <div class="select">
							<select name="venta_pago_forma">
								<option value="" selected="" >Seleccione una opción</option>
								<?php
									echo $insLogin->generarSelect(FORMAS_PAGO,"VACIO");
								?>
							</select>
						</div>
                    </div>
                    <div class="column">
                        <label for="" class="label">Importe: </label>
                        <input class="input" type="number" name="venta_pago_importe" id="venta_pago_importe">
                    </div>
                    <div class="column">
                        <label for="" class="label">Detalle: </label>
                        <input class="input" type="text" name="venta_pago_detalle">
                    </div>
                </div>
                <div class="columns">
                    <div class="column">Total de la venta: <?php echo MONEDA_SIMBOLO.number_format($datos_venta['venta_importe'], MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR); ?></div>
                    <div class="column">
                        <?php
                        $suma_pagos = $insLogin->seleccionarDatos("Normal", "pago_venta WHERE venta_codigo = '".$datos_venta['venta_codigo']."'", "SUM(venta_pago_importe) as suma_pagos", 0);
                        if ($suma_pagos->rowCount() >= 1) {
                            $suma_pagos = $suma_pagos->fetch();
                            $suma_pagos_value = $suma_pagos['suma_pagos'] !== NULL ? $suma_pagos['suma_pagos'] : 0;
                        } else {
                            $suma_pagos_value = 0;
                        }
                        echo "Suma de sus pagos: ".MONEDA_SIMBOLO.number_format($suma_pagos_value, MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
                        ?>
                    </div>
                    <div class="column">
                        <?php
                        $saldo = $datos_venta['venta_importe'] - $suma_pagos_value;
                        echo "Saldo: ".MONEDA_SIMBOLO.number_format($saldo, MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
                        ?>
                        <input type="hidden" name="saldo" value="<?php echo $saldo; ?>">
                    </div>
                </div>
                <p class="has-text-centered">
					<button type="submit" class="button is-link is-light" id="btnEnviar">Registrar pago</button>
					<button type="button" class="button is-link is-light" id="btnSaldar">Saldar total</button>
				</p>
            </form>
        </section>
    </div>
</div>

<script>
	
	document.getElementById('btnSaldar').addEventListener('click', function () {
	// Obtener el saldo desde el campo oculto
	const saldo = document.querySelector('input[name="saldo"]').value;

	// Insertar el saldo en el campo "Importe"
	const inputImporte = document.getElementById('venta_pago_importe');
	inputImporte.value = saldo;

	// Simular clic en el botón "Registrar pago" (submit)
	document.getElementById('btnEnviar').click();
	});
</script>