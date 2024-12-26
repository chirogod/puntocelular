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
        $equipo = $this->seleccionarDatos("Normal", "equipo", "id_equipo", $id_equipo);
        $equipo = $equipo->fetch();
        $sena_vendedor = $this->limpiarCadena($_POST['sena_vendedor']);

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

        if($sena_vendedor == ""){
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
}


?>