
<div class="container is-fluid is-max-desktop">
	<h1 class="title">Sena</h1>
</div>
<div class="container is-max-desktop pb-1 pt-1">
    <?php
	
		include "./app/views/includes/btn_back.php";
        $id_equipo=$insLogin->limpiarCadena($url[1]);

		$datos=$insLogin->seleccionarDatos("Normal","sena INNER JOIN cliente ON sena.id_cliente=cliente.id_cliente INNER JOIN equipo ON sena.id_equipo=equipo.id_equipo INNER JOIN caja ON sena.id_caja=caja.id_caja WHERE sena.id_equipo = '$id_equipo'","*",0);
		$usd_pc = $_SESSION['usd_pc'];
        if($datos->rowCount()==1){
			$datos=$datos->fetch();

            $efectivo_usd = $datos['equipo_costo'] * 1.4;
            $efectivo_ars = ($efectivo_usd * $usd_pc);   
            $precio = $efectivo_ars * 1.4;

            $sin_int_3 = $precio / 3;
            $sin_int_6 = $precio / 6; 
            $fijas_9 = ($efectivo_ars * 1.5) / 9;
            $fijas_12 = ($efectivo_ars * 1.6) / 12;
            $pago1 = $efectivo_ars * 1.1;

            $sena = 0;
            $sena += $datos['sena_ars'] / $usd_pc;
            $sena += $datos['sena_pcp'] / $usd_pc;
            $sena_usd_ars = $datos['sena_usd'];
            $sena_pcu_ars = $datos['sena_pcu'];
            $sena += $sena_usd_ars;
            $sena += $sena_pcu_ars;

            $restante_usd = $efectivo_usd - $sena;

            $suma_pagos = $insLogin->seleccionarDatos("Normal", "pago_sena WHERE id_sena = '".$datos['id_sena']."'", "SUM(sena_pago_importe) as suma_pagos", 0);
            if ($suma_pagos->rowCount() >= 1) {
                $suma_pagos = $suma_pagos->fetch();
                $suma_pagos_value = $suma_pagos['suma_pagos'] !== NULL ? $suma_pagos['suma_pagos'] : 0;
                $suma_pagos_value_usd = $suma_pagos_value / $usd_pc;
            } else {
                $suma_pagos_value = 0;
            }
    ?>

<input type="hidden" id="usd_pc" value="<?php echo $usd_pc?>">

<div class="container">
    
    <div class="box">
        <h2 class="title">Datos de la sena <?php echo $datos['id_sena']; ?></h2>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <div class="full-width sale-details text-condensedLight">
                        <div class="has-text-weight-bold">Fecha</div>
                        <span class="has-text-link"><?php echo date("d-m-Y", strtotime($datos['sena_fecha']))." ".$datos['sena_hora']; ?></span>
                    </div>
                </div>
                <div class="control">
                    <div class="full-width sale-details text-condensedLight">
                        <div class="has-text-weight-bold">Cliente</div>
                        <span class="has-text-link"><?php echo $datos['cliente_nombre_completo']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="column">
                <div class="control">
                    <div class="full-width sale-details text-condensedLight">
                        <div class="has-text-weight-bold">Equipo</div>
                        <span class="has-text-link"><?php echo $datos['equipo_marca'] ." ". $datos['equipo_modelo'] ." - ". $datos['equipo_almacenamiento'] ." - ". $datos['equipo_color'] ; ?></span>
                    </div>
                </div>
                <div class="control">
                    <div class="full-width sale-details text-condensedLight">
                        <div class="has-text-weight-bold">Vendedor</div>
                        <span class="has-text-link"><?php echo $datos['sena_vendedor']; ?></span>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <div class="box">
        <h2 class="title">Datos del equipo</h2>
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
                                <td>$<?php echo number_format($efectivo_ars)?></td>
                            </tr>
                            <tr>
                                <td>Precio en Efectivo USD</td>
                                <td id="efectivo_usd"><?php echo number_format($efectivo_usd, 2)?></td>
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
                                <td id="restante_efectivo_usd"><?php echo number_format($restante_usd - $suma_pagos_value_usd,2)?></td>
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
                    <td><input type="number" name="sena_ars" id="sena_ars" value="<?php echo $datos['sena_ars']; ?>" step="0.01"></td>
                </tr>

                <!-- Campo para la seña en dólares (USD) -->
                <tr>
                    <td><label for="sena_usd">Seña (USD)</label></td>
                    <td><input type="number" name="sena_usd" id="sena_usd" value="<?php echo $datos['sena_usd']; ?>" step="0.01"></td>
                </tr>

                <!-- Campo para la seña en pesos del plan canje -->
                <tr>
                    <td><label for="sena_pcp">Seña Plan Canje (Pesos)</label></td>
                    <td><input type="number" name="sena_pcp" id="sena_pcp" value="<?php echo $datos['sena_pcp']; ?>" step="0.01"></td>
                </tr>

                <!-- Campo para la seña en dólares del plan canje -->
                <tr>
                    <td><label for="sena_pcu">Seña Plan Canje (USD)</label></td>
                    <td><input type="number" name="sena_pcu" id="sena_pcu" value="<?php echo $datos['sena_pcu']; ?>" step="0.01"></td>
                </tr>
                </tbody>
            </table>
            <button type="button" class="button is-link is-light" onclick="print_orden('<?php echo APP_URL."app/pdf/comprobanteSena.php?code=".$datos['id_sena']; ?>')" >
                <i class="fas fa-file-invoice-dollar fa-2x"></i> &nbsp;
                Comprobante de sena
            </button>
            <div class="columns pb-6 is-max-desktop">
                <p class="has-text-centered full-width">
                    <?php
                    echo '<button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-pay" >
                                        Registrar pago
                                    </button> &nbsp;&nbsp;'
                    ?>
                </p>
            </div>
    </div>
    
    <?php
        }
    ?>
</div>
<?php
    include "./app/views/includes/print_invoice_script.php";
?>

<!-- Modal registrar pago -->
<div class="modal" id="modal-js-pay">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; Pagos de la sena </p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/pagoAjax.php" method="POST" autocomplete="off" name="formsale">
                <input type="hidden" name="modulo_pago" value="registrar_pago_sena_equipo">
                <input type="hidden" name="id_sena" id="id_sena" value="<?php echo $datos['id_sena']; ?>">
                <input type="hidden" name="id_equipo" value="<?php echo $datos['id_equipo']; ?>">
                <div class="columns">
                    <div class="column">
                        <label for="" class="label">Fecha: </label>
                        <input name="sena_pago_fecha" class="input" type="date" value="<?php echo date("Y-m-d"); ?>">
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <label for="" class="label">Forma de pago: </label>
                        <div class="select">
							<select name="sena_pago_forma">
								<option value="" selected="" >Seleccione una opción</option>
								<?php
									echo $insLogin->generarSelect(FORMAS_PAGO,"VACIO");
								?>
							</select>
						</div>
                    </div>
                    <div class="column">
                        <label for="" class="label">Importe: </label>
                        <input class="input" type="number" name="sena_pago_importe" id="sena_pago_importe">
                    </div>
                    <div class="column">
                        <label for="" class="label">Detalle: </label>
                        <input class="input" type="text" name="sena_pago_detalle">
                    </div>
                </div>
                <div class="columns">
                    <div class="column">Total de la venta: <?php echo "USD".MONEDA_SIMBOLO.number_format($efectivo_usd, MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR); ?></div>
                    <div class="column">
                        <?php
                        
                        echo "Suma de pagos: USD".MONEDA_SIMBOLO.number_format($suma_pagos_value_usd, MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR);
                        ?>
                    </div>
                    <div class="column">
                        <?php
                        $saldo = $restante_usd - $suma_pagos_value_usd;
                        echo "Saldo: USD".MONEDA_SIMBOLO.number_format($saldo, MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR);
                        ?>
                        <input type="hidden" name="saldo" value="<?php echo $saldo * $usd_pc; ?>">
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
        const inputImporte = document.getElementById('sena_pago_importe');
        inputImporte.value = saldo;

        // Simular clic en el botón "Registrar pago" (submit)
        document.getElementById('btnEnviar').click();
    });



    const usd_pc = document.getElementById("usd_pc").value;
    // Obtener el precio en efectivo USD desde el PHP
    let efectivo_usd = parseFloat(document.querySelector('#restante_efectivo_usd').textContent) || 0;

    let precio = efectivo_usd * 1.4 * usd_pc;
    let efectivo = efectivo_usd * usd_pc;
    let sin_int_3 = precio / 3;
    let sin_int_6 = precio / 6; 
    let fijas_9 = (efectivo_usd * 1.5 * usd_pc) / 9;
    let fijas_12 = (efectivo_usd * 1.6 * usd_pc) / 12;

    document.getElementById("restante_lista").textContent = '$' + number_format(precio.toFixed(2));
    document.getElementById("restante_3").textContent = '$' + number_format(sin_int_3.toFixed(2));
    document.getElementById("restante_6").textContent = '$' + number_format(sin_int_6.toFixed(2));
    document.getElementById("restante_9").textContent = '$' + number_format(fijas_9.toFixed(2));
    document.getElementById("restante_12").textContent = '$' + number_format(fijas_12.toFixed(2));
    document.getElementById("restante_1pago").textContent = '$' + number_format(efectivo.toFixed(2));
    document.getElementById("restante_efectivo_ars").textContent = '$' + number_format(efectivo.toFixed(2));


    // Función para formatear números con separadores de miles
    function number_format(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>

