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
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
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
    }

?>