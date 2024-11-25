<?php include "app/views/includes/admin_security.php"; ?>
<div class="container is-fluid mb-2">
    <h1 class="title">Articulo</h1>
    <h2 class="subtitle"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar articulo</h2>
</div>
<div class="container is-max-desktop mt-6 ">
    <div class="columns">
        <div class="column">
        <div class="field  mt-6 mb-6">
        <label class="label">Nombre, descripcion, codigo.</label>
        <div class="control">
            <input class="input" type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" name="input_codigo" id="input_codigo" maxlength="30" >
        </div>
    </div>
    <div class="container" id="resultado-busqueda"></div>
        </div>
    </div>
</div>


<script>
    document.querySelector('#input_codigo').addEventListener('input', function(){
        let input_codigo=document.querySelector('#input_codigo').value;

        input_codigo=input_codigo.trim();

        if(input_codigo!=""){

            let datos = new FormData();
            datos.append("buscar_articulo", input_codigo);
            datos.append("modulo_articulo", "buscar_articulo");

            fetch('<?php echo APP_URL; ?>app/ajax/articuloAjax.php',{
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