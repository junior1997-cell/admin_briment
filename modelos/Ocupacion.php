<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Ocupacion
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre_ocupacion)
	{
		$sql="INSERT INTO ocupacion (nombre_ocupacion, user_created)VALUES ('$nombre_ocupacion','" . $_SESSION['idusuario'] . "')";
		$intertar =  ejecutarConsulta_retornarID($sql); 
		if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('ocupacion','".$intertar['data']."','Nueva ocupacion registrada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idocupacion,$nombre_ocupacion)
	{
		$sql="UPDATE ocupacion SET nombre_ocupacion='$nombre_ocupacion',user_updated= '" . $_SESSION['idusuario'] . "' WHERE idocupacion='$idocupacion'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('proveedor','$idocupacion','Ocupacion editada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar ocupacion
	public function desactivar($idocupacion)
	{
		$sql="UPDATE ocupacion SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "'  WHERE idocupacion='$idocupacion'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('ocupacion','".$idocupacion."','Ocupacion desactivada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar ocupacion
	public function activar($idocupacion)
	{
		$sql="UPDATE ocupacion SET estado='1' WHERE idocupacion='$idocupacion'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar ocupacion
	public function eliminar($idocupacion)
	{
		$sql="UPDATE ocupacion SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idocupacion='$idocupacion'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('ocupacion','$idocupacion','Ocupacion Eliminada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idocupacion)
	{
		$sql="SELECT * FROM ocupacion WHERE idocupacion='$idocupacion'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM ocupacion 	WHERE estado=1  AND estado_delete=1  ORDER BY nombre_ocupacion ASC";
		return ejecutarConsulta($sql);		
	}

}
?>