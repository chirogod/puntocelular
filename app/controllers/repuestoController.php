<?php
    namespace app\controllers;
    use app\models\mainModel;

    class repuestoController extends mainModel {
        public function registrarPedidoControlador(){
            $id_seccion = $_POST['id_seccion'];

            $id_marca = "";
			$id_modelo = "";
			$orden_equipo_marca = "";
			$orden_otro_modelo = "";
            $repuesto_descripcion = "";

            

			if (isset($_POST['id_marca']) && $_POST['id_marca'] !== "") {
				$id_marca = $this->limpiarCadena($_POST['id_marca']);
				$datos_marca = $this->seleccionarDatos("Unico", "marca", "id_marca", $id_marca);
				$datos_marca = $datos_marca->fetch();
				$repuesto_descripcion .= $datos_marca['marca_descripcion'];
			} else if (isset($_POST['repuesto_otra_marca']) && $_POST['repuesto_otra_marca'] !== "") {
				$repuesto_descripcion .= $this->limpiarCadena($_POST['repuesto_otra_marca']);
			}
		
			if ($repuesto_descripcion == "") {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "Debe indicar la marca!",
					"icono" => "error"
				];
				return json_encode($alerta);
				exit();
			}
		
			// Check if an existing model is selected or a new model is entered
			if (isset($_POST['id_modelo']) && $_POST['id_modelo'] !== "") {
				$id_modelo = $this->limpiarCadena($_POST['id_modelo']);
				$datos_modelo = $this->seleccionarDatos("Unico", "modelo", "id_modelo", $id_modelo);
				$datos_modelo = $datos_modelo->fetch();
				$repuesto_descripcion .= " ".$datos_modelo['modelo_descripcion'];
			} else if (isset($_POST['repuesto_otro_modelo']) && $_POST['repuesto_otro_modelo'] !== "") {
				$repuesto_descripcion .= " ".$this->limpiarCadena($_POST['repuesto_otro_modelo']);
			}
		
			if ($repuesto_descripcion == "") {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "Debe indicar el modelo!",
					"icono" => "error"
				];
				return json_encode($alerta);
				exit();
			}
            
            $repuesto_color = $_POST['repuesto_color'];
            $id_orden = $_POST['id_orden'];
            if($repuesto_color != ""){
                $repuesto = $repuesto_descripcion." (".$repuesto_color.")";
            }else{
                $repuesto = $repuesto_descripcion;
            }
            $pedido_repuesto_hora = date("H:i:s");
            $pedido_repuesto_fecha = date("Y-m-d");
            $pedido_repuesto_responsable = $_POST['pedido_repuesto_responsable'];
            $id_sucursal = $_SESSION['id_sucursal'];

            if($id_seccion == "" || $repuesto_descripcion == "" || $id_orden == ""){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No introdujo los datos necesarios para completar el pedido",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            $check_orden = $this->ejecutarConsulta("SELECT * FROM orden WHERE id_orden = '$id_orden'");
            if($check_orden->rowCount() <= 0){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"La orden no existe en la base de datos",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            $datos_pedido = [
                [
                    "campo_nombre"=>"id_seccion_repuesto",
                    "campo_marcador"=>":Seccion",
                    "campo_valor"=>$id_seccion
                ],
                [
                    "campo_nombre"=>"pedido_repuesto_descripcion",
                    "campo_marcador"=>":Repuesto",
                    "campo_valor"=>$repuesto
                ],
                [
                    "campo_nombre"=>"id_orden",
                    "campo_marcador"=>":Orden",
                    "campo_valor"=>$id_orden
                ],
                [
                    "campo_nombre"=>"pedido_repuesto_fecha",
                    "campo_marcador"=>":Fecha",
                    "campo_valor"=>$pedido_repuesto_fecha
                ],
                [
                    "campo_nombre"=>"pedido_repuesto_hora",
                    "campo_marcador"=>":Hora",
                    "campo_valor"=>$pedido_repuesto_hora
                ],
                [
                    "campo_nombre"=>"pedido_estado",
                    "campo_marcador"=>":Estado",
                    "campo_valor"=>'espera'
                ],
                [
                    "campo_nombre"=>"pedido_repuesto_responsable",
                    "campo_marcador"=>":Responsable",
                    "campo_valor"=>$pedido_repuesto_responsable
                ],
                [
                    "campo_nombre"=>"id_sucursal",
                    "campo_marcador"=>":Sucursal",
                    "campo_valor"=>$id_sucursal
                ]
            ];
            $registrar_pedido = $this->guardarDatos("pedido_repuesto",$datos_pedido);
            if($registrar_pedido->rowCount()==1){
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Pedido registrado",
                    "texto"=>"El pedido del repuesto ".$repuesto." se registró con éxito",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo registrar el pedido, por favor intente nuevamente",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
            return json_encode($alerta);
        }

        public function listarPedidosControlador(){
            $datos = $this->ejecutarConsulta("SELECT 
                pedido_repuesto.id_pedido_repuesto, 
                pedido_repuesto.pedido_repuesto_descripcion, 
                pedido_repuesto.pedido_repuesto_fecha, 
                pedido_repuesto.pedido_repuesto_hora, 
                pedido_repuesto.id_orden,
                pedido_repuesto.id_seccion_repuesto,
                seccion_repuesto.id_seccion_repuesto,  
                seccion_repuesto.seccion_repuesto_descripcion,
                pedido_repuesto.pedido_repuesto_responsable,
                pedido_repuesto.pedido_estado,
                pedido_repuesto.pedido_estado
                FROM pedido_repuesto 
                INNER JOIN orden ON pedido_repuesto.id_orden = orden.id_orden
                INNER JOIN seccion_repuesto ON pedido_repuesto.id_seccion_repuesto = seccion_repuesto.id_seccion_repuesto
                INNER JOIN sucursal ON pedido_repuesto.id_sucursal = sucursal.id_sucursal
                WHERE pedido_repuesto.pedido_estado != 'ingreso'
                AND pedido_repuesto.id_sucursal = '".$_SESSION['id_sucursal']."'
                ORDER BY pedido_repuesto.id_pedido_repuesto DESC")
            ;
        

            $seccionRepuestos = [];
            foreach ($datos as $fila) {
                $id_pedido_repuesto = $fila['id_pedido_repuesto'];
                $id_seccion_repuesto = $fila['id_seccion_repuesto'];
                $seccion_repuesto_descripcion = $fila['seccion_repuesto_descripcion'];
                $pedido_repuesto_descripcion = $fila['pedido_repuesto_descripcion'];
                $pedido_repuesto_fecha = $fila['pedido_repuesto_fecha'];
                $pedido_repuesto_hora = $fila['pedido_repuesto_hora'];
                $id_orden = $fila['id_orden'];
                $pedido_repuesto_responsable = $fila['pedido_repuesto_responsable'];
                $pedido_estado = $fila['pedido_estado'];
                if (!isset($seccionRepuestos[$id_seccion_repuesto])) {
                    $seccionRepuestos[$id_seccion_repuesto] = [
                        'seccion' => $seccion_repuesto_descripcion,
                        'pedidos' => []
                    ];
                }
                if (!empty($pedido_repuesto_descripcion)) {
                    $seccionRepuestos[$id_seccion_repuesto]['pedidos'][] = [
                        'id'=>$id_pedido_repuesto,
                        'pedido' => $pedido_repuesto_descripcion,
                        'fecha' => $pedido_repuesto_fecha,
                        'hora' => $pedido_repuesto_hora,
                        'orden' => $id_orden,
                        'responsable' => $pedido_repuesto_responsable,
                        'estado' => $pedido_estado
                    ];
                }
            }

            // Generar la tabla HTML
            echo '<table class="table is-striped is-hoverable is-fullwidth">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Pedidos de repuestos</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($seccionRepuestos as $seccionRepuesto) {
                echo '<tr>';
                echo '<td colspan="2">';
                echo '<details>';
                echo '<summary>';
                echo $seccionRepuesto['seccion'];
                echo '</summary>';

                echo '<table class="table is-striped is-hoverable is-fullwidth mt-3">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Repuesto</th>';
                echo '<th>Fecha</th>';
                echo '<th>Orden</th>';
                echo '<th>Responsable</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                foreach ($seccionRepuesto['pedidos'] as $pedido) {
                    $pedido_estado ="";
                    if($pedido['estado'] == "pedido"){
                        $pedido_estado = "tachado";
                    }
                    echo '<tr class="'.$pedido_estado.'">';
                    echo '<td>' . $pedido['pedido'] . '</td>';
                    echo '<td>' . $pedido['fecha'] .' - ' . $pedido['hora'] .'</td>';
                    echo '<td>' . $pedido['orden'] . '</td>';
                    echo '<td>' . $pedido['responsable'] . '</td>';
                    echo '<td>
                            <form class="FormularioAjax" action="'.APP_URL.'app/ajax/repuestoAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >
                                <input type="hidden" name="modulo_repuesto" value="ingreso_pedido">
                                <input type="hidden" name="id_pedido_repuesto" value="'. $pedido['id'] .'">  
                                <button type="submit" class="button is-success is-rounded is-small" title="Remover producto">
                                    <p>Pedido</p>
                                </button>
                            </form>
                          </td>';
                    echo '<td>
                            <form class="FormularioAjax" action="'.APP_URL.'app/ajax/repuestoAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >
                                <input type="hidden" name="modulo_repuesto" value="eliminar_pedido">
                                <input type="hidden" name="id_pedido_repuesto" value="'. $pedido['id'] .'">  
                                <button type="submit" class="button is-danger is-rounded is-small" title="Ya ingreso">
                                    Ingreso<i class="fas fa-trash-restore fa-fw"></i>
                                </button>
                            </form>
                           </td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '</details>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';

        }

        public function ingresoPedidoControlador(){
            $id_pedido_repuesto = $_POST['id_pedido_repuesto'];
            $datos=[
                [
                    "campo_nombre"=>"pedido_estado",
                    "campo_marcador"=>":Estado",
                    "campo_valor"=>"pedido"
                ]
            ];
            $condicion=[
				"condicion_campo"=>"id_pedido_repuesto",
				"condicion_marcador"=>":Id",
				"condicion_valor"=>$id_pedido_repuesto
			];
            $actualizar = $this->actualizarDatos('pedido_repuesto', $datos, $condicion);
            if($actualizar){
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Operacion exitosa",
                    "texto"=>"El repuesto se marco como pedido.",
                    "icono"=>"success"
                ];
                
            }else{
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo marcar el repuesto como ingresado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
            return json_encode($alerta);
        }

        //se le elimina de la lista al pasar al estado 'ingreso'. No se elimina de la base de datos
        public function eliminarPedidoControlador(){
            $id_pedido_repuesto = $_POST['id_pedido_repuesto'];
            $datos=[
                [
                    "campo_nombre"=>"pedido_estado",
                    "campo_marcador"=>":Estado",
                    "campo_valor"=>"ingreso"
                ]
            ];
            $condicion=[
				"condicion_campo"=>"id_pedido_repuesto",
				"condicion_marcador"=>":Id",
				"condicion_valor"=>$id_pedido_repuesto
			];
            $actualizar = $this->actualizarDatos('pedido_repuesto', $datos, $condicion);
            if($actualizar){
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Operacion exitosa",
                    "texto"=>"El repuesto se elimino de la lista, se marco como que ingreso.",
                    "icono"=>"success"
                ];
                
            }else{
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo marcar el repuesto como ingresado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
            return json_encode($alerta);
        }

        public function buscarPedidoControlador() {
            $where = "";
            $where_estado = "";
            $where_fecha = "";
            // Si viene la sucursal, aplicamos el filtro
			if (isset($_POST['sucursal']) && !empty($_POST['sucursal'])) {
				$id_sucursal = intval($this->limpiarCadena($_POST['sucursal']));
				$where = "WHERE pedido_repuesto.id_sucursal = '$id_sucursal'";
			}
		
			// Si viene el estado, aplicamos el filtro
			if (isset($_POST['estado']) && !empty($_POST['estado'])) {
				$estado = $this->limpiarCadena($_POST['estado']);
				$where_estado = "AND pedido_repuesto.pedido_estado = '$estado'";
			}
		
			// Si vienen las fechas, aplicamos el filtro
			if (isset($_POST['fecha_inicio']) && !empty($_POST['fecha_inicio']) && isset($_POST['fecha_fin']) && !empty($_POST['fecha_fin'])) {
				$fecha_inicio = $this->limpiarCadena($_POST['fecha_inicio']);
				$fecha_fin = $this->limpiarCadena($_POST['fecha_fin']);
				$where_fecha = "AND pedido_repuesto.pedido_repuesto_fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
			}

            $consulta = "SELECT 
                pedido_repuesto.id_pedido_repuesto, 
                pedido_repuesto.pedido_repuesto_descripcion, 
                pedido_repuesto.pedido_repuesto_fecha, 
                pedido_repuesto.pedido_repuesto_hora, 
                pedido_repuesto.id_orden,
                pedido_repuesto.id_sucursal,
                sucursal.id_sucursal,
                sucursal.sucursal_descripcion,
                pedido_repuesto.id_seccion_repuesto,
                seccion_repuesto.id_seccion_repuesto,  
                seccion_repuesto.seccion_repuesto_descripcion,
                pedido_repuesto.pedido_repuesto_responsable,
                pedido_repuesto.pedido_estado
            FROM pedido_repuesto 
            INNER JOIN orden ON pedido_repuesto.id_orden = orden.id_orden
            INNER JOIN seccion_repuesto ON pedido_repuesto.id_seccion_repuesto = seccion_repuesto.id_seccion_repuesto
            INNER JOIN sucursal ON pedido_repuesto.id_sucursal = sucursal.id_sucursal
            $where $where_estado $where_fecha 
            ORDER BY pedido_repuesto.pedido_repuesto_fecha DESC";
        
            $datos_pedido = $this->ejecutarConsulta($consulta);
        
            $seccionRepuestos = [];
            foreach ($datos_pedido as $fila) {
                $id_seccion_repuesto = $fila['id_seccion_repuesto'];
                if (!isset($seccionRepuestos[$id_seccion_repuesto])) {
                    $seccionRepuestos[$id_seccion_repuesto] = [
                        'seccion' => $fila['seccion_repuesto_descripcion'],
                        'pedidos' => []
                    ];
                }
        
                $seccionRepuestos[$id_seccion_repuesto]['pedidos'][] = [
                    'id' => $fila['id_pedido_repuesto'],
                    'pedido' => $fila['pedido_repuesto_descripcion'],
                    'fecha' => $fila['pedido_repuesto_fecha'],
                    'hora' => $fila['pedido_repuesto_hora'],
                    'orden' => $fila['id_orden'],
                    'responsable' => $fila['pedido_repuesto_responsable'],
                    'estado' => $fila['pedido_estado'],
                    'sucursal'=> $fila['sucursal_descripcion']
                ];
            }
        
            echo '<table class="table is-striped is-hoverable is-fullwidth">';
            echo '<thead><tr><th class="has-text-left">Pedidos de Repuestos</th></tr></thead>';
            echo '<tbody>';
        
            foreach ($seccionRepuestos as $seccion) {
                echo '<tr><td colspan="5"><details>';
                echo '<summary class="has-text-left is-clickable">' . htmlspecialchars($seccion['seccion']) . '</summary>';
                echo '<table class="table is-striped is-hoverable is-fullwidth mt-3">';
                echo '<thead>
                        <tr>
                            <th>Repuesto</th>
                            <th>Fecha</th>
                            <th>Orden</th>
                            <th>Responsable</th>
                            <th>Estado</th>
                            <th>Sucursal</th>
                        </tr>
                    </thead>';
                echo '<tbody>';
        
                foreach ($seccion['pedidos'] as $pedido) {
                    $pedido_estado = "";
    
                    if ($pedido['estado'] === "pedido") {
                        $pedido_estado = "tachado"; // Clase para tachar el texto
                    } elseif ($pedido['estado'] === "ingreso") {
                        $pedido_estado = "eliminado"; // Clase para tachar y fondo rojo tenue
                    }
                    echo '<tr class="' . $pedido_estado . '">';
                    echo '<td>' . htmlspecialchars($pedido['pedido']) . '</td>';
                    echo '<td>' . htmlspecialchars($pedido['fecha']) . ' - ' . htmlspecialchars($pedido['hora']) . '</td>';
                    echo '<td>' . htmlspecialchars($pedido['orden']) . '</td>';
                    echo '<td>' . htmlspecialchars($pedido['responsable']) . '</td>';
                    echo '<td>' . htmlspecialchars(ucfirst($pedido['estado'])) . '</td>';
                    echo '<td>' . htmlspecialchars($pedido['sucursal']) . '</td>';
                    echo '</tr>';
                }
        
                echo '</tbody></table></details></td></tr>';
            }
        
            echo '</tbody></table>';
        }
    }