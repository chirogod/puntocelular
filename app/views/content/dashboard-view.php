<div class="container is-fluid">
  <h1 class="title">Home</h1>
  <div class="columns is-flex is-justify-content-center">
    <h2 class="subtitle">¡Bienvenido <?php echo $_SESSION['usuario_nombre']; ?>!</h2>
  </div>
</div>

<?php
  $sucursal = $_SESSION['id_sucursal'];

  $total_usuarios = $insLogin->seleccionarDatos("Normal", "usuario", "id_usuario", 0);
  $total_clientes = $insLogin->seleccionarDatos("Normal", "cliente", "id_cliente", 0);
  $total_productos = $insLogin->seleccionarDatosSucursal("Normal", "articulo", "id_articulo", 0, $_SESSION['id_sucursal']);
  $total_ventas = $insLogin->seleccionarDatosSucursal("Normal", "venta", "id_venta", 0, $_SESSION['id_sucursal']);
  $total_ordenes = $insLogin->seleccionarDatosSucursal("Normal", "orden", "id_orden", 0, $_SESSION['id_sucursal']);
  $total_ventas_mes = $insLogin->seleccionarDatosSucursal("Normal", "venta", "id_venta", 0, $_SESSION['id_sucursal'], "MONTH(venta_fecha) = MONTH(CURRENT_DATE) AND YEAR(venta_fecha) = YEAR(CURRENT_DATE)");
  $total_montos_ventas_mes = $insLogin->seleccionarDatosSucursal("Normal", "venta", "SUM(venta_importe) AS total_ventas_mes", 0, $_SESSION['id_sucursal'], "MONTH(venta_fecha) = MONTH(CURRENT_DATE) AND YEAR(venta_fecha) = YEAR(CURRENT_DATE)");
  $total_caja_fisica = $insLogin->seleccionarCajaFisicaSucursal("Normal", "caja", "SUM(caja_monto) AS total_caja_fisica", 0, $_SESSION['id_sucursal'], "caja_codigo LIKE '%Efectivo%'");

  
  // Obtener las ventas del día
  $total_ventas_dia = $insLogin->Consultar("SELECT COUNT(*) AS total_ventas FROM venta WHERE id_sucursal = '$_SESSION[id_sucursal]' AND DATE(venta_fecha) = CURDATE();");
  $total_ventas_dia = $total_ventas_dia->fetchColumn();

  // Obtener el total de montos de caja del día
  $total_montos_caja_dia = $insLogin->Consultar(" SELECT SUM(total_pago) AS total_caja_hoy FROM (
          SELECT venta_pago_importe AS total_pago
          FROM pago_venta
          WHERE DATE(venta_pago_fecha) = CURDATE() AND id_sucursal = '$_SESSION[id_sucursal]'
          
          UNION ALL
          
          SELECT orden_pago_importe AS total_pago
          FROM pago_orden
          WHERE DATE(orden_pago_fecha) = CURDATE() AND id_sucursal = '$_SESSION[id_sucursal]'
      ) AS total_pagos
  ");
  $total_montos_caja_dia = $total_montos_caja_dia->fetchColumn();

  // Obtener el total de órdenes del día
  $total_ordenes_dia = $insLogin->Consultar("SELECT COUNT(*) AS total_ordenes FROM orden WHERE id_sucursal = '$_SESSION[id_sucursal]' AND DATE(orden_fecha) = CURDATE();");
  $total_ordenes_dia = $total_ordenes_dia->fetchColumn();
?>

<div class="container pb-6 pt-6 is-max-desktop">
  <div class="columns">
    <div class="column is-one-quarter">
      <div class="card">
        <div class="card-content has-text-centered">
          <p class="heading">Caja Física</p>
          <p class="title">$<?php echo $total_caja_fisica->fetchColumn(); ?></p>
        </div>
      </div>
    </div>
    <!-- Tarjeta Ventas del Día -->
    <div class="column is-one-quarter">
      <div class="card">
        <div class=" card-content has-text-centered">
          <p class="heading">Ventas del Día</p>
          <p class="title"><?php echo $total_ventas_dia ?></p>
        </div>
      </div>
    </div>

    <!-- Tarjeta Realizar Venta -->
    <div class="column is-one-quarter">
        <a href="<?php echo APP_URL; ?>saleNew/" class="has-text-dark">
            <div class="card">
                <div class="card-content has-text-centered">
                    <p class="heading">Realizar Venta</p>
                    <p class="title">
                        <i class="fas fa-shopping-cart fa-1x"></i>
                    </p>
                </div>
            </div>
        </a>
    </div>

    <!-- Nueva Tarjeta -->
    <div class="column is-one-quarter">
        <a href="<?php echo APP_URL; ?>equipoList/" class="has-text-dark">
            <div class="card">
                <div class="card-content has-text-centered">
                    <p class="heading">Lista equipos</p>
                    <p class="title">
                        <i class="fas fa-list fa-1x"></i>
                    </p>
                </div>
            </div>
        </a>
    </div>
  </div>

  <div class="columns">
      <!-- Tarjeta Total del Día -->
      <div class="column is-one-quarter">
          <div class="card">
              <div class="card-content has-text-centered">
                  <p class="heading">Total del Día</p>
                  <p class="title">$<?php echo $total_montos_caja_dia; ?></p>
              </div>
          </div>
      </div>

      <!-- Tarjeta Órdenes del Día -->
      <div class="column is-one-quarter">
          <div class="card">
              <div class="card-content has-text-centered">
                  <p class="heading">Órdenes del Día</p>
                  <p class="title"><?php echo $total_ordenes_dia; ?></p>
              </div>
          </div>
      </div>

      <!-- Tarjeta Realizar Orden -->
      <div class="column is-one-quarter">
        <a href="<?php echo APP_URL; ?>ordenNew/" class="has-text-dark">
          <div class="card">
            <div class="card-content has-text-centered">
              <p class="heading">Realizar Orden</p>
              <p class="title">
                  <i class="fas fa-screwdriver-wrench fa-1x"></i>
              </p>
            </div>
          </div>
        </a>
      </div>

      <!-- Tarjeta calculadora de cotos -->
      <div class="column is-one-quarter">
        <a href="<?php echo APP_URL; ?>calcularCostos/" class="has-text-dark">
          <div class="card">
            <div class="card-content has-text-centered">
              <p class="heading">Calcular costos</p>
              <p class="title">
                  <i class="fas fa-calculator fa-1x"></i>
              </p>
            </div>
          </div>
        </a>
      </div>
  </div>
</div>
