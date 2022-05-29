<?php 
	//define("BASE_URL","http://http://localhost/Tienda_Virtual/");

	//Constantes de la URL
	const BASE_URL = "http://localhost/Tienda_Virtual";

	//Zona horaria
	date_default_timezone_set('America/Bogota');

	//Datos de conexión a la base de datos
	const DB_HOST     = "localhost";
	const DB_NAME     = "db_tiendavirtual";
	const DB_USER     = "root";
	const DB_PASSWORD = "Colombia123.";
	const DB_CHARSET  = "charset=utf8";
	
	//Delimitadores decimal y millar Ej. 24.199,00
	const SPD         = ",";
	const SPM         = ".";

	//Simbolo de moneda
	const SMONEY = "$";	

	//Datos envio de correo
	const NOMBRE_REMITENTE = "TiendaOK";
	const EMAIL_REMITENTE  = "no-reply@fastech.co";
	const NOMBRE_EMPESA    = "TiendaOK";
	const WEB_EMPRESA      = "www.tiendaok.co";	
?>