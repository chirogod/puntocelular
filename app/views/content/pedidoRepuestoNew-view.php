<style>
    summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        background-color: #f5f5f5;
        font-weight: bold;
        font-size: 1.1rem;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.2s;
    }
    summary:hover {
        background-color: #e3e3e3;
    }
    summary::before {
        content: "➤";
        margin-right: 10px;
        transition: transform 0.3s ease;
    }
    details[open] summary::before {
        transform: rotate(90deg);
    }
</style>
<?php
    use app\controllers\repuestoController;

    $insRepuesto = new repuestoController();

?>

<div class="container is-fluid mb-4">
	<h1 class="title">Pedido de repuestos</h1>
</div>

<div class="container pb-6 is-max-desktop">
    <div class="columns">
        <div class="column">
            <div class="box">
                <h2 class="subtitle">Nuevo pedido</h2>    
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/repuestoAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

                    <input type="hidden" name="modulo_repuesto" value="registrar_pedido">

                    <div class="columns">
                        <div class="column">

                            <label>Seccion <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                            <div class="select">
                                <select name="id_seccion" >
                                    <option value="" selected="" >Seleccione una opción</option>
                                    <?php
                                        $datos_seccion=$insLogin->seleccionarDatos("Normal","seccion_repuesto","*",0);

                                        $cc=1;
                                        while($campos_seccion=$datos_seccion->fetch()){
                                            echo '<option value="'.$campos_seccion['id_seccion_repuesto'].'">'.$cc.' - '.$campos_seccion['seccion_repuesto_descripcion'].'</option>';
                                            $cc++;
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="column">
                            <label>Repuesto<?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input class="input" type="text" name="repuesto_descripcion" maxlength="40" required >
                        </div>
                    </div>  
                    <div class="columns">
                        <div class="column">
                            <label>Color<?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input class="input" type="text" name="repuesto_color" maxlength="40" >
                        </div>
                        <div class="column">
                            <label>Orden<?php echo CAMPO_OBLIGATORIO; ?></label>
                            <input class="input" type="text" name="id_orden" maxlength="40" required >
                        </div>
                    </div>      
                            
                    
                    

                    <p class="has-text-centered">
                        <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
                        <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
                    </p>
                    <p class="has-text-centered pt-1">
                        <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container is-max-desktop pb-4">
    <?php
        echo $insRepuesto->listarPedidosControlador();
    ?>
</div>