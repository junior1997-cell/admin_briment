<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Cargo
{
	//Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

	//Implementamos un método para insertar registros
	public function insertar($nombre)
	{
		//var_dump($nombre);die();
		$sql="INSERT INTO cargo_trabajador(nombre, user_created)VALUES('$nombre','$this->id_usr_sesion')";
		$intertar =  ejecutarConsulta_retornarID($sql); 
		if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('cargo_trabajador','".$intertar['data']."','Nuevo cargo trabajador registrado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idcargo_trabajador,$nombre)
	{
		$sql="UPDATE cargo_trabajador SET nombre='$nombre',user_updated= '$this->id_usr_sesion' WHERE idcargo_trabajador='$idcargo_trabajador'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('cargo_trabajador','$idcargo_trabajador','Cargo trabajador editado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar cargo_trabajador
	public function desactivar($idcargo_trabajador)
	{
		$sql="UPDATE cargo_trabajador SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idcargo_trabajador='$idcargo_trabajador'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('cargo_trabajador','".$idcargo_trabajador."','Cargo trabajador desactivado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar cargo_trabajador
	public function activar($idcargo_trabajador)
	{
		$sql="UPDATE cargo_trabajador SET estado='1' WHERE idcargo_trabajador='$idcargo_trabajador'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar
	public function eliminar($idcargo_trabajador)
	{
		$sql="UPDATE cargo_trabajador SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idcargo_trabajador='$idcargo_trabajador'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('cargo_trabajador','$idcargo_trabajador','Cargo trabajador Eliminado','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}
	

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcargo_trabajador)
	{
		$sql="SELECT * FROM cargo_trabajador WHERE idcargo_trabajador='$idcargo_trabajador'; ";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM cargo_trabajador WHERE estado=1 AND estado_delete=1  ORDER BY nombre ASC";
		return ejecutarConsulta($sql);
				
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM cargo_trabajador where estado=1";
		return ejecutarConsulta($sql);		
	}
	
}
?>