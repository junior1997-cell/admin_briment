<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Unidades_m
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$abreviatura,$descripcion)
	{
		$sql="INSERT INTO unidad_medida (nombre,abreviatura, descripcion, user_created)VALUES ('$nombre','$abreviatura', '$descripcion','" . $_SESSION['idusuario'] . "')";
		$intertar =  ejecutarConsulta_retornarID($sql); 
		if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('unidad_medida','".$intertar['data']."','Nueva unidad medida registrada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idunidad_medida,$nombre,$abreviatura,$descripcion)
	{
		$sql="UPDATE unidad_medida SET nombre='$nombre',abreviatura='$abreviatura', descripcion = '$descripcion',user_updated= '" . $_SESSION['idusuario'] . "' WHERE idunidad_medida='$idunidad_medida'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('unidad_medida','$idunidad_medida','Unidad medida editada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar unidad_medida
	public function desactivar($idunidad_medida)
	{
		$sql="UPDATE unidad_medida SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idunidad_medida='$idunidad_medida'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('unidad_medida','".$idunidad_medida."','Unidad de medida desactivada','" . $_SESSION['idusuario'] . "')";
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
	public function eliminar($idunidad_medida)
	{
		$sql="UPDATE unidad_medida SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idunidad_medida='$idunidad_medida'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('unidad_medida','$idunidad_medida','Unidad de medida Eliminaao','" . $_SESSION['idusuario'] . "')";
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