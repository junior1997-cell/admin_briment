<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Unidades_m
{
	//Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$abreviatura,$descripcion)
	{
		$sql="INSERT INTO unidad_medida (nombre,abreviatura, descripcion, user_created)VALUES ('$nombre','$abreviatura', '$descripcion','$this->id_usr_sesion')";
		$intertar =  ejecutarConsulta_retornarID($sql); 
		if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_d = $nombre.','.$abreviatura.','.$descripcion;
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5, 'unidad_medida','".$intertar['data']."','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idunidad_medida,$nombre,$abreviatura,$descripcion)
	{
		$sql="UPDATE unidad_medida SET nombre='$nombre',abreviatura='$abreviatura', descripcion = '$descripcion',user_updated= '$this->id_usr_sesion' WHERE idunidad_medida='$idunidad_medida'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_d = $idunidad_medida.','.$nombre.','.$abreviatura.','.$descripcion;
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6, 'unidad_medida','$idunidad_medida','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar unidad_medida
	public function desactivar($id)
	{
		$sql="UPDATE unidad_medida SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idunidad_medida='$id'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (2, 'unidad_medida','".$id."','$id','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar unidad_medida
	public function activar($idunidad_medida)
	{
		$sql="UPDATE unidad_medida SET estado='1' WHERE idunidad_medida='$idunidad_medida'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar unidad_medida
	public function eliminar($id)
	{
		$sql="UPDATE unidad_medida SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idunidad_medida='$id'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (4, 'unidad_medida','$id','$id','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idunidad_medida)
	{
		$sql="SELECT * FROM unidad_medida WHERE idunidad_medida='$idunidad_medida'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function tbla_unidad_medida()
	{
		$sql="SELECT * FROM unidad_medida WHERE estado=1  AND estado_delete=1  ORDER BY nombre ASC";
		return ejecutarConsulta($sql);			
	}

	
}
?>