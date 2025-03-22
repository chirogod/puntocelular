<?php
namespace app\controllers;
use app\models\mainModel;

class senaController extends mainModel{

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

        if($_SESSION['datos_cliente_sena']['id_cliente']==1){
            $_SESSION['datos_cliente_sena']=[
                "id_cliente"=>$campos['id_cliente'],
                "cliente_tipo_doc"=>$campos['cliente_tipo_doc'],
                "cliente_documento"=>$campos['cliente_documento'],
                "cliente_nombre_completo"=>$campos['cliente_nombre_completo']
            ];

            $alerta=[
                "tipo"=>"redireccionar",
                "url"=>APP_URL."senaEquipoNew/".$id_equipo
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

        unset($_SESSION['datos_cliente_sena']);

        if(empty($_SESSION['datos_cliente_sena'])){
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

    /*---------- Controlador registrar sena ----------*/
    public function registrarSenaControlador(){
        $id_equipo = $this->limpiarCadena($_POST['id_equipo']);
        /*== Comprobando equipo en la DB ==*/
        $equipo = $this->seleccionarDatos("Unico", "equipo", "id_equipo", $id_equipo);
        $equipo = $equipo->fetch();

        /*== Comprobando cliente en la DB ==*/
        $id_usuario = $this->limpiarCadena($_POST['sena_vendedor']);
        $check_usuario = $this->ejecutarConsulta("SELECT * FROM usuario WHERE id_usuario = '$id_usuario'");
        if ($check_usuario->rowCount() > 0) {
            // si ya existe generar otro
            $usuario=$check_usuario->fetch();
            $sena_vendedor = $usuario['usuario_nombre_completo'];
        }


        $caja=$_SESSION['caja'];

        if(!isset($_SESSION['datos_cliente_sena'])){
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
        $check_cliente=$this->ejecutarConsulta("SELECT id_cliente FROM cliente WHERE id_cliente='".$_SESSION['datos_cliente_sena']['id_cliente']."'");
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

        if($id_usuario == ""){
            $alerta=[
                "tipo"=>"simple",
                "titulo"=>"Ocurrió un error inesperado",
                "texto"=>"No se ha ingresado el vendedor!",
                "icono"=>"error"
            ];
            return json_encode($alerta);
            exit();
        }


       
        $sena_fecha=date("Y-m-d");
        $sena_hora=date("h:i a");

        /* REGISTRAANDO IMPORTES SENA */
        $sena_ars = $_POST['sena_ars'];
        $sena_usd = $_POST['sena_usd'];
        $sena_pcp = $_POST['sena_pcp'];
        $sena_pcu = $_POST['sena_pcu'];

        /*== Preparando datos para enviarlos al modelo ==*/
        $datos_sena_equipo=[
            [
                "campo_nombre"=>"sena_fecha",
                "campo_marcador"=>":Fecha",
                "campo_valor"=>$sena_fecha
            ],
            [
                "campo_nombre"=>"sena_hora",
                "campo_marcador"=>":Hora",
                "campo_valor"=>$sena_hora
            ],
            [
                "campo_nombre"=>"sena_ars",
                "campo_marcador"=>":Ars",
                "campo_valor"=>$sena_ars
            ],
            [
                "campo_nombre"=>"sena_usd",
                "campo_marcador"=>":Usd",
                "campo_valor"=>$sena_usd
            ],
            [
                "campo_nombre"=>"sena_pcp",
                "campo_marcador"=>":Pcp",
                "campo_valor"=>$sena_pcp
            ],
            [
                "campo_nombre"=>"sena_pcu",
                "campo_marcador"=>":Pcu",
                "campo_valor"=>$sena_pcu
            ],
            [
                "campo_nombre"=>"id_equipo",
                "campo_marcador"=>":Equipo",
                "campo_valor"=>$id_equipo
            ],
            [
                "campo_nombre"=>"sena_vendedor",
                "campo_marcador"=>":Vendedor",
                "campo_valor"=>$sena_vendedor
            ],
            [
                "campo_nombre"=>"id_sucursal",
                "campo_marcador"=>":Sucursal",
                "campo_valor"=>$_SESSION['id_sucursal']
            ],
            [
                "campo_nombre"=>"id_cliente",
                "campo_marcador"=>":Cliente",
                "campo_valor"=>$_SESSION['datos_cliente_sena']['id_cliente']
            ],
            [
                "campo_nombre"=>"id_caja",
                "campo_marcador"=>":Caja",
                "campo_valor"=>$caja
            ]
        ];
        /*== Agregando sena ==*/
        $agregar_sena=$this->guardarDatos("sena",$datos_sena_equipo);

        // SI LA SENIA ES DE UN EQUIPO DE PREVENTA, QUE SE LISTE EN PEDIDO DE EQUIPOS.

        $pedido_equipo_estado = 'espera';
        $pedido_equipo_marca = $equipo['equipo_marca'];
        $pedido_equipo_modelo = $equipo['equipo_modelo'];
        $pedido_equipo_almacenamiento = $equipo['equipo_almacenamiento'];
        $pedido_equipo_ram = $equipo['equipo_ram'];
        $pedido_equipo_bateria = $equipo['equipo_bateria'];
        $pedido_equipo_color = $equipo['equipo_color'];
        $pedido_equipo_modulo = $equipo['equipo_modulo'];
        $pedido_equipo_hora = date("H:i:s");
        $pedido_equipo_fecha = date("Y-m-d");
        $pedido_equipo_responsable = $sena_vendedor;

        if($equipo['equipo_modulo'] == 'android_prev' || $equipo['equipo_modulo'] == 'apple_nuevo_prev' || $equipo['equipo_modulo'] == 'apple_reac_prev'){
            $datos_equipo = [
                [
                    "campo_nombre"=>"pedido_equipo_estado",
                    "campo_marcador"=>":Estado",
                    "campo_valor"=>$pedido_equipo_estado
                ],
                [
                    "campo_nombre"=>"pedido_equipo_marca",
                    "campo_marcador"=>":Marca",
                    "campo_valor"=>$pedido_equipo_marca
                ],
                [
                    "campo_nombre"=>"pedido_equipo_modelo",
                    "campo_marcador"=>":Modelo",
                    "campo_valor"=>$pedido_equipo_modelo
                ],
                [
                    "campo_nombre"=>"pedido_equipo_almacenamiento",
                    "campo_marcador"=>":Almacenamiento",
                    "campo_valor"=>$pedido_equipo_almacenamiento
                ],
                [
                    "campo_nombre"=>"pedido_equipo_ram",
                    "campo_marcador"=>":Ram",
                    "campo_valor"=>$pedido_equipo_ram
                ],
                [
                    "campo_nombre"=>"pedido_equipo_bateria",
                    "campo_marcador"=>":Bateria",
                    "campo_valor"=>$pedido_equipo_bateria
                ],
                [
                    "campo_nombre"=>"pedido_equipo_color",
                    "campo_marcador"=>":Color",
                    "campo_valor"=>$pedido_equipo_color
                ],
                [
                    "campo_nombre"=>"id_sucursal",
                    "campo_marcador"=>":Sucursal",
                    "campo_valor"=>$_SESSION['id_sucursal']
                ],
                [
                    "campo_nombre"=>"pedido_equipo_modulo",
                    "campo_marcador"=>":Modulo",
                    "campo_valor"=>$pedido_equipo_modulo
                ],
                [
                    "campo_nombre"=>"pedido_equipo_fecha",
                    "campo_marcador"=>":Fecha",
                    "campo_valor"=>$pedido_equipo_fecha
                ],
                [
                    "campo_nombre"=>"pedido_equipo_hora",
                    "campo_marcador"=>":Hora",
                    "campo_valor"=>$pedido_equipo_hora
                ],
                [
                    "campo_nombre"=>"pedido_equipo_responsable",
                    "campo_marcador"=>":Responsable",
                    "campo_valor"=>$pedido_equipo_responsable
                ]
            ];
    
            $registrar_equipo = $this->guardarDatos("pedido_equipo", $datos_equipo);
        }

        if($agregar_sena->rowCount()==1){
            /* PONER EL EQUIPO EN ESTADO DE VENDIDO */
            $reservado = "Reservado";
            $datos_equipo_up=[
                [
                    "campo_nombre"=>"equipo_estado",
                    "campo_marcador"=>":Estado",
                    "campo_valor"=>$reservado
                ]
            ];

            $condicion=[
                "condicion_campo"=>"id_equipo",
                "condicion_marcador"=>":ID",
                "condicion_valor"=>$id_equipo
            ];

            $this->actualizarDatos("equipo",$datos_equipo_up,$condicion);
            unset($_SESSION['datos_cliente_sena']);
            unset($_SESSION['financiacion_equipo']);


            $alerta=[
                "tipo"=>"redireccionar",
                "url"=>APP_URL."senaEquipoDetail/".$id_equipo
            ];
        
        }
        return json_encode($alerta);
        exit();
    }

    /*----------  Controlador listar sena  ----------*/
		public function listarSenaControlador($pagina,$registros,$url,$busqueda){

			$pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);

			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			$campos_tablas = "sena.id_sena, sena.sena_fecha, sena.sena_hora, sena.id_equipo, sena.sena_vendedor ,  sena.id_cliente, cliente.id_cliente, cliente.cliente_nombre_completo, sena.id_caja, sena.sena_ars, sena.sena_usd, sena.sena_pcp, sena_pcu, sena_fecha_entrega, equipo.id_equipo, equipo.equipo_codigo, equipo.equipo_estado, equipo.equipo_marca, equipo.equipo_modelo, equipo.equipo_almacenamiento, equipo.equipo_ram, equipo.equipo_bateria, equipo.equipo_color, equipo.equipo_costo, equipo.equipo_imei, equipo.equipo_modulo ";

			if(isset($busqueda) && $busqueda!=""){

				$consulta_datos="SELECT $campos_tablas 
								FROM sena 
								INNER JOIN cliente ON sena.id_cliente=cliente.id_cliente 
								INNER JOIN equipo ON sena.id_equipo=equipo.id_equipo 
								INNER JOIN caja ON sena.id_caja=caja.id_caja 
								WHERE 
									sena.id_sena LIKE '%$busqueda%'  
									OR cliente.cliente_nombre_completo LIKE '%$busqueda%' 
									OR equipo.equipo_marca LIKE '%$busqueda%' 
                                    OR equipo.equipo_modelo LIKE '%$busqueda%' 
									OR caja.caja_nombre LIKE '%$busqueda%' 
									AND sena.id_sucursal = '$_SESSION[id_sucursal]'
								ORDER BY sena.id_sena DESC LIMIT $inicio,$registros";
			
				$consulta_total="SELECT COUNT(id_sena) 
								FROM sena 
								INNER JOIN cliente ON sena.id_cliente=cliente.id_cliente 
								INNER JOIN equipo ON sena.id_equipo=equipo.id_equipo 
								INNER JOIN caja ON sena.id_caja=caja.id_caja 
								WHERE 
									sena.id_sena LIKE '%$busqueda%'  
									OR cliente.cliente_nombre_completo LIKE '%$busqueda%' 
									OR equipo.equipo_marca LIKE '%$busqueda%' 
                                    OR equipo.equipo_modelo LIKE '%$busqueda%' 
									OR caja.caja_nombre LIKE '%$busqueda%' 
									AND sena.id_sucursal = '$_SESSION[id_sucursal]'";
			
			}else{
			
				$consulta_datos="SELECT $campos_tablas 
								FROM sena 
								INNER JOIN cliente ON sena.id_cliente=cliente.id_cliente 
								INNER JOIN equipo ON sena.id_equipo=equipo.id_equipo 
								INNER JOIN caja ON sena.id_caja=caja.id_caja  
								WHERE sena.id_sucursal = '$_SESSION[id_sucursal]'
								ORDER BY sena.id_sena DESC LIMIT $inicio,$registros";
			
				$consulta_total="SELECT COUNT(id_sena) 
								FROM sena 
								INNER JOIN cliente ON sena.id_cliente=cliente.id_cliente 
								INNER JOIN equipo ON sena.id_equipo=equipo.id_equipo 
								INNER JOIN caja ON sena.id_caja=caja.id_caja 
								WHERE sena.id_sucursal = '$_SESSION[id_sucursal]'";
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
		                    <th class="has-text-centered">Fecha</th>
                            <th class="has-text-centered">Equipo</th>
		                    <th class="has-text-centered">Cliente</th>
		                </tr>
		            </thead>
		            <tbody>
		    ';

		    if($total>=1 && $pagina<=$numeroPaginas){
				$contador=$inicio+1;
				$pag_inicio=$inicio+1;
				foreach($datos as $rows){
					$tabla.='
						<tr class="has-text-centered" style="cursor: pointer;" onclick="window.location.href=\'' . APP_URL . 'senaEquipoDetail/' . $rows['id_equipo'] . '/\'">
							<td>'.$rows['id_sena'].'</td>
							<td>'.date("d-m-Y", strtotime($rows['sena_fecha'])).' '.$rows['sena_hora'].'</td>
							<td>'.$rows['equipo_marca'].' ' . $rows['equipo_modelo'] .' '.$rows['equipo_almacenamiento'].' ' . $rows['equipo_color'] .'</td>
							<td>'.$rows['cliente_nombre_completo'].'</td>
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
				$tabla.='<p class="has-text-right">Mostrando senas <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

				$tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
			}

			return $tabla;
		}
}


?>