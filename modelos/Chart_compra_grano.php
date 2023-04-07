<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ChartCompraGrano
{
  //Implementamos nuestro variable global
  public $id_usr_sesion;

  //Implementamos nuestro constructor
  public function __construct($id_usr_sesion = 0)
  {
    $this->id_usr_sesion = $id_usr_sesion;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function box_content_reporte() {
    $data = Array();

    $sql_1 = "SELECT COUNT(idpersona) as cant_cliente FROM compra_grano WHERE estado='1' AND estado_delete='1' GROUP BY idpersona";
    $cant_clientes = ejecutarConsultaArray($sql_1); if ($cant_clientes['status'] == false) { return $cant_clientes; }

    $sql_2 = "SELECT SUM(dcg.peso_neto) AS peso_neto 
    FROM detalle_compra_grano AS dcg, compra_grano AS cg 
    WHERE dcg.idcompra_grano = cg.idcompra_grano  AND dcg.tipo_grano = 'COCO' AND cg.estado = '1'  AND cg.estado_delete = '1'  GROUP BY dcg.tipo_grano;";
    $kilo_coco = ejecutarConsultaSimpleFila($sql_2);  if ($kilo_coco['status'] == false) { return $kilo_coco; }

    $sql_2_1 = "SELECT SUM(dcg.subtotal) AS subtotal_coco 
    FROM detalle_compra_grano AS dcg, compra_grano AS cg 
    WHERE dcg.idcompra_grano = cg.idcompra_grano  AND dcg.tipo_grano = 'COCO' AND cg.estado = '1'  AND cg.estado_delete = '1'  GROUP BY dcg.tipo_grano;";
    $sol_coco = ejecutarConsultaSimpleFila($sql_2_1);  if ($sol_coco['status'] == false) { return $sol_coco; }

    $sql_3 = "SELECT SUM(dcg.peso_neto) AS peso_neto 
    FROM detalle_compra_grano AS dcg, compra_grano AS cg 
    WHERE dcg.idcompra_grano = cg.idcompra_grano  AND dcg.tipo_grano = 'PERGAMINO' AND cg.estado = '1'  AND cg.estado_delete = '1'  GROUP BY dcg.tipo_grano;";
    $kilo_pergamino = ejecutarConsultaSimpleFila($sql_3);  if ($kilo_pergamino['status'] == false) { return $kilo_pergamino; }

    $sql_3_1 = "SELECT SUM(dcg.subtotal) AS subtotal_pergamino
    FROM detalle_compra_grano AS dcg, compra_grano AS cg 
    WHERE dcg.idcompra_grano = cg.idcompra_grano  AND dcg.tipo_grano = 'PERGAMINO' AND cg.estado = '1'  AND cg.estado_delete = '1'  GROUP BY dcg.tipo_grano;";
    $sol_pergamino = ejecutarConsultaSimpleFila($sql_3_1);  if ($sol_pergamino['status'] == false) { return $sol_pergamino; }

    $sql_4 = "SELECT SUM(total_compra) as total_compra FROM compra_grano WHERE estado ='1' AND estado_delete ='1'";
    $total_compra = ejecutarConsultaSimpleFila($sql_4); if ($total_compra['status'] == false) { return $total_compra; }

    $sql_4 = "SELECT SUM( monto) as total_pago 
    FROM pago_compra_grano as pcg, compra_grano as cg
    WHERE pcg.idcompra_grano = cg.idcompra_grano AND pcg.estado = '1' AND pcg.estado_delete = '1' AND cg.estado = '1' AND cg.estado_delete = '1';";
    $total_pago = ejecutarConsultaSimpleFila($sql_4); if ($total_pago['status'] == false) { return $total_pago; }

    $data = array(
      'cant_clientes'   => (empty($cant_clientes['data']) ? 0 : count($cant_clientes['data']) ),
      'kilo_coco'       => (empty($kilo_coco['data']) ? 0 : $kilo_coco['data']['peso_neto']),
      'soles_coco'       => (empty($sol_coco['data']) ? 0 : $sol_coco['data']['subtotal_coco']),
      'kilo_pergamino'  => (empty($kilo_pergamino['data']) ? 0 : $kilo_pergamino['data']['peso_neto']),
      'soles_pergamino' => (empty($sol_pergamino['data']) ? 0 : $sol_pergamino['data']['subtotal_pergamino']),
      'total_compra'    => (empty($total_compra['data']) ? 0 : $total_compra['data']['total_compra']),      
      'total_pago'      => (empty($total_pago['data']) ? 0 : $total_pago['data']['total_pago']),      
    );

    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];
    
  }

  public function chart_linea($id_proyecto, $year_filtro, $mes_filtro, $dias_filtro) {
    $data_compra = Array(); $data_pagos = Array(); $data_kilos_pergamino = Array(); $data_kilos_coco = Array();

    $producto_mas_vendido_nombre = Array(); $producto_mas_vendido_cantidad = Array();

    $factura_total = 0; $factura_aceptadas = 0; $factura_rechazadas = 0; $factura_eliminadas = 0; $factura_rechazadas_eliminadas = 0;

    $factura_total_gasto = 0; $factura_total_pago = 0;

    $productos_mas_vendidos = [];

    if ($year_filtro == null || $year_filtro == '' || $mes_filtro == null || $mes_filtro == null || $mes_filtro == 'null') {
      for ($i=1; $i <= 12 ; $i++) { 
        $sql_1 = "SELECT idpersona, SUM(total_compra) as total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
        ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
        FROM compra_grano  WHERE MONTH(fecha_compra)='$i' AND   YEAR(fecha_compra) = '$year_filtro'  AND estado='1' AND estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_1); if ($mes['status'] == false) { return $mes; }
        array_push($data_compra, (empty($mes['data']) ? 0 : (empty($mes['data']['total_gasto']) ? 0 : floatval($mes['data']['total_gasto']) ) ));
        
        $sql_1 = "SELECT  SUM(pcg.monto) as total_pago
        FROM pago_compra_grano AS pcg, compra_grano AS pg
        WHERE pcg.idcompra_grano = pg.idcompra_grano AND MONTH(pcg.fecha_pago)='$i' AND  YEAR(pcg.fecha_pago) = '$year_filtro' AND pg.estado='1' AND pg.estado_delete='1' AND pcg.estado='1' AND pcg.estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_1); if ($mes['status'] == false) { return $mes; }
        array_push($data_pagos, (empty($mes['data']) ? 0 : (empty($mes['data']['total_pago']) ? 0 : floatval($mes['data']['total_pago']) ) ));

        $sql_2 = "SELECT SUM(dcg.peso_neto) as peso_neto  
        FROM detalle_compra_grano as dcg, compra_grano as cg 
        WHERE dcg.idcompra_grano = cg.idcompra_grano AND MONTH(cg.fecha_compra)='$i' AND YEAR(cg.fecha_compra) = '$year_filtro' 
        AND cg.estado='1' AND cg.estado_delete='1' AND dcg.tipo_grano = 'PERGAMINO';";
        $mes = ejecutarConsultaSimpleFila($sql_2);  if ($mes['status'] == false) { return $mes; }
        array_push($data_kilos_pergamino, (empty($mes['data']) ? 0 : (empty($mes['data']['peso_neto']) ? 0 : floatval($mes['data']['peso_neto']) ) )); 
        
        $sql_2 = "SELECT SUM(dcg.peso_neto) as peso_neto  
        FROM detalle_compra_grano as dcg, compra_grano as cg 
        WHERE dcg.idcompra_grano = cg.idcompra_grano AND MONTH(cg.fecha_compra)='$i' AND YEAR(cg.fecha_compra) = '$year_filtro' 
        AND cg.estado='1' AND cg.estado_delete='1' AND dcg.tipo_grano = 'COCO';";
        $mes = ejecutarConsultaSimpleFila($sql_2);  if ($mes['status'] == false) { return $mes; }
        array_push($data_kilos_coco, (empty($mes['data']) ? 0 : (empty($mes['data']['peso_neto']) ? 0 : floatval($mes['data']['peso_neto']) ) ));      
  
      }
      $sql_3 = "SELECT COUNT(idcompra_grano) as factura_total FROM compra_grano WHERE  YEAR(fecha_compra) = '$year_filtro';";
      $factura_total = ejecutarConsultaSimpleFila($sql_3); if ($factura_total['status'] == false) { return $factura_total; }

      $sql_4 = "SELECT COUNT(idcompra_grano) as factura_aceptadas FROM compra_grano WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1';";
      $factura_aceptadas = ejecutarConsultaSimpleFila($sql_4); if ($factura_aceptadas['status'] == false) { return $factura_aceptadas; }

      $sql_5 = "SELECT COUNT(idcompra_grano) as factura_rechazadas FROM compra_grano WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='0' AND estado_delete='1';";
      $factura_rechazadas = ejecutarConsultaSimpleFila($sql_5); if ($factura_rechazadas['status'] == false) { return $factura_rechazadas; }

      $sql_6 = "SELECT COUNT(idcompra_grano) as factura_eliminadas FROM compra_grano WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='0';";
      $factura_eliminadas = ejecutarConsultaSimpleFila($sql_6); if ($factura_eliminadas['status'] == false) { return $factura_eliminadas; }

      $sql_7 = "SELECT COUNT(idcompra_grano) as factura_rechazadas_eliminadas FROM compra_grano WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='0' OR estado_delete='0';";
      $factura_rechazadas_eliminadas = ejecutarConsultaSimpleFila($sql_7); if ($factura_rechazadas_eliminadas['status'] == false) { return $factura_rechazadas_eliminadas; }

      // -------------------------
      $sql_8 = "SELECT idpersona, SUM(total_compra) as factura_total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
      ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
      FROM compra_grano  WHERE  YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1';";
      $factura_total_gasto = ejecutarConsultaSimpleFila($sql_8); if ($factura_total_gasto['status'] == false) { return $factura_total_gasto; }

      $sql_9 = "SELECT SUM(pcg.monto) as factura_total_pago  
      FROM pago_compra_grano as pcg, compra_grano as cg 
      WHERE pcg.idcompra_grano = cg.idcompra_grano  AND  YEAR(pcg.fecha_pago) = '$year_filtro' AND cg.estado='1' AND cg.estado_delete='1' AND pcg.estado='1' AND pcg.estado_delete='1';";
      $factura_total_pago = ejecutarConsultaSimpleFila($sql_9);  if ($factura_total_pago['status'] == false) { return $factura_total_pago; }

      // -----------------------
      $sql_10 = "SELECT dcg.tipo_grano, SUM(dcg.peso_bruto) as peso_bruto, SUM(dcg.dcto_humedad) AS dcto_humedad, SUM(dcg.dcto_rendimiento) AS dcto_rendimiento, SUM(dcg.dcto_cascara) AS dcto_cascara, SUM(dcg.dcto_tara) AS dcto_tara, SUM(dcg.peso_neto) AS peso_neto
      FROM compra_grano as cg, detalle_compra_grano as dcg
      WHERE cg.idcompra_grano = dcg.idcompra_grano AND  YEAR(cg.fecha_compra) = '$year_filtro'
      GROUP BY dcg.tipo_grano ;";
      $productos_mas_vendidos = ejecutarConsultaArray($sql_10);  if ($productos_mas_vendidos['status'] == false) { return $productos_mas_vendidos; }

      if ( !empty($productos_mas_vendidos['data']) ) {
        foreach ($productos_mas_vendidos['data'] as $key => $value) {
          array_push($producto_mas_vendido_nombre, $value['tipo_grano']);
          array_push($producto_mas_vendido_cantidad, $value['peso_neto']);
        }        
      }

    }else{
      for ($i=1; $i <= $dias_filtro ; $i++) {
        $sql_1 = "SELECT idpersona, SUM(total_compra) as total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
        ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
        FROM compra_grano  
        WHERE DAY(fecha_compra)='$i' AND MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' 
        AND estado='1' AND estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_1);  if ($mes['status'] == false) { return $mes; }
        array_push($data_compra, (empty($mes['data']) ? 0 : (empty($mes['data']['total_gasto']) ? 0 : floatval($mes['data']['total_gasto']) ) ));

        $sql_1 = "SELECT  SUM(pcg.monto) as total_pago
        FROM pago_compra_grano AS pcg, compra_grano AS pg
        WHERE pcg.idcompra_grano = pg.idcompra_grano AND DAY(pcg.fecha_pago)='$i' AND MONTH(pcg.fecha_pago)='$mes_filtro' AND  YEAR(pcg.fecha_pago) = '$year_filtro' 
        AND pg.estado='1' AND pg.estado_delete='1' AND pcg.estado='1' AND pcg.estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_1); if ($mes['status'] == false) { return $mes; }
        array_push($data_pagos, (empty($mes['data']) ? 0 : (empty($mes['data']['total_pago']) ? 0 : floatval($mes['data']['total_pago']) ) ));
  
        $sql_2 = "SELECT SUM(dcg.peso_neto) as peso_neto  
        FROM detalle_compra_grano as dcg, compra_grano as dg 
        WHERE dcg.idcompra_grano = dg.idcompra_grano AND DAY(dg.fecha_compra)='$i' AND MONTH(dg.fecha_compra)='$mes_filtro' 
        AND YEAR(dg.fecha_compra) = '$year_filtro' AND dg.estado='1' AND dg.estado_delete='1' AND dcg.tipo_grano = 'PERGAMINO';";
        $mes = ejecutarConsultaSimpleFila($sql_2); if ($mes['status'] == false) { return $mes; }
        array_push($data_kilos_pergamino, (empty($mes['data']) ? 0 : (empty($mes['data']['peso_neto']) ? 0 : floatval($mes['data']['peso_neto']) ) ));
        
        $sql_2 = "SELECT SUM(dcg.peso_neto) as peso_neto  
        FROM detalle_compra_grano as dcg, compra_grano as dg 
        WHERE dcg.idcompra_grano = dg.idcompra_grano AND DAY(dg.fecha_compra)='$i' AND MONTH(dg.fecha_compra)='$mes_filtro' 
        AND YEAR(dg.fecha_compra) = '$year_filtro' AND dg.estado='1' AND dg.estado_delete='1' AND dcg.tipo_grano = 'COCO';";
        $mes = ejecutarConsultaSimpleFila($sql_2); if ($mes['status'] == false) { return $mes; }
        array_push($data_kilos_coco, (empty($mes['data']) ? 0 : (empty($mes['data']['peso_neto']) ? 0 : floatval($mes['data']['peso_neto']) ) ));
      }

      $sql_3 = "SELECT COUNT(idcompra_grano) as factura_total FROM compra_grano WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' ;";
      $factura_total = ejecutarConsultaSimpleFila($sql_3); if ($factura_total['status'] == false) { return $factura_total; }

      $sql_4 = "SELECT COUNT(idcompra_grano) as factura_aceptadas FROM compra_grano WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1' ;";
      $factura_aceptadas = ejecutarConsultaSimpleFila($sql_4); if ($factura_aceptadas['status'] == false) { return $factura_aceptadas; }

      $sql_5 = "SELECT COUNT(idcompra_grano) as factura_rechazadas FROM compra_grano WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='0' AND estado_delete='1' ;";
      $factura_rechazadas = ejecutarConsultaSimpleFila($sql_5);  if ($factura_rechazadas['status'] == false) { return $factura_rechazadas; }

      $sql_6 = "SELECT COUNT(idcompra_grano) as factura_eliminadas FROM compra_grano WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='0' ;";
      $factura_eliminadas = ejecutarConsultaSimpleFila($sql_6); if ($factura_eliminadas['status'] == false) { return $factura_eliminadas; }

      $sql_7 = "SELECT COUNT(idcompra_grano) as factura_rechazadas_eliminadas FROM compra_grano WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='0' OR estado_delete='0' ;";
      $factura_rechazadas_eliminadas = ejecutarConsultaSimpleFila($sql_7); if ($factura_rechazadas_eliminadas['status'] == false) { return $factura_rechazadas_eliminadas; }

      // -------------------------
      $sql_8 = "SELECT idpersona, SUM(total_compra) as factura_total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
      ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
      FROM compra_grano  WHERE  MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro'  AND estado='1' AND estado_delete='1';";
      $factura_total_gasto = ejecutarConsultaSimpleFila($sql_8);  if ($factura_total_gasto['status'] == false) { return $factura_total_gasto; }

      $sql_9 = "SELECT SUM(pcg.monto) as factura_total_pago  
      FROM pago_compra_grano as pcg, compra_grano as cp 
      WHERE pcg.idcompra_grano = cp.idcompra_grano  AND MONTH(pcg.fecha_pago)='$mes_filtro' AND YEAR(pcg.fecha_pago) = '$year_filtro' AND cp.estado='1' AND cp.estado_delete='1' AND pcg.estado='1' AND pcg.estado_delete='1';";
      $factura_total_pago = ejecutarConsultaSimpleFila($sql_9);  if ($factura_total_pago['status'] == false) { return $factura_total_pago; }

      // -----------------------
      $sql_10 = "SELECT dcg.tipo_grano, SUM(dcg.peso_bruto) as peso_bruto, SUM(dcg.dcto_humedad) AS dcto_humedad, SUM(dcg.dcto_rendimiento) AS dcto_rendimiento, SUM(dcg.dcto_cascara) AS dcto_cascara, SUM(dcg.dcto_tara) AS dcto_tara, SUM(dcg.peso_neto) AS peso_neto
      FROM compra_grano as cg, detalle_compra_grano as dcg
      WHERE cg.idcompra_grano = dcg.idcompra_grano AND MONTH(cg.fecha_compra)='$mes_filtro' AND  YEAR(cg.fecha_compra) = '$year_filtro'
      GROUP BY dcg.tipo_grano;";
      $productos_mas_vendidos = ejecutarConsultaArray($sql_10);  if ($productos_mas_vendidos['status'] == false) { return $productos_mas_vendidos; }

      if ( !empty($productos_mas_vendidos['data']) ) {
        foreach ($productos_mas_vendidos['data'] as $key => $value) {
          array_push($producto_mas_vendido_nombre, $value['tipo_grano']);
          array_push($producto_mas_vendido_cantidad, $value['peso_neto']);
        }        
      }
    }
    
    
    return $retorno = [
      'status'=> true, 'message' => 'Salió todo ok,', 
      'data' => [
        'total_compra'=>$data_compra, 
        'total_deposito'=>$data_pagos, 

        'total_kilos_pergamino'=>$data_kilos_pergamino, 
        'total_kilos_coco'=>$data_kilos_coco, 

        'factura_total'=>(empty($factura_total['data']) ? 0 : (empty($factura_total['data']['factura_total']) ? 0 : floatval($factura_total['data']['factura_total']) ) ), 
        'factura_aceptadas'=>(empty($factura_aceptadas['data']) ? 0 : (empty($factura_aceptadas['data']['factura_aceptadas']) ? 0 : floatval($factura_aceptadas['data']['factura_aceptadas']) ) ), 
        'factura_rechazadas'=>(empty($factura_rechazadas['data']) ? 0 : (empty($factura_rechazadas['data']['factura_rechazadas']) ? 0 : floatval($factura_rechazadas['data']['factura_rechazadas']) ) ), 
        'factura_eliminadas'=>(empty($factura_eliminadas['data']) ? 0 : (empty($factura_eliminadas['data']['factura_eliminadas']) ? 0 : floatval($factura_eliminadas['data']['factura_eliminadas']) ) ),
        'factura_rechazadas_eliminadas'=>(empty($factura_rechazadas_eliminadas['data']) ? 0 : (empty($factura_rechazadas_eliminadas['data']['factura_rechazadas_eliminadas']) ? 0 : floatval($factura_rechazadas_eliminadas['data']['factura_rechazadas_eliminadas']) ) ), 
        
        'factura_total_gasto'=>(empty($factura_total_gasto['data']) ? 0 : (empty($factura_total_gasto['data']['factura_total_gasto']) ? 0 : floatval($factura_total_gasto['data']['factura_total_gasto']) ) ),
        'factura_total_pago'=>(empty($factura_total_pago['data']) ? 0 : (empty($factura_total_pago['data']['factura_total_pago']) ? 0 : floatval($factura_total_pago['data']['factura_total_pago']) ) ),

        'productos_mas_vendidos'=>$productos_mas_vendidos['data'], 
        'producto_mas_vendido_nombre'=>$producto_mas_vendido_nombre, 
        'producto_mas_vendido_cantidad'=>$producto_mas_vendido_cantidad, 
      ]  
    ];
  }

  public function anios_select2() {
    $sql = "SELECT DISTINCTROW YEAR(fecha_compra) as anios FROM compra_grano ORDER BY fecha_compra DESC;";
    return ejecutarConsultaArray($sql);
  }
    
}

?>
