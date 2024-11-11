<div class="container is-fluid">
	<h1 class="title">Ventas</h1>
	<h2 class="subtitle"><i class="fas fa-shopping-bag fa-fw"></i> &nbsp; Información de venta</h2>
</div>

<div class="container pb-6 pt-2 is-max-desktop mb-2">
	<?php
	
		include "./app/views/includes/btn_back.php";

		$code=$insLogin->limpiarCadena($url[1]);

		$datos=$insLogin->seleccionarDatos("Normal","venta INNER JOIN cliente ON venta.id_cliente=cliente.id_cliente INNER JOIN usuario ON venta.id_usuario=usuario.id_usuario INNER JOIN caja ON venta.id_caja=caja.id_caja WHERE (venta_codigo='".$code."')","*",0);
		
		if($datos->rowCount()==1){
			$datos_venta=$datos->fetch();
	?>
	<h2 class="title has-text-centered ">Datos de la venta <?php echo " (".$code.")"; ?></h2>
	<div class="columns pb-1 pt-2">
		<div class="column">

			<div class="full-width sale-details text-condensedLight">
				<div class="has-text-weight-bold">Fecha</div>
				<span class="has-text-link"><?php echo date("d-m-Y", strtotime($datos_venta['venta_fecha']))." ".$datos_venta['venta_hora']; ?></span>
			</div>

			<div class="full-width sale-details text-condensedLight">
				<div class="has-text-weight-bold">Código de venta</div>
				<span class="has-text-link"><?php echo $datos_venta['venta_codigo']; ?></span>
			</div>

		</div>

		<div class="column">

			<div class="full-width sale-details text-condensedLight">
				<div class="has-text-weight-bold">Vendedor</div>
				<span class="has-text-link"><?php echo $datos_venta['usuario_nombre_completo']; ?></span>
			</div>

			<div class="full-width sale-details text-condensedLight">
				<div class="has-text-weight-bold">Cliente</div>
				<span class="has-text-link"><?php echo $datos_venta['cliente_nombre_completo']; ?></span>
			</div>

		</div>

		<div class="column">

			<div class="full-width sale-details text-condensedLight">
				<div class="has-text-weight-bold">Total</div>
				<span class="has-text-link"><?php echo MONEDA_SIMBOLO.number_format($datos_venta['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE; ?></span>
			</div>

		</div>

	</div>

	<div class="columns pb-6 pt-6 is-max-desktop">
		<div class="column">
			<div class="table-container">
                <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th class="has-text-centered">#</th>
                            <th class="has-text-centered">Producto</th>
                            <th class="has-text-centered">Cant.</th>
							<th class="has-text-centered">Financiacion</th>
                            <th class="has-text-centered">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        	$detalle_venta=$insLogin->seleccionarDatos("Normal","venta_detalle WHERE venta_codigo='".$datos_venta['venta_codigo']."'","*",0);

                            if($detalle_venta->rowCount()>=1){

                                $detalle_venta=$detalle_venta->fetchAll();
                            	$cc=1;

                                foreach($detalle_venta as $detalle){
                        ?>
                        <tr class="has-text-centered" >
                            <td><?php echo $cc; ?></td>
                            <td><?php echo $detalle['venta_detalle_descripcion_producto']; ?></td>
                            <td><?php echo $detalle['venta_detalle_cantidad_producto']; ?></td>
                            <td><?php echo $detalle['venta_detalle_financiacion_producto'] ?></td>
                            <td><?php echo MONEDA_SIMBOLO.number_format($detalle['venta_detalle_total'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?></td>
                        </tr>
                        <?php
                                $cc++;
                            }
                        ?>
                        <tr class="has-text-centered" >
							<td colspan="3"></td>
							<td class="has-text-weight-bold">
								TOTAL
							</td>
							<td class="has-text-weight-bold">
								<?php echo MONEDA_SIMBOLO.number_format($datos_venta['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?>
							</td>
						</tr>
						<tr class="has-text-centered" >
							<td colspan="3"></td>
							<td class="has-text-weight-bold">
								SUS PAGOS
							</td>
							<td class="has-text-weight-bold">
								<?php
								$suma_pagos = $insLogin->seleccionarDatos("Normal", "pago_venta WHERE venta_codigo = '".$datos_venta['venta_codigo']."'","SUM(venta_pago_importe) as suma_pagos",0);
								if($suma_pagos->rowCount() >= 1){
									$suma_pagos = $suma_pagos->fetch();
									if($suma_pagos['suma_pagos'] !== NULL){
										echo MONEDA_SIMBOLO.number_format($suma_pagos['suma_pagos'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
									}else{
										echo "0.00";
									}
								}else{
									echo "0.00";
								}
								?>
							</td>
						</tr>
						<tr class="has-text-centered" >
							<td colspan="3"></td>
							<td class="has-text-weight-bold">
								SALDO
							</td>
							<td class="has-text-weight-bold">
								<?php
								$suma_pagos = $insLogin->seleccionarDatos("Normal", "pago_venta WHERE venta_codigo = '".$datos_venta['venta_codigo']."'","SUM(venta_pago_importe) as suma_pagos",0);
								if($suma_pagos->rowCount() >= 1){
									$suma_pagos = $suma_pagos->fetch();
									$saldo = $datos_venta['venta_importe'] - $suma_pagos['suma_pagos'];
									echo MONEDA_SIMBOLO.number_format($saldo,MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
								}else{
									echo MONEDA_SIMBOLO.number_format($datos_venta['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
								}
								?>
							</td>
						</tr>
                        <?php
                            }else{
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

	<div class="columns pb-6 is-max-desktop">
		<p class="has-text-centered full-width">
			<?php
			echo '<button type="button" class="button is-link is-light is-medium" onclick="print_invoice(\''.APP_URL.'app/pdf/invoice.php?code='.$datos_venta['venta_codigo'].'\')" title="Imprimir comprobante Nro. '.$datos_venta['id_venta'].'" >
			<i class="fas fa-file-invoice-dollar fa-fw"></i> &nbsp; Comprobante de venta
			</button> &nbsp;&nbsp;'
			?>
		</p>
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
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/pagoAjax.php" method="POST" autocomplete="off" name="formsale" >
                <input type="hidden" name="modulo_pago" value="registrar_pago_venta">
                <input type="hidden" name="venta_codigo" id="venta_codigo">
                <div class="columns">
					<div class="column">
						<label for="" class="label">Venta codigo: </label>
						<input readonly name="venta_codigo" class="input" type="text" value="<?php echo $datos_venta['venta_codigo']?> ">
					</div>
					<div class="column">
						<label for="" class="label">Fecha: </label>
						<input name="venta_pago_fecha" class="input" name="" type="date" value="<?php echo date("Y-m-d"); ?>" >
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<label for="" class="label">Forma de pago: </label>
						<div class="select">
							<select name="venta_pago_forma">
								<option selected disabled>Seleccione una opcion</option>
								<option value="Efectivo">Efectivo</option>
								<option value="Transferencia">Transferencia</option>
							</select>
						</div>
						
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
					<div class="column">Total de la venta: <?php echo $datos_venta['venta_importe'] ?></div>
					<div class="column">
						<?php
						$suma_pagos = $insLogin->seleccionarDatos("Normal", "pago_venta WHERE venta_codigo = '".$datos_venta['venta_codigo']."'","SUM(venta_pago_importe) as suma_pagos",0);
						$suma_pagos = $insLogin->seleccionarDatos("Normal", "pago_venta WHERE venta_codigo = '".$datos_venta['venta_codigo']."'","SUM(venta_pago_importe) as suma_pagos",0);
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
						$suma_pagos = $insLogin->seleccionarDatos("Normal", "pago_venta WHERE venta_codigo = '".$datos_venta['venta_codigo']."'","SUM(venta_pago_importe) as suma_pagos",0);
						if($suma_pagos->rowCount() >= 1){
							$suma_pagos = $suma_pagos->fetch();
							$saldo = $datos_venta['venta_importe'] - $suma_pagos['suma_pagos'];
							echo "Saldo: ".MONEDA_SIMBOLO.number_format($saldo,MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
						}else{
							echo "Saldo: ".MONEDA_SIMBOLO.number_format($datos_venta['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE;
						}
						?>
					</div>
				</div>
				<p class="has-text-centered">
					
					<button type="submit" class="button is-link is-light" >Registrar pago</button>
				</p>
			</form>
		</section>
	</div>
</div>
