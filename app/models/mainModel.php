<?php
    namespace app\models;
    use \PDO;

    if(file_exists(__DIR__."/../../config/server.php")){
		require_once __DIR__."/../../config/server.php";
	}

    class mainModel{
        private $server = DB_SERVER;
        private $name = DB_NAME;
        private $user = DB_USER;
        private $pass = DB_PASS;

        /*--- CONECTAR DB ---*/
        protected function conectar(){
            $conexion = new PDO("mysql:host=".$this->server.";dbname=".$this->name,$this->user,$this->pass);
			$conexion->exec("SET CHARACTER SET utf8");
			return $conexion;
        }

        /*--- EJECUTAR CONSULTAS ---*/
        protected function ejecutarConsulta($consulta){
            $sql=$this->conectar()->prepare($consulta);
			$sql->execute();
			return $sql;
        }

		public function Consultar($consulta){
			$sql=$this->conectar()->prepare($consulta);
			$sql->execute();
			return $sql;
		}

        /*--- LIMPIAR CADENAS ---*/
        public function limpiarCadena($cadena){

			$palabras=["<script>","</script>","<script src","<script type=","SELECT * FROM","SELECT "," SELECT ","DELETE FROM","INSERT INTO","DROP TABLE","DROP DATABASE","TRUNCATE TABLE","SHOW TABLES","SHOW DATABASES","<?php","?>","--","^","==",";","::"];

			$cadena=trim($cadena);
			$cadena=stripslashes($cadena);

			foreach($palabras as $palabra){
				$cadena=str_ireplace($palabra, "", $cadena);
			}

			$cadena=trim($cadena);
			$cadena=stripslashes($cadena);

			return $cadena;
		}

        /*--- VERIFICAR PATTERN DE LOS DATOS ---*/
        protected function verificarDatos($filtro,$cadena){
			if(preg_match("/^".$filtro."$/", $cadena)){
				return false;
            }else{
                return true;
            }
		}

        /*----------  EJECUTAR INSERT  ----------*/
		protected function guardarDatos($tabla,$datos){

			$query="INSERT INTO $tabla (";

			$C=0;
			foreach ($datos as $clave){
				if($C>=1){ $query.=","; }
				$query.=$clave["campo_nombre"];
				$C++;
			}
			
			$query.=") VALUES(";

			$C=0;
			foreach ($datos as $clave){
				if($C>=1){ $query.=","; }
				$query.=$clave["campo_marcador"];
				$C++;
			}

			$query.=")";
			$sql=$this->conectar()->prepare($query);

			foreach ($datos as $clave){
				$sql->bindParam($clave["campo_marcador"],$clave["campo_valor"]);
			}

			$sql->execute();

			return $sql;
		}

		/* cargar modelos por marca en el select de las ordenes */
		public function cargarModelosPorMarca($marcaId) {
			// Asegúrate de que la consulta esté bien formada
			$datos_modelo = $this->seleccionarDatos("Normal", "modelo", "*", 0);
		
			$modelos = [];
			while ($campos_modelo = $datos_modelo->fetch()) {
				// Filtra los modelos según la marca
				if ($campos_modelo['id_marca'] == $marcaId) {
					$modelos[] = [
						'id_modelo' => $campos_modelo['id_modelo'],
						'modelo_descripcion' => $campos_modelo['modelo_descripcion']
					];
				}
			}
			
			// Ordenar alfabéticamente por 'modelo_descripcion'
			usort($modelos, function ($a, $b) {
				return strcmp($a['modelo_descripcion'], $b['modelo_descripcion']);
			});
		
			return $modelos;
		}


		/*---------- SELECCIONAR DATOS ----------*/
        public function seleccionarDatos($tipo,$tabla,$campo,$id){
			$tipo=$this->limpiarCadena($tipo);
			$tabla=$this->limpiarCadena($tabla);
			$campo=$this->limpiarCadena($campo);
			$id=$this->limpiarCadena($id);

            if($tipo=="Unico"){
                $sql=$this->conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:ID");
                $sql->bindParam(":ID",$id);
            }elseif($tipo=="Normal"){
                $sql=$this->conectar()->prepare("SELECT $campo FROM $tabla");
            }
            $sql->execute();

            return $sql;
		}

		/*---------- SELECCIONAR DATOS ESPECIFICOS ----------*/
        public function seleccionarDatosEspecificos($tabla,$campo,$condicion){
			$tabla=$this->limpiarCadena($tabla);
			$campo=$this->limpiarCadena($campo);
			$condicion=$this->limpiarCadena($condicion);

            $sql=$this->conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:condicion");
            $sql->bindParam(":condicion",$condicion);
            
            $sql->execute();

            return $sql;
		}

		/*---------- SELECCIONAR DATOS ESPECIFICOS ----------*/
        public function seleccionarDatosFechaHoy($tabla,$sucursal){
			$tabla=$this->limpiarCadena($tabla);
			$sucursal=$this->limpiarCadena($sucursal);

            $sql=$this->conectar()->prepare("SELECT * FROM ventas WHERE ");
            $sql->bindParam(":condicion",$condicion);
            
            $sql->execute();

            return $sql;
		}

		/*---------- FILTRAR POR SUCURSAL --------- */
        public function seleccionarDatosSucursal($tipo,$tabla,$campo,$id, $id_sucursal){
			$tipo=$this->limpiarCadena($tipo);
			$tabla=$this->limpiarCadena($tabla);
			$campo=$this->limpiarCadena($campo);
			$id=$this->limpiarCadena($id);
			$id_sucursal=$this->limpiarCadena($id_sucursal);

            if($tipo=="Unico"){
                $sql=$this->conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:ID AND $id_sucursal=:IDSUCURSAL");
                $sql->bindParam(":ID",$id);
				$sql->bindParam(":IDSUCURSAL",$id_sucursal);
            }elseif($tipo=="Normal"){
                $sql=$this->conectar()->prepare("SELECT $campo FROM $tabla WHERE id_sucursal=:IDSUCURSAL");
				$sql->bindParam(":IDSUCURSAL", $id_sucursal);
            }
            $sql->execute();

            return $sql;
		}

		/*FILTRAR CADA CAJA FISICA DE CADA SUCURSAL */
		public function seleccionarCajaFisicaSucursal($tipo,$tabla,$campo,$id, $id_sucursal, $condicion = ""){
			$tipo=$this->limpiarCadena($tipo);
			$tabla=$this->limpiarCadena($tabla);
			$campo=$this->limpiarCadena($campo);
			$id=$this->limpiarCadena($id);
			$id_sucursal=$this->limpiarCadena($id_sucursal);

            if($tipo=="Unico"){
                $sql=$this->conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:ID AND $id_sucursal=:IDSUCURSAL");
                $sql->bindParam(":ID",$id);
				$sql->bindParam(":IDSUCURSAL",$id_sucursal);
            }elseif($tipo=="Normal"){
				$sql=$this->conectar()->prepare("SELECT $campo FROM $tabla WHERE id_sucursal=:IDSUCURSAL AND $condicion");
				$sql->bindParam(":IDSUCURSAL", $id_sucursal);
			}
            $sql->execute();

            return $sql;
		}
		
		/*----------  EJECUTAR UN UPDATE  ----------*/
		protected function actualizarDatos($tabla,$datos,$condicion){

			$query="UPDATE $tabla SET ";

			$C=0;
			foreach ($datos as $clave){
				if($C>=1){ $query.=","; }
				$query.=$clave["campo_nombre"]."=".$clave["campo_marcador"];
				$C++;
			}

			$query.=" WHERE ".$condicion["condicion_campo"]."=".$condicion["condicion_marcador"];

			$sql=$this->conectar()->prepare($query);

			foreach ($datos as $clave){
				$sql->bindParam($clave["campo_marcador"],$clave["campo_valor"]);
			}

			$sql->bindParam($condicion["condicion_marcador"],$condicion["condicion_valor"]);

			$sql->execute();

			return $sql;
		}


		/*---------- ELIMINAR REGISTRO ----------*/
        protected function eliminarRegistro($tabla,$campo,$id){
            $sql=$this->conectar()->prepare("DELETE FROM $tabla WHERE $campo=:id");
            $sql->bindParam(":id",$id);
            $sql->execute();
            
            return $sql;
        }


		/*---------- Paginador de tablas ----------*/
		protected function paginadorTablas($pagina,$numeroPaginas,$url,$botones){
	        $tabla='<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

	        if($pagina<=1){
	            $tabla.='
	            <a class="pagination-previous is-disabled" disabled ><i class="fas fa-arrow-alt-circle-left"></i> &nbsp; Anterior</a>
	            <ul class="pagination-list">
	            ';
	        }else{
	            $tabla.='
	            <a class="pagination-previous" href="'.$url.($pagina-1).'/"><i class="fas fa-arrow-alt-circle-left"></i> &nbsp; Anterior</a>
	            <ul class="pagination-list">
	                <li><a class="pagination-link" href="'.$url.'1/">1</a></li>
	                <li><span class="pagination-ellipsis">&hellip;</span></li>
	            ';
	        }


	        $ci=0;
	        for($i=$pagina; $i<=$numeroPaginas; $i++){

	            if($ci>=$botones){
	                break;
	            }

	            if($pagina==$i){
	                $tabla.='<li><a class="pagination-link is-current" href="'.$url.$i.'/">'.$i.'</a></li>';
	            }else{
	                $tabla.='<li><a class="pagination-link" href="'.$url.$i.'/">'.$i.'</a></li>';
	            }

	            $ci++;
	        }


	        if($pagina==$numeroPaginas){
	            $tabla.='
	            </ul>
	            <a class="pagination-next is-disabled" disabled ><i class="fas fa-arrow-alt-circle-right"></i> &nbsp; Siguiente</a>
	            ';
	        }else{
	            $tabla.='
	                <li><span class="pagination-ellipsis">&hellip;</span></li>
	                <li><a class="pagination-link" href="'.$url.$numeroPaginas.'/">'.$numeroPaginas.'</a></li>
	            </ul>
	            <a class="pagination-next" href="'.$url.($pagina+1).'/"><i class="fas fa-arrow-alt-circle-right"></i> &nbsp; Siguiente</a>
	            ';
	        }

	        $tabla.='</nav>';
	        return $tabla;
	    }


	    /*----------  Funcion generar select ----------*/
		public function generarSelect($datos,$campo_db){
			$check_select='';
			$text_select='';
			$count_select=1;
			$select='';
			foreach($datos as $row){

				if($campo_db==$row){
					$check_select='selected=""';
					$text_select=' (Actual)';
				}

				$select.='<option value="'.$row.'" '.$check_select.'>'.$row.$text_select.'</option>';

				$check_select='';
				$text_select='';
				$count_select++;
			}
			return $select;
		}

		/*----------  GENERAR CODIGOS  ----------*/
		protected function generarCodigoAleatorio($longitud,$correlativo){
			$codigo="";
			$caracter="Letra";
			for($i=1; $i<=$longitud; $i++){
				if($caracter=="Letra"){
					$letra_aleatoria=chr(rand(ord("a"),ord("z")));
					$letra_aleatoria=strtoupper($letra_aleatoria);
					$codigo.=$letra_aleatoria;
					$caracter="Numero";
				}else{
					$numero_aleatorio=rand(0,9);
					$codigo.=$numero_aleatorio;
					$caracter="Letra";
				}
			}
			return $codigo."-".$correlativo;
		}


		/*----------  LIMPIAR CADENAS  ----------*/
		public function limitarCadena($cadena,$limite,$sufijo){
			if(strlen($cadena)>$limite){
				return substr($cadena,0,$limite).$sufijo;
			}else{
				return $cadena;
			}
		}


		/*---------- VERIFICAR FECHAS ----------*/
		public function verificarFecha($fecha){
			$valores=explode('-',$fecha);
			if(count($valores)==3 && checkdate($valores[1], $valores[2], $valores[0])){
				return false;
			}else{
				return true;
			}
		}
    }



?>