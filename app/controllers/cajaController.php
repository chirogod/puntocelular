<?php
    namespace app\controllers;
    use app\models\mainModel;

    class cajaController extends mainModel{
        /*-----CONTROLADOR PARA REGISTRAR MOVIMIENTOS DE LA CAJA FISICA ---------*/
        public function registrarCajaControlador(){
            $sucursal = $_SESSION['id_sucursal'];
            
            $fecha_caja_movimiento=date("Y-m-d");
            $hora_caja_movimiento=date("h:i a");
            $tipo_movimiento = $_POST['tipo_movimiento'];
            $importe_movimiento = $this->limpiarCadena($_POST['importe_movimiento']);
            $detalle_movimiento = $this->limpiarCadena($_POST['detalle_movimiento']);
            $id_usuario = $_SESSION['id_usuario'];

            if($importe_movimiento<=0 ){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No ha ingresado un importe valido",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            $check_usuario=$this->ejecutarConsulta("SELECT id_usuario FROM usuario WHERE id_usuario='$id_usuario'");
			if($check_usuario->rowCount()<=0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el usuario registrado en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            /*== Comprobando caja en la DB ==*/
            $check_caja = $this->ejecutarConsulta("SELECT * FROM caja WHERE id_sucursal='$sucursal' AND caja_codigo LIKE '%Efectivo%'");;
			if($check_caja->rowCount()<=0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"La caja no está registrada en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }else{
                $datos_caja=$check_caja->fetch();
            }

            /*== Formateando variables ==*/
            $importe_movimiento=number_format($importe_movimiento,MONEDA_DECIMALES,'.','');

            $importe_movimiento_final = $importe_movimiento;
            $importe_movimiento_final=number_format($importe_movimiento_final,MONEDA_DECIMALES,'.','');
             
            /*== Calculando total en caja ==*/
            $movimiento_cantidad=$importe_movimiento;
            $movimiento_cantidad=number_format($movimiento_cantidad,MONEDA_DECIMALES,'.','');
            if($tipo_movimiento == "Ingreso"){
                $total_caja=$datos_caja['caja_monto']+$movimiento_cantidad;
            }elseif ($tipo_movimiento == "Egreso") {
                $total_caja=$datos_caja['caja_monto']-$movimiento_cantidad;
            }
                       
            $total_caja=number_format($total_caja,MONEDA_DECIMALES,'.','');

            $sucursal_id = $datos_caja['id_sucursal'];
            $caja_fisica = $this->ejecutarConsulta("SELECT * FROM caja WHERE id_sucursal = '$sucursal_id' AND caja_codigo LIKE '%Efectivo%'")->fetch();

            $datos_movimiento = [
                [
                    "campo_nombre"=>"fecha_caja_movimiento",
                    "campo_marcador"=>":Fecha",
                    "campo_valor"=>$fecha_caja_movimiento
                ],
                [
                    "campo_nombre"=>"hora_caja_movimiento",
                    "campo_marcador"=>":Hora",
                    "campo_valor"=>$hora_caja_movimiento
                ],
                [
                    "campo_nombre"=>"tipo_movimiento",
                    "campo_marcador"=>":Tipo",
                    "campo_valor"=>$tipo_movimiento
                ],
                [
                    "campo_nombre"=>"importe_movimiento",
                    "campo_marcador"=>":Importe",
                    "campo_valor"=>$importe_movimiento
                ],
                [
                    "campo_nombre"=>"detalle_movimiento",
                    "campo_marcador"=>":Detalle",
                    "campo_valor"=>$detalle_movimiento
                ],
                [
                    "campo_nombre"=>"id_sucursal",
                    "campo_marcador"=>":Sucursal",
                    "campo_valor"=>$sucursal
                ],
                [
                    "campo_nombre"=>"id_usuario",
                    "campo_marcador"=>":Usuario",
                    "campo_valor"=>$id_usuario
                ],
                [
                    "campo_nombre"=>"caja_codigo",
                    "campo_marcador"=>":Caja",
                    "campo_valor"=>$caja_fisica['caja_codigo']
                ]
            ];
            
            if ($caja_fisica) {
                $datos_caja_fisica_up=[
                    [
                        "campo_nombre"=>"caja_monto",
                        "campo_marcador"=>":Monto",
                        "campo_valor"=>$total_caja
                    ]
                ];
        
                $condicion_caja_fisica=[
                    "condicion_campo"=>"id_caja",
                    "condicion_marcador"=>":ID",
                    "condicion_valor"=>$caja_fisica['id_caja']
                ];
        
                $this->actualizarDatos("caja",$datos_caja_fisica_up,$condicion_caja_fisica);
            }

            $registrar_movimiento = $this->guardarDatos("caja_movimiento", $datos_movimiento);
            if ($registrar_movimiento->rowCount()==1) {
                $alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"movimiento registrado con exito",
					"texto"=>"El movimiento se registro con exito",
					"icono"=>"success"
				];
            }else{
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el movimiento, por favor intente nuevamente",
					"icono"=>"error"
				];
            }
            //retornamos el json 
            return json_encode($alerta);
        }

        /*----------  Controlador listar MOVIMIENTOS DE LA CAJA FISICA ----------*/
		public function listarCajaControlador($pagina,$registros,$url,$busqueda){
            $pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);

			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			$consulta_datos="SELECT * FROM caja_movimiento WHERE id_sucursal = '$_SESSION[id_sucursal]' AND caja_codigo LIKE '%Efectivo%'  ORDER BY fecha_caja_movimiento ASC LIMIT $inicio,$registros";

			$consulta_total="SELECT COUNT(id_caja_movimiento) FROM caja_movimiento ";


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
		                    <th class="has-text-centered">Fecha</th>
		                    <th class="has-text-centered">Hora</th>
		                    <th class="has-text-centered">Tipo</th>
                            <th class="has-text-centered">Importe</th>
		                    <th class="has-text-centered">Detalle</th>
                            <th class="has-text-centered">Usuario</th>
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
							<td>'.$rows['fecha_caja_movimiento'].'</td>
							<td>'.$rows['hora_caja_movimiento'].'</td>
                            <td>'.$rows['tipo_movimiento'].'</td>
                            <td>'.$rows['importe_movimiento'].'</td>
							<td>'.$rows['detalle_movimiento'].'</td>
			                <td>'.$rows['id_usuario'].'</td>
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
									<a href="'.APP_URL.'cajaIng/" class="button is-success is-rounded is-small">Ingresar dinero</a>
								</div>
                                <div class="mt-1">
									<a href="'.APP_URL.'cajaEg/" class="button is-success is-rounded is-small">Egresar dinero</a>
								</div>
			                </td>
			            </tr>
					';
				}
			}

			$tabla.='</tbody></table></div>';

			### Paginacion ###
			if($total>0 && $pagina<=$numeroPaginas){
				$tabla.='<p class="has-text-right">Mostrando movimientos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

				$tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
			}

			return $tabla;
        }

    }


?>