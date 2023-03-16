<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Color
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre, $hexadecimal) {
		//var_dump($nombre);die();
		$sql="INSERT INTO color(nombre_color, hexadecimal, user_created)VALUES('$nombre', '$hexadecimal','" . $_SESSION['idusuario'] . "' )";

		$intertar =  ejecutarConsulta_retornarID($sql); 
		if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('color','".$intertar['data']."','Nuevo color registrado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idcolor, $nombre, $hexadecimal) {
		$sql="UPDATE color SET nombre_color='$nombre', hexadecimal ='$hexadecimal',user_updated= '" . $_SESSION['idusuario'] . "' WHERE idcolor='$idcolor'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('color','$idcolor','Color editado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar color
	public function desactivar($idcolor) {
		$sql="UPDATE color SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idcolor='$idcolor'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('Color','".$idcolor."','Color desactivado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar color
	public function activar($idcolor) {
		$sql="UPDATE color SET estado='1' WHERE idcolor='$idcolor'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar color
	public function eliminar($idcolor) {
		$sql="UPDATE color SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idcolor='$idcolor'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('color','$idcolor','Color Eliminado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcolor) {
		$sql="SELECT * FROM color WHERE idcolor='$idcolor'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar() {
		$sql="SELECT * FROM color WHERE idcolor>'1' AND estado=1  AND estado_delete=1 ORDER BY nombre_color ASC";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function select() {
		$sql="SELECT * FROM color where estado=1";
		return ejecutarConsulta($sql);		
	}
}
?>