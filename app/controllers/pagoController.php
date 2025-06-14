<?php
    namespace app\controllers;
    use app\models\mainModel;

    class pagoController extends mainModel{

        public function registrarPagoVentaControlador(){
            $venta_codigo = $_POST['venta_codigo'];
            $venta_pago_fecha = date("Y-m-d");
            $venta_pago_hora = date("h:i a");
            $venta_pago_forma = $_POST['venta_pago_forma'];
            if($venta_pago_forma == ""){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No introdujo el metodo de pago",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
            $venta_pago_detalle = $_POST['venta_pago_detalle'];

            $check_venta = $this->ejecutarConsulta("SELECT * FROM venta WHERE venta_codigo ='$venta_codigo'");
            $datos_venta = $check_venta->fetch();
            $id_venta = $datos_venta['id_venta'];
            $venta_importe = $datos_venta['venta_importe'];
            
            $venta_pago_importe = $_POST['venta_pago_importe'];
            if($venta_pago_importe == ""){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No introdujo el importe",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            $caja=$_SESSION['caja'];
            $check_caja=$this->ejecutarConsulta("SELECT * FROM caja WHERE id_caja='$caja' ");
			$datos_caja=$check_caja->fetch();
            
            $cajaVentas = $datos_venta['id_caja'];

            $movimiento_cantidad = $venta_pago_importe;
            $movimiento_cantidad=number_format($movimiento_cantidad,MONEDA_DECIMALES,'.','');

            $total_caja=$datos_caja['caja_monto']+$movimiento_cantidad;

            $total_caja=number_format($total_caja,MONEDA_DECIMALES,'.','');


            $datos_pago = [
                [
                    "campo_nombre"=>"venta_pago_fecha",
                    "campo_marcador"=>":Fecha",
                    "campo_valor"=>$venta_pago_fecha
                ],
                [
                    "campo_nombre"=>"venta_pago_hora",
                    "campo_marcador"=>":Hora",
                    "campo_valor"=>$venta_pago_hora
                ],
                [
                    "campo_nombre"=>"venta_pago_forma",
                    "campo_marcador"=>":Forma",
                    "campo_valor"=>$venta_pago_forma
                ],
                [
                    "campo_nombre"=>"venta_pago_detalle",
                    "campo_marcador"=>":Detalle",
                    "campo_valor"=>$venta_pago_detalle
                ],
                [
                    "campo_nombre"=>"venta_pago_importe",
                    "campo_marcador"=>":Importe",
                    "campo_valor"=>$venta_pago_importe
                ],
                [
                    "campo_nombre"=>"venta_codigo",
                    "campo_marcador"=>":VentaCodigo",
                    "campo_valor"=>$venta_codigo
                ],
                [
                    "campo_nombre"=>"id_sucursal",
                    "campo_marcador"=>":Sucursal",
                    "campo_valor"=>$_SESSION['id_sucursal']
                ]
            ];
    
            $registrar_pago = $this->guardarDatos("pago_venta", $datos_pago);
            if ($registrar_pago->rowCount()==1) {
                $alerta=[
                    "tipo"=>"redireccionar",
                    "url"=>APP_URL."saleDetail/$venta_codigo"
                ];

            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo registrar el pago, por favor intente nuevamente",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
            
            //movimientos para la caja
            if ($venta_pago_forma == 'Efectivo') {
				// Update cash balance in "caja ventas"
				$datos_caja_up=[
					[
						"campo_nombre"=>"caja_monto",
						"campo_marcador"=>":Monto",
						"campo_valor"=>$total_caja
					]
				];
			
				$condicion_caja=[
					"condicion_campo"=>"id_caja",
					"condicion_marcador"=>":ID",
					"condicion_valor"=>$caja
				];
			
				$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja);
			
				// Update cash balance in "caja fisica" of the corresponding branch
				$sucursal_id = $datos_caja['id_sucursal'];
				$caja_fisica = $this->ejecutarConsulta("SELECT * FROM caja WHERE caja_codigo LIKE '%Efectivo%' AND id_sucursal = '$_SESSION[id_sucursal]'")->fetch();
				$total_caja_fisica=$caja_fisica['caja_monto']+$movimiento_cantidad;
            	$total_caja_fisica=number_format($total_caja_fisica,MONEDA_DECIMALES,'.','');
				if ($caja_fisica) {
					$datos_caja_fisica_up=[
						[
							"campo_nombre"=>"caja_monto",
							"campo_marcador"=>":Monto",
							"campo_valor"=>$total_caja_fisica
						]
					];
			
					$condicion_caja_fisica=[
						"condicion_campo"=>"id_caja",
						"condicion_marcador"=>":ID",
						"condicion_valor"=>$caja_fisica['id_caja']
					];
			
					$this->actualizarDatos("caja",$datos_caja_fisica_up,$condicion_caja_fisica);
				}
			} else {
				// Update cash balance in "caja ventas"
				$datos_caja_up=[
					[
						"campo_nombre"=>"caja_monto",
						"campo_marcador"=>":Monto",
						"campo_valor"=>$total_caja
					]
				];
			
				$condicion_caja=[
					"condicion_campo"=>"id_caja",
					"condicion_marcador"=>":ID",
					"condicion_valor"=>$caja
				];
			
				$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja);
			}

            if(!$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja)){

                $this->eliminarRegistro("venta_detalle","venta_codigo",$venta_codigo);
                $this->eliminarRegistro("venta","venta_codigo",$venta_codigo);

                foreach($_SESSION['datos_producto_venta'] as $producto){

                    $datos_producto_rs=[
                        [
							"campo_nombre"=>"articulo_stock",
							"campo_marcador"=>":Stock",
							"campo_valor"=>$producto['articulo_stock_total_old']
						]
                    ];

                    $condicion=[
                        "condicion_campo"=>"producto_id",
                        "condicion_marcador"=>":ID",
                        "condicion_valor"=>$producto['producto_id']
                    ];

                    $this->actualizarDatos("articulo",$datos_producto_rs,$condicion);
                }

                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido registrar la venta, por favor intente nuevamente. Código de error: 003",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();

            }

            return json_encode($alerta);
        }

        public function registrarPagoMixtoVentaControlador(){

            
            $venta_codigo = $_POST['venta_codigo'];
            $venta_pago_fecha = date("Y-m-d");
            $venta_pago_hora = date("h:i a");
            $venta_pago_forma = $_POST['venta_pago_forma'];
            $venta_pago_importe = $_POST['venta_pago_importe'];
            $venta_pago_detalle = $_POST['venta_pago_detalle'];

            for($i = 0; $i<count($_POST['venta_pago_forma']); $i++){
                $venta_pago_forma_2 = $venta_pago_forma[$i];
                $venta_pago_importe_2 = $venta_pago_importe[$i];
                $venta_pago_detalle_2 = $venta_pago_detalle[$i];
            
                $check_venta = $this->ejecutarConsulta("SELECT * FROM venta WHERE venta_codigo ='$venta_codigo'");
                $datos_venta = $check_venta->fetch();
                $id_venta = $datos_venta['id_venta'];
                $venta_importe = $datos_venta['venta_importe'];

                $caja=$_SESSION['caja'];
                $check_caja=$this->ejecutarConsulta("SELECT * FROM caja WHERE id_caja='$caja' ");
                $datos_caja=$check_caja->fetch();
                
                $cajaVentas = $datos_venta['id_caja'];

                $movimiento_cantidad = $venta_pago_importe_2;
                $movimiento_cantidad=number_format($movimiento_cantidad,MONEDA_DECIMALES,'.','');

                $total_caja=$datos_caja['caja_monto']+$movimiento_cantidad;

                $total_caja=number_format($total_caja,MONEDA_DECIMALES,'.','');


                $datos_pago = [
                    [
                        "campo_nombre"=>"venta_pago_fecha",
                        "campo_marcador"=>":Fecha",
                        "campo_valor"=>$venta_pago_fecha
                    ],
                    [
                        "campo_nombre"=>"venta_pago_hora",
                        "campo_marcador"=>":Hora",
                        "campo_valor"=>$venta_pago_hora
                    ],
                    [
                        "campo_nombre"=>"venta_pago_forma",
                        "campo_marcador"=>":Forma",
                        "campo_valor"=>$venta_pago_forma_2
                    ],
                    [
                        "campo_nombre"=>"venta_pago_detalle",
                        "campo_marcador"=>":Detalle",
                        "campo_valor"=>$venta_pago_detalle_2
                    ],
                    [
                        "campo_nombre"=>"venta_pago_importe",
                        "campo_marcador"=>":Importe",
                        "campo_valor"=>$venta_pago_importe_2
                    ],
                    [
                        "campo_nombre"=>"venta_codigo",
                        "campo_marcador"=>":VentaCodigo",
                        "campo_valor"=>$venta_codigo
                    ],
                    [
                        "campo_nombre"=>"id_sucursal",
                        "campo_marcador"=>":Sucursal",
                        "campo_valor"=>$_SESSION['id_sucursal']
                    ]
                ];
        
                $registrar_pago = $this->guardarDatos("pago_venta", $datos_pago);
                if ($registrar_pago->rowCount()==1) {
                    $alerta=[
                        "tipo"=>"redireccionar",
                        "url"=>APP_URL."saleDetail/$venta_codigo"
                    ];

                }else{
                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"No se pudo registrar el pago, por favor intente nuevamente",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
                
                //movimientos para la caja
                if ($venta_pago_forma_2 == 'Efectivo') {
                    // Update cash balance in "caja ventas"
                    $datos_caja_up=[
                        [
                            "campo_nombre"=>"caja_monto",
                            "campo_marcador"=>":Monto",
                            "campo_valor"=>$total_caja
                        ]
                    ];
                
                    $condicion_caja=[
                        "condicion_campo"=>"id_caja",
                        "condicion_marcador"=>":ID",
                        "condicion_valor"=>$caja
                    ];
                
                    $this->actualizarDatos("caja",$datos_caja_up,$condicion_caja);
                
                    // Update cash balance in "caja fisica" of the corresponding branch
                    $sucursal_id = $datos_caja['id_sucursal'];
                    $caja_fisica = $this->ejecutarConsulta("SELECT * FROM caja WHERE caja_codigo LIKE '%Efectivo%' AND id_sucursal = '$_SESSION[id_sucursal]'")->fetch();
                    $total_caja_fisica=$caja_fisica['caja_monto']+$movimiento_cantidad;
                    $total_caja_fisica=number_format($total_caja_fisica,MONEDA_DECIMALES,'.','');
                    if ($caja_fisica) {
                        $datos_caja_fisica_up=[
                            [
                                "campo_nombre"=>"caja_monto",
                                "campo_marcador"=>":Monto",
                                "campo_valor"=>$total_caja_fisica
                            ]
                        ];
                
                        $condicion_caja_fisica=[
                            "condicion_campo"=>"id_caja",
                            "condicion_marcador"=>":ID",
                            "condicion_valor"=>$caja_fisica['id_caja']
                        ];
                
                        $this->actualizarDatos("caja",$datos_caja_fisica_up,$condicion_caja_fisica);
                    }
                } else {
                    // Update cash balance in "caja ventas"
                    $datos_caja_up=[
                        [
                            "campo_nombre"=>"caja_monto",
                            "campo_marcador"=>":Monto",
                            "campo_valor"=>$total_caja
                        ]
                    ];
                
                    $condicion_caja=[
                        "condicion_campo"=>"id_caja",
                        "condicion_marcador"=>":ID",
                        "condicion_valor"=>$caja
                    ];
                
                    $this->actualizarDatos("caja",$datos_caja_up,$condicion_caja);
                }

                if(!$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja)){

                    $this->eliminarRegistro("venta_detalle","venta_codigo",$venta_codigo);
                    $this->eliminarRegistro("venta","venta_codigo",$venta_codigo);

                    foreach($_SESSION['datos_producto_venta'] as $producto){

                        $datos_producto_rs=[
                            [
                                "campo_nombre"=>"articulo_stock",
                                "campo_marcador"=>":Stock",
                                "campo_valor"=>$producto['articulo_stock_total_old']
                            ]
                        ];

                        $condicion=[
                            "condicion_campo"=>"producto_id",
                            "condicion_marcador"=>":ID",
                            "condicion_valor"=>$producto['producto_id']
                        ];

                        $this->actualizarDatos("articulo",$datos_producto_rs,$condicion);
                    }

                    $alerta=[
                        "tipo"=>"simple",
                        "titulo"=>"Ocurrió un error inesperado",
                        "texto"=>"No hemos podido registrar la venta, por favor intente nuevamente. Código de error: 003",
                        "icono"=>"error"
                    ];
                    return json_encode($alerta);
                    exit();

                }

                
            }
            return json_encode($alerta);
        }

        public function registrarPagoVentaEquipoControlador(){
            $venta_equipo_codigo = $_POST['venta_equipo_codigo'];
            $id_equipo = $_POST['id_equipo'];
            $venta_pago_equipo_fecha = $_POST['venta_pago_equipo_fecha'];
            $venta_pago_equipo_hora = date("h:i a");
            $venta_pago_equipo_forma = $_POST['venta_pago_equipo_forma'];
            if($venta_pago_equipo_forma == ""){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No introdujo el metodo de pago",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
            $venta_pago_equipo_detalle = $_POST['venta_pago_equipo_detalle'];

            $check_venta = $this->ejecutarConsulta("SELECT * FROM venta_equipo WHERE venta_equipo_codigo ='$venta_equipo_codigo'");
            $datos_venta = $check_venta->fetch();
            
            $venta_pago_equipo_importe = $_POST['venta_pago_equipo_importe'];
            if($venta_pago_equipo_importe == ""){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No introdujo el importe",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            $caja=$_SESSION['caja'];
            $check_caja=$this->ejecutarConsulta("SELECT * FROM caja WHERE id_caja='$caja' ");
			$datos_caja=$check_caja->fetch();

            $movimiento_cantidad = $venta_pago_equipo_importe;
            $movimiento_cantidad=number_format($movimiento_cantidad,MONEDA_DECIMALES,'.','');

            $total_caja=$datos_caja['caja_monto']+$movimiento_cantidad;

            $total_caja=number_format($total_caja,MONEDA_DECIMALES,'.','');


            $datos_pago = [
                [
                    "campo_nombre"=>"venta_pago_equipo_fecha",
                    "campo_marcador"=>":Fecha",
                    "campo_valor"=>$venta_pago_equipo_fecha
                ],
                [
                    "campo_nombre"=>"venta_pago_equipo_hora",
                    "campo_marcador"=>":Hora",
                    "campo_valor"=>$venta_pago_equipo_hora
                ],
                [
                    "campo_nombre"=>"venta_pago_equipo_forma",
                    "campo_marcador"=>":Forma",
                    "campo_valor"=>$venta_pago_equipo_forma
                ],
                [
                    "campo_nombre"=>"venta_pago_equipo_detalle",
                    "campo_marcador"=>":Detalle",
                    "campo_valor"=>$venta_pago_equipo_detalle
                ],
                [
                    "campo_nombre"=>"venta_pago_equipo_importe",
                    "campo_marcador"=>":Importe",
                    "campo_valor"=>$venta_pago_equipo_importe
                ],
                [
                    "campo_nombre"=>"venta_equipo_codigo",
                    "campo_marcador"=>":VentaCodigo",
                    "campo_valor"=>$venta_equipo_codigo
                ],
                [
                    "campo_nombre"=>"id_sucursal",
                    "campo_marcador"=>":Sucursal",
                    "campo_valor"=>$_SESSION['id_sucursal']
                ]
            ];
    
            $registrar_pago = $this->guardarDatos("pago_venta_equipo", $datos_pago);
            if ($registrar_pago->rowCount()==1) {
                $alerta=[
                    "tipo"=>"redireccionar",
                    "url"=>APP_URL."saleEquipoDetail/$id_equipo"
                ];

            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo registrar el pago, por favor intente nuevamente",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
            
            //movimientos para la caja
            if ($venta_pago_equipo_forma == 'Efectivo') {
				// Update cash balance in "caja ventas"
				$datos_caja_up=[
					[
						"campo_nombre"=>"caja_monto",
						"campo_marcador"=>":Monto",
						"campo_valor"=>$total_caja
					]
				];
			
				$condicion_caja=[
					"condicion_campo"=>"id_caja",
					"condicion_marcador"=>":ID",
					"condicion_valor"=>$caja
				];
			
				$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja);
			
				// Update cash balance in "caja fisica" of the corresponding branch
				$sucursal_id = $datos_caja['id_sucursal'];
				$caja_fisica = $this->ejecutarConsulta("SELECT * FROM caja WHERE caja_codigo LIKE '%Efectivo%' AND id_sucursal = '$_SESSION[id_sucursal]'")->fetch();
				$total_caja_fisica=$caja_fisica['caja_monto']+$movimiento_cantidad;
            	$total_caja_fisica=number_format($total_caja_fisica,MONEDA_DECIMALES,'.','');
				if ($caja_fisica) {
					$datos_caja_fisica_up=[
						[
							"campo_nombre"=>"caja_monto",
							"campo_marcador"=>":Monto",
							"campo_valor"=>$total_caja_fisica
						]
					];
			
					$condicion_caja_fisica=[
						"condicion_campo"=>"id_caja",
						"condicion_marcador"=>":ID",
						"condicion_valor"=>$caja_fisica['id_caja']
					];
			
					$this->actualizarDatos("caja",$datos_caja_fisica_up,$condicion_caja_fisica);
				}
			} else {
				// Update cash balance in "caja ventas"
				$datos_caja_up=[
					[
						"campo_nombre"=>"caja_monto",
						"campo_marcador"=>":Monto",
						"campo_valor"=>$total_caja
					]
				];
			
				$condicion_caja=[
					"condicion_campo"=>"id_caja",
					"condicion_marcador"=>":ID",
					"condicion_valor"=>$caja
				];
			
				$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja);
			}

            if(!$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja)){

                $this->eliminarRegistro("venta_equipo_detalle","venta_equipo_codigo",$venta_equipo_codigo);
                $this->eliminarRegistro("venta_equipo","venta_equipo_codigo",$venta_equipo_codigo);

                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido registrar la venta, por favor intente nuevamente. Código de error: 003",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();

            }

            return json_encode($alerta);
        }

        public function registrarPagoSenaEquipoControlador(){
            $id_sena = $_POST['id_sena'];
            $id_equipo = $_POST['id_equipo'];
            $sena_pago_fecha = $_POST['sena_pago_fecha'];
            $sena_pago_hora = date("h:i a");
            $sena_pago_forma = $_POST['sena_pago_forma'];
            if($sena_pago_forma == ""){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No introdujo el metodo de pago",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
            $sena_pago_detalle = $_POST['sena_pago_detalle'];

            $check_sena = $this->ejecutarConsulta("SELECT * FROM sena WHERE id_sena ='$id_sena'");
            $datos_sena = $check_sena->fetch();
            
            $sena_pago_importe = $_POST['sena_pago_importe'];
            if($sena_pago_importe == ""){
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No introdujo el importe",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }

            $caja=$_SESSION['caja'];
            $check_caja=$this->ejecutarConsulta("SELECT * FROM caja WHERE id_caja='$caja' ");
			$datos_caja=$check_caja->fetch();

            $movimiento_cantidad = $sena_pago_importe;
            $movimiento_cantidad=number_format($movimiento_cantidad,MONEDA_DECIMALES,'.','');

            $total_caja=$datos_caja['caja_monto']+$movimiento_cantidad;

            $total_caja=number_format($total_caja,MONEDA_DECIMALES,'.','');


            $datos_pago = [
                [
                    "campo_nombre"=>"sena_pago_fecha",
                    "campo_marcador"=>":Fecha",
                    "campo_valor"=>$sena_pago_fecha
                ],
                [
                    "campo_nombre"=>"sena_pago_hora",
                    "campo_marcador"=>":Hora",
                    "campo_valor"=>$sena_pago_hora
                ],
                [
                    "campo_nombre"=>"sena_pago_forma",
                    "campo_marcador"=>":Forma",
                    "campo_valor"=>$sena_pago_forma
                ],
                [
                    "campo_nombre"=>"sena_pago_detalle",
                    "campo_marcador"=>":Detalle",
                    "campo_valor"=>$sena_pago_detalle
                ],
                [
                    "campo_nombre"=>"sena_pago_importe",
                    "campo_marcador"=>":Importe",
                    "campo_valor"=>$sena_pago_importe
                ],
                [
                    "campo_nombre"=>"id_sena",
                    "campo_marcador"=>":IdSena",
                    "campo_valor"=>$id_sena
                ],
                [
                    "campo_nombre"=>"id_sucursal",
                    "campo_marcador"=>":Sucursal",
                    "campo_valor"=>$_SESSION['id_sucursal']
                ]
            ];
    
            $registrar_pago = $this->guardarDatos("pago_sena", $datos_pago);
            if ($registrar_pago->rowCount()==1) {
                $alerta=[
                    "tipo"=>"redireccionar",
                    "url"=>APP_URL."senaEquipoDetail/$id_equipo"
                ];

            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo registrar el pago, por favor intente nuevamente",
                    "icono"=>"error"
                ];
                return json_encode($alerta);
                exit();
            }
            
            //movimientos para la caja
            if ($sena_pago_forma == 'Efectivo') {
				// Update cash balance in "caja ventas"
				$datos_caja_up=[
					[
						"campo_nombre"=>"caja_monto",
						"campo_marcador"=>":Monto",
						"campo_valor"=>$total_caja
					]
				];
			
				$condicion_caja=[
					"condicion_campo"=>"id_caja",
					"condicion_marcador"=>":ID",
					"condicion_valor"=>$caja
				];
			
				$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja);
			
				// Update cash balance in "caja fisica" of the corresponding branch
				$sucursal_id = $datos_caja['id_sucursal'];
				$caja_fisica = $this->ejecutarConsulta("SELECT * FROM caja WHERE caja_codigo LIKE '%Efectivo%' AND id_sucursal = '$_SESSION[id_sucursal]'")->fetch();
				$total_caja_fisica=$caja_fisica['caja_monto']+$movimiento_cantidad;
            	$total_caja_fisica=number_format($total_caja_fisica,MONEDA_DECIMALES,'.','');
				if ($caja_fisica) {
					$datos_caja_fisica_up=[
						[
							"campo_nombre"=>"caja_monto",
							"campo_marcador"=>":Monto",
							"campo_valor"=>$total_caja_fisica
						]
					];
			
					$condicion_caja_fisica=[
						"condicion_campo"=>"id_caja",
						"condicion_marcador"=>":ID",
						"condicion_valor"=>$caja_fisica['id_caja']
					];
			
					$this->actualizarDatos("caja",$datos_caja_fisica_up,$condicion_caja_fisica);
				}
			} else {
				// Update cash balance in "caja ventas"
				$datos_caja_up=[
					[
						"campo_nombre"=>"caja_monto",
						"campo_marcador"=>":Monto",
						"campo_valor"=>$total_caja
					]
				];
			
				$condicion_caja=[
					"condicion_campo"=>"id_caja",
					"condicion_marcador"=>":ID",
					"condicion_valor"=>$caja
				];
			
				$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja);
			}

            return json_encode($alerta);
        }
        

        public function registrarPagoOrdenControlador(){
            $orden_codigo = $_POST['orden_codigo'];
            $orden_pago_fecha = $_POST['orden_pago_fecha'];
            $orden_pago_hora = date("h:i a");
            $orden_pago_forma = $_POST['orden_pago_forma'];
            $orden_pago_importe = $_POST['orden_pago_importe'];
            $orden_pago_detalle = $_POST['orden_pago_detalle'];

            $check_orden = $this->ejecutarConsulta("SELECT * FROM orden WHERE orden_codigo ='$orden_codigo'");
            $datos_orden = $check_orden->fetch();

            if($orden_pago_forma == "Efectivo"){
                $orden_importe = $datos_orden['orden_total_efectivo'];
            }else{
                $orden_importe = $datos_orden['orden_total_lista'];
            }

            

            $orden_saldo = $orden_importe - $orden_pago_importe;

            $caja=$_SESSION['caja'];
            $check_caja=$this->ejecutarConsulta("SELECT * FROM caja WHERE id_caja='$caja' ");
			$datos_caja=$check_caja->fetch();
            
            $cajaVentas = $datos_orden['id_caja'];

            $movimiento_cantidad = $orden_pago_importe;
            $movimiento_cantidad=number_format($movimiento_cantidad,MONEDA_DECIMALES,'.','');

            $total_caja=$datos_caja['caja_monto']+$movimiento_cantidad;

            $total_caja=number_format($total_caja,MONEDA_DECIMALES,'.','');


            $datos_pago = [
                [
                    "campo_nombre"=>"orden_pago_fecha",
                    "campo_marcador"=>":Fecha",
                    "campo_valor"=>$orden_pago_fecha
                ],
                [
                    "campo_nombre"=>"orden_pago_hora",
                    "campo_marcador"=>":Hora",
                    "campo_valor"=>$orden_pago_hora
                ],
                [
                    "campo_nombre"=>"orden_pago_forma",
                    "campo_marcador"=>":Forma",
                    "campo_valor"=>$orden_pago_forma
                ],
                [
                    "campo_nombre"=>"orden_pago_detalle",
                    "campo_marcador"=>":Detalle",
                    "campo_valor"=>$orden_pago_detalle
                ],
                [
                    "campo_nombre"=>"orden_pago_importe",
                    "campo_marcador"=>":Importe",
                    "campo_valor"=>$orden_pago_importe
                ],
                [
                    "campo_nombre"=>"orden_codigo",
                    "campo_marcador"=>":OrdenCodigo",
                    "campo_valor"=>$orden_codigo
                ],
                [
                    "campo_nombre"=>"id_sucursal",
                    "campo_marcador"=>":Sucursal",
                    "campo_valor"=>$_SESSION['id_sucursal']
                ]
            ];
    
            $registrar_pago = $this->guardarDatos("pago_orden", $datos_pago);
            if ($registrar_pago->rowCount()==1) {
                $alerta=[
                    "tipo"=>"recargar",
                    "titulo"=>"Pago registrado",
                    "texto"=>"El pago se registro con exito",
                    "icono"=>"success"
                ];
            }else{
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Ocurrió un error inesperado",
                    "texto"=>"No se pudo registrar el pago, por favor intente nuevamente",
                    "icono"=>"error"
                ];
            }
            
            //movimientos para la caja
            if ($orden_pago_forma == 'Efectivo') {
				// Update cash balance in "caja ventas"
				$datos_caja_up=[
					[
						"campo_nombre"=>"caja_monto",
						"campo_marcador"=>":Monto",
						"campo_valor"=>$total_caja
					]
				];
			
				$condicion_caja=[
					"condicion_campo"=>"id_caja",
					"condicion_marcador"=>":ID",
					"condicion_valor"=>$caja
				];
			
				$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja);
			
				// Update cash balance in "caja fisica" of the corresponding branch
				$sucursal_id = $datos_caja['id_sucursal'];
				$caja_fisica = $this->ejecutarConsulta("SELECT * FROM caja WHERE caja_codigo LIKE '%Efectivo%' AND id_sucursal = '$_SESSION[id_sucursal]'")->fetch();
				$total_caja_fisica=$caja_fisica['caja_monto']+$movimiento_cantidad;
            	$total_caja_fisica=number_format($total_caja_fisica,MONEDA_DECIMALES,'.','');
				if ($caja_fisica) {
					$datos_caja_fisica_up=[
						[
							"campo_nombre"=>"caja_monto",
							"campo_marcador"=>":Monto",
							"campo_valor"=>$total_caja_fisica
						]
					];
			
					$condicion_caja_fisica=[
						"condicion_campo"=>"id_caja",
						"condicion_marcador"=>":ID",
						"condicion_valor"=>$caja_fisica['id_caja']
					];
			
					$this->actualizarDatos("caja",$datos_caja_fisica_up,$condicion_caja_fisica);
				}
			} else {
				// Update cash balance in "caja ventas"
				$datos_caja_up=[
					[
						"campo_nombre"=>"caja_monto",
						"campo_marcador"=>":Monto",
						"campo_valor"=>$total_caja
					]
				];
			
				$condicion_caja=[
					"condicion_campo"=>"id_caja",
					"condicion_marcador"=>":ID",
					"condicion_valor"=>$caja
				];
			
				$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja);
			}

            if(!$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja)){

                $this->eliminarRegistro("orden_detalle","orden_codigo",$orden_codigo);
                $this->eliminarRegistro("orden","orden_codigo",$orden_codigo);

                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido registrar la venta, por favor intente nuevamente. Código de error: 003",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();

            }

            return json_encode($alerta);

        }

    }
?>