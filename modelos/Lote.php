<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Lote
{
	//Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

	//Implementamos un método para insertar registros
	public function insertar($nombre_lote, $fecha_vencimiento, $descripcion)
	{
		$sql = "SELECT * FROM lote WHERE nombre = '$nombre_lote' AND fecha_vencimiento ='$fecha_vencimiento';";
    $buscando = ejecutarConsultaArray($sql);  if ($buscando['status'] == false) { return $buscando; }

		if ( empty($buscando['data']) ) {
			$sql="INSERT INTO lote (nombre,fecha_vencimiento, descripcion, user_created)VALUES ('$nombre_lote','$fecha_vencimiento', '$descripcion','$this->id_usr_sesion')";
			$intertar =  ejecutarConsulta_retornarID($sql); if ($intertar['status'] == false) {  return $intertar; } 
			
			//add registro en nuestra bitacora
			$sql_d = $nombre_lote.', '.$fecha_vencimiento.', '.$descripcion;
			$sql_bit = "INSERT INTO bitacora_bd(idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5, 'lote','".$intertar['data']."','$sql_d','$this->id_usr_sesion')";
			$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
			
			return $intertar;
		} else {
			$info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Nombre: </b>'.$value['nombre'].'<br>
          <b>Stock: </b>'.$value['stock'].'<br>
          <b>F.V.: </b>'.$value['fecha_vencimiento'].'<br>
          <b>Descripción: </b>'.$value['descripcion'].'<br>
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .'<br>
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
      return $sw;
		}	
	}

	//Implementamos un método para editar registros
	public function editar($idlote,$nombre_lote,$fecha_vencimiento,$descripcion)
	{
		$sql="UPDATE lote SET nombre='$nombre_lote',fecha_vencimiento='$fecha_vencimiento', descripcion = '$descripcion',user_updated= '$this->id_usr_sesion' WHERE idlote='$idlote'";
		$editar =  ejecutarConsulta($sql);	if ( $editar['status'] == false) {return $editar; } 
	
		//add registro en nuestra bitacora
		$sql_d = $idlote.', '.$nombre_lote.', '.$fecha_vencimiento.', '.$descripcion;
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6,'lote','$idlote','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
	
		return $editar;
	}

	//Implementamos un método para desactivar lote
	public function desactivar($idlote)
	{
		$sql="UPDATE lote SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idlote='$idlote'";
		$desactivar= ejecutarConsulta($sql);
		if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (2,'lote','$idlote','$idlote','$this->id_usr_sesion')";
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
		$sql="UPDATE lote SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idlote='$idlote'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (4, 'lote','$idlote','$idlote','$this->id_usr_sesion')";
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