<?php

	namespace app\controllers;
	use app\models\mainModel;

	class ordenController extends mainModel{

		/*---------- Controlador buscar cliente ----------*/
        public function buscarClienteOrdenControlador(){

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
        public function agregarClienteOrdenControlador(){

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

			if($_SESSION['datos_cliente_orden']['id_cliente']==1){
                $_SESSION['datos_cliente_orden']=[
                    "id_cliente"=>$campos['id_cliente'],
                    "cliente_tipo_doc"=>$campos['cliente_tipo_doc'],
                    "cliente_documento"=>$campos['cliente_documento'],
                    "cliente_nombre_completo"=>$campos['cliente_nombre_completo']
                ];

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Cliente agregado!",
					"texto"=>"El cliente se agregó para realizar una orden",
					"icono"=>"success"
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
        public function removerClienteOrdenControlador(){

			unset($_SESSION['datos_cliente_orden']);

			if(empty($_SESSION['datos_cliente_orden'])){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Cliente removido!",
					"texto"=>"Los datos del cliente se han quitado de la orden",
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


        /*---------- Controlador registrar orden ----------*/
		public function registrarOrdenControlador(){

			//datos
			$orden_codigo;
			$id_cliente = $_SESSION['datos_cliente_orden']['id_cliente'];
			$orden_fecha = date("Y-m-d");
			$orden_hora = date("h:i a");
			$orden_observaciones = $this->limpiarCadena($_POST['orden_observaciones']);
			
			//agregar equipo
			$id_marca = $_POST['id_marca'];
			$id_modelo = $_POST['id_modelo'];
			$orden_serie_equipo = $_POST['orden_serie_equipo'];
			$orden_equipo_ingresa_encendido = $_POST['orden_equipo_ingresa_encendido'];
			$orden_equipo_detalles_fisicos = $_POST['orden_equipo_detalles_fisicos'];
			$orden_equipo_contrasena = $_POST['orden_equipo_contrasena'];
			$orden_falla = $_POST['orden_falla'];

			//detalles
			$orden_accesorios = $_POST['orden_accesorios'];
			$orden_telefonista = $_POST['orden_telefonista'];
			$orden_estado = "Pendiente";
			$orden_tipo = $_POST['orden_tipo'];
			$id_tecnico = $_POST['id_tecnico'];
			$orden_importe_lista = $_POST['orden_importe_lista'];
			$orden_importe_efectivo = $_POST['orden_importe_efectivo'];

			//codigo de orden incrementado en 1 cada vez q se hace una nueva orden
			$correlativo=$this->ejecutarConsulta("SELECT id_orden FROM orden");
			$correlativo=($correlativo->rowCount())+1;
			$orden_codigo=$correlativo;

			$datos_orden = [
				//datos
				[
					"campo_nombre"=>"orden_codigo",
					"campo_marcador"=>":Codigo",
					"campo_valor"=>$orden_codigo
				],
				[
					"campo_nombre"=>"id_cliente",
					"campo_marcador"=>":Cliente",
					"campo_valor"=>$_SESSION['datos_cliente_orden']['id_cliente']
				],
				[
					"campo_nombre"=>"orden_fecha",
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$orden_fecha
				],
				[
					"campo_nombre"=>"orden_hora",
					"campo_marcador"=>":Hora",
					"campo_valor"=>$orden_hora
				],
				[
					"campo_nombre"=>"orden_observaciones",
					"campo_marcador"=>":Observaciones",
					"campo_valor"=>$orden_observaciones
				],

				//agregar equipo
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
					"campo_nombre"=>"orden_serie_equipo",
					"campo_marcador"=>":NSerie",
					"campo_valor"=>$orden_serie_equipo
				],
				[
					"campo_nombre"=>"orden_equipo_ingresa_encendido",
					"campo_marcador"=>":Encendido",
					"campo_valor"=>$orden_equipo_ingresa_encendido
				],
				[
					"campo_nombre"=>"orden_equipo_detalles_fisicos",
					"campo_marcador"=>":Detalles",
					"campo_valor"=>$orden_equipo_detalles_fisicos
				],
				[
					"campo_nombre"=>"orden_equipo_contrasena",
					"campo_marcador"=>":Contrasena",
					"campo_valor"=>$orden_equipo_contrasena
				],
				[
					"campo_nombre"=>"orden_falla",
					"campo_marcador"=>":Falla",
					"campo_valor"=>$orden_falla
				],
				

				//detalles
				[
					"campo_nombre"=>"orden_accesorios",
					"campo_marcador"=>":Accesorios",
					"campo_valor"=>$orden_accesorios
				],
				[
					"campo_nombre"=>"id_tecnico",
					"campo_marcador"=>":Tecnico",
					"campo_valor"=>$id_tecnico
				],
				[
					"campo_nombre"=>"orden_telefonista",
					"campo_marcador"=>":Telefonista",
					"campo_valor"=>$orden_telefonista
				],
				
				[
					"campo_nombre"=>"orden_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$orden_estado
				],
				[
					"campo_nombre"=>"orden_tipo",
					"campo_marcador"=>":Tipo",
					"campo_valor"=>$orden_tipo
				],
				[
					"campo_nombre"=>"orden_importe_lista",
					"campo_marcador"=>":Lista",
					"campo_valor"=>$orden_importe_lista
				],
				[
					"campo_nombre"=>"orden_importe_efectivo",
					"campo_marcador"=>":Efectivo",
					"campo_valor"=>$orden_importe_efectivo
				],

				//demas
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
					"campo_nombre"=>"id_caja",
					"campo_marcador"=>":Caja",
					"campo_valor"=>$_SESSION['caja']
				]
			];

			$registrar_orden = $this->guardarDatos("orden", $datos_orden);

			//vaciando variable de sesion de clientes
			unset($_SESSION['datos_cliente_orden']);

			if ($registrar_orden->rowCount()==1) {
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Orden registrada con exito",
					"texto"=>"La orden se registro con exito",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar la orden, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			//retornamos el json 
			return json_encode($alerta);


			
		}

		/*---------- Controlador actualizar orden -------- */
		public function actualizarOrdenControlador(){

		}

		/*---------- Controlador registrar informe tecnico orden -------- */
		public function registrarInformeTecnicoOrdenControlador(){
			$orden_codigo = $_POST['orden_codigo'];
			$orden_informe_tecnico = $_POST['orden_informe_tecnico'];
			$orden_total_reparacion = $_POST['orden_total_reparacion'];
			
			$datos =[
				[
					"campo_nombre"=>"orden_informe_tecnico",
					"campo_marcador"=>":InformeTecnico",
					"campo_valor"=>$orden_informe_tecnico
				],
				[
					"campo_nombre"=>"orden_total_reparacion",
					"campo_marcador"=>":Total",
					"campo_valor"=>$orden_total_reparacion
				],
			];

			$condicion=[
				"condicion_campo"=>"orden_codigo",
				"condicion_marcador"=>":CODIGO",
				"condicion_valor"=>$orden_codigo
			];
	
			if($this->actualizarDatos("orden",$datos,$condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Orden actualizada",
					"texto"=>"El informe tecnico se registro correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar el informe tecnico, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
	
			return json_encode($alerta);
		}

		/*---------- Controlador aceptar orden -------- */
		public function aceptarOrdenControlador(){

			$orden_codigo = $_POST['orden_codigo'];
			$orden_fecha_aceptada = $_POST['orden_fecha_aceptada'];
			$orden_fecha_prometida = $_POST['orden_fecha_prometida'];
			$orden_estado = "Aceptada";

			$datos =[
				[
					"campo_nombre"=>"orden_fecha_aceptada",
					"campo_marcador"=>":FechaAceptada",
					"campo_valor"=>$orden_fecha_aceptada
				],
				[
					"campo_nombre"=>"orden_fecha_prometida",
					"campo_marcador"=>":FechaPrometida",
					"campo_valor"=>$orden_fecha_prometida
				],
				[
					"campo_nombre"=>"orden_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$orden_estado
				]
			];

			$condicion=[
				"condicion_campo"=>"orden_codigo",
				"condicion_marcador"=>":CODIGO",
				"condicion_valor"=>$orden_codigo
			];
	
			if($this->actualizarDatos("orden",$datos,$condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Orden aceptada",
					"texto"=>"Se acepto la orden",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar la orden, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
	
			return json_encode($alerta);
		}

		/*---------- Controlador aceptar orden -------- */
		public function cambiarEstadoOrdenControlador(){
			$orden_codigo = $_POST['orden_codigo'];
			$orden_estado = $_POST['orden_estado'];

			$datos =[
				[
					"campo_nombre"=>"orden_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$orden_estado
				]
			];

			$condicion=[
				"condicion_campo"=>"orden_codigo",
				"condicion_marcador"=>":CODIGO",
				"condicion_valor"=>$orden_codigo
			];
	
			if($this->actualizarDatos("orden",$datos,$condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Orden actualizada",
					"texto"=>"Se actualizo el estado de la orden",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar el informe tecnico, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
	
			return json_encode($alerta);
		}

		/*---------- Controlador aceptar orden -------- */
		public function entregarOrdenControlador(){

			$orden_codigo = $_POST['orden_codigo'];
			$orden_fecha_entregada = $_POST['orden_fecha_entregada'];
			$orden_fecha_garantia = $_POST['orden_fecha_garantia'];
			$orden_estado = "Entregada";

			$datos =[
				[
					"campo_nombre"=>"orden_fecha_entregada",
					"campo_marcador"=>":FechaEntregada",
					"campo_valor"=>$orden_fecha_entregada
				],
				[
					"campo_nombre"=>"orden_fecha_garantia",
					"campo_marcador"=>":FechaGarantia",
					"campo_valor"=>$orden_fecha_garantia
				],
				[
					"campo_nombre"=>"orden_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$orden_estado
				]
			];

			$condicion=[
				"condicion_campo"=>"orden_codigo",
				"condicion_marcador"=>":CODIGO",
				"condicion_valor"=>$orden_codigo
			];
	
			if($this->actualizarDatos("orden",$datos,$condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Orden entregada",
					"texto"=>"Se entrego la orden",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar la orden, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
	
			return json_encode($alerta);
		}

        /*----------  Controlador listar orden  ----------*/
		public function listarOrdenControlador($pagina, $registros, $url, $busqueda) {
			$pagina = $this->limpiarCadena($pagina);
			$registros = $this->limpiarCadena($registros);
			$url = $this->limpiarCadena($url);
			$url = APP_URL . $url . "/";
			$busqueda = $this->limpiarCadena($busqueda);
			$tabla = "";
		
			// Configuración de la paginación
			$pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
			$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
		
			// Campos a seleccionar de la tabla orden
			$campos_tablas = "orden.id_orden, 
							  orden.orden_codigo, 
							  orden.orden_fecha, 
							  orden.orden_hora, 
							  orden.orden_importe_lista, 
							  orden.id_usuario, 
							  orden.id_cliente, 
							  orden.orden_tipo, 
							  orden.orden_falla, 
							  orden.orden_informe_tecnico, 
							  orden.orden_observaciones, 
							  orden.id_marca, 
							  orden.id_modelo, 
							  orden.orden_accesorios,
							  orden.orden_telefonista, 
							  orden.id_tecnico, 
							  usuario.id_usuario,
							  usuario.usuario_nombre_completo, 
							  cliente.id_cliente, 
							  cliente.cliente_nombre_completo, 
							  marca.id_marca, 
							  marca.marca_descripcion";

			// Consulta con búsqueda
			if (isset($busqueda) && $busqueda != "") {
				$consulta_datos = "SELECT $campos_tablas 
								   FROM orden 
								   INNER JOIN cliente ON orden.id_cliente = cliente.id_cliente 
								   INNER JOIN usuario ON orden.id_usuario = usuario.id_usuario 
								   INNER JOIN marca ON orden.id_marca = marca.id_marca 
								   WHERE 
									   orden.id_orden LIKE '%$busqueda%' 
									   OR orden.orden_codigo LIKE '%$busqueda%' 
									   OR cliente.cliente_nombre_completo LIKE '%$busqueda%' 
									   OR usuario.usuario_nombre_completo LIKE '%$busqueda%' 
									   AND orden.id_sucursal = '$_SESSION[id_sucursal]'
								   ORDER BY orden.id_orden DESC LIMIT $inicio, $registros";
		
				$consulta_total = "SELECT COUNT(orden.id_orden) 
								   FROM orden 
								   INNER JOIN cliente ON orden.id_cliente = cliente.id_cliente 
								   INNER JOIN usuario ON orden.id_usuario = usuario.id_usuario 
								   WHERE 
									   orden.id_orden LIKE '%$busqueda%' 
									   OR orden.orden_codigo LIKE '%$busqueda%' 
									   OR cliente.cliente_nombre_completo LIKE '%$busqueda%'
									   AND orden.id_sucursal = '$_SESSION[id_sucursal]'";
			} else {
				// Consulta sin búsqueda
				$consulta_datos = "SELECT $campos_tablas 
								   FROM orden 
								   INNER JOIN cliente ON orden.id_cliente = cliente.id_cliente 
								   INNER JOIN usuario ON orden.id_usuario = usuario.id_usuario 
								   INNER JOIN marca ON orden.id_marca = marca.id_marca 
								   WHERE orden.id_sucursal = '$_SESSION[id_sucursal]' 
								   ORDER BY orden.id_orden DESC LIMIT $inicio, $registros";
		
				$consulta_total = "SELECT COUNT(orden.id_orden) 
								   FROM orden 
								   INNER JOIN cliente ON orden.id_cliente = cliente.id_cliente 
								   INNER JOIN usuario ON orden.id_usuario = usuario.id_usuario 
								   WHERE orden.id_sucursal = '$_SESSION[id_sucursal]'";
			}
		
			// Ejecución de las consultas
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
		
			$total = $this->ejecutarConsulta($consulta_total);
			$total = (int) $total->fetchColumn();
		
			// Configuración de la paginación
			$numeroPaginas = ceil($total / $registros);
		
			// Generación de la tabla
			$tabla .= '
				<div class="table-container">
					<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
						<thead>
							<tr>
								<th class="has-text-centered">NRO.</th>
								<th class="has-text-centered">Código</th>
								<th class="has-text-centered">Fecha</th>
								<th class="has-text-centered">Cliente</th>
								<th class="has-text-centered">Vendedor</th>
								<th class="has-text-centered">Total</th>
								<th class="has-text-centered">Detalle</th>
							</tr>
						</thead>
						<tbody>
			';
		
			if ($total >= 1 && $pagina <= $numeroPaginas) {
				$contador = $inicio + 1;
				$pag_inicio = $inicio + 1;
				foreach ($datos as $rows) {
					$tabla .= '
						<tr class="has-text-centered">
							<td>' . $rows['id_orden'] . '</td>
							<td>' . $rows['orden_codigo'] . '</td>
							<td>' . date("d-m-Y", strtotime($rows['orden_fecha'])) . ' ' . $rows['orden_hora'] . '</td>
							<td>' . $rows['cliente_nombre_completo'] . '</td>
							<td>' . $rows['orden_telefonista'] . '</td>
							<td>' . MONEDA_SIMBOLO . number_format($rows['orden_importe_lista'], MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR) . ' ' . MONEDA_NOMBRE . '</td>
							<td>
								<a href="' . APP_URL . 'ordenDetail/' . $rows['orden_codigo'] . '/" class="button is-link is-rounded is-small" title="Información de orden Nro. ' . $rows['id_orden'] . '">
									<i class="fas fa-shopping-bag fa-fw"></i>
								</a>
							</td>
						</tr>
					';
					$contador++;
				}
				$pag_final = $contador - 1;
			} else {
				if ($total >= 1) {
					$tabla .= '
						<tr class="has-text-centered">
							<td colspan="7">
								<a href="' . $url . '1/" class="button is-link is-rounded is-small mt-4 mb-4">
									Haga clic acá para recargar el listado
								</a>
							</td>
						</tr>
					';
				} else {
					$tabla .= '
						<tr class="has-text-centered">
							<td colspan="7">
								No hay registros en el sistema
							</td>
						</tr>
					';
				}
			}
		
			$tabla .= '</tbody></table></div>';
		
			// Paginación
			if ($total > 0 && $pagina <= $numeroPaginas) {
				$tabla .= '<p class="has-text-right">Mostrando órdenes <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
				$tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
			}
		
			return $tabla;
		}		
	}

?>