<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion_v2.php";

  class Persona
  {
    //Implementamos nuestro variable global
    public $id_usr_sesion;
    //Implementamos nuestro constructor
    public function __construct( $id_usr_sesion = 0 )
    {
      $this->id_usr_sesion = $id_usr_sesion;
    }

    public function insertar($id_tipo_persona,$tipo_documento,$num_documento,$nombre,$email,$telefono,
    $direccion,$nacimiento,$cargo_trabajador,$sueldo_mensual,$sueldo_diario, $imagen1) {
      $sw = Array();
      // var_dump($idcargo_persona,$nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad,  $email, $banco, $cta_bancaria,  $cci,  $titular_cuenta, $ruc, $imagen1); die();
      $sql_0 = "SELECT nombres,tipo_documento, numero_documento, correo, estado, estado_delete FROM persona as t WHERE numero_documento = '$num_documento';";
      $existe = ejecutarConsultaArray($sql_0); if ($existe['status'] == false) { return $existe;}
      
      if ( empty($existe['data']) ) {

        $sql="INSERT INTO persona(idtipo_persona, nombres, tipo_documento, numero_documento, celular, direccion, correo, fecha_nacimiento,idcargo_trabajador,sueldo_mensual,sueldo_diario, foto_perfil,user_created) 
        VALUES ('$id_tipo_persona','$nombre','$tipo_documento','$num_documento','$telefono','$direccion','$email','$nacimiento','$cargo_trabajador','$sueldo_mensual','$sueldo_diario','$imagen1', '$this->id_usr_sesion')";
        $new_persona = ejecutarConsulta_retornarID($sql);  if ($new_persona['status'] == false) { return $new_persona;}

        //add registro en nuestra bitacora
        $sql_d = $id_tipo_persona.', '.$tipo_documento.', '.$num_documento.', '.$nombre.', '.$email.', '.$telefono.', '.$direccion.', '.$nacimiento.', '.$cargo_trabajador.', '.$sueldo_mensual.', '.$sueldo_diario.', '.$imagen1;
        $sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5,'persona','".$new_persona['data']."','$sql_d','$this->id_usr_sesion')";
        $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
        
        $sw = array( 'status' => true, 'message' => 'noduplicado', 'data' => $new_persona['data'], 'id_tabla' =>$new_persona['id_tabla'] );

      } else {
        $info_repetida = ''; 

        foreach ($existe['data'] as $key => $value) {
          $info_repetida .= '<li class="text-left font-size-13px">
            <span class="font-size-15px text-danger"><b>Nombre: </b>'.$value['nombres'].'</span><br>
            <b>'.$value['tipo_documento'].': </b>'.$value['numero_documento'].'<br>
            <b>Correo: </b>'.$value['correo'].'<br>
            <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
            <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
            <hr class="m-t-2px m-b-2px">
          </li>'; 
        }
        $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
      }      
      
      return $sw;        
    }

    public function editar($idpersona,$id_tipo_persona,$tipo_documento,$num_documento,$nombre,$email,$telefono,
    $direccion,$nacimiento,$cargo_trabajador,$sueldo_mensual,$sueldo_diario, $imagen1) {
      $sql="UPDATE persona SET idtipo_persona='$id_tipo_persona',nombres='$nombre',
      tipo_documento='$tipo_documento',numero_documento='$num_documento',celular='$telefono',
      direccion='$direccion',correo='$email',
      fecha_nacimiento='$nacimiento',idcargo_trabajador='$cargo_trabajador',
      sueldo_mensual='$sueldo_mensual',sueldo_diario='$sueldo_diario',
      foto_perfil='$imagen1',
      user_updated= '$this->id_usr_sesion' WHERE idpersona='$idpersona'";	      
      $persona = ejecutarConsulta($sql); if ($persona['status'] == false) { return  $persona;}

      //add registro en nuestra bitacora
      $sql_d = $idpersona.', '.$id_tipo_persona.', '.$tipo_documento.', '.$num_documento.', '.$nombre.', '.$email.', '.$telefono.', '.$direccion.', '.$nacimiento.', '.$cargo_trabajador.', '.$sueldo_mensual.', '.$sueldo_diario.', '. $imagen1;
      $sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6,'persona','".$idpersona."','$sql_d','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
      
      // return $persona;     
      return array( 'status' => true, 'message' => 'todo ok', 'data' => $idpersona, 'id_tabla' =>$idpersona ); 
      
    }

    public function desactivar($id) {
      $sql="UPDATE persona SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idpersona='$id'";
      $desactivar =  ejecutarConsulta($sql);  if ( $desactivar['status'] == false) {return $desactivar; }  

      //add registro en nuestra bitacora
      $sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES ('persona','.$id.','$id','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

      return $desactivar;
    }

    public function eliminar($id) {
      $sql="UPDATE persona SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idpersona='$id'";
      $eliminar =  ejecutarConsulta($sql); if ( $eliminar['status'] == false) {return $eliminar; }  

      //add registro en nuestra bitacora
      $sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (4,'persona','$id','$id','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

      return $eliminar;
    }

    public function mostrar($idpersona) {
      $sql="SELECT * FROM persona WHERE idpersona='$idpersona'";
      return ejecutarConsultaSimpleFila($sql);

    }

    public function verdatos($idpersona) {
      $sql=" SELECT p.idpersona, p.idcargo_trabajador, cp.nombre as cargo, p.nombres, p.tipo_documento, 
      p.numero_documento, p.fecha_nacimiento,  p.sueldo_mensual, p.sueldo_diario, 
      p.direccion, p.celular, p.correo, p.foto_perfil, p.estado
      FROM persona as p, cargo_trabajador as cp
      WHERE p.idcargo_trabajador= cp.idcargo_trabajador AND p.idpersona='$idpersona' ";
      return ejecutarConsultaSimpleFila($sql);

    }

    public function tbla_principal($tipo_persona) {
      $filtro="";

      if ($tipo_persona=='todos') { $filtro = "AND p.idtipo_persona>1"; }else{ $filtro = "AND p.idtipo_persona='$tipo_persona' "; }

      $sql="SELECT p.idpersona, p.idtipo_persona,  p.nombres, p.tipo_documento, p.numero_documento, p.celular, p.direccion, p.correo, p.estado, 
      p.foto_perfil, p.sueldo_mensual, p.sueldo_diario, tp.nombre as tipo_persona, ct.nombre as cargo
      FROM persona as p,  tipo_persona as tp, cargo_trabajador as ct 
      WHERE p.idtipo_persona=tp.idtipo_persona  AND p.idcargo_trabajador = ct.idcargo_trabajador 
      $filtro AND p.estado ='1' AND p.estado_delete='1';";

      $persona = ejecutarConsultaArray($sql); if ($persona['status'] == false) { return  $persona;}
      
      return $persona;

    }

    public function obtenerImg($idpersona) {

      $sql = "SELECT foto_perfil FROM persona WHERE idpersona='$idpersona'";

      return ejecutarConsultaSimpleFila($sql);
    }

    /* =========================== S E C C I O N   R E C U P E R A R   B A N C O S =========================== */


    /* =========================== S E C C I O N  T I P O   P E R S O N A  =========================== */

    public function actualizar_tipo_persona($id_persona, $idtipo_persona)
    {
      $sql = "UPDATE persona SET idtipo_persona='$idtipo_persona' WHERE idpersona='$id_persona'";
      return ejecutarConsulta($sql);
    }

    public function tipo_persona()
    {
      $sql = "SELECT idtipo_persona, nombre FROM tipo_persona WHERE  estado=1 AND estado_delete=1 AND idtipo_persona>1;";
      return ejecutarConsultaArray($sql);
    }

  }

?>
