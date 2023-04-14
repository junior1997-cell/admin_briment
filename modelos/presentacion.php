<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class presentacion
{
	//Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$descripcion)
	{
		$sql="INSERT INTO presentacion (nombre,descripcion, user_created)VALUES ('$nombre', '$descripcion','$this->id_usr_sesion')";
		$intertar =  ejecutarConsulta_retornarID($sql); if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_d = $nombre.','.$descripcion;
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5,'presentacion','".$intertar['data']."','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idpresentacion,$nombre,$descripcion)
	{
		$sql="UPDATE presentacion SET nombre='$nombre', descripcion = '$descripcion',user_updated= '$this->id_usr_sesion' WHERE idpresentacion='$idpresentacion'";
		$editar =  ejecutarConsulta($sql); if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_d = $idpresentacion.','.$nombre.','.$descripcion;
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6,'presentacion','$idpresentacion','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar presentacion
	public function desactivar($id)
	{
		$sql="UPDATE presentacion SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idpresentacion='$id'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (2, 'presentacion','".$id."','$id','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar presentacion
	public function activar($idpresentacion)
	{
		$sql="UPDATE presentacion SET estado='1' WHERE idpresentacion='$idpresentacion'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar presentacion
	public function eliminar($id)
	{
		$sql="UPDATE presentacion SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idpresentacion='$id'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (4, 'presentacion','$id','$id','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idpresentacion)
	{
		$sql="SELECT * FROM presentacion WHERE idpresentacion='$idpresentacion'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function tbla_presentacion()
	{
		$sql="SELECT * FROM presentacion WHERE estado=1  AND estado_delete=1  ORDER BY nombre ASC";
		return ejecutarConsulta($sql);			
	}

	
}
?>