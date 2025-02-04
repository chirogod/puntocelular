<?php

	namespace app\controllers;
	use app\models\mainModel;

	class saleController extends mainModel{

		/*---------- Controlador buscar codigo de producto ----------*/
		public function buscarCodigoVentaControlador(){

			/*== Recuperando codigo de busqueda ==*/
			$articulo=$this->limpiarCadena($_POST['buscar_codigo']);

			/*== Comprobando que no este vacio el campo ==*/
			if($articulo==""){
				return '
				<article class="message is-warning mt-4 mb-4">
					<div class="message-header">
						<p>¡Ocurrio un error inesperado!</p>
					</div>
					<div class="message-body has-text-centered">
						<i class="fas fa-exclamation-triangle fa-2x"></i><br>
						Debes de introducir el Nombre, Marca o Modelo del articulo
					</div>
				</article>';
				exit();
			}

			/*== Seleccionando productos en la DB ==*/
			$datos_articulos=$this->ejecutarConsulta("SELECT * FROM articulo WHERE (articulo_descripcion LIKE '%$articulo%' OR articulo_marca LIKE '%$articulo%' OR articulo_modelo LIKE '%$articulo%') AND id_sucursal = '$_SESSION[id_sucursal]' ORDER BY articulo_descripcion ASC");

			if($datos_articulos->rowCount()>=1){

				$datos_articulos = $datos_articulos->fetchAll();

				$tabla='<div class="table-container mb-6"><table class="table is-striped is-narrow is-hoverable is-fullwidth"><tbody>';
				$tabla.='
							<div class="table-container">
							<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
								<thead>
									<tr>
										<th class="has-text-centered">Codigo</th>
										<th class="has-text-centered">Articulo</th>
										<th class="has-text-centered">P. lista</th>
										<th class="has-text-centered">P. efectivo</th>
										<th class="has-text-centered">Agregar</th>
									</tr>
								</thead>
								<tbody>
						';
				foreach($datos_articulos as $rows){
					$tabla.='
					<tr class="has-text-left">
						<td class="has-text-centered">'.$rows['articulo_codigo'].'</td>
						<td class="has-text-centered">'.$rows['articulo_descripcion'].'</td>
						<td class="has-text-centered">'.$rows['articulo_precio_venta'] * 1.4.'</td>
						<td class="has-text-centered">'.$rows['articulo_precio_venta'] .'</td>
						<td class="has-text-centered">
							<button type="button" class="button is-link is-rounded is-small" onclick="agregar_codigo(\''.$rows['articulo_codigo'].'\')"><i class="fas fa-plus-circle"></i></button>
						</td>
					</tr>
					';
				}

				$tabla.='</tbody></table></div>';
				return $tabla;
			}else{
				return '<article class="message is-warning mt-4 mb-4">
					<div class="message-header">
						<p>¡Ocurrio un error inesperado!</p>
					</div>
					<div class="message-body has-text-centered">
						<i class="fas fa-exclamation-triangle fa-2x"></i><br>
						No hemos encontrado ningún articulo en el sistema que coincida con <strong>“'.$articulo.'”
					</div>
				</article>';

				exit();
			}
		}


        /*---------- Controlador agregar producto a venta ----------*/
        public function agregarProductoCarritoControlador(){

            /*== Recuperando codigo del producto ==*/
            $codigo = $this->limpiarCadena($_POST['articulo_codigo']);

            if($codigo==""){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Debes de introducir el código del producto",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            /*== Verificando integridad de los datos ==*/
            if($this->verificarDatos("[a-zA-Z0-9- ]{1,70}",$codigo)){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El código no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            /*== Comprobando producto en la DB ==*/
            $check_articulo=$this->ejecutarConsulta("SELECT * FROM articulo WHERE articulo_codigo = '$codigo'");
            if($check_articulo->rowCount()<=0){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el articulo con codigo: '$codigo'",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }else{
                $campos=$check_articulo->fetch();
            }

            /*== Codigo de producto ==*/
            $codigo=$campos['articulo_codigo'];

            if(empty($_SESSION['datos_producto_venta'][$codigo])){

                $detalle_cantidad=1;

                $stock_total=$campos['articulo_stock']-$detalle_cantidad;


                $detalle_total=$detalle_cantidad*$campos['articulo_precio_venta'];
                $detalle_total=number_format($detalle_total,MONEDA_DECIMALES,'.','');

                $_SESSION['datos_producto_venta'][$codigo]=[
                    "id_articulo"=>$campos['id_articulo'],
					"articulo_codigo"=>$campos['articulo_codigo'],
					"articulo_stock"=>$stock_total,
					"articulo_stock_old"=>$campos['articulo_stock'],
                    "venta_detalle_precio_compra_producto"=>$campos['articulo_precio_compra'],
                    "venta_detalle_precio_venta_producto"=>$campos['articulo_precio_venta'],
                    "venta_detalle_cantidad_producto"=>1,
                    "venta_detalle_total"=>$detalle_total,
                    "venta_detalle_descripcion_producto"=>$campos['articulo_descripcion']
                ];

                $_SESSION['alerta_producto_agregado']="Se agrego <strong>".$campos['articulo_descripcion']."</strong> a la venta";
            }else{
                $detalle_cantidad=($_SESSION['datos_producto_venta'][$codigo]['venta_detalle_cantidad_producto'])+1;

                $stock_total=$campos['articulo_stock']-$detalle_cantidad;

                $detalle_total=$detalle_cantidad*$campos['articulo_precio_venta'];
                $detalle_total=number_format($detalle_total,MONEDA_DECIMALES,'.','');

                $_SESSION['datos_producto_venta'][$codigo]=[
                    "id_articulo"=>$campos['id_articulo'],
					"articulo_codigo"=>$campos['articulo_codigo'],
					"articulo_stock"=>$stock_total,
					"articulo_stock_total_old"=>$campos['articulo_stock'],
                    "venta_detalle_precio_compra_producto"=>$campos['articulo_precio_compra'],
                    "venta_detalle_precio_venta_producto"=>$campos['articulo_precio_venta'],
                    "venta_detalle_cantidad_producto"=>$detalle_cantidad,
                    "venta_detalle_total"=>$detalle_total,
                    "venta_detalle_descripcion_producto"=>$campos['articulo_descripcion']
                ];

                $_SESSION['alerta_producto_agregado']="Se agrego +1 <strong>".$campos['articulo_descripcion']."</strong> a la venta. Total en carrito: <strong>$detalle_cantidad</strong>";
            }

            $alerta=[
				"tipo"=>"redireccionar",
				"url"=>APP_URL."saleNew/"
			];

			return json_encode($alerta);
        }

		private function calcularOperadorFinanciacion($financiacion) {
			switch ($financiacion) {
				case "Efectivo":
					return 1;
				case "3cuotas":
					return 1.4;
				case "6cuotas":
					return 1.4;
				case "9cuotas":
					return 1.5;
				case "12cuotas":
					return 1.6;
				default:
					return null;
			}
		}

		public function financiarProducto(){
			
            $codigo = $this->limpiarCadena($_POST['articulo_codigo']);
			$financiacion = $this->limpiarCadena($_POST['financiacion']);

			// Validar el código del producto
			if($codigo == ""){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Ocurrio un error inesperado!",
					"texto"=>"No se introdujo el codigo del producto",
					"icono"=>"error"
				];
				return json_encode($alerta);
				exit();
			}

			// Comprobar si el producto existe en la base de datos
			$check_articulo = $this->ejecutarConsulta("SELECT * FROM articulo WHERE articulo_codigo = '$codigo'");
			if($check_articulo->rowCount() <= 0){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Ocurrio un error inesperado!",
					"texto"=>"No se encontro el articulo de codigo '$codigo'",
					"icono"=>"error"
				];
				return json_encode($alerta);
				exit();
			} else {
				$campos = $check_articulo->fetch();
			}

			// Obtener el precio de financiamiento basado en la opción seleccionada
			$operacion = $this->calcularOperadorFinanciacion($financiacion);
			if ($operacion === null) {
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Ocurrio un error inesperado!",
					"texto"=>"Forma de financiamiento no valida",
					"icono"=>"error"
				];
				return json_encode($alerta);
				exit();
			}

			// Asegúrate de que la sesión de productos de venta esté inicializada
			if (!isset($_SESSION['financiacion'])) {
				$_SESSION['financiacion'] = [];
			}

			// Almacena los detalles de financiamiento para el producto
			$_SESSION['financiacion'][$codigo] = [
				"id_articulo" => $campos['id_articulo'],
				"articulo_codigo" => $campos['articulo_codigo'],
				"venta_detalle_financiacion_producto" => $financiacion,
				"venta_detalle_total" => ($_SESSION['datos_producto_venta'][$codigo]['venta_detalle_total'] * $operacion),
				"venta_detalle_descripcion_producto" => $campos['articulo_descripcion']
			];
            
            $alerta=[
				"tipo"=>"redireccionar",
				"url"=>APP_URL."saleNew/"
			];

			return json_encode($alerta);
		}

        /*---------- Controlador remover producto de venta ----------*/
        public function removerProductoCarritoControlador(){

            /*== Recuperando codigo del producto ==*/
            $codigo=$this->limpiarCadena($_POST['articulo_codigo']);

            unset($_SESSION['datos_producto_venta'][$codigo]);
			unset($_SESSION['financiacion'][$codigo]);
            if(empty($_SESSION['datos_producto_venta'][$codigo])){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Articulo removido!",
					"texto"=>"El articulo se ha removido de la venta",
					"icono"=>"success"
				];
				
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido remover el articulo, por favor intente nuevamente",
					"icono"=>"error"
				];
            }
            return json_encode($alerta);
        }


        /*---------- Controlador actualizar producto de venta ----------*/
        public function actualizarProductoCarritoControlador(){

            /*== Recuperando codigo & cantidad del producto ==*/
            $codigo=$this->limpiarCadena($_POST['articulo_codigo']);
            $cantidad=$this->limpiarCadena($_POST['articulo_cantidad']);
           
			/*== comprobando campos vacios ==*/
            if($codigo=="" || $cantidad==""){
            	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No podemos actualizar la cantidad de productos debido a que faltan algunos parámetros de configuración",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            /*== comprobando cantidad de productos ==*/
            if($cantidad<=0){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Debes de introducir una cantidad mayor a 0",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            /*== Comprobando producto en la DB ==*/
            $check_producto=$this->ejecutarConsulta("SELECT * FROM articulo WHERE articulo_codigo='$codigo'");
            if($check_producto->rowCount()<=0){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el articulo con código: '$codigo'",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }else{
                $campos=$check_producto->fetch();
            }

            /*== comprobando producto en carrito ==*/
            if(!empty($_SESSION['datos_producto_venta'][$codigo])){

                if($_SESSION['datos_producto_venta'][$codigo]["venta_detalle_cantidad_producto"]==$cantidad){
                    $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"No has modificado la cantidad de productos",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
                }

                if($cantidad>$_SESSION['datos_producto_venta'][$codigo]["venta_detalle_cantidad_producto"]){
                    $diferencia_productos="agrego +".($cantidad-$_SESSION['datos_producto_venta'][$codigo]["venta_detalle_cantidad_producto"]);
                }else{
                    $diferencia_productos="quito -".($_SESSION['datos_producto_venta'][$codigo]["venta_detalle_cantidad_producto"]-$cantidad);
                }


                $detalle_cantidad=$cantidad;

                $stock_total=$campos['articulo_stock']-$detalle_cantidad;

                if($stock_total<0){
                    $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Lo sentimos, no hay existencias suficientes del producto seleccionado. Existencias disponibles: ".($stock_total+$detalle_cantidad)."",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
                }

				
                $precio_venta = $_SESSION['datos_producto_venta'][$codigo]['venta_detalle_precio_venta_producto'];
                
                $detalle_total=$detalle_cantidad*$precio_venta;
                $detalle_total=number_format($detalle_total,MONEDA_DECIMALES,'.','');

                $_SESSION['datos_producto_venta'][$codigo]=[
                    "id_articulo"=>$campos['articulo_id'],
					"articulo_codigo"=>$campos['articulo_codigo'],
					"articulo_stock_total"=>$stock_total,
					"articulo_stock_total_old"=>$campos['articulo_stock'],
                    "venta_detalle_precio_compra_producto"=>$campos['articulo_precio_compra'],
                    "venta_detalle_precio_venta_producto"=>$precio_venta,
                    "venta_detalle_cantidad_producto"=>$detalle_cantidad,
                    "venta_detalle_total"=>$detalle_total,
                    "venta_detalle_descripcion_producto"=>$campos['articulo_descripcion']
                ];

                $_SESSION['alerta_producto_agregado']="Se $diferencia_productos <strong>".$campos['articulo_descripcion']."</strong> a la venta. Total en carrito <strong>$detalle_cantidad</strong>";

                $alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL."saleNew/"
				];

				return json_encode($alerta);
            }else{
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el producto que desea actualizar en el carrito",
					"icono"=>"error"
				];
				return json_encode($alerta);
            }
        }


        /*---------- Controlador buscar cliente ----------*/
        public function buscarClienteVentaControlador(){

            /*== Recuperando termino de busqueda ==*/
			$cliente=$this->limpiarCadena($_POST['buscar_cliente']);

			/*== Comprobando que no este vacio el campo ==*/
			if($cliente==""){
				return '
				<article class="message is-warning mt-4 mb-4">
					 <div class="message-header">
					    <p>¡Ocurrio un error inesperado!</p>
					 </div>
				    <div class="message-body has-text-centered">
				    	<i class="fas fa-exclamation-triangle fa-2x"></i><br>
						Debes de introducir el Numero de documento, Nombre, Apellido o Teléfono del cliente
				    </div>
				</article>';
				exit();
            }

            /*== Seleccionando clientes en la DB ==*/
            $datos_cliente=$this->ejecutarConsulta("SELECT * FROM cliente WHERE (id_cliente!='1') AND (cliente_documento LIKE '%$cliente%' OR cliente_nombre_completo LIKE '%$cliente%' OR cliente_telefono_1 LIKE '%$cliente%' OR cliente_telefono_2  LIKE '%$cliente%' OR cliente_email LIKE '%$cliente%' OR cliente_codigo LIKE '%$cliente%' ) ORDER BY cliente_nombre_completo ASC");

            if($datos_cliente->rowCount()>=1){

				$datos_cliente=$datos_cliente->fetchAll();

				$tabla='<div class="table-container mb-6"><table class="table is-striped is-narrow is-hoverable is-fullwidth"><tbody>';

				foreach($datos_cliente as $rows){
					$tabla.='
					<tr>
                        <td class="has-text-left" ><i class="fas fa-male fa-fw"></i> &nbsp; '.$rows['cliente_nombre_completo'].' ('.$rows['cliente_tipo_doc'].': '.$rows['cliente_documento'].')</td>
                        <td class="has-text-centered" >
                            <button type="button" class="button is-link is-rounded is-small" onclick="agregar_cliente('.$rows['id_cliente'].')"><i class="fas fa-user-plus"></i></button>
                        </td>
                    </tr>
                    ';
				}

				$tabla.='</tbody></table></div>';
				return $tabla;
			}else{
				return '
				<article class="message is-warning mt-4 mb-4">
					 <div class="message-header">
					    <p>¡Ocurrio un error inesperado!</p>
					 </div>
				    <div class="message-body has-text-centered">
				    	<i class="fas fa-exclamation-triangle fa-2x"></i><br>
						No hemos encontrado ningún cliente en el sistema que coincida con <strong>“'.$cliente.'”</strong>
				    </div>
				</article>';
				exit();
			}
        }


        /*---------- Controlador agregar cliente ----------*/
        public function agregarClienteVentaControlador(){

            /*== Recuperando id del cliente ==*/
			$id=$this->limpiarCadena($_POST['id_cliente']);

			/*== Comprobando cliente en la DB ==*/
			$check_cliente=$this->ejecutarConsulta("SELECT * FROM cliente WHERE id_cliente='$id'");
			if($check_cliente->rowCount()<=0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido agregar el cliente debido a un error, por favor intente nuevamente",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			}else{
				$campos=$check_cliente->fetch();
            }

			if($_SESSION['datos_cliente_venta']['id_cliente']==1){
                $_SESSION['datos_cliente_venta']=[
                    "id_cliente"=>$campos['id_cliente'],
                    "cliente_tipo_doc"=>$campos['cliente_tipo_doc'],
                    "cliente_documento"=>$campos['cliente_documento'],
                    "cliente_nombre_completo"=>$campos['cliente_nombre_completo']
                ];

				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL."saleNew/"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido agregar el cliente debido a un error, por favor intente nuevamente",
					"icono"=>"error"
				];
            }
            return json_encode($alerta);
        }


        /*---------- Controlador remover cliente ----------*/
        public function removerClienteVentaControlador(){

			unset($_SESSION['datos_cliente_venta']);

			if(empty($_SESSION['datos_cliente_venta'])){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Cliente removido!",
					"texto"=>"Los datos del cliente se han quitado de la venta",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido remover el cliente, por favor intente nuevamente",
					"icono"=>"error"
				];	
			}
			return json_encode($alerta);
        }


        /*---------- Controlador registrar venta ----------*/
        public function registrarVentaControlador(){
			
			$venta_observaciones = $this->limpiarCadena($_POST['venta_observaciones']);
			$venta_vendedor = $this->limpiarCadena($_POST['venta_vendedor']);

            $caja=$_SESSION['caja'];

            if($_SESSION['venta_importe']<=0 || (!isset($_SESSION['datos_producto_venta']) && count($_SESSION['datos_producto_venta'])<=0)){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No ha agregado productos a esta venta",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }
			if((!isset($_SESSION['financiacion']) && count($_SESSION['financiacion'])<=0)){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has financiado los articulos de esta venta",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            if(!isset($_SESSION['datos_cliente_venta'])){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No ha seleccionado ningún cliente para realizar esta venta",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }


            /*== Comprobando cliente en la DB ==*/
			$check_cliente=$this->ejecutarConsulta("SELECT id_cliente FROM cliente WHERE id_cliente='".$_SESSION['datos_cliente_venta']['id_cliente']."'");
			if($check_cliente->rowCount()<=0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el cliente registrado en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }


            /*== Comprobando caja en la DB ==*/
            $check_caja=$this->ejecutarConsulta("SELECT * FROM caja WHERE id_caja='$caja' ");
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

			if($venta_vendedor == ""){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se ha ingresado el vendedor!",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			}


            /*== Formateando variables ==*/
            $venta_importe=number_format($_SESSION['venta_importe'],MONEDA_DECIMALES,'.','');

            $venta_fecha=date("Y-m-d");
            $venta_hora=date("h:i a");

            $venta_importe_final=$venta_importe;
            $venta_importe_final=number_format($venta_importe_final,MONEDA_DECIMALES,'.','');



            /*== Calculando total en caja ==*/
            $movimiento_cantidad=$venta_importe;
            $movimiento_cantidad=number_format($movimiento_cantidad,MONEDA_DECIMALES,'.','');

            $total_caja=$datos_caja['caja_monto']+$movimiento_cantidad;
            $total_caja=number_format($total_caja,MONEDA_DECIMALES,'.','');

			


            /*== Actualizando productos ==*/
            $errores_productos=0;
			foreach($_SESSION['datos_producto_venta'] as $productos){

                /*== Obteniendo datos del producto ==*/
                $check_producto=$this->ejecutarConsulta("SELECT * FROM articulo WHERE id_articulo='".$productos['id_articulo']."' AND articulo_codigo='".$productos['articulo_codigo']."'");
                if($check_producto->rowCount()<1){
                    $errores_productos=1;
                    break;
                }else{
                    $datos_producto=$check_producto->fetch();
                }

                /*== Respaldando datos de BD para poder restaurar en caso de errores ==*/
                $_SESSION['datos_producto_venta'][$productos['articulo_codigo']]['articulo_stock']=$datos_producto['articulo_stock']-$_SESSION['datos_producto_venta'][$productos['articulo_codigo']]['venta_detalle_cantidad_producto'];

                $_SESSION['datos_producto_venta'][$productos['articulo_codigo']]['articulo_stock_total_old']=$datos_producto['articulo_stock'];

                /*== Preparando datos para enviarlos al modelo ==*/
                $datos_producto_up=[
                    [
						"campo_nombre"=>"articulo_stock",
						"campo_marcador"=>":Stock",
						"campo_valor"=>$_SESSION['datos_producto_venta'][$productos['articulo_codigo']]['articulo_stock']
					]
                ];

                $condicion=[
                    "condicion_campo"=>"id_articulo",
                    "condicion_marcador"=>":ID",
                    "condicion_valor"=>$productos['id_articulo']
                ];

                /*== Actualizando producto ==*/
                if(!$this->actualizarDatos("articulo",$datos_producto_up,$condicion)){
                    $errores_productos=1;
                    break;
                }
            }

            /*== Reestableciendo DB debido a errores ==*/
            if($errores_productos==1){

                foreach($_SESSION['datos_producto_venta'] as $producto){

                    $datos_producto_rs=[
                        [
							"campo_nombre"=>"articulo_stock",
							"campo_marcador"=>":Stock",
							"campo_valor"=>$producto['articulo_stock_total_old']
						]
                    ];

                    $condicion=[
                        "condicion_campo"=>"id_articulo",
                        "condicion_marcador"=>":ID",
                        "condicion_valor"=>$producto['id_articulo']
                    ];

                    $this->actualizarDatos("articulo",$datos_producto_rs,$condicion);
                }

                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar los productos en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            /*== generando codigo de venta ==*/
            $correlativo=$this->ejecutarConsulta("SELECT id_venta FROM venta");
			$correlativo=($correlativo->rowCount())+1;
            $codigo_venta=$this->generarCodigoAleatorio(10,$correlativo);

            /*== Preparando datos para enviarlos al modelo ==*/
			$datos_venta_reg=[
				[
					"campo_nombre"=>"venta_codigo",
					"campo_marcador"=>":Codigo",
					"campo_valor"=>$codigo_venta
				],
				[
					"campo_nombre"=>"venta_fecha",
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$venta_fecha
				],
				[
					"campo_nombre"=>"venta_hora",
					"campo_marcador"=>":Hora",
					"campo_valor"=>$venta_hora
				],
				[
					"campo_nombre"=>"venta_importe",
					"campo_marcador"=>":Total",
					"campo_valor"=>$venta_importe
				],
				[
					"campo_nombre"=>"venta_observaciones",
					"campo_marcador"=>":Observaciones",
					"campo_valor"=>$venta_observaciones
				],
				[
					"campo_nombre"=>"venta_vendedor",
					"campo_marcador"=>":Vendedor",
					"campo_valor"=>$venta_vendedor
				],
				[
					"campo_nombre"=>"id_sucursal",
					"campo_marcador"=>":Sucursal",
					"campo_valor"=>$_SESSION['id_sucursal']
				],
				[
					"campo_nombre"=>"id_usuario",
					"campo_marcador"=>":Usuario",
					"campo_valor"=>$_SESSION['id_usuario']
				],
				[
					"campo_nombre"=>"id_cliente",
					"campo_marcador"=>":Cliente",
					"campo_valor"=>$_SESSION['datos_cliente_venta']['id_cliente']
				],
				[
					"campo_nombre"=>"id_caja",
					"campo_marcador"=>":Caja",
					"campo_valor"=>$caja
				]
            ];

            /*== Agregando venta ==*/
            $agregar_venta=$this->guardarDatos("venta",$datos_venta_reg);

            if($agregar_venta->rowCount()!=1){
                foreach($_SESSION['datos_producto_venta'] as $producto){

                    $datos_producto_rs=[
                        [
							"campo_nombre"=>"articulo_stock",
							"campo_marcador"=>":Stock",
							"campo_valor"=>$producto['articulo_stock_total_old']
						]
                    ];

                    $condicion=[
                        "condicion_campo"=>"id_articulo",
                        "condicion_marcador"=>":ID",
                        "condicion_valor"=>$producto['id_articulo']
                    ];

                    $this->actualizarDatos("articulo",$datos_producto_rs,$condicion);
                }

                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido registrar la venta, por favor intente nuevamente. Código de error: 001",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            /*== Agregando detalles de la venta ==*/
            $errores_venta_detalle=0;
            foreach($_SESSION['datos_producto_venta'] as $venta_detalle){

                /*== Preparando datos para enviarlos al modelo ==*/
                $datos_venta_detalle_reg=[
                	[
						"campo_nombre"=>"venta_detalle_cantidad_producto",
						"campo_marcador"=>":Cantidad",
						"campo_valor"=>$venta_detalle['venta_detalle_cantidad_producto']
					],
					[
						"campo_nombre"=>"venta_detalle_precio_compra_producto",
						"campo_marcador"=>":PrecioCompra",
						"campo_valor"=>$venta_detalle['venta_detalle_precio_compra_producto']
					],
					[
						"campo_nombre"=>"venta_detalle_precio_venta_producto",
						"campo_marcador"=>":PrecioVenta",
						"campo_valor"=>$venta_detalle['venta_detalle_precio_venta_producto']
					],
					[
						"campo_nombre"=>"venta_detalle_financiacion_producto",
						"campo_marcador"=>":Financiacion",
						"campo_valor"=>$_SESSION['financiacion'][$venta_detalle['articulo_codigo']]['venta_detalle_financiacion_producto']
					],
					[
						"campo_nombre"=>"venta_detalle_total",
						"campo_marcador"=>":Total",
						"campo_valor"=>$_SESSION['financiacion'][$venta_detalle['articulo_codigo']]['venta_detalle_total']
					],
					[
						"campo_nombre"=>"venta_detalle_descripcion_producto",
						"campo_marcador"=>":Descripcion",
						"campo_valor"=>$venta_detalle['venta_detalle_descripcion_producto']
					],
					[
						"campo_nombre"=>"venta_codigo",
						"campo_marcador"=>":VentaCodigo",
						"campo_valor"=>$codigo_venta
					],
					[
						"campo_nombre"=>"id_articulo",
						"campo_marcador"=>":Producto",
						"campo_valor"=>$venta_detalle['id_articulo']
					]
                ];

                $agregar_detalle_venta=$this->guardarDatos("venta_detalle",$datos_venta_detalle_reg);

                if($agregar_detalle_venta->rowCount()!=1){
                    $errores_venta_detalle=1;
                    break;
                }
            }

            /*== Reestableciendo DB debido a errores ==*/
            if($errores_venta_detalle==1){

                $this->eliminarRegistro("venta_detalle","venta_codigo",$codigo_venta);
                $this->eliminarRegistro("venta","venta_codigo",$codigo_venta);

                foreach($_SESSION['datos_producto_venta'] as $producto){

                    $datos_producto_rs=[
                        [
							"campo_nombre"=>"articulo_stock",
							"campo_marcador"=>":Stock",
							"campo_valor"=>$producto['articulo_stock_total_old']
						]
                    ];

                    $condicion=[
                        "condicion_campo"=>"id_articulo",
                        "condicion_marcador"=>":ID",
                        "condicion_valor"=>$producto['id_articulo']
                    ];

                    $this->actualizarDatos("articulo",$datos_producto_rs,$condicion);
                }

                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido registrar la venta, por favor intente nuevamente. Código de error: 002",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }
			
            /*== Vaciando variables de sesion ==*/
            unset($_SESSION['venta_total']);
            unset($_SESSION['datos_cliente_venta']);
            unset($_SESSION['datos_producto_venta']);
			unset($_SESSION['financiacion']);

            $_SESSION['venta_codigo_factura']=$codigo_venta;

            $alerta=[
				"tipo"=>"redireccionar",
				"url"=>APP_URL."saleNew/"
			];
			return json_encode($alerta);
	        exit();
        }

		/*---------- Controlador eliminar venta ---------- */
		public function eliminarVentaControlador() {
			$venta_codigo = $this->limpiarCadena($_POST['venta_codigo']);

			// Obtener los datos de la venta
			$datos_venta = $this->ejecutarConsulta("SELECT * FROM venta WHERE venta_codigo = '$venta_codigo'");
			$datos_venta_detalle = $this->ejecutarConsulta("SELECT * FROM venta_detalle WHERE venta_codigo = '$venta_codigo'");
			$datos_pago_venta = $this->ejecutarConsulta("SELECT * FROM pago_venta WHERE venta_codigo = '$venta_codigo'");

			$datos_venta = $datos_venta->fetch();
			$datos_venta_detalle = $datos_venta_detalle->fetchAll();
			$datos_pago_venta = $datos_pago_venta->fetchAll();

			// Verificar si la venta existe
			if (!$datos_venta) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "No hemos encontrado la venta en el sistema",
					"icono" => "error"
				];
				return json_encode($alerta);
				exit();
			}

			// Calcular el total pagado en efectivo y en otras formas
			$pagado_efectivo = 0;
			$pagado_otra_forma = 0;
			foreach ($datos_pago_venta as $pago) {
				if ($pago['venta_pago_forma'] == "Efectivo") {
					$pagado_efectivo += $pago['venta_pago_importe'];
				} else {
					$pagado_otra_forma += $pago['venta_pago_importe'];
				}
			}

			// Obtener los datos de la caja
			$id_caja = $datos_venta['id_caja'];
			$datos_caja = $this->ejecutarConsulta("SELECT * FROM caja WHERE id_caja = '$id_caja'");
			$datos_caja = $datos_caja->fetch();

			// Verificar si la caja existe
			if (!$datos_caja) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "No hemos encontrado la caja en el sistema",
					"icono" => "error"
				];
				return json_encode($alerta);
				exit();
			}

			// Restar el importe de la venta de la caja
			$total_caja = $datos_caja['caja_monto'] - $datos_venta['venta_importe'];
			$total_caja = number_format($total_caja, MONEDA_DECIMALES, '.', '');
			$datos_caja_up = [
				[
					"campo_nombre" => "caja_monto",
					"campo_marcador" => ":Monto",
					"campo_valor" => $total_caja
				]
			];
			$condicion_caja = [
				"condicion_campo" => "id_caja",
				"condicion_marcador" => ":ID",
				"condicion_valor" => $id_caja
			];
			$this->actualizarDatos("caja", $datos_caja_up, $condicion_caja);

			// Restar el importe en efectivo de la caja física
			$id_sucursal = $datos_venta['id_sucursal'];
			$caja_fisica = $this->ejecutarConsulta("SELECT * FROM caja WHERE caja_codigo LIKE '%Efectivo%' AND id_sucursal = '$id_sucursal'");
			$caja_fisica = $caja_fisica->fetch();

			// Verificar si la caja física existe
			if ($caja_fisica) {
				$total_caja_fisica = $caja_fisica['caja_monto'] - $pagado_efectivo;
				$total_caja_fisica = number_format($total_caja_fisica, MONEDA_DECIMALES, '.', '');
				$datos_caja_fisica_up = [
					[
						"campo_nombre" => "caja_monto",
						"campo_marcador" => ":Monto",
						"campo_valor" => $total_caja_fisica
					]
				];
				$condicion_caja_fisica = [
					"condicion_campo" => "id_caja",
					"condicion_marcador" => ":ID",
					"condicion_valor" => $caja_fisica['id_caja']
				];
				$this->actualizarDatos("caja", $datos_caja_fisica_up, $condicion_caja_fisica);
			}

			// Eliminar los detalles de la venta
			foreach ($datos_venta_detalle as $detalle) {
				$this->eliminarRegistro("venta_detalle", "id_venta_detalle", $detalle['id_venta_detalle']);
			}

			// Eliminar los pagos de la venta
			foreach ($datos_pago_venta as $pago) {
				$this->eliminarRegistro("pago_venta", "id_pago_venta", $pago['id_pago_venta']);
			}

			// Eliminar la venta
			$eliminar_venta = $this->eliminarRegistro("venta", "venta_codigo", $venta_codigo);

			// Actualizar el stock de los productos
			foreach ($datos_venta_detalle as $detalle) {
				$producto = $this->ejecutarConsulta("SELECT * FROM articulo WHERE id_articulo = '".$detalle['id_articulo']."'");
				$producto = $producto->fetch();

				$nuevo_stock = $producto['articulo_stock'] + $detalle['venta_detalle_cantidad_producto'];

				$datos_producto_up = [
					[
						"campo_nombre" => "articulo_stock",
						"campo_marcador" => ":Stock",
						"campo_valor" => $nuevo_stock
					]
				];

				$condicion = [
					"condicion_campo" => "id_articulo",
					"condicion_marcador" => ":ID",
					"condicion_valor" => $detalle['id_articulo']
				];

				$this->actualizarDatos("articulo", $datos_producto_up, $condicion);
			}

			if($eliminar_venta->rowCount() == 1){
				$alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Pago registrado",
                    "texto"=>"El pago se registro con exito",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo registrar el pago, por favor intente nuevamente",
                    "icono"=>"error"
                ];
            }

			return json_encode($alerta);
		}



        /*----------  Controlador listar venta  ----------*/
		public function listarVentaControlador($pagina,$registros,$url,$busqueda){

			$pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);

			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			$campos_tablas = "venta.id_venta, venta.venta_codigo, venta.venta_fecha, venta.venta_hora, venta.venta_importe, venta.id_usuario, venta.id_cliente, venta.id_caja, usuario.id_usuario, usuario.usuario_nombre_completo, cliente.id_cliente, cliente.cliente_nombre_completo";

			if(isset($busqueda) && $busqueda!=""){

				$consulta_datos="SELECT $campos_tablas 
								FROM venta 
								INNER JOIN cliente ON venta.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON venta.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON venta.id_caja=caja.id_caja 
								WHERE 
									venta.id_venta LIKE '%$busqueda%' 
									OR venta.venta_codigo LIKE '%$busqueda%' 
									OR cliente.cliente_nombre_completo LIKE '%$busqueda%' 
									OR usuario.usuario_nombre_completo LIKE '%$busqueda%' 
									OR caja.caja_nombre LIKE '%$busqueda%' 
									AND venta.id_sucursal = '$_SESSION[id_sucursal]'
								ORDER BY venta.id_venta DESC LIMIT $inicio,$registros";
			
				$consulta_total="SELECT COUNT(id_venta) 
								FROM venta 
								INNER JOIN cliente ON venta.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON venta.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON venta.id_caja=caja.id_caja 
								WHERE 
									venta.id_venta LIKE '%$busqueda%' 
									OR venta.venta_codigo LIKE '%$busqueda%' 
									OR cliente.cliente_nombre_completo LIKE '%$busqueda%' 
									OR usuario.usuario_nombre_completo LIKE '%$busqueda%' 
									OR caja.caja_nombre LIKE '%$busqueda%'
									AND venta.id_sucursal = '$_SESSION[id_sucursal]'";
			
			}else{
			
				$consulta_datos="SELECT $campos_tablas 
								FROM venta 
								INNER JOIN cliente ON venta.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON venta.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON venta.id_caja=caja.id_caja 
								WHERE venta.id_sucursal = '$_SESSION[id_sucursal]'
								ORDER BY venta.id_venta DESC LIMIT $inicio,$registros";
			
				$consulta_total="SELECT COUNT(id_venta) 
								FROM venta 
								INNER JOIN cliente ON venta.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON venta.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON venta.id_caja=caja.id_caja
								WHERE venta.id_sucursal = '$_SESSION[id_sucursal]'";
			}

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
		                    <th class="has-text-centered">NRO.</th>
		                    <th class="has-text-centered">Codigo</th>
		                    <th class="has-text-centered">Fecha</th>
		                    <th class="has-text-centered">Cliente</th>
		                    <th class="has-text-centered">Vendedor</th>
		                    <th class="has-text-centered">Importe</th>
		                </tr>
		            </thead>
		            <tbody>
		    ';

		    if($total>=1 && $pagina<=$numeroPaginas){
				$contador=$inicio+1;
				$pag_inicio=$inicio+1;
				foreach($datos as $rows){
					$tabla.='
						<tr class="has-text-centered" style="cursor: pointer;" onclick="window.location.href=\'' . APP_URL . 'saleDetail/' . $rows['venta_codigo'] . '/\'">
							<td>'.$rows['id_venta'].'</td>
							<td>'.$rows['venta_codigo'].'</td>
							<td>'.date("d-m-Y", strtotime($rows['venta_fecha'])).' '.$rows['venta_hora'].'</td>
							<td>'.$rows['cliente_nombre_completo'].'</td>
							<td>'.$rows['usuario_nombre_completo'].'</td>
							<td>'.MONEDA_SIMBOLO.number_format($rows['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE.'</td>
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
				$tabla.='<p class="has-text-right">Mostrando ventas <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

				$tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
			}

			return $tabla;
		}


		


		/*----------  Controlador actualizar precio producto  ----------*/
		public function actualizarPrecioProducto(){

			/*== Recuperando datos del producto ==*/
			$codigo=$this->limpiarCadena($_POST['articulo_codigo']);
			$precio=$this->limpiarCadena($_POST['articulo_precio']);

			if($codigo==""){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se ha encontrado el código del producto",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            /*== Verificando integridad de los datos ==*/
            if($this->verificarDatos("[a-zA-Z0-9- ]{1,70}",$codigo)){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El código no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            /*== comprobando producto en carrito ==*/
            if(!empty($_SESSION['datos_producto_venta'][$codigo])){

            	$precio=number_format($precio,MONEDA_DECIMALES,'.','');

            	if($precio<$_SESSION['datos_producto_venta'][$codigo]['venta_detalle_precio_compra_producto']){
            		$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"El precio de venta no puede ser menor al precio de compra (".MONEDA_SIMBOLO.$_SESSION['datos_producto_venta'][$codigo]['venta_detalle_precio_compra']." ".MONEDA_NOMBRE.")",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
            	}

                $detalle_total=$_SESSION['datos_producto_venta'][$codigo]['venta_detalle_cantidad_producto']*$precio;
                $detalle_total=number_format($detalle_total,MONEDA_DECIMALES,'.','');

                $_SESSION['datos_producto_venta'][$codigo]['venta_detalle_precio_venta_producto']=$precio;
                $_SESSION['datos_producto_venta'][$codigo]['venta_detalle_total']=$detalle_total;

                $alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Precio actualizado!",
					"texto"=>"El precio del producto se actualizo correctamente para realizar la venta",
					"icono"=>"success"
				];

            }else{
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el producto que desea actualizar en el carrito",
					"icono"=>"error"
				];
            }
            return json_encode($alerta);
		}

		/*----------  Controlador listar venta  ----------*/
		public function listarVentasClienteControlador($pagina,$registros,$url,$busqueda, $id_cliente){

			$pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);

			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			$campos_tablas = "venta.id_venta, venta.venta_codigo, venta.venta_fecha, venta.venta_hora, venta.venta_importe, venta.id_usuario, venta.id_cliente, venta.id_caja, venta.id_sucursal, sucursal.id_sucursal, sucursal.sucursal_descripcion, usuario.id_usuario, usuario.usuario_nombre_completo, cliente.id_cliente, cliente.cliente_nombre_completo";

			$consulta_datos = "SELECT venta.id_venta, venta.venta_codigo, venta.venta_fecha, venta.venta_hora, venta.venta_importe, venta.id_usuario, venta.id_cliente, venta.id_caja, venta.id_sucursal, sucursal.id_sucursal, sucursal.sucursal_descripcion, usuario.id_usuario, usuario.usuario_nombre_completo, cliente.id_cliente, cliente.cliente_nombre_completo
								FROM venta 
								INNER JOIN cliente ON venta.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON venta.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON venta.id_caja=caja.id_caja 
								INNER JOIN sucursal ON venta.id_sucursal=sucursal.id_sucursal 
								AND venta.id_cliente = $id_cliente
								ORDER BY venta.id_venta DESC";

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			$tabla.='
		        <div class="table-container">
		        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
		            <thead>
		                <tr>
		                    <th class="has-text-centered">NRO.</th>
		                    <th class="has-text-centered">Codigo</th>
		                    <th class="has-text-centered">Fecha</th>
		                    <th class="has-text-centered">Vendedor</th>
		                    <th class="has-text-centered">Importe</th>
							<th class="has-text-centered">Sucursal</th>
		                </tr>
		            </thead>
		            <tbody>
		    ';
			$total = 1;
		    if($total>=1 ){
				$contador=$inicio+1;
				$pag_inicio=$inicio+1;
				foreach($datos as $rows){
					$tabla.='
						<tr class="has-text-centered" style="cursor: pointer;" onclick="window.location.href=\'' . APP_URL . 'saleDetail/' . $rows['venta_codigo'] . '/\'">
							<td>'.$rows['id_venta'].'</td>
							<td>'.$rows['venta_codigo'].'</td>
							<td>'.date("d-m-Y", strtotime($rows['venta_fecha'])).' '.$rows['venta_hora'].'</td>
							<td>'.$rows['usuario_nombre_completo'].'</td>
							<td>'.MONEDA_SIMBOLO.number_format($rows['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE.'</td>
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
			                    <a href="'.$url.'5904/" class="button is-link is-rounded is-small mt-4 mb-4">
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


			return $tabla;
		}

	}