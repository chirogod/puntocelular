<?php include "app/views/includes/admin_security.php"; ?>
<div class="container is-fluid mb-2">
    <h1 class="title">Articulo</h1>
    <h2 class="subtitle"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar articulo</h2>
</div>
<div class="container is-max-desktop mt-6 ">
    <?php
    
        use app\controllers\articuloController;
        $insArticulo = new articuloController();

        if(!isset($_SESSION[$url[0]]) && empty($_SESSION[$url[0]])){
    ?>
    <div class="columns">
        <div class="column">
            <form class="FormularioAjaxSinAlerta" action="<?php echo APP_URL; ?>app/ajax/buscadorAjax.php" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="buscar">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?" required>
                    </p>
                    <p class="control">
                        <button class="button is-info" type="submit" >Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <?php }else{ ?>
    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-6 mb-6 FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/buscadorAjax.php" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="eliminar">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
                <p><i class="fas fa-search fa-fw"></i> &nbsp; Estas buscando <strong>“<?php echo $_SESSION[$url[0]]; ?>”</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded"><i class="fas fa-trash-restore"></i> &nbsp; Eliminar busqueda</button>
            </form>
        </div>
    </div>
    <?php
            echo $insArticulo->listarArticuloControlador($url[1],15,$url[0],$_SESSION[$url[0]]);
        }
    ?>
</div>

<div class="container is-max-desktop mt-3">


	<?php

		echo $insArticulo->listarArticuloControlador($url[1],15,$url[0],"");
	?>
</div>


<script>

    document.querySelector('#input_codigo').addEventListener('input', function(){
        let input_codigo=document.querySelector('#input_codigo').value;

        input_codigo=input_codigo.trim();

        if(input_codigo!=""){

            let datos = new FormData();
            datos.append("buscar_codigo", input_codigo);
            datos.append("modulo_venta", "buscar_codigo");

            fetch('<?php echo APP_URL; ?>app/ajax/ventaAjax.php',{
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.text())
            .then(respuesta =>{
                let resultado_busqueda=document.querySelector('#resultado-busqueda');
                resultado_busqueda.innerHTML=respuesta;
            });

        }else{
            let resultado_busqueda=document.querySelector('#resultado-busqueda');
            resultado_busqueda.innerHTML='';
        }
    });
    
</script>