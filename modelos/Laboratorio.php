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
	public function insertar($nombre, $descripcion)
	{
		//var_dump($nombre);die();
		$sql="INSERT INTO `laboratorio`(`nombre`, `descripcion`, user_created) VALUES ('$nombre', '$descripcion','" . $_SESSION['idusuario'] . "')";
		$insertar =  ejecutarConsulta_retornarID($sql); 
		if ($insertar['status'] == false) {  return $insertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('laboratorio','".$insertar['data']."','Nueva categoría de insumos registrada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $insertar;
	}

	//Implementamos un método para editar registros
	public function editar($idlaboratorio,$nombre,$descripcion)
	{
		$sql="UPDATE laboratorio SET nombre='$nombre', descripcion= '$descripcion',user_updated= '" . $_SESSION['idusuario'] . "' WHERE idlaboratorio='$idlaboratorio'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('laboratorio','$idlaboratorio','Categoría de insumos editada','" . $_SESSION['idusuario'] . "')";
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
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('laboratorio','".$idlaboratorio."','Categoría de insumos desactivado','" . $_SESSION['idusuario'] . "')";
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
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('trabajador','$idlaboratorio','Categoría de insumos Eliminado','" . $_SESSION['idusuario'] . "')";
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
		$sql="SELECT * FROM laboratorio WHERE  idlaboratorio>1 AND estado=1 AND estado_delete=1  ORDER BY nombre ASC";
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