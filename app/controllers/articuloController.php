<?php


namespace app\controllers;
use app\models\mainModel;

class articuloController extends mainModel{
    public function registrarArticuloControlador(){
        $articulo_descripcion = $this->limpiarCadena($_POST['articulo_descripcion']);
        $articulo_stock = $this->limpiarCadena($_POST['articulo_stock']);
        $articulo_stock_min = $this->limpiarCadena($_POST['articulo_stock_min']);
        $articulo_stock_max = $this->limpiarCadena($_POST['articulo_stock_max']);
        $id_rubro = $this->limpiarCadena($_POST['id_rubro']);
        $id_sucursal = $_SESSION['id_sucursal'];
        $articulo_garantia = $this->limpiarCadena($_POST['articulo_garantia']);
        $articulo_observacion = $this->limpiarCadena($_POST['articulo_observacion']);
        $articulo_activo = "SI";
        $articulo_moneda = $this->limpiarCadena($_POST['articulo_moneda']);
        $articulo_precio_compra = $this->limpiarCadena($_POST['articulo_precio_compra']);
        
        $articulo_porcentaje_ganancia = $this->limpiarCadena($_POST['articulo_porcentaje_ganancia']);
        $articulo_marca = $this->limpiarCadena($_POST['articulo_marca']);
        $articulo_modelo = $this->limpiarCadena($_POST['articulo_modelo']);

        //verificar campos obligatorios
        if($articulo_descripcion == "" || $articulo_stock == "" || $id_rubro == "" || $id_sucursal == "" || $articulo_moneda == "" || $articulo_precio_compra == "" ){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No has llenado todos los campos que son obligatorios",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        //verificar integridad datos
        if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\\s\/\(\)%\/\-\.]{3,100}", $articulo_descripcion)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El nombre del articulo no cumple con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }
        
        $articulo_codigo = $this->limpiarCadena($_POST['articulo_codigo']);
        //verificar codigo si se puso manualmente
        if ($articulo_codigo != "") {
            $check_codigo =$this->ejecutarConsulta("SELECT * FROM articulo WHERE articulo_codigo = '$articulo_codigo'");
            if ($check_codigo->rowCount() > 0) {
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El codigo del articulo ya existe",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }else{
            /*== sino generar aleatoriamente el codigo del articulo ==*/
            $correlativo=$this->ejecutarConsulta("SELECT id_articulo FROM articulo");
            $correlativo=($correlativo->rowCount())+1;
            $articulo_codigo=$this->generarCodigoAleatorio(7,$correlativo);

            // verificar que el codigo no exista ya
            $check_codigo =$this->ejecutarConsulta("SELECT * FROM articulo WHERE articulo_codigo = '$articulo_codigo'");
            if ($check_codigo->rowCount() > 0) {
                // si ya existe generar otro
                $articulo_codigo=$this->generarCodigoAleatorio(7,$correlativo);
            }
        }
        

        
        if($this->verificarDatos("^[0-9]+$", $articulo_stock)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El stock del articulo no cumple con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        if ($articulo_stock_min != "") {
            if($this->verificarDatos("^[0-9]+$", $articulo_stock_min)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El stock minimo del articulo no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }
        
        if ($articulo_stock_max != "") {
            if($this->verificarDatos("^[0-9]+$", $articulo_stock_max)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El stock maximo del articulo no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }

        if ($articulo_garantia != "") {
            if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\\s\/\(\)%\/\-\.]{0,100}", $articulo_garantia)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La garantia del articulo no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }


        if($this->verificarDatos("[0-9.]{1,25}", $articulo_precio_compra)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El precio de compra del articulo no cumple con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }



        if ($articulo_marca != "") {
            if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{1,30}", $articulo_marca)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La marca del articulo no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }

        if ($articulo_modelo != "") {
            if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{1,30}", $articulo_modelo)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El modelo del articulo no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }

        /* operaciones con el precio de compra */
        if($articulo_porcentaje_ganancia == ""){
            $precio_venta = $this->limpiarCadena($_POST['articulo_precio_venta']);
        }else{
            $precio_venta = $articulo_precio_compra * (1 + ($articulo_porcentaje_ganancia/100));
        }
        

        $datos_articulo = [
            [
                "campo_nombre"=>"articulo_codigo",
                "campo_marcador"=>":Codigo",
                "campo_valor"=>$articulo_codigo
            ],
            [
                "campo_nombre"=>"articulo_descripcion",
                "campo_marcador"=>":Descripcion",
                "campo_valor"=>$articulo_descripcion
            ],
            [
                "campo_nombre"=>"articulo_stock",
                "campo_marcador"=>":Stock",
                "campo_valor"=>$articulo_stock
            ],
            [
                "campo_nombre"=>"articulo_stock_min",
                "campo_marcador"=>":ArticuloStockMin",
                "campo_valor"=>$articulo_stock_min
            ],
            [
                "campo_nombre"=>"articulo_stock_max",
                "campo_marcador"=>":ArticuloStockMax",
                "campo_valor"=>$articulo_stock_max
            ],
            [
                "campo_nombre"=>"id_rubro",
                "campo_marcador"=>":Rubro",
                "campo_valor"=>$id_rubro
            ],
            [
                "campo_nombre"=>"id_sucursal",
                "campo_marcador"=>":Sucursal",
                "campo_valor"=>$id_sucursal
            ],
            [
                "campo_nombre"=>"articulo_garantia",
                "campo_marcador"=>":Garantia",
                "campo_valor"=>$articulo_garantia
            ],
            [
                "campo_nombre"=>"articulo_observacion",
                "campo_marcador"=>":Observacion",
                "campo_valor"=>$articulo_observacion
            ],
            [
                "campo_nombre"=>"articulo_moneda",
                "campo_marcador"=>":Moneda",
                "campo_valor"=>$articulo_moneda
            ],
            [
                "campo_nombre"=>"articulo_precio_compra",
                "campo_marcador"=>":PrecioCompra",
                "campo_valor"=>$articulo_precio_compra
            ],
            [
                "campo_nombre"=>"articulo_porcentaje_ganancia",
                "campo_marcador"=>":PorcentajeGanancia",
                "campo_valor"=>$articulo_porcentaje_ganancia
            ],
            [
                "campo_nombre"=>"articulo_precio_venta",
                "campo_marcador"=>":PrecioVenta",
                "campo_valor"=>$precio_venta
            ],
            [
                "campo_nombre"=>"articulo_marca",
                "campo_marcador"=>":Marca",
                "campo_valor"=>$articulo_marca
            ],
            [
                "campo_nombre"=>"articulo_modelo",
                "campo_marcador"=>":Modelo",
                "campo_valor"=>$articulo_modelo
            ],
            [
                "campo_nombre"=>"articulo_activo",
                "campo_marcador"=>":Activo",
                "campo_valor"=>$articulo_activo
            ]
        ];

        $registrar_articulo = $this->guardarDatos("articulo", $datos_articulo);
        if ($registrar_articulo->rowCount()==1) {
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"Articulo registrado con exito",
                "texto"=>"El articulo " .$articulo_descripcion. " se registro con exito",
                "icono"=>"success"
            ];
        }else{
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No se pudo registrar el articulo, por favor intente nuevamente",
                "icono"=>"error"
            ];
        }
        //retornamos el json 
        return json_encode($alerta);
    }

    public function actualizarArticuloControlador(){

        $id=$this->limpiarCadena($_POST['id_articulo']);
        $articulo_codigo = $this->limpiarCadena($_POST['articulo_codigo']);

        # Verificando producto #
        $datos=$this->ejecutarConsulta("SELECT * FROM articulo WHERE articulo_codigo='$articulo_codigo'");
        if($datos->rowCount()<=0){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No hemos encontrado el articulo en el sistema",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }else{
            $datos=$datos->fetch();
        }

        # Almacenando datos#
        $codigo=$this->limpiarCadena($_POST['articulo_codigo']);
        $nombre=$this->limpiarCadena($_POST['articulo_descripcion']);

        $precio_compra=$this->limpiarCadena($_POST['articulo_precio_compra']);
        $articulo_porcentaje_ganancia = $this->limpiarCadena($_POST['articulo_porcentaje_ganancia']);
        /* operaciones con el precio de compra */
        $precio_venta = $precio_compra * (1 + ($articulo_porcentaje_ganancia/100));

        $stock=$this->limpiarCadena($_POST['articulo_stock']);
        $stock_minimo=$this->limpiarCadena($_POST['articulo_stock_min']);
        $stock_maximo=$this->limpiarCadena($_POST['articulo_stock_max']);

        $activo = $this->limpiarCadena($_POST['articulo_activo']);
        $rubro = $this->limpiarCadena($_POST['id_rubro']);
        $id_sucursal = $this->limpiarCadena($_POST['id_sucursal']);
        
        $garantia = $this->limpiarCadena($_POST['articulo_garantia']);
        $observacion = $this->limpiarCadena($_POST['articulo_observacion']);

        $moneda = $this->limpiarCadena($_POST['articulo_moneda']);

        $marca=$this->limpiarCadena($_POST['articulo_marca']);
        $modelo=$this->limpiarCadena($_POST['articulo_modelo']);

        # Verificando campos obligatorios #
        if($codigo=="" || $nombre=="" || $precio_compra=="" || $stock=="" || $id_sucursal == ""){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No has llenado todos los campos que son obligatorios",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Verificando integridad de los datos #
        if($this->verificarDatos("[a-zA-Z0-9- ]{1,77}",$codigo)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El CODIGO no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}",$nombre)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El NOMBRE no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        if($this->verificarDatos("[0-9.]{1,25}",$precio_compra)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El PRECIO DE COMPRA no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        if($this->verificarDatos("[0-9]{1,22}",$stock)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El STOCK O EXISTENCIAS no coincide con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        if($marca!=""){
            if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,30}",$marca)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La MARCA no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }

        if($modelo!=""){
            if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,30}",$modelo)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El MODELO no coincide con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }

        # Verificando categoria #
        if($datos['id_rubro']!=$rubro){
            $check_rubro=$this->ejecutarConsulta("SELECT id_rubro FROM rubro WHERE id_rubro='$rubro'");
            if($check_rubro->rowCount()<=0){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El rubro seleccionado no existe en el sistema",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }


        # Comprobando precio de compra del producto #
        $precio_compra=number_format($precio_compra,MONEDA_DECIMALES,'.','');
        if($precio_compra<0){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El PRECIO DE COMPRA no puede ser menor a 0",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }


        # Comprobando codigo de producto #
        if($datos['articulo_codigo']!=$codigo){
            $check_codigo=$this->ejecutarConsulta("SELECT articulo_codigo FROM articulo WHERE articulo_codigo='$codigo'");
            if($check_codigo->rowCount()>=1){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El código del arituc que ha ingresado ya se encuentra registrado en el sistema",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }

        # Comprobando nombre de producto #
        if($datos['articulo_descripcion']!=$nombre){
            $check_nombre=$this->ejecutarConsulta("SELECT articulo_descripcion FROM articulo WHERE articulo_codigo='$codigo' AND articulo_descripcion='$nombre'");
            if($check_nombre->rowCount()>=1){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Ya existe un articulo registrado con el mismo nombre y código de barras",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
        }


        $producto_datos_up=[
            [
                "campo_nombre"=>"articulo_codigo",
                "campo_marcador"=>":Codigo",
                "campo_valor"=>$codigo
            ],
            [
                "campo_nombre"=>"articulo_descripcion",
                "campo_marcador"=>":Nombre",
                "campo_valor"=>$nombre
            ],
            [
                "campo_nombre"=>"articulo_stock",
                "campo_marcador"=>":Stock",
                "campo_valor"=>$stock
            ],
            [
                "campo_nombre"=>"articulo_stock_min",
                "campo_marcador"=>":StockMin",
                "campo_valor"=>$stock_minimo
            ],
            [
                "campo_nombre"=>"articulo_stock_max",
                "campo_marcador"=>":Stock_max",
                "campo_valor"=>$stock_maximo
            ],
            [
                "campo_nombre"=>"articulo_precio_compra",
                "campo_marcador"=>":PrecioCompra",
                "campo_valor"=>$precio_compra
            ],
            [
                "campo_nombre"=>"articulo_porcentaje_ganancia",
                "campo_marcador"=>":PorcentajeGanancia",
                "campo_valor"=>$articulo_porcentaje_ganancia
            ],
            [
                "campo_nombre"=>"articulo_precio_venta",
                "campo_marcador"=>":PrecioVenta",
                "campo_valor"=>$precio_venta
            ],
            [
                "campo_nombre"=>"articulo_marca",
                "campo_marcador"=>":Marca",
                "campo_valor"=>$marca
            ],
            [
                "campo_nombre"=>"articulo_modelo",
                "campo_marcador"=>":Modelo",
                "campo_valor"=>$modelo
            ],
            [
                "campo_nombre"=>"id_rubro",
                "campo_marcador"=>":Rubro",
                "campo_valor"=>$rubro
            ],
            [
                "campo_nombre"=>"articulo_moneda",
                "campo_marcador"=>":Moneda",
                "campo_valor"=>$moneda
            ],
            [
                "campo_nombre"=>"id_sucursal",
                "campo_marcador"=>":Sucursal",
                "campo_valor"=>$id_sucursal
            ],
            [
                "campo_nombre"=>"articulo_garantia",
                "campo_marcador"=>":Garantia",
                "campo_valor"=>$garantia
            ],
            [
                "campo_nombre"=>"articulo_observacion",
                "campo_marcador"=>":Observacion",
                "campo_valor"=>$observacion
            ],
            [
                "campo_nombre"=>"articulo_activo",
                "campo_marcador"=>":Activo",
                "campo_valor"=>$activo
            ],
        ];

        $condicion=[
            "condicion_campo"=>"id_articulo",
            "condicion_marcador"=>":ID",
            "condicion_valor"=>$id
        ];

        if($this->actualizarDatos("articulo",$producto_datos_up,$condicion)){
            $alerta=[
                "tipo"=>"recargar",
                "titulo"=>"Articulo actualizado",
                "texto"=>"Los datos del articulo '".$datos['articulo_descripcion']."' se actualizaron correctamente",
                "icono"=>"success"
            ];
        }else{
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No hemos podido actualizar los datos del articulo '".$datos['articulo_descripcion']."', por favor intente nuevamente",
                "icono"=>"error"
            ];
        }

        return json_encode($alerta);
    }

    public function listarArticuloControlador($pagina,$registros,$url,$busqueda){
        $pagina=$this->limpiarCadena($pagina);
        $registros=$this->limpiarCadena($registros);

        $url=$this->limpiarCadena($url);
        $url=APP_URL.$url."/";

        $busqueda=$this->limpiarCadena($busqueda);
        $tabla="";

        $pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
        $inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

        $sucursal = $_SESSION['id_sucursal'];

        if(isset($busqueda) && $busqueda!=""){

            $consulta_datos="SELECT * 
                            FROM articulo 
                            WHERE 
                                id_sucursal = '$sucursal' 
                                AND articulo_descripcion LIKE '%$busqueda%'
                                OR articulo_codigo LIKE '%$busqueda%'";

            $consulta_total="SELECT COUNT(id_articulo) FROM articulo WHERE id_sucursal = '$sucursal' AND articulo_descripcion LIKE '%$busqueda%' OR articulo_codigo LIKE '%$busqueda%'";

        }else{

            $consulta_datos="SELECT * FROM articulo WHERE id_sucursal = '$sucursal'  ORDER BY id_articulo DESC LIMIT $inicio,$registros";

            $consulta_total="SELECT COUNT(id_articulo) FROM articulo WHERE id_sucursal = '$sucursal'";

        }

        $datos = $this->ejecutarConsulta($consulta_datos);
        $datos = $datos->fetchAll();

        $total = $this->ejecutarConsulta($consulta_total);
        $total = (int) $total->fetchColumn();

        $numeroPaginas =ceil($total/$registros);
        
        $tabla.='
            <div class="table-container">
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th class="has-text-centered">#</th>
                        <th class="has-text-centered">Articulo</th>
                        <th class="has-text-centered">Codigo</th>
                        <th class="has-text-centered">Stock</th>
                        <th class="has-text-centered">Precio venta</th>
                    </tr>
                </thead>
                <tbody>
        ';

        if($total>=1 && $pagina<=$numeroPaginas){
            $contador=$inicio+1;
            $pag_inicio=$inicio+1;
            foreach($datos as $rows){
                $tabla.='
                    <tr class="has-text-centered" style="cursor: pointer;" onclick="window.location.href=\'' . APP_URL . 'artUpdate/' . $rows['id_articulo'] . '/\'" >
                        <td>'.$contador.'</td>
                        <td>'.$rows['articulo_descripcion'].'</td>
                        <td>'.$rows['articulo_codigo'].'</td>
                        <td>'.$rows['articulo_stock'].'</td>
                        <td>'.$rows['articulo_precio_venta'].'</td>                        
                    </tr>
                ';
                $contador++;
            }
            $pag_final=$contador-1;
        }else{
            if($total>=1){
                $tabla.='
                    <tr class="has-text-centered" >
                        <td colspan="7">
                            <a href="'.$url.'1/" class="button is-link is-rounded is-small mt-4 mb-4">
                                Haga clic acá para recargar el listado
                            </a>
                        </td>
                    </tr>
                ';
            }else{
                $tabla.='
                    <tr class="has-text-centered" >
                        <td colspan="7">
                            No hay registros en el sistema
                            <div class="mt-1">
                                <a href="'.APP_URL.'artNew/" class="button is-success is-rounded is-small">Registrar articulos</a>
                            </div>
                        </td>
                    </tr>
                ';
            }
        }

        $tabla.='</tbody></table></div>';

        ### Paginacion ###
        if($total>0 && $pagina<=$numeroPaginas){
            $tabla.='<p class="has-text-right">Mostrando articulo <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

            $tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
        }

        return $tabla;
    }

    /*---------- Controlador buscar cliente ----------*/
    public function buscarArticuloControlador(){

        /*== Recuperando termino de busqueda ==*/
        $articulo=$this->limpiarCadena($_POST['buscar_articulo']);

        /*== Comprobando que no este vacio el campo ==*/
        if($articulo==""){
            return '
            <article class="message is-warning mt-4 mb-4">
                 <div class="message-header">
                    <p>¡Ocurrio un error inesperado!</p>
                 </div>
                <div class="message-body has-text-centered">
                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                    Debes de introducir el nombre, descripcion, codigo del articulo.
                </div>
            </article>';
            exit();
        }

        /*== Seleccionando clientes en la DB ==*/
        $datos_cliente=$this->ejecutarConsulta("SELECT * FROM articulo WHERE (articulo_descripcion LIKE '%$articulo%' OR articulo_codigo LIKE '%$articulo%') ORDER BY id_articulo DESC");

        if($datos_cliente->rowCount()>=1){

            $datos_cliente=$datos_cliente->fetchAll();

            $tabla='<div class="table-container mb-6"><table class="table is-striped is-narrow is-hoverable is-fullwidth"><tbody>';

            foreach($datos_cliente as $rows){
                $tabla.='
                <tr>
                    <td class="has-text-left" ><i class="fas fa-male fa-fw"></i> &nbsp; '.$rows['articulo_descripcion'].' (Stock: '.$rows['articulo_stock'].')</td>

                </tr>
                ';
            }

            $tabla.='</tbody></table></div>';
            return $tabla;
        }else{
            return '
            <article class="message is-warning mt-4 mb-4">
                 <div class="message-header">
                    <p>¡Ocurrio un error inesperado!</p>
                 </div>
                <div class="message-body has-text-centered">
                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                    No hemos encontrado ningún articulo en el sistema que coincida con <strong>“'.$articulo.'”</strong>
                </div>
            </article>';
            exit();
        }
    }

    
}


