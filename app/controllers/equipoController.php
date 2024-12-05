<?php
    namespace app\controllers;
    use app\models\mainModel;

    class equipoController extends mainModel{
        public function registrarEquipoControlador(){
            /*== sino generar aleatoriamente el codigo del equipo ==*/
            $correlativo=$this->ejecutarConsulta("SELECT id_equipo FROM equipo");
            $correlativo=($correlativo->rowCount())+1;
            $equipo_codigo=$this->generarCodigoAleatorio(7,$correlativo);

            // verificar que el codigo no exista ya
            $check_codigo =$this->ejecutarConsulta("SELECT * FROM equipo WHERE equipo_codigo = '$equipo_codigo'");
            if ($check_codigo->rowCount() > 0) {
                // si ya existe generar otro
                $equipo_codigo=$this->generarCodigoAleatorio(7,$correlativo);
            }

            $id_marca = $this->limpiarCadena($_POST['id_marca']);
            $id_modelo = $this->limpiarCadena($_POST['id_modelo']);
            $equipo_estado = "Disponible";
            $equipo_almacenamiento = $this->limpiarCadena($_POST['equipo_almacenamiento']);
            $equipo_color = $this->limpiarCadena($_POST['equipo_color']);
            $equipo_ram = $this->limpiarCadena($_POST['equipo_ram']);
            $equipo_costo = $this->limpiarCadena($_POST['equipo_costo']);
            $equipo_imei = $this->limpiarCadena($_POST['equipo_imei']);
            $id_sucursal = $_SESSION['id_sucursal'];
            $equipo_modulo = $this->limpiarCadena($_POST['equipo_modulo']);
    
            $datos_equipo = [
                [
                    "campo_nombre"=>"equipo_codigo",
                    "campo_marcador"=>":Codigo",
                    "campo_valor"=>$equipo_codigo
                ],
                [
                    "campo_nombre"=>"equipo_estado",
                    "campo_marcador"=>":Estado",
                    "campo_valor"=>$equipo_estado
                ],
                [
                    "campo_nombre"=>"id_marca",
                    "campo_marcador"=>":Marca",
                    "campo_valor"=>$id_marca
                ],
                [
                    "campo_nombre"=>"id_modelo",
                    "campo_marcador"=>":Modelo",
                    "campo_valor"=>$id_modelo
                ],
                [
                    "campo_nombre"=>"equipo_almacenamiento",
                    "campo_marcador"=>":Almacenamiento",
                    "campo_valor"=>$equipo_almacenamiento
                ],
                [
                    "campo_nombre"=>"equipo_ram",
                    "campo_marcador"=>":Ram",
                    "campo_valor"=>$equipo_ram
                ],
                [
                    "campo_nombre"=>"equipo_color",
                    "campo_marcador"=>":Color",
                    "campo_valor"=>$equipo_color
                ],
                [
                    "campo_nombre"=>"equipo_imei",
                    "campo_marcador"=>":Imei",
                    "campo_valor"=>$equipo_imei
                ],
                [
                    "campo_nombre"=>"equipo_costo",
                    "campo_marcador"=>":Costo",
                    "campo_valor"=>$equipo_costo
                ],
                [
                    "campo_nombre"=>"id_sucursal",
                    "campo_marcador"=>":Sucursal",
                    "campo_valor"=>$id_sucursal
                ],
                [
                    "campo_nombre"=>"equipo_modulo",
                    "campo_marcador"=>":Modulo",
                    "campo_valor"=>$equipo_modulo
                ],
            ];
    
            $registrar_equipo = $this->guardarDatos("equipo", $datos_equipo);
            if ($registrar_equipo->rowCount()==1) {
                $alerta=[
                    "tipo"=>"limpiar",
                    "titulo"=>"Equipo registrado con exito",
                    "texto"=>"El equipo se registro con exito",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo registrar el equipo, por favor intente nuevamente",
                    "icono"=>"error"
                ];
            }
            //retornamos el json 
            return json_encode($alerta);
        }

        /*---------- Controlador buscar cliente ----------*/
        public function buscarEquipoControlador(){
            $estado = $this->limpiarCadena($_POST['estado']);
            $modulo = $this->limpiarCadena($_POST['modulo']);
            $sucursal = $_SESSION['id_sucursal'];
        
            $where = "WHERE id_sucursal = '$sucursal'";
        
            if($estado == "Disponible"){
                $where .= " AND equipo_estado = 'Disponible'";
            } elseif($estado == "Reservado"){
                $where .= " AND equipo_estado = 'Reservado'";
            }elseif($estado == "Vendido"){
                $where .= " AND equipo_estado = 'Vendido'";
            }elseif($estado == "Pausado"){
                $where .= " AND equipo_estado = 'Pausado'";
            }else{
                $where .= "";
            }
        
            switch($modulo){
                case 'android_nuevo':
                    $orderBy = " AND equipo_modulo = 'android_nuevo'";
                    break;
                case 'iphone_nuevo':
                    $orderBy = " AND equipo_modulo = 'iphone_nuevo'";
                    break;
                case 'android_reac':
                    $orderBy = " AND equipo_modulo = 'android_reac'";
                    break;
                case 'iphone_reac':
                    $orderBy = " AND equipo_modulo = 'iphone_reac'";
                    break;
                case 'android':
                    $orderBy = " AND equipo_modulo = 'android'";
                    break;
                case 'iphone':
                    $orderBy = " AND equipo_modulo = 'iphone'";
                    break;
                case 'prestamo':
                    $orderBy = " AND equipo_modulo = 'prestamo'";
                    break;
            }
        
            $consulta_datos = "SELECT 
                                    e.id_equipo, e.equipo_codigo, e.equipo_estado, 
                                    m.marca_descripcion, mo.modelo_descripcion,
                                    e.equipo_almacenamiento, e.equipo_ram, e.equipo_color, 
                                    e.equipo_costo, e.equipo_imei, e.id_sucursal, e.equipo_modulo
                                FROM equipo e
                                JOIN marca m ON e.id_marca = m.id_marca
                                JOIN modelo mo ON e.id_modelo = mo.id_modelo 
                                $where $orderBy";
            $datos_equipo = $this->ejecutarConsulta($consulta_datos);
        
            if ($datos_equipo->rowCount() >= 1) {
                $datos_equipo = $datos_equipo->fetchAll();
                $tabla = '<div class="table-container style="border-collapse: collapse;">
                    <table class="table is-striped is-narrow is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th style="border: 1px solid black;">Estado</th>
                            <th style="border: 1px solid black;">Marca</th>
                            <th style="border: 1px solid black;">Modelo</th>
                            <th style="border: 1px solid black;">Almacenamiento</th>
                            <th style="border: 1px solid black;">RAM</th>
                            <th style="border: 1px solid black;">Color</th>
                            <th style="border: 1px solid black;">Costo</th>
                            <th style="border: 1px solid black;">IMEI</th>
                        </tr>
                    </thead>
                    <tbody>';
            
                    foreach ($datos_equipo as $rows) {
                        // Determinar clase CSS según el estado
                        $clase_estado = '';
                        switch (strtolower($rows['equipo_estado'])) {
                            case 'disponible':
                                $clase_estado = 'is-success'; // Verde
                                break;
                            case 'reservado':
                                $clase_estado = 'is-warning'; // Amarillo
                                break;
                            case 'vendido':
                                $clase_estado = 'is-danger'; // Rojo
                                break;
                            case 'pausado':
                                $clase_estado = 'is-info'; // Azul
                                break;
                            default:
                                $clase_estado = ''; // Sin clase
                        }
                    
                        $tabla .= '
                        <tr class="' . $clase_estado . '" style="cursor: pointer;" onclick="window.location.href=\'' . APP_URL . 'equipoUpdate/' . $rows['id_equipo'] . '/\'">
                            <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_estado']) . '</td>
                            <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['marca_descripcion']) . '</td>
                            <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['modelo_descripcion']) . '</td>
                            <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_almacenamiento']) . '</td>
                            <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_ram']) . '</td>
                            <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_color']) . '</td>
                            <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_costo']) . '</td>
                            <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_imei']) . '</td>
                        </tr>';
                    }
            
                $tabla .= '</tbody></table></div>';
                echo $tabla;
            } else {
                echo '<p class="notification is-warning">No hay equipos registrados.</p>';
            }
        }

    }

    





?>