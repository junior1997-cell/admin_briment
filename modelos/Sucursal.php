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
	public function insertar($nombre_lote,$fecha_vencimiento,$descripcion)
	{
		$sql="INSERT INTO lote (nombre,fecha_vencimiento, descripcion, user_created)VALUES ('$nombre_lote','$fecha_vencimiento', '$descripcion','" . $_SESSION['idusuario'] . "')";
		$intertar =  ejecutarConsulta_retornarID($sql); 
		if ($intertar['status'] == false) {  return $intertar; } 
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('lote','".$intertar['data']."','Nuevo Lote registrado','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $intertar;
	}

	//Implementamos un método para editar registros
	public function editar($idlote,$nombre_lote,$fecha_vencimiento,$descripcion)
	{
		$sql="UPDATE lote SET nombre='$nombre_lote',fecha_vencimiento='$fecha_vencimiento', descripcion = '$descripcion',user_updated= '" . $_SESSION['idusuario'] . "' WHERE idlote='$idlote'";
		$editar =  ejecutarConsulta($sql);
		if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('lote','$idlote','Lote editada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar lote
	public function desactivar($idlote)
	{
		$sql="UPDATE lote SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idlote='$idlote'";
		$desactivar= ejecutarConsulta($sql);

		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('lote','".$idlote."','Unidad de medida desactivada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
	}

	//Implementamos un método para activar lote
	public function activar($idlote)
	{
		$sql="UPDATE lote SET estado='1' WHERE idlote='$idlote'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar lote
	public function eliminar($idlote)
	{
		$sql="UPDATE lote SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idlote='$idlote'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('lote','$idlote','Unidad de medida Eliminaao','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idlote)
	{
		$sql="SELECT * FROM lote WHERE idlote='$idlote'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function tbla_lote()
	{
		$sql="SELECT * FROM lote WHERE estado=1  AND estado_delete=1  ORDER BY nombre ASC";
		return ejecutarConsulta($sql);			
	}

	
}
?>