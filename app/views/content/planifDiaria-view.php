<?php
$mes_actual = date("n");
$dias_mes = date("t");

$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$mes_descripcion = $meses[$mes_actual];

// Valores iniciales de configuración (se pueden guardar en la BD en el futuro)
$objetivo = isset($_POST['objetivo']) ? $_POST['objetivo'] : 400;
$dias_laborales = isset($_POST['dias_laborales']) ? $_POST['dias_laborales'] : 24;
$dias_trabajados = isset($_POST['dias_trabajados']) ? $_POST['dias_trabajados'] : 21;

// Consulta cantidad de órdenes en el mes
$ordenes_mes = $insLogin->Consultar("SELECT COUNT(*) FROM orden WHERE MONTH(orden_fecha) = $mes_actual AND id_sucursal = {$_SESSION['id_sucursal']}");
$ordenes_mes = $ordenes_mes->fetchColumn();

// Función para contar órdenes por día
function aguti($dia) {
    global $insLogin, $mes_actual;
    $ordenes_dia = $insLogin->Consultar("SELECT COUNT(*) FROM orden WHERE DAY(orden_fecha) = $dia AND MONTH(orden_fecha) = $mes_actual AND id_sucursal = {$_SESSION['id_sucursal']}");
    return $ordenes_dia->fetchColumn();
}

?>

<div class="container is-fluid mt-1 mb-4">
    <h1 class="title">Planificación diaria - <?php echo $mes_descripcion ?></h1>
</div>

<!-- Formulario para modificar la configuración -->
<div class="container is-max-desktop">
    <form method="POST">
        <div class="columns">
            <div class="column">
                <label class="label">Objetivo</label>
                    <input class="input" type="number" name="objetivo" value="<?php echo $objetivo; ?>" required>
            </div>
            <div class="column">
                <label class="label">Días laborales</label>
                <input class="input" type="number" name="dias_laborales" value="<?php echo $dias_laborales; ?>" required>
            </div>
            <div class="column">
                <label class="label">Días trabajados</label>
                <input class="input" type="number" name="dias_trabajados" value="<?php echo $dias_trabajados; ?>" required>
            </div>
            <div class="column">
                <button class="button is-primary" type="submit">Actualizar</button>
            </div>
        </div>
    </form>
</div>

<!-- Tabla de planificación -->
<div class="container mt-5 is-max-desktop">
    <table class="table is-striped is-bordered is-fullwidth">
        <thead>
            <tr>
                <th>FECHA</th>
                <th>INGRESO DEL DÍA</th>
                <th>ACUMULADO</th>
                <th>%</th>
                <th>PROYECCIÓN</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $acumulado = 0;
            for ($i = 1; $i <= $dias_mes; $i++) {
                $ordenes_dia = aguti($i);
                $acumulado += $ordenes_dia;
                $porcentaje = ($objetivo > 0) ? ($acumulado / $objetivo) * 100 : 0;
                $proyeccion = ($dias_trabajados > 0) ? ($acumulado / $dias_trabajados * $dias_laborales) : 0;

                echo "<tr>
                        <td>$i</td>
                        <td>$ordenes_dia</td>
                        <td>$acumulado</td>
                        <td>" . number_format($porcentaje, 2) . "%</td>
                        <td>" . number_format($proyeccion, 2) . "</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
