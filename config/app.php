<?php

	const APP_URL="http://localhost:8080/puntocelular/";
	const APP_NAME="GESTION";
	const APP_SESSION_NAME="GESTION";

	/*----------  Tipos de documentos  ----------*/
	const DOCUMENTOS=["DNI","Cedula","Licencia","Pasaporte","Otro"];

	/*----------  Localidades  ----------*/
	const LOCALIDADES=["Lujan", "Rodriguez", "Moron", "Moreno", "Pilar", "Otra"];	

	/*----------  Provincias  ----------*/
	const PROVINCIAS=["Buenos Aires", "Cordoba", "Otra"];

	/*----------  Paises  ----------*/
	const PAISES=["Argentina", "Bolivia", "Chile", "Venezuela", "Otro"];	

	/*---------- Monedas ---------- */
	const MONEDAS =["ARS", "USD"];


	/*----------  Configuración de moneda  ----------*/
	const MONEDA_SIMBOLO="$";
	const MONEDA_NOMBRE="ARS";
	const MONEDA_DECIMALES="2";
	const MONEDA_SEPARADOR_MILLAR=".";
	const MONEDA_SEPARADOR_DECIMAL=",";

	/* dolar punto celular */
	const COSTO_OPERATIVO_HORA = 24000;

	/*---------- Formas de pago ----------*/
	const FORMAS_PAGO = ["Efectivo", "Transferencia", "Tarjeta de credito", "Tarjeta de debito", "QR MP", "Cuenta DNI"];

	/*----------  Tipos de almacenamientos para equipo  ----------*/
	const ALMACENAMIENTO=["16GB","32GB","64GB","128GB","256GB","512GB", "1TB"];

	/*----------  Tipos de almacenamientos para equipo  ----------*/
	const RAM=["2GB","3GB","4GB","6GB","8GB", "12GB"];


	/*----------  Marcador de campos obligatorios (Font Awesome) ----------*/
	const CAMPO_OBLIGATORIO='<span class="material-symbols-outlined">emergency</span>';

	/*----------  Zona horaria  ----------*/
	date_default_timezone_set("America/Argentina/Buenos_Aires");

	

?>
