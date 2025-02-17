<style>
    a{
        color: #000;
        text-decoration:underline;
    }
    a:hover {
        color: orange; /* Cambia el color según tus necesidades */
    }
    #modal-js-info.is-active {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #modal-js-info .modal-card {
        width: 100vw;  /* Ocupa todo el ancho de la pantalla */
        height: 100vh; /* Ocupa todo el alto de la pantalla */
        max-width: none; /* Elimina la restricción de ancho */
        max-height: none; /* Elimina la restricción de alto */
        display: flex;
        flex-direction: column;
        padding: 0;
        margin: 0;
        overflow: hidden;
    }

    #modal-js-info .modal-card-body {
        flex-grow: 1; /* Hace que el contenido crezca para llenar el espacio */
    }

    
    #modal-js-info .modal-card-body iframe {
        width: 100%;
        height: 100%;
    }

    #modal-js-info .modal-card-head {
        padding: 10px; /* Reduce padding */
        font-size: 0.9rem; /* Reduce font size */
    }
</style>

<div class="container is-fluid is-max-desktop pt-2">
    <h1 class="title is-4"><i class="fas fa-calculator fa-fw"></i> Calculadora de costos</h1>
</div>
<div class="container pb-4 pt-4 is-max-desktop">
    <div class="columns">
        <div class="column">
            <div class="box">
                <h2 class="subtitle is-5">Calculadora</h2>
                <table class="table is-bordered is-fullwidth is-narrow">
                    <thead>
                        <tr class="has-background-warning has-text-white">
                            <th>COSTO OPERATIVO POR HORA</th>
                            <th class="has-text-right" id="costo_operativo_hora"><?php echo $_SESSION['costo_operativo_hora'] ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>HORAS DE TRABAJO</td>
                            <td class="has-text-right"><input id="horas_trabajo" type="number" class="input is-small"></td>
                        </tr>
                        <tr>
                            <td>COSTO DE REPUESTO</td>
                            <td class="has-text-right"><input id="costo_repuesto" type="number" class="input is-small"></td>
                        </tr>
                        <tr>
                            <td>RIESGO</td>
                            <td class="has-text-right"><input id="riesgo" type="number" class="input is-small"></td>
                        </tr>
                    </tbody>
                    
                </table>
            </div>
        </div>
        <div class="column">
            <div class="box">
                <h2 class="subtitle is-5">Listas</h2>
                <table class="table is-bordered is-fullwidth is-narrow has-text-centered">
                    </thead>
                    <tbody>
                        <tr>
                            <td><a class="is-clickable"  target="_blank" href="https://docs.google.com/spreadsheets/d/1OnEQ-rxLO9OG5HOeZrrq6NjfWxt67PVKBojKVqPen2A/edit#gid=1597786750">TANETE</a></td>
                        </tr>
                        <tr>
                            <td><a target="_blank" href="https://docs.google.com/spreadsheets/d/1kkeR2En-TAFazcPBlKXA6zXmAjfuUVCqvzSobazKZnM/edit#gid=805420923">JV</a></td>
                        </tr>
                        <tr>
                            <td><a target="_blank" href="https://docs.google.com/spreadsheets/d/1u2r_noapAWubYyhA0e8UsJZec05kkY94ZHMs5_wL8CM/edit?pli=1#gid=0">MD</a></td>
                        </tr>
                        <tr>
                            <td><a target="_blank" href="https://docs.google.com/spreadsheets/d/18zJJq5KVlIl-K9KfpTQtK3HmofQwzO9O962ShspKSWg/edit#gid=0">MD ALTERNATIVOS</a></td>
                        </tr>
                        <tr>
                        <td><a target="_blank" href="https://docs.google.com/spreadsheets/d/1UqA3Ly6AJR7hTTl1pmRul0hz7fS_IOd7/edit?gid=1266385039#gid=1266385039">MASTERCELL</a></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="button is-link is-light js-modal-trigger has-text-centered" data-target="modal-js-info" >
                    Criterios
                </button>
            </div>
        </div>
    </div>

    <div class="box">
        <h2 class="subtitle is-5">Precios</h2>
        <table class="table is-bordered is-fullwidth is-narrow">
            <tbody>
                <tr>
                    <td>3 CUOTAS SIN INTERÉS</td>
                    <td class="has-text-right" id="cuotas_3_sin_int"></td>
                    <td class="has-text-right" id="precio_cuota_3"></td>
                </tr>
                <tr>
                    <td>20% DE DESCUENTO EN EFECTIVO</td>
                    <td class="has-text-right" id="descuento_efectivo"></td>
                </tr>
                <tr>
                    <td>6 CUOTAS</td>
                    <td class="has-text-right" id="cuotas_6"></td>
                    <td class="has-text-right" id="precio_cuota_6"></td>
                </tr>
                <tr>
                    <td>12 CUOTAS</td>
                    <td class="has-text-right" id="cuotas_12"></td>
                    <td class="has-text-right" id="precio_cuota_12"></td>
                </tr>
                <tr>
                    <td>UPSELLING EFECTIVO</td>
                    <td class="has-text-right" id="upsel_efectivo"></td>
                </tr>
                <tr>
                    <td>UPSELLING 3 CUOTAS</td>
                    <td class="has-text-right" id="upsel_3_cuotas"></td>
                    <td class="has-text-right" id="precio_cuota_upsel_3"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal criterios -->
<div class="modal" id="modal-js-info">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title is-uppercase">&nbsp; criterios </p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <iframe src="https://docs.google.com/spreadsheets/d/1ELs5ZnZjj98Y5B-v1ddYEx9-r6cw7-bgCRWxFfiI9BA/preview" frameborder="0"></iframe>
        </section>
    </div>
</div>


<script>
    function calcularCuotasSinInteres() {
        var horasTrabajo = parseFloat(document.getElementById('horas_trabajo').value);
        var costoRepuesto = parseFloat(document.getElementById('costo_repuesto').value);
        var riesgo = parseInt(document.getElementById('riesgo').value);
        var multiplicadorRiesgo = 0;

        switch (riesgo) {
            case 1:
                multiplicadorRiesgo = 1.45;
                break;
            case 2:
                multiplicadorRiesgo = 1.75;
                break;
            case 3:
                multiplicadorRiesgo = 2.2;
                break;
            case 4:
                multiplicadorRiesgo = 2.6;
                break;
            case 5:
                multiplicadorRiesgo = 3.0;
                break;
            default:
                multiplicadorRiesgo = 1.0; // Valor por defecto si no coincide con ningún caso
                break;
        }

        if (!isNaN(horasTrabajo) && !isNaN(costoRepuesto) && !isNaN(riesgo)) {
            var cuotas_sin_interes = ((<?php echo $_SESSION['costo_operativo_hora'] ?> * horasTrabajo) + costoRepuesto) * multiplicadorRiesgo * 1.3;
            var descuento_efectivo = cuotas_sin_interes * 0.8;
            var cuotas_6 = descuento_efectivo * 1.5;
            var cuotas_12 = descuento_efectivo * 1.9;
            var mano_de_obra = descuento_efectivo - costoRepuesto;
            var upsel_efectivo = ((mano_de_obra - costoRepuesto) / 2) + costoRepuesto;
            var upsel_3_cuotas = upsel_efectivo * 1.35;
            var precio_cuota_3 = cuotas_sin_interes/3;
            var precio_cuota_6 = cuotas_6/6;
            var precio_cuota_12 = cuotas_12/12;
            var precio_cuota_upsel_3 = upsel_3_cuotas/3;

            document.getElementById('cuotas_3_sin_int').innerHTML = "$" + cuotas_sin_interes.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('precio_cuota_3').innerHTML = "$" + precio_cuota_3.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('descuento_efectivo').innerHTML = "$" + descuento_efectivo.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('cuotas_6').innerHTML = "$" + cuotas_6.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('precio_cuota_6').innerHTML = "$" + precio_cuota_6.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('cuotas_12').innerHTML = "$" + cuotas_12.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('precio_cuota_12').innerHTML = "$" + precio_cuota_12.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('upsel_efectivo').innerHTML = "$" + upsel_efectivo.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('upsel_3_cuotas').innerHTML = "$" + upsel_3_cuotas.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('precio_cuota_upsel_3').innerHTML = "$" + precio_cuota_upsel_3.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    document.getElementById('horas_trabajo').addEventListener('input', calcularCuotasSinInteres);
    document.getElementById('costo_repuesto').addEventListener('input', calcularCuotasSinInteres);
    document.getElementById('riesgo').addEventListener('input', calcularCuotasSinInteres);
</script>