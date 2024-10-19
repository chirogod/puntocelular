<?php
    namespace app\controllers;
    use app\models\mainModel;

    class equipoController extends mainModel{
        public function registrarEquipoControlador(){
            $equipo_descripcion = $this->limpiarCadena($_POST['equipo_descripcion']);
            $equipo_marca = $this->limpiarCadena($_POST['equipo_marca']);
            $equipo_modelo = $this->limpiarCadena($_POST['equipo_modelo']);
            $equipo_stock = $this->limpiarCadena($_POST['equipo_stock']);
            $id_rubro = $this->limpiarCadena($_POST['id_rubro']);
            $id_sucursal = $this->limpiarCadena($_POST['id_sucursal']);
            $equipo_moneda = $this->limpiarCadena($_POST['equipo_moneda']);
            $equipo_precio_compra = $this->limpiarCadena($_POST['equipo_precio_compra']);
            $equipo_precio_venta = $this->limpiarCadena($_POST['equipo_precio_venta']);
            $equipo_garantia = $this->limpiarCadena($_POST['equipo_garantia']);
            
    
            //verificar campos obligatorios
            if($equipo_descripcion == "" || $equipo_stock == "" || $id_rubro == "" || $id_sucursal == "" || $equipo_moneda == "" || $equipo_precio_compra == "" || $equipo_precio_venta == ""){
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
            if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,100}", $equipo_descripcion)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El nombre del equipo no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
            
            $equipo_codigo = $this->limpiarCadena($_POST['equipo_codigo']);
            //verificar codigo si se puso manualmente
            if ($equipo_codigo != "") {
                $check_codigo =$this->ejecutarConsulta("SELECT * FROM equipo WHERE equipo_codigo = '$equipo_codigo'");
                if ($check_codigo->rowCount() > 0) {
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El codigo del equipo ya existe",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }else{
                /*== sino generar aleatoriamente el codigo del producto ==*/
                $correlativo=$this->ejecutarConsulta("SELECT id_equipo FROM equipo");
                $correlativo=($correlativo->rowCount())+1;
                $equipo_codigo=$this->generarCodigoAleatorio(7,$correlativo);
    
                // verificar que el codigo no exista ya
                $check_codigo =$this->ejecutarConsulta("SELECT * FROM equipo WHERE equipo_codigo = '$equipo_codigo'");
                if ($check_codigo->rowCount() > 0) {
                    // si ya existe generar otro
                    $equipo_codigo=$this->generarCodigoAleatorio(7,$correlativo);
                }
            }
            
    
            
            if($this->verificarDatos("^[0-9]+$", $equipo_stock)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El stock del equipo no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
    
            if ($equipo_garantia != "") {
                if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,100}", $equipo_garantia)){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"La garantia del equipo no cumple con el formato solicitado",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }
    
            if($this->verificarDatos("[0-9.]{1,25}", $equipo_precio_compra)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El precio de compra del equipo no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
    
            if($this->verificarDatos("[0-9.]{1,25}", $equipo_precio_venta)){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El precio de venta del equipo no cumple con el formato solicitado",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
    
            if ($equipo_marca != "") {
                if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{1,30}", $equipo_marca)){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"La marca del equipo no cumple con el formato solicitado",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }
    
            if ($equipo_modelo != "") {
                if($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]{1,30}", $equipo_modelo)){
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El modelo del equipo no cumple con el formato solicitado",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }
    
            $datos_equipo = [
                [
                    "campo_nombre"=>"equipo_codigo",
                    "campo_marcador"=>":Codigo",
                    "campo_valor"=>$equipo_codigo
                ],
                [
                    "campo_nombre"=>"equipo_descripcion",
                    "campo_marcador"=>":Descripcion",
                    "campo_valor"=>$equipo_descripcion
                ],
                [
                    "campo_nombre"=>"equipo_stock",
                    "campo_marcador"=>":Stock",
                    "campo_valor"=>$equipo_stock
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
                    "campo_nombre"=>"equipo_garantia",
                    "campo_marcador"=>":Garantia",
                    "campo_valor"=>$equipo_garantia
                ],
                [
                    "campo_nombre"=>"equipo_moneda",
                    "campo_marcador"=>":Moneda",
                    "campo_valor"=>$equipo_moneda
                ],
                [
                    "campo_nombre"=>"equipo_precio_compra",
                    "campo_marcador"=>":PrecioCompra",
                    "campo_valor"=>$equipo_precio_compra
                ],
                [
                    "campo_nombre"=>"equipo_precio_venta",
                    "campo_marcador"=>":PrecioVenta",
                    "campo_valor"=>$equipo_precio_venta
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
                ]
            ];
    
            $registrar_equipo = $this->guardarDatos("equipo", $datos_equipo);
            if ($registrar_equipo->rowCount()==1) {
                $alerta=[
                    "tipo"=>"limpiar",
                    "titulo"=>"Equipo registrado con exito",
                    "texto"=>"El equipo " .$equipo_descripcion. " se registro con exito",
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

        public function listarEquipoControlador(){

        }

        public function actualizarEquipoControlador(){
            
        }
    }





?>