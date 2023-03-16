<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ConcretoAgregado
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  // :::::::::::::::::::::::::: S E C C I O N   I T E M S  ::::::::::::::::::::::::::

  //Implementamos un método para insertar registros
  public function insertar_item( $idproyecto, $nombre_item, $modulo, $columna_bombeado, $descripcion_item) {
    $sql = "SELECT  nombre, columna_servicio_bombeado,  descripcion, modulo, estado, estado_delete
    FROM tipo_tierra_concreto WHERE nombre = '$nombre_item' ;";
    $buscando = ejecutarConsultaArray($sql);
    if ($buscando['status'] == false) { return $buscando; }

    if ( empty($buscando['data']) ) {
      $sql = "INSERT INTO tipo_tierra_concreto (nombre, modulo, columna_servicio_bombeado, descripcion) 
      VALUES ('$nombre_item', '$modulo', '$columna_bombeado', '$descripcion_item')";
      return ejecutarConsulta($sql);
    } else {
      $info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Modulo: </b>'.$value['modulo'].'<br>
          <b>Nombre: </b>'.$value['nombre'].'<br>
          <b>Columna Calidad: </b>'.($value['columna_servicio_bombeado'] ? '<span class="text-center badge badge-success">Si</span>' : '<span class="text-center badge badge-danger">No</span>').'<br>
          <b>Descripción: </b>'.'<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $value['descripcion'] . '</textarea>'.'<br>
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
  public function editar_item( $idproyecto, $idtipo_tierra, $nombre_item, $modulo, $columna_bombeado, $descripcion_item)  {
     
    $sql = "UPDATE tipo_tierra_concreto SET nombre='$nombre_item', modulo='$modulo',
    columna_servicio_bombeado='$columna_bombeado', descripcion='$descripcion_item' 
    WHERE idtipo_tierra_concreto='$idtipo_tierra'";
    return ejecutarConsulta($sql);
  }

  public function desactivar_item($idtipo_tierra) {
    $sql = "UPDATE tipo_tierra_concreto SET estado='0' WHERE idtipo_tierra_concreto ='$idtipo_tierra'";
    return ejecutarConsulta($sql);
  }

  public function activar_item($idtipo_tierra)  {
    $sql = "UPDATE tipo_tierra_concreto SET estado='1' WHERE idtipo_tierra_concreto ='$idtipo_tierra'";
    return ejecutarConsulta($sql);
  }

  public function eliminar_item($idtipo_tierra) {
    $sql = "UPDATE tipo_tierra_concreto SET estado_delete='0' WHERE idtipo_tierra_concreto ='$idtipo_tierra'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar_item($idtipo_tierra) {
    $sql = "SELECT * FROM tipo_tierra_concreto WHERE  idtipo_tierra_concreto ='$idtipo_tierra'";

    return ejecutarConsultaSimpleFila($sql);    
  }

  //Implementar un método para listar los registros
  public function tbla_principal_item($id_proyecto) {
    $sql = "SELECT * FROM tipo_tierra_concreto WHERE  modulo ='Concreto y Agregado' AND estado_delete='1'  AND estado='1' ORDER BY nombre ASC";
    return ejecutarConsulta($sql);
  }
  
  //Implementar un método para listar los registros
  public function lista_de_items($id_proyecto) {
    $sql = "SELECT * FROM tipo_tierra_concreto WHERE  modulo ='Concreto y Agregado' AND estado_delete='1' AND estado='1' ORDER BY nombre ASC";
    return ejecutarConsultaArray($sql);
  }
  
  // :::::::::::::::::::::::::: S E C C I O N    C O N C R E T O    A G R E G A D O::::::::::::::::::::::::::

  //Implementamos un método para insertar registros
  public function insertar_concreto( $idtipo_tierra_c, $idproveedor, $fecha, $nombre_dia, $calidad, $cantidad, $precio_unitario, $total, $descripcion_concreto) {    
    
    $sql = "INSERT INTO concreto_agregado( idproveedor, idtipo_tierra, fecha, nombre_dia, calidad, cantidad, precio_unitario, total, detalle) 
    VALUES ('$idproveedor', '$idtipo_tierra_c', ' $fecha', '$nombre_dia', '$calidad', '$cantidad', '$precio_unitario', '$total', '$descripcion_concreto')";
    return ejecutarConsulta($sql);   
    
  }

  //Implementamos un método para editar registros
  public function editar_concreto($idconcreto_agregado, $idtipo_tierra_c, $idproveedor, $fecha, $nombre_dia, $calidad, $cantidad, $precio_unitario, $total, $descripcion_concreto)  {
     
    $sql = "UPDATE concreto_agregado 
    SET idproveedor='$idproveedor', idtipo_tierra='$idtipo_tierra_c', fecha='$fecha', nombre_dia='$nombre_dia',
    calidad='$calidad', cantidad='$cantidad', precio_unitario='$precio_unitario', total='$total', detalle='$descripcion_concreto' 
    WHERE idconcreto_agregado ='$idconcreto_agregado'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal_concreto($id_proyecto, $idtipo_tierra, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {
    $data = [];
    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND cpp.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND cpp.fecha_compra = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND cpp.fecha_compra = '$fecha_2'";
    }   
    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND cpp.idproveedor = '$id_proveedor'"; }

    // extraemos las compras segun: GRUPO
    $sql_1="SELECT cpp.idproyecto, cpp.idcompra_proyecto, cpp.fecha_compra, cpp.tipo_comprobante, cpp.serie_comprobante, 
    cpp.total as total_compra, cpp.estado as estado_compra,
    prov.razon_social, prov.tipo_documento, prov.ruc
    FROM compra_por_proyecto as cpp, detalle_compra as dc, producto as p, proveedor as prov
    WHERE cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = p.idproducto AND cpp.idproveedor = prov.idproveedor
    AND p.idtipo_tierra_concreto = '$idtipo_tierra' AND cpp.idproyecto = '$id_proyecto' AND cpp.estado ='1' AND cpp.estado_delete = '1' 
    $filtro_proveedor $filtro_fecha GROUP BY cpp.idcompra_proyecto ORDER BY cpp.fecha_compra DESC;";	
		$compra = ejecutarConsultaArray($sql_1);	

		if ($compra['status'] == false) { return $compra; }

    foreach ($compra['data'] as $key => $value) {
      $idcompra_proyecto = $value['idcompra_proyecto'];
      
      // Detalle compra
      $sql_2="SELECT 	dc.idproducto, p.nombre as nombre_producto, dc.cantidad, dc.precio_sin_igv, dc.igv, dc.precio_con_igv,
      dc.descuento, dc.subtotal, dc.unidad_medida, dc.color,  dc.ficha_tecnica_producto, p.imagen
      FROM detalle_compra AS dc, producto AS p, unidad_medida AS um, color AS c
      WHERE dc.idproducto=p.idproducto AND p.idcolor = c.idcolor 
      AND p.idunidad_medida = um.idunidad_medida AND dc.idcompra_proyecto='$idcompra_proyecto';";	
      $detalle_compra = ejecutarConsultaArray($sql_2);	
      if ($detalle_compra['status'] == false) { return $detalle_compra; }

      $estado_bombeado = '';
      $precio_bombeado = 0; $precio_sin_bombeado = 0;
      $descuento = 0; $cantidad_sin_bombeado = 0;
      $html_producto = ''; $precio_producto = 0;
      $cont_producto = 0;
      foreach ($detalle_compra['data'] as $key => $val) {
        if ($val['nombre_producto'] == 'SERVICIO BOMBEADO') {
          $estado_bombeado         = 'SI';
          $precio_bombeado        += floatval($val['subtotal']);
        } else {
          $cont_producto++;
          $estado_bombeado         = 'NO';
          $precio_sin_bombeado    += floatval($val['subtotal']);
          $cantidad_sin_bombeado  += floatval($val['cantidad']);
          $html_producto          .=  (count($detalle_compra['data']) > 2 ? '<p class="mb-0"><b class="mr-1">-</b>'.$val['nombre_producto'].'</p>' : $val['nombre_producto']);
          $precio_producto         =  floatval($val['precio_con_igv']);
        }   
        $descuento += floatval($val['descuento']);
      }

      $sql_3 = "SELECT COUNT(comprobante) as cant_comprobantes FROM factura_compra_insumo WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
      $cant_comprobantes = ejecutarConsultaSimpleFila($sql_3);
      if ($cant_comprobantes['status'] == false) { return $cant_comprobantes; }
    
      $data[] = [
        'idproyecto'            => $value['idproyecto'],
        'idcompra_proyecto'     => $value['idcompra_proyecto'],
        'fecha_compra'          => $value['fecha_compra'],
        'nombre_dia'            => nombre_dia_semana($value['fecha_compra']),        
        'nombre_producto'       => $html_producto,        
        'tipo_comprobante'      => $value['tipo_comprobante'],
        'serie_comprobante'     => $value['serie_comprobante'],
        'cantidad_sin_bombeado' => $cantidad_sin_bombeado,
        'precio_con_igv'        => $precio_producto,
        'descuento'             => $descuento,
        'subtotal_sin_bombeado' => $precio_sin_bombeado,
        'subtotal_bombeado'     => $precio_bombeado,
        'estado_bombeado'       => $estado_bombeado,
        'total_compra'          => $value['total_compra'],
        'proveedor'             => $value['razon_social'],
        'tipo_documento'        => $value['tipo_documento'],
        'ruc'                   => $value['ruc'],
        'estado_compra'         => $value['estado_compra'],    
        'cant_comprobantes'     => (empty($cant_comprobantes['data']['cant_comprobantes']) ? 0 : floatval($cant_comprobantes['data']['cant_comprobantes']) ),
      ];
    }
  
    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data, 'affected_rows' =>$compra['affected_rows'],  ] ;
  }

  //Implementar un método para listar los registros
  public function total_concreto($id_proyecto, $idtipo_tierra, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND ca.fecha BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND ca.fecha = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND ca.fecha = '$fecha_2'";
    }   
    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND p.idproveedor = '$id_proveedor'"; }

    $sql="SELECT  SUM(dc.cantidad) AS cantidad, AVG(dc.precio_con_igv) AS precio_promedio, SUM(dc.descuento) AS descuento, SUM(dc.subtotal) AS subtotal, SUM(cpp.total) as total_compra
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov, tipo_tierra_concreto as ttc 
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
    AND ttc.idtipo_tierra_concreto = pr.idtipo_tierra_concreto AND cpp.idproyecto ='$id_proyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1'
		AND cpp.idproveedor = prov.idproveedor AND pr.idtipo_tierra_concreto = '$idtipo_tierra' 
		ORDER BY cpp.fecha_compra DESC;";
    
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar_concreto($idconcreto_agregado) {
    $sql = "SELECT * FROM concreto_agregado WHERE  idconcreto_agregado ='$idconcreto_agregado'";
    return ejecutarConsultaSimpleFila($sql);    
  }

  // opciones
  public function desactivar_concreto($idconcreto_agregado) {
    $sql = "UPDATE concreto_agregado SET estado='0' WHERE idconcreto_agregado ='$idconcreto_agregado'";
    return ejecutarConsulta($sql);
  }

  public function activar_concreto($idconcreto_agregado)  {
    $sql = "UPDATE concreto_agregado SET estado='1' WHERE idconcreto_agregado ='$idconcreto_agregado'";
    return ejecutarConsulta($sql);
  }

  public function eliminar_concreto($idconcreto_agregado) {
    $sql = "UPDATE concreto_agregado SET estado_delete='0' WHERE idconcreto_agregado ='$idconcreto_agregado'";
    return ejecutarConsulta($sql);
  }

  // :::::::::::::::::::::::::: S E C C I O N    R E S U M E N ::::::::::::::::::::::::::
  //Implementar un método para listar los registros
  public function tbla_principal_resumen($idproyecto) {
    $sql="SELECT cpp.idproyecto, cpp.idcompra_proyecto, dc.iddetalle_compra, dc.idproducto, um.nombre_medida, um.abreviacion as um_abreviacion, 
		c.nombre_color, ttc.nombre as grupo, SUM(dc.cantidad) AS cantidad_total, SUM(dc.precio_con_igv) AS precio_con_igv, 
    SUM(dc.descuento) AS descuento_total, SUM(dc.subtotal) precio_total , COUNT(dc.idproducto) AS count_productos, 
    AVG(dc.precio_con_igv) AS promedio_precio
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, tipo_tierra_concreto AS ttc,
    unidad_medida AS um, color AS c
    WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
    AND um.idunidad_medida  = pr.idunidad_medida  AND c.idcolor = pr.idcolor  AND ttc.idtipo_tierra_concreto = pr.idtipo_tierra_concreto
    AND cpp.idproyecto = '$idproyecto'  AND cpp.estado = '1' AND cpp.estado_delete = '1' AND ttc.modulo ='Concreto y Agregado'
    AND pr.idtipo_tierra_concreto != '1' GROUP BY pr.idtipo_tierra_concreto ORDER BY ttc.nombre ASC;";
    return ejecutarConsulta($sql);
  }

  public function total_resumen($idproyecto) {
    $sql = "SELECT SUM( dc.subtotal ) AS total, SUM( dc.cantidad ) AS cantidad, SUM( dc.descuento ) AS descuento 
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, tipo_tierra_concreto AS ttc
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
		AND pr.idtipo_tierra_concreto=ttc.idtipo_tierra_concreto AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' 
    AND pr.idtipo_tierra_concreto != '1' AND cpp.estado_delete = '1';";
    return ejecutarConsultaSimpleFila($sql);
  }
}

?>
