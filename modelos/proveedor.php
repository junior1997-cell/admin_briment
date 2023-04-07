<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Proveedor
{
	//Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

	//Seleccionar Trabajador Select2
	public function select2_proveedor()
	{
		$sql="SELECT idproveedor,razon_social,ruc FROM proveedor WHERE estado='1'";
		return ejecutarConsulta($sql);		
	}

}

?>