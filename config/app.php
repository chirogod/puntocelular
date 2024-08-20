<?php

	const APP_URL="http://localhost/puntocelular/";
	const APP_NAME="GESTION";
	const APP_SESSION_NAME="GESTION";

	/*----------  Tipos de documentos  ----------*/
	const DOCUMENTOS_USUARIOS=["DNI","Cedula","Licencia","Pasaporte","Otro"];

	/*----------  Configuración de moneda  ----------*/
	const MONEDA_SIMBOLO="$";
	const MONEDA_NOMBRE="ARS";
	const MONEDA_DECIMALES="2";
	const MONEDA_SEPARADOR_MILLAR=".";
	const MONEDA_SEPARADOR_DECIMAL=",";

	/*---------- Formas de pago ----------*/
	const FORMAS_PAGO = ["Efectivo, Transferencia"];


	/*----------  Marcador de campos obligatorios (Font Awesome) ----------*/
	const CAMPO_OBLIGATORIO='&nbsp; <i class="fas fa-edit"></i> &nbsp;';

	/*----------  Zona horaria  ----------*/
	date_default_timezone_set("America/Argentina/Buenos_Aires");

?>