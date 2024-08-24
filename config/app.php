<?php

	const APP_URL="http://localhost/puntocelular/";
	const APP_NAME="GESTION";
	const APP_SESSION_NAME="GESTION";

	/*----------  Tipos de documentos  ----------*/
	const DOCUMENTOS=["DNI","Cedula","Licencia","Pasaporte","Otro"];

	/*----------  Localidades  ----------*/
	const LOCALIDADES=["Lujan"];	

	/*----------  Provincias  ----------*/
	const PROVINCIAS=["Buenos Aires"];

	/*----------  Paises  ----------*/
	const PAISES=["Argentina"];	

	/*----------  Configuración de moneda  ----------*/
	const MONEDA_SIMBOLO="$";
	const MONEDA_NOMBRE="ARS";
	const MONEDA_DECIMALES="2";
	const MONEDA_SEPARADOR_MILLAR=".";
	const MONEDA_SEPARADOR_DECIMAL=",";

	/*---------- Formas de pago ----------*/
	const FORMAS_PAGO = ["Efectivo, Transferencia"];


	/*----------  Marcador de campos obligatorios (Font Awesome) ----------*/
	const CAMPO_OBLIGATORIO='<span class="material-symbols-outlined">emergency</span>';

	/*----------  Zona horaria  ----------*/
	date_default_timezone_set("America/Argentina/Buenos_Aires");

?>