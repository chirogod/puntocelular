<?php

    namespace app\models;

    class viewsModel{

        public function obtenerVistasModelo($vista){
            //lista de las vistas que si existen
            $listaBlanca = [
                "dashboard",
                "logOut",
                "userNew",
                "userList",
                "userSearch",
                "userUpdate",
                "clientNew",
                "clientList",
                "clientSearch",
                "clientUpdate",
                "rubroNew",
                "artNew",
                "artList",
                "artSearch",
                "artRub",
                "artUpdate",
                "saleNew",
                "saleSearch",
                "saleDetail",
                "saleList"
            ];
            //si la $vista esta en el array de las vistas que existe
            if (in_array($vista,$listaBlanca)){
                //y si existe ese archivo con un -view.php adelante
                if (is_file("app/views/content/".$vista."-view.php")) {
                    //el contenido que se debe cargar es toda esa ruta
                    $contenido = "app/views/content/".$vista."-view.php";
                }else {
                    //sino se carga 404 que es q no se encontro la vista
                    $contenido = "404";
                }
            //o si la vista es login o index
            }elseif ($vista == "login" || $vista == "index") {
                //se debe cargar el contenido del login
                $contenido = "login";
            //sino no existe
            }else {
                $contenido = "404";
            }
            
            return $contenido;
        }

    }

?>