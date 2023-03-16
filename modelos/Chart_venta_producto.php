<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ChartVentaProducto
{
  //Implementamos nuestro constructor
  public function __construct() { }  

  //Implementar un método para mostrar los datos de un registro a modificar
  public function box_content_reporte() {
    $data = Array();

    $sql_1 = "SELECT COUNT(vp.idpersona) AS cant 
    FROM venta_producto AS vp 
    WHERE vp.estado = '1' AND vp.estado_delete = '1' GROUP BY vp.idpersona;";
    $cant_clientes = ejecutarConsultaArray($sql_1); if ($cant_clientes['status'] == false) { return $cant_clientes; }

    $sql_2 = "SELECT COUNT(dvp.idproducto) AS cant 
    FROM detalle_venta_producto AS dvp, venta_producto AS vp 
    WHERE dvp.idventa_producto = vp.idventa_producto AND vp.estado = '1' AND vp.estado_delete = '1' GROUP BY dvp.idproducto;";
    $cant_producto = ejecutarConsultaArray($sql_2); if ($cant_producto['status'] == false) { return $cant_producto; }

    $sql_3 = "SELECT SUM( vp.total) as cant FROM venta_producto as vp WHERE vp.estado ='1' AND vp.estado_delete = '1';";
    $cant_total_venta = ejecutarConsultaSimpleFila($sql_3); if ($cant_total_venta['status'] == false) { return $cant_total_venta; }

    $sql_4 = "SELECT SUM(pvp.monto) as cant 
    FROM pago_venta_producto as pvp, venta_producto as vp 
    WHERE pvp.idventa_producto = vp.idventa_producto AND vp.estado ='1' AND vp.estado_delete = '1';";
    $cant_total_pago = ejecutarConsultaSimpleFila($sql_4); if ($cant_total_pago['status'] == false) { return $cant_total_pago; }

    $data = array(
      'cant_clientes'   => ( empty($cant_clientes['data']) ? 0 : count($cant_clientes['data']) ),
      'cant_producto'   => (empty($cant_producto['data']) ? 0 : count($cant_producto['data'])),
      'cant_total_venta'=> (empty($cant_total_venta['data']) ? 0 : (empty($cant_total_venta['data']['cant']) ? 0 : floatval($cant_total_venta['data']['cant']) )),
      'cant_total_pago' => (empty($cant_total_pago['data']) ? 0 : (empty($cant_total_pago['data']['cant']) ? 0 : floatval($cant_total_pago['data']['cant']) )),
      
    );
    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];
    
  }

  public function export_productos_mas_usados($anio, $mes) {   

    if ($anio == null || $anio == '' || $mes == null || $mes == '' || $mes == 'null') {
      $sql_1 = "SELECT dt.idproducto, p.nombre as producto, p.imagen, p.precio_unitario as precio_referencial, SUM(dt.cantidad) AS cantidad_vendida, p.descripcion
      FROM venta_producto as cpp, detalle_venta_producto as dt, producto as p
      WHERE cpp.idventa_producto = dt.idventa_producto AND dt.idproducto = p.idproducto AND  YEAR(cpp.fecha_venta) = '$anio'
      GROUP BY dt.idproducto
      ORDER BY SUM(dt.cantidad) DESC
      LIMIT 0 , 6;";
      return ejecutarConsultaArray($sql_1); 
    } else {
      $sql_2 = "SELECT dt.idproducto,p.nombre as producto, p.imagen, p.precio_unitario as precio_referencial, SUM(dt.cantidad) AS cantidad_vendida, p.descripcion
      FROM venta_producto as cpp, detalle_venta_producto as dt, producto as p
      WHERE cpp.idventa_producto = dt.idventa_producto AND dt.idproducto = p.idproducto AND MONTH(cpp.fecha_venta)='$mes' AND  YEAR(cpp.fecha_venta) = '$anio'
      GROUP BY dt.idproducto
      ORDER BY SUM(dt.cantidad) DESC
      LIMIT 0 , 6;";
      return ejecutarConsultaArray($sql_2); 
    } 
    
  }

  public function chart_linea($id_proyecto, $year_filtro, $mes_filtro, $dias_filtro) {
    $data_gasto = Array(); $data_pagos = Array();

    $producto_mas_vendido_nombre = Array(); $producto_mas_vendido_cantidad = Array();

    $factura_total = 0; $factura_aceptadas = 0; $factura_rechazadas = 0; $factura_eliminadas = 0; $factura_rechazadas_eliminadas = 0;

    $factura_total_gasto = 0; $factura_total_pago = 0;

    $productos_mas_vendidos = [];

    if ($year_filtro == null || $year_filtro == '' || $mes_filtro == null || $mes_filtro == '' || $mes_filtro == 'null') {
      for ($i=1; $i <= 12 ; $i++) { 
        $sql_1 = "SELECT idpersona, SUM(total) as total_gasto , ELT(MONTH(fecha_venta), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
        ELT(MONTH(fecha_venta), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_venta 
        FROM venta_producto  WHERE MONTH(fecha_venta)='$i' AND   YEAR(fecha_venta) = '$year_filtro' AND estado='1' AND estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_1); if ($mes['status'] == false) { return $mes; }
        array_push($data_gasto, (empty($mes['data']) ? 0 : (empty($mes['data']['total_gasto']) ? 0 : floatval($mes['data']['total_gasto']) ) ));
  
        $sql_2 = "SELECT SUM(pg.monto) as total_deposito  
        FROM pago_venta_producto as pg, venta_producto as cpp 
        WHERE pg.idventa_producto = cpp.idventa_producto AND MONTH(pg.fecha_pago)='$i' AND YEAR(pg.fecha_pago) = '$year_filtro' AND cpp.estado='1' AND cpp.estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_2); if ($mes['status'] == false) { return $mes; }
        array_push($data_pagos, (empty($mes['data']) ? 0 : (empty($mes['data']['total_deposito']) ? 0 : floatval($mes['data']['total_deposito']) ) ));       
  
      }
      $sql_3 = "SELECT COUNT(idventa_producto) as factura_total FROM venta_producto WHERE  YEAR(fecha_venta) = '$year_filtro' ;";
      $factura_total = ejecutarConsultaSimpleFila($sql_3); if ($factura_total['status'] == false) { return $factura_total; }

      $sql_4 = "SELECT COUNT(idventa_producto) as factura_aceptadas FROM venta_producto WHERE YEAR(fecha_venta) = '$year_filtro' AND estado='1' AND estado_delete='1' ;";
      $factura_aceptadas = ejecutarConsultaSimpleFila($sql_4); if ($factura_aceptadas['status'] == false) { return $factura_aceptadas; }

      $sql_5 = "SELECT COUNT(idventa_producto) as factura_rechazadas FROM venta_producto WHERE YEAR(fecha_venta) = '$year_filtro' AND estado='0' AND estado_delete='1' ;";
      $factura_rechazadas = ejecutarConsultaSimpleFila($sql_5); if ($factura_rechazadas['status'] == false) { return $factura_rechazadas; }

      $sql_6 = "SELECT COUNT(idventa_producto) as factura_eliminadas FROM venta_producto WHERE YEAR(fecha_venta) = '$year_filtro' AND estado='1' AND estado_delete='0' ;";
      $factura_eliminadas = ejecutarConsultaSimpleFila($sql_6); if ($factura_eliminadas['status'] == false) { return $factura_eliminadas; }

      $sql_7 = "SELECT COUNT(idventa_producto) as factura_rechazadas_eliminadas FROM venta_producto WHERE YEAR(fecha_venta) = '$year_filtro' AND estado='0' OR estado_delete='0' ;";
      $factura_rechazadas_eliminadas = ejecutarConsultaSimpleFila($sql_7); if ($factura_rechazadas_eliminadas['status'] == false) { return $factura_rechazadas_eliminadas; }

      // -------------------------
      $sql_8 = "SELECT SUM(total) as factura_total_gasto
      FROM venta_producto  WHERE  YEAR(fecha_venta) = '$year_filtro' AND estado='1' AND estado_delete='1';";
      $factura_total_gasto = ejecutarConsultaSimpleFila($sql_8); if ($factura_total_gasto['status'] == false) { return $factura_total_gasto; }

      $sql_9 = "SELECT SUM(pvp.monto) as factura_total_pago  
      FROM pago_venta_producto as pvp, venta_producto as vp 
      WHERE pvp.idventa_producto = vp.idventa_producto  AND  YEAR(pvp.fecha_pago) = '$year_filtro' AND vp.estado='1' AND vp.estado_delete='1' AND pvp.estado='1' AND pvp.estado_delete='1';";
      $factura_total_pago = ejecutarConsultaSimpleFila($sql_9); if ($factura_total_pago['status'] == false) { return $factura_total_pago; }

      // -----------------------
      $sql_10 = "SELECT dt.idproducto, p.nombre as producto, p.imagen, p.precio_unitario as precio_referencial, SUM(dt.cantidad) AS cantidad_vendida, p.descripcion
      FROM venta_producto as cpp, detalle_venta_producto as dt, producto as p
      WHERE cpp.idventa_producto = dt.idventa_producto AND dt.idproducto = p.idproducto AND  YEAR(cpp.fecha_venta) = '$year_filtro'
      GROUP BY dt.idproducto
      ORDER BY SUM(dt.cantidad) DESC
      LIMIT 0 , 6;";
      $productos_mas_vendidos = ejecutarConsultaArray($sql_10); if ($productos_mas_vendidos['status'] == false) { return $productos_mas_vendidos; }

      if ( !empty($productos_mas_vendidos['data']) ) {
        foreach ($productos_mas_vendidos['data'] as $key => $value) {
          array_push($producto_mas_vendido_nombre, $value['producto']);
          array_push($producto_mas_vendido_cantidad, $value['cantidad_vendida']);
        }        
      }

    }else{
      for ($i=1; $i <= $dias_filtro ; $i++) {
        $sql_1 = "SELECT idpersona, SUM(total) as total_gasto , ELT(MONTH(fecha_venta), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
        ELT(MONTH(fecha_venta), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_venta 
        FROM venta_producto  WHERE DAY(fecha_venta)='$i' AND MONTH(fecha_venta)='$mes_filtro' AND YEAR(fecha_venta) = '$year_filtro' AND estado='1' AND estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_1); if ($mes['status'] == false) { return $mes; }
        array_push($data_gasto, (empty($mes['data']) ? 0 : (empty($mes['data']['total_gasto']) ? 0 : floatval($mes['data']['total_gasto']) ) ));
  
        $sql_2 = "SELECT SUM(pg.monto) as total_deposito  
        FROM pago_venta_producto as pg, venta_producto as cpp 
        WHERE pg.idventa_producto = cpp.idventa_producto AND DAY(pg.fecha_pago)='$i' AND MONTH(pg.fecha_pago)='$mes_filtro' AND YEAR(pg.fecha_pago) = '$year_filtro' AND cpp.estado='1' AND cpp.estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_2); if ($mes['status'] == false) { return $mes; }
        array_push($data_pagos, (empty($mes['data']) ? 0 : (empty($mes['data']['total_deposito']) ? 0 : floatval($mes['data']['total_deposito']) ) ));
      }

      $sql_3 = "SELECT COUNT(idventa_producto) as factura_total FROM venta_producto WHERE MONTH(fecha_venta)='$mes_filtro' AND YEAR(fecha_venta) = '$year_filtro';";
      $factura_total = ejecutarConsultaSimpleFila($sql_3); if ($factura_total['status'] == false) { return $factura_total; }

      $sql_4 = "SELECT COUNT(idventa_producto) as factura_aceptadas FROM venta_producto WHERE MONTH(fecha_venta)='$mes_filtro' AND YEAR(fecha_venta) = '$year_filtro' AND estado='1' AND estado_delete='1' ;";
      $factura_aceptadas = ejecutarConsultaSimpleFila($sql_4); if ($factura_aceptadas['status'] == false) { return $factura_aceptadas; }

      $sql_5 = "SELECT COUNT(idventa_producto) as factura_rechazadas FROM venta_producto WHERE MONTH(fecha_venta)='$mes_filtro' AND YEAR(fecha_venta) = '$year_filtro' AND estado='0' AND estado_delete='1' ;";
      $factura_rechazadas = ejecutarConsultaSimpleFila($sql_5); if ($factura_rechazadas['status'] == false) { return $factura_rechazadas; }

      $sql_6 = "SELECT COUNT(idventa_producto) as factura_eliminadas FROM venta_producto WHERE MONTH(fecha_venta)='$mes_filtro' AND YEAR(fecha_venta) = '$year_filtro' AND estado='1' AND estado_delete='0' ;";
      $factura_eliminadas = ejecutarConsultaSimpleFila($sql_6); if ($factura_eliminadas['status'] == false) { return $factura_eliminadas; }

      $sql_7 = "SELECT COUNT(idventa_producto) as factura_rechazadas_eliminadas FROM venta_producto WHERE MONTH(fecha_venta)='$mes_filtro' AND YEAR(fecha_venta) = '$year_filtro' AND estado='0' OR estado_delete='0' ;";
      $factura_rechazadas_eliminadas = ejecutarConsultaSimpleFila($sql_7); if ($factura_rechazadas_eliminadas['status'] == false) { return $factura_rechazadas_eliminadas; }

      // -------------------------
      $sql_8 = "SELECT SUM(total) as factura_total_gasto 
      FROM venta_producto  WHERE  MONTH(fecha_venta)='$mes_filtro' AND YEAR(fecha_venta) = '$year_filtro' AND estado='1' AND estado_delete='1';";
      $factura_total_gasto = ejecutarConsultaSimpleFila($sql_8); if ($factura_total_gasto['status'] == false) { return $factura_total_gasto; }

      $sql_9 = "SELECT SUM(pvp.monto) as factura_total_pago  
      FROM pago_venta_producto as pvp, venta_producto as vp 
      WHERE pvp.idventa_producto = vp.idventa_producto  AND MONTH(pvp.fecha_pago)='$mes_filtro' AND YEAR(pvp.fecha_pago) = '$year_filtro' AND vp.estado='1' AND vp.estado_delete='1' AND pvp.estado='1' AND pvp.estado_delete='1';";
      $factura_total_pago = ejecutarConsultaSimpleFila($sql_9); if ($factura_total_pago['status'] == false) { return $factura_total_pago; }

      // -----------------------
      $sql_10 = "SELECT dt.idproducto,p.nombre as producto, p.imagen, p.precio_unitario as precio_referencial, SUM(dt.cantidad) AS cantidad_vendida, p.descripcion
      FROM venta_producto as cpp, detalle_venta_producto as dt, producto as p
      WHERE cpp.idventa_producto = dt.idventa_producto AND dt.idproducto = p.idproducto AND MONTH(cpp.fecha_venta)='$mes_filtro' AND  YEAR(cpp.fecha_venta) = '$year_filtro'
      GROUP BY dt.idproducto
      ORDER BY SUM(dt.cantidad) DESC
      LIMIT 0 , 6;";
      $productos_mas_vendidos = ejecutarConsultaArray($sql_10); if ($productos_mas_vendidos['status'] == false) { return $productos_mas_vendidos; }

      if ( !empty($productos_mas_vendidos['data']) ) {
        foreach ($productos_mas_vendidos['data'] as $key => $value) {
          array_push($producto_mas_vendido_nombre, $value['producto']);
          array_push($producto_mas_vendido_cantidad, $value['cantidad_vendida']);
        }        
      }
    }
    
    
    return $retorno = [
      'status'=> true, 'message' => 'Salió todo ok,', 
      'data' => [
        'total_gasto'=>$data_gasto, 
        'total_deposito'=>$data_pagos, 

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
    $sql = "SELECT DISTINCTROW YEAR(fecha_venta) as anios FROM venta_producto WHERE estado = '1' AND estado_delete = '1' ORDER BY fecha_venta DESC;";
    return ejecutarConsultaArray($sql);
  }
    
}

?>
