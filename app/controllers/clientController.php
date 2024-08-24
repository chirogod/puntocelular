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
            
			$tabla.='
		        <div class="table-container">
		        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
		            <thead>
		                <tr>
		                    <th class="has-text-centered">#</th>
		                    <th class="has-text-centered">Nombre</th>
		                    <th class="has-text-centered">Email</th>
		                    <th class="has-text-centered">Telefono 1</th>
		                    <th class="has-text-centered">Telefono 2</th>
                            <th class="has-text-centered">Domicilio</th>
                            <th class="has-text-centered">Localida</th>
                            <th class="has-text-centered">Provincia</th>
                            <th class="has-text-centered">Pais</th>
                            <th class="has-text-centered">Tipo</th>
                            <th class="has-text-centered">Documento</th>
                            <th class="has-text-centered">Nacimiento</th>
		                    <th class="has-text-centered">Actualizar</th>
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
							<td>'.$contador.'</td>
							<td>'.$rows['cliente_nombre_completo'].'</td>
							<td>'.$rows['cliente_email'].'</td>
                            <td>'.$rows['cliente_telefono_1'].'</td>
                            <td>'.$rows['cliente_telefono_2'].'</td>
                            <td>'.$rows['cliente_domicilio'].'</td>
                            <td>'.$rows['cliente_localidad'].'</td>
                            <td>'.$rows['cliente_provincia'].'</td>
                            <td>'.$rows['cliente_pais'].'</td>
                            <td>'.$rows['cliente_tipo_doc'].'</td>
                            <td>'.$rows['cliente_documento'].'</td>
                            <td>'.$rows['cliente_nacimiento'].'</td>
							
			                <td>
			                    <a href="'.APP_URL.'clientUpdate/'.$rows['id_cliente'].'/" class="button is-success is-rounded is-small">
			                    	<i class="fas fa-sync fa-fw"></i>
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
				$tabla.='<p class="has-text-right">Mostrando clientes <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

				$tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
			}

			return $tabla;
        }

        /*
        public function actualizarUsuarioControlador(){

            $id_usuario = $this->limpiarCadena($_POST['id_usuario']);

			$datos = $this->ejecutarConsulta("SELECT * FROM usuario where id_usuario = '$id_usuario'");
			if ($datos->rowCount()<=0) {
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se encontro el usuario en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			}else{
				$datos = $datos->fetch();
			}

			$usuario_nombre_completo = $this->limpiarCadena($_POST['usuario_nombre_completo']);
			$usuario_usuario = $this->limpiarCadena($_POST['usuario_usuario']);
			$usuario_email = $this->limpiarCadena($_POST['usuario_email']);
			$usuario_telefono = $this->limpiarCadena($_POST['usuario_telefono']);
			$usuario_rol = $this->limpiarCadena($_POST['usuario_rol']);
			$usuario_activo = $this->limpiarCadena($_POST['usuario_activo']);
			$usuario_clave_1 = $this->limpiarCadena($_POST['usuario_clave_2']);
			$usuario_clave_2 = $this->limpiarCadena($_POST['usuario_clave_2']);


            if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $usuario_nombre_completo)) {
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El nombre no cumple con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }else if($this->verificarDatos("[a-zA-Z0-9]{4,20}", $usuario_usuario)){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El usuario no cumple con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }
			if ($usuario_clave_1!="" && $usuario_clave_2 !="") {
				if ($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $usuario_clave_1) || $this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $usuario_clave_2)) {
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Las claves no coinciden con el formato solicitado",
						"icono"=>"error"
					];
					return json_encode($alerta);
					exit();
				}
			}
				

            //verificar si el email no existe ya
            if($usuario_email!="" && $datos['usuario_email'] != $usuario_email){
				if(filter_var($usuario_email, FILTER_VALIDATE_EMAIL)){
					$check_email=$this->ejecutarConsulta("SELECT usuario_email FROM usuario WHERE usuario_email='$usuario_email'");
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

            //verificar igualdad de claves
			if($usuario_clave_1!="" || $usuario_clave_2!=""){
            	if($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}",$usuario_clave_1) || $this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $usuario_clave_2)){

			        $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Las CLAVES no coinciden con el formato solicitado",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
			    }else{
			    	if($usuario_clave_1 != $usuario_clave_2){

						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error inesperado",
							"texto"=>"Las nuevas CLAVES que acaba de ingresar no coinciden, por favor verifique e intente nuevamente",
							"icono"=>"error"
						];
						return json_encode($alerta);
						exit();
			    	}else{
			    		$clave = $usuario_clave_1;
			    	}
			    }
			}else{
				$clave=$datos['usuario_clave'];
            }

            //verificar usuario
			if($usuario_usuario!="" && $datos['usuario_usuario'] != $usuario_usuario){
				$check_usuario =$this->ejecutarConsulta("SELECT * FROM usuario WHERE usuario_usuario = '$usuario_usuario'");
				if ($check_usuario->rowCount() > 0) {
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"El usuario ya existe",
						"icono"=>"error"
					];
					return json_encode($alerta);
					exit();
				}
			}
            //verificar que el cargo exista
            if ($usuario_rol != "Administrador" && $usuario_rol != "Empleado" && $usuario_rol != "Tecnico" && $usuario_rol != "Ventas") {
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"El cargo no existe",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

			$usuario_datos = [
				[
					"campo_nombre"=>"usuario_nombre_completo",
					"campo_marcador"=>":NombreCompleto",
					"campo_valor"=>$usuario_nombre_completo
				],
				[
					"campo_nombre"=>"usuario_usuario",
					"campo_marcador"=>":Usuario",
					"campo_valor"=>$usuario_usuario
				],
				[
					"campo_nombre"=>"usuario_email",
					"campo_marcador"=>":Email",
					"campo_valor"=>$usuario_email
				],
				[
					"campo_nombre"=>"usuario_telefono",
					"campo_marcador"=>":Telefono",
					"campo_valor"=>$usuario_telefono
				],
				[
					"campo_nombre"=>"usuario_rol",
					"campo_marcador"=>":Rol",
					"campo_valor"=>$usuario_rol
				],
				[
					"campo_nombre"=>"usuario_clave",
					"campo_marcador"=>":Clave",
					"campo_valor"=>$clave
				],
				[
					"campo_nombre"=>"usuario_activo",
					"campo_marcador"=>":Activo",
					"campo_valor"=>$usuario_activo
				]
			];

			$condicion = [
				"condicion_campo"=>"id_usuario",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id_usuario
			];

			if($this->actualizarDatos("usuario",$usuario_datos,$condicion)){

				if($id_usuario==$_SESSION['id_usuario']){
					$_SESSION['usuario_nombre_completo']=$usuario_nombre_completo;
					$_SESSION['usuario_usuario']=$usuario_usuario;
				}

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Usuario actualizado",
					"texto"=>"Los datos del usuario ".$datos['usuario_nombre_completo']." se actualizaron correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar los datos del usuario ".$datos['usuario_nombre_completo'].", por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);

        }*/
    } 
  
?>