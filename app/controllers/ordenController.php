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

			/*== Comprobando que no este vacio el campo ==*/
			if(strlen($cliente)<=3){
				return '
				<article class="message is-warning mt-4 mb-4">
						<div class="message-header">
						<p>¡Ocurrio un error inesperado!</p>
						</div>
					<div class="message-body has-text-centered">
						<i class="fas fa-exclamation-triangle fa-2x"></i><br>
						Debes de introducir al menos tres caracteres
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

			$id_cliente = $_SESSION['datos_cliente_orden']['id_cliente'];
			$orden_fecha = date("Y-m-d");
			$orden_hora = date("h:i a");
			$orden_observaciones = $this->limpiarCadena($_POST['orden_observaciones']);
			$id_marca = "";
			$id_modelo = "";
			$orden_equipo_marca = "";
			$orden_otro_modelo = "";

			$orden_equipo_email = $_POST['orden_equipo_email'];
			$orden_equipo_pass = $_POST['orden_equipo_pass'];

			if(isset($_POST['orden_descripcion'])){
				$orden_descripcion = $this->limpiarCadena($_POST['orden_descripcion']);
				if($orden_descripcion === "otro" && !empty($_POST['orden_descripcion_otro'])){
					$orden_descripcion_otro = $this->limpiarCadena($_POST['orden_descripcion_otro']);
					// Aquí podrías usar $orden_descripcion_otro como valor final
					$orden_descripcion = $orden_descripcion_otro;
				}
			}

			//agregar equipo
			// Check if an existing brand is selected or a new brand is entered
			if (isset($_POST['id_marca']) && $_POST['id_marca'] !== "") {
				$id_marca = $this->limpiarCadena($_POST['id_marca']);
				$datos_marca = $this->seleccionarDatos("Unico", "marca", "id_marca", $id_marca);
				$datos_marca = $datos_marca->fetch();
				$orden_equipo_marca = $datos_marca['marca_descripcion'];
			} else if (isset($_POST['orden_otra_marca']) && $_POST['orden_otra_marca'] !== "") {
				$orden_equipo_marca = $this->limpiarCadena($_POST['orden_otra_marca']);
			}
		
			if ($orden_equipo_marca == "") {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "Debe indicar la marca del equipo!",
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
				$orden_equipo_modelo = $datos_modelo['modelo_descripcion'];
			} else if (isset($_POST['orden_otro_modelo']) && $_POST['orden_otro_modelo'] !== "") {
				$orden_equipo_modelo = $this->limpiarCadena($_POST['orden_otro_modelo']);
			}
		
			if ($orden_equipo_modelo == "") {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "Debe indicar el modelo del equipo!",
					"icono" => "error"
				];
				return json_encode($alerta);
				exit();
			}

			$orden_equipo_ingresa_encendido = $_POST['orden_equipo_ingresa_encendido'];
			if(!isset($orden_equipo_ingresa_encendido)){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Debe indicar si el equipo ingresa encendido o no!",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			}

			$orden_equipo_detalles_fisicos = $_POST['orden_equipo_detalles_fisicos'];

			$orden_equipo_contrasena = $_POST['orden_equipo_contrasena'];
			if($orden_equipo_contrasena == ""){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Debe indicar la contrasena del equipo!(aunque sea un punto)",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			}

			
			$orden_equipo_patron = $_POST['orden_equipo_patron'];

			$orden_falla = $_POST['orden_falla'];
			if($orden_falla == ""){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Debe indicar la falla del equipo!",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			}

			//detalles
			$orden_accesorios = $_POST['orden_accesorios'];
			$orden_telefonista = $_POST['orden_telefonista'];
			if($orden_telefonista == ""){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Debe indicar el telefonista!",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			}

			$orden_ubicacion_fisica = $_POST['orden_ubicacion_fisica'];
			if($orden_ubicacion_fisica == ""){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Debe indicar la ubicacion!",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			}

			$orden_estado = "Pendiente";
			$orden_tipo = $_POST['orden_tipo'];
			$fecha_prometida = $_POST['orden_fecha_prometida'];
			                                                                                                                                                                                                                                                                                        
			$orden_total_lista = $_POST['orden_total_lista'];
			$orden_total_efectivo = $_POST['orden_total_efectivo'];

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
					"campo_nombre"=>"orden_descripcion",
					"campo_marcador"=>":Descripcion",
					"campo_valor"=>$orden_descripcion
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
					"campo_nombre"=>"orden_equipo_marca",
					"campo_marcador"=>":Marca",
					"campo_valor"=>$orden_equipo_marca
				],
				[
					"campo_nombre"=>"orden_equipo_modelo",
					"campo_marcador"=>":Modelo",
					"campo_valor"=>$orden_equipo_modelo
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
					"campo_nombre"=>"orden_equipo_patron",
					"campo_marcador"=>":Patron",
					"campo_valor"=>$orden_equipo_patron
				],
				[
					"campo_nombre"=>"orden_falla",
					"campo_marcador"=>":Falla",
					"campo_valor"=>$orden_falla
				],
				[
					"campo_nombre"=>"orden_equipo_email",
					"campo_marcador"=>":EmailIcloud",
					"campo_valor"=>$orden_equipo_email
				],
				[
					"campo_nombre"=>"orden_equipo_pass",
					"campo_marcador"=>":PassIcloud",
					"campo_valor"=>$orden_equipo_pass
				],
				//detalles
				[
					"campo_nombre"=>"orden_accesorios",
					"campo_marcador"=>":Accesorios",
					"campo_valor"=>$orden_accesorios
				],
				[
					"campo_nombre"=>"orden_telefonista",
					"campo_marcador"=>":Telefonista",
					"campo_valor"=>$orden_telefonista
				],
				[
					"campo_nombre"=>"orden_ubicacion_fisica",
					"campo_marcador"=>":UbicFisica",
					"campo_valor"=>$orden_ubicacion_fisica
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
					"campo_nombre"=>"orden_fecha_prometida",
					"campo_marcador"=>":FechaPrometida",
					"campo_valor"=>$fecha_prometida
				],
				[
					"campo_nombre"=>"orden_total_lista",
					"campo_marcador"=>":totalLista",
					"campo_valor"=>$orden_total_lista
				],
				[
					"campo_nombre"=>"orden_total_efectivo",
					"campo_marcador"=>":totalefectivo",
					"campo_valor"=>$orden_total_efectivo
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

			if ($registrar_orden->rowCount()==1) {
				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL."ordenDetail/".$orden_codigo
				];
				unset($_SESSION['datos_cliente_orden']);
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
			$orden_codigo = $_POST['orden_codigo'];
			
			$orden_observaciones = $this->limpiarCadena($_POST['orden_observaciones']);
			if(!isset($orden_observaciones)){
				$orden_observaciones = "";
			}
			$orden_accesorios = $this->limpiarCadena($_POST['orden_accesorios']);
			if(!isset($orden_accesorios)){
				$orden_accesorios = "";
			}
			$orden_ubicacion_fisica = $_POST['orden_ubicacion_fisica'];

			$orden_equipo_contrasena = $_POST['orden_equipo_contrasena'];
			if(!isset($orden_equipo_contrasena)){
				$orden_equipo_contrasena = "";
			}

			
			$datos = [
				//datos
				[
					"campo_nombre"=>"orden_observaciones",
					"campo_marcador"=>":Observaciones",
					"campo_valor"=>$orden_observaciones
				],
				[
					"campo_nombre"=>"orden_equipo_contrasena",
					"campo_marcador"=>":Contrasena",
					"campo_valor"=>$orden_equipo_contrasena
				],
				[
					"campo_nombre"=>"orden_accesorios",
					"campo_marcador"=>":Accesorios",
					"campo_valor"=>$orden_accesorios
				],
				[
					"campo_nombre"=>"orden_ubicacion_fisica",
					"campo_marcador"=>":UbicacionFisica",
					"campo_valor"=>$orden_ubicacion_fisica
				]
			];

			$condicion=[
				"condicion_campo"=>"orden_codigo",
				"condicion_marcador"=>":CodigoOrden",
				"condicion_valor"=>$orden_codigo
			];

			$registrar_orden = $this->actualizarDatos("orden", $datos, $condicion);


			if ($registrar_orden->rowCount()==1) {
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Orden actualizada",
					"texto"=>"La orden se actualizo con exito",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo actualizar la orden, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
			//retornamos el json 
			return json_encode($alerta);
		}

		/*---------- Controlador registrar informe tecnico orden -------- */
		public function registrarInformeTecnicoOrdenControlador(){
			$orden_codigo = $_POST['orden_codigo'];
			$orden_informe_tecnico = $_POST['orden_informe_tecnico'];
			$orden_total_lista = $_POST['orden_total_lista'];
			$orden_total_efectivo = $_POST['orden_total_efectivo'];
			$orden = $this->ejecutarConsulta("SELECT * FROM orden WHERE orden_codigo='$orden_codigo'");
			$orden = $orden->fetch();

			$orden_informe_tecnico_completo = $orden['orden_informe_tecnico'].  "\n".$_SESSION['usuario_nombre']." - ".date("d-m-Y")." - ".date("H:i")."\n"."    -". $orden_informe_tecnico ."\n";
			
			$orden_total_lista_anterior = $orden['orden_total_lista'];			
			$orden_total_efectivo_anterior = $orden['orden_total_efectivo'];

			$orden_fecha_aceptada = $_POST['orden_fecha_aceptada'] ?? "";
			$orden_fecha_prometida = $_POST['orden_fecha_prometida'] ?? "";
			
			$datos =[
				[
					"campo_nombre"=>"orden_informe_tecnico",
					"campo_marcador"=>":InformeTecnico",
					"campo_valor"=>$orden_informe_tecnico_completo
				],
				[
					"campo_nombre"=>"orden_total_lista",
					"campo_marcador"=>":Lista",
					"campo_valor"=>$orden_total_lista
				],
				[
					"campo_nombre"=>"orden_total_efectivo",
					"campo_marcador"=>":Efectivo",
					"campo_valor"=>$orden_total_efectivo
				],
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
			exit();
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
			exit();
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
							  orden.orden_total_lista, 
							  orden.orden_total_efectivo,
							  orden.id_usuario, 
							  orden.id_cliente, 
							  orden.orden_tipo, 
							  orden.orden_falla, 
							  orden.orden_informe_tecnico, 
							  orden.orden_observaciones, 
							  orden.orden_equipo_marca, 
							  orden.orden_equipo_modelo, 
							  orden.orden_accesorios,
							  orden.orden_telefonista, 
							  orden.orden_tecnico_asignado, 
							  orden.orden_ubicacion_fisica, 
							  usuario.id_usuario,
							  usuario.usuario_nombre_completo, 
							  cliente.id_cliente, 
							  cliente.cliente_nombre_completo";

			// Consulta con búsqueda
			if (isset($busqueda) && $busqueda != "") {
				$consulta_datos = "SELECT $campos_tablas 
								   FROM orden 
								   INNER JOIN cliente ON orden.id_cliente = cliente.id_cliente 
								   INNER JOIN usuario ON orden.id_usuario = usuario.id_usuario 
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
								<th class="has-text-centered">Código</th>
								<th class="has-text-centered">Fecha</th>
								<th class="has-text-centered">Cliente</th>
								<th class="has-text-centered">Vendedor</th>
								<th class="has-text-centered">Total Lista</th>
								<th class="has-text-centered">Total Efectivo</th>
							</tr>
						</thead>
						<tbody>
			';
		
			if ($total >= 1 && $pagina <= $numeroPaginas) {
				$contador = $inicio + 1;
				$pag_inicio = $inicio + 1;
				foreach ($datos as $rows) {
					$tabla .= '
						<tr class="has-text-centered" style="cursor: pointer;" onclick="window.location.href=\'' . APP_URL . 'ordenDetail/' . $rows['orden_codigo'] . '/\'">
							<td>' . $rows['orden_codigo'] . '</td>
							<td>' . date("d-m-Y", strtotime($rows['orden_fecha'])) . ' ' . $rows['orden_hora'] . '</td>
							<td>' . $rows['cliente_nombre_completo'] . '</td>
							<td>' . $rows['usuario_nombre_completo'] . '</td>
							<td>' . MONEDA_SIMBOLO . number_format($rows['orden_total_lista'], MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR) . ' ' . MONEDA_NOMBRE . '</td>
							<td>' . MONEDA_SIMBOLO . number_format($rows['orden_total_efectivo'], MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR) . ' ' . MONEDA_NOMBRE . '</td>
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

		/*----------  Controlador listar ordenes de un cliente  ----------*/
		public function listarOrdenesClienteControlador($pagina,$registros,$url,$busqueda, $id_cliente){

			$pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);

			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			$campos_tablas = "orden.id_orden, orden.orden_codigo, orden.orden_fecha, orden.orden_hora, orden.orden_total_lista, orden.orden_total_efectivo, orden.id_usuario, orden.id_cliente, orden.id_caja, orden.id_sucursal, sucursal.id_sucursal, sucursal.sucursal_descripcion, usuario.id_usuario, usuario.usuario_nombre_completo, cliente.id_cliente, cliente.cliente_nombre_completo";

			$consulta_datos = "SELECT orden.id_orden, orden.orden_codigo, orden.orden_fecha, orden.orden_hora, orden.orden_total_lista, orden.orden_total_efectivo, orden.id_usuario, orden.id_cliente, orden.id_caja, orden.id_sucursal, sucursal.id_sucursal, sucursal.sucursal_descripcion, usuario.id_usuario, usuario.usuario_nombre_completo, cliente.id_cliente, cliente.cliente_nombre_completo
								FROM orden 
								INNER JOIN cliente ON orden.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON orden.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON orden.id_caja=caja.id_caja 
								INNER JOIN sucursal ON orden.id_sucursal=sucursal.id_sucursal
								WHERE orden.id_sucursal = $_SESSION[id_sucursal]
								AND orden.id_cliente = $id_cliente
								ORDER BY orden.id_orden DESC";

			$consulta_total = "SELECT count(orden.id_orden) from orden WHERE id_cliente = $id_cliente";

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			$total = $this->ejecutarConsulta($consulta_total);
			$total = (int) $total->fetchColumn();

			// Configuración de la paginación
			$numeroPaginas = ceil($total / $registros);

			$tabla.='
		        <div class="table-container">
		        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
		            <thead>
		                <tr>
		                    <th class="has-text-centered">NRO.</th>
		                    <th class="has-text-centered">Codigo</th>
		                    <th class="has-text-centered">Fecha</th>
		                    <th class="has-text-centered">Telefonista</th>
		                    <th class="has-text-centered">Importe Lista</th>
							<th class="has-text-centered">Importe Efectivo</th>
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
						<tr class="has-text-centered" style="cursor: pointer;" onclick="window.location.href=\'' . APP_URL . 'ordenDetail/' . $rows['orden_codigo'] . '/\'">
							<td>'.$rows['id_orden'].'</td>
							<td>'.$rows['orden_codigo'].'</td>
							<td>'.date("d-m-Y", strtotime($rows['orden_fecha'])).' '.$rows['orden_hora'].'</td>
							<td>'.$rows['usuario_nombre_completo'].'</td>
							<td>'.MONEDA_SIMBOLO.number_format($rows['orden_total_lista'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE.'</td>
							<td>'.MONEDA_SIMBOLO.number_format($rows['orden_total_efectivo'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE.'</td>
							<td>'.$rows['sucursal_descripcion'].'</td>
						</tr>
					';
					$contador++;
				}
				$pag_final=$contador-1;
			}else{
				$tabla.='
					<tr class="has-text-centered" >
						<td colspan="7">
							<a href="'.$url.'5904/" class="button is-link is-rounded is-small mt-4 mb-4">
								Haga clic acá para recargar el listado
							</a>
						</td>
					</tr>
				';
				$tabla.='
					<tr class="has-text-centered" >
						<td colspan="7">
							No hay registros en el sistema
						</td>
					</tr>
				';
			}

			$tabla.='</tbody></table></div>';

			// Paginación
			if ($total > 0 && $pagina <= $numeroPaginas) {
				$tabla .= '<p class="has-text-right">Mostrando órdenes <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
				$tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
			}

			return $tabla;
		}

		/* agregar PRODUCTOS A LA ORDEN  */

		/*---------- Controlador buscar codigo de producto ----------*/
		public function buscarCodigoVentaControlador(){

			/*== Recuperando codigo de busqueda ==*/
			$articulo=$this->limpiarCadena($_POST['buscar_codigo']);

			// Log para verificar el valor recibido
			error_log("Valor recibido en el controlador: " . $articulo);

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
										<th class="has-text-centered">P. Lista</th>
										<th class="has-text-centered">P. Efectivo</th>
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


        /*---------- Controlador agregar producto a orden ----------*/
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

            if(empty($_SESSION['datos_producto_orden'][$codigo])){

                $detalle_cantidad=1;

                $stock_total=$campos['articulo_stock']-$detalle_cantidad;

                $detalle_total=$detalle_cantidad*$campos['articulo_precio_venta'];
                $detalle_total=number_format($detalle_total,MONEDA_DECIMALES,'.','');

                $_SESSION['datos_producto_orden'][$codigo]=[
                    "id_articulo"=>$campos['id_articulo'],
					"articulo_codigo"=>$campos['articulo_codigo'],
					"articulo_stock"=>$stock_total,
					"articulo_stock_old"=>$campos['articulo_stock'],
                    "orden_detalle_precio_compra_producto"=>$campos['articulo_precio_compra'],
                    "orden_detalle_precio_venta_producto"=>$campos['articulo_precio_venta'],
                    "orden_detalle_cantidad_producto"=>1,
                    "orden_detalle_total"=>$detalle_total,
                    "orden_detalle_descripcion_producto"=>$campos['articulo_descripcion']
                ];

            }else{
                $detalle_cantidad=($_SESSION['datos_producto_orden'][$codigo]['orden_detalle_cantidad_producto'])+1;

                $stock_total=$campos['articulo_stock']-$detalle_cantidad;


                $detalle_total=$detalle_cantidad*$campos['articulo_precio_venta'];
                $detalle_total=number_format($detalle_total,MONEDA_DECIMALES,'.','');

                $_SESSION['datos_producto_orden'][$codigo]=[
                    "id_articulo"=>$campos['id_articulo'],
					"articulo_codigo"=>$campos['articulo_codigo'],
					"articulo_stock"=>$stock_total,
					"articulo_stock_total_old"=>$campos['articulo_stock'],
                    "orden_detalle_precio_compra_producto"=>$campos['articulo_precio_compra'],
                    "orden_detalle_precio_venta_producto"=>$campos['articulo_precio_venta'],
                    "orden_detalle_cantidad_producto"=>$detalle_cantidad,
                    "orden_detalle_total"=>$detalle_total,
                    "orden_detalle_descripcion_producto"=>$campos['articulo_descripcion']
                ];

                }

				$alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Articulo agregado!",
                    "texto"=>"El articulo se agrego al carrito de la orden",
                    "icono"=>"success"
                ];

			return json_encode($alerta);
        }

		/*---------- Controlador remover producto de orden ----------*/
        public function removerProductoCarritoControlador(){

            /*== Recuperando codigo del producto ==*/
            $codigo=$this->limpiarCadena($_POST['articulo_codigo']);

            unset($_SESSION['datos_producto_orden'][$codigo]);
			unset($_SESSION['financiacion'][$codigo]);
            if(empty($_SESSION['datos_producto_orden'][$codigo])){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Articulo removido!",
					"texto"=>"El articulo se ha removido de la orden",
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
					"icono"=>"success"
				];
			}

			// Comprobar si el producto existe en la base de datos
			$check_articulo = $this->ejecutarConsulta("SELECT * FROM articulo WHERE articulo_codigo = '$codigo'");
			if($check_articulo->rowCount() <= 0){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Ocurrio un error inesperado!",
					"texto"=>"No se encontro el articulo de codigo '$codigo'",
					"icono"=>"success"
				];
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
			}

			// Asegúrate de que la sesión de productos de orden esté inicializada
			if (!isset($_SESSION['financiacion'])) {
				$_SESSION['financiacion'] = [];
			}

			// Almacena los detalles de financiamiento para el producto
			$_SESSION['financiacion'][$codigo] = [
				"id_articulo" => $campos['id_articulo'],
				"articulo_codigo" => $campos['articulo_codigo'],
				"orden_detalle_financiacion_producto" => $financiacion,
				"orden_detalle_total" => ($_SESSION['datos_producto_orden'][$codigo]['orden_detalle_total'] * $operacion),
				"orden_detalle_descripcion_producto" => $campos['articulo_descripcion']
			];
            
            $alerta=[
				"tipo"=>"recargar",
				"titulo"=>"Articulo financiado",
				"icono"=>"success"
			];

			return json_encode($alerta);
		}

		/*---------- Controlador registrar produtcos a la orden ----------*/
        public function registrarProductosOrdenControlador(){

            $caja = $_SESSION['caja'];
			$orden = $_SESSION['orden'];
			/*== Comprobando orden en la DB ==*/
            $check_orden=$this->ejecutarConsulta("SELECT * FROM orden WHERE orden_codigo='$orden' ");
			if($check_orden->rowCount()<=0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"La orden no existe o no está registrada en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }else{
                $datos_orden=$check_orden->fetch();
            }
			$codigo_orden =  $datos_orden['orden_codigo'];
			$orden_total_lista_old =  $datos_orden['orden_total_lista'];
			$orden_total_efectivo_old =  $datos_orden['orden_total_efectivo'];

            if($_SESSION['orden_importe']<=0 || (!isset($_SESSION['datos_producto_orden']) && count($_SESSION['datos_producto_orden'])<=0)){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No ha agregado productos a esta orden",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }
			if((!isset($_SESSION['financiacion']) && count($_SESSION['financiacion'])<=0)){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has financiado los articulos agregados a esta orden",
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

            /*== Formateando variables ==*/
            $orden_importe=number_format($_SESSION['orden_importe'],MONEDA_DECIMALES,'.','');

            $orden_importe_final=$orden_importe;
            $orden_importe_final=number_format($orden_importe_final,MONEDA_DECIMALES,'.','');



            /*== Calculando total en caja ==*/
            $movimiento_cantidad=$orden_importe;
            $movimiento_cantidad=number_format($movimiento_cantidad,MONEDA_DECIMALES,'.','');

            $total_caja=$datos_caja['caja_monto']+$movimiento_cantidad;
            $total_caja=number_format($total_caja,MONEDA_DECIMALES,'.','');
			$orden_total_lista = $orden_total_lista_old + $movimiento_cantidad;
			$orden_total_efectivo = $orden_total_efectivo_old + $movimiento_cantidad;
			


            /*== Actualizando productos ==*/
            $errores_productos=0;
			foreach($_SESSION['datos_producto_orden'] as $productos){

                /*== Obteniendo datos del producto ==*/
                $check_producto=$this->ejecutarConsulta("SELECT * FROM articulo WHERE id_articulo='".$productos['id_articulo']."' AND articulo_codigo='".$productos['articulo_codigo']."'");
                if($check_producto->rowCount()<1){
                    $errores_productos=1;
                    break;
                }else{
                    $datos_producto=$check_producto->fetch();
                }

                /*== Respaldando datos de BD para poder restaurar en caso de errores ==*/
                $_SESSION['datos_producto_orden'][$productos['articulo_codigo']]['articulo_stock']=$datos_producto['articulo_stock']-$_SESSION['datos_producto_orden'][$productos['articulo_codigo']]['orden_detalle_cantidad_producto'];

                $_SESSION['datos_producto_orden'][$productos['articulo_codigo']]['articulo_stock_total_old']=$datos_producto['articulo_stock'];

                /*== Preparando datos para enviarlos al modelo ==*/
                $datos_producto_up=[
                    [
						"campo_nombre"=>"articulo_stock",
						"campo_marcador"=>":Stock",
						"campo_valor"=>$_SESSION['datos_producto_orden'][$productos['articulo_codigo']]['articulo_stock']
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

                foreach($_SESSION['datos_producto_orden'] as $producto){

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


			/*== Agregando detalles de la orden ==*/
            $errores_orden_detalle=0;
            foreach($_SESSION['datos_producto_orden'] as $orden_detalle){

                /*== Preparando datos para enviarlos al modelo ==*/
                $datos_orden_detalle_reg=[
                	[
						"campo_nombre"=>"orden_detalle_cantidad_producto",
						"campo_marcador"=>":Cantidad",
						"campo_valor"=>$orden_detalle['orden_detalle_cantidad_producto']
					],
					[
						"campo_nombre"=>"orden_detalle_precio_compra_producto",
						"campo_marcador"=>":PrecioCompra",
						"campo_valor"=>$orden_detalle['orden_detalle_precio_compra_producto']
					],
					[
						"campo_nombre"=>"orden_detalle_precio_venta_producto",
						"campo_marcador"=>":PrecioVenta",
						"campo_valor"=>$orden_detalle['orden_detalle_precio_venta_producto']
					],
					[
						"campo_nombre"=>"orden_detalle_financiacion_producto",
						"campo_marcador"=>":Financiacion",
						"campo_valor"=>$_SESSION['financiacion'][$orden_detalle['articulo_codigo']]['orden_detalle_financiacion_producto']
					],
					[
						"campo_nombre"=>"orden_detalle_total",
						"campo_marcador"=>":Total",
						"campo_valor"=>$_SESSION['financiacion'][$orden_detalle['articulo_codigo']]['orden_detalle_total']
					],
					[
						"campo_nombre"=>"orden_detalle_descripcion_producto",
						"campo_marcador"=>":Descripcion",
						"campo_valor"=>$orden_detalle['orden_detalle_descripcion_producto']
					],
					[
						"campo_nombre"=>"orden_codigo",
						"campo_marcador"=>":OrdenCodigo",
						"campo_valor"=>$codigo_orden
					],
					[
						"campo_nombre"=>"id_articulo",
						"campo_marcador"=>":Producto",
						"campo_valor"=>$orden_detalle['id_articulo']
					]
                ];

                $agregar_detalle_orden=$this->guardarDatos("orden_productos",$datos_orden_detalle_reg);

                if($agregar_detalle_orden->rowCount()!=1){
                    $errores_orden_detalle=1;
                    break;
                }
            }

            if($agregar_detalle_orden->rowCount()!=1){
                foreach($_SESSION['datos_producto_orden'] as $producto){

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
					"texto"=>"No hemos podido registrar la orden, por favor intente nuevamente. Código de error: 001",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            

            /*== Reestableciendo DB debido a errores ==*/
            if($errores_orden_detalle==1){

                $this->eliminarRegistro("orden_detalle","orden_codigo",$codigo_orden);
                $this->eliminarRegistro("orden","orden_codigo",$codigo_orden);

                foreach($_SESSION['datos_producto_orden'] as $producto){

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
					"texto"=>"No hemos podido registrar la orden, por favor intente nuevamente. Código de error: 002",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

			$act_orden_total=[
				[
					"campo_nombre"=>"orden_total_lista",
					"campo_marcador"=>":TotalLista",
					"campo_valor"=>$orden_total_lista
				],
				[
					"campo_nombre"=>"orden_total_efectivo",
					"campo_marcador"=>":TotalEfectivo",
					"campo_valor"=>$orden_total_efectivo
				],
				[
					"campo_nombre"=>"orden_total_productos",
					"campo_marcador"=>":TotalProductos",
					"campo_valor"=>$orden_importe
				]
			];

			$condicion=[
				"condicion_campo"=>"orden_codigo",
				"condicion_marcador"=>":Codigo",
				"condicion_valor"=>$codigo_orden
			];

			$this->actualizarDatos("orden",$act_orden_total,$condicion);
			
            /*== Vaciando variables de sesion ==*/
            unset($_SESSION['orden_total']);
            unset($_SESSION['datos_cliente_orden']);
            unset($_SESSION['datos_producto_orden']);

			$alerta=[
				"tipo"=>"recargar",
				"titulo"=>"¡Producto agregado!",
				"texto"=>"El producto se agrego correctamente a la orden",
				"icono"=>"success"
			];
			return json_encode($alerta);
			exit();
        }

		public function buscarOrdenControlador() {
			$where = "";
			$tecnico = "";
			$where_estado = "";
			$where_fecha = "";
		
			// Si viene la sucursal, aplicamos el filtro
			if (isset($_POST['sucursal']) && !empty($_POST['sucursal'])) {
				$id_sucursal = intval($this->limpiarCadena($_POST['sucursal']));
				$where = "WHERE o.id_sucursal = '$id_sucursal'";
			}
		
			// Si viene el técnico, aplicamos el filtro
			if (isset($_POST['tecnico']) && !empty($_POST['tecnico'])) {
				$id_usuario = $this->limpiarCadena($_POST['tecnico']);
				$datos_usuario = $this->seleccionarDatos("Unico", "usuario", "id_usuario", $id_usuario);
				$datos_usuario = $datos_usuario->fetch();
				$nombre_completo_tecnico = $datos_usuario['usuario_nombre_completo'];
				$tecnico = "AND o.orden_tecnico_asignado = '$nombre_completo_tecnico'";
			}
		
			// Si viene el estado, aplicamos el filtro
			if (isset($_POST['estado']) && !empty($_POST['estado'])) {
				$estado = $this->limpiarCadena($_POST['estado']);
				$where_estado = "AND o.orden_estado = '$estado'";
			}
		
			// Si vienen las fechas, aplicamos el filtro
			if (isset($_POST['fecha_inicio']) && !empty($_POST['fecha_inicio']) && isset($_POST['fecha_fin']) && !empty($_POST['fecha_fin'])) {
				$fecha_inicio = $this->limpiarCadena($_POST['fecha_inicio']);
				$fecha_fin = $this->limpiarCadena($_POST['fecha_fin']);
				$where_fecha = "AND o.orden_fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
			}
		
			// Consulta optimizada con JOIN para evitar múltiples consultas
			$consulta_datos = "
				SELECT o.*, 
					   c.cliente_nombre_completo, 
					   s.sucursal_descripcion
				FROM orden o
				JOIN cliente c ON o.id_cliente = c.id_cliente
				JOIN sucursal s ON o.id_sucursal = s.id_sucursal
				$where $tecnico $where_estado $where_fecha
				ORDER BY o.id_orden DESC
			";
		
			$datos_orden = $this->ejecutarConsulta($consulta_datos);
		
			if ($datos_orden->rowCount() >= 1) {
				$datos_orden = $datos_orden->fetchAll();
		
				$tabla = '<div class="table-container is-size-7" style="border-collapse: collapse;">
					<table class="table is-striped is-narrow is-hoverable is-fullwidth">
					<thead>
						<tr>
							<th style="border: 1px solid black;">Nro. orden</th>
							<th style="border: 1px solid black;">Fecha</th>
							<th style="border: 1px solid black;">Cliente</th>
							<th style="border: 1px solid black;">Equipo</th>
							<th style="border: 1px solid black;">Costo Rpto</th>
							<th style="border: 1px solid black;">Técnico</th>
							<th style="border: 1px solid black;">Estado</th>
							<th style="border: 1px solid black;">Verific.</th>
							<th style="border: 1px solid black;">Sucursal</th>
						</tr>
					</thead>
					<tbody>';
		
				foreach ($datos_orden as $orden) {
					$datos_verificacion = $this->seleccionarDatos("Unico", "verificacion", "orden_codigo", $orden['orden_codigo']);
					$datos_verificacion = $datos_verificacion->fetchAll();
					$verificada = "No verif";
					if($datos_verificacion){
						$verificada = "Verif";
					}
					$tabla .= '
						<tr style="cursor: pointer;" onclick="window.location.href=\'' . APP_URL . 'ordenDetail/' . $orden['orden_codigo'] . '/\'">
							<td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($orden['orden_codigo']) . '</td>
							<td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($orden['orden_fecha']) . '</td>
							<td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($orden['cliente_nombre_completo']) . '</td>
							<td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($orden['orden_equipo_marca']) . ' '.htmlspecialchars($orden['orden_equipo_modelo']).'</td>
							<td class="has-text-centered" style="border: 1px solid black;">COSTO RPTO</td>
							<td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($orden['orden_ubicacion_fisica']) . '</td>
							<td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($orden['orden_estado']) . '</td>
							<td class="has-text-centered" style="border: 1px solid black;">' . $verificada . '</td>
							<td class="has-text-centered" style="border: 1px solid black;">' . htmlspecialchars($orden['sucursal_descripcion']) . '</td>
						</tr>';
				}
		
				$tabla .= '</tbody></table></div>';
				echo $tabla;
		
			} else {
				echo '<p class="notification is-warning">No hay equipos registrados.</p>';
			}
		}	

		/*---------- Controlador registrar orden ----------*/
		public function iniciarVerificacionControlador(){
			$orden_codigo = $_POST['orden_codigo'];
			$verificacion_fecha = date("Y-m-d");
			$verificacion_hora_inicio = date("H:i");
			$verificacion_responsable = $this->limpiarCadena($_POST['verificacion_responsable']);
			$verificacion_detalles = $_POST['verificacion_detalles'];


			$datos_verificacion = [
				[
					"campo_nombre"=>"verificacion_responsable",
					"campo_marcador"=>":Responsable",
					"campo_valor"=>$verificacion_responsable
				],
				[
					"campo_nombre"=>"orden_codigo",
					"campo_marcador"=>":Orden",
					"campo_valor"=>$orden_codigo
				],
				[
					"campo_nombre"=>"verificacion_fecha",
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$verificacion_fecha
				],
				[
					"campo_nombre"=>"verificacion_vida",
					"campo_marcador"=>":Vida",
					"campo_valor"=>"inicio"
				],
				[
					"campo_nombre"=>"verificacion_hora_inicio",
					"campo_marcador"=>":HoraInicio",
					"campo_valor"=>$verificacion_hora_inicio
				],
				[
					"campo_nombre"=>"verificacion_detalles",
					"campo_marcador"=>":Detalles",
					"campo_valor"=>$verificacion_detalles
				]
			];

			$datos_orden = $this->seleccionarDatos("Unico", "orden", "orden_codigo", $orden_codigo);
			$datos_orden = $datos_orden->fetch();
			$nuevo_informe = $datos_orden['orden_informe_tecnico'].  "\n".$_SESSION['usuario_nombre']." - ".date("d-m-Y")." - ".date("H:i")."\n"."    -". $verificacion_detalles ."\n";

			$registrar_verificacion = $this->guardarDatos("verificacion", $datos_verificacion);

			if ($registrar_verificacion->rowCount()==1) {
				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL."ordenDetail/".$orden_codigo
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar la verificacion, por favor intente nuevamente",
					"icono"=>"error"
				];
				return json_encode($alerta);
				exit();
			}

			//actualizar datos del estado de la orden, reparar / ensamblar / reparada
			$datos_estado = [
				[
					"campo_nombre"=>"orden_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>"Verificando"
				],

			];
			$condicion=[
				"condicion_campo"=>"orden_codigo",
				"condicion_marcador"=>":CodigoOrden",
				"condicion_valor"=>$orden_codigo
			];

			$this->actualizarDatos("orden", $datos_estado, $condicion);

			$datos =[
				[
					"campo_nombre"=>"orden_informe_tecnico",
					"campo_marcador"=>":InformeTecnico",
					"campo_valor"=>$nuevo_informe
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
		
			
			//retornamos el json 
			return json_encode($alerta);
		}


		public function finalizarVerificacionControlador(){
			$orden_codigo = $_POST['orden_codigo'];
			$datos_orden = $this->seleccionarDatos("Unico", "orden", "orden_codigo", $orden_codigo);
			$datos_orden = $datos_orden->fetch();
			$datos_verif = $this->ejecutarConsulta("SELECT * FROM verificacion WHERE orden_codigo = '$orden_codigo' AND verificacion_vida = 'inicio' ORDER BY id_verificacion DESC LIMIT 1");
			$datos_verif = $datos_verif->fetch();
			$verificacion_hora_inicio = $datos_verif['verificacion_hora_inicio'];
			$verificacion_hora_fin = $_POST['verificacion_hora_fin'];

			// de aqui hasta
			$inicio = \DateTime::createFromFormat('H:i', $verificacion_hora_inicio);
			$fin = \DateTime::createFromFormat('H:i', $verificacion_hora_fin);

			// Si la hora de fin es menor que la hora de inicio, sumamos 1 día al fin
			if ($fin <= $inicio) {
				$fin->modify('+1 day');
			}

			$intervalo = $inicio->diff($fin);

			$horas = $intervalo->h;
			$minutos = $intervalo->i;
			$totalMinutos = ($horas * 60) + $minutos;

			$duracion_formato = $intervalo->format('%H:%i');
			$verificacion_duracion = $duracion_formato;

			//aqui no guarda sino hasta recargar manualmente la pagina 

			$verificacion_estacion_sig = $this->limpiarCadena($_POST['verificacion_estacion_sig']);
			$verificacion_tecnico_asignado = $this->limpiarCadena($_POST['verificacion_tecnico_asignado']);
			$verificacion_detalles = $_POST['verificacion_detalles'];
			$verificacion_estado = $_POST['verificacion_estado'];
			$id_verificacion = $_POST['id_verificacion'];

			$datos_verificacion = [
				[
					"campo_nombre"=>"verificacion_vida",
					"campo_marcador"=>":Vida",
					"campo_valor"=>"finalizo"
				],
				[
					"campo_nombre"=>"verificacion_hora_fin",
					"campo_marcador"=>":HoraInicio",
					"campo_valor"=>$verificacion_hora_fin
				],
				[
					"campo_nombre"=>"verificacion_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$verificacion_estado
				],
				[
					"campo_nombre"=>"verificacion_estacion_sig",
					"campo_marcador"=>":EstacionSig",
					"campo_valor"=>$verificacion_estacion_sig
				],
				[
					"campo_nombre"=>"verificacion_tecnico_asignado",
					"campo_marcador"=>":TecnicoAsig",
					"campo_valor"=>$verificacion_tecnico_asignado
				],
				[
					"campo_nombre"=>"verificacion_duracion",
					"campo_marcador"=>":Duracion",
					"campo_valor"=>$verificacion_duracion
				],
				[
					"campo_nombre"=>"verificacion_detalles",
					"campo_marcador"=>":Detalles",
					"campo_valor"=>$verificacion_detalles
				]
			];

			$condicion_ve=[
				"condicion_campo"=>"id_verificacion",
				"condicion_marcador"=>":idVerificacion",
				"condicion_valor"=>$id_verificacion
			];
			$registrar_verificacion = $this->actualizarDatos("verificacion", $datos_verificacion, $condicion_ve);

			if ($registrar_verificacion->rowCount()==1) {
				$alerta=[
					"tipo"=>"redireccionar",
					"url"=>APP_URL."ordenDetail/".$orden_codigo
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar la verificacion, por favor intente nuevamente",
					"icono"=>"error"
				];
				return json_encode($alerta);
				exit();
			}

			//actualizar datos del estado de la orden, reparar / ensamblar / reparada
			$datos_estado = [
				[
					"campo_nombre"=>"orden_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>$verificacion_estacion_sig
				],

			];
			$condicion=[
				"condicion_campo"=>"orden_codigo",
				"condicion_marcador"=>":CodigoOrden",
				"condicion_valor"=>$orden_codigo
			];

			$this->actualizarDatos("orden", $datos_estado, $condicion);


			
			$nuevo_informe = $datos_orden['orden_informe_tecnico'].  "\n".$_SESSION['usuario_nombre']." - ".date("d-m-Y")." - ".date("H:i")."\n"."    -". $verificacion_detalles ."\n";
			$datos =[
				[
					"campo_nombre"=>"orden_informe_tecnico",
					"campo_marcador"=>":InformeTecnico",
					"campo_valor"=>$nuevo_informe
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
			
		
			
			//retornamos el json 
			return json_encode($alerta);
		}



	}

?>