<style>
    a{
        color: #000;
        text-decoration:underline;
    }
    a:hover {
        color: orange; /* Cambia el color según tus necesidades */
    }
</style>

<?php 
    include "./app/views/includes/admin_security.php";
?>

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
                            <th class="has-text-right" id="costo_operativo_hora"><?php echo COSTO_OPERATIVO_HORA ?></th>
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
                </tr>
                <tr>
                    <td>20% DE DESCUENTO EN EFECTIVO</td>
                    <td class="has-text-right" id="descuento_efectivo"></td>
                </tr>
                <tr>
                    <td>6 CUOTAS</td>
                    <td class="has-text-right" id="cuotas_6"></td>
                </tr>
                <tr>
                    <td>12 CUOTAS</td>
                    <td class="has-text-right" id="cuotas_12"></td>
                </tr>
                <tr>
                    <td>UPSELLING EFECTIVO</td>
                    <td class="has-text-right" id="upsel_efectivo"></td>
                </tr>
                <tr>
                    <td>UPSELLING 3 CUOTAS</td>
                    <td class="has-text-right" id="upsel_3_cuotas"></td>
                </tr>
            </tbody>
        </table>
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
                multiplicadorRiesgo = 2.0;
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
            var cuotas_sin_interes = ((<?php echo COSTO_OPERATIVO_HORA ?> * horasTrabajo) + costoRepuesto) * multiplicadorRiesgo * 1.3;
            var descuento_efectivo = cuotas_sin_interes * 0.8;
            var cuotas_6 = descuento_efectivo * 1.5;
            var cuotas_12 = descuento_efectivo * 1.9;
            var mano_de_obra = descuento_efectivo - costoRepuesto;
            var upsel_efectivo = ((mano_de_obra - costoRepuesto) / 2) + costoRepuesto;
            var upsel_3_cuotas = upsel_efectivo * 1.35;

            document.getElementById('cuotas_3_sin_int').innerHTML = "$" + cuotas_sin_interes.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('descuento_efectivo').innerHTML = "$" + descuento_efectivo.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('cuotas_6').innerHTML = "$" + cuotas_6.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('cuotas_12').innerHTML = "$" + cuotas_12.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('upsel_efectivo').innerHTML = "$" + upsel_efectivo.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('upsel_3_cuotas').innerHTML = "$" + upsel_3_cuotas.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    document.getElementById('horas_trabajo').addEventListener('input', calcularCuotasSinInteres);
    document.getElementById('costo_repuesto').addEventListener('input', calcularCuotasSinInteres);
    document.getElementById('riesgo').addEventListener('input', calcularCuotasSinInteres);
</script>