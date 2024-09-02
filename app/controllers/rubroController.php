<?php
namespace app\controllers;
use app\models\mainModel;

class rubroController extends mainModel{
    public function registrarRubroControlador(){
        $rubro = $this->limpiarCadena($_POST['rubro_descripcion']);
        $rubro_sucursal = $this->limpiarCadena($_POST['rubro_sucursal']);

        if($rubro == "" || $rubro_sucursal == ""){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No has llenado todos los campos que son obligatorios",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$rubro)){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"El nombre del rubro no cumple con el formato solicitado",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        $check_nombre = $this->ejecutarConsulta("SELECT * FROM rubro WHERE rubro_descripcion = '$rubro'");
        if ($check_nombre->rowCount()>0) {
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"Ya hay un rubro registrado con ese nombre",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }

        $check_sucursal=$this->ejecutarConsulta("SELECT * FROM sucursal WHERE id_sucursal = '$rubro_sucursal'");
		if($check_sucursal->rowCount()<=0){
		    $alerta=[
				"tipo"=>"simple",
				"titulo"=>"Ocurrió un error inesperado",
				"texto"=>"La sucursal no existe en el sistema",
				"icono"=>"error"
			];
			return json_encode($alerta);
		    exit();
		}

        $datos_rubro = [
            [
                "campo_nombre"=>"rubro_descripcion",
                "campo_marcador"=>":Rubro",
                "campo_valor"=>$rubro
            ],
            [
                "campo_nombre"=>"id_sucursal",
                "campo_marcador"=>":Sucursal",
                "campo_valor"=>$rubro_sucursal
            ]
        ];

        $registrar_rubro = $this->guardarDatos("rubro", $datos_rubro);

            if ($registrar_rubro->rowCount()==1) {
                $alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Rubro registrado con exito",
					"texto"=>"El rubro '".$rubro."' se registro con exito",
					"icono"=>"success"
				];
            }else{
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el rubro, por favor intente nuevamente",
					"icono"=>"error"
				];
            }
        //retornamos el json 
        return json_encode($alerta);
    }

    public function listarRubroControlador($pagina,$registros,$url,$busqueda){
        $pagina=$this->limpiarCadena($pagina);
        $registros=$this->limpiarCadena($registros);

        $url=$this->limpiarCadena($url);
        $url=APP_URL.$url."/";

        $busqueda=$this->limpiarCadena($busqueda);
        $tabla="";

        $pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
        $inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

        if (isset($busqueda) && $busqueda != "") {
            $consulta_datos = "SELECT r.*, s.sucursal_descripcion
                             FROM rubro r 
                             INNER JOIN sucursal s ON r.id_sucursal = s.id_sucursal 
                             WHERE ((r.rubro_descripcion LIKE '%$busqueda%' OR r.id_rubro LIKE '%$busqueda%' OR r.id_sucursal LIKE '%$busqueda%' )) 
                             ORDER BY r.rubro_descripcion ASC LIMIT $inicio,$registros";
    
            $consulta_total = "SELECT COUNT(r.id_rubro) 
                             FROM rubro r 
                             INNER JOIN sucursal s ON r.id_sucursal = s.id_sucursal 
                             WHERE ((r.rubro_descripcion LIKE '%$busqueda%' OR r.id_rubro LIKE '%$busqueda%' OR r.id_sucursal LIKE '%$busqueda%') )";
        } else {
            $consulta_datos = "SELECT r.*, s.sucursal_descripcion
                             FROM rubro r 
                             INNER JOIN sucursal s ON r.id_sucursal = s.id_sucursal 
                             ORDER BY r.rubro_descripcion ASC LIMIT $inicio,$registros";
    
            $consulta_total = "SELECT COUNT(r.id_rubro) 
                             FROM rubro r 
                             INNER JOIN sucursal s ON r.id_sucursal = s.id_sucursal";
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
                        <td>'.$rows['rubro_descripcion'].'</td>
                        <td>'.$rows['sucursal_descripcion'].'</td>
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







?>