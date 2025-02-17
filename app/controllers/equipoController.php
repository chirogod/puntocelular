<?php
    namespace app\controllers;
    use app\models\mainModel;

    class equipoController extends mainModel{

        public function registrarEquipoControlador(){
            $equipo_modulo = $this->limpiarCadena($_POST['equipo_modulo']);
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
            $check_marca = $this->ejecutarConsulta("SELECT * FROM marca WHERE id_marca = '$id_marca'");
            if ($check_marca->rowCount() > 0) {
                // si ya existe generar otro
                $marca=$check_marca->fetch();
                $equipo_marca = $marca['marca_descripcion'];
            }
            
            $id_modelo = $this->limpiarCadena($_POST['id_modelo']);
            $check_modelo = $this->ejecutarConsulta("SELECT * FROM modelo WHERE id_modelo = '$id_modelo'");
            if ($check_modelo->rowCount() > 0) {
                // si ya existe generar otro
                $modelo=$check_modelo->fetch();
                $equipo_modelo = $modelo['modelo_descripcion'];
            }

            $equipo_estado = "Disponible";
            $equipo_almacenamiento = $this->limpiarCadena($_POST['equipo_almacenamiento']);
            $equipo_color = $this->limpiarCadena($_POST['equipo_color']);

            if($equipo_modulo == "iphone" || $equipo_modulo == "iphone_nuevo" || $equipo_modulo == "iphone_reac"){
                $equipo_ram = "-";
                
            }else{
                $equipo_ram = $this->limpiarCadena($_POST['equipo_ram']);
            }

            if($equipo_modulo == "iphone_reac"){
                $equipo_bateria = $this->limpiarCadena($_POST['equipo_bateria']);
            }else{
                $equipo_bateria = "-";
            }



            
            
            $equipo_costo = $this->limpiarCadena($_POST['equipo_costo']);
            $equipo_imei = $this->limpiarCadena($_POST['equipo_imei']);
            $id_sucursal = $_SESSION['id_sucursal'];
    
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
                    "campo_nombre"=>"equipo_marca",
                    "campo_marcador"=>":Marca",
                    "campo_valor"=>$equipo_marca
                ],
                [
                    "campo_nombre"=>"equipo_modelo",
                    "campo_marcador"=>":Modelo",
                    "campo_valor"=>$equipo_modelo
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
                    "campo_nombre"=>"equipo_bateria",
                    "campo_marcador"=>":Bateria",
                    "campo_valor"=>$equipo_bateria
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

        public function actualizarEquipoControlador(){
            $equipo_codigo=$this->limpiarCadena($_POST['equipo_codigo']);

            $equipo_estado = $this->limpiarCadena($_POST['equipo_estado']);
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

            $condicion=[
                "condicion_campo"=>"equipo_codigo",
                "condicion_marcador"=>":Codigo",
                "condicion_valor"=>$equipo_codigo
            ];
    
            if($this->actualizarDatos("equipo",$datos_equipo,$condicion)){
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Equipo actualizado",
                    "texto"=>"Los datos del equipo se actualizaron correctamente",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No hemos podido actualizar los datos del equipo, por favor intente nuevamente",
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
            $precio = $this->limpiarCadena($_POST['precio']);
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
                default:
                    $orderBy = " ";
                    break;
            }

            switch ($precio) {
                case 'menor2mayor':
                    $orderPrice = " ORDER BY equipo_costo ASC";
                    break;
                case 'mayor2menor':
                    $orderPrice = " ORDER BY equipo_costo DESC";
                    break;
                case 'todos':
                    $orderPrice = "";
                    break;
            }


        
            $consulta_datos = "SELECT * FROM equipo $where $orderBy $orderPrice";
            $datos_equipo = $this->ejecutarConsulta($consulta_datos);
        
            if ($datos_equipo->rowCount() >= 1) {
                $datos_equipo = $datos_equipo->fetchAll();
                $tabla = '<div class="table-container is-size-7" style="border-collapse: collapse;">
                    <table class="table is-striped is-narrow is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th style="border: 1px solid black;">Estado</th>
                            <th style="border: 1px solid black;">Marca</th>
                            <th style="border: 1px solid black;">Modelo</th>
                            <th style="border: 1px solid black;">Almac.</th>';
                // Verificar si los registros tienen "RAM" o "BATERIA"
                foreach ($datos_equipo as $equipo) {
                    if ($equipo['equipo_ram'] != "-") {
                        $tabla .= '<th style="border: 1px solid black;">Ram</th>';
                        break; // Evitamos seguir iterando innecesariamente
                    } elseif ($equipo['equipo_bateria'] != "-") {
                        $tabla .= '<th style="border: 1px solid black;">Bateria</th>';
                        break;
                    }
                }
                $tabla .='
                            <th style="border: 1px solid black;">Color</th>
                            <th style="border: 1px solid black;">Costo</th>
                            <th style="border: 1px solid black;">Precio</th>
                            <th style="border: 1px solid black;">3 s/i</th>
                            <th style="border: 1px solid black;">6 s/i</th>
                            <th style="border: 1px solid black;">9 fijas</th>
                            <th style="border: 1px solid black;">12 fijas</th>
                            <th style="border: 1px solid black;">Efectivo</th>
                            <th style="border: 1px solid black;">Efectivo usd</th>
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
                    
                        $usd_pc = $_SESSION['usd_pc'];
                        $efectivo_usd = $rows['equipo_costo'] * 1.4;
                        $precio = $efectivo_usd * 1.4 * $usd_pc;
                        $efectivo = $precio * 0.75;
                        $sin_int_3 = $precio / 3;
                        $sin_int_6 = $precio / 6; 
                        $fijas_9 = ($efectivo_usd * 1.5 * $usd_pc) / 9;
                        $fijas_12 = ($efectivo_usd * 1.6 * $usd_pc) / 12;
                    
                        $tabla .= '
                            <tr class="' . $clase_estado . '" style="cursor: pointer;" onclick="window.location.href=\'' . APP_URL . 'equipoUpdate/' . $rows['id_equipo'] . '/\'">
                                <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_estado']) . '</td>
                                <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_marca']) . '</td>
                                <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_modelo']) . '</td>
                                <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_almacenamiento']) . '</td>';
                        if($rows['equipo_ram'] != "-"){
                            $tabla .= ' <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_ram']) . '</td>';
                        }                                
                               
                        if($rows['equipo_bateria'] != "-"){
                            $tabla .= ' <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_bateria']) . '</td>';
                        } 
                        
                        $tabla .= '<td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_color']) . '</td>
                                <td class="has-text-centered" style="border: 1px solid black;">$' . htmlspecialchars(number_format(round($rows['equipo_costo']), 0)) . '</td>
                                <td class="has-text-centered" style="border: 1px solid black;">$' . htmlspecialchars(number_format(round($precio), 0)) . '</td>
                                <td class="has-text-centered" style="border: 1px solid black;">$' . htmlspecialchars(number_format(round($sin_int_3), 0)) . '</td>
                                <td class="has-text-centered" style="border: 1px solid black;">$' . htmlspecialchars(number_format(round($sin_int_6), 0)) . '</td>
                                <td class="has-text-centered" style="border: 1px solid black;">$' . htmlspecialchars(number_format(round($fijas_9), 0)) . '</td>
                                <td class="has-text-centered" style="border: 1px solid black;">$' . htmlspecialchars(number_format(round($fijas_12), 0)) . '</td>
                                <td class="has-text-centered" style="border: 1px solid black;">$' . htmlspecialchars(number_format(round($efectivo), 0)) . '</td>
                                <td class="has-text-centered" style="border: 1px solid black;">$' . htmlspecialchars(number_format(round($efectivo_usd), 0)) . '</td>
                                <td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($rows['equipo_imei']) . '</td>
                            ';

                            if($rows['equipo_estado'] == "Disponible"){
                                $tabla .= '
                                <td class="has-text-centered" style="border: 1px solid black;" onclick="event.stopPropagation(); window.location.href=\'' . APP_URL . 'saleEquipoNew/' . $rows['id_equipo'] . '/\'">
                                    <i class="fas fa-cart-plus fa-fw"></i>
                                </td>
                                <td class="has-text-centered" style="border: 1px solid black;" onclick="event.stopPropagation(); window.location.href=\'' . APP_URL . 'senaEquipoNew/' . $rows['id_equipo'] . '/\'">
                                    <i class="fas fa-file fa-fw"></i>
                                </td>
                            </tr>';
                            }else{
                            $tabla .= '
                            </tr>';
                            }
                    }
            
                $tabla .= '</tbody></table></div>';
                echo $tabla;
            } else {
                echo '<p class="notification is-warning">No hay equipos registrados.</p>';
            }
        }

        public function registrarMarcaControlador(){
            $marca_descripcion = $this->limpiarCadena($_POST['marca_descripcion']);
            $check_marca = $this->ejecutarConsulta("SELECT * FROM marca WHERE marca_descripcion = '$marca_descripcion'");
            if ($check_marca->rowCount() > 0) {
                $alerta=[
                    "tipo"=>"limpiar",
                    "titulo"=>"Error",
                    "texto"=>"La marca '$marca_descripcion' ya esta registrada",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
			    exit();
            }
            $datos_marca = [
                [
                    "campo_nombre"=>"marca_descripcion",
                    "campo_marcador"=>":Marca",
                    "campo_valor"=>$marca_descripcion
                ]
            ];
    
            $registrar_marca = $this->guardarDatos("marca", $datos_marca);
            if ($registrar_marca->rowCount()==1) {
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Marca registrada con exito",
                    "texto"=>"La marca '$marca_descripcion' se registro con exito",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo registrar la marca, por favor intente nuevamente",
                    "icono"=>"error"
                ];
            }
            //retornamos el json 
            return json_encode($alerta);
        }

        public function registrarModeloControlador(){
            $id_marca = $this->limpiarCadena($_POST['id_marca']);
            $modelo_descripcion = $this->limpiarCadena($_POST['modelo_descripcion']);
            $check_modelo = $this->ejecutarConsulta("SELECT * FROM modelo WHERE modelo_descripcion = '$modelo_descripcion'");
            if ($check_modelo->rowCount() > 0) {
                $alerta=[
                    "tipo"=>"limpiar",
                    "titulo"=>"Error",
                    "texto"=>"El modelo '$modelo_descripcion' ya esta registrada",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
			    exit();
            }
            $datos_modelo = [
                [
                    "campo_nombre"=>"modelo_descripcion",
                    "campo_marcador"=>":Modelo",
                    "campo_valor"=>$modelo_descripcion
                ],
                [
                    "campo_nombre"=>"id_marca",
                    "campo_marcador"=>":Marca",
                    "campo_valor"=>$id_marca
                ]
            ];
    
            $registrar_modelo = $this->guardarDatos("modelo", $datos_modelo);
            if ($registrar_modelo->rowCount()==1) {
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Modelo registrado con exito",
                    "texto"=>"El modelo '$modelo_descripcion' se registro con exito",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo registrar el modelo, por favor intente nuevamente",
                    "icono"=>"error"
                ];
            }
            //retornamos el json 
            return json_encode($alerta);
        }

        public function listarMarcasModelosControlador(){
            $datos = $this->ejecutarConsulta("SELECT marca.id_marca, marca.marca_descripcion, modelo.id_modelo, modelo.modelo_descripcion FROM marca LEFT JOIN modelo ON marca.id_marca = modelo.id_marca ORDER BY marca.marca_descripcion, modelo.modelo_descripcion");

            // Agrupar los modelos por marca
            $marcasModelos = [];
            foreach ($datos as $fila) {
                $idMarca = $fila['id_marca'];
                $marcaDescripcion = $fila['marca_descripcion'];
                $modeloDescripcion = $fila['modelo_descripcion'];

                if (!isset($marcasModelos[$idMarca])) {
                    $marcasModelos[$idMarca] = [
                        'marca' => $marcaDescripcion,
                        'modelos' => []
                    ];
                }
                if (!empty($modeloDescripcion)) {
                    $marcasModelos[$idMarca]['modelos'][] = $modeloDescripcion;
                }
            }

            // Generar la tabla HTML
            echo '<table class="table is-striped is-hoverable is-fullwidth"';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Marca</th>';
            echo '<th>Modelos</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($marcasModelos as $marca) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($marca['marca']) . '</td>';
                echo '<td>';
                if (!empty($marca['modelos'])) {
                    echo implode(", ", array_map('htmlspecialchars', $marca['modelos']));
                } else {
                    echo '<span class="has-text-grey">Sin modelos asociados</span>';
                }
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            
        }

    }
?>