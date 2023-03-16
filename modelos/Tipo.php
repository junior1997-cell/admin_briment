<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Tipo
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre_tipo, $descripcion)
	{
		$sql="INSERT INTO tipo_persona (nombre, descripcion, user_created)VALUES ('$nombre_tipo', '$descripcion','" . $_SESSION['idusuario'] . "')";
		$intertar =  ejecutarConsulta_retornarID($sql); 
		if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('tipo_persona','".$intertar['data']."','Nuevo tipo trabajador registrado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idtipo_persona,$nombre_tipo,$descripcion)
	{
		$sql="UPDATE tipo_persona SET nombre='$nombre_tipo', descripcion= '$descripcion', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idtipo_persona='$idtipo_persona'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('tipo_persona','$idtipo_persona','Tipo trabajador editado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar tipo
	public function desactivar($idtipo_persona)
	{
		$sql="UPDATE tipo_persona SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idtipo_persona='$idtipo_persona'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('tipo_persona','".$idtipo_persona."','Tipo trabajador desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar tipo
	public function activar($idtipo_persona)
	{
		$sql="UPDATE tipo_persona SET estado='1' WHERE idtipo_persona='$idtipo_persona'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar tipo
	public function eliminar($idtipo_persona)
	{
		$sql="UPDATE tipo_persona SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idtipo_persona='$idtipo_persona'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('tipo_persona','$idtipo_persona','Tipo trabajador Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idtipo_persona)
	{
		$sql="SELECT * FROM tipo_persona WHERE idtipo_persona='$idtipo_persona'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar_tipo()
	{
		$sql="SELECT * FROM tipo_persona WHERE estado=1 AND estado_delete=1 ORDER BY idtipo_persona ASC";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM tipo_persona where estado=1";
		return ejecutarConsulta($sql);		
	}
}
?>