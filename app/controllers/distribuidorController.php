<?php

namespace app\controllers;
use app\models\mainModel;

class distribuidorController extends mainModel{
    public function registrarDistribuidorControlador(){
        $distribuidor = $_POST['distribuidor_descripcion'];
        $link = $_POST['distribuidor_link'];
        if($distribuidor == "" || $link == ""){
            $alert = ([
                "tipo"=> "simple",
                "title" => "Ocurrió un error inesperado",
                "text" => "No se han llenado todos los campos obligatorios",
                "type" => "error"
            ]);
            return json_encode($alert);
            exit();
        }

        $check_nombre = $this->ejecutarConsulta("SELECT * FROM distribuidor WHERE distribuidor_descripcion = '$distribuidor'");
        if ($check_nombre->rowCount()>0) {
            $alerta=[
				"tipo"=>"simple",
				"titulo"=>"Error",
				"texto"=>"El distribuidor '$distribuidor' ya esta registrado en el sistema",
				"icono"=>"error"
			];
            return json_encode($alerta);
            exit();
        }

        $datos=[
            [
                "campo_nombre" => "distribuidor_descripcion",
                "campo_marcador" => ":Distribuidor",
                "campo_valor" => $distribuidor            
            ],
            [
                "campo_nombre" => "distribuidor_link",
                "campo_marcador" => ":Link",
                "campo_valor" => $link            
            ],
            [
                "campo_nombre" => "distribuidor_mostrar",
                "campo_marcador" => ":Mostrar",
                "campo_valor" => "SI"         
            ]
        ];

        $guardar = $this->guardarDatos(("distribuidor"),$datos);
        if($guardar->rowCount()>=1){
            $alerta=[
				"tipo"=>"recargar",
				"titulo"=>"Distribuidor registrado",
				"texto"=>"Se registro el distribuidor '$distribuidor' en el sistema",
				"icono"=>"success"
			];
            return json_encode($alerta);
            exit();
        }else{
            $alerta=[
				"tipo"=>"simple",
				"titulo"=>"Algo salio mal",
				"texto"=>"No se pudo registrar el distribuidor en el sistema",
				"icono"=>"error"
			];
            return json_encode($alerta);
            exit();
        }
        
    }

    public function editarDistribuidorControlador(){
        $distribuidor = $_POST['distribuidor_descripcion'];
        $link = $_POST['distribuidor_link'];
        $id = $_POST['id_distribuidor'];
        $mostrar = $_POST['distribuidor_mostrar'];
        if($distribuidor == "" || $link == ""){
            $alert = ([
                "tipo"=> "simple",
                "title" => "Ocurrió un error inesperado",
                "text" => "No se han llenado todos los campos obligatorios",
                "type" => "error"
            ]);
            return json_encode($alert);
            exit();
        }

        $datos=[
            [
                "campo_nombre" => "distribuidor_descripcion",
                "campo_marcador" => ":Distribuidor",
                "campo_valor" => $distribuidor            
            ],
            [
                "campo_nombre" => "distribuidor_link",
                "campo_marcador" => ":Link",
                "campo_valor" => $link            
            ],
            [
                "campo_nombre" => "distribuidor_mostrar",
                "campo_marcador" => ":Mostrar",
                "campo_valor" => $mostrar         
            ]
        ];

        $condicion=[
            "condicion_campo"=>"id_distribuidor",
            "condicion_marcador"=>":ID",
            "condicion_valor"=>$id
        ];

        $actualizar = $this->actualizarDatos("distribuidor",$datos,$condicion);
        if($actualizar->rowCount()>=1){
            $alerta=[
				"tipo"=>"recargar",
				"titulo"=>"Distribuidor actualizado",
				"texto"=>"Se actualizo el distribuidor '$distribuidor'",
				"icono"=>"success"
			];
            return json_encode($alerta);
            exit();
        }else{
            $alerta=[
				"tipo"=>"simple",
				"titulo"=>"Algo salio mal",
				"texto"=>"No se pudo actualizar el distribuidor",
				"icono"=>"error"
			];
            return json_encode($alerta);
            exit();
        }
        
    }


    public function listarDistribuidorControlador($pagina,$registros,$url,$busqueda){
        $pagina=$this->limpiarCadena($pagina);
        $registros=$this->limpiarCadena($registros);

        $url=$this->limpiarCadena($url);
        $url=APP_URL.$url."/";

        $busqueda=$this->limpiarCadena($busqueda);
        $tabla="";

        $pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
        $inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

        $consulta_datos = "SELECT * FROM distribuidor ORDER BY distribuidor_mostrar DESC";
        $consulta_total = "SELECT COUNT(*) FROM distribuidor ORDER BY distribuidor_mostrar DESC";

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
                        <th class="has-text-centered">Distribuidor</th>
                        <th class="has-text-centered">MOSTRAR</th>
                        <th class="has-text-centered">EDITAR</th>
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
                        <td>'.$rows['distribuidor_descripcion'].'</td>
                        <td>'.$rows['distribuidor_mostrar'].'</td>
                        <td><button onclick="window.location.href=\'' . APP_URL . 'distribuidorDetail/' . $rows['id_distribuidor'] . '/\'" type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-edit" ><i class="fas fa-pen"></i>&nbsp;Editar</button></td>
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
            $tabla.='<p class="has-text-right">Mostrando rubros <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

            $tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
        }

        return $tabla;
    }
}