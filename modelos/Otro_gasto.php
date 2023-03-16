<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Otro_gasto
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  //$idotro_gasto,$idproyecto,$fecha_viaje,$tipo_viajero,$tipo_ruta,$cantidad,$precio_unitario,$precio_parcial,$ruta,$descripcion,$foto2
  //Implementamos un método para insertar registros
  public function insertar($idproyecto, $fecha_g, $precio_parcial, $subtotal, $igv,$val_igv,$tipo_gravada, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion, $glosa)
  {

    $sql_1 = "SELECT ruc, razon_social, tipo_comprobante, numero_comprobante, forma_de_pago, fecha_g, subtotal, costo_parcial,estado, estado_delete 
            FROM otro_gasto 
            WHERE idproyecto ='$idproyecto' AND ruc='$ruc' AND tipo_comprobante ='$tipo_comprobante' AND numero_comprobante ='$nro_comprobante';";
    $val_compr = ejecutarConsultaArray($sql_1);
    if ($val_compr['status'] == false) { return  $val_compr;}

    if (empty($val_compr['data']) || $tipo_comprobante=='Ninguno') {

      $sql = "INSERT INTO otro_gasto (idproyecto, tipo_comprobante, numero_comprobante, forma_de_pago, fecha_g, costo_parcial,subtotal,igv,val_igv,tipo_gravada,descripcion, comprobante,ruc,razon_social,direccion,glosa) 
      VALUES ('$idproyecto','$tipo_comprobante','$nro_comprobante','$forma_pago','$fecha_g','$precio_parcial','$subtotal','$igv','$val_igv','$tipo_gravada','$descripcion','$comprobante','$ruc', '$razon_social', '$direccion','$glosa')";
      return ejecutarConsulta($sql);

    } else {
      $info_repetida = '';

      foreach ($val_compr['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
        <span class="font-size-18px text-danger"><b >'.$value['tipo_comprobante'].': </b> '.$value['numero_comprobante'].'</span><br>
        <b>Razón Social: </b>'.$value['razon_social'].'<br>
        <b>Ruc: </b>'.$value['ruc'].'<br>
        <b>Fecha: </b>'.format_d_m_a($value['fecha_g']).'<br>
        <b>Forma de pago: </b>'.$value['forma_de_pago'].'<br>
        <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b>
        <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
        <hr class="m-t-2px m-b-2px">
        </li>';
      }
      return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );
    }

  }

  //Implementamos un método para editar registros
  public function editar($idotro_gasto, $idproyecto, $fecha_g, $precio_parcial, $subtotal, $igv,$val_igv,$tipo_gravada, $descripcion, $forma_pago, $tipo_comprobante, $nro_comprobante, $comprobante, $ruc, $razon_social, $direccion, $glosa)
  {

    if ($tipo_comprobante =='Factura' || $tipo_comprobante =='Boleta' ) { } else { $ruc =''; $razon_social =''; $direccion =''; }
    
    $sql = "UPDATE otro_gasto SET 
		idproyecto='$idproyecto',
		fecha_g='$fecha_g',
		costo_parcial='$precio_parcial',
		subtotal='$subtotal',
		igv='$igv',
		val_igv='$val_igv',
		tipo_gravada='$tipo_gravada',
		descripcion='$descripcion',
		forma_de_pago='$forma_pago',
		tipo_comprobante='$tipo_comprobante',
		numero_comprobante='$nro_comprobante',
		comprobante='$comprobante',
    ruc='$ruc',
    razon_social='$razon_social',
    direccion='$direccion',
    glosa='$glosa'

		WHERE idotro_gasto='$idotro_gasto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idotro_gasto)
  {
    $sql = "UPDATE otro_gasto SET estado='0' WHERE idotro_gasto ='$idotro_gasto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para desactivar categorías
  public function eliminar($idotro_gasto)
  {
    $sql = "UPDATE otro_gasto SET estado_delete='0' WHERE idotro_gasto ='$idotro_gasto'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idotro_gasto)
  {
    $sql = "SELECT*FROM otro_gasto   
		WHERE idotro_gasto ='$idotro_gasto'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function listar($idproyecto,$fecha_1,$fecha_2,$id_proveedor,$comprobante)
  {
      
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; $filtro_proveedor_1="";

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fecha_g BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND fecha_g = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND fecha_g = '$fecha_2'";
    }  

    if ( empty($id_proveedor)) {
      $filtro_proveedor = "";
    } else if ( $id_proveedor=='vacio' ) { 
      $filtro_proveedor = "AND ruc IN ('',NULL)";
    } else { 
      $filtro_proveedor = "AND ruc = '$id_proveedor'"; 
    }

    if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; } 

    $sql = "SELECT*FROM otro_gasto WHERE idproyecto='$idproyecto' AND estado_delete='1' AND estado='1' $filtro_proveedor $filtro_fecha $filtro_comprobante  ORDER BY idotro_gasto DESC";
    return ejecutarConsulta($sql);
  }

  //Seleccionar un comprobante
  public function ficha_tec($idotro_gasto)
  {
    $sql = "SELECT comprobante FROM otro_gasto WHERE idotro_gasto='$idotro_gasto'";
    return ejecutarConsulta($sql);
  }

  //total
  public function total($idproyecto,$fecha_1,$fecha_2,$id_proveedor,$comprobante)
  {
      
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND fecha_g BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND fecha_g = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND fecha_g = '$fecha_2'";
    }   

    if (empty($id_proveedor)) {
      $filtro_proveedor = "";
    } else if ( $id_proveedor=='vacio' ) { 
      $filtro_proveedor = "AND ruc IN ('',NULL)";
    } else { 
      $filtro_proveedor = "AND ruc = '$id_proveedor'"; 
    }

    if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND tipo_comprobante = '$comprobante'"; } 

    $sql = "SELECT SUM(costo_parcial) as precio_parcial FROM otro_gasto WHERE idproyecto='$idproyecto' $filtro_proveedor $filtro_fecha $filtro_comprobante  AND estado=1 AND estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //seelect2  - proveedores
  public function selecct_provedor_og($idproyecto)
  {
    $sql = "SELECT ruc,razon_social,direccion FROM otro_gasto WHERE ruc!='' AND ruc!='null' AND idproyecto = '$idproyecto'  GROUP BY ruc;";
    return ejecutarConsultaArray($sql);
  }

}

?>
