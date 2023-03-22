<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Laboratorio
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre_laboratorio)
	{
		//var_dump($nombre);die();
		$sql="INSERT INTO `laboratorio`(`nombre`, user_created) VALUES ('$nombre_laboratorio','" . $_SESSION['idusuario'] . "')";
		$insertar =  ejecutarConsulta_retornarID($sql); 
		if ($insertar['status'] == false) {  return $insertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('laboratorio','".$insertar['data']."','Nueva laboratorio (Marca) registrada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $insertar;
	}

	//Implementamos un método para editar registros
	public function editar($idlaboratorio,$nombre_laboratorio)
	{
		$sql="UPDATE laboratorio SET nombre='$nombre_laboratorio',user_updated= '" . $_SESSION['idusuario'] . "' WHERE idlaboratorio='$idlaboratorio'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('laboratorio','$idlaboratorio','Marca editada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar laboratorio
	public function desactivar($idlaboratorio)
	{
		$sql="UPDATE laboratorio SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idlaboratorio='$idlaboratorio'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('laboratorio','".$idlaboratorio."','Marca desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar laboratorio
	public function activar($idlaboratorio)
	{
		$sql="UPDATE laboratorio SET estado='1' WHERE idlaboratorio='$idlaboratorio'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar
	public function delete($idlaboratorio)
	{
		$sql="UPDATE laboratorio SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idlaboratorio='$idlaboratorio'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador','$idlaboratorio','Marca Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idlaboratorio)
	{
		$sql="SELECT * FROM laboratorio WHERE idlaboratorio='$idlaboratorio'; ";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM laboratorio WHERE  idlaboratorio AND estado=1 AND estado_delete=1  ORDER BY nombre ASC";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM laboratorio where idlaboratorio>1 AND estado=1 AND estado_delete=1";
		return ejecutarConsulta($sql);		
	}

}
?>