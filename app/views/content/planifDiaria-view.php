<?php
$mes_actual = date("n");
$dias_mes = date("t");

$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$meses_abreviados = [
    1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
    5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
    9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
];
$mes_descripcion = $meses[$mes_actual];
$mes_descripcion_abreviado = $meses_abreviados[$mes_actual];

//select de la base de datos segun la sucursal, los objetivos etc.
$datos_sucursal = $insLogin->seleccionarDatos("Unico", "sucursal", "id_sucursal", $_SESSION['id_sucursal']);
$datos_sucursal = $datos_sucursal->fetch();
// Valores iniciales de configuración (se pueden guardar en la BD en el futuro)
$objetivo = $datos_sucursal['sucursal_objetivo_taller'];
$dias_laborales = $datos_sucursal['sucursal_laborales'];
$dias_trabajados = $datos_sucursal['sucursal_trabajados'];

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
<div class=" container box is-max-desktop">
    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/sucursalAjax.php" method="POST" autocomplete="off" >
        <input type="hidden" name="modulo_sucursal" value="actualizar_taller">
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
            <div class="has-text-centered">
                <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Actualizar</button>
            </div>
            
        </div>
    </form>
</div>

<!-- Tabla de planificación -->
<div class="container mt-5 mb-5 is-max-desktop has-text-centered">
    <table class="table is-striped is-bordered is-fullwidth">
        <thead>
            <tr>
                <th>FECHA</th>
                <th>INGRESO DEL DÍA</th>
                <th>ACUMULADO</th>
                <th class="has-text-centered">%</th>
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
                        <td>$i - $mes_descripcion_abreviado</td>
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
