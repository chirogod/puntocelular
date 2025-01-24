<?php

	namespace app\controllers;
	use app\models\mainModel;

	class saleEquipoController extends mainModel{

		private function calcularOperadorFinanciacion($costo, $financiacion) {
			$efectivo_usd = $costo * 1.4;
			$precio = $efectivo_usd * 1.4 *USD_PC;
			$efectivo = $precio * 0.75;
			$sin_int_3 = $precio;
			$sin_int_6 = $precio; 
			$fijas_9 = ($efectivo_usd * 1.5 * USD_PC);
			$fijas_12 = ($efectivo_usd * 1.6 * USD_PC);
			switch ($financiacion) {
				case "Efectivo":
					return $efectivo;
				case "3cuotas":
					return $sin_int_3;
				case "6cuotas":
					return $sin_int_6;
				case "9cuotas":
					return $fijas_9;
				case "12cuotas":
					return $fijas_12;
				default:
					return null;
			}
		}

		public function financiarProducto(){
			
            $id_equipo = $this->limpiarCadena($_POST['id_equipo']);
			$financiacion = $this->limpiarCadena($_POST['financiacion_equipo']);

			// Validar el código del producto
			if($id_equipo == ""){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Ocurrio un error inesperado!",
					"texto"=>"No se introdujo el codigo del producto",
					"icono"=>"error"
				];
				return json_encode($alerta);
				exit();
			}

			// Comprobar si el equipo existe en la base de datos
			$check_equipo = $this->ejecutarConsulta("SELECT * FROM equipo WHERE id_equipo = '$id_equipo'");
			if($check_equipo->rowCount() <= 0){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Ocurrio un error inesperado!",
					"texto"=>"No se encontro el equipo de codigo '$id_equipo'",
					"icono"=>"error"
				];
				return json_encode($alerta);
				exit();
			} else {
				$campos = $check_equipo->fetch();
			}

			// Obtener el precio de financiamiento basado en la opción seleccionada
			$operacion = $this->calcularOperadorFinanciacion($campos['equipo_costo'],$financiacion);
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
			if (!isset($_SESSION['financiacion_equipo'])) {
				$_SESSION['financiacion_equipo'] = [];
			}

			// Almacena los detalles de financiamiento para el producto
			$_SESSION['financiacion_equipo'][$id_equipo] = [
				"id_equipo" => $campos['id_equipo'],
				"venta_equipo_financiacion" => $financiacion,
				"venta_equipo_total" => round($operacion),
			];
            
            $alerta=[
				"tipo"=>"redireccionar",
				"url"=>APP_URL."saleEquipoNew/".$id_equipo
			];

			return json_encode($alerta);
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
			$id_equipo = $this->limpiarCadena($_POST['id_equipo']);

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

			if($_SESSION['datos_cliente_venta_equipo']['id_cliente']==1){
                $_SESSION['datos_cliente_venta_equipo']=[
                    "id_cliente"=>$campos['id_cliente'],
                    "cliente_tipo_doc"=>$campos['cliente_tipo_doc'],
                    "cliente_documento"=>$campos['cliente_documento'],
                    "cliente_nombre_completo"=>$campos['cliente_nombre_completo']
                ];

				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL."saleEquipoNew/".$id_equipo
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

			unset($_SESSION['datos_cliente_venta_equipo']);

			if(empty($_SESSION['datos_cliente_venta_equipo'])){
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
			$id_equipo = $this->limpiarCadena($_POST['id_equipo']);
			/*== Comprobando equipo en la DB ==*/
			$equipo = $this->seleccionarDatos("Normal", "equipo", "id_equipo", $id_equipo);
			$equipo = $equipo->fetch();
			$venta_vendedor = $this->limpiarCadena($_POST['venta_vendedor']);

            $caja=$_SESSION['caja'];

			if((!isset($_SESSION['financiacion_equipo']) && count($_SESSION['financiacion_equipo'])<=0)){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has financiado los articulos de esta venta",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            if(!isset($_SESSION['datos_cliente_venta_equipo'])){
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
			$check_cliente=$this->ejecutarConsulta("SELECT id_cliente FROM cliente WHERE id_cliente='".$_SESSION['datos_cliente_venta_equipo']['id_cliente']."'");
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
            $venta_importe=number_format($_SESSION['venta_equipo_importe'],MONEDA_DECIMALES,'.','');

            $venta_fecha=date("Y-m-d");
            $venta_hora=date("h:i a");

            $venta_importe_final=$venta_importe;
            $venta_importe_final=number_format($venta_importe_final,MONEDA_DECIMALES,'.','');



            /*== Calculando total en caja ==*/
            $movimiento_cantidad=$venta_importe;
            $movimiento_cantidad=number_format($movimiento_cantidad,MONEDA_DECIMALES,'.','');

            $total_caja=$datos_caja['caja_monto']+$movimiento_cantidad;
            $total_caja=number_format($total_caja,MONEDA_DECIMALES,'.','');


            /*== generando codigo de venta ==*/
            $correlativo=$this->ejecutarConsulta("SELECT id_venta_equipo FROM venta_equipo");
			$correlativo=($correlativo->rowCount())+1;
            $codigo_venta=$this->generarCodigoAleatorio(10,$correlativo);

            /*== Preparando datos para enviarlos al modelo ==*/
			$datos_venta_equipo=[
				[
					"campo_nombre"=>"venta_equipo_codigo",
					"campo_marcador"=>":Codigo",
					"campo_valor"=>$codigo_venta
				],
				[
					"campo_nombre"=>"venta_equipo_fecha",
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$venta_fecha
				],
				[
					"campo_nombre"=>"venta_equipo_hora",
					"campo_marcador"=>":Hora",
					"campo_valor"=>$venta_hora
				],
				[
					"campo_nombre"=>"id_equipo",
					"campo_marcador"=>":Equipo",
					"campo_valor"=>$id_equipo
				],
				[
					"campo_nombre"=>"venta_equipo_financiacion",
					"campo_marcador"=>":Financiacion",
					"campo_valor"=>$_SESSION['financiacion_equipo'][$id_equipo]['venta_equipo_financiacion']
				],
				[
					"campo_nombre"=>"venta_equipo_importe",
					"campo_marcador"=>":Total",
					"campo_valor"=>$venta_importe
				],
				[
					"campo_nombre"=>"venta_equipo_vendedor",
					"campo_marcador"=>":Vendedor",
					"campo_valor"=>$venta_vendedor
				],
				[
					"campo_nombre"=>"id_sucursal",
					"campo_marcador"=>":Sucursal",
					"campo_valor"=>$_SESSION['id_sucursal']
				],
				[
					"campo_nombre"=>"id_cliente",
					"campo_marcador"=>":Cliente",
					"campo_valor"=>$_SESSION['datos_cliente_venta_equipo']['id_cliente']
				],
				[
					"campo_nombre"=>"id_caja",
					"campo_marcador"=>":Caja",
					"campo_valor"=>$caja
				]
            ];

            /*== Agregando venta ==*/
            $agregar_venta=$this->guardarDatos("venta_equipo",$datos_venta_equipo);

            if($agregar_venta->rowCount()==1){
				/* PONER EL EQUIPO EN ESTADO DE VENDIDO */
				$vendido = "Vendido";
                $datos_equipo_up=[
                    [
						"campo_nombre"=>"equipo_estado",
						"campo_marcador"=>":Estado",
						"campo_valor"=>$vendido
					]
                ];

                $condicion=[
                    "condicion_campo"=>"id_equipo",
                    "condicion_marcador"=>":ID",
                    "condicion_valor"=>$id_equipo
                ];

                $this->actualizarDatos("equipo",$datos_equipo_up,$condicion);
                /*== Vaciando variables de sesion ==*/
				unset($_SESSION['venta_total']);
				unset($_SESSION['datos_cliente_venta_equipo']);
				unset($_SESSION['financiacion_equipo']);


				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL."saleNew/"
				];
			
            }
			return json_encode($alerta);
	        exit();
        }


		/*----------  Controlador eliminar venta  ----------*/
		public function eliminarVentaControlador(){

			$id=$this->limpiarCadena($_POST['id_venta']);

			# Verificando venta #
		    $datos=$this->ejecutarConsulta("SELECT * FROM venta WHERE id_venta='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado la venta en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    # Verificando detalles de venta #
		    $check_detalle_venta=$this->ejecutarConsulta("SELECT venta_detalle_id FROM venta_detalle WHERE venta_codigo='".$datos['venta_codigo']."'");
		    $check_detalle_venta=$check_detalle_venta->rowCount();

		    if($check_detalle_venta>0){

		        $eliminarVentaDetalle=$this->eliminarRegistro("venta_detalle","venta_codigo",$datos['venta_codigo']);

		        if($eliminarVentaDetalle->rowCount()!=$check_detalle_venta){
		        	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"No hemos podido eliminar la venta del sistema, por favor intente nuevamente",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
		        }

		    }


		    $eliminarVenta=$this->eliminarRegistro("venta","id_venta",$id);

		    if($eliminarVenta->rowCount()==1){

		        $alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Venta eliminada",
					"texto"=>"La venta ha sido eliminada del sistema correctamente",
					"icono"=>"success"
				];

		    }else{
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido eliminar la venta del sistema, por favor intente nuevamente",
					"icono"=>"error"
				];
		    }

		    return json_encode($alerta);
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

			$campos_tablas = "venta_equipo.id_venta, venta_equipo.venta_codigo, venta_equipo.venta_fecha, venta_equipo.venta_hora, venta_equipo.venta_importe, venta_equipo.id_usuario, venta_equipo.id_cliente, venta_equipo.id_caja, usuario.id_usuario, usuario.usuario_nombre_completo, cliente.id_cliente, cliente.cliente_nombre_completo";

			$consulta_datos = "SELECT venta_equipo.id_venta, venta_equipo.venta_codigo, venta_equipo.venta_fecha, venta_equipo.venta_hora, venta_equipo.venta_importe, venta_equipo.id_usuario, venta_equipo.id_cliente, venta_equipo.id_caja, usuario.id_usuario, usuario.usuario_nombre_completo, cliente.id_cliente, cliente.cliente_nombre_completo
								FROM venta 
								INNER JOIN cliente ON venta_equipo.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON venta_equipo.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON venta_equipo.id_caja=caja.id_caja 
								WHERE venta_equipo.id_sucursal = 1
								AND venta_equipo.id_cliente = $id_cliente
								ORDER BY venta_equipo.id_venta_equipo DESC";

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
						<tr class="has-text-centered" style="cursor: pointer;" onclick="window.location.href=\'' . APP_URL . 'saleEquipoDetail/' . $rows['id_equipo'] . '/\'">
							<td>'.$rows['id_venta'].'</td>
							<td>'.$rows['venta_codigo'].'</td>
							<td>'.date("d-m-Y", strtotime($rows['venta_fecha'])).' '.$rows['venta_hora'].'</td>
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

		/*----------  Controlador listar venta  ----------*/
		public function listarVentaEquipoControlador($pagina,$registros,$url,$busqueda){

			$pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);

			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			$campos_tablas = "venta_equipo.id_venta_equipo, venta_equipo.venta_equipo_codigo, venta_equipo.venta_equipo_fecha, venta_equipo.venta_equipo_hora, venta_equipo.venta_equipo_financiacion, venta_equipo.venta_equipo_vendedor, venta_equipo.venta_equipo_importe, venta_equipo.id_cliente, venta_equipo.id_cliente, venta_equipo.id_caja, cliente.id_cliente, cliente.cliente_nombre_completo";

			if(isset($busqueda) && $busqueda!=""){

				$consulta_datos="SELECT $campos_tablas 
								FROM venta_equipo
								INNER JOIN cliente ON venta_equipo.id_cliente=cliente.id_cliente
								INNER JOIN caja ON venta_equipo.id_caja=caja.id_caja 
								WHERE 
									venta_equipo.id_venta_equipo LIKE '%$busqueda%' 
									OR venta_equipo.venta_equipo_codigo LIKE '%$busqueda%' 
									OR cliente.cliente_nombre_completo LIKE '%$busqueda%' 
									OR caja.caja_nombre LIKE '%$busqueda%' 
									OR equipo.equipo_marca LIKE '%$busqueda%' 
									OR equipo.equipo_modelo LIKE '%$busqueda%' 
									AND venta_equipo.id_sucursal = '$_SESSION[id_sucursal]'
								ORDER BY venta_equipo.id_venta_equipo DESC LIMIT $inicio,$registros";
			
				$consulta_total="SELECT COUNT(id_venta_equipo) 
								FROM venta_equipo
								INNER JOIN cliente ON venta_equipo.id_cliente=cliente.id_cliente 
								INNER JOIN caja ON venta_equipo.id_caja=caja.id_caja 
								WHERE 
									venta_equipo.id_venta_equipo LIKE '%$busqueda%' 
									OR venta_equipo.venta_codigo LIKE '%$busqueda%' 
									OR cliente.cliente_nombre_completo LIKE '%$busqueda%'
									OR caja.caja_nombre LIKE '%$busqueda%'
									OR equipo.equipo_marca LIKE '%$busqueda%' 
									OR equipo.equipo_modelo LIKE '%$busqueda%'
									AND venta_equipo.id_sucursal = '$_SESSION[id_sucursal]'";
			
			}else{
			
				$consulta_datos="SELECT $campos_tablas 
								FROM venta_equipo
								INNER JOIN cliente ON venta_equipo.id_cliente=cliente.id_cliente
								INNER JOIN caja ON venta_equipo.id_caja=caja.id_caja 
								WHERE venta_equipo.id_sucursal = '$_SESSION[id_sucursal]'
								ORDER BY venta_equipo.id_venta_equipo DESC LIMIT $inicio,$registros";
			
				$consulta_total="SELECT COUNT(id_venta_equipo) 
								FROM venta_equipo
								INNER JOIN cliente ON venta_equipo.id_cliente=cliente.id_cliente
								INNER JOIN caja ON venta_equipo.id_caja=caja.id_caja
								WHERE venta_equipo.id_sucursal = '$_SESSION[id_sucursal]'";
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
						<tr class="has-text-centered" style="cursor: pointer;" onclick="window.location.href=\'' . APP_URL . 'saleEquipoDetail/' . $rows['id_equipo'] . '/\'">
							<td>'.$rows['id_venta_equipo'].'</td>
							<td>'.$rows['venta_equipo_codigo'].'</td>
							<td>'.date("d-m-Y", strtotime($rows['venta_equipo_fecha'])).' '.$rows['venta_equipo_hora'].'</td>
							<td>'.$rows['cliente_nombre_completo'].'</td>
							<td>'.$rows['venta_equipo_vendedor'].'</td>
							<td>'.MONEDA_SIMBOLO.number_format($rows['venta_equipo_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE.'</td>
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

	}