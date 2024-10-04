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
?>

<div class="container pb-6 pt-6 is-max-desktop">
  <div class="columns is-multiline">
    <?php if ($_SESSION['usuario_rol'] == "Administrador") { ?>
    <!-- Tarjeta Caja Física -->
    <div class="column is-one-quarter">
      <div class="card">
        <div class="card-content has-text-centered">
          <p class="heading">Caja Física</p>
          <p class="title">$<?php echo $total_caja_fisica->fetchColumn(); ?></p>
        </div>
      </div>
    </div>
    
    <!-- Tarjeta Usuarios -->
    <div class="column is-one-quarter">
      <div class="card">
        <div class="card-content has-text-centered">
          <p class="heading">Usuarios</p>
          <p class="title"><?php echo $total_usuarios->rowCount(); ?></p>
        </div>
      </div>
    </div>
    <?php } ?>

    <!-- Tarjeta Clientes -->
    <div class="column is-one-quarter">
      <div class="card">
        <div class="card-content has-text-centered">
          <p class="heading">Clientes</p>
          <p class="title"><?php echo $total_clientes->rowCount(); ?></p>
        </div>
      </div>
    </div>

    <?php if ($_SESSION['usuario_rol'] == "Administrador") { ?>
    <!-- Tarjeta Productos -->
    <div class="column is-one-quarter">
      <div class="card">
        <div class="card-content has-text-centered">
          <p class="heading">Productos</p>
          <p class="title"><?php echo $total_productos->rowCount(); ?></p>
        </div>
      </div>
    </div>
    <?php } ?>

    <!-- Tarjeta Ventas Totales -->
    <div class="column is-one-quarter">
      <div class="card">
        <div class="card-content has-text-centered">
          <p class="heading">Ventas Totales</p>
          <p class="title"><?php echo $total_ventas->rowCount(); ?></p>
        </div>
      </div>
    </div>

    <?php if ($_SESSION['usuario_rol'] == "Administrador") { ?>
    <!-- Tarjeta Ventas del Mes -->
    <div class="column is-one-quarter">
      <div class="card">
        <div class="card-content has-text-centered">
          <p class="heading">Ventas del Mes</p>
          <p class="title"><?php echo $total_ventas_mes->rowCount(); ?></p>
        </div>
      </div>
    </div>

    <!-- Tarjeta Total del Mes -->
    <div class="column is-one-quarter">
      <div class="card">
        <div class="card-content has-text-centered">
          <p class="heading">Total del Mes</p>
          <p class="title">$<?php echo $total_montos_ventas_mes->fetchColumn(); ?></p>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
