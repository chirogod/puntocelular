<?php
    namespace app\controllers;
    use app\models\mainModel;

    class sucursalController extends mainModel{

        public function registrarSucursalControlador(){
            $sucursal_descripcion = $this->limpiarCadena($_POST['sucursal_descripcion']);
            $sucursal_direccion = $this->limpiarCadena($_POST['sucursal_direccion']);
            $sucursal_localidad = $this->limpiarCadena($_POST['sucursal_localidad']);
            $sucursal_telefono = $this->limpiarCadena($_POST['sucursal_telefono']);
            $sucursal_email = $this->limpiarCadena($_POST['sucursal_email']);
            $sucursal_pie_nota = $this->limpiarCadena($_POST['sucursal_pie_nota']);
            $sucursal_pie_comprobante = $this->limpiarCadena($_POST['sucursal_pie_comprobante']);
            $sucursal_firma_email = $this->limpiarCadena($_POST['sucursal_firma_email']);

            if($sucursal_descripcion == "" || $sucursal_direccion == "" || $sucursal_localidad == "" || $sucursal_telefono == "" || $sucursal_email == "" || $sucursal_pie_nota == "" || $sucursal_pie_comprobante == "" || $sucursal_firma_email == ""){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No has llenado todos los campos que son obligatorios",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,100}",$sucursal_descripcion)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El nombre de la sucursal no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            $check_nombre = $this->ejecutarConsulta("SELECT * FROM sucursal WHERE sucursal_descripcion = '$sucursal_descripcion'");
            if ($check_nombre->rowCount()>0) {
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Ya hay una sucursal registrada con ese nombre",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($this->verificarDatos("[0-9()+]{8,20}",$sucursal_telefono)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El telefono no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}",$sucursal_direccion)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La sucursal de la direccion no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}",$sucursal_pie_nota)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El pie de nota no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}",$sucursal_pie_comprobante)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El pie de comprobante no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}",$sucursal_firma_email)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La firma email no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }


            $datos_sucursal = [
                [
                    "campo_nombre"=>"sucursal_descripcion",
                    "campo_marcador"=>":Sucursal",
                    "campo_valor"=>$sucursal_descripcion
                ],
                [
                    "campo_nombre"=>"sucursal_telefono",
                    "campo_marcador"=>":Telefono",
                    "campo_valor"=>$sucursal_telefono
                ],
                [
                    "campo_nombre"=>"sucursal_direccion",
                    "campo_marcador"=>":Direccion",
                    "campo_valor"=>$sucursal_direccion
                ],
                [
                    "campo_nombre"=>"sucursal_localidad",
                    "campo_marcador"=>":Localidad",
                    "campo_valor"=>$sucursal_localidad
                ],
                [
                    "campo_nombre"=>"sucursal_email",
                    "campo_marcador"=>":Email",
                    "campo_valor"=>$sucursal_email
                ],
                [
                    "campo_nombre"=>"sucursal_pie_nota",
                    "campo_marcador"=>":PieNota",
                    "campo_valor"=>$sucursal_pie_nota
                ],
                [
                    "campo_nombre"=>"sucursal_pie_comprobante",
                    "campo_marcador"=>":PieComprobante",
                    "campo_valor"=>$sucursal_pie_comprobante
                ],
                [
                    "campo_nombre"=>"sucursal_firma_email",
                    "campo_marcador"=>":FirmaEmail",
                    "campo_valor"=>$sucursal_firma_email
                ],
                
            ];

            $registrar_sucursal = $this->guardarDatos("sucursal", $datos_sucursal);

                if ($registrar_sucursal->rowCount()==1) {
                    $alerta=[
                        "tipo"=>"limpiar",
                        "titulo"=>"Sucursal registrado con exito",
                        "texto"=>"La sucursal '".$sucursal_descripcion."' se registro con exito",
                        "icono"=>"success"
                    ];
                }else{
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"No se pudo registrar la sucursal, por favor intente nuevamente",
                        "icono"=>"error"
                    ];
                }
            //retornamos el json 
            return json_encode($alerta);
        }

        public function listarSucursalControlador($pagina,$registros,$url,$busqueda){
            $pagina=$this->limpiarCadena($pagina);
            $registros=$this->limpiarCadena($registros);
    
            $url=$this->limpiarCadena($url);
            $url=APP_URL.$url."/";
    
            $busqueda=$this->limpiarCadena($busqueda);
            $tabla="";
    
            $pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
            $inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;
    
            if(isset($busqueda) && $busqueda!=""){

				$consulta_datos="SELECT * FROM sucursal WHERE ((sucursal_descripcion LIKE '%$busqueda%')) ORDER BY sucursal_descripcion ASC LIMIT $inicio,$registros";

				$consulta_total="SELECT COUNT(id_sucursal) FROM sucursal WHERE ((sucursal_descripcion LIKE '%$busqueda%') )";

			}else{

				$consulta_datos="SELECT * FROM sucursal  ORDER BY sucursal_descripcion ASC LIMIT $inicio,$registros";

				$consulta_total="SELECT COUNT(id_sucursal) FROM sucursal ";

			}
    
            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();
    
            $total = $this->ejecutarConsulta($consulta_total);
            $total = (int) $total->fetchColumn();
    
            $numeroPaginas =ceil($total/$registros);
    
            $tabla.='
    
                
                <div class="table-container">
                <tr class="has-text-centered" >
                            <td colspan="7">
                                <a href="'.$url.'1/" class="button is-link is-rounded is-small mt-4 mb-4">
                                    Haga clic acá para recargar el listado
                                </a>
                            </td>
                        </tr>
                
    
                <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th class="has-text-centered">#</th>
                            <th class="has-text-centered">Nombre</th>
                            <th class="has-text-centered">Sucursal</th>
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
                            <td>'.$rows['sucursal_descripcion'].'</td>
                            <td>'.$rows['sucursal_direccion'].'</td>
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
                            </td>
                        </tr>
                    ';
                }
            }
    
            $tabla.='</tbody></table></div>';
    
            ### Paginacion ###
            if($total>0 && $pagina<=$numeroPaginas){
                $tabla.='<p class="has-text-right">Mostrando sucursales <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
    
                $tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
            }
    
            return $tabla;
        }

        public function actualizarTallerControlador(){
            $objetivo = $_POST['objetivo'];
            $dias_laborales = $_POST['dias_laborales'];
            $dias_trabajados = $_POST['dias_trabajados'];

            if($objetivo == "" || $dias_laborales == "" || $dias_trabajados == ""){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No has llenado todos los campos que son obligatorios",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            if($objetivo == 0 || $dias_laborales == 0 || $dias_trabajados == 0){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Los campos no pueden valer 0",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            $datos_sucursal = [
                [
                    "campo_nombre"=>"sucursal_objetivo_taller",
                    "campo_marcador"=>":Objetivo",
                    "campo_valor"=>$objetivo
                ],
                [
                    "campo_nombre"=>"sucursal_laborales",
                    "campo_marcador"=>":DiasLaborales",
                    "campo_valor"=>$dias_laborales
                ],
                [
                    "campo_nombre"=>"sucursal_trabajados",
                    "campo_marcador"=>":DiasTrabajados",
                    "campo_valor"=>$dias_trabajados
                ]
            ];
            $condicion=[
                "condicion_campo"=>"id_sucursal",
                "condicion_marcador"=>":ID",
                "condicion_valor"=>$_SESSION['id_sucursal']
            ];
            if($this->actualizarDatos("sucursal",$datos_sucursal,$condicion)){
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Datos actualizados",
                    "texto"=>"Los datos se actualizaron correctamente",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No hemos podido actualizar los datos, por favor intente nuevamente",
                    "icono"=>"error"
                ];
            }
    
            return json_encode($alerta);

        }
    }




?>