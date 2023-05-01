<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ChartCompraProducto
{
  //Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

  //Implementar un método para mostrar los datos de un registro a modificar
  public function box_content_reporte( $id_sucursal ) {
    $data = Array();

    // total de clientees
    $sql_1 = "SELECT COUNT(cp.idpersona) AS cant 
    FROM compra_producto AS cp 
    WHERE cp.estado = '1' AND cp.estado_delete = '1' AND cp.idpersona_sucursal = '$id_sucursal' GROUP BY cp.idpersona;";
    $cant_clientes = ejecutarConsultaArray($sql_1); if ($cant_clientes['status'] == false) { return $cant_clientes; }

    // total de producto
    $sql_2 = "SELECT COUNT(dcp.idproducto) AS cant 
    FROM detalle_compra_producto AS dcp, compra_producto AS cp 
    WHERE dcp.idcompra_producto = cp.idcompra_producto AND cp.estado = '1' AND cp.estado_delete = '1' AND cp.idpersona_sucursal = '$id_sucursal' GROUP BY dcp.idproducto;";
    $cant_producto = ejecutarConsultaArray($sql_2); if ($cant_producto['status'] == false) { return $cant_producto; }

    // total de venta
    $sql_3 = "SELECT SUM( cp.total_compra) as cant FROM compra_producto as cp WHERE cp.estado ='1' AND cp.estado_delete = '1' AND cp.idpersona_sucursal = '$id_sucursal';";
    $cant_total_venta = ejecutarConsultaSimpleFila($sql_3); if ($cant_total_venta['status'] == false) { return $cant_total_venta; }

    // calcular stock
    $sql_1 = "SELECT SUM(dcp.cantidad) as cant_compra FROM detalle_compra_producto as dcp, compra_producto as cp
    WHERE dcp.idcompra_producto = cp.idcompra_producto AND cp.estado = '1' AND cp.estado_delete = '1' AND dcp.estado = '1' AND dcp.estado_delete = '1' AND cp.idpersona_sucursal = '$id_sucursal' ;";
    $compra = ejecutarConsultaSimpleFila($sql_1); if ( $compra['status'] == false) {return $compra; }  

    $sql_2 = "SELECT SUM(dvp.cantidad) as cant_venta FROM detalle_venta_producto as dvp, venta_producto as vp
    WHERE vp.idventa_producto = dvp.idventa_producto AND vp.estado = '1' AND vp.estado_delete = '1' AND dvp.estado = '1' AND dvp.estado_delete = '1' AND vp.idpersona_sucursal = '$id_sucursal' ;";
    $venta = ejecutarConsultaSimpleFila($sql_2); if ( $venta['status'] == false) {return $venta; }  

    $n_compra = empty($compra['data']) ? 0 : (empty($compra['data']['cant_compra']) ? 0 : floatval($compra['data']['cant_compra']) ) ;
    $n_venta  = empty($venta['data']) ? 0 : (empty($venta['data']['cant_venta']) ? 0 : floatval($venta['data']['cant_venta']) ) ;

    $stock = $n_compra - $n_venta;

    $data = array(
      'cant_clientes'   => ( empty($cant_clientes['data']) ? 0 : count($cant_clientes['data']) ),
      'cant_producto'   => (empty($cant_producto['data']) ? 0 : count($cant_producto['data'])),
      'cant_total_venta'=> (empty($cant_total_venta['data']) ? 0 : (empty($cant_total_venta['data']['cant']) ? 0 : floatval($cant_total_venta['data']['cant']) )),
      'cant_total_stock'=> $stock,
      
    );
    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];
    
  }

  public function export_productos_mas_usados($id_sucursal, $anio, $mes) {   

    if ($anio == null || $anio == '' || $mes == null || $mes == '' || $mes == 'null') {
      $sql_1 = "SELECT dt.idproducto, p.nombre as producto, p.imagen, p.precio_venta as precio_referencial, SUM(dt.cantidad) AS cantidad_vendida, p.descripcion, l.nombre as laboratorio
      FROM compra_producto as cp, detalle_compra_producto as dt, producto as p, laboratorio as l
      WHERE cp.idcompra_producto = dt.idcompra_producto AND dt.idproducto = p.idproducto AND l.idlaboratorio = p.idlaboratorio AND YEAR(cp.fecha_compra) = '$anio' AND cp.idpersona_sucursal = '$id_sucursal'
      GROUP BY dt.idproducto
      ORDER BY SUM(dt.cantidad) DESC
      LIMIT 0 , 6;";
      return ejecutarConsultaArray($sql_1); 
    } else {
      $sql_2 = "SELECT dt.idproducto,p.nombre as producto, p.imagen, p.precio_venta as precio_referencial, SUM(dt.cantidad) AS cantidad_vendida, p.descripcion, l.nombre as laboratorio
      FROM compra_producto as cp, detalle_compra_producto as dt, producto as p, laboratorio as l
      WHERE cp.idcompra_producto = dt.idcompra_producto AND dt.idproducto = p.idproducto AND l.idlaboratorio = p.idlaboratorio AND MONTH(cp.fecha_compra)='$mes' AND  YEAR(cp.fecha_compra) = '$anio' AND cp.idpersona_sucursal = '$id_sucursal'
      GROUP BY dt.idproducto
      ORDER BY SUM(dt.cantidad) DESC
      LIMIT 0 , 6;";
      return ejecutarConsultaArray($sql_2); 
    } 
    
  }

  public function chart_linea($id_sucursal, $year_filtro, $mes_filtro, $dias_filtro) {
    $data_gasto = Array(); $data_pagos = Array();

    $producto_mas_vendido_nombre = Array(); $producto_mas_vendido_cantidad = Array();

    $factura_total = 0; $factura_aceptadas = 0; $factura_rechazadas = 0; $factura_eliminadas = 0; $factura_rechazadas_eliminadas = 0;

    $factura_total_gasto = 0; $factura_total_pago = 0;

    $productos_mas_vendidos = [];

    if ($year_filtro == null || $year_filtro == '' || $mes_filtro == null || $mes_filtro == '' || $mes_filtro == 'null') {
      for ($i=1; $i <= 12 ; $i++) { 
        $sql_1 = "SELECT idpersona, SUM(total_compra) as total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
        ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
        FROM compra_producto  WHERE MONTH(fecha_compra)='$i' AND YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1' AND idpersona_sucursal = '$id_sucursal';";
        $mes = ejecutarConsultaSimpleFila($sql_1); if ($mes['status'] == false) { return $mes; }
        array_push($data_gasto, (empty($mes['data']) ? 0 : (empty($mes['data']['total_gasto']) ? 0 : floatval($mes['data']['total_gasto']) ) ));
  
        $sql_2 = "SELECT SUM(pg.monto) as total_deposito  
        FROM pago_compra_producto as pg, compra_producto as cp 
        WHERE pg.idcompra_producto = cp.idcompra_producto AND MONTH(pg.fecha_pago)='$i' AND YEAR(pg.fecha_pago) = '$year_filtro' AND cp.estado='1' AND cp.estado_delete='1' AND cp.idpersona_sucursal = '$id_sucursal';";
        $mes = ejecutarConsultaSimpleFila($sql_2); if ($mes['status'] == false) { return $mes; }
        array_push($data_pagos, (empty($mes['data']) ? 0 : (empty($mes['data']['total_deposito']) ? 0 : floatval($mes['data']['total_deposito']) ) ));       
  
      }
      $sql_3 = "SELECT COUNT(idcompra_producto) as factura_total FROM compra_producto WHERE  YEAR(fecha_compra) = '$year_filtro' ;";
      $factura_total = ejecutarConsultaSimpleFila($sql_3); if ($factura_total['status'] == false) { return $factura_total; }

      $sql_4 = "SELECT COUNT(idcompra_producto) as factura_aceptadas FROM compra_producto WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1' ;";
      $factura_aceptadas = ejecutarConsultaSimpleFila($sql_4); if ($factura_aceptadas['status'] == false) { return $factura_aceptadas; }

      $sql_5 = "SELECT COUNT(idcompra_producto) as factura_rechazadas FROM compra_producto WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='0' AND estado_delete='1' ;";
      $factura_rechazadas = ejecutarConsultaSimpleFila($sql_5); if ($factura_rechazadas['status'] == false) { return $factura_rechazadas; }

      $sql_6 = "SELECT COUNT(idcompra_producto) as factura_eliminadas FROM compra_producto WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='0' ;";
      $factura_eliminadas = ejecutarConsultaSimpleFila($sql_6); if ($factura_eliminadas['status'] == false) { return $factura_eliminadas; }

      $sql_7 = "SELECT COUNT(idcompra_producto) as factura_rechazadas_eliminadas FROM compra_producto WHERE YEAR(fecha_compra) = '$year_filtro' AND estado='0' OR estado_delete='0' ;";
      $factura_rechazadas_eliminadas = ejecutarConsultaSimpleFila($sql_7); if ($factura_rechazadas_eliminadas['status'] == false) { return $factura_rechazadas_eliminadas; }

      // -------------------------
      $sql_8 = "SELECT SUM(total_compra) as factura_total_gasto
      FROM compra_producto  WHERE  YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1';";
      $factura_total_gasto = ejecutarConsultaSimpleFila($sql_8); if ($factura_total_gasto['status'] == false) { return $factura_total_gasto; }

      $sql_9 = "SELECT SUM(pcp.monto) as factura_total_pago  
      FROM pago_compra_producto as pcp, compra_producto as cp 
      WHERE pcp.idcompra_producto = cp.idcompra_producto  AND  YEAR(pcp.fecha_pago) = '$year_filtro' AND cp.estado='1' AND cp.estado_delete='1' AND pcp.estado='1' AND pcp.estado_delete='1';";
      $factura_total_pago = ejecutarConsultaSimpleFila($sql_9); if ($factura_total_pago['status'] == false) { return $factura_total_pago; }

      // -----------------------
      $sql_10 = "SELECT dt.idproducto, p.nombre as producto, p.imagen, p.precio_venta as precio_referencial, SUM(dt.cantidad) AS cantidad_vendida, p.descripcion, l.nombre as laboratorio
      FROM compra_producto as cp, detalle_compra_producto as dt, producto as p, laboratorio as l
      WHERE cp.idcompra_producto = dt.idcompra_producto AND dt.idproducto = p.idproducto AND l.idlaboratorio = p.idlaboratorio AND  YEAR(cp.fecha_compra) = '$year_filtro'
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
        $sql_1 = "SELECT idpersona, SUM(total_compra) as total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
        ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
        FROM compra_producto  WHERE DAY(fecha_compra)='$i' AND MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_1); if ($mes['status'] == false) { return $mes; }
        array_push($data_gasto, (empty($mes['data']) ? 0 : (empty($mes['data']['total_gasto']) ? 0 : floatval($mes['data']['total_gasto']) ) ));
  
        $sql_2 = "SELECT SUM(pg.monto) as total_deposito  
        FROM pago_compra_producto as pg, compra_producto as cpp 
        WHERE pg.idcompra_producto = cpp.idcompra_producto AND DAY(pg.fecha_pago)='$i' AND MONTH(pg.fecha_pago)='$mes_filtro' AND YEAR(pg.fecha_pago) = '$year_filtro' AND cpp.estado='1' AND cpp.estado_delete='1';";
        $mes = ejecutarConsultaSimpleFila($sql_2); if ($mes['status'] == false) { return $mes; }
        array_push($data_pagos, (empty($mes['data']) ? 0 : (empty($mes['data']['total_deposito']) ? 0 : floatval($mes['data']['total_deposito']) ) ));
      }

      $sql_3 = "SELECT COUNT(idcompra_producto) as factura_total FROM compra_producto WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro';";
      $factura_total = ejecutarConsultaSimpleFila($sql_3); if ($factura_total['status'] == false) { return $factura_total; }

      $sql_4 = "SELECT COUNT(idcompra_producto) as factura_aceptadas FROM compra_producto WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1' ;";
      $factura_aceptadas = ejecutarConsultaSimpleFila($sql_4); if ($factura_aceptadas['status'] == false) { return $factura_aceptadas; }

      $sql_5 = "SELECT COUNT(idcompra_producto) as factura_rechazadas FROM compra_producto WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='0' AND estado_delete='1' ;";
      $factura_rechazadas = ejecutarConsultaSimpleFila($sql_5); if ($factura_rechazadas['status'] == false) { return $factura_rechazadas; }

      $sql_6 = "SELECT COUNT(idcompra_producto) as factura_eliminadas FROM compra_producto WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='0' ;";
      $factura_eliminadas = ejecutarConsultaSimpleFila($sql_6); if ($factura_eliminadas['status'] == false) { return $factura_eliminadas; }

      $sql_7 = "SELECT COUNT(idcompra_producto) as factura_rechazadas_eliminadas FROM compra_producto WHERE MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='0' OR estado_delete='0' ;";
      $factura_rechazadas_eliminadas = ejecutarConsultaSimpleFila($sql_7); if ($factura_rechazadas_eliminadas['status'] == false) { return $factura_rechazadas_eliminadas; }

      // -------------------------
      $sql_8 = "SELECT SUM(total_compra) as factura_total_gasto 
      FROM compra_producto  WHERE  MONTH(fecha_compra)='$mes_filtro' AND YEAR(fecha_compra) = '$year_filtro' AND estado='1' AND estado_delete='1';";
      $factura_total_gasto = ejecutarConsultaSimpleFila($sql_8); if ($factura_total_gasto['status'] == false) { return $factura_total_gasto; }

      $sql_9 = "SELECT SUM(pcg.monto) as factura_total_pago  
      FROM pago_compra_producto as pcg, compra_producto as cp 
      WHERE pcg.idcompra_producto = cp.idcompra_producto  AND MONTH(pcg.fecha_pago)='$mes_filtro' AND YEAR(pcg.fecha_pago) = '$year_filtro' AND cp.estado='1' AND cp.estado_delete='1' AND pcg.estado='1' AND pcg.estado_delete='1';";
      $factura_total_pago = ejecutarConsultaSimpleFila($sql_9); if ($factura_total_pago['status'] == false) { return $factura_total_pago; }

      // -----------------------
      $sql_10 = "SELECT dt.idproducto,p.nombre as producto, p.imagen, p.precio_venta as precio_referencial, SUM(dt.cantidad) AS cantidad_vendida, p.descripcion, l.nombre as laboratorio
      FROM compra_producto as cpp, detalle_compra_producto as dt, producto as p, laboratorio as l
      WHERE cpp.idcompra_producto = dt.idcompra_producto AND dt.idproducto = p.idproducto AND l.idlaboratorio = p.idlaboratorio AND MONTH(cpp.fecha_compra)='$mes_filtro' AND  YEAR(cpp.fecha_compra) = '$year_filtro'
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
    $sql = "SELECT DISTINCTROW YEAR(fecha_compra) as anios FROM compra_producto WHERE estado = '1' AND estado_delete = '1' ORDER BY fecha_compra DESC;";
    return ejecutarConsultaArray($sql);
  }
    
}

?>
