<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class presentacion
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$descripcion)
	{
		$sql="INSERT INTO presentacion (nombre,descripcion, user_created)VALUES ('$nombre', '$descripcion','" . $_SESSION['idusuario'] . "')";
		$intertar =  ejecutarConsulta_retornarID($sql); 
		if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('presentacion','".$intertar['data']."','Nueva presentación registrada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idpresentacion,$nombre,$descripcion)
	{
		$sql="UPDATE presentacion SET nombre='$nombre', descripcion = '$descripcion',user_updated= '" . $_SESSION['idusuario'] . "' WHERE idpresentacion='$idpresentacion'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('presentacion','$idpresentacion','Presentación editada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar presentacion
	public function desactivar($idpresentacion)
	{
		$sql="UPDATE presentacion SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idpresentacion='$idpresentacion'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('presentacion','".$idpresentacion."','Presentación desactivada','" . $_SESSION['idusuario'] . "')";
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
	public function eliminar($idpresentacion)
	{
		$sql="UPDATE presentacion SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idpresentacion='$idpresentacion'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('presentacion','$idpresentacion','Presentación Eliminado','" . $_SESSION['idusuario'] . "')";
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