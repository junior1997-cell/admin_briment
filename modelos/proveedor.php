<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Proveedor
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Seleccionar Trabajador Select2
	public function select2_proveedor()
	{
		$sql="SELECT idproveedor,razon_social,ruc FROM proveedor WHERE estado='1'";
		return ejecutarConsulta($sql);		
	}

}

?>