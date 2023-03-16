<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Bancos
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre, $alias, $formato_cta, $formato_cci, $formato_detracciones, $imagen1)
	{
		$sql="INSERT INTO bancos (nombre, alias, formato_cta, formato_cci, formato_detracciones, icono, user_created)
		VALUES ('$nombre', '$alias', '$formato_cta', '$formato_cci', '$formato_detracciones', '$imagen1','" . $_SESSION['idusuario'] . "')";

		$intertar =  ejecutarConsulta_retornarID($sql); 

		if ($intertar['status'] == false) {  return $intertar; } 

		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('bancos','".$intertar['data']."','Nuevo banco registrado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idbancos, $nombre, $alias, $formato_cta, $formato_cci, $formato_detracciones, $imagen1)
	{
		$sql="UPDATE bancos SET nombre='$nombre', alias ='$alias', formato_cta='$formato_cta', 
		formato_cci='$formato_cci', formato_detracciones='$formato_detracciones', icono='$imagen1',
		user_updated= '" . $_SESSION['idusuario'] . "' 
		WHERE idbancos='$idbancos'";
		$editar =  ejecutarConsulta($sql);

		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('bancos','$idbancos','Banco editado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar bancos
	public function desactivar($idbancos)
	{
		$sql="UPDATE bancos SET estado='0' ,user_trash= '" . $_SESSION['idusuario'] . "' WHERE idbancos='$idbancos'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('bancos','".$idbancos."','Banco desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar bancos
	public function activar($idbancos)
	{
		$sql="UPDATE bancos SET estado='1' WHERE idbancos='$idbancos'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar bancos
	public function eliminar($idbancos)
	{
		$sql="UPDATE bancos SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idbancos='$idbancos'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('bancos','$idbancos','Banco Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}
	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idbancos)
	{
		$sql="SELECT * FROM bancos WHERE idbancos='$idbancos'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM bancos WHERE idbancos > 1 	AND estado=1  AND estado_delete=1 ORDER BY nombre ASC";
		return ejecutarConsulta($sql);		
	}
	
	//Implementar un método para listar los registros y mostrar en el select
	public function obtenerImg($id){
		$sql="SELECT icono FROM bancos where idbancos = '$id' ";
		return ejecutarConsultaSimpleFila($sql);		
	}
}
?>