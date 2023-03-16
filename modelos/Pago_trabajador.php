<?php
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion_v2.php";
  // global $total;
  class PagoTrabajador
  {

    //Implementamos nuestro constructor
    public function __construct()
    {
    }
    public function insertar_mes_pago($idpersona,$nombres,$mes,$anio)
    {

      $sql="SELECT idmes_pago_trabajador, idpersona, mes_nombre, anio,estado,estado_delete 
      FROM mes_pago_trabajador 
      WHERE idpersona='$idpersona' AND mes_nombre='$mes' AND anio='$anio' ";

      $buscando = ejecutarConsultaArray($sql); if ($buscando['status'] == false) { return $buscando; }
  
      if ( empty($buscando['data']) ) {
        $sql="INSERT INTO mes_pago_trabajador (idpersona,mes_nombre,anio)
        VALUES ('$idpersona','$mes','$anio')";
        return ejecutarConsulta($sql);
      } else {
        $info_repetida = ''; 
  
        foreach ($buscando['data'] as $key => $value) {
          $info_repetida .= '<li class="text-left font-size-13px">
            <b>Nombre: </b>'.$nombres.'<br>
            <b>Descripción: </b>'. $value['mes_nombre'] . ' del '.$value['anio'].'<br>
            <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .'<br>
            <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
            <hr class="m-t-2px m-b-2px">
          </li>'; 
        }
        $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
        return $sw;
      }   


    }
    // =====================================================================================================
    // =====================================================================================================
    //================================== S E C C I O N   P A G O S ========================================= 
    // =====================================================================================================
    // =====================================================================================================

    //Implementamos un método para insertar registros
    public function insertar_pago($idmes_pago_trabajador_p,$nombre_mes,$monto,$fecha_pago,$descripcion,$comprobante)
    {
      $sql ="INSERT INTO pago_trabajador(idmes_pago_trabajador, fecha_pago, nombre_mes, monto, descripcion, comprobante)
      VALUES ('$idmes_pago_trabajador_p','$fecha_pago','$nombre_mes','$monto','$descripcion','$comprobante')";
      return ejecutarConsulta($sql);
    }

    //implementamos un metodo para editar registros
    public function editar_pago($idpago_trabajador,$idmes_pago_trabajador_p,$nombre_mes,$monto,$fecha_pago,$descripcion,$comprobante)
    {
      // var_dump($idpago_trabajador,$idmes_pago_trabajador_p,$nombre_mes,$monto,$fecha_pago,$descripcion,$comprobante);die();
      $sql="UPDATE pago_trabajador SET 
      idmes_pago_trabajador='$idmes_pago_trabajador_p',
      fecha_pago='$fecha_pago',
      nombre_mes='$nombre_mes',
      monto='$monto',
      descripcion='$descripcion',
      comprobante='$comprobante' 
      WHERE idpago_trabajador='$idpago_trabajador'";
      return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar registros
    public function desactivar_pago($idpago_trabajador)
    {
      $sql="UPDATE pago_trabajador SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idpago_trabajador='$idpago_trabajador'";
      $desactivar =  ejecutarConsulta($sql);

      if ( $desactivar['status'] == false) {return $desactivar; }  

      //add registro en nuestra bitacora
      $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_trabajador','.$idpago_trabajador.','Desativar el registro Trabajador','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

      return $desactivar;
    }
    
    //Implementamos un método para activar registros
    public function eliminar_pago($idpago_trabajador) {
      $sql="UPDATE pago_trabajador SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idpago_trabajador='$idpago_trabajador'";
      $eliminar =  ejecutarConsulta($sql);
      
      if ( $eliminar['status'] == false) {return $eliminar; }  

      //add registro en nuestra bitacora
      $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_trabajador','.$idpago_trabajador.','Eliminar registro Trabajador','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

      return $eliminar;
    }
    //Implementamos un método para mostrar los datos de un registro a modificar
    public function mostrar_pago($idpago_trabajador)
    {
      $sql="SELECT * FROM pago_trabajador WHERE idpago_trabajador='$idpago_trabajador'";
      return ejecutarConsultaSimpleFila($sql);
    }

    //Implementamos un método para listar los registros
    public function listar_pago($idmes_pago_trabajador)
    {
      $sql="SELECT pt.idpago_trabajador, pt.idmes_pago_trabajador, pt.fecha_pago, pt.nombre_mes, pt.monto, pt.descripcion, pt.comprobante 
      FROM pago_trabajador as pt, mes_pago_trabajador as mpt 
      WHERE pt.idmes_pago_trabajador = mpt.idmes_pago_trabajador AND pt.estado=1 AND pt.estado_delete=1 AND mpt.idmes_pago_trabajador='$idmes_pago_trabajador';";
      return ejecutarConsulta($sql);		
    }
    //total pagos por  total_pago_trabajador
    public function total_pago_trabajador($idmes_pago_trabajador)
    {
      $sql="SELECT SUM(monto) as total FROM pago_trabajador WHERE estado =1 AND estado_delete=1 AND idmes_pago_trabajador='$idmes_pago_trabajador'";
      return ejecutarConsultaSimpleFila($sql);  

    }
    // =====================================================================================================
    // =====================================================================================================
    // =====================================================================================================
    // =====================================================================================================

    //datos trabajador
    public function datos_trabajador($idtrabajador)
    {
      $sql = "SELECT p.idpersona, p.idtipo_persona, p.idbancos, p.idcargo_trabajador, p.nombres, p.tipo_documento, p.numero_documento, 
      p.fecha_nacimiento, p.edad, p.celular, p.direccion, p.correo, p.cuenta_bancaria, p.cci, 
      p.titular_cuenta, p.es_socio, p.sueldo_mensual, p.sueldo_diario, p.foto_perfil, ct.nombre as cargo,b.nombre as banco 
      FROM persona as p , cargo_trabajador as ct, bancos as b
      WHERE p.idcargo_trabajador = ct.idcargo_trabajador AND p.idbancos=b.idbancos AND p.idpersona='$idtrabajador';";
      return ejecutarConsultaSimpleFila($sql);
    }
    // Ver pagos trabajador
    public function verdatos($idpago_trabajador) {
      $sql=" SELECT pt.idpago_trabajador, pt.fecha_pago, pt.monto as monto_pago, pt.descripcion, pt.comprobante, t.idtrabajador, ct.nombre as cargo,
      t.nombres as nombre_trabajador, t.numero_documento, t.sueldo_mensual, t.imagen_perfil, t.tipo_documento, t.sueldo_diario, pt.estado
      FROM pago_trabajador as pt, trabajador as t, cargo_trabajador as ct
      WHERE pt.idtrabajador= t.idtrabajador AND t.idcargo_trabajador = ct.idcargo_trabajador  AND pt.idpago_trabajador='$idpago_trabajador' ";
      return ejecutarConsultaSimpleFila($sql);

    }

    public function tbla_mes_pago($idpersona) {
      $data = Array();
      $sql="SELECT idmes_pago_trabajador, mes_nombre, anio FROM mes_pago_trabajador WHERE idpersona='$idpersona'  AND estado=1 AND estado_delete =1";

      $pagos_meses = ejecutarConsultaArray($sql); if ($pagos_meses['status'] == false) { return  $pagos_meses;}

          // actualizamos el stock
      foreach ($pagos_meses['data'] as $key => $value) { 
           
        $slq2 = "SELECT SUM(monto) as total FROM pago_trabajador WHERE estado =1 AND estado_delete=1 AND idmes_pago_trabajador='".$value['idmes_pago_trabajador']."';";
        $total_por_meses = ejecutarConsultaSimpleFila($slq2); if ($total_por_meses['status'] == false) { return  $total_por_meses;}

        $pago_total_por_meses =(empty($total_por_meses['data']) ? 0 : (empty($total_por_meses['data']['total']) ? 0 : floatval($total_por_meses['data']['total']) ) );
        

        $data[] = [
          'idmes_pago_trabajador'   => $value['idmes_pago_trabajador'],
          'anio'  => $value['anio'],
          'mes_nombre'        => $value['mes_nombre'],
          'pago_total_por_meses'   => $pago_total_por_meses,
        ];
        
      }	
      return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data, 'affected_rows' =>$pagos_meses['affected_rows'],  ] ;

    }

    public function obtenerImg($idtrabajador) {

      $sql = "SELECT comprobante FROM pago_trabajador WHERE idtrabajador='$idtrabajador'";

      return ejecutarConsultaSimpleFila($sql);
    }

    public function formato_banco($idbanco){
      $sql="SELECT nombre, formato_cta, formato_cci, formato_detracciones FROM bancos WHERE estado='1' AND idbancos = '$idbanco';";
      return ejecutarConsultaSimpleFila($sql);		
    }

    /* =========================== S E C C I O N   R E C U P E R A R   B A N C O S =========================== */

    public function recuperar_banco(){
      $sql="SELECT idtrabajador, idbancos, cuenta_bancaria_format, cci_format FROM trabajador;";
      $bancos_old = ejecutarConsultaArray($sql);
      if ($bancos_old['status'] == false) { return $bancos_old;}	
      
      $bancos_new = [];
      foreach ($bancos_old['data'] as $key => $value) {
        $id = $value['idtrabajador']; 
        $idbancos = $value['idbancos']; 
        $cuenta_bancaria_format = $value['cuenta_bancaria_format']; 
        $cci_format = $value['cci_format'];

        $sql2="INSERT INTO cuenta_banco_trabajador( idtrabajador, idbancos, cuenta_bancaria, cci, banco_seleccionado) 
        VALUES ('$id','$idbancos','$cuenta_bancaria_format','$cci_format', '1');";
        $bancos_new = ejecutarConsulta($sql2);
        if ($bancos_new['status'] == false) { return $bancos_new;}
      } 
      
      return $bancos_new;
    }

  }

?>
