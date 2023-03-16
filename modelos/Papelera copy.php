<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Papelera
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }


  public function tabla_principal($nube_idproyecto) {
    $data = Array();   
    
    /*----------------------------------------------------------------
    ---------------F I N  M O D U L O   O T R O S--------------------
    ----------------------------------------------------------------*/

    $sql_1 = "SELECT idbancos,  nombre, formato_cta, formato_cci, formato_detracciones, alias, created_at, updated_at, estado FROM bancos 
    WHERE estado = '0' AND estado_delete= '1';";
    $banco = ejecutarConsultaArray($sql_1);

    if ($banco['status'] == false) { return $banco; }

    if (!empty($banco['data'])) {
      foreach ($banco['data'] as $key => $value1) {
        $data[] = array(
          'nombre_tabla'    => 'bancos',
          'nombre_id_tabla' => 'idbancos',
          'id_tabla'        => $value1['idbancos'],
          'modulo'          => 'Bancos',
          'nombre_archivo'  => '<b>Banco: </b>'.$value1['nombre'].'<br>'. 
          '<b>Alias: </b>'.$value1['alias'].'<br>'.
          '<b>Formato Cta: </b>'.$value1['formato_cta'].'<br>'. 
          '<b>Formato Cci: </b>'.$value1['formato_cci'].'<br>'. 
          '<b>Formato Dtrac: </b>'.$value1['formato_detracciones'].'<br>' ,
          'descripcion'     => '- - -',
          'created_at'      => $value1['created_at'],
          'updated_at'      => $value1['updated_at'],
        );
      }
    }
    $sql2 = "SELECT idunidad_medida, nombre, descripcion, created_at, updated_at FROM unidad_medida WHERE estado='0' AND estado_delete=1;";
    $unidad_medida = ejecutarConsultaArray($sql2); if ($unidad_medida['status'] == false) { return $unidad_medida; }

    if (!empty($unidad_medida['data'])) {
      foreach ($unidad_medida['data'] as $key => $value2) {
        $data[] = array(
          'nombre_tabla'    => 'unidad_medida',
          'nombre_id_tabla' => 'idunidad_medida',
          'modulo'          => 'Unidad medida',
          'id_tabla'        => $value2['idunidad_medida'],
          'nombre_archivo'  => $value2['nombre'],
          'descripcion'     => ' - - - ',
          'created_at'      => $value2['created_at'],
          'updated_at'      => $value2['updated_at'],
        );
      }
    }
    //sql para mostrar los datos de tipo_persona
    $sql3 = "SELECT idtipo_persona, nombre, descripcion, created_at, updated_at FROM tipo_persona WHERE estado='0' AND estado_delete=1;";
    $tipo_persona = ejecutarConsultaArray($sql3); if ($tipo_persona['status'] == false) { return $tipo_persona; }
    
    if (!empty($tipo_persona['data'])) {
      foreach ($tipo_persona['data'] as $key => $value3) {
        $data[] = array(
          'nombre_tabla'    => 'tipo_persona',
          'nombre_id_tabla' => 'idtipo_persona',
          'modulo'          => 'tipo persona',
          'id_tabla'        => $value3['idtipo_persona'],
          'nombre_archivo'  => $value3['nombre'],
          'descripcion'     => ' - - - ',
          'created_at'      => $value3['created_at'],
          'updated_at'      => $value3['updated_at'],
        );
      }
    }
    //sql para mostrar los datos de cargos
    $sql4 = "SELECT idcargo_trabajador, nombre, created_at, updated_at FROM cargo_trabajador WHERE estado='0' AND estado_delete=1;";
    $cargo_trabajador = ejecutarConsultaArray($sql4); if ($cargo_trabajador['status'] == false) { return $cargo_trabajador; }

    if (!empty($cargo_trabajador['data'])) {
      foreach ($cargo_trabajador['data'] as $key => $value4) {
        $data[] = array(
          'nombre_tabla'    => 'cargo_trabajador',
          'nombre_id_tabla' => 'idcargo_trabajador',
          'modulo'          => 'Cargo trabajador',
          'id_tabla'        => $value4['idcargo_trabajador'],
          'nombre_archivo'  => $value4['nombre'],
          'descripcion'     => ' - - - ',
          'created_at'      => $value4['created_at'],
          'updated_at'      => $value4['updated_at'],
        );
      }
    }
    //sql para mostrar los datos de categoria_producto
    $sql5 = "SELECT idcategoria_producto, nombre, descripcion, created_at, updated_at FROM categoria_producto WHERE estado='0' AND estado_delete=1;";
    $categoria_producto = ejecutarConsultaArray($sql5); if ($categoria_producto['status'] == false) { return $categoria_producto; }
    if (!empty($categoria_producto['data'])) {
      foreach ($categoria_producto['data'] as $key => $value5) {
        $data[] = array(
          'nombre_tabla'    => 'categoria_producto',
          'nombre_id_tabla' => 'idcategoria_producto',
          'modulo'          => 'categoría producto',
          'id_tabla'        => $value5['idcategoria_producto'],
          'nombre_archivo'  => $value5['nombre'],
          'descripcion'     => ' - - - ',
          'created_at'      => $value5['created_at'],
          'updated_at'      => $value5['updated_at'],
        );
      }
    }

   /*----------------------------------------------------------------
    ---------------F I N  M O D U L O   O T R O S--------------------
    ----------------------------------------------------------------*/

      





    $sql_2 = "SELECT idcargo_trabajador,  nombre, estado, created_at, updated_at FROM cargo_trabajador WHERE estado = '0' AND estado_delete = '1';";
    $cargo_trabajador = ejecutarConsultaArray($sql_2);

    if ($cargo_trabajador['status'] == false) { return $cargo_trabajador; }

    if (!empty($cargo_trabajador['data'])) {
      foreach ($cargo_trabajador['data'] as $key => $value2) {
        $data[] = array(
          'nombre_tabla'    => 'cargo_trabajador',
          'nombre_id_tabla' => 'idcargo_trabajador',
          'id_tabla'        => $value2['idcargo_trabajador'],
          'modulo'          => 'Cargo Trabajdor',
          'nombre_archivo'  => $value2['nombre'],
          'descripcion'     => '- - -',
          'nombre_proyecto'  => 'General',
          'created_at'      => $value2['created_at'],
          'updated_at'      => $value2['updated_at'],
        );
      }
    }

    $sql_3 = "SELECT cpt.idcarpeta, cpt.nombre, cpt.estado, cpt.created_at, cpt.updated_at,p.nombre_codigo
    FROM carpeta_plano_otro as cpt, proyecto as p
    WHERE cpt.estado = '0' AND cpt.estado_delete = '1' AND cpt.idproyecto = '$nube_idproyecto' AND cpt.idproyecto=p.idproyecto";
    $carpeta_plano_otro = ejecutarConsultaArray($sql_3);

    if ($carpeta_plano_otro['status'] == false) { return $carpeta_plano_otro; }

    if (!empty($carpeta_plano_otro['data'])) {
      foreach ($carpeta_plano_otro['data'] as $key => $value3) {
        $data[] = array(
          'nombre_tabla'    => 'carpeta_plano_otro',
          'nombre_id_tabla' => 'idcarpeta',
          'id_tabla'        => $value3['idcarpeta'],
          'modulo'          => 'Carpeta planos y Otros',
          'nombre_archivo'  => $value3['nombre'],
          'descripcion'     => '- - -',
          'nombre_proyecto'  => $value3['nombre_codigo'],
          'created_at'      => $value3['created_at'],
          'updated_at'      => $value3['updated_at'],
        );
      }
    }
    
    $sql_4 = "SELECT idcategoria_insumos_af, nombre, estado, created_at, updated_at FROM categoria_insumos_af WHERE estado = '0' AND estado_delete = '1';";
    $categoria_insumos_af = ejecutarConsultaArray($sql_4);

    if ($categoria_insumos_af['status'] == false) { return $categoria_insumos_af; }

    if (!empty($categoria_insumos_af['data'])) {
      foreach ($categoria_insumos_af['data'] as $key => $value4) {
        $data[] = array(
          'nombre_tabla'    => 'categoria_insumos_af',
          'nombre_id_tabla' => 'idcategoria_insumos_af',
          'id_tabla'        => $value4['idcategoria_insumos_af'],
          'modulo'          => 'Clasificación de Productos',
          'nombre_archivo'  => $value4['nombre'],
          'descripcion'     => '- - -',
          'nombre_proyecto'  => 'General',
          'created_at'      => $value4['created_at'],
          'updated_at'      => $value4['updated_at'],
        );
      }
    }

    $sql_5 = "SELECT idcolor, nombre_color, estado, created_at, updated_at FROM color WHERE estado = '0' AND estado_delete = '1';";
    $color = ejecutarConsultaArray($sql_5);

    if ($color['status'] == false) { return $color; }

    if (!empty($color['data'])) {
      foreach ($color['data'] as $key => $value5) {
        $data[] = array(
          'nombre_tabla'    => 'color',
          'nombre_id_tabla' => 'idcolor',
          'id_tabla'        => $value5['idcolor'],
          'modulo'          => 'Color',
          'nombre_archivo'  => $value5['nombre_color'],
          'descripcion'     => '- - -',
          'nombre_proyecto'  => 'General',
          'created_at'      => $value5['created_at'],
          'updated_at'      => $value5['updated_at'],
        );
      }
    }

    $sql_6 = "SELECT cx.idcomida_extra, cx.tipo_comprobante, cx.razon_social, cx.costo_parcial, cx.numero_comprobante, cx.descripcion, cx.estado, 
    cx.created_at, cx.updated_at, p.nombre_codigo
    FROM comida_extra as cx, proyecto as p
    WHERE cx.estado = '0' AND cx.estado_delete = '1' AND cx.idproyecto ='$nube_idproyecto' AND  cx.idproyecto=p.idproyecto;";
    $comida_extra = ejecutarConsultaArray($sql_6);

    if ($comida_extra['status'] == false) { return $comida_extra; }

    if (!empty($comida_extra['data'])) {
      foreach ($comida_extra['data'] as $key => $value6) {
        $data[] = array(
          'nombre_tabla'    => 'comida_extra',
          'nombre_id_tabla' => 'idcomida_extra',
          'id_tabla'        => $value6['idcomida_extra'],
          'modulo'          => 'Comida Extras',
          'nombre_archivo'  => '<b>'.$value6['tipo_comprobante'].': </b>'.$value6['numero_comprobante'].'<br>'.
          '<b>Monto: </b>'.number_format($value6['costo_parcial'], 2, '.', ',').'<br>' .
          '<b>Proveedor: </b>'.$value6['razon_social'].'<br>',
          'descripcion'     => $value6['descripcion'],
          'nombre_proyecto'  => $value6['nombre_codigo'],
          'created_at'      => $value6['created_at'],
          'updated_at'      => $value6['updated_at'],
        );
      }
    }

    $sql_7 = "SELECT idcompra_af_general, tipo_comprobante, serie_comprobante, descripcion, estado, created_at, updated_at 
    FROM compra_af_general WHERE estado = '0' AND estado_delete = '1'";
    $compra_af_general = ejecutarConsultaArray($sql_7);

    if ($compra_af_general['status'] == false) { return $compra_af_general; }

    if (!empty($compra_af_general['data'])) {
      foreach ($compra_af_general['data'] as $key => $value7) {
        $data[] = array(
          'nombre_tabla'    => 'compra_af_general',
          'nombre_id_tabla' => 'idcompra_af_general',
          'id_tabla'        => $value7['idcompra_af_general'],
          'modulo'          => 'All Activos Fijo',
          'nombre_archivo'  => $value7['tipo_comprobante'] . ': ' . $value7['serie_comprobante'],
          'descripcion'     => $value7['descripcion'],
          'nombre_proyecto'  => 'General',
          'created_at'      => $value7['created_at'],
          'updated_at'      => $value7['updated_at'],
        );
      }
    }

    $sql_8 = "SELECT cpp.idcompra_proyecto, cpp.total, cpp.tipo_comprobante, cpp.serie_comprobante, cpp.descripcion, cpp.estado, cpp.created_at, 
    cpp.updated_at, p.nombre_codigo, prov.razon_social
    FROM compra_por_proyecto as cpp, proyecto as p, proveedor AS prov
    WHERE cpp.idproyecto=p.idproyecto AND cpp.idproveedor = prov.idproveedor  AND cpp.estado = '0' AND cpp.estado_delete = '1' 
    AND cpp.idproyecto = '$nube_idproyecto';";
    $compra_por_proyecto = ejecutarConsultaArray($sql_8);

    if ($compra_por_proyecto['status'] == false) { return $compra_por_proyecto; }

    if (!empty($compra_por_proyecto['data'])) {
      foreach ($compra_por_proyecto['data'] as $key => $value8) {
        $data[] = array(
          'nombre_tabla'    => 'compra_por_proyecto',
          'nombre_id_tabla' => 'idcompra_proyecto',
          'modulo'          => 'Compras',
          'id_tabla'        => $value8['idcompra_proyecto'],
          'nombre_archivo'  => '<b>'.$value8['tipo_comprobante'].': </b>'.$value8['serie_comprobante'].'<br>'.
          '<b>Monto: </b>'.number_format($value8['total'], 2, '.', ',').'<br>'.
          '<b>Proveedor: </b>'.$value8['razon_social'].'<br>' ,
          'descripcion'     => $value8['descripcion'],
          'nombre_proyecto'  => $value8['nombre_codigo'],
          'created_at'      => $value8['created_at'],
          'updated_at'      => $value8['updated_at'],
        );
      }
    }

    $sql_9 = "SELECT f.idfactura, m.nombre AS maquina, f.codigo, f.tipo_comprobante, f.codigo, f.monto, f.descripcion, f.estado, 
    f.created_at, f.updated_at, p.nombre_codigo
    FROM factura AS f, maquinaria AS m, proyecto AS p
    WHERE f.idmaquinaria = m.idmaquinaria AND f.idproyecto=p.idproyecto AND m.tipo = '1' AND f.estado = '0' AND f.estado_delete = '1'
    AND f.idproyecto = '$nube_idproyecto';";
    $factura_m = ejecutarConsultaArray($sql_9);

    if ($factura_m['status'] == false) { return $factura_m; }

    if (!empty($factura_m['data'])) {
      foreach ($factura_m['data'] as $key => $value9) {
        $data[] = array(
          'nombre_tabla'    => 'factura',
          'nombre_id_tabla' => 'idfactura',
          'modulo'          => 'Factura Servicio Maquina',
          'id_tabla'        => $value9['idfactura'],
          'nombre_archivo'  => '<b>'.$value9['tipo_comprobante'].': </b>'.$value9['codigo'].'<br>'.
          '<b>Monto: </b>'.number_format($value9['monto'], 2, '.', ',').'<br>'.
          '<b>Máquina: </b>'.$value9['maquina'].'<br>' ,
          'descripcion'     => $value9['descripcion'],
          'nombre_proyecto'  => $value9['nombre_codigo'],
          'created_at'      => $value9['created_at'],
          'updated_at'      => $value9['updated_at'],
        );
      }
    }

    $sql_10 = "SELECT f.idfactura, m.nombre AS maquina, f.codigo, f.tipo_comprobante, f.codigo, f.monto, f.descripcion, f.estado, 
    f.created_at, f.updated_at, p.nombre_codigo
    FROM factura AS f, maquinaria AS m, proyecto AS p
    WHERE f.idmaquinaria = m.idmaquinaria AND m.tipo = '2' AND f.estado = '0' AND f.estado_delete = '1' AND 
    f.idproyecto = '$nube_idproyecto' AND  f.idproyecto=p.idproyecto;";
    $factura_e = ejecutarConsultaArray($sql_10);

    if ($factura_e['status'] == false) { return $factura_e; }

    if (!empty($factura_e['data'])) {
      foreach ($factura_e['data'] as $key => $value10) {
        $data[] = array(
          'nombre_tabla'    => 'factura',
          'nombre_id_tabla' => 'idfactura',
          'modulo'          => 'Factura Servicio Equipo',
          'id_tabla'        => $value10['idfactura'],
          'nombre_archivo'  => '<b>'.$value10['tipo_comprobante'].': </b>'.$value10['codigo'].'<br>'.
          '<b>Monto: </b>'.number_format($value10['monto'], 2, '.', ',').'<br>'.
          '<b>Máquina: </b>'.$value10['maquina'].'<br>' ,
          'descripcion'     => $value10['descripcion'],
          'nombre_proyecto'  => $value10['nombre_codigo'],
          'created_at'      => $value10['created_at'],
          'updated_at'      => $value10['updated_at'],
        );
      }
    }

    $sql_11 = "SELECT fb.idfactura_break, fb.tipo_comprobante, fb.nro_comprobante, fb.descripcion, fb.estado, fb.created_at, 
    fb.updated_at, p.nombre_codigo, fb.monto, sb.numero_semana, sb.fecha_inicial, sb.fecha_final
    FROM factura_break AS fb, semana_break AS sb, proyecto AS p
    WHERE fb.idsemana_break = sb.idsemana_break AND fb.estado = '0' AND fb.estado_delete = '1' AND sb.idproyecto = '$nube_idproyecto'  AND sb.idproyecto = p.idproyecto";
    $factura_break = ejecutarConsultaArray($sql_11);

    if ($factura_break['status'] == false) { return $factura_break; }

    if (!empty($factura_break['data'])) {
      foreach ($factura_break['data'] as $key => $value11) {
        $data[] = array(
          'nombre_tabla'    => 'factura',
          'nombre_id_tabla' => 'idfactura_break',
          'modulo'          => 'Breack',
          'id_tabla'        => $value11['idfactura_break'],
          'nombre_archivo'  => '<b>'.$value11['tipo_comprobante'].': </b>'.$value11['nro_comprobante'].'<br>'.
          '<b>Monto: </b>'.number_format($value11['monto'], 2, '.', ',').'<br>'.
          '<b>Semana: </b>'. $value11['fecha_inicial'].' - '.$value11['fecha_final'].'<br>'.  
          '<b>Num: </b>'.$value11['numero_semana'].'<br>',
          'descripcion'     => $value11['descripcion'],
          'nombre_proyecto'  => $value11['nombre_codigo'],
          'created_at'      => $value11['created_at'],
          'updated_at'      => $value11['updated_at'],
        );
      }
    }

    $sql_12 = "SELECT dp.iddetalle_pension, dp.tipo_comprobante, dp.numero_comprobante, dp.descripcion, dp.estado,  dp.created_at, 
    dp.updated_at, proy.nombre_codigo, prov.razon_social, dp.precio_parcial
    FROM detalle_pension AS dp, pension AS p, proyecto AS proy, proveedor AS prov
    WHERE dp.idpension = p.idpension  AND p.idproyecto = proy.idproyecto AND p.idproveedor = prov.idproveedor AND dp.estado = '0' AND     dp.estado_delete = '1' AND p.idproyecto = '$nube_idproyecto';";
      $detalle_pension = ejecutarConsultaArray($sql_12);

    if ($detalle_pension['status'] == false) { return $detalle_pension; }

    if (!empty($detalle_pension['data'])) {
      foreach ($detalle_pension['data'] as $key => $value12) {
        $data[] = array(
          'nombre_tabla'    => 'detalle_pension',
          'nombre_id_tabla' => 'iddetalle_pension',
          'modulo'          => 'Detalle Pensión',
          'id_tabla'        => $value12['iddetalle_pension'],
          'nombre_archivo'  => '<b>'.$value12['tipo_comprobante'].': </b>'.$value12['numero_comprobante'].'<br>'.
          '<b>Monto: </b>'.number_format($value12['precio_parcial'], 2, '.', ',').'<br>' .
          '<b>Proveedor: </b>'. $value12['razon_social']. '<br>' ,
          'descripcion'     => $value12['descripcion'],
          'nombre_proyecto'  => $value12['nombre_codigo'],
          'created_at'      => $value12['created_at'],
          'updated_at'      => $value12['updated_at'],
        );
      }
    }

    $sql_13 = "SELECT h.idhospedaje, h.tipo_comprobante, h.numero_comprobante, h.razon_social, h.precio_parcial, h.descripcion, h.estado, 
    h.estado_delete, h.created_at, h.updated_at, p.nombre_codigo
    FROM hospedaje as h, proyecto AS p
    WHERE  h.idproyecto = p.idproyecto AND h.estado = '0' AND h.estado_delete = '1' AND h.idproyecto = '$nube_idproyecto' ;";
    $hospedaje = ejecutarConsultaArray($sql_13);

    if ($hospedaje['status'] == false) { return $hospedaje; }

    if (!empty($hospedaje['data'])) {
      foreach ($hospedaje['data'] as $key => $value13) {
        $data[] = array(
          'nombre_tabla'    => 'hospedaje',
          'nombre_id_tabla' => 'idhospedaje',
          'modulo'          => 'Hospedaje',
          'id_tabla'        => $value13['idhospedaje'],
          'nombre_archivo'  => '<b>'.$value13['tipo_comprobante'].': </b>'.$value13['numero_comprobante'].'<br>'.
          '<b>Monto: </b>'.number_format($value13['precio_parcial'], 2, '.', ',').'<br>'. 
          '<b>Proveedor: </b>'. $value13['razon_social']. '<br>' ,
          'descripcion'     => $value13['descripcion'],
          'nombre_proyecto'  => $value13['nombre_codigo'],
          'created_at'      => $value13['created_at'],
          'updated_at'      => $value13['updated_at'],
        );
      }
    }

    $sql_14 = "SELECT m.idmaquinaria,  m.nombre, p.razon_social AS proveedor, m.codigo_maquina, m.tipo, m.estado, m.created_at, m.updated_at
    FROM maquinaria AS m, proveedor AS p
    WHERE m.idproveedor = p.idproveedor AND m.estado = '0' AND m.estado_delete = '1';";
    $maquinaria = ejecutarConsultaArray($sql_14);

    if ($maquinaria['status'] == false) { return $maquinaria; }

    if (!empty($maquinaria['data'])) {
      foreach ($maquinaria['data'] as $key => $value14) {
        $data[] = array(
          'nombre_tabla'    => 'maquinaria',
          'nombre_id_tabla' => 'idmaquinaria',
          'modulo'          => 'Maquinaria y Equipos',
          'id_tabla'        => $value14['idmaquinaria'],
          'nombre_archivo'  => '<b>'.($value14['tipo'] == '1' ? 'Maquina' : 'Equipo' ) .': </b>'. $value14['nombre']. '<br>'.
          '<b>Modelo: </b>'.$value14['codigo_maquina'].'<br>'.
          '<b>Proveedor: </b>'.$value14['proveedor'].'<br>' ,
          'descripcion'     =>'- - -',
          'nombre_proyecto' => 'General',
          'created_at'      => $value14['created_at'],
          'updated_at'      => $value14['updated_at'],
        );
      }
    }

    $sql_15 = "SELECT idocupacion, nombre_ocupacion, estado, created_at, updated_at FROM ocupacion WHERE estado  = '0' AND estado_delete = '1';";
    $ocupacion = ejecutarConsultaArray($sql_15);

    if ($ocupacion['status'] == false) { return $ocupacion; }

    if (!empty($ocupacion['data'])) {
      foreach ($ocupacion['data'] as $key => $value15) {
        $data[] = array(
          'nombre_tabla'    => 'ocupacion',
          'nombre_id_tabla' => 'idocupacion',
          'modulo'          => 'Ocupación',
          'id_tabla'        => $value15['idocupacion'],
          'nombre_archivo'  => $value15['nombre_ocupacion'],
          'descripcion'     => '- - -',
          'nombre_proyecto'  => 'General',
          'created_at'      => $value15['created_at'],
          'updated_at'      => $value15['updated_at'],
        );
      }
    }
    
    $sql_16 = "SELECT os.idotro_servicio, os.tipo_comprobante, os.numero_comprobante, os.descripcion, os.estado, os.created_at, os.updated_at, p.nombre_codigo
    FROM otro_servicio as os, proyecto AS p 
    WHERE os.estado = '0' AND os.estado_delete = '1' AND  os.idproyecto  = '$nube_idproyecto' AND os.idproyecto = p.idproyecto;";
    $otro_servicio = ejecutarConsultaArray($sql_16);

    if ($otro_servicio['status'] == false) { return $otro_servicio; }

    if (!empty($otro_servicio['data'])) {
      foreach ($otro_servicio['data'] as $key => $value16) {
        $data[] = array(
          'nombre_tabla'    => 'otro_servicio',
          'nombre_id_tabla' => 'idotro_servicio',
          'modulo'          => 'Otros Servicio',
          'id_tabla'        => $value16['idotro_servicio'],
          'nombre_archivo'  => $value16['tipo_comprobante'] . ': ' . $value16['numero_comprobante'] ,
          'descripcion'     => $value16['descripcion'],
          'nombre_proyecto'  => $value16['nombre_codigo'],
          'created_at'      => $value16['created_at'],
          'updated_at'      => $value16['updated_at'],
        );
      }
    }

    $sql_17 = "SELECT pqso.idpagos_q_s_obrero, pqso.monto_deposito, t.nombres AS trabajador, pqso.descripcion, pqso.estado,  pqso.created_at, 
    pqso.updated_at, p.nombre_codigo , p.fecha_pago_obrero, rqsa.numero_q_s, rqsa.fecha_q_s_inicio, rqsa.fecha_q_s_fin
    FROM pagos_q_s_obrero AS pqso, resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp, trabajador AS t, proyecto AS p 
    WHERE pqso.idresumen_q_s_asistencia = rqsa.idresumen_q_s_asistencia AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idtrabajador = t.idtrabajador AND
    tpp.idproyecto = '$nube_idproyecto' AND pqso.estado = '0' AND pqso.estado_delete = '1' AND tpp.idproyecto = p.idproyecto; ";
    $pagos_q_s_obrero = ejecutarConsultaArray($sql_17);

    if ($pagos_q_s_obrero['status'] == false) { return $pagos_q_s_obrero; }

    if (!empty($pagos_q_s_obrero['data'])) {
      foreach ($pagos_q_s_obrero['data'] as $key => $value17) {
        $data[] = array(
          'nombre_tabla'    => 'pagos_q_s_obrero',
          'nombre_id_tabla' => 'idpagos_q_s_obrero',
          'modulo'          => 'Pago Obrero',
          'id_tabla'        => $value17['idpagos_q_s_obrero'],
          'nombre_archivo'  => '<b>Trabajador: </b>'. $value17['trabajador']. '<br>'.
          '<b>Monto pago: </b>'.number_format($value17['monto_deposito'], 2, '.', ',').'<br>'.
          '<b>'.$value17['fecha_pago_obrero'].': </b>'.$value17['fecha_q_s_inicio'] . ' - ' . $value17['fecha_q_s_fin'] . '<br>'.
          '<b>Num: </b>'.$value17['numero_q_s']. '<br>',
          'descripcion'     => $value17['descripcion'],
          'nombre_proyecto'  => $value17['nombre_codigo'],
          'created_at'      => $value17['created_at'],
          'updated_at'      => $value17['updated_at'],
        );
      }
    }

    $sql_18 = "SELECT pxma.idpagos_x_mes_administrador, t.nombres AS trabajador, pxma.monto, pxma.descripcion, pxma.estado, pxma.created_at, 
    pxma.updated_at, p.nombre_codigo, fmpa.fecha_inicial, fmpa.fecha_final, fmpa.nombre_mes 
    FROM pagos_x_mes_administrador AS pxma, fechas_mes_pagos_administrador AS fmpa, trabajador_por_proyecto AS tpp, trabajador AS t, proyecto AS p 
    WHERE pxma.idfechas_mes_pagos_administrador = fmpa.idfechas_mes_pagos_administrador AND fmpa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto 
    AND tpp.idtrabajador = t.idtrabajador AND pxma.estado = '0' AND pxma.estado_delete = '1' AND tpp.idproyecto = '$nube_idproyecto' AND tpp.idproyecto = p.idproyecto;";
    $pagos_x_mes_administrador = ejecutarConsultaArray($sql_18);

    if ($pagos_x_mes_administrador['status'] == false) { return $pagos_x_mes_administrador; }

    if (!empty($pagos_x_mes_administrador['data'])) {
      foreach ($pagos_x_mes_administrador['data'] as $key => $value18) {
        $data[] = array(
          'nombre_tabla'    => 'pagos_x_mes_administrador',
          'nombre_id_tabla' => 'idpagos_x_mes_administrador',
          'modulo'          => 'Pago Administrador',
          'id_tabla'        => $value18['idpagos_x_mes_administrador'],
          'nombre_archivo'  => '<b>Trabajador: </b>'. $value18['trabajador']. '<br>'.
          '<b>Monto pago: </b>'.number_format($value18['monto'], 2, '.', ',').'<br>'.
          '<b>Fechas: </b>'.$value18['fecha_inicial'] . ' - ' . $value18['fecha_final'] . '<br>'.
          '<b>Mes: </b>'.$value18['nombre_mes']. '<br>',
          'descripcion'     => $value18['descripcion'],
          'nombre_proyecto' => $value18['nombre_codigo'],
          'created_at'      => $value18['created_at'],
          'updated_at'      => $value18['updated_at'],
        );
      }
    }

    $sql_19 = "SELECT idpago_af_general, beneficiario, monto, descripcion, fecha_pago, created_at, updated_at
    FROM pago_af_general WHERE  estado='0' AND estado_delete='1'";
    $pago_af_general = ejecutarConsultaArray($sql_19);

    if ($pagos_x_mes_administrador['status'] == false) { return $pagos_x_mes_administrador; }

    if (!empty($pago_af_general['data'])) {
      foreach ($pago_af_general['data'] as $key => $value19) {
        $data[] = array(
          'nombre_tabla'    => 'pago_af_general',
          'nombre_id_tabla' => 'idpago_af_general',
          'modulo'          => 'Pago activo general',
          'id_tabla'        => $value19['idpago_af_general'],
          'nombre_archivo'  => '<b>Beneficiario: </b>'. $value19['beneficiario']. '<br>'.
          '<b>Monto pago: </b>'.number_format($value19['monto'], 2, '.', ',').'<br>'.
          '<b>Fecha: </b>'.$value19['fecha_pago'] . '<br>'.
          '<b>'.$value19['tipo_pago']. ': </b>'.$value19['numero_operacion']. '<br>',
          'descripcion'     => $value19['descripcion'],
          'nombre_proyecto'  => 'General',
          'created_at'      => $value19['created_at'],
          'updated_at'      => $value19['updated_at'],
        );
      }
    }

    $sql_20 = "SELECT pc.idpago_compras, pc.beneficiario, pc.descripcion, pc.monto, pc.created_at, pc.updated_at, p.nombre_codigo, pc.fecha_pago,
    pc.tipo_pago, pc.numero_operacion
    FROM pago_compras as pc, compra_por_proyecto as cpp, proyecto as p
    WHERE pc.estado='0' AND pc.estado_delete='1' AND cpp.idproyecto='$nube_idproyecto' AND pc.idcompra_proyecto=cpp.idcompra_proyecto AND cpp.idproyecto=p.idproyecto";
    $pago_compras = ejecutarConsultaArray($sql_20);

    if ($pago_compras['status'] == false) { return $pago_compras; }

    if (!empty($pago_compras['data'])) {
      foreach ($pago_compras['data'] as $key => $value20) {
        $data[] = array(
          'nombre_tabla'    => 'pago_compras',
          'nombre_id_tabla' => 'idpago_compras',
          'modulo'          => 'Pago compras',
          'id_tabla'        => $value20['idpago_compras'],
          'nombre_archivo'  => '<b>Beneficiario: </b>'. $value20['beneficiario']. '<br>'.
          '<b>Monto pago: </b>'.number_format($value20['monto'], 2, '.', ',').'<br>'.
          '<b>Fecha: </b>'.$value20['fecha_pago'] . '<br>'.
          '<b>'.$value20['tipo_pago']. ': </b>'.$value20['numero_operacion']. '<br>',
          'descripcion'     => $value20['descripcion'],
          'nombre_proyecto'  => $value20['nombre_codigo'],
          'created_at'      => $value20['created_at'],
          'updated_at'      => $value20['updated_at'],
        );
      }
    }
    
    $sql21 = "SELECT ps.idpago_servicio, ps.beneficiario, ps.descripcion, ps.monto, ps.fecha_pago, ps.tipo_pago, ps.numero_operacion, 
    ps.created_at, ps.updated_at, p.nombre_codigo
    FROM pago_servicio AS ps, maquinaria AS m, proyecto AS p 
    WHERE ps.estado='0' AND ps.estado_delete=1 AND  m.idmaquinaria=ps.id_maquinaria  AND m.tipo=1 AND ps.idproyecto=p.idproyecto AND ps.idproyecto='$nube_idproyecto'";
    $pago_servicio_maquina = ejecutarConsultaArray($sql21);

    if ($pago_servicio_maquina['status'] == false) { return $pago_servicio_maquina; }

    if (!empty($pago_servicio_maquina['data'])) {
      foreach ($pago_servicio_maquina['data'] as $key => $value21) {
        $data[] = array(
          'nombre_tabla'    => 'pago_servicio',
          'nombre_id_tabla' => 'idpago_servicio',
          'modulo'          => 'Pago Servicio Maquinaria',
          'id_tabla'        => $value21['idpago_servicio'],
          'nombre_archivo'  => '<b>Beneficiario: </b>'. $value21['beneficiario']. '<br>'.
          '<b>Monto pago: </b>'.number_format($value21['monto'], 2, '.', ',').'<br>'.
          '<b>Fecha: </b>'.$value21['fecha_pago'] . '<br>'.
          '<b>'.$value21['tipo_pago']. ': </b>'.$value21['numero_operacion']. '<br>',
          'descripcion'     => $value21['descripcion'],
          'nombre_proyecto'  => $value21['nombre_codigo'],
          'created_at'      => $value21['created_at'],
          'updated_at'      => $value21['updated_at'],
        );
      }
    }

    $sql22 = "SELECT ps.idpago_servicio, ps.beneficiario, ps.descripcion, ps.monto, ps.fecha_pago, ps.tipo_pago, ps.numero_operacion, 
     ps.created_at,  ps.updated_at, p.nombre_codigo
    FROM pago_servicio AS ps, maquinaria AS m, proyecto AS p 
    WHERE ps.estado='0' AND ps.estado_delete=1 AND  m.idmaquinaria=ps.id_maquinaria  AND m.tipo=2 AND ps.idproyecto=p.idproyecto AND ps.idproyecto='$nube_idproyecto'";
    $pago_servicio_equipo = ejecutarConsultaArray($sql22);

    if ($pago_servicio_equipo['status'] == false) { return $pago_servicio_equipo; }

    if (!empty($pago_servicio_equipo['data'])) {
      foreach ($pago_servicio_equipo['data'] as $key => $value22) {
        $data[] = array(
          'nombre_tabla'    => 'pago_servicio',
          'nombre_id_tabla' => 'idpago_servicio',
          'modulo'          => 'Pago Servicio Equipo',
          'id_tabla'        => $value22['idpago_servicio'],
          'nombre_archivo'  => '<b>Beneficiario: </b>'. $value22['beneficiario']. '<br>'.
          '<b>Monto pago: </b>'.number_format($value22['monto'], 2, '.', ',').'<br>'.
          '<b>Fecha: </b>'.$value22['fecha_pago'] . '<br>'.
          '<b>'.$value22['tipo_pago']. ': </b>'.$value22['numero_operacion']. '<br>',
          'descripcion'     => $value22['descripcion'],
          'nombre_proyecto'  => $value22['nombre_codigo'],
          'created_at'      => $value22['created_at'],
          'updated_at'      => $value22['updated_at'],
        );
      }
    }

    $sql23 = "SELECT ps.idpago_subcontrato, ps.beneficiario, ps.monto, ps.descripcion,  ps.fecha_pago,  ps.tipo_pago,  ps.numero_operacion,
     ps.created_at, ps.updated_at, p.nombre_codigo
    FROM pago_subcontrato AS ps, subcontrato as s, proyecto as p
    WHERE ps.estado='0' AND ps.estado_delete='1' AND ps.idsubcontrato=s.idsubcontrato AND s.idproyecto=p.idproyecto AND s.idproyecto='$nube_idproyecto'";
    $pago_subcontrato = ejecutarConsultaArray($sql23);

    if ($pago_subcontrato['status'] == false) { return $pago_subcontrato; }

    if (!empty($pago_subcontrato['data'])) {
      foreach ($pago_subcontrato['data'] as $key => $value23) {
        $data[] = array(
          'nombre_tabla'    => 'pago_subcontrato',
          'nombre_id_tabla' => 'idpago_subcontrato',
          'modulo'          => 'Pago sub contrato',
          'id_tabla'        => $value23['idpago_subcontrato'],
          'nombre_archivo'  => '<b>Beneficiario: </b>'. $value23['beneficiario']. '<br>'.
          '<b>Monto pago: </b>'.number_format($value23['monto'], 2, '.', ',').'<br>'.
          '<b>Fecha: </b>'.$value23['fecha_pago'] . '<br>'.
          '<b>'.$value23['tipo_pago']. ': </b>'.$value23['numero_operacion']. '<br>',
          'descripcion'     => $value23['descripcion'],
          'nombre_proyecto'  => $value23['nombre_codigo'],
          'created_at'      => $value23['created_at'],
          'updated_at'      => $value23['updated_at'],
        );
      }
    }
    
    $sql24 = "SELECT ps.idplanilla_seguro, ps.tipo_comprobante, ps.numero_comprobante, ps.costo_parcial, ps.fecha_p_s,
    ps.created_at, ps.updated_at, p.nombre_codigo
    FROM planilla_seguro as ps, proyecto as p
    WHERE ps.idproyecto=p.idproyecto AND ps.estado='0' AND ps.estado_delete='1' AND ps.idproyecto='$nube_idproyecto'";
    $planilla_seguro = ejecutarConsultaArray($sql24);

    if ($planilla_seguro['status'] == false) { return $planilla_seguro; }

    if (!empty($planilla_seguro['data'])) {
      foreach ($planilla_seguro['data'] as $key => $value24) {
        $data[] = array(
          'nombre_tabla'    => 'planilla_seguro',
          'nombre_id_tabla' => 'idplanilla_seguro',
          'modulo'          => 'Planillas y seguros',
          'id_tabla'        => $value24['idplanilla_seguro'],
          'nombre_archivo'  => '<b>'.$value24['tipo_comprobante']. ': </b>'.$value24['numero_comprobante']. '<br>'.
          '<b>Monto pago: </b>'.number_format($value24['costo_parcial'], 2, '.', ',').'<br>'.
          '<b>Fecha: </b>'.$value24['fecha_p_s'] . '<br>',
          'descripcion'     => '- - -',
          'nombre_proyecto'  => $value24['nombre_codigo'],
          'created_at'      => $value24['created_at'],
          'updated_at'      => $value24['updated_at'],
        );
      }
    }
    
    $sql25 = "SELECT po.idplano_otro, po.nombre, po.descripcion, po.created_at, po.updated_at, p.nombre_codigo , cpo.nombre as nombre_capeta
    FROM plano_otro AS po, carpeta_plano_otro AS cpo, proyecto AS p 
    WHERE  po.estado='0' AND po.estado_delete='1' AND po.id_carpeta=cpo.idcarpeta AND cpo.idproyecto=p.idproyecto AND  cpo.idproyecto='$nube_idproyecto'";
    $plano_otro = ejecutarConsultaArray($sql25);

    if ($plano_otro['status'] == false) { return $plano_otro; }

    if (!empty($plano_otro['data'])) {
      foreach ($plano_otro['data'] as $key => $value25) {
        $data[] = array(
          'nombre_tabla'    => 'plano_otro',
          'nombre_id_tabla' => 'idplano_otro',
          'modulo'          => 'Planos y otros',
          'id_tabla'        => $value25['idplano_otro'],
          'nombre_archivo'  =>   '<b>Archivo: </b>'.$value25['nombre']. '<br>'.
          '<b>Carpeta: </b>'.$value25['nombre_capeta'].'<br>',
          'descripcion'     => $value25['descripcion'],
          'nombre_proyecto'  => $value25['nombre_codigo'],
          'created_at'      => $value25['created_at'],
          'updated_at'      => $value25['updated_at'],
        );
      }
    }

    $sql26 = "SELECT p.idproducto, p.nombre, p.descripcion, p.precio_total, p.created_at, p.updated_at, c.nombre_color
    FROM producto as p, color AS c 
    WHERE p.idcolor = c.idcolor AND p.estado='0' AND p.estado_delete='1' AND p.idcategoria_insumos_af = '1'";
    $producto_insumo = ejecutarConsultaArray($sql26);

    if ($producto_insumo['status'] == false) { return $producto_insumo; }

    if (!empty($producto_insumo['data'])) {
      foreach ($producto_insumo['data'] as $key => $value26) {
        $data[] = array(
          'nombre_tabla'    => 'producto',
          'nombre_id_tabla' => 'idproducto',
          'modulo'          => 'Insumos',
          'id_tabla'        => $value26['idproducto'],
          'nombre_archivo'  => '<b>Insumo: </b>'.$value26['nombre']. '<br>'.
          '<b>Precio: </b>'.number_format($value26['precio_total'], 2, '.', ',').'<br>'.
          '<b>Color: </b>'.$value26['nombre_color']. '<br>',
          'descripcion'     => $value26['descripcion'],
          'nombre_proyecto'  => 'General',
          'created_at'      => $value26['created_at'],
          'updated_at'      => $value26['updated_at'],
        );
      }
    }

    $sql27 = "SELECT p.idproducto, p.nombre, p.descripcion, p.precio_total, p.created_at, p.updated_at, c.nombre_color, ciaf.nombre AS categoria
    FROM producto as p, color AS c, categoria_insumos_af AS ciaf  
    WHERE p.idcolor = c.idcolor AND ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af  AND p.estado='0' AND p.estado_delete='1' AND p.idcategoria_insumos_af != '1'";
    $producto_activo_fijo = ejecutarConsultaArray($sql27);

    if ($producto_activo_fijo['status'] == false) { return $producto_activo_fijo; }

    if (!empty($producto_activo_fijo['data'])) {
      foreach ($producto_activo_fijo['data'] as $key => $value27) {
        $data[] = array(
          'nombre_tabla'    => 'producto',
          'nombre_id_tabla' => 'idproducto',
          'modulo'          => 'Activos fijos',
          'id_tabla'        => $value27['idproducto'],
          'nombre_archivo'  => '<b>Insumo: </b>'.$value27['nombre']. '<br>'.
          '<b>Precio: </b>'.number_format($value27['precio_total'], 2, '.', ',').'<br>'. 
          '<b>Clasificacion: </b>'.$value27['categoria']. '<br>'.
          '<b>Color: </b>'.$value27['nombre_color']. '<br>',
          'descripcion'     => $value27['descripcion'],
          'nombre_proyecto'  => 'General',
          'created_at'      => $value27['created_at'],
          'updated_at'      => $value27['updated_at'],
        );
      }
    }

    $sql28 = "SELECT idproveedor,razon_social, ruc, created_at,updated_at FROM proveedor WHERE estado='0'AND estado_delete=1";
    $proveedor = ejecutarConsultaArray($sql28);

    if ($proveedor['status'] == false) { return $proveedor; }

    if (!empty($proveedor['data'])) {
      foreach ($proveedor['data'] as $key => $value28) {
        $data[] = array(
          'nombre_tabla'    => 'proveedor',
          'nombre_id_tabla' => 'idproveedor',
          'modulo'          => 'Proveedores',
          'id_tabla'        => $value28['idproveedor'],
          'nombre_archivo'  => '<b>Proveedor: </b>'.$value28['razon_social']. '<br>'.
          '<b>Ruc: </b>'.$value28['ruc'].'<br>',
          'descripcion'     => '- - - ',
          'nombre_proyecto'  => 'General',
          'created_at'      => $value28['created_at'],
          'updated_at'      => $value28['updated_at'],
        );
      }
    }
        
    $sql29 = "SELECT s.idservicio, s.descripcion, s.costo_parcial, s.unidad_medida, s.created_at, s.updated_at, m.nombre, p.nombre_codigo
    FROM servicio AS s, maquinaria AS m, proyecto AS p
    WHERE s.estado='0' AND s.estado_delete='1' AND s.idmaquinaria=m.idmaquinaria AND s.idproyecto=p.idproyecto AND m.tipo='1' AND s.idproyecto='$nube_idproyecto'";
    $servicio_maquina = ejecutarConsultaArray($sql29);

    if ($servicio_maquina['status'] == false) { return $servicio_maquina; }

    if (!empty($servicio_maquina['data'])) {
      foreach ($servicio_maquina['data'] as $key => $value29) {
        $data[] = array(
          'nombre_tabla'    => 'servicio',
          'nombre_id_tabla' => 'idservicio',
          'modulo'          => 'Servicio Maquinaria',
          'id_tabla'        => $value29['idservicio'],
          'nombre_archivo'  => '<b>Maquina: </b>'.$value29['nombre']. '<br>'.
          '<b>Monto: </b>'.number_format($value29['costo_parcial'], 2, '.', ',').'<br>'.
          '<b>Unidad Medida: </b>'.$value29['unidad_medida'].'<br>',
          'descripcion'     => $value29['descripcion'],
          'nombre_proyecto'  => $value29['nombre_codigo'],
          'created_at'      => $value29['created_at'],
          'updated_at'      => $value29['updated_at'],
        );
      }
    }

    $sql30 = "SELECT s.idservicio, s.descripcion, s.costo_parcial, s.unidad_medida, s.created_at, s.updated_at, m.nombre, p.nombre_codigo
    FROM servicio AS s, maquinaria AS m, proyecto AS p
    WHERE s.estado='0' AND s.estado_delete='1' AND s.idmaquinaria=m.idmaquinaria AND s.idproyecto=p.idproyecto AND m.tipo='2' AND s.idproyecto='$nube_idproyecto'";
    $servicio_equipo = ejecutarConsultaArray($sql30);

    if ($servicio_equipo['status'] == false) { return $servicio_equipo; }

    if (!empty($servicio_equipo['data'])) {
      foreach ($servicio_equipo['data'] as $key => $value30) {
        $data[] = array(
          'nombre_tabla'    => 'servicio',
          'nombre_id_tabla' => 'idservicio',
          'modulo'          => 'Servicio Equipos',
          'id_tabla'        => $value30['idservicio'],
          'nombre_archivo'  => '<b>Maquina: </b>'.$value30['nombre']. '<br>'.
          '<b>Monto: </b>'.number_format($value30['costo_parcial'], 2, '.', ',').'<br>'.
          '<b>Unidad Medida: </b>'.$value30['unidad_medida'].'<br>',
          'descripcion'     => $value30['descripcion'],
          'nombre_proyecto'  => $value30['nombre_codigo'],
          'created_at'      => $value30['created_at'],
          'updated_at'      => $value30['updated_at'],
        );
      }
    }

    $sql31 = "SELECT sb.idsubcontrato, sb.tipo_comprobante, sb.numero_comprobante, sb.costo_parcial,  sb.descripcion, sb.created_at, 
    sb.updated_at, prov.razon_social, p.nombre_codigo
    FROM subcontrato as sb, proveedor as prov , proyecto as p
    WHERE sb.estado='0' AND sb.estado_delete=1 AND sb.idproveedor=prov.idproveedor AND sb.idproyecto=p.idproyecto AND sb.idproyecto='$nube_idproyecto'";
    $subcontrato = ejecutarConsultaArray($sql31);

    if ($subcontrato['status'] == false) { return $subcontrato; }

    if (!empty($subcontrato['data'])) {
      foreach ($subcontrato['data'] as $key => $value31) {
        $data[] = array(
          'nombre_tabla'    => 'subcontrato',
          'nombre_id_tabla' => 'idsubcontrato',
          'modulo'          => 'Sub contrato',
          'id_tabla'        => $value31['idsubcontrato'],
          'nombre_archivo'  =>  '<b>'.$value31['tipo_comprobante']. ': </b>'.$value31['numero_comprobante']. '<br>'.
          '<b>Monto: </b>'.number_format($value31['costo_parcial'], 2, '.', ',').'<br>'.
          '<b>Proveedor: </b>'.$value31['razon_social'].'<br>' ,
          'descripcion'     => $value31['descripcion'],
          'nombre_proyecto'  => $value31['nombre_codigo'],
          'created_at'      => $value31['created_at'],
          'updated_at'      => $value31['updated_at'],
        );
      }
    }

    $sql32 = "SELECT idtipo_trabajador, nombre, created_at, updated_at FROM tipo_trabajador WHERE estado='0' AND estado_delete='1'";
    $tipo_trabajador = ejecutarConsultaArray($sql32);

    if ($tipo_trabajador['status'] == false) { return $tipo_trabajador; }

    if (!empty($tipo_trabajador['data'])) {
      foreach ($tipo_trabajador['data'] as $key => $value32) {
        $data[] = array(
          'nombre_tabla'    => 'tipo_trabajador',
          'nombre_id_tabla' => 'idtipo_trabajador',
          'modulo'          => 'Tipo Trabajador',
          'id_tabla'        => $value32['idtipo_trabajador'],
          'nombre_archivo'  => $value32['nombre'] ,
          'descripcion'     => '- - -',
          'nombre_proyecto' => 'General',
          'created_at'      => $value32['created_at'],
          'updated_at'      => $value32['updated_at'],
        );
      }
    }

    $sql33 = "SELECT idtrabajador, nombres, tipo_documento, numero_documento, ruc, created_at, updated_at, descripcion_expulsion 
    FROM trabajador WHERE estado='0' AND estado_delete='1'";
    $trabajador = ejecutarConsultaArray($sql33);

    if ($trabajador['status'] == false) { return $trabajador; }

    if (!empty($trabajador['data'])) {
      foreach ($trabajador['data'] as $key => $value33) {
        $data[] = array(
          'nombre_tabla'    => 'trabajador',
          'nombre_id_tabla' => 'idtrabajador',
          'modulo'          => 'Trabajador',
          'id_tabla'        => $value33['idtrabajador'],
          'nombre_archivo'  => '<b>Trabajador: </b>'.$value33['nombres']. '<br>'.
          '<b>'.$value33['tipo_documento'].': </b>'.$value33['numero_documento'].'<br>'.
          '<b>Ruc: </b>'.$value33['ruc'].'<br>' ,
          'descripcion'     => $value33['descripcion_expulsion'],
          'nombre_proyecto' => 'General',
          'created_at'      => $value33['created_at'],
          'updated_at'      => $value33['updated_at'],
        );
      }
    }
    
    $sql34 = "SELECT tpp.idtrabajador_por_proyecto, tpp.desempenio, tpp.sueldo_mensual, tpp.created_at, tpp.updated_at, t.nombres, 
    p.nombre_codigo, ctr.nombre as cargo
    FROM trabajador_por_proyecto as tpp,  proyecto as p, trabajador as t, cargo_trabajador as ctr
    WHERE tpp.idcargo_trabajador = ctr.idcargo_trabajador AND  tpp.idproyecto=p.idproyecto AND tpp.idtrabajador=t.idtrabajador AND
    tpp.estado='0' AND tpp.estado_delete='1' AND tpp.idproyecto='$nube_idproyecto'";
    $trabajador_por_proyecto = ejecutarConsultaArray($sql34);

    if ($trabajador_por_proyecto['status'] == false) { return $trabajador_por_proyecto; }

    if (!empty($trabajador_por_proyecto['data'])) {
      foreach ($trabajador_por_proyecto['data'] as $key => $value34) {
        $data[] = array(
          'nombre_tabla'    => 'trabajador_por_proyecto',
          'nombre_id_tabla' => 'idtrabajador_por_proyecto',
          'modulo'          => 'Trabajador por proyecto',
          'id_tabla'        => $value34['idtrabajador_por_proyecto'],
          'nombre_archivo'  => '<b>Trabajador: </b>'.$value34['nombres']. '<br>'.
          '<b>Sueldo Mensual: </b>'.number_format($value34['sueldo_mensual'],2,'.',',').'<br>'.
          '<b>Cargo: </b>'.$value34['cargo'].'<br>'.
          '<b>Desempeño: </b>'.$value34['desempenio'].'<br>' ,
          'descripcion'     => '- - - ',
          'nombre_proyecto'  => $value34['nombre_codigo'],
          'created_at'      => $value34['created_at'],
          'updated_at'      => $value34['updated_at'],
        );
      }
    }

    
    $sql35 = "SELECT t.idtransporte, t.tipo_comprobante, t.numero_comprobante, t.precio_parcial, t.descripcion, prov.razon_social,
    t.created_at , t.updated_at, p.nombre_codigo
    FROM transporte as t, proyecto as p, proveedor as prov
    WHERE   t.idproyecto=p.idproyecto AND t.idproveedor = prov.idproveedor AND t.estado='0'  AND t.estado_delete='1' AND t.idproyecto='$nube_idproyecto'";
    $transporte = ejecutarConsultaArray($sql35);

    if ($transporte['status'] == false) { return $transporte; }

    if (!empty($transporte['data'])) {
      foreach ($transporte['data'] as $key => $value35) {
        $data[] = array(
          'nombre_tabla'    => 'transporte',
          'nombre_id_tabla' => 'idtransporte',
          'modulo'          => 'Transporte',
          'id_tabla'        => $value35['idtransporte'],
          'nombre_archivo'  => '<b>'.$value35['tipo_comprobante']. ': </b>'.$value35['numero_comprobante']. '<br>'.
          '<b>Monto: </b>'.number_format($value35['precio_parcial'],2,'.',',').'<br>'.
          '<b>Proveedor: </b>'.$value35['razon_social'].'<br>' ,
          'descripcion'     => $value35['descripcion'],
          'nombre_proyecto'  => $value35['nombre_codigo'],
          'created_at'      => $value35['created_at'],
          'updated_at'      => $value35['updated_at'],
        );
      }
    }
            
    $sql37 = "SELECT u.idusuario, u.cargo, u.created_at, u.updated_at, t.nombres FROM usuario as u, trabajador as t WHERE u.estado='0' AND u.estado_delete='1' AND u.idtrabajador=t.idtrabajador";
    $usuario = ejecutarConsultaArray($sql37);

    if ($usuario['status'] == false) { return $usuario; }

    if (!empty($usuario['data'])) {
      foreach ($usuario['data'] as $key => $value37) {
        $data[] = array(
          'nombre_tabla'    => 'usuario',
          'nombre_id_tabla' => 'idusuario',
          'modulo'          => 'Usuario',
          'id_tabla'        => $value37['idusuario'],
          'nombre_archivo'  => $value37['nombres'].': '.$value37['cargo'] ,
          'descripcion'     => ' - - - ',
          'nombre_proyecto'  => 'General',
          'created_at'      => $value37['created_at'],
          'updated_at'      => $value37['updated_at'],
        );
      }
    }
    $sql38 = "SELECT of.idotra_factura, of.tipo_comprobante, of.numero_comprobante, of.costo_parcial, of.descripcion,
    of.created_at, of.updated_at,p.razon_social
    FROM otra_factura as of, proveedor as p
    WHERE of.estado='0' AND of.estado_delete='1' AND of.idproveedor=p.idproveedor";
    $otra_factura = ejecutarConsultaArray($sql38);

    if ($otra_factura['status'] == false) { return $otra_factura; }

    if (!empty($otra_factura['data'])) {
      foreach ($otra_factura['data'] as $key => $value38) {
        $data[] = array(
          'nombre_tabla'    => 'otra_factura',
          'nombre_id_tabla' => 'idotra_factura',
          'modulo'          => 'Otras facturas',
          'id_tabla'        => $value38['idotra_factura'],
          'nombre_archivo'  => '<b>'.$value38['tipo_comprobante']. ': </b>'.$value38['numero_comprobante']. '<br>'.
          '<b>Monto: </b>'.number_format($value38['costo_parcial'],2,'.',',').'<br>'.
          '<b>Proveedor: </b>'.$value38['razon_social'].'<br>' ,
          'descripcion'     => $value38['descripcion'],
          'nombre_proyecto'  => 'General',
          'created_at'      => $value38['created_at'],
          'updated_at'      => $value38['updated_at'],
        );
      }
    }

    // SEMANA DEL OBRERO --------- Este tiene su propia papelera
    /*$sql39 = "SELECT rqsa.idresumen_q_s_asistencia, rqsa.numero_q_s, rqsa.fecha_q_s_inicio, rqsa.fecha_q_s_fin,rqsa.created_at, rqsa.updated_at, t.nombres, p.nombre_codigo
    FROM resumen_q_s_asistencia as rqsa, trabajador_por_proyecto as tpp, trabajador as t, proyecto as p 
    WHERE rqsa.estado='0' AND rqsa.estado_delete='1' AND tpp.idtrabajador_por_proyecto=rqsa.idtrabajador_por_proyecto AND  t.idtrabajador=tpp.idtrabajador AND p.idproyecto=tpp.idproyecto AND tpp.idproyecto='$nube_idproyecto'";

    $resumen_q_s_asistencia = ejecutarConsultaArray($sql39);

    if (!empty($resumen_q_s_asistencia)) {
      foreach ($resumen_q_s_asistencia as $key => $value39) {
        $data[] = array(
          'nombre_tabla'    => 'resumen_q_s_asistencia',
          'nombre_id_tabla' => 'idresumen_q_s_asistencia',
          'modulo'          => 'Asistencia por semanas',
          'id_tabla'        => $value39['idresumen_q_s_asistencia'],
          'nombre_archivo'  => $value39['nombres'].' - '.$value39['fecha_q_s_inicio'].' ─ '.$value39['fecha_q_s_fin'] ,
          'descripcion'     => '- - - ',
          'nombre_proyecto' => $value39['nombre_codigo'],
          'created_at'      => $value39['created_at'],
          'updated_at'      => $value39['updated_at'],
        );
      }
    }*/

    ///--------------------------------------
    $sql40 = "SELECT sb.idsemana_break,sb.numero_semana, sb.fecha_inicial, sb.fecha_final, sb.total, sb.created_at, sb.updated_at, p.nombre_codigo
    FROM semana_break as sb, proyecto as p 
    WHERE sb.estado=0 AND sb.estado_delete=1 AND sb.idproyecto=p.idproyecto AND sb.idproyecto='$nube_idproyecto'";
    $semana_break = ejecutarConsultaArray($sql40);

    if ($semana_break['status'] == false) { return $semana_break; }

    if (!empty($semana_break['data'])) {
      foreach ($semana_break['data'] as $key => $value40) {
        $data[] = array(
          'nombre_tabla'    => 'semana_break',
          'nombre_id_tabla' => 'idsemana_break',
          'modulo'          => 'Breaks por semanas',
          'id_tabla'        => $value40['idsemana_break'],
          'nombre_archivo'  => '<b>Semana '.$value40['numero_semana'].'</b>: '.$value40['fecha_inicial'].' ─ '.$value40['fecha_final']. '<br>'.
          '<b>Monto:</b> '.number_format($value40['total'],2,'.',',').'<br>' ,
          'descripcion'     => '- - - ',
          'nombre_proyecto'  => $value40['nombre_codigo'],
          'created_at'      => $value40['created_at'],
          'updated_at'      => $value40['updated_at'],
        );
      }
    }

    // $sql41 = "SELECT sp.idsemana_pension, sp.fecha_inicio, sp.fecha_fin, sp.numero_semana, sp.created_at, sp.updated_at, ser_p.nombre_servicio, p.nombre_codigo, prov.razon_social
    // FROM semana_pension as sp, servicio_pension ser_p, pension as pen, proyecto as p, proveedor as prov
    // WHERE sp.estado='0' AND sp.estado_delete='1' AND sp.idservicio_pension=ser_p.idservicio_pension AND ser_p.idpension=pen.idpension AND pen.idproyecto=p.idproyecto AND pen.idproveedor=prov.idproveedor AND pen.idproyecto='$nube_idproyecto'";
    // $semana_pension = ejecutarConsultaArray($sql41);

    // if ($semana_pension['status'] == false) { return $semana_pension; }

    // if (!empty($semana_pension['data'])) {
    //   foreach ($semana_pension['data'] as $key => $value41) {
    //     $data[] = array(
    //       'nombre_tabla'    => 'semana_pension',
    //       'nombre_id_tabla' => 'idsemana_pension',
    //       'modulo'          => 'Pensión por semanas',
    //       'id_tabla'        => $value41['idsemana_pension'],
    //       'nombre_archivo'  => '<b>Semana '.$value41['numero_semana'].'</b>: '.$value41['fecha_inicio'].' ─ '.$value41['fecha_fin']. '<br>'.
    //       '<b>Servicio:</b> '.$value41['nombre_servicio']. '<br>'.
    //       '<b>Proveedor:</b> '.$value41['razon_social'] .'<br>',
    //       'descripcion'     => ' - - - ',
    //       'nombre_proyecto'  => $value41['nombre_codigo'],
    //       'created_at'      => $value41['created_at'],
    //       'updated_at'      => $value41['updated_at'],
    //     );
    //   }
    // }

    
    // $sql42 = "SELECT ser_p.idservicio_pension, ser_p.nombre_servicio, ser_p.precio, ser_p.created_at, ser_p.updated_at, p.nombre_codigo, prov.razon_social
    // FROM servicio_pension  as ser_p, pension as pen, proyecto as p, proveedor as prov 
    // WHERE ser_p.estado='0' AND ser_p.estado_delete='1' AND ser_p.idpension=pen.idpension AND pen.idproyecto=p.idproyecto AND pen.idproveedor=prov.idproveedor AND pen.idproyecto='$nube_idproyecto'";
    // $servicio_pension = ejecutarConsultaArray($sql42);

    // if ($servicio_pension['status'] == false) { return $servicio_pension; }

    // if (!empty($servicio_pension['data'])) {
    //   foreach ($servicio_pension['data'] as $key => $value42) {
    //     $data[] = array(
    //       'nombre_tabla'    => 'servicio_pension',
    //       'nombre_id_tabla' => 'idservicio_pension',
    //       'modulo'          => 'Servicio pensión',
    //       'id_tabla'        => $value42['idservicio_pension'],
    //       'nombre_archivo'  => '<b>Servicio:</b> '.$value42['nombre_servicio'].'('.$value42['precio'].')'.'<br>'.
    //       '<b>Proveedor:</b> '.$value42['razon_social'] .'<br>', 
    //       'descripcion'     => '- - - ',
    //       'nombre_proyecto'  => $value42['nombre_codigo'],
    //       'created_at'      => $value42['created_at'],
    //       'updated_at'      => $value42['updated_at'],
    //     );
    //   }
    // }

   $sql43 = "SELECT v.idvalorizacion,v.nombre, v.fecha_inicio, v.fecha_fin, v.created_at, v.updated_at, p.nombre_codigo FROM valorizacion as v, proyecto as p
   WHERE v.estado='0' AND v.estado_delete='1' AND v.idproyecto=p.idproyecto AND v.idproyecto='$nube_idproyecto'";
    $valorizacion = ejecutarConsultaArray($sql43);

    if ($valorizacion['status'] == false) { return $valorizacion; }

    if (!empty($valorizacion['data'])) {
      foreach ($valorizacion['data'] as $key => $value43) {
        $data[] = array(
          'nombre_tabla'    => 'valorizacion',
          'nombre_id_tabla' => 'idvalorizacion',
          'modulo'          => 'Valorización',
          'id_tabla'        => $value43['idvalorizacion'],
          'nombre_archivo'  => $value43['nombre'].'  -- documento que pertenece a las fechas: '.$value43['fecha_inicio'].'─'.$value43['fecha_fin'] ,
          'descripcion'     => '- - - ',
          'nombre_proyecto' => $value43['nombre_codigo'],
          'created_at'      => $value43['created_at'],
          'updated_at'      => $value43['updated_at'],
        );
      }
    }

    $sql44 = "SELECT fpa.idfechas_mes_pagos_administrador, fpa.fecha_inicial, fpa.fecha_final, fpa.monto_x_mes, fpa.nombre_mes,
    fpa.created_at, fpa.updated_at, t.nombres, p.nombre_codigo
    FROM fechas_mes_pagos_administrador AS fpa, trabajador_por_proyecto as tpp, trabajador as t, proyecto as p
    WHERE fpa.estado='0' AND fpa.estado_delete='1' AND fpa.idtrabajador_por_proyecto=tpp.idtrabajador_por_proyecto AND tpp.idtrabajador=t.idtrabajador AND tpp.idproyecto=p.idproyecto AND tpp.idproyecto='$nube_idproyecto'";
    $fechas_mes_pagos_administrador = ejecutarConsultaArray($sql44);

    if ($fechas_mes_pagos_administrador['status'] == false) { return $fechas_mes_pagos_administrador; }

    if (!empty($fechas_mes_pagos_administrador['data'])) {
      foreach ($fechas_mes_pagos_administrador['data'] as $key => $value44) {
        $data[] = array(
          'nombre_tabla'    => 'fechas_mes_pagos_administrador',
          'nombre_id_tabla' => 'idfechas_mes_pagos_administrador',
          'modulo'          => 'Fechas mes pagos administrador',
          'id_tabla'        => $value44['idfechas_mes_pagos_administrador'],
          'nombre_archivo'  => '<b>Trabajador:</b> '.$value44['nombres'].  '<br>'. 
          '<b>Fechas:</b> '.$value44['fecha_inicial'].'─'.$value44['fecha_final'] . '<br>'.
          '<b>Monto:</b> '. $value44['monto_x_mes']. '<br>'.
          '<b>Mes:</b> '.$value44['nombre_mes']. '<br>'  ,
          'descripcion'     => '- - - ',
          'nombre_proyecto' => $value44['nombre_codigo'],
          'created_at'      => $value44['created_at'],
          'updated_at'      => $value44['updated_at'],
        );
      }
    }

    $sql45 = "SELECT prov.razon_social AS proveedor, oi.idotro_ingreso, oi.idproyecto, oi.idproveedor, oi.ruc, oi.razon_social, oi.direccion, oi.tipo_comprobante, 
    oi.numero_comprobante, oi.forma_de_pago, oi.fecha_i, oi.subtotal, oi.igv, oi.costo_parcial, oi.descripcion, oi.glosa, oi.comprobante, 
    oi.val_igv, oi.tipo_gravada, oi.estado, oi.estado_delete, oi.created_at, oi.updated_at, p.nombre_codigo
    FROM otro_ingreso AS oi, proveedor AS prov, proyecto AS p
    WHERE oi.idproveedor = prov.idproveedor AND oi.idproyecto = p.idproyecto AND oi.estado='0' AND oi.estado_delete ='1' AND oi.idproyecto='$nube_idproyecto'";
    $otro_ingreso = ejecutarConsultaArray($sql45);

    if ($otro_ingreso['status'] == false) { return $otro_ingreso; }

    if (!empty($otro_ingreso['data'])) {
      foreach ($otro_ingreso['data'] as $key => $value45) {
        $data[] = array(
          'nombre_tabla'    => 'otro_ingreso',
          'nombre_id_tabla' => 'idotro_ingreso',
          'modulo'          => 'Otro Ingreso',
          'id_tabla'        => $value45['idotro_ingreso'],
          'nombre_archivo'  => '<b>Proveedor:</b> '.$value45['proveedor'].'<br>'. 
          '<b>'.$value45['tipo_comprobante'].':</b> '.$value45['numero_comprobante'].'<br>'.
          '<b>Monto: </b>'.$value45['costo_parcial'].'<br>' ,
          'descripcion'     => '- - - ',
          'nombre_proyecto' => $value45['nombre_codigo'],
          'created_at'      => $value45['created_at'],
          'updated_at'      => $value45['updated_at'],
        );
      }
    }

    return $data;
  }

  //Desactivar 
  public function recuperar($nombre_tabla, $nombre_id_tabla, $id_tabla)
  {
    $sql = "UPDATE $nombre_tabla SET estado='1',user_updated= '" . $_SESSION['idusuario'] . "' WHERE $nombre_id_tabla ='$id_tabla'";

		$recuperar= ejecutarConsulta($sql);

		if ($recuperar['status'] == false) {  return $recuperar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('$nombre_tabla','".$id_tabla."','Factura recuperada desde papelera','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $recuperar;
  }

  //eliminar
  public function eliminar_permanente($nombre_tabla, $nombre_id_tabla, $id_tabla)
  {
    $sql = "UPDATE $nombre_tabla SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE $nombre_id_tabla ='$id_tabla'";
		$eliminar =  ejecutarConsulta($sql);
		if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('$nombre_tabla','$id_tabla','Factura eliminada desde papelera','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }
}

?>
