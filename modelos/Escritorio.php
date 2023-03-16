<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Escritorio
{

  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  
  // optenemos el total de PROYECTOS, PROVEEDORES, TRABAJADORES, SERVICIO
  public function tablero()  {
    $sql = "SELECT COUNT(p.idproducto) AS cant_producto FROM producto AS p WHERE p.estado = '1' AND p.estado_delete = '1';";
    $sql2 = "SELECT COUNT(p.idpersona) AS cant_agricultor FROM persona AS p WHERE p.idtipo_persona = '2' AND p.estado = '1' AND p.estado_delete = '1';";
    $sql3 = "SELECT COUNT(p.idpersona) AS cant_trabajador FROM persona AS p WHERE p.idtipo_persona = '4' AND p.estado = '1' AND p.estado_delete = '1';";
    $sql4 = "SELECT SUM(vp.total) AS cant_venta_producto FROM venta_producto AS vp WHERE vp.estado = '1' AND vp.estado_delete = '1';";

    $data1 = ejecutarConsultaSimpleFila($sql); if ($data1['status'] == false) { return $data1; }
    $data2 = ejecutarConsultaSimpleFila($sql2); if ($data2['status'] == false) { return $data2; }
    $data3 = ejecutarConsultaSimpleFila($sql3); if ($data3['status'] == false) { return $data3; }
    $data4 = ejecutarConsultaSimpleFila($sql4); if ($data4['status'] == false) { return $data4; }

    $results = [
      "status" => true,
      "data" => [
        "cant_producto"  => (empty($data1['data']) ? 0 : (empty($data1['data']['cant_producto']) ? 0 : floatval($data1['data']['cant_producto']) ) ),
        "cant_agricultor" => (empty($data2['data']) ? 0 : (empty($data2['data']['cant_agricultor']) ? 0 : floatval($data2['data']['cant_agricultor']) ) ),
        "cant_trabajador"=> (empty($data3['data']) ? 0 : (empty($data3['data']['cant_trabajador']) ? 0 : floatval($data3['data']['cant_trabajador']) ) ),
        "cant_venta_producto"  => (empty($data4['data']) ? 0 : (empty($data4['data']['cant_venta_producto']) ? 0 : floatval($data4['data']['cant_venta_producto'])  ) ),
      ],
      "message"=> 'Todo oka'
    ];
    
    return $results;
  }

  public function chart_producto( ) {

    $data_venta = Array(); $data_pagos = Array();
    $data_compra = Array(); $data_pagos = Array(); $data_kilos_pergamino = Array(); $data_kilos_coco = Array();

    for ($i=1; $i <= 12 ; $i++) { 
      $sql_1 = "SELECT idpersona, SUM(total) as total_gasto , ELT(MONTH(fecha_venta), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
      ELT(MONTH(fecha_venta), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_venta 
      FROM venta_producto  WHERE MONTH(fecha_venta)='$i'  AND estado='1' AND estado_delete='1';";
      $mes = ejecutarConsultaSimpleFila($sql_1); if ($mes['status'] == false) { return $mes; }
      array_push($data_venta, (empty($mes['data']) ? 0 : (empty($mes['data']['total_gasto']) ? 0 : floatval($mes['data']['total_gasto']) ) ));

      $sql_2 = "SELECT SUM(pg.monto) as total_deposito  
      FROM pago_venta_producto as pg, venta_producto as cpp 
      WHERE pg.idventa_producto = cpp.idventa_producto AND MONTH(pg.fecha_pago)='$i' AND cpp.estado='1' AND cpp.estado_delete='1';";
      $mes = ejecutarConsultaSimpleFila($sql_2); if ($mes['status'] == false) { return $mes; }
      array_push($data_pagos, (empty($mes['data']) ? 0 : (empty($mes['data']['total_deposito']) ? 0 : floatval($mes['data']['total_deposito']) ) ));       

    }

    for ($i=1; $i <= 12 ; $i++) { 
      $sql_1 = "SELECT idpersona, SUM(total_compra) as total_gasto , ELT(MONTH(fecha_compra), 'En.', 'Febr.', 'Mzo.', 'Abr.', 'My.', 'Jun.', 'Jul.', 'Agt.', 'Sept.', 'Oct.', 'Nov.', 'Dic.') as mes_name_abreviado, 
      ELT(MONTH(fecha_compra), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') as mes_name, fecha_compra 
      FROM compra_grano  WHERE MONTH(fecha_compra)='$i' AND estado='1' AND estado_delete='1';";
      $mes = ejecutarConsultaSimpleFila($sql_1); if ($mes['status'] == false) { return $mes; }
      array_push($data_compra, (empty($mes['data']) ? 0 : (empty($mes['data']['total_gasto']) ? 0 : floatval($mes['data']['total_gasto']) ) ));
      
      $sql_1 = "SELECT  SUM(pcg.monto) as total_pago
      FROM pago_compra_grano AS pcg, compra_grano AS pg
      WHERE pcg.idcompra_grano = pg.idcompra_grano AND MONTH(pcg.fecha_pago)='$i' AND pg.estado='1' AND pg.estado_delete='1' AND pcg.estado='1' AND pcg.estado_delete='1';";
      $mes = ejecutarConsultaSimpleFila($sql_1); if ($mes['status'] == false) { return $mes; }
      array_push($data_pagos, (empty($mes['data']) ? 0 : (empty($mes['data']['total_pago']) ? 0 : floatval($mes['data']['total_pago']) ) ));

      $sql_2 = "SELECT SUM(dcg.peso_neto) as peso_neto  
      FROM detalle_compra_grano as dcg, compra_grano as cg 
      WHERE dcg.idcompra_grano = cg.idcompra_grano AND MONTH(cg.fecha_compra)='$i' 
      AND cg.estado='1' AND cg.estado_delete='1' AND dcg.tipo_grano = 'PERGAMINO';";
      $mes = ejecutarConsultaSimpleFila($sql_2);  if ($mes['status'] == false) { return $mes; }
      array_push($data_kilos_pergamino, (empty($mes['data']) ? 0 : (empty($mes['data']['peso_neto']) ? 0 : floatval($mes['data']['peso_neto']) ) )); 
      
      $sql_2 = "SELECT SUM(dcg.peso_neto) as peso_neto  
      FROM detalle_compra_grano as dcg, compra_grano as cg 
      WHERE dcg.idcompra_grano = cg.idcompra_grano AND MONTH(cg.fecha_compra)='$i' 
      AND cg.estado='1' AND cg.estado_delete='1' AND dcg.tipo_grano = 'COCO';";
      $mes = ejecutarConsultaSimpleFila($sql_2);  if ($mes['status'] == false) { return $mes; }
      array_push($data_kilos_coco, (empty($mes['data']) ? 0 : (empty($mes['data']['peso_neto']) ? 0 : floatval($mes['data']['peso_neto']) ) ));      

    }

    return $retorno = [
      'status'=> true, 'message' => 'Salió todo ok,', 
      'data' => [
        'total_venta'=>$data_venta, 
        'total_pagos'=>$data_pagos,

        'total_compra'=>$data_compra, 
        'total_deposito'=>$data_pagos,
        'total_kilos_pergamino'=>$data_kilos_pergamino, 
        'total_kilos_coco'=>$data_kilos_coco,        
      ]
    ];   
  }

  public function sumas_totales( ) {
    $sql_1 = "SELECT SUM(total) total_venta FROM venta_producto WHERE estado = '1' AND estado_delete ='1';";
    $sql_2 = "SELECT SUM(dvp.subtotal - (dvp.cantidad * dvp.precio_compra)) as total_utilidad FROM detalle_venta_producto as dvp, venta_producto as vp 
    WHERE dvp.idventa_producto = vp.idventa_producto AND vp.estado ='1' AND vp.estado_delete='1' AND dvp.estado='1' AND dvp.estado_delete='1';";
    $sql_3 = "SELECT SUM(total_compra) as total_compra FROM compra_grano WHERE estado = '1' AND estado_delete ='1';";
    $sql_4 = "SELECT SUM(monto) as total_deposito FROM pago_compra_grano as pcg, compra_grano as pg 
    WHERE pcg.idcompra_grano = pg.idcompra_grano AND pcg.estado = '1' AND pcg.estado_delete = '1' AND pg.estado = '1' AND pg.estado_delete = '1';";

    $data1 = ejecutarConsultaSimpleFila($sql_1);  if ($data1['status'] == false) { return $data1; }
    $data2 = ejecutarConsultaSimpleFila($sql_2);  if ($data2['status'] == false) { return $data2; }
    $data3 = ejecutarConsultaSimpleFila($sql_3);  if ($data3['status'] == false) { return $data3; }
    $data4 = ejecutarConsultaSimpleFila($sql_4);  if ($data4['status'] == false) { return $data4; }

    return $retorno = [
      'status'=> true, 'message' => 'Salió todo ok,', 
      'data' => [
        'total_venta'   =>(empty($data1['data']) ? 0 : (empty($data1['data']['total_venta']) ? 0 : floatval($data1['data']['total_venta'])) ), 
        'total_utilidad' =>(empty($data2['data']) ? 0 : (empty($data2['data']['total_utilidad']) ? 0 : floatval($data2['data']['total_utilidad'])) ),
        'total_compra'  =>(empty($data3['data']) ? 0 : (empty($data3['data']['total_compra']) ? 0 : floatval($data3['data']['total_compra'])) ), 
        'total_deposito_compra'=>(empty($data4['data']) ? 0 : (empty($data4['data']['total_deposito']) ? 0 : floatval($data4['data']['total_deposito'])) ),        
      ]
    ];   
  }
}

?>
