<?php
    namespace app\controllers;
    use app\models\viewsModel;

    class viewsController extends viewsModel{
        /*--- obtener una vista segun la $vista que se le pasa ---*/
        public function obtenerVistasControlador($vista){
            //si la vista no esta vacia
            if ($vista != "") {
                //la respuesta sera obtener la vista con el modelo
                $respuesta = $this->obtenerVistasModelo($vista);
            }else {
                //sino la vista sera login
                $respuesta = "login";
            }
            
            return $respuesta;
        }
    }


?>