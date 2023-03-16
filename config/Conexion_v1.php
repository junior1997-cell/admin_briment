<?php 
require_once "global_local.php";
require "../config/funcion_general.php";

$conexion = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

mysqli_query( $conexion, 'SET NAMES "'.DB_ENCODE.'"');

//Si tenemos un posible error en la conexi贸n lo mostramos
if (mysqli_connect_errno())
{
	printf("Falló conexión a la base de datos: %s\n",mysqli_connect_error());
	exit();
}

if (!function_exists('ejecutarConsulta'))
{
	function ejecutarConsulta($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);
		return $query;
	}

	function ejecutarConsultaSimpleFila($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);		
		$row = $query->fetch_assoc();
		return $row;
	}

	function ejecutarConsultaArray($sql)
	{
		global $conexion;

		//$data= Array();	$i = 0;

		$query = $conexion->query($sql);

		for ($data = array (); $row = $query->fetch_assoc(); $data[] = $row);
		return $data;
	}
	

	function ejecutarConsulta_retornarID($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);		
		return $conexion->insert_id;			
	}

	function limpiarCadena($str)
	{
		// htmlspecialchars($str);
		global $conexion;
		$str = mysqli_real_escape_string($conexion,trim($str));
		return $str;
	}

	function encodeCadenaHtml($str)
	{
		// htmlspecialchars($str);
		global $conexion;
		$encod = "UTF-8";
		$str = mysqli_real_escape_string($conexion,trim($str));
		return htmlspecialchars($str, ENT_QUOTES);
	}

	function decodeCadenaHtml($str)
	{				
		$encod = "UTF-8";
		return htmlspecialchars_decode($str, ENT_QUOTES);
	}
}
?>