<div class="container is-fluid">
	<h1 class="title">Home</h1>
  	<div class="columns is-flex is-justify-content-center">
  		<h2 class="subtitle">¡Bienvenido <?php echo $_SESSION['usuario_nombre']; ?>!</h2>
  	</div>
</div>
<?php
	$sucursal = $_SESSION['id_sucursal'];
	
	$total_usuarios = $insLogin->seleccionarDatos("Normal","usuario","id_usuario",0);

	$total_clientes=$insLogin->seleccionarDatos("Normal","cliente","id_cliente",0);

	$total_productos=$insLogin->seleccionarDatos("Normal","articulo","id_articulo",0);

	$total_ventas=$insLogin->seleccionarDatosSucursal("Normal","venta","id_venta",0, $_SESSION['id_sucursal']);

	$total_ordenes = $insLogin->seleccionarDatosSucursal("Normal", "orden", "id_orden",0, $_SESSION['id_sucursal']);

	$total_ventas_mes = $insLogin->seleccionarDatosSucursal("Normal", "venta", "id_venta", 0,$_SESSION['id_sucursal'], "MONTH(venta_fecha) = MONTH(CURRENT_DATE) AND YEAR(venta_fecha) = YEAR(CURRENT_DATE)");

	$total_montos_ventas_mes = $insLogin->seleccionarDatosSucursal("Normal", "venta", "SUM(venta_importe) AS total_ventas_mes", 0, $_SESSION['id_sucursal'], "MONTH(venta_fecha) = MONTH(CURRENT_DATE) AND YEAR(venta_fecha) = YEAR(CURRENT_DATE)");

?>
<div class="container pb-6 pt-6 is-max-desktop">

	<div class="columns pb-6">
		<div class="column">
			<nav class="level is-mobile">
				<?php if($_SESSION['usuario_rol']=="Administrador"){ ?>
			  	<div class="level-item has-text-centered">
				    <a href="<?php echo APP_URL; ?>cashierList/">
				      	TOTAL DE LAS VENTAS
				    </a>
			  	</div>
			  	<div class="level-item has-text-centered">
			    	<a href="<?php echo APP_URL; ?>userList/">
			      		<p class="heading"><i class="fas fa-users fa-fw"></i> &nbsp; Usuarios</p>
			      		<p class="title"><?php echo $total_usuarios->rowCount(); ?></p>
			    	</a>
			  	</div>
			  	<?php } ?>

			  	<div class="level-item has-text-centered">
				    <a href="<?php echo APP_URL; ?>clientList/">
				      	<p class="heading"><i class="fas fa-address-book fa-fw"></i> &nbsp; Clientes</p>
				      	<p class="title"><?php echo $total_clientes->rowCount(); ?></p>
				    </a>
			  	</div>
			</nav>
		</div>
	</div>

	<div class="columns pt-6">
		<div class="column">
			<nav class="level is-mobile">
				<?php if($_SESSION['usuario_rol']=="Administrador"){ ?>
					<div class="level-item has-text-centered">
			    	<a href="<?php echo APP_URL; ?>saleList/">
			      		<p class="heading"><i class="fas fa-shopping-cart fa-fw"></i> &nbsp; Ordenes totales</p>
			      		<p class="title"><?php echo $total_ordenes->rowCount(); ?></p>
			    	</a>
			  	</div>
			  	<div class="level-item has-text-centered">
				    <a href="<?php echo APP_URL; ?>productList/">
				      	<p class="heading"><i class="fas fa-cubes fa-fw"></i> &nbsp; Productos</p>
				      	<p class="title"><?php echo $total_productos->rowCount(); ?></p>
				    </a>
			  	</div>
			  	<?php } ?>
			  	<div class="level-item has-text-centered">
			    	<a href="<?php echo APP_URL; ?>saleList/">
			      		<p class="heading"><i class="fas fa-shopping-cart fa-fw"></i> &nbsp; Ventas totales</p>
			      		<p class="title"><?php echo $total_ventas->rowCount(); ?></p>
			    	</a>
			  	</div>
			</nav>
		</div>
	</div>

	<div class="columns pt-6">
		<div class="column">
			<nav class="level is-mobile">
				<?php if($_SESSION['usuario_rol']=="Administrador"){ ?>
					<div class="level-item has-text-centered">
			    	<a href="<?php echo APP_URL; ?>saleList/">
			      		<p class="heading"><i class="fas fa-shopping-cart fa-fw"></i> &nbsp; Ventas del mes</p>
			      		<p class="title"><?php echo $total_ventas_mes->rowCount(); ?></p>
			    	</a>
			  	</div>
			  	<div class="level-item has-text-centered">
					<a href="">
						<p class="heading"><i class="fas fa-cubes fa-fw"></i> &nbsp; Total del mes</p>
						<p class="title"><?php echo $total_montos_ventas_mes->fetchColumn(); ?></p>
					</a>
					
				</div>
			  	<?php } ?>
			</nav>
		</div>
	</div>

</div>