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
            if($equipo_modulo == 'android_prev' || $equipo_modulo == 'apple_reac_prev' || $equipo_modulo == 'apple_nuevo_prev'){
                $equipo_estado = "Preventa";
            }
            
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
                case 'iphone':
                    $orderBy = " AND equipo_modulo = 'iphone'";
                    break;
                case 'android_reac':
                    $orderBy = " AND equipo_modulo = 'android_reac'";
                    break;
                case 'apple_nuevo_prev':
                    $orderBy = " AND equipo_modulo = 'apple_nuevo_prev'";
                    break;
                case 'apple_reac_prev':
                    $orderBy = " AND equipo_modulo = 'apple_reac_prev'";
                    break;
                case 'android_prev':
                    $orderBy = " AND equipo_modulo = 'android_prev'";
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
                    
                        $update = "";
                        if($_SESSION['usuario_rol'] == "Administrador"){
                            $update = '"onclick="window.location.href=\'' . APP_URL . 'equipoUpdate/' . $rows['id_equipo'] . '/\'"';
                        }
                        $tabla .= '
                            <tr class="' . $clase_estado . '" style="cursor: pointer; '. $update .'">
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

                            if($rows['equipo_estado'] == "Disponible" ||  $rows['equipo_estado'] == "Preventa" && ($rows['equipo_modulo'] != 'iphone' || $rows['equipo_modulo'] == 'android_nuevo' || $rows['equipo_modulo'] == 'android_reac')){
                                $tabla .= '

                                <td class="has-text-centered" style="border: 1px solid black;" onclick="event.stopPropagation(); window.location.href=\'' . APP_URL . 'senaEquipoNew/' . $rows['id_equipo'] . '/\'">
                                    <i class="fas fa-file fa-fw"></i>
                                </td>
                            </tr>';
                            }elseif($rows['equipo_estado'] == "Disponible"){
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

        public function registrarPedidoEquipoControlador(){
            $pedido_equipo_modulo = $this->limpiarCadena($_POST['pedido_equipo_modulo']);

            $id_marca = $this->limpiarCadena($_POST['id_marca']);
            $check_marca = $this->ejecutarConsulta("SELECT * FROM marca WHERE id_marca = '$id_marca'");
            if ($check_marca->rowCount() > 0) {
                // si ya existe generar otro
                $marca=$check_marca->fetch();
                $pedido_equipo_marca = $marca['marca_descripcion'];
            }
            
            $id_modelo = $this->limpiarCadena($_POST['id_modelo']);
            $check_modelo = $this->ejecutarConsulta("SELECT * FROM modelo WHERE id_modelo = '$id_modelo'");
            if ($check_modelo->rowCount() > 0) {
                // si ya existe generar otro
                $modelo=$check_modelo->fetch();
                $pedido_equipo_modelo = $modelo['modelo_descripcion'];
            }

            $pedido_equipo_almacenamiento = $this->limpiarCadena($_POST['pedido_equipo_almacenamiento']);
            $pedido_equipo_color = $this->limpiarCadena($_POST['pedido_equipo_color']);

            if($pedido_equipo_modulo == "iphone"){
                $pedido_equipo_ram = "-";
                
            }else{
                $pedido_equipo_ram = $this->limpiarCadena($_POST['pedido_equipo_ram']);
            }

            if($pedido_equipo_modulo == "iphone" || $pedido_equipo_modulo == "android_reac"){
                $pedido_equipo_bateria = $this->limpiarCadena($_POST['pedido_equipo_bateria']);
            }else{
                $pedido_equipo_bateria = "-";
            }

            $pedido_equipo_estado = 'espera';

            $pedido_equipo_hora = date("H:i:s");
            $pedido_equipo_fecha = date("Y-m-d");
            $pedido_equipo_responsable = $_POST['pedido_equipo_responsable'];
            
            $id_sucursal = $_SESSION['id_sucursal'];
    
            $datos_equipo = [
                [
                    "campo_nombre"=>"pedido_equipo_estado",
                    "campo_marcador"=>":Estado",
                    "campo_valor"=>$pedido_equipo_estado
                ],
                [
                    "campo_nombre"=>"pedido_equipo_marca",
                    "campo_marcador"=>":Marca",
                    "campo_valor"=>$pedido_equipo_marca
                ],
                [
                    "campo_nombre"=>"pedido_equipo_modelo",
                    "campo_marcador"=>":Modelo",
                    "campo_valor"=>$pedido_equipo_modelo
                ],
                [
                    "campo_nombre"=>"pedido_equipo_almacenamiento",
                    "campo_marcador"=>":Almacenamiento",
                    "campo_valor"=>$pedido_equipo_almacenamiento
                ],
                [
                    "campo_nombre"=>"pedido_equipo_ram",
                    "campo_marcador"=>":Ram",
                    "campo_valor"=>$pedido_equipo_ram
                ],
                [
                    "campo_nombre"=>"pedido_equipo_bateria",
                    "campo_marcador"=>":Bateria",
                    "campo_valor"=>$pedido_equipo_bateria
                ],
                [
                    "campo_nombre"=>"pedido_equipo_color",
                    "campo_marcador"=>":Color",
                    "campo_valor"=>$pedido_equipo_color
                ],
                [
                    "campo_nombre"=>"id_sucursal",
                    "campo_marcador"=>":Sucursal",
                    "campo_valor"=>$id_sucursal
                ],
                [
                    "campo_nombre"=>"pedido_equipo_modulo",
                    "campo_marcador"=>":Modulo",
                    "campo_valor"=>$pedido_equipo_modulo
                ],
                [
                    "campo_nombre"=>"pedido_equipo_fecha",
                    "campo_marcador"=>":Fecha",
                    "campo_valor"=>$pedido_equipo_fecha
                ],
                [
                    "campo_nombre"=>"pedido_equipo_hora",
                    "campo_marcador"=>":Hora",
                    "campo_valor"=>$pedido_equipo_hora
                ],
                [
                    "campo_nombre"=>"pedido_equipo_responsable",
                    "campo_marcador"=>":Responsable",
                    "campo_valor"=>$pedido_equipo_responsable
                ]
            ];
    
            $registrar_equipo = $this->guardarDatos("pedido_equipo", $datos_equipo);
            if ($registrar_equipo->rowCount()==1) {
                $alerta=[
                    "tipo"=>"limpiar",
                    "titulo"=>"Pedido de equipo registrado",
                    "texto"=>"El pedido se registro con exito",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo registrar el pedido, por favor intente nuevamente",
                    "icono"=>"error"
                ];
            }
            //retornamos el json 
            return json_encode($alerta);
        }

        public function listarPedidosEquiposControlador(){
            $datos = $this->ejecutarConsulta("SELECT 
                pedido_equipo.id_pedido_equipo, 
                pedido_equipo.pedido_equipo_fecha, 
                pedido_equipo.pedido_equipo_hora,
                pedido_equipo.pedido_equipo_marca,
                pedido_equipo.pedido_equipo_modelo, 
                pedido_equipo.pedido_equipo_almacenamiento,
                pedido_equipo.pedido_equipo_ram,
                pedido_equipo.pedido_equipo_color,
                pedido_equipo.pedido_equipo_estado,
                pedido_equipo.pedido_equipo_bateria,
                pedido_equipo.id_sucursal,
                pedido_equipo.pedido_equipo_responsable,
                pedido_equipo.pedido_equipo_modulo
                FROM pedido_equipo
                INNER JOIN sucursal ON pedido_equipo.id_sucursal = sucursal.id_sucursal
                WHERE pedido_equipo.pedido_equipo_estado != 'eliminado'
                AND pedido_equipo.id_sucursal = '".$_SESSION['id_sucursal']."'
                ORDER BY pedido_equipo.id_pedido_equipo DESC"
            );

            echo '<table class="table is-striped is-hoverable is-fullwidth">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Pedidos de equipos</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
        
            $modulos = []; // Agrupar pedidos por módulo
            foreach ($datos as $dato) {
                $modulo = $dato['pedido_equipo_modulo'];
                $modulos[$modulo][] = $dato;
            }
        
            foreach ($modulos as $modulo => $pedidos) {
                echo '<tr>';
                echo '<td colspan="2">';
                echo '<details>';
                echo '<summary class="is-clickable">' . htmlspecialchars($modulo) . '</summary>';
        
                echo '<table class="table is-striped is-hoverable is-fullwidth mt-3">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Marca</th>';
                echo '<th>Marca</th>';
                echo '<th>Fecha</th>';
                echo '<th>Responsable</th>';
                echo '<th>Ingreso</th>';
                echo '<th>Eliminar</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
        
                foreach ($pedidos as $pedido) {
                    $pedido_estado = ($pedido['pedido_equipo_estado'] === "ingreso") ? "tachado" : "";
                    echo '<tr class="'. $pedido_estado .'">';
                    echo '<td>' . htmlspecialchars($pedido['pedido_equipo_marca']) . '</td>';
                    echo '<td>' . htmlspecialchars($pedido['pedido_equipo_modelo']) . '</td>';
                    echo '<td>' . htmlspecialchars($pedido['pedido_equipo_fecha']) . ' - ' . htmlspecialchars($pedido['pedido_equipo_hora']) . '</td>';
                    echo '<td>' . htmlspecialchars($pedido['pedido_equipo_responsable']) . '</td>';
        
                    // Formulario para ingreso
                    echo '<td>
                            <form class="FormularioAjax" action="'.APP_URL.'app/ajax/equipoAjax.php" method="POST" autocomplete="off">
                                <input type="hidden" name="modulo_equipo" value="ingreso_pedido">
                                <input type="hidden" name="id_pedido_equipo" value="'. htmlspecialchars($pedido['id_pedido_equipo']) .'">
                                <button type="submit" class="button is-success is-rounded is-small">Ingreso</button>
                            </form>
                          </td>';
        
                    // Formulario para eliminar
                    echo '<td>
                            <form class="FormularioAjax" action="'.APP_URL.'app/ajax/equipoAjax.php" method="POST" autocomplete="off">
                                <input type="hidden" name="modulo_equipo" value="eliminar_pedido">
                                <input type="hidden" name="id_pedido_equipo" value="'. htmlspecialchars($pedido['id_pedido_equipo']) .'">
                                <button type="submit" class="button is-danger is-rounded is-small">
                                    <i class="fas fa-trash-restore"></i>
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
            $id_pedido_equipo = $_POST['id_pedido_equipo'];
            $datos=[
                [
                    "campo_nombre"=>"pedido_equipo_estado",
                    "campo_marcador"=>":Estado",
                    "campo_valor"=>"ingreso"
                ]
            ];
            $condicion=[
				"condicion_campo"=>"id_pedido_equipo",
				"condicion_marcador"=>":Id",
				"condicion_valor"=>$id_pedido_equipo
			];
            $actualizar = $this->actualizarDatos('pedido_equipo', $datos, $condicion);
            if($actualizar){
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Operacion exitosa",
                    "texto"=>"El equipo se marco como ingresado.",
                    "icono"=>"success"
                ];
                
            }else{
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo marcar el equipo como ingresado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
            return json_encode($alerta);
        }

        //se le elimina de la lista al pasar al estado 'eliminado'. No se elimina de la base de datos
        public function eliminarPedidoControlador(){
            $id_pedido_equipo = $_POST['id_pedido_equipo'];
            $datos=[
                [
                    "campo_nombre"=>"pedido_equipo_estado",
                    "campo_marcador"=>":Estado",
                    "campo_valor"=>"eliminado"
                ]
            ];
            $condicion=[
				"condicion_campo"=>"id_pedido_equipo",
				"condicion_marcador"=>":Id",
				"condicion_valor"=>$id_pedido_equipo
			];
            $actualizar = $this->actualizarDatos('pedido_equipo', $datos, $condicion);
            if($actualizar){
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Operacion exitosa",
                    "texto"=>"El equipo se elimino de la lista.",
                    "icono"=>"success"
                ];
                
            }else{
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo marcar el equipo como eliminado",
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
				$where = "WHERE pedido_equipo.id_sucursal = '$id_sucursal'";
			}
		
			// Si viene el estado, aplicamos el filtro
			if (isset($_POST['estado']) && !empty($_POST['estado'])) {
				$estado = $this->limpiarCadena($_POST['estado']);
				$where_estado = "AND pedido_equipo.pedido_equipo_estado = '$estado'";
			}
		
			// Si vienen las fechas, aplicamos el filtro
			if (isset($_POST['fecha_inicio']) && !empty($_POST['fecha_inicio']) && isset($_POST['fecha_fin']) && !empty($_POST['fecha_fin'])) {
				$fecha_inicio = $this->limpiarCadena($_POST['fecha_inicio']);
				$fecha_fin = $this->limpiarCadena($_POST['fecha_fin']);
				$where_fecha = "AND pedido_equipo.pedido_equipo_fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
			}

            $consulta = "SELECT 
                pedido_equipo.id_pedido_equipo, 
                pedido_equipo.pedido_equipo_fecha, 
                pedido_equipo.pedido_equipo_hora, 
                pedido_equipo.pedido_equipo_modulo, 
                pedido_equipo.pedido_equipo_modelo, 
                pedido_equipo.pedido_equipo_marca,
                pedido_equipo.pedido_equipo_color,
                pedido_equipo.pedido_equipo_ram,
                pedido_equipo.pedido_equipo_almacenamiento, 
                pedido_equipo.id_sucursal,
                sucursal.id_sucursal,
                sucursal.sucursal_descripcion,
                pedido_equipo.pedido_equipo_responsable,
                pedido_equipo.pedido_equipo_estado
            FROM pedido_equipo 
            INNER JOIN sucursal ON pedido_equipo.id_sucursal = sucursal.id_sucursal
            $where $where_estado $where_fecha 
            ORDER BY pedido_equipo.pedido_equipo_fecha DESC";
        
            $datos = $this->ejecutarConsulta($consulta);
        
            echo '<table class="table is-striped is-hoverable is-fullwidth">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Pedidos de equipos</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
        
            $modulos = []; // Agrupar pedidos por módulo
            foreach ($datos as $dato) {
                $modulo = $dato['pedido_equipo_modulo'];
                $modulos[$modulo][] = $dato;
            }
        
            foreach ($modulos as $modulo => $pedidos) {
                echo '<tr>';
                echo '<td colspan="2">';
                echo '<details>';
                echo '<summary class="is-clickable">' . htmlspecialchars($modulo) . '</summary>';
        
                echo '<table class="table is-striped is-hoverable is-fullwidth mt-3">';
                echo '<thead>
                        <tr>
                            <th>Equipo</th>
                            <th>Fecha</th>
                            <th>Responsable</th>
                            <th>Estado</th>
                            <th>Sucursal</th>
                        </tr>
                    </thead>';
                echo '<tbody>';
        
                foreach ($pedidos as $pedido) {
                    $pedido_estado = "";
    
                    if ($pedido['pedido_equipo_estado'] === "ingreso") {
                        $pedido_estado = "tachado"; // Clase para tachar el texto
                    } elseif ($pedido['pedido_equipo_estado'] === "eliminado") {
                        $pedido_estado = "eliminado"; // Clase para tachar y fondo rojo tenue
                    }
                    echo '<tr class="'. $pedido_estado .'">';
                    echo '<td>' . htmlspecialchars($pedido['pedido_equipo_marca']) . ' - ' . htmlspecialchars($pedido['pedido_equipo_modelo']) . ' - ' . htmlspecialchars($pedido['pedido_equipo_color']) . ' - ' . htmlspecialchars($pedido['pedido_equipo_ram']) . ' - ' . htmlspecialchars($pedido['pedido_equipo_almacenamiento']) . '</td>';
                    echo '<td>' . htmlspecialchars($pedido['pedido_equipo_fecha']) . ' - ' . htmlspecialchars($pedido['pedido_equipo_hora']) . '</td>';
                    echo '<td>' . htmlspecialchars($pedido['pedido_equipo_responsable']) . '</td>';
                    echo '<td>' . htmlspecialchars($pedido['pedido_equipo_estado']) . '</td>';
                    echo '<td>' . htmlspecialchars($pedido['sucursal_descripcion']) . '</td>';
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
?>