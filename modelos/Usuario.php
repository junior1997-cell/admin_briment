<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Usuario
{
  //Implementamos nuestro variable global
  public $id_usr_sesion;

  //Implementamos nuestro constructor
  public function __construct($id_usr_sesion = 0)
  {
    $this->id_usr_sesion = $id_usr_sesion;
  }

  //Implementamos un método para insertar registros
  public function insertar($trabajador, $cargo, $login, $clave, $permisos) {

    // insertamos al usuario
    $sql = "INSERT INTO usuario ( idpersona, login, password,user_created) VALUES ('$trabajador','$login', '$clave','$this->id_usr_sesion')";
    $data_user = ejecutarConsulta_retornarID($sql); if ($data_user['status'] == false){return $data_user; }

    //add registro en nuestra bitacora
    $sql2 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario','" . $data_user['data'] . "','Registrar','$this->id_usr_sesion')";
    $bitacora1 = ejecutarConsulta($sql2); if ( $bitacora1['status'] == false) {return $bitacora1; }

    //add registro en nuestra bitacora
    $sql2 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario','" . $data_user['data'] . "','Registrar','$this->id_usr_sesion')";
    $bitacora1 = ejecutarConsulta($sql2); if ( $bitacora1['status'] == false) {return $bitacora1; }

    $num_elementos = 0; $sw = "";

    if ( !empty($permisos) ) {

      while ($num_elementos < count($permisos)) {
        
        $idusuarionew = $data_user['data'];

        $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso, user_created) VALUES('$idusuarionew', '$permisos[$num_elementos]','$this->id_usr_sesion')";

        $sw = ejecutarConsulta_retornarID($sql_detalle);  

        if ( $sw['status'] == false) {return $sw; }

        //add registro en nuestra bitacora
        $sql2 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','" .  $sw['data'] . "','Registrar permisos','$this->id_usr_sesion')";
        $bitacora = ejecutarConsulta($sql2);

        if ( $bitacora['status'] == false) {return $bitacora; }

        $num_elementos++;

      }

      return $sw;

    }else{

      return $data_user;

    }
  }

  //Implementamos un método para editar registros
  public function editar($idusuario, $trabajador,$trabajador_old, $cargo, $login, $clave, $permisos) {

    $trab = "";
    if (empty($trabajador)) {$trab = $trabajador_old;}else{$trab = $trabajador; }
    // var_dump($trab);die();
    $update_user = '[]';
    
    //Eliminamos todos los permisos asignados para volverlos a registrar
    $sqldel = "DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
    $delete =  ejecutarConsulta($sqldel); if ( $delete['status'] == false) {return $delete; }   

    $sql = "UPDATE usuario SET 
    idpersona='$trab', login='$login', password='$clave', user_updated= '$this->id_usr_sesion' WHERE idusuario='$idusuario'";
    $update_user = ejecutarConsulta($sql); if ($update_user['status'] == false) {return $update_user; }     
    
    //add registro en nuestra bitacora
    $sql5_1 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario', '$idusuario' ,'Editamos los campos del usuario','$this->id_usr_sesion')";
    $bitacora5_1 = ejecutarConsulta($sql5_1); if ( $bitacora5_1['status'] == false) {return $bitacora5_1; }  

    $num_elementos = 0; $sw = "";

    if ($permisos != "") {      

      while ($num_elementos < count($permisos)) {

        $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso,user_created) VALUES('$idusuario', '$permisos[$num_elementos]','$this->id_usr_sesion')";

        $sw = ejecutarConsulta_retornarID($sql_detalle);  

        if ( $sw['status'] == false) {return $sw; }

        //add registro en nuestra bitacora
        $sqlsw = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','" .  $sw['data'] . "','Asigamos nuevos persmisos cuando editamos usuario','$this->id_usr_sesion')";
        $bitacorasw = ejecutarConsulta($sqlsw);

        if ( $bitacorasw['status'] == false) {return $bitacorasw; }

        $num_elementos = $num_elementos + 1;

      }

      return $sw;
    
    }

  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idusuario) {
    $sql = "UPDATE usuario SET estado='0', user_trash= '$this->id_usr_sesion' WHERE idusuario='$idusuario'";
    $desactivar = ejecutarConsulta($sql); if ( $desactivar['status'] == false) {return $desactivar; }    

    //add registro en nuestra bitacora
    $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','$idusuario','Registro desactivado','$this->id_usr_sesion')";
    $bitacorade = ejecutarConsulta($sqlde); if ( $bitacorade['status'] == false) {return $bitacorade; }   

    return $desactivar;
  }

  //Implementamos un método para activar :: !!sin usar ::
  public function activar($idusuario) {
    $sql = "UPDATE usuario SET estado='1', user_updated= '$this->id_usr_sesion' WHERE idusuario='$idusuario'";
    $activar= ejecutarConsulta($sql); if ( $activar['status'] == false) {return $activar; }    

    //add registro en nuestra bitacora
    $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','$idusuario','Registro activado','$this->id_usr_sesion')";
    $bitacorade = ejecutarConsulta($sqlde); if ( $bitacorade['status'] == false) {return $bitacorade; }   

    return $activar;
  }

  //Implementamos un método para eliminar usuario
  public function eliminar($idusuario) {
    $sql = "UPDATE usuario SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idusuario='$idusuario'";
    $eliminar= ejecutarConsulta($sql);  if ( $eliminar['status'] == false) {return $eliminar; }    

    //add registro en nuestra bitacora
    $sqlde = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('usuario_permiso','$idusuario','Registro Eliminado','$this->id_usr_sesion')";
    $bitacorade = ejecutarConsulta($sqlde); if ( $bitacorade['status'] == false) {return $bitacorade; }   

    return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idusuario) {
    $sql = "SELECT u.idusuario, u.idpersona, u.login, u.password, u.estado, p.nombres 
    FROM usuario AS u, persona AS p 
    WHERE u.idusuario='$idusuario' AND u.idpersona = p.idpersona;";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function validar_usuario($idusuario, $user) {
    $validar_user = empty($idusuario) ? "" : "AND u.idusuario != '$idusuario'" ;
    $sql = "SELECT u.idusuario, u.idpersona, u.login, u.password, u.estado, p.nombres 
    FROM usuario AS u, persona AS p 
    WHERE u.idpersona = p.idpersona AND u.login = '$user' $validar_user;";
    $buscando =  ejecutarConsultaArray($sql); if ( $buscando['status'] == false) {return $buscando; }

    if (empty($buscando['data'])) { return true; }else { return false; }
  }

  //Implementar un método para listar los registros
  public function tbla_principal() {
    $datos = Array();
    $sql_1 = "SELECT u.idusuario, u.last_sesion, p.nombres, p.tipo_documento, p.numero_documento, p.celular, p.correo, ct.nombre as cargo, 
    u.login, p.foto_perfil, p.tipo_documento, u.estado 
    FROM usuario as u, persona as p,cargo_trabajador as ct 
    WHERE u.idpersona = p.idpersona AND p.idcargo_trabajador =ct.idcargo_trabajador AND u.estado=1 AND u.estado_delete=1 ORDER BY p.nombres ASC;";
    $tabla_principal = ejecutarConsultaArray($sql_1); if ($tabla_principal['status'] == false) { return $tabla_principal;}

    foreach ($tabla_principal['data'] as $key => $val) {
      $id = $val['idusuario'];
      $sql_2 = "SELECT ps.idpersona_sucursal, ps.idsucursal, ps.idusuario, ps.estado, ps.estado_delete, s.nombre as sucursal, s.direccion, s.codigo
      FROM persona_sucursal as ps, sucursal as s
      WHERE ps.idsucursal = s.idsucursal AND ps.idusuario = '$id' AND ps.estado_delete = '1' AND ps.estado = '1';";     
      $sucursal = ejecutarConsultaArray($sql_2);  if ($sucursal['status'] == false) { return $sucursal;}

      $data_sucursal = "";
      foreach ($sucursal['data'] as $key2 => $val2) {
        $data_sucursal .= $val2['sucursal'] . ($key2 >= 1 ? ',' : '') ;
      }
      
      $datos[] = [
        'idusuario'       =>$val['idusuario'],
        'last_sesion'     =>$val['last_sesion'],
        'nombres'         =>$val['nombres'],
        'tipo_documento'  =>$val['tipo_documento'],
        'numero_documento'=>$val['numero_documento'],
        'celular'         =>$val['celular'],
        'correo'          =>$val['correo'],
        'cargo'           =>$val['cargo'],
        'login'           =>$val['login'],
        'foto_perfil'     =>$val['foto_perfil'],
        'tipo_documento'  =>$val['tipo_documento'],
        'estado'          =>$val['estado'],
        'sucursal'        => $data_sucursal
      ];      
    }

    return [
      'status'  => true, 
      'message' => 'Salió todo ok,', 
      'data'    => $datos
    ];  
  }

  //Implementar un método para listar los permisos marcados
  public function listarmarcados($idusuario) {
    $sql = "SELECT * FROM usuario_permiso WHERE idusuario='$idusuario' ";
    return ejecutarConsulta($sql);
  }

  //Función para verificar el acceso al sistema
  //Función para verificar el acceso al sistema
  public function verificar($login, $clave) {
    $sql = "SELECT u.idusuario, p.nombres, p.tipo_documento, p.numero_documento, p.celular, p.correo, ct.nombre as cargo, u.login, p.foto_perfil, p.tipo_documento 
    FROM usuario as u, persona as p, cargo_trabajador as ct 
    WHERE  u.idpersona = p.idpersona AND p.idcargo_trabajador =ct.idcargo_trabajador AND  u.login='$login' AND u.password='$clave' 
    AND p.estado=1 and p.estado_delete=1 and u.estado=1 and u.estado_delete=1;";
    $usuario = ejecutarConsultaSimpleFila($sql); if ( $usuario['status'] == false) {return $usuario; }

    // si no es encontrado el usuario, retornamos vacio
    if ( empty($usuario['data']) ) {
      return [
        'status'  => true, 
        'message' => 'Salió todo ok,', 
        'data'    => [ 'usuario'   => null, 'sucursal'  => null ]
      ]; 
    }

    $id = $usuario['data']['idusuario'];
    $sql_1 = "SELECT ps.idpersona_sucursal, s.nombre, s.codigo, s.direccion
    FROM persona_sucursal as ps, sucursal as s
    WHERE ps.idsucursal AND s.idsucursal AND ps.estado = '1' AND ps.estado_delete = '1' AND ps.idusuario = '$id';";
    $sucursal = ejecutarConsultaSimpleFila($sql_1); if ( $sucursal['status'] == false) {return $sucursal; }    

    return [
      'status'  => true, 
      'message' => 'Salió todo ok,', 
      'data'    => [
        'usuario'   => $usuario['data'],
        'sucursal'  => $sucursal['data']
      ]
    ]; 
  }

  //Función para verificar el acceso al sistema
  public function ultima_sesion($id) {
    $sql = "UPDATE usuario SET last_sesion= current_timestamp() WHERE idusuario = '$id';";
    return ejecutarConsulta($sql);
  }

  //Seleccionar Trabajador Select2 ok
  public function select2_trabajador() {
    $sql = "SELECT p.idpersona, p.nombres, p.numero_documento, p.foto_perfil, p.celular 
    FROM persona as p LEFT JOIN usuario as u ON p.idpersona=u.idpersona 
    WHERE p.idtipo_persona='2' AND p.estado =1 AND p.estado_delete=1 AND u.idusuario IS NULL;";
    return ejecutarConsulta($sql);
  }

  public function select2_cargo_trabajador($id_persona){
    $sql = "SELECT ct.nombre as cargo FROM persona as p, cargo_trabajador as ct WHERE p.idcargo_trabajador= ct.idcargo_trabajador AND p.idpersona = '$id_persona'; ";
    return ejecutarConsultaSimpleFila($sql);
  }

  // ::::::::::::::::::::::::::::::::: S E C C I O N   S U C U R S A L :::::::::::::::::::::::::::::

  public function insertar_sucursal($idusuario, $sucursal){

    $sql_0 = "SELECT  ps.estado, ps.estado_delete, s.nombre as sucursal, s.direccion, s.codigo
    FROM persona_sucursal as ps, sucursal as s
    WHERE ps.idsucursal = s.idsucursal AND ps.idsucursal='$sucursal' AND ps.idusuario='$idusuario'; ";
    $buscando =  ejecutarConsultaArray($sql_0); if ( $buscando['status'] == false) {return $buscando; }

    if ( empty($buscando['data']) ) {
      // eliminamos todas las sucursales 
      $sql_1 = "UPDATE persona_sucursal SET estado='0' WHERE idusuario='$idusuario'";
      $papelera =  ejecutarConsulta($sql_1); if ( $papelera['status'] == false) {return $papelera; }

      // insertar
      $sql_2 = "INSERT INTO persona_sucursal(idsucursal, idusuario) VALUES ('$sucursal', '$idusuario')";
      $new_sucursal =  ejecutarConsulta($sql_2); if ( $new_sucursal['status'] == false) {return $new_sucursal; }

      //add registro en nuestra bitacora
      $sql_3 = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, codigo, id_user) VALUES ('persona_sucursal','".$new_sucursal['data']."','Registro creado', 'created_at', '$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql_3); if ( $bitacora['status'] == false) {return $bitacora; }  

      return $new_sucursal;
    } else {
      $info_repetida = ''; 
      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <span class="font-size-15px text-danger"><b>Nombre: </b>'.$value['sucursal'].'</span><br>
          <b>Direccion: </b>'.$value['direccion'].'<br>
          <b>Código: </b>'.$value['codigo'].'<br>
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      return array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
    }    
  }

  public function editar_sucursal($idpersona_sucursal, $idpersona_suc, $sucursal){   

    // editar
    $sql = "UPDATE persona_sucursal SET idsucursal='$sucursal', idusuario='$idpersona_suc'
    WHERE idpersona_sucursal= '$idpersona_sucursal'; ";
    $edit_sucursal = ejecutarConsulta($sql); if ( $edit_sucursal['status'] == false) {return $edit_sucursal; } 

    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, codigo, id_user) VALUES ('persona_sucursal','".$edit_sucursal['data']."','Registro actualizado', 'updated_at', '$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

    return $edit_sucursal;
  }

  public function tbla_principal_sucursal($id_persona){
    $sql = "SELECT ps.idpersona_sucursal, ps.idsucursal, ps.idusuario, ps.estado, ps.estado_delete, s.nombre as sucursal, s.direccion, s.codigo
    FROM persona_sucursal as ps, sucursal as s
    WHERE ps.idsucursal = s.idsucursal AND ps.idusuario = '$id_persona' AND ps.estado_delete = '1';";     
    return ejecutarConsulta($sql); 
  }

  public function desactivar_sucursal($idpersona_sucursal){    

    $sql = "UPDATE persona_sucursal SET estado='0' WHERE idpersona_sucursal='$idpersona_sucursal'";     
    return ejecutarConsulta($sql); 
  }

  public function activar_sucursal($idpersona_sucursal, $id_persona){

    // eliminamos todas las sucursales 
    $sql = "UPDATE persona_sucursal SET estado='0' WHERE idusuario='$id_persona'";
    $papelera =  ejecutarConsulta($sql); if ( $papelera['status'] == false) {return $papelera; }

    $sql = "UPDATE persona_sucursal SET estado='1' WHERE idpersona_sucursal='$idpersona_sucursal'";     
    return ejecutarConsulta($sql); 
  }

  public function eliminar_sucursal($idpersona_sucursal){
    $sql = "UPDATE persona_sucursal SET estado_delete='0' WHERE idpersona_sucursal='$idpersona_sucursal'";     
    return ejecutarConsulta($sql); 
  }

}

?>
