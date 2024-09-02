<?php


namespace app\controllers;
use app\models\mainModel;

class articuloController extends mainModel{
    public function registrarArticuloControlador(){
        $articulo_codigo = $this->limpiarCadena($_POST['articulo_codigo']);
        $articulo_descripcion = $this->limpiarCadena($_POST['articulo_descripcion']);
        $articulo_stock = $this->limpiarCadena($_POST['articulo_stock']);
        $articulo_stock_min = $this->limpiarCadena($_POST['articulo_stock_min']);
        $articulo_stock_max = $this->limpiarCadena($_POST['articulo_stock_max']);
        $id_rubro = $this->limpiarCadena($_POST['id_rubro']);
        $id_sucursal = $this->limpiarCadena($_POST['id_sucursal']);
        $articulo_garantia = $this->limpiarCadena($_POST['articulo_garantia']);
        $articulo_observacion = $this->limpiarCadena($_POST['articulo_observacion']);
        $articulo_activo = $this->limpiarCadena($_POST['articulo_activo']);
        $articulo_moneda = $this->limpiarCadena($_POST['articulo_moneda']);
        $articulo_precio_compra = $this->limpiarCadena($_POST['articulo_precio_compra']);
        $articulo_precio_venta = $this->limpiarCadena($_POST['articulo_precio_venta']);
        $articulo_marca = $this->limpiarCadena($_POST['articulo_marca']);
        $articulo_modelo = $this->limpiarCadena($_POST['articulo_modelo']);

        //verificar campos obligatorios
        if($articulo_descripcion == "" || $articulo_stock == "" || $id_rubro == "" || $id_sucursal == "" || $articulo_moneda == "" || $articulo_precio_compra == "" || $articulo_precio_venta == "" || $articulo_codigo == "" || $articulo_activo == ""){
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
        if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{3,200}", $articulo_descripcion)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El nombre del articulo no cumple con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        //verificar codigo
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
            if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,100}", $articulo_garantia)){
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

        if ($articulo_observacion != "") {
            if($this->verificarDatos("^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]*$", $articulo_observacion)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La observacion del articulo no cumple con el formato solicitado",
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

        if($this->verificarDatos("[0-9.]{1,25}", $articulo_precio_venta)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El precio de venta del articulo no cumple con el formato solicitado",
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
                "campo_nombre"=>"articulo_precio_venta",
                "campo_marcador"=>":PrecioVenta",
                "campo_valor"=>$articulo_precio_venta
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

    public function listarArticuloControlador($pagina,$registros,$url,$busqueda){
        $pagina=$this->limpiarCadena($pagina);
        $registros=$this->limpiarCadena($registros);

        $url=$this->limpiarCadena($url);
        $url=APP_URL.$url."/";

        $busqueda=$this->limpiarCadena($busqueda);
        $tabla="";

        $pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
        $inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

        if(isset($busqueda) && $busqueda!=""){

            $consulta_datos = "SELECT * FROM articulo WHERE articulo_descripcion LIKE '%$busqueda%' OR articulo_codigo LIKE '%$busqueda%'";

            $consulta_total="SELECT COUNT(id_articulo) FROM articulo WHERE articulo_descripcion LIKE '%$busqueda%' OR articulo_codigo LIKE '%$busqueda%'";

        }else{

            $consulta_datos="SELECT * FROM articulo  ORDER BY articulo_descripcion ASC LIMIT $inicio,$registros";

            $consulta_total="SELECT COUNT(id_articulo) FROM articulo ";

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
                        <th class="has-text-centered">Detalle</th>
                    </tr>
                </thead>
                <tbody>
        ';

        if($total>=1 && $pagina<=$numeroPaginas){
            $contador=$inicio+1;
            $pag_inicio=$inicio+1;
            foreach($datos as $rows){
                $tabla.='
                    <tr class="has-text-centered" >
                        <td>'.$contador.'</td>
                        <td>'.$rows['articulo_descripcion'].'</td>
                        <td>'.$rows['articulo_codigo'].'</td>
                        <td>'.$rows['articulo_stock'].'</td>
                        <td>'.$rows['articulo_precio_venta'].'</td>
                        
                        <td>
                            <a href="'.APP_URL.'artUpdate/'.$rows['id_articulo'].'/" class="button is-success is-rounded is-small">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                        </td>
                        
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
                                <a href="'.APP_URL.'artNew/" class="button is-success is-rounded is-small">Registrar cliente</a>
                            </div>
                        </td>
                    </tr>
                ';
            }
        }

        $tabla.='</tbody></table></div>';

        ### Paginacion ###
        if($total>0 && $pagina<=$numeroPaginas){
            $tabla.='<p class="has-text-right">Mostrando clientes <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

            $tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
        }

        return $tabla;
    }
}