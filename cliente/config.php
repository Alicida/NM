<?php
require_once 'messages.php';

define( 'BASE_PATH', 'https://navidadmovistar.com.mx/cliente/');//Ruta base donde se encuentra
define( 'DB_HOST', 'PMYSQL117.dns-servicio.com' );//Servidor de la base de datos
define( 'DB_USERNAME', 'UserNavidad');//Usuario de la base de datos
define( 'DB_PASSWORD', 'L8ip~u53');//Contraseña de la base de datos
define( 'DB_NAME', '7063345_NavidadMovistar');//Nombre de la base de datos


function __autoload($class)
{
	$parts = explode('_', $class);
	$path = implode(DIRECTORY_SEPARATOR,$parts);
	require_once $path . '.php';
}
