<?php
    namespace app\controllers;
    use app\models\mainModel;

    class repuestoController extends mainModel {
        public function registrarPedidoControlador(){
            $id_seccion = $_POST['id_seccion'];
            $repuesto_descripcion = $_POST['repuesto_descripcion'];
            $repuesto_color = $_POST['repuesto_color'];
            $id_orden = $_POST['id_orden'];
            if($repuesto_color != ""){
                $repuesto = $repuesto_descripcion." (".$repuesto_color.")";
            }else{
                $repuesto = $repuesto_descripcion;
            }
            $pedido_repuesto_hora = date("H:i:s");
            $pedido_repuesto_fecha = date("Y-m-d");
            $id_usuario = $_SESSION['id_usuario'];
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
                    "campo_nombre"=>"id_usuario",
                    "campo_marcador"=>":Usuario",
                    "campo_valor"=>$id_usuario
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
                pedido_repuesto.id_usuario,
                usuario.id_usuario,
                usuario.usuario_nombre_completo,
                pedido_repuesto.pedido_repuesto_recibido 
                FROM pedido_repuesto 
                INNER JOIN usuario ON pedido_repuesto.id_usuario = usuario.id_usuario 
                INNER JOIN orden ON pedido_repuesto.id_orden = orden.id_orden
                INNER JOIN seccion_repuesto ON pedido_repuesto.id_seccion_repuesto = seccion_repuesto.id_seccion_repuesto
                INNER JOIN sucursal ON pedido_repuesto.id_sucursal = sucursal.id_sucursal
                WHERE pedido_repuesto.id_sucursal = '".$_SESSION['id_sucursal']."'
                ORDER BY pedido_repuesto.id_pedido_repuesto DESC")
            ;
        

            $seccionRepuestos = [];
            foreach ($datos as $fila) {
                $id_seccion_repuesto = $fila['id_seccion_repuesto'];
                $seccion_repuesto_descripcion = $fila['seccion_repuesto_descripcion'];
                $pedido_repuesto_descripcion = $fila['pedido_repuesto_descripcion'];
                $pedido_repuesto_fecha = $fila['pedido_repuesto_fecha'];
                $pedido_repuesto_hora = $fila['pedido_repuesto_hora'];
                $id_orden = $fila['id_orden'];
                $usuario_nombre_completo = $fila['usuario_nombre_completo'];
                if (!isset($seccionRepuestos[$id_seccion_repuesto])) {
                    $seccionRepuestos[$id_seccion_repuesto] = [
                        'seccion' => $seccion_repuesto_descripcion,
                        'pedidos' => []
                    ];
                }
                if (!empty($pedido_repuesto_descripcion)) {
                    $seccionRepuestos[$id_seccion_repuesto]['pedidos'][] = [
                        'pedido' => $pedido_repuesto_descripcion,
                        'fecha' => $pedido_repuesto_fecha,
                        'hora' => $pedido_repuesto_hora,
                        'orden' => $id_orden,
                        'usuario' => $usuario_nombre_completo
                    ];
                }
            }

            // Generar la tabla HTML
            echo '<table class="table is-striped is-hoverable is-fullwidth">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Sección</th>';
            echo '<th>Pedidos</th>';
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
                echo '<th>Hora</th>';
                echo '<th>Orden</th>';
                echo '<th>Usuario</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                foreach ($seccionRepuesto['pedidos'] as $pedido) {
                    echo '<tr>';
                    echo '<td>' . $pedido['pedido'] . '</td>';
                    echo '<td>' . $pedido['fecha'] . '</td>';
                    echo '<td>' . $pedido['hora'] . '</td>';
                    echo '<td>' . $pedido['orden'] . '</td>';
                    echo '<td>' . $pedido['usuario'] . '</td>';
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
    }