<?php
    namespace app\controllers;
    use app\models\mainModel;

    class clientController extends mainModel{
        public function registrarClienteControlador(){
            //almacenar datos del nuevo cliente
            $cliente_nombre_completo = $this->limpiarCadena($_POST['cliente_nombre_completo']);
            $cliente_email = $this->limpiarCadena($_POST['cliente_email']);
            $cliente_telefono_1 = $this->limpiarCadena($_POST['cliente_telefono_1']);
            $cliente_telefono_2 = $this->limpiarCadena($_POST['cliente_telefono_2']);
            $cliente_domicilio = $this->limpiarCadena($_POST['cliente_domicilio']);
            $cliente_localidad = $this->limpiarCadena($_POST['cliente_localidad']);
            $cliente_provincia = $this->limpiarCadena($_POST['cliente_provincia']);
            $cliente_pais = $this->limpiarCadena($_POST['cliente_pais']);
            $cliente_tipo_doc = $this->limpiarCadena($_POST['cliente_tipo_doc']);
            $cliente_documento = $this->limpiarCadena($_POST['cliente_documento']);
            $cliente_nacimiento = $this->limpiarCadena($_POST['cliente_nacimiento']);

            //verificar campos obligatorios
            if ($cliente_nombre_completo == "" || $cliente_documento == ""){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios (nombre y documento)",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            //verificar integridad datos
            if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $cliente_nombre_completo)) {
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El nombre no cumple con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }
            if ($this->verificarDatos("[0-9]{7,30}", $cliente_documento)) {
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El documento no cumple con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }
            //chequear domicilio
            if ($cliente_nacimiento != "") {
                if ($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}", $cliente_domicilio)) {
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El domicilio no cumple con el formato solicitado",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }
            //verificar si el email no existe ya
            if($cliente_email!=""){
				if(filter_var($cliente_email, FILTER_VALIDATE_EMAIL)){
					$check_email=$this->ejecutarConsulta("SELECT cliente_email FROM cliente WHERE cliente_email='$cliente_email'");
					if($check_email->rowCount()>0){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error inesperado",
							"texto"=>"El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
							"icono"=>"error"
						];
						return json_encode($alerta);
						exit();
					}
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Ha ingresado un correo electrónico no valido",
						"icono"=>"error"
					];
					return json_encode($alerta);
					exit();
				}
            }

            //verificar cliente por nombre
            $check_cliente_nombre =$this->ejecutarConsulta("SELECT * FROM cliente WHERE cliente_nombre_completo = '$cliente_nombre_completo'");
            if ($check_cliente_nombre->rowCount() > 0) {
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El cliente ya existe",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            //verificar cliente por documento a ver si ya esta registrado
            if ($cliente_documento != "") {
                $check_cliente_documento =$this->ejecutarConsulta("SELECT * FROM cliente WHERE cliente_documento = '$cliente_documento'");
                if ($check_cliente_documento->rowCount() > 0) {
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El cliente con dni ''$cliente_documento'' ya existe",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }


            //almacenar los datos en un arreglo para guardarlos
            $datos_cliente = [
                [
                    "campo_nombre"=>"cliente_nombre_completo",
                    "campo_marcador"=>":NombreCompleto",
                    "campo_valor"=>$cliente_nombre_completo
                ],
                [
                    "campo_nombre"=>"cliente_email",
                    "campo_marcador"=>":Email",
                    "campo_valor"=>$cliente_email
                ],
                [
                    "campo_nombre"=>"cliente_telefono_1",
                    "campo_marcador"=>":Telefono1",
                    "campo_valor"=>$cliente_telefono_1
                ],
                [
                    "campo_nombre"=>"cliente_telefono_2",
                    "campo_marcador"=>":Telefono2",
                    "campo_valor"=>$cliente_telefono_2
                ],
                [
                    "campo_nombre"=>"cliente_domicilio",
                    "campo_marcador"=>":Domicilio",
                    "campo_valor"=>$cliente_domicilio
                ],
                [
                    "campo_nombre"=>"cliente_localidad",
                    "campo_marcador"=>":Localidad",
                    "campo_valor"=>$cliente_localidad
                ],
                [
                    "campo_nombre"=>"cliente_provincia",
                    "campo_marcador"=>":Provincia",
                    "campo_valor"=>$cliente_provincia
                ],
                [
                    "campo_nombre"=>"cliente_pais",
                    "campo_marcador"=>":Pais",
                    "campo_valor"=>$cliente_pais
                ],
                [
                    "campo_nombre"=>"cliente_tipo_doc",
                    "campo_marcador"=>":TipoDoc",
                    "campo_valor"=>$cliente_tipo_doc
                ],
                [
                    "campo_nombre"=>"cliente_documento",
                    "campo_marcador"=>":Documento",
                    "campo_valor"=>$cliente_documento
                ],
                [
                    "campo_nombre"=>"cliente_nacimiento",
                    "campo_marcador"=>":Nacimiento",
                    "campo_valor"=>$cliente_nacimiento
                ]
            ];

            $registrar_cliente = $this->guardarDatos("cliente", $datos_cliente);
            if ($registrar_cliente->rowCount()==1) {
                $alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Cliente registrado con exito",
					"texto"=>"El cliente " .$cliente_nombre_completo. " se registro con exito",
					"icono"=>"success"
				];
            }else{
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el cliente, por favor intente nuevamente",
					"icono"=>"error"
				];
            }
            //retornamos el json 
            return json_encode($alerta);
        }

        public function listarClienteControlador($pagina,$registros,$url,$busqueda){
            $pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);

			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){

				$consulta_datos="SELECT * FROM cliente WHERE ((cliente_nombre_completo LIKE '%$busqueda%' OR cliente_email LIKE '%$busqueda%' OR cliente_documento LIKE '%$busqueda%' )) ORDER BY cliente_nombre_completo ASC LIMIT $inicio,$registros";

				$consulta_total="SELECT COUNT(id_cliente) FROM cliente WHERE ((cliente_nombre_completo LIKE '%$busqueda%' OR cliente_email LIKE '%$busqueda%' OR cliente_documento LIKE '%$busqueda%') )";

			}else{

				$consulta_datos="SELECT * FROM cliente  ORDER BY cliente_nombre_completo ASC LIMIT $inicio,$registros";

				$consulta_total="SELECT COUNT(id_cliente) FROM cliente ";

			}

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			$total = $this->ejecutarConsulta($consulta_total);
			$total = (int) $total->fetchColumn();

			$numeroPaginas =ceil($total/$registros);
            
			$tabla .= '
				<div class="table-container">
				<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
					<thead>
						<tr>
							<th class="has-text-centered">#</th>
							<th class="has-text-centered">Nombre</th>
							<th class="has-text-centered">Email</th>
							<th class="has-text-centered">Telefono 1</th>
							<th class="has-text-centered">Documento</th>
							<th class="has-text-centered">Ventas</th>
							<th class="has-text-centered">Ordenes</th>
						</tr>
					</thead>
					<tbody>
			';

			if ($total >= 1 && $pagina <= $numeroPaginas) {
				$contador = $inicio + 1;
				$pag_inicio = $inicio + 1;
				foreach ($datos as $rows) {
					// Envolver la fila en un enlace
					$tabla .= '
						<tr class="has-text-centered" style="cursor: pointer;" onclick="window.location.href=\'' . APP_URL . 'clientUpdate/' . $rows['id_cliente'] . '/\'">
							<td>' . $contador . '</td>
							<td>' . $rows['cliente_nombre_completo'] . '</td>
							<td>' . $rows['cliente_email'] . '</td>
							<td>' . $rows['cliente_telefono_1'] . '</td>
							<td>' . $rows['cliente_documento'] . '</td>
							<td>
			                    <a href="'.APP_URL.'salesClientDetail/'.$rows['id_cliente'].'/" class="button is-link is-rounded is-small" title="Ventas del cliente. '.$rows['id_cliente'].'" >
			                    	<i class="fas fa-shopping-bag fa-fw"></i>
			                    </a>
			                </td>
							<td>
			                    <a href="'.APP_URL.'ordersClientDetail/'.$rows['id_cliente'].'/" class="button is-link is-rounded is-small" title="Ordenes del cliente. '.$rows['id_cliente'].'" >
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
							<td colspan="5">
								<a href="' . $url . '1/" class="button is-link is-rounded is-small mt-4 mb-4">
									Haga clic acá para recargar el listado
								</a>
							</td>
						</tr>
					';
				} else {
					$tabla .= '
						<tr class="has-text-centered">
							<td colspan="5">
								No hay registros en el sistema
								<div class="mt-1">
									<a href="' . APP_URL . 'clientNew/" class="button is-success is-rounded is-small">Registrar cliente</a>
								</div>
							</td>
						</tr>
					';
				}
			}

			$tabla .= '</tbody></table></div>';

			### Paginacion ###
			if($total>0 && $pagina<=$numeroPaginas){
				$tabla.='<p class="has-text-right">Mostrando clientes <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

				$tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
			}

			return $tabla;
        }

		/*----------  Controlador listar ventas del cliente  ----------*/
		public function listarVentasClienteControlador($pagina,$registros,$url,$busqueda, $cliente){

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
									AND venta.id_cliente = '$cliente';
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
									AND venta.id_sucursal = '$_SESSION[id_sucursal]'
									AND venta.id_cliente = '$cliente'";
			
			}else{
			
				$consulta_datos="SELECT $campos_tablas 
								FROM venta 
								INNER JOIN cliente ON venta.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON venta.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON venta.id_caja=caja.id_caja 
								WHERE venta.id_sucursal = '$_SESSION[id_sucursal]' AND venta.id_cliente = '$cliente';
								ORDER BY venta.id_venta DESC LIMIT $inicio,$registros";
			
				$consulta_total="SELECT COUNT(id_venta) 
								FROM venta 
								INNER JOIN cliente ON venta.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON venta.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON venta.id_caja=caja.id_caja
								WHERE venta.id_sucursal = '$_SESSION[id_sucursal]' AND venta.id_cliente = '$cliente';";
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
		                    <th class="has-text-centered">Vendedor</th>
		                    <th class="has-text-centered">Total</th>
		                    <th class="has-text-centered">Detalle</th>
		                </tr>
		            </thead>
		            <tbody>
		    ';

		    if($total>=1 && $pagina<=$numeroPaginas){
				$contador=$inicio+1;
				$pag_inicio=$inicio+1;
				foreach($datos as $rows){
					$tabla.='
						<tr class="has-text-centered" >
							<td>'.$rows['id_venta'].'</td>
							<td>'.$rows['venta_codigo'].'</td>
							<td>'.date("d-m-Y", strtotime($rows['venta_fecha'])).' '.$rows['venta_hora'].'</td>
							<td>'.$rows['usuario_nombre_completo'].'</td>
							<td>'.MONEDA_SIMBOLO.number_format($rows['venta_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE.'</td>
			                <td>

			                    <a href="'.APP_URL.'saleDetail/'.$rows['venta_codigo'].'/" class="button is-link is-rounded is-small" title="Informacion de venta Nro. '.$rows['id_venta'].'" >
			                    	<i class="fas fa-shopping-bag fa-fw"></i>
			                    </a>

			                </td>
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

		/*----------  Controlador listar ordenes  ----------*/
		public function listarOrdenesClienteControlador($pagina,$registros,$url,$busqueda, $cliente){

			$pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);

			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			$campos_tablas = "orden.id_orden, orden.orden_codigo, orden.orden_fecha, orden.orden_hora, orden.orden_total, orden.id_usuario, orden.id_cliente, orden.id_caja, usuario.id_usuario, usuario.usuario_nombre_completo, cliente.id_cliente, cliente.cliente_nombre_completo";

			if(isset($busqueda) && $busqueda!=""){

				$consulta_datos="SELECT $campos_tablas 
								FROM orden 
								INNER JOIN cliente ON orden.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON orden.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON orden.id_caja=caja.id_caja 
								WHERE 
									orden.id_orden LIKE '%$busqueda%' 
									OR orden.orden_codigo LIKE '%$busqueda%' 
									OR cliente.cliente_nombre_completo LIKE '%$busqueda%' 
									OR usuario.usuario_nombre_completo LIKE '%$busqueda%' 
									OR caja.caja_nombre LIKE '%$busqueda%' 
									AND orden.id_sucursal = '$_SESSION[id_sucursal]'
									AND orden.id_cliente = '$cliente';
								ORDER BY orden.id_orden DESC LIMIT $inicio,$registros";
			
				$consulta_total="SELECT COUNT(id_orden) 
								FROM orden 
								INNER JOIN cliente ON orden.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON orden.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON orden.id_caja=caja.id_caja 
								WHERE 
									orden.id_orden LIKE '%$busqueda%' 
									OR orden.orden_codigo LIKE '%$busqueda%' 
									OR cliente.cliente_nombre_completo LIKE '%$busqueda%' 
									OR usuario.usuario_nombre_completo LIKE '%$busqueda%' 
									OR caja.caja_nombre LIKE '%$busqueda%'
									AND orden.id_sucursal = '$_SESSION[id_sucursal]'
									AND orden.id_cliente = '$cliente'";
			
			}else{
			
				$consulta_datos="SELECT $campos_tablas 
								FROM orden 
								INNER JOIN cliente ON orden.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON orden.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON orden.id_caja=caja.id_caja 
								WHERE orden.id_sucursal = '$_SESSION[id_sucursal]' AND orden.id_cliente = '$cliente';
								ORDER BY orden.id_orden DESC LIMIT $inicio,$registros";
			
				$consulta_total="SELECT COUNT(id_orden) 
								FROM orden 
								INNER JOIN cliente ON orden.id_cliente=cliente.id_cliente 
								INNER JOIN usuario ON orden.id_usuario=usuario.id_usuario 
								INNER JOIN caja ON orden.id_caja=caja.id_caja
								WHERE orden.id_sucursal = '$_SESSION[id_sucursal]' AND orden.id_cliente = '$cliente';";
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
		                    <th class="has-text-centered">Telefonista</th>
		                    <th class="has-text-centered">Total</th>
		                    <th class="has-text-centered">Detalle</th>
		                </tr>
		            </thead>
		            <tbody>
		    ';

		    if($total>=1 && $pagina<=$numeroPaginas){
				$contador=$inicio+1;
				$pag_inicio=$inicio+1;
				foreach($datos as $rows){
					$tabla.='
						<tr class="has-text-centered" >
							<td>'.$rows['id_orden'].'</td>
							<td>'.$rows['orden_codigo'].'</td>
							<td>'.date("d-m-Y", strtotime($rows['orden_fecha'])).' '.$rows['orden_hora'].'</td>
							<td>'.$rows['usuario_nombre_completo'].'</td>
							<td>'.MONEDA_SIMBOLO.number_format($rows['orden_importe'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE.'</td>
			                <td>

			                    <a href="'.APP_URL.'saleDetail/'.$rows['orden_codigo'].'/" class="button is-link is-rounded is-small" title="Informacion de orden Nro. '.$rows['id_orden'].'" >
			                    	<i class="fas fa-shopping-bag fa-fw"></i>
			                    </a>

			                </td>
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
			                    <a href="'.$url."".$cliente.'/" class="button is-link is-rounded is-small mt-4 mb-4">
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
				$tabla.='<p class="has-text-right">Mostrando ordenes <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

				$tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
			}

			return $tabla;
		}
        
        public function actualizarClienteControlador(){
            $id_cliente = $this->limpiarCadena($_POST['id_cliente']);

			$datos = $this->ejecutarConsulta("SELECT * FROM cliente where id_cliente = '$id_cliente'");
			if ($datos->rowCount()<=0) {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se encontro el cliente en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			}else{
				$datos = $datos->fetch();
			}

			$cliente_nombre_completo = $this->limpiarCadena($_POST['cliente_nombre_completo']);
			$cliente_email = $this->limpiarCadena($_POST['cliente_email']);
			$cliente_telefono_1 = $this->limpiarCadena($_POST['cliente_telefono_1']);
			$cliente_telefono_2 = $this->limpiarCadena($_POST['cliente_telefono_2']);
			$cliente_domicilio = $this->limpiarCadena($_POST['cliente_domicilio']);
			$cliente_localidad = $this->limpiarCadena($_POST['cliente_localidad']);
			$cliente_provincia = $this->limpiarCadena($_POST['cliente_provincia']);
			$cliente_pais = $this->limpiarCadena($_POST['cliente_pais']);
			$cliente_tipo_doc = $this->limpiarCadena($_POST['cliente_tipo_doc']);
			$cliente_documento = $this->limpiarCadena($_POST['cliente_documento']);
			$cliente_nacimiento = $this->limpiarCadena($_POST['cliente_nacimiento']);

			$cliente_datos = [];

            if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\\s\/\(\)%\/\-\.]{3,100}", $cliente_nombre_completo)) {
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El nombre no cumple con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

			if ($this->verificarDatos("[0-9]{4,20}", $cliente_telefono_1)) {
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El telefono 1 no cumple con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

			if ($cliente_telefono_2 != $datos['cliente_telefono_2']) {
				if ($cliente_telefono_2 != "" && $this->verificarDatos("[0-9]{4,20}", $cliente_telefono_2)) {
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"El telefono 2 no cumple con el formato solicitado",
						"icono"=>"error"
					];
					return json_encode($alerta);
					exit();
				}

				$cliente_datos[] = [
					"campo_nombre"=>"cliente_telefono_2",
					"campo_marcador"=>":Telefono2",
					"campo_valor"=>$cliente_telefono_2
				];
			}

			if ($cliente_domicilio != $datos['cliente_domicilio']) {
				if ($cliente_domicilio != "" && $this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\\s\/\(\)%\/\-\.]{3,100}", $cliente_domicilio)) {
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"El domicilio no cumple con el formato solicitado",
						"icono"=>"error"
					];
					return json_encode($alerta);
					exit();
				}

				$cliente_datos[] = [
					"campo_nombre"=>"cliente_domicilio",
					"campo_marcador"=>":Domicilio",
					"campo_valor"=>$cliente_domicilio
				];
			}

			if ($cliente_documento != $datos['cliente_documento']) {
				if ($cliente_documento != "" && $this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}", $cliente_documento)) {
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"El documento no cumple con el formato solicitado",
						"icono"=>"error"
					];
					return json_encode($alerta);
					exit();
				}

				$cliente_datos[] = [
					"campo_nombre"=>"cliente_documento",
					"campo_marcador"=>":Documento",
					"campo_valor"=>$cliente_documento
				];
			}
				

            //verificar si el email no existe ya si se quiere actualizar
            if($cliente_email!="" && $datos['cliente_email'] != $cliente_email){
				if(filter_var($cliente_email, FILTER_VALIDATE_EMAIL)){
					$check_email=$this->ejecutarConsulta("SELECT cliente_email FROM cliente WHERE cliente_email='$cliente_email'");
					if($check_email->rowCount()>0){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error inesperado",
							"texto"=>"El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
							"icono"=>"error"
						];
						return json_encode($alerta);
						exit();
					}
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Ha ingresado un correo electrónico no valido",
						"icono"=>"error"
					];
					return json_encode($alerta);
					exit();
				}
            }

			//verificar dni en caso de actualizarse
			if($cliente_documento!="" && $datos['cliente_documento'] != $cliente_documento){
				$check_documento =$this->ejecutarConsulta("SELECT * FROM cliente WHERE cliente_documento = '$cliente_documento'");
				if ($check_documento->rowCount() > 0) {
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Ya hay un cliente registrado con ese documento",
						"icono"=>"error"
					];
					return json_encode($alerta);
					exit();
				}
			}
            
			$cliente_datos = [
				[
					"campo_nombre"=>"cliente_nombre_completo",
					"campo_marcador"=>":NombreCompleto",
					"campo_valor"=>$cliente_nombre_completo
				],
				[
					"campo_nombre"=>"cliente_email",
					"campo_marcador"=>":Email",
					"campo_valor"=>$cliente_email
				],
				[
					"campo_nombre"=>"cliente_telefono_1",
					"campo_marcador"=>":Telefono1",
					"campo_valor"=>$cliente_telefono_1
				],
				[
					"campo_nombre"=>"cliente_localidad",
					"campo_marcador"=>":Localidad",
					"campo_valor"=>$cliente_localidad
				],
				[
					"campo_nombre"=>"cliente_provincia",
					"campo_marcador"=>":Provincia",
					"campo_valor"=>$cliente_provincia
				],
				[
					"campo_nombre"=>"cliente_pais",
					"campo_marcador"=>":Pais",
					"campo_valor"=>$cliente_pais
				],
				[
					"campo_nombre"=>"cliente_tipo_doc",
					"campo_marcador"=>":TipoDoc",
					"campo_valor"=>$cliente_tipo_doc
				],
				[
					"campo_nombre"=>"cliente_documento",
					"campo_marcador"=>":Documento",
					"campo_valor"=>$cliente_documento
				]
			];

			$condicion = [
				"condicion_campo"=>"id_cliente",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id_cliente
			];

			if($this->actualizarDatos("cliente",$cliente_datos,$condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Cliente actualizado",
					"texto"=>"Los datos del cliente ".$datos['cliente_nombre_completo']." se actualizaron correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar los datos del cliente ".$datos['cliente_nombre_completo'].", por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);

        }

		/*---------- Controlador buscar cliente ----------*/
        public function buscarClienteControlador(){

            /*== Recuperando termino de busqueda ==*/
			$cliente=$this->limpiarCadena($_POST['buscar_cliente']);

			/*== Si campo esta vacio se muestran todos los clientes ==*/
			if($cliente==""){
				$datos_cliente=$this->ejecutarConsulta("SELECT * FROM cliente");
            }else{
				$datos_cliente=$this->ejecutarConsulta("SELECT * FROM cliente WHERE (cliente_documento LIKE '%$cliente%' OR cliente_nombre_completo LIKE '%$cliente%' OR cliente_telefono_1 LIKE '%$cliente%' OR cliente_telefono_2  LIKE '%$cliente%' OR cliente_email LIKE '%$cliente%' OR cliente_codigo LIKE '%$cliente%' ) ORDER BY cliente_nombre_completo ASC");
			}

          	if($datos_cliente->rowCount()>=1){

				$datos_cliente=$datos_cliente->fetchAll();

				$tabla='<div class="table-container mb-6"><table class="table is-striped is-narrow is-hoverable is-fullwidth"><tbody>';

				foreach($datos_cliente as $rows){
					$tabla.='
					<tr style="cursor: pointer;" >
                        <td class="has-text-left" onclick="window.location.href=\'' . APP_URL . 'clientUpdate/' . $rows['id_cliente'] . '/\'" ><i class="fas fa-male fa-fw"></i> &nbsp; '.$rows['cliente_nombre_completo'].' ('.$rows['cliente_tipo_doc'].': '.$rows['cliente_documento'].')</td>
						<td class="has-text-right">
							<i class="fas fa-shopping-cart icon-action" onclick="window.location.href=\'' . APP_URL . 'salesClientDetail/' . $rows['id_cliente'] . '/\'"></i>
							<label>|</label>
							<i class="fas fa-receipt icon-action" onclick="window.location.href=\'' . APP_URL . 'ordersClientDetail/' . $rows['id_cliente'] . '/\'"></i>
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

		public function registrarClienteGuardarSesionControlador(){
            //almacenar datos del nuevo cliente
            $cliente_nombre_completo = $this->limpiarCadena($_POST['cliente_nombre_completo']);
            $cliente_email = $this->limpiarCadena($_POST['cliente_email']);
            $cliente_telefono_1 = $this->limpiarCadena($_POST['cliente_telefono_1']);
            $cliente_telefono_2 = $this->limpiarCadena($_POST['cliente_telefono_2']);
            $cliente_domicilio = $this->limpiarCadena($_POST['cliente_domicilio']);
            $cliente_localidad = $this->limpiarCadena($_POST['cliente_localidad']);
            $cliente_provincia = $this->limpiarCadena($_POST['cliente_provincia']);
            $cliente_pais = $this->limpiarCadena($_POST['cliente_pais']);
            $cliente_tipo_doc = $this->limpiarCadena($_POST['cliente_tipo_doc']);
            $cliente_documento = $this->limpiarCadena($_POST['cliente_documento']);
            $cliente_nacimiento = $this->limpiarCadena($_POST['cliente_nacimiento']);

            //verificar campos obligatorios
            if ($cliente_nombre_completo == "" || $cliente_documento == ""){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios (nombre y documento)",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            //verificar integridad datos
            if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $cliente_nombre_completo)) {
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El nombre no cumple con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }
            if ($this->verificarDatos("[0-9]{7,30}", $cliente_documento)) {
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El documento no cumple con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }
            //chequear domicilio
            if ($cliente_nacimiento != "") {
                if ($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}", $cliente_domicilio)) {
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El domicilio no cumple con el formato solicitado",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }
            //verificar si el email no existe ya
            if($cliente_email!=""){
				if(filter_var($cliente_email, FILTER_VALIDATE_EMAIL)){
					$check_email=$this->ejecutarConsulta("SELECT cliente_email FROM cliente WHERE cliente_email='$cliente_email'");
					if($check_email->rowCount()>0){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error inesperado",
							"texto"=>"El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
							"icono"=>"error"
						];
						return json_encode($alerta);
						exit();
					}
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Ha ingresado un correo electrónico no valido",
						"icono"=>"error"
					];
					return json_encode($alerta);
					exit();
				}
            }

            //verificar cliente por nombre
            $check_cliente_nombre =$this->ejecutarConsulta("SELECT * FROM cliente WHERE cliente_nombre_completo = '$cliente_nombre_completo'");
            if ($check_cliente_nombre->rowCount() > 0) {
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El cliente ya existe",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            //verificar cliente por documento a ver si ya esta registrado
            if ($cliente_documento != "") {
                $check_cliente_documento =$this->ejecutarConsulta("SELECT * FROM cliente WHERE cliente_documento = '$cliente_documento'");
                if ($check_cliente_documento->rowCount() > 0) {
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"El cliente con dni ''$cliente_documento'' ya existe",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }


            //almacenar los datos en un arreglo para guardarlos
            $datos_cliente = [
                [
                    "campo_nombre"=>"cliente_nombre_completo",
                    "campo_marcador"=>":NombreCompleto",
                    "campo_valor"=>$cliente_nombre_completo
                ],
                [
                    "campo_nombre"=>"cliente_email",
                    "campo_marcador"=>":Email",
                    "campo_valor"=>$cliente_email
                ],
                [
                    "campo_nombre"=>"cliente_telefono_1",
                    "campo_marcador"=>":Telefono1",
                    "campo_valor"=>$cliente_telefono_1
                ],
                [
                    "campo_nombre"=>"cliente_telefono_2",
                    "campo_marcador"=>":Telefono2",
                    "campo_valor"=>$cliente_telefono_2
                ],
                [
                    "campo_nombre"=>"cliente_domicilio",
                    "campo_marcador"=>":Domicilio",
                    "campo_valor"=>$cliente_domicilio
                ],
                [
                    "campo_nombre"=>"cliente_localidad",
                    "campo_marcador"=>":Localidad",
                    "campo_valor"=>$cliente_localidad
                ],
                [
                    "campo_nombre"=>"cliente_provincia",
                    "campo_marcador"=>":Provincia",
                    "campo_valor"=>$cliente_provincia
                ],
                [
                    "campo_nombre"=>"cliente_pais",
                    "campo_marcador"=>":Pais",
                    "campo_valor"=>$cliente_pais
                ],
                [
                    "campo_nombre"=>"cliente_tipo_doc",
                    "campo_marcador"=>":TipoDoc",
                    "campo_valor"=>$cliente_tipo_doc
                ],
                [
                    "campo_nombre"=>"cliente_documento",
                    "campo_marcador"=>":Documento",
                    "campo_valor"=>$cliente_documento
                ],
                [
                    "campo_nombre"=>"cliente_nacimiento",
                    "campo_marcador"=>":Nacimiento",
                    "campo_valor"=>$cliente_nacimiento
                ]
            ];

            $registrar_cliente = $this->guardarDatos("cliente", $datos_cliente);
            if ($registrar_cliente->rowCount()==1) {
				$campos = $this->ejecutarConsulta("SELECT * FROM cliente WHERE cliente_documento = '$cliente_documento'");
				$campos = $campos->fetch();
				if($_SESSION['datos_cliente_orden']['id_cliente']==1){
					$_SESSION['datos_cliente_orden']=[
						"id_cliente"=>$campos['id_cliente'],
						"cliente_tipo_doc"=>$campos['cliente_tipo_doc'],
						"cliente_documento"=>$campos['cliente_documento'],
						"cliente_nombre_completo"=>$campos['cliente_nombre_completo']
					];
					$alerta=[
						"tipo"=>"recargar",
						"titulo"=>"Cliente registrado con exito",
						"texto"=>"El cliente " .$cliente_nombre_completo. " se registro con exito y se agrego a la orden",
						"icono"=>"success"
					];
				}
            }else{
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el cliente, por favor intente nuevamente",
					"icono"=>"error"
				];
            }
            //retornamos el json 
            return json_encode($alerta);
        }
    } 
  
?>