<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Color
{
	//Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

	//Implementamos un método para insertar registros
	public function insertar($nombre, $hexadecimal) {
		//var_dump($nombre);die();
		$sql="INSERT INTO color(nombre_color, hexadecimal, user_created)VALUES('$nombre', '$hexadecimal','$this->id_usr_sesion' )";

		$intertar =  ejecutarConsulta_retornarID($sql); 
		if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES ('color','".$intertar['data']."','Nuevo color registrado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idcolor, $nombre, $hexadecimal) {
		$sql="UPDATE color SET nombre_color='$nombre', hexadecimal ='$hexadecimal',user_updated= '$this->id_usr_sesion' WHERE idcolor='$idcolor'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES ('color','$idcolor','Color editado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar color
	public function desactivar($idcolor) {
		$sql="UPDATE color SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idcolor='$idcolor'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES ('Color','".$idcolor."','Color desactivado','$this->id_usr_sesion')";
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
		$sql="UPDATE color SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idcolor='$idcolor'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES ('color','$idcolor','Color Eliminado','$this->id_usr_sesion')";
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