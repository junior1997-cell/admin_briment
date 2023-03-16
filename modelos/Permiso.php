<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Permiso
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	
	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM permiso ORDER BY idpermiso ASC";

		return ejecutarConsulta($sql);		
	}

	public function ver_usuarios($id_permiso)
	{
		$sql = "SELECT pers.nombres, pers.tipo_documento, pers.numero_documento, pers.foto_perfil, ct.nombre as cargo, u.created_at
		FROM permiso as p, usuario_permiso as up, usuario as u, persona as pers, cargo_trabajador ct
		WHERE p.idpermiso = up.idpermiso and up.idusuario = u.idusuario and u.idpersona = pers.idpersona 
		AND pers.idcargo_trabajador = ct.idcargo_trabajador and u.estado='1' AND u.estado_delete='1' and p.idpermiso = '$id_permiso';";

		return ejecutarConsulta($sql);	
	}

}

?>