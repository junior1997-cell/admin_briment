<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Resumen_general
{
  //Implementamos nuestro constructor
  public function __construct() { }

  // TABLA
  public function tabla_compras($idproyecto, $fecha_filtro_1, $fecha_filtro_2, $id_proveedor) {

    $Arraycompras = [];   $filtro_proveedor = ""; $filtro_fecha = "";

    if (empty($id_proveedor) || $id_proveedor == 0) {
      $filtro_proveedor = "";
    } else {
      $filtro_proveedor = "AND cpp.idproveedor = '$id_proveedor'";
    }

    if ( !empty($fecha_filtro_1) && !empty($fecha_filtro_2) ) {
      $filtro_fecha = "AND cpp.fecha_compra BETWEEN '$fecha_filtro_1' AND '$fecha_filtro_2'";
    } else {
      if (!empty($fecha_filtro_1)) {
        $filtro_fecha = "AND cpp.fecha_compra = '$fecha_filtro_1'";
      }else{
        if (!empty($fecha_filtro_2)) {
          $filtro_fecha = "AND cpp.fecha_compra = '$fecha_filtro_2'";
        }     
      }      
    }    

    $sql = "SELECT cpp.idcompra_proyecto, cpp.idproyecto, cpp.idproveedor, cpp.fecha_compra, cpp.total, p.razon_social, cpp.descripcion 
		FROM compra_por_proyecto as cpp, proveedor as p
		WHERE cpp.idproyecto='$idproyecto' AND cpp.idproveedor=p.idproveedor $filtro_fecha $filtro_proveedor AND cpp.estado='1' AND cpp.estado_delete='1' 
		ORDER by cpp.fecha_compra DESC";

    $compras = ejecutarConsultaArray($sql);
    if ($compras['status'] == false) {  return $compras;}

    if (!empty($compras['data'])) {
      foreach ($compras['data'] as $key => $value) {
        $idcompra = $value['idcompra_proyecto'];

        $sql_2 = "SELECT SUM(pc.monto) as total_p FROM pago_compras as pc WHERE pc.idcompra_proyecto='$idcompra' AND pc.estado='1' GROUP BY idcompra_proyecto";
        $t_monto = ejecutarConsultaSimpleFila($sql_2);
        if ($t_monto['status'] == false) {  return $t_monto;}

        $Arraycompras[] = [
          "idcompra_proyecto" => $value['idcompra_proyecto'],
          "idproyecto" => $value['idproyecto'],
          "idproveedor" => $value['idproveedor'],
          "fecha_compra" => $value['fecha_compra'],
          "monto_total" => ($retVal_1 = empty($value['total']) ? 0 : $value['total']),
          "proveedor" => $value['razon_social'],
          "descripcion" => $value['descripcion'],

          "monto_pago_total" => ($retVal_2 = empty($t_monto['data']) ? 0 : ($retVal_3 = empty($t_monto['data']['total_p']) ? 0 : $t_monto['data']['total_p'])),
        ];
      }
    }

    return $retorno = ['status'=> true, 'message'=> 'todo oka ps', 'data'=>$Arraycompras ] ;
  }

  // TABLA
  public function tabla_maquinaria_y_equipo($idproyecto, $fecha_filtro_1, $fecha_filtro_2, $id_proveedor, $tipo)  {

    $serv_maquinaria = [];  $pago_total = 0; $filtro_proveedor = ""; $filtro_fecha = "";    

    if ( !empty($fecha_filtro_1) && !empty($fecha_filtro_2) ) {
      $filtro_fecha = "AND s.fecha_entrega BETWEEN '$fecha_filtro_1' AND '$fecha_filtro_2'";
    } else {
      if (!empty($fecha_filtro_1)) {
        $filtro_fecha = "AND s.fecha_entrega = '$fecha_filtro_1'";
      }else{
        if (!empty($fecha_filtro_2)) {
          $filtro_fecha = "AND s.fecha_entrega = '$fecha_filtro_2'";
        }     
      }      
    }

    if (empty($id_proveedor) || $id_proveedor == 0) { $filtro_proveedor = ""; } else { $filtro_proveedor = "AND m.idproveedor = '$id_proveedor'"; }

    $sql ="SELECT s.idmaquinaria as idmaquinaria, s.idproyecto as idproyecto, m.nombre as maquina, p.razon_social as razon_social, 
    SUM(s.costo_parcial) AS costo_parcial , s.fecha_entrega
		FROM servicio as s, maquinaria as m, proveedor as p 
		WHERE s.estado = '1' AND s.estado_delete='1' AND s.idproyecto='$idproyecto' AND m.tipo = '$tipo' $filtro_proveedor
		AND s.idmaquinaria=m.idmaquinaria AND m.idproveedor=p.idproveedor 
    GROUP BY s.idmaquinaria;";

    $maquinaria = ejecutarConsultaArray($sql);
    if ($maquinaria['status'] == false) {  return $maquinaria;}

    if (!empty($maquinaria['data'])) {
      foreach ($maquinaria['data'] as $key => $val) {

        $idmaquinaria = $val['idmaquinaria']; $deposito_m = 0; $estado_deposito = false; $deposito_cubre = 0;

        $sql_2 = "SELECT SUM(ps.monto) as deposito FROM pago_servicio ps 
        WHERE ps.idproyecto='$idproyecto' AND ps.id_maquinaria='$idmaquinaria' AND ps.estado='1';";
        $deposito_mquina = ejecutarConsultaSimpleFila($sql_2);
        if ($deposito_mquina['status'] == false) {  return $deposito_mquina;}

        $deposito_m = (empty($deposito_mquina['data'])) ? 0 : $retVal = (empty($deposito_mquina['data']['deposito'])) ? 0 : floatval($deposito_mquina['data']['deposito']);

        $sql_3 = "SELECT s.idmaquinaria as idmaquinaria, s.idproyecto as idproyecto, m.nombre as maquina, p.razon_social as razon_social, 
        s.costo_parcial , s.fecha_entrega
        FROM servicio as s, maquinaria as m, proveedor as p 
        WHERE s.estado = '1' AND s.idproyecto='$idproyecto' AND m.tipo = '$tipo' AND s.idmaquinaria = '$idmaquinaria' $filtro_fecha
        AND s.idmaquinaria=m.idmaquinaria AND m.idproveedor=p.idproveedor ORDER by s.fecha_entrega ASC;";
        $desglose_mquina = ejecutarConsultaArray($sql_3);
        if ($desglose_mquina['status'] == false) {  return $desglose_mquina;}

        if (!empty($desglose_mquina['data'])) {
          foreach ($desglose_mquina['data'] as $keys => $value) {
             
            if ( floatval($value['costo_parcial']) < $deposito_m) {

              $deposito_m = $deposito_m - floatval($value['costo_parcial']);
              $deposito_cubre = $value['costo_parcial'];

            } else {   

              if ($deposito_m > 0) {

                $deposito_cubre = $deposito_m;
                $estado_deposito = true;
              }               
            }
            
            $serv_maquinaria[] = [
              "idmaquinaria"=> $value['idmaquinaria'],
              "idproyecto"  => $value['idproyecto'],
              "maquina"     => $value['maquina'],
              "cantidad_veces"  => 1,              
              "fecha_entrega"   => ($retVal_4 = empty($value['fecha_entrega']) ? '' : $value['fecha_entrega']),
              "proveedor"   => $value['razon_social'],

              "costo_parcial"   => ($retVal_1 = empty($value['costo_parcial']) ? 0 : $value['costo_parcial']),
              "deposito" => $deposito_cubre,
            ];

            if ($estado_deposito) { $deposito_m = 0; $deposito_cubre = 0;  }
          }
        }        
      }
    }

    return $retorno = ['status'=> true, 'message'=> 'todo oka ps', 'data'=> $serv_maquinaria];
  }

  //ver detallete por maquina-equipo
  public function ver_detalle_maq_equ($idmaquinaria, $idproyecto)  {
    $sql = "SELECT * FROM servicio as s 
    WHERE s.idmaquinaria='$idmaquinaria' AND s.idproyecto='$idproyecto' AND s.estado = '1'
    ORDER BY s.fecha_entrega DESC";

    return ejecutarConsulta($sql);
  }

  // TABLA
  public function tabla_transportes($idproyecto, $fecha_filtro_1, $fecha_filtro_2)  {

    $filtro_fecha = "";   

    if ( !empty($fecha_filtro_1) && !empty($fecha_filtro_2) ) {
      $filtro_fecha = "AND t.fecha_viaje BETWEEN '$fecha_filtro_1' AND '$fecha_filtro_2'";
    } else {
      if (!empty($fecha_filtro_1)) {
        $filtro_fecha = "AND t.fecha_viaje = '$fecha_filtro_1'";
      }else{
        if (!empty($fecha_filtro_2)) {
          $filtro_fecha = "AND t.fecha_viaje = '$fecha_filtro_2'";
        }     
      }      
    }

    $sql = "SELECT t.idtransporte, t.idproyecto, t.fecha_viaje, t.descripcion, t.precio_parcial, t.comprobante 
		FROM transporte as t, proyecto as p 
		WHERE t.idproyecto='$idproyecto' AND t.idproyecto=p.idproyecto AND t.estado='1' AND t.estado_delete='1' $filtro_fecha
		ORDER BY t.fecha_viaje DESC";
    return ejecutarConsultaArray($sql);
  }

  // TABLA
  public function tabla_hospedajes($idproyecto, $fecha_filtro_1, $fecha_filtro_2)  {

    $filtro_fecha = "";   

    if ( !empty($fecha_filtro_1) && !empty($fecha_filtro_2) ) {
      $filtro_fecha = "AND h.fecha_comprobante BETWEEN '$fecha_filtro_1' AND '$fecha_filtro_2'";
    } else {
      if (!empty($fecha_filtro_1)) {
        $filtro_fecha = "AND h.fecha_comprobante = '$fecha_filtro_1'";
      }else{
        if (!empty($fecha_filtro_2)) {
          $filtro_fecha = "AND h.fecha_comprobante = '$fecha_filtro_2'";
        }     
      }      
    }

    $sql = "SELECT h.idhospedaje, h.idproyecto, h.fecha_comprobante, h.descripcion, h.precio_parcial, h.comprobante 
		FROM hospedaje as h, proyecto as p 
		WHERE h.idproyecto=p.idproyecto AND h.idproyecto='$idproyecto' AND h.estado='1' AND h.estado_delete='1' $filtro_fecha
		ORDER BY h.fecha_comprobante DESC";
    return ejecutarConsultaArray($sql);
  }

  // TABLA
  public function tabla_comidas_extras($idproyecto, $fecha_filtro_1, $fecha_filtro_2)  {

    $filtro_fecha = "";   

    if ( !empty($fecha_filtro_1) && !empty($fecha_filtro_2) ) {
      $filtro_fecha = "AND ce.fecha_comida BETWEEN '$fecha_filtro_1' AND '$fecha_filtro_2'";
    } else {
      if (!empty($fecha_filtro_1)) {
        $filtro_fecha = "AND ce.fecha_comida = '$fecha_filtro_1'";
      }else{
        if (!empty($fecha_filtro_2)) {
          $filtro_fecha = "AND ce.fecha_comida = '$fecha_filtro_2'";
        }     
      }      
    }
    
    $sql = "SELECT ce.idcomida_extra, ce.idproyecto, ce.fecha_comida, ce.descripcion, ce.costo_parcial, ce.comprobante 
		FROM comida_extra as ce, proyecto as p 
		WHERE ce.estado='1' AND ce.estado_delete='1' AND ce.idproyecto=p.idproyecto AND ce.idproyecto='$idproyecto' $filtro_fecha
    ORDER BY ce.fecha_comida DESC";
    return ejecutarConsultaArray($sql);
  }

  // TABLA
  public function tabla_breaks($idproyecto, $fecha_filtro_1, $fecha_filtro_2)  {

    $filtro_fecha = "";   

    if ( !empty($fecha_filtro_1) && !empty($fecha_filtro_2) ) {
      $filtro_fecha = "AND sb.fecha_inicial BETWEEN '$fecha_filtro_1' AND '$fecha_filtro_2'";
    } else {
      if (!empty($fecha_filtro_1)) {
        $filtro_fecha = "AND sb.fecha_inicial = '$fecha_filtro_1'";
      }else{
        if (!empty($fecha_filtro_2)) {
          $filtro_fecha = "AND sb.fecha_inicial = '$fecha_filtro_2'";
        }     
      }      
    }

    $sql = "SELECT sb.idsemana_break, sb.idproyecto, sb.numero_semana, sb.fecha_inicial, sb.fecha_final, sb.total
		FROM semana_break as sb, proyecto as p
		WHERE sb.idproyecto ='$idproyecto' AND sb.estado='1' AND sb.estado_delete='1' AND sb.idproyecto=p.idproyecto $filtro_fecha
    ORDER BY sb.fecha_inicial DESC";
    return ejecutarConsultaArray($sql);
  }

  // DETALLE
  public function listar_comprobantes_breaks($idsemana_break)  {
    $sql = "SELECT * FROM factura_break 
		WHERE idsemana_break  ='$idsemana_break'";
    return ejecutarConsulta($sql);
  }

  // TABLA
  public function tabla_pensiones($idproyecto, $id_proveedor)  {
    $serv_pension = [];   $filtro_proveedor = "";

    if (empty($id_proveedor) || $id_proveedor == 0) {
      $filtro_proveedor = "";
    } else {
      $filtro_proveedor = "AND p.idproveedor = '$id_proveedor'";
    }

    $sql = "SELECT p.idpension, p.idproyecto, p.idproveedor, pr_v.razon_social, pr_v.direccion, p.estado
		FROM pension as p, proyecto as py, proveedor as pr_v
		WHERE p.estado='1' AND p.estado_delete='1' AND p.idproyecto='$idproyecto' AND p.idproyecto=py.idproyecto AND p.idproveedor=pr_v.idproveedor 
		$filtro_proveedor";
    $pension = ejecutarConsultaArray($sql);
    if ($pension['status'] == false) {  return $pension;}

    if (!empty($pension['data'])) {
      foreach ($pension['data'] as $key => $value) {
        $idpension = $value['idpension'];

        $sql_2 = "SELECT  SUM(precio_parcial) as gasto_pension FROM detalle_pension WHERE estado='1' AND estado_delete='1' AND idpension='$idpension'";
        $gasto_pension = ejecutarConsultaSimpleFila($sql_2);
        if ($gasto_pension['status'] == false) {  return $gasto_pension;}     

        $serv_pension[] = [
          "idpension" => $value['idpension'],
          "idproyecto" => $value['idproyecto'],
          "idproveedor" => $value['idproveedor'],
          "proveedor" => $value['razon_social'],
          "direccion" => $value['direccion'],

          "monto_total_pension" => (empty($gasto_pension['data']) ? 0 : (empty($gasto_pension['data']['gasto_pension']) ? 0 : $gasto_pension['data']['gasto_pension']) ),
          "deposito" =>  (empty($gasto_pension['data']) ? 0 : (empty($gasto_pension['data']['gasto_pension']) ? 0 : $gasto_pension['data']['gasto_pension']) )
        ];
      }
    }

    return $retorno = ['status'=> true, 'message'=> 'todo oka ps', 'data'=> $serv_pension];
  }

  public function ver_detalle_x_servicio($idpension)  {
    $sql = "SELECT * FROM detalle_pension WHERE  idpension ='$idpension' AND estado='1' AND  estado_delete='1' ORDER BY fecha_inicial DESC";
    return ejecutarConsulta($sql);
  }

  public function listar_comprobantes_pension($idpension)  {
    $sql = "SELECT * FROM detalle_pension	WHERE idpension  ='$idpension'";
    return ejecutarConsulta($sql);
  }

  // TABLA
  public function tabla_administrativo($idproyecto, $id_trabajador)  {
    $administrativo = [];
    $m_total_x_meses = 0;
    $pago_monto_total = 0;

    $consulta_filtro = "";

    if (empty($id_trabajador) || $id_trabajador == 0) {
      $consulta_filtro = "";
    } else {
      $consulta_filtro = "AND tpp.idtrabajador_por_proyecto = '$id_trabajador'";
    }

    $sql = "SELECT tpp.idtrabajador_por_proyecto, tpp.idproyecto, t.nombres, ct.nombre as cargo 
		FROM trabajador_por_proyecto as tpp, trabajador as t, cargo_trabajador as ct, tipo_trabajador as tt 
		WHERE tpp.idproyecto='$idproyecto' AND tt.nombre !='Obrero' AND tpp.idtrabajador=t.idtrabajador 
		AND tpp.idcargo_trabajador=ct.idcargo_trabajador AND ct.idcargo_trabajador=tpp.idcargo_trabajador 
		AND ct.idtipo_trabjador =tt.idtipo_trabajador  AND tpp.estado = '1' AND tpp.estado_delete = '1' $consulta_filtro";
    $traba_adm = ejecutarConsultaArray($sql);
    if ($traba_adm['status'] == false) {  return $traba_adm;}

    if (!empty($traba_adm['data'])) {
      foreach ($traba_adm['data'] as $key => $value) {
        $pago_monto_total = 0;

        $idtrabajador_por_proyecto = $value['idtrabajador_por_proyecto'];

        $sql_2 = "SELECT idfechas_mes_pagos_administrador, monto_x_mes FROM fechas_mes_pagos_administrador WHERE idtrabajador_por_proyecto='$idtrabajador_por_proyecto'";
        $fechas_mes_pagos_administrador = ejecutarConsultaArray($sql_2);
        if ($fechas_mes_pagos_administrador['status'] == false) {  return $fechas_mes_pagos_administrador;}

        $sql_3 = "SELECT SUM(monto_x_mes) as total_montos_x_meses FROM fechas_mes_pagos_administrador WHERE idtrabajador_por_proyecto='$idtrabajador_por_proyecto'";
        $total_montos_x_meses = ejecutarConsultaSimpleFila($sql_3);
        if ($total_montos_x_meses['status'] == false) {  return $total_montos_x_meses;}

        foreach ($fechas_mes_pagos_administrador['data'] as $key => $valor) {
          $idfechas_mes_pagos_administrador = $valor['idfechas_mes_pagos_administrador'];

          $sql_4 = "SELECT SUM(monto) as total_monto_pago FROM pagos_x_mes_administrador WHERE idfechas_mes_pagos_administrador='$idfechas_mes_pagos_administrador' AND estado=1";
          $return_monto_pago = ejecutarConsultaSimpleFila($sql_4);
          if ($return_monto_pago['status'] == false) {  return $return_monto_pago;}

          $pago_monto_total += empty($return_monto_pago['data']) ? 0 : ($retVal_1 = empty($return_monto_pago['data']['total_monto_pago']) ? 0 : floatval($return_monto_pago['data']['total_monto_pago']));
        }

        if (empty($total_montos_x_meses['data']['total_montos_x_meses']) || $total_montos_x_meses['data']['total_montos_x_meses'] == null) {
          $m_total_x_meses = 0;
        } else {
          $m_total_x_meses = $total_montos_x_meses['data']['total_montos_x_meses'];
        }

        $administrativo[] = [
          "idtrabajador_por_proyecto" => $value['idtrabajador_por_proyecto'],
          "idproyecto" => $value['idproyecto'],
          "nombres" => $value['nombres'],
          "cargo" => $value['cargo'],

          "total_montos_x_meses" => $m_total_x_meses,

          "deposito" => $pago_monto_total,
        ];
      }
    }

    return $retorno = ['status'=> true, 'message'=> 'todo oka ps', 'data'=>$administrativo];
  }

  public function r_detalle_trab_administrativo($idtrabajador_por_proyecto)  {
    $detalle_pagos_adm = [];
    $monto_total = 0;

    $sql = "SELECT idfechas_mes_pagos_administrador,idtrabajador_por_proyecto,fecha_inicial,fecha_final,nombre_mes,cant_dias_laborables,monto_x_mes 
		FROM fechas_mes_pagos_administrador	WHERE idtrabajador_por_proyecto='$idtrabajador_por_proyecto'";
    $fechas_mes_pagos_adm = ejecutarConsultaArray($sql);
    if ($fechas_mes_pagos_adm['status'] == false) {  return $fechas_mes_pagos_adm;}

    if (!empty($fechas_mes_pagos_adm['data'])) {
      foreach ($fechas_mes_pagos_adm['data'] as $key => $value) {
        $idfechas_mes_pagos_adm = $value['idfechas_mes_pagos_administrador'];

        $sql_2 = "SELECT SUM(monto) as monto_total_pago FROM pagos_x_mes_administrador WHERE idfechas_mes_pagos_administrador='$idfechas_mes_pagos_adm' AND estado=1";
        $return_monto_pago = ejecutarConsultaSimpleFila($sql_2);
        if ($return_monto_pago['status'] == false) {  return $return_monto_pago;}

        $monto_total = empty($return_monto_pago['data']) ? 0 : ($retVal_1 = empty($return_monto_pago['data']['monto_total_pago']) ? 0 : floatval($return_monto_pago['data']['monto_total_pago']));

        $detalle_pagos_adm[] = [
          "fecha_inicial" => $value['fecha_inicial'],
          "fecha_final" => $value['fecha_final'],
          "nombre_mes" => $value['nombre_mes'],
          "cant_dias_laborables" => $value['cant_dias_laborables'],
          "monto_x_mes" => $value['monto_x_mes'],

          'return_monto_pago' => $monto_total,
        ];
      }
    }

    return $retorno = ['status'=> true, 'message'=> 'todo oka ps', 'data'=> $detalle_pagos_adm];
  }  

  // TABLA
  public function tabla_obrero($idproyecto, $id_trabajador)  {

    $obrero = Array();

    $total_deposito_obrero = 0;

    $consulta_filtro = "";

    if (empty($id_trabajador) || $id_trabajador == 0) {
      $consulta_filtro = "GROUP by tpp.idtrabajador_por_proyecto";
    } else {
      $consulta_filtro = "AND ra.idtrabajador_por_proyecto = '$id_trabajador'";
    }

    $sql = "SELECT ra.idresumen_q_s_asistencia,ra.idtrabajador_por_proyecto, t.nombres, SUM(ra.pago_quincenal) as pago_quincenal 
		FROM resumen_q_s_asistencia as ra, trabajador_por_proyecto as tpp, trabajador as t 
		WHERE ra.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND tpp.idproyecto ='$idproyecto' 
		AND tpp.idtrabajador=t.idtrabajador AND ra.estado_envio_contador = '1'  AND ra.estado = '1' AND ra.estado_delete='1' $consulta_filtro  ";
    $trabaj_obrero = ejecutarConsultaArray($sql);
    if ($trabaj_obrero['status'] == false) {  return $trabaj_obrero;}

    if (!empty($trabaj_obrero['data'])) {
      
      foreach ($trabaj_obrero['data'] as $key => $value) {

        if ( !empty($value['idtrabajador_por_proyecto']) ) {

          $idtrabajador_por_proyecto = $value['idtrabajador_por_proyecto'];

          $sql_2 = "SELECT SUM(pqso.monto_deposito) AS total_deposito 
					FROM trabajador_por_proyecto AS tpp, resumen_q_s_asistencia AS rqsa, pagos_q_s_obrero AS pqso 
					WHERE tpp.idtrabajador_por_proyecto = rqsa.idtrabajador_por_proyecto AND rqsa.idresumen_q_s_asistencia = pqso.idresumen_q_s_asistencia 
          AND pqso.estado = '1' AND tpp.idtrabajador_por_proyecto = '$idtrabajador_por_proyecto'";
          $total_deposito = ejecutarConsultaSimpleFila($sql_2);
          if ($total_deposito['status'] == false) {  return $total_deposito;}

          $total_deposito_obrero = empty($total_deposito['data']) ? 0 : ($retVal_1 = empty($total_deposito['data']['total_deposito']) ? 0 : floatval($total_deposito['data']['total_deposito']));

          $obrero[] = array(
            "idresumen_q_s_asistencia" => $value['idresumen_q_s_asistencia'],
            "idtrabajador_por_proyecto" => $value['idtrabajador_por_proyecto'],
            "nombres" => $value['nombres'],
            "pago_quincenal" => ($retVal = empty($value['pago_quincenal']) ? 0 : $value['pago_quincenal']),

            "deposito" => $total_deposito_obrero,
          );
        }
      }      
    }

    return $retorno = ['status'=> true, 'message'=> 'todo oka ps', 'data'=> $obrero];

  }

  // DETALLE por cada obrero
  public function r_detalle_x_obrero($idtrabajador_x_proyecto)  {
    $data = [];

    $sql_1 = "SELECT tpp.sueldo_hora, rqsa.idresumen_q_s_asistencia, rqsa.idtrabajador_por_proyecto, rqsa.numero_q_s, rqsa.fecha_q_s_inicio, rqsa.fecha_q_s_fin, 
		rqsa.total_hn, rqsa.total_he, rqsa.total_dias_asistidos, rqsa.sabatical, rqsa.sabatical_manual_1, rqsa.sabatical_manual_2, 
		rqsa.pago_parcial_hn, rqsa.pago_parcial_he, rqsa.adicional_descuento, rqsa.descripcion_descuento, rqsa.pago_quincenal, 
		rqsa.estado_envio_contador, rqsa.recibos_x_honorarios
		FROM resumen_q_s_asistencia AS rqsa, trabajador_por_proyecto AS tpp
		WHERE rqsa.idtrabajador_por_proyecto = '$idtrabajador_x_proyecto' AND rqsa.estado_envio_contador = '1' 
    AND rqsa.idtrabajador_por_proyecto = tpp.idtrabajador_por_proyecto AND rqsa.estado = '1';";
    $q_s = ejecutarConsultaArray($sql_1);
    if ($q_s['status'] == false) {  return $q_s;}

    if (!empty($q_s['data'])) {
      foreach ($q_s['data'] as $key => $q_s) {
        $id = $q_s['idresumen_q_s_asistencia'];

        $sql_2 = "SELECT SUM(monto_deposito) AS deposito  FROM pagos_q_s_obrero WHERE estado = '1' AND idresumen_q_s_asistencia = '$id';";
        $depositos = ejecutarConsultaSimpleFila($sql_2);
        if ($depositos['status'] == false) {  return $depositos;}

        $data[] = [
          'sueldo_hora' => ($retVal_1 = empty($q_s['sueldo_hora']) ? 0 : $q_s['sueldo_hora']),
          'idresumen_q_s_asistencia' => $q_s['idresumen_q_s_asistencia'],
          'idtrabajador_por_proyecto' => $q_s['idtrabajador_por_proyecto'],
          'numero_q_s' => ($retVal_2 = empty($q_s['numero_q_s']) ? 0 : $q_s['numero_q_s']),
          'fecha_q_s_inicio' => $q_s['fecha_q_s_inicio'],
          'fecha_q_s_fin' => $q_s['fecha_q_s_fin'],
          'total_hn' => ($retVal_3 = empty($q_s['total_hn']) ? 0 : $q_s['total_hn']),
          'total_he' => ($retVal_4 = empty($q_s['total_he']) ? 0 : $q_s['total_he']),
          'total_dias_asistidos' => ($retVal_5 = empty($q_s['total_dias_asistidos']) ? 0 : $q_s['total_dias_asistidos']),
          'sabatical' => ($retVal_6 = empty($q_s['sabatical']) ? 0 : $q_s['sabatical']),
          'sabatical_manual_1' => $q_s['sabatical_manual_1'],
          'sabatical_manual_2' => $q_s['sabatical_manual_2'],
          'pago_parcial_hn' => ($retVal_7 = empty($q_s['pago_parcial_hn']) ? 0 : $q_s['pago_parcial_hn']),
          'pago_parcial_he' => ($retVal_8 = empty($q_s['pago_parcial_he']) ? 0 : $q_s['pago_parcial_he']),
          'adicional_descuento' => ($retVal_9 = empty($q_s['adicional_descuento']) ? 0 : $q_s['adicional_descuento']),
          'descripcion_descuento' => $q_s['descripcion_descuento'],
          'pago_quincenal' => ($retVal_10 = empty($q_s['pago_quincenal']) ? 0 : $q_s['pago_quincenal']),
          'estado_envio_contador' => $q_s['estado_envio_contador'],
          'recibos_x_honorarios' => $q_s['recibos_x_honorarios'],

          'deposito' => ($retVal_11 = empty($depositos['data']) ? 0 : ($retVal_12 = empty($depositos['data']['deposito']) ? 0 : $depositos['data']['deposito'])),
        ];
      }
    }

    return $retorno = ['status'=> true, 'message'=> 'todo oka ps', 'data'=> $data];
  }

  // TABLA
  public function tabla_otros_gastos($idproyecto, $fecha_filtro_1, $fecha_filtro_2)  {

    $filtro_fecha = "";   

    if ( !empty($fecha_filtro_1) && !empty($fecha_filtro_2) ) {
      $filtro_fecha = "AND og.fecha_g BETWEEN '$fecha_filtro_1' AND '$fecha_filtro_2'";
    } else {
      if (!empty($fecha_filtro_1)) {
        $filtro_fecha = "AND og.fecha_g = '$fecha_filtro_1'";
      }else{
        if (!empty($fecha_filtro_2)) {
          $filtro_fecha = "AND og.fecha_g = '$fecha_filtro_2'";
        }     
      }      
    }

    $sql = "SELECT og.idotro_gasto, og.idproyecto,  og.fecha_g, og.costo_parcial, og.descripcion, og.comprobante, og.estado
    FROM otro_gasto AS og, proyecto AS p
    WHERE og.idproyecto = p.idproyecto AND og.idproyecto = '$idproyecto' AND og.estado = '1' AND og.estado_delete = '1' $filtro_fecha
		ORDER BY og.fecha_g DESC";
    return ejecutarConsultaArray($sql);
  }

  // TABLA
  public function tabla_sub_contrato($idproyecto, $fecha_filtro_1, $fecha_filtro_2, $id_proveedor)  {

    $list_subcontrato= Array();  $filtro_fecha = "";   $filtro_proveedor = "";

    if (empty($id_proveedor) || $id_proveedor == 0) {
      $filtro_proveedor = "";
    } else {
      $filtro_proveedor = "AND s.idproveedor = '$id_proveedor'";
    }

    if ( !empty($fecha_filtro_1) && !empty($fecha_filtro_2) ) {
      $filtro_fecha = "AND s.fecha_subcontrato BETWEEN '$fecha_filtro_1' AND '$fecha_filtro_2'";
    } else {
      if (!empty($fecha_filtro_1)) {
        $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_filtro_1'";
      }else{
        if (!empty($fecha_filtro_2)) {
          $filtro_fecha = "AND s.fecha_subcontrato = '$fecha_filtro_2'";
        }     
      }      
    }

    $sql = "SELECT s.idsubcontrato, s.idproyecto, s.idproveedor, s.tipo_comprobante, s.numero_comprobante, s.forma_de_pago, 
    s.fecha_subcontrato, s.val_igv, s.subtotal, s.igv, s.costo_parcial, s.descripcion, s.glosa, s.comprobante, p.razon_social, p.tipo_documento, p.ruc
    FROM subcontrato AS s, proveedor as p
    WHERE s.idproveedor = p.idproveedor and s.estado = '1' AND s.estado_delete = '1' AND s.idproyecto='$idproyecto' $filtro_proveedor $filtro_fecha 
		ORDER BY s.fecha_subcontrato DESC";
    $sub_contrato = ejecutarConsultaArray($sql);
    if ($sub_contrato['status'] == false) {  return $sub_contrato;}

    if (!empty($sub_contrato['data'])) {			
			foreach ($sub_contrato['data'] as $key => $value) {

				$id=$value['idsubcontrato'];

				$sql_2="SELECT SUM(monto) as total_deposito FROM pago_subcontrato WHERE idsubcontrato='$id' AND estado='1' AND  estado_delete='1';";
				$total_deposito= ejecutarConsultaSimpleFila($sql_2);
        if ($total_deposito['status'] == false) {  return $total_deposito;}

				$list_subcontrato[]= array(

					"idsubcontrato"      => $value['idsubcontrato'],
					"idproyecto"     	 => $value['idproyecto'],
					"idproveedor"        => $value['idproveedor'],
					"tipo_comprobante"   => $value['tipo_comprobante'],
					"forma_de_pago"      => $value['forma_de_pago'],
					"numero_comprobante" => $value['numero_comprobante'],
					"fecha_subcontrato"  => $value['fecha_subcontrato'],
					"subtotal"           => empty($value['subtotal']) ? 0 : $value['subtotal']  ,
					"igv"                => empty($value['igv']) ? 0 : $value['igv']  ,
					"costo_parcial"      => empty($value['costo_parcial']) ? 0 : $value['costo_parcial']  ,
					"descripcion"        => $value['descripcion'],
					"comprobante"        => $value['comprobante'],
          "razon_social"        => $value['razon_social'],

					"total_deposito"     => ($retVal_2 = empty($total_deposito['data']) ? 0 : ($retVal_3 = empty($total_deposito['data']['total_deposito']) ? 0 : $total_deposito['data']['total_deposito'])),

				);	
				
			}
		}

    return $retorno = ['status'=> true, 'message'=> 'todo oka ps', 'data'=>$list_subcontrato];
    
  }

  // SELECT2
  public function select_proveedores()  {
    $sql = "SELECT idproveedor, razon_social, ruc FROM proveedor WHERE estado = '1' AND estado_delete = '1'";
    return ejecutarConsulta($sql);
  }

  // SELECT2
  public function selecct_trabajadores($idproyecto)  {
    $sql = "SELECT tpp.idtrabajador_por_proyecto, t.nombres, t.numero_documento FROM trabajador_por_proyecto as tpp, trabajador as t
		WHERE tpp.idtrabajador= t.idtrabajador AND tpp.idproyecto='$idproyecto' AND tpp.estado = '1' AND tpp.estado_delete = '1'";
    return ejecutarConsulta($sql);
  }
}
?>

