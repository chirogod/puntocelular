<?php include "./app/views/includes/admin_security.php"; ?>
<div class="container is-fluid mb-6">
	<h1 class="title">Articulos</h1>
</div>
<div class="container is-max-desktop">

	<div class="form-rest mb-6 mt-6"></div>

	<?php
		use app\controllers\articuloController;

		$insArticulo = new articuloController();

		echo $insArticulo->listarArticuloControlador($url[1],15,$url[0],"");
	?>
</div>