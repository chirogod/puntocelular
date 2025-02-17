<?php

    namespace app\controllers;
    use app\models\mainModel;

    class loginController extends mainModel{

        public function iniciarSesionControlador(){
            $usuario = $this->limpiarCadena($_POST['login_usuario']);
            $clave = $this->limpiarCadena($_POST['login_clave']);
            $sucursal = $this->limpiarCadena($_POST['sucursal']);

            if ($usuario == "" && $clave == "") {
                echo '
                    <article class="message is-danger">
				        <div class="message-body">
				            <strong>Ocurrió un error inesperado</strong><br>
				            No has llenado todos los campos que son obligatorios
				        </div>
				    </article>;
                ';
            }else{
                if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $usuario)){
                    echo '
                        <article class="message is-danger">
                            <div class="message-body">
                                <strong>Ocurrió un error inesperado</strong><br>
                               El usuario no cumple con el formato requerido
                            </div>
                        </article>;
                    ';  
                }else{
                    if ($this->verificarDatos("[a-zA-Z0-9$@.-]{3,100}", $clave)){
                        echo '
                            <article class="message is-danger">
                                <div class="message-body">
                                    <strong>Ocurrió un error inesperado</strong><br>
                                La clave no cumple con el formato requerido
                                </div>
                            </article>;
                        ';  
                    }else{
                        $check_usuario = $this->ejecutarConsulta("SELECT * FROM usuario WHERE usuario_usuario = '$usuario'");
                        if ($check_usuario->rowCount()==1) {
                            $check_usuario = $check_usuario->fetch();
                            if ($check_usuario['usuario_usuario'] == $usuario && $check_usuario['usuario_clave'] == $clave) {
                                $_SESSION['id_usuario'] = $check_usuario['id_usuario'];
                                $_SESSION['usuario_nombre'] = $check_usuario['usuario_nombre_completo'];
                                $_SESSION['usuario_email'] = $check_usuario['usuario_email'];
                                $_SESSION['usuario_telefono'] = $check_usuario['usuario_telefono'];
                                $_SESSION['usuario_dni'] = $check_usuario['usuario_dni'];
                                $_SESSION['usuario_nacimiento'] = $check_usuario['usuario_nacimiento'];
                                $_SESSION['usuario_usuario'] = $check_usuario['usuario_usuario'];
                                $_SESSION['usuario_rol'] = $check_usuario['usuario_rol'];
                                

                                $check_sucursal = $this->ejecutarConsulta("SELECT * FROM sucursal WHERE id_sucursal = '$sucursal'");
                                $check_sucursal = $check_sucursal->fetch();
                                if ($check_sucursal['id_sucursal'] == $sucursal) {
                                    $_SESSION['id_sucursal'] = $check_sucursal['id_sucursal'];
                                    $_SESSION['sucursal_descripcion'] = $check_sucursal['sucursal_descripcion'];
                                    $_SESSION['sucursal_direccion'] = $check_sucursal['sucursal_direccion'];
                                    $_SESSION['sucursal_telefono'] = $check_sucursal['sucursal_telefono'];
                                    $_SESSION['sucursal_email'] = $check_sucursal['sucursal_email'];
                                    $_SESSION['sucursal_pie_nota'] = $check_sucursal['sucursal_pie_nota'];
                                    $_SESSION['sucursal_pie_comprobante'] = $check_sucursal['sucursal_pie_comprobante'];
                                    $_SESSION['sucursal_firma_email'] = $check_sucursal['sucursal_firma_email'];
                                    $_SESSION['usd_pc'] = $check_sucursal['sucursal_usd'];
                                    $_SESSION['costo_operativo_hora'] = $check_sucursal['sucursal_costo_operativo_hora'];
                                }else{
                                    echo '
                                        <article class="message is-danger">
                                            <div class="message-body">
                                                <strong>Ocurrió un error inesperado</strong><br>
                                                La sucursal no existe
                                            </div>
                                        </article>;
                                    ';  
                                }

                                $check_caja = $this->ejecutarConsulta("SELECT * FROM caja WHERE id_sucursal = '$sucursal'");
                                $check_caja = $check_caja->fetch();
                                if ($check_caja['id_caja']) {
                                    $_SESSION['caja'] = $check_caja['id_caja'];
                                }

                                if (headers_sent()) {
                                    echo "<script> window.location.href='".APP_URL."dashboard/' ; </script>";
                                }else {
                                    header("Location: ".APP_URL."dashboard/ ");
                                }
                            }else{
					    		echo '<article class="message is-danger">
								  <div class="message-body">
								    <strong>Ocurrió un error inesperado</strong><br>
								    Usuario o clave incorrectos
								  </div>
								</article>';
					    	}
                        }else{
                            echo '<article class="message is-danger">
                              <div class="message-body">
                                <strong>Ocurrió un error inesperado</strong><br>
                                Usuario o clave incorrectos
                              </div>
                            </article>';
                        }
                    }
                }
            }

        }

        
        public function cerrarSesionControlador(){
            session_destroy();
            if (headers_sent()) {
                echo "<script> window.location.href='".APP_URL."login/' ; </script>";
                exit();
            }else {
                header("Location: ".APP_URL."login/ ");
                exit();
            }
            exit();
        }

    }





?>