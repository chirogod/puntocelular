<?php
    namespace app\controllers;
    use app\models\mainModel;

    class userController extends mainModel{
        public function registrarUsuarioControlador(){
            //almacenar datos del nuevo usuario
            $usuario_nombre_completo = $this->limpiarCadena($_POST['usuario_nombre_completo']);
            $usuario_usuario = $this->limpiarCadena($_POST['usuario_usuario']);
            $usuario_telefono = $this->limpiarCadena($_POST['usuario_telefono']);
            $usuario_email = $this->limpiarCadena($_POST['usuario_email']);
            $usuario_dni = $this->limpiarCadena($_POST['usuario_dni']);
            $usuario_nacimiento = $this->limpiarCadena($_POST['usuario_nacimiento']);
            $usuario_clave_1 = $this->limpiarCadena($_POST['usuario_clave_1']);
            $usuario_clave_2 = $this->limpiarCadena($_POST['usuario_clave_2']);
            $usuario_rol = $this->limpiarCadena($_POST['usuario_rol']);

            if ($usuario_nombre_completo == "" || $usuario_usuario == "" || $usuario_rol == "" || $usuario_clave_1 == "" || $usuario_clave_2 == "" ) {
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

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
            }elseif ($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $usuario_clave_1) || $this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $usuario_clave_1)) {
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Las claves no coinciden con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            //verificar si el email no existe ya
            if($usuario_email!=""){
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
            if ($usuario_clave_1 != $usuario_clave_2) {
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"Las claves no coinciden",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }else{
                $clave = $usuario_clave_1;
            }

            //verificar usuario
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

            //almacenar los datos en un arreglo para guardarlos
            $datos_usuarios = [
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
                    "campo_nombre"=>"usuario_telefono",
                    "campo_marcador"=>":Telefono",
                    "campo_valor"=>$usuario_telefono
                ],
                [
                    "campo_nombre"=>"usuario_email",
                    "campo_marcador"=>":Email",
                    "campo_valor"=>$usuario_email
                ],
                [
                    "campo_nombre"=>"usuario_dni",
                    "campo_marcador"=>":Dni",
                    "campo_valor"=>$usuario_dni
                ],
                [
                    "campo_nombre"=>"usuario_nacimiento",
                    "campo_marcador"=>":Nacimiento",
                    "campo_valor"=>$usuario_nacimiento
                ],
                [
                    "campo_nombre"=>"usuario_clave",
                    "campo_marcador"=>":Clave",
                    "campo_valor"=>$clave
                ],
                [
                    "campo_nombre"=>"usuario_rol",
                    "campo_marcador"=>":Rol",
                    "campo_valor"=>$usuario_rol
                ]
            ];

            $registrar_usuario = $this->guardarDatos("usuario", $datos_usuarios);
            if ($registrar_usuario->rowCount()==1) {
                $alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Usuario registrado con exito",
					"texto"=>"El usuario " .$usuario_nombre_completo. " se registro con exito",
					"icono"=>"success"
				];
            }else{
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el usuario, por favor intente nuevamente",
					"icono"=>"error"
				];
            }
            //retornamos el json 
            return json_encode($alerta);
        }

        public function listarUsuarioControlador($pagina,$registros,$url,$busqueda){
            $pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);

			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){

				$consulta_datos="SELECT * FROM usuario WHERE ((usuario_nombre_completo LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%' OR usuario_dni LIKE '%$busqueda%')) ORDER BY usuario_nombre_completo ASC LIMIT $inicio,$registros";

				$consulta_total="SELECT COUNT(id_usuario) FROM usuario WHERE ((usuario_nombre_completo LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%' OR usuario_dni LIKE '%$busqueda%'))";

			}else{

				$consulta_datos="SELECT * FROM usuario  ORDER BY usuario_nombre_completo ASC LIMIT $inicio,$registros";

				$consulta_total="SELECT COUNT(id_usuario) FROM usuario ";

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
		                    <th class="has-text-centered">Usuario</th>
                            <th class="has-text-centered">Rol</th>
		                    <th class="has-text-centered">Detalles</th>
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
							<td>'.$rows['usuario_nombre_completo'].'</td>
							<td>'.$rows['usuario_usuario'].'</td>
                            <td>'.$rows['usuario_rol'].'</td>
                            
							
			                <td>
			                    <a href="'.APP_URL.'userUpdate/'.$rows['id_usuario'].'/" class="button is-success is-rounded is-small">
			                    	<i class="fas fa-search fa-fw"></i>
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
								<div class="mt-1">
									<a href="'.APP_URL.'clientNew/" class="button is-success is-rounded is-small">Registrar cliente</a>
								</div>
			                </td>
			            </tr>
					';
				}
			}

			$tabla.='</tbody></table></div>';

			### Paginacion ###
			if($total>0 && $pagina<=$numeroPaginas){
				$tabla.='<p class="has-text-right">Mostrando usuarios <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

				$tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
			}

			return $tabla;
        }

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

        }
    }

?>