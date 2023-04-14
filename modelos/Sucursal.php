<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Sucursal
{
  //Implementamos nuestro variable global
  public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct( $id_usr_sesion = 0 )
	{
    $this->id_usr_sesion = $id_usr_sesion;
	}

	//Implementamos un método para insertar registros
	public function insertar($nombre, $codigo, $direccion)
	{
		$sql="INSERT INTO sucursal (nombre, codigo, direccion, user_created)VALUES ('$nombre','$codigo', '$direccion','$this->id_usr_sesion')";
		$intertar =  ejecutarConsulta_retornarID($sql); if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_d = $nombre.', '.$codigo.', '.$direccion;
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5,'sucursal','".$intertar['data']."','$sql_d', '$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idsucursal, $nombre, $codigo, $direccion)
	{
		$sql="UPDATE sucursal SET nombre='$nombre', codigo='$codigo', direccion='$direccion', user_updated= '$this->id_usr_sesion' WHERE idsucursal='$idsucursal'";
		$editar =  ejecutarConsulta($sql); if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_d =$idsucursal.', '.$nombre.', '.$codigo.', '.$direccion;
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6, 'sucursal','$idsucursal', '$sql_d', '$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar lote
	public function desactivar($id)
	{
		$sql="UPDATE sucursal SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idsucursal='$id'";
		$desactivar= ejecutarConsulta($sql); if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (2, 'sucursal','".$id."', '$id','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar sucursal
	public function activar($idsucursal)
	{
		$sql="UPDATE sucursal SET estado='1' WHERE idsucursal='$idsucursal'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar sucursal
	public function eliminar($id)
	{
		$sql="UPDATE sucursal SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idsucursal='$id'";
		$eliminar =  ejecutarConsulta($sql);	if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (4, 'sucursal','$id','$id', '$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idsucursal)
	{
		$sql="SELECT * FROM sucursal WHERE idsucursal='$idsucursal'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function tbla_sucursal()
	{
		$sql="SELECT * FROM sucursal WHERE estado=1  AND estado_delete=1  ORDER BY nombre ASC";
		return ejecutarConsulta($sql);			
	}

	
}
?>