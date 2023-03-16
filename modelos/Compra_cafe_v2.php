<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Compra_cafe_v2
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  // ::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C O M P R A  ::::::::::::::::::::::::::::::::::::::::: 

  //Implementamos un método para insertar registros
  
  public function insertar(  $idcliente, $ruc_dni_cliente, $fecha_compra, $tipo_comprobante, $numero_comprobante, $descripcion, 
  $metodo_pago, $monto_pago_compra, $fecha_proximo_pago, $subtotal_compra, $val_igv, $igv_compra, $total_compra, $tipo_gravada, $tipo_grano, $unidad_medida, $peso_bruto, 
  $sacos, $dcto_humedad, $dcto_rendimiento, $dcto_segunda, $dcto_cascara, $dcto_taza, $dcto_tara, 
  $peso_neto,  $quintal_neto, 
  $precio_sin_igv, $precio_igv, $precio_con_igv, $descuento, $subtotal_producto ) {   

    $sql_2 = "SELECT p.nombres as cliente, p.tipo_documento, p.numero_documento, tp.nombre as tipo_persona, cg.idcompra_grano, cg.idpersona, cg.fecha_compra, cg.metodo_pago, cg.tipo_comprobante, cg.numero_comprobante, cg.total_compra, cg.descripcion, cg.estado, cg.estado_delete
    FROM compra_grano as cg, persona as p, tipo_persona as tp
    WHERE cg.idpersona = p.idpersona AND p.idtipo_persona = tp.idtipo_persona 
    AND p.numero_documento ='$ruc_dni_cliente' AND cg.tipo_comprobante ='$tipo_comprobante' AND cg.numero_comprobante = '$numero_comprobante'";
    $compra_existe = ejecutarConsultaArray($sql_2); if ($compra_existe['status'] == false) { return  $compra_existe;}

    if (empty($compra_existe['data']) || $tipo_comprobante == 'Ninguno') {
      // creamos una compra
      $sql_3 = "INSERT INTO compra_grano( idpersona, fecha_compra,  tipo_comprobante, numero_comprobante, val_igv, subtotal_compra, igv_compra, total_compra, tipo_gravada, descripcion, metodo_pago, fecha_proximo_pago, user_created ) 
      VALUES ('$idcliente','$fecha_compra','$tipo_comprobante','$numero_comprobante','$val_igv','$subtotal_compra','$igv_compra','$total_compra', '$tipo_gravada', '$descripcion', '$metodo_pago', '$fecha_proximo_pago', '".$_SESSION['idusuario']."')";
      $id_compra = ejecutarConsulta_retornarID($sql_3); if ($id_compra['status'] == false) { return  $id_compra;}
      $id = $id_compra['data']; 

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_grano','".$id_compra['data']."','Agregar compra cafe','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

      //add update table autoincrement_comprobante - numero de comprobante de pago
      if ($tipo_comprobante == 'Boleta') {
        $sql_nro = "UPDATE autoincrement_comprobante SET 	compra_cafe_b = 	compra_cafe_b + '1' WHERE idautoincrement_comprobante = '1'";
        $nro_comprobante = ejecutarConsulta($sql_nro); if ($nro_comprobante['status'] == false) { return  $nro_comprobante;}
      } else if ($tipo_comprobante == 'Factura'){
        $sql_nro = "UPDATE autoincrement_comprobante SET 	compra_cafe_f = 	compra_cafe_f + '1' WHERE idautoincrement_comprobante = '1'";
        $nro_comprobante = ejecutarConsulta($sql_nro); if ($nro_comprobante['status'] == false) { return  $nro_comprobante;}
      } else if ($tipo_comprobante == 'Nota de venta'){
        $sql_nro = "UPDATE autoincrement_comprobante SET 	compra_cafe_nv = 	compra_cafe_nv + '1' WHERE idautoincrement_comprobante = '1'";
        $nro_comprobante = ejecutarConsulta($sql_nro); if ($nro_comprobante['status'] == false) { return  $nro_comprobante;}
      }       

      // creamos un pago de compra
      $insert_pago = "INSERT INTO pago_compra_grano( idcompra_grano, forma_pago, fecha_pago, monto, descripcion, comprobante, user_created) 
      VALUES ('$id','EFECTIVO','$fecha_compra','$monto_pago_compra', '', '', '".$_SESSION['idusuario']."')";
      $new_pago = ejecutarConsulta_retornarID($insert_pago); if ($new_pago['status'] == false) { return  $new_pago;}

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_compra_grano','".$new_pago['data']."','Agregar pago cafe','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

      $det_compra_new = "";

      if ( !empty($id_compra['data']) ) {                     

        $sql_detalle = "INSERT INTO detalle_compra_grano( idcompra_grano, tipo_grano, unidad_medida, peso_bruto, 
        sacos, dcto_humedad, dcto_rendimiento, dcto_segunda, dcto_cascara, dcto_taza, dcto_tara, 
        peso_neto, quintal_neto,
        precio_sin_igv,	precio_igv,	precio_con_igv, descuento_adicional, subtotal) 
        VALUES ('$id','$tipo_grano','$unidad_medida','$peso_bruto',
        '$sacos','$dcto_humedad','$dcto_rendimiento','$dcto_segunda','$dcto_cascara','$dcto_taza','$dcto_tara',
        '$peso_neto', '$quintal_neto',
        '$precio_sin_igv','$precio_igv','$precio_con_igv','$descuento', '$subtotal_producto')";
        
        $det_compra_new =  ejecutarConsulta_retornarID($sql_detalle); if ($det_compra_new['status'] == false) { return  $det_compra_new;}

        //add registro en nuestra bitacora.
        $sql_bit_d = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('detalle_compra_grano','".$det_compra_new['data']."','Agregar detalle compra cafe','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql_bit_d); if ( $bitacora['status'] == false) {return $bitacora; } 
        
      }
      return $det_compra_new;

    } else {

      $info_repetida = ''; 

      foreach ($compra_existe['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b class="font-size-18px text-danger">'.$value['tipo_comprobante'].': </b> <span class="font-size-18px text-danger">'.$value['numero_comprobante'].'</span><br>
          <b>Razón Social: </b>'.$value['cliente'].'<br>
          <b>'.$value['tipo_documento'].': </b>'.$value['numero_documento'].'<br>          
          <b>Fecha: </b>'.format_d_m_a($value['fecha_compra']).'<br>
          <b>Total: </b>'.number_format($value['total_compra'], 2, '.', ',').'<br>
          <b>Tipo Persona: </b>'.$value['tipo_persona'].'<br>
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b> 
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );      
    }      
  }

  //Implementamos un método para editar registros
  public function editar( $idcompra_grano, $idcliente, $ruc_dni_cliente, $fecha_compra, $tipo_comprobante, $numero_comprobante, $descripcion, 
  $metodo_pago, $monto_pago_compra, $fecha_proximo_pago, $subtotal_compra, $val_igv, $igv_compra, $total_compra, $tipo_gravada, $tipo_grano, $unidad_medida, $peso_bruto, 
  $sacos, $dcto_humedad, $dcto_rendimiento, $dcto_segunda, $dcto_cascara, $dcto_taza, $dcto_tara, 
  $peso_neto,  $quintal_neto, 
  $precio_sin_igv, $precio_igv, $precio_con_igv, $descuento, $subtotal_producto ) {

    if ( !empty($idcompra_grano) ) {
      //Eliminamos todos detalles de comprras anteriores
      $sqldel = "DELETE FROM detalle_compra_grano WHERE idcompra_grano='$idcompra_grano';";
      $delete_compra = ejecutarConsulta($sqldel); if ($delete_compra['status'] == false) { return $delete_compra; }

      // actualizamos la compra
      $sql = "UPDATE compra_grano SET idpersona='$idcliente',fecha_compra='$fecha_compra', tipo_comprobante='$tipo_comprobante',
      numero_comprobante='$numero_comprobante',val_igv='$val_igv',subtotal_compra='$subtotal_compra', igv_compra='$igv_compra',
      total_compra='$total_compra', tipo_gravada='$tipo_gravada', descripcion='$descripcion', metodo_pago='$metodo_pago', 
      fecha_proximo_pago='$fecha_proximo_pago', user_updated= '" . $_SESSION['idusuario'] . "' WHERE idcompra_grano = '$idcompra_grano'";
      $update_compra = ejecutarConsulta($sql); if ($update_compra['status'] == false) { return $update_compra; }

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_grano','$idcompra_grano','Editar compra cafe','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }

      $det_compra_new = "";       

      $sql_detalle = "INSERT INTO detalle_compra_grano( idcompra_grano, tipo_grano, unidad_medida, peso_bruto, 
        sacos, dcto_humedad, dcto_rendimiento, dcto_segunda, dcto_cascara, dcto_taza, dcto_tara, 
        peso_neto, quintal_neto,
        precio_sin_igv,	precio_igv,	precio_con_igv, descuento_adicional, subtotal) 
        VALUES ('$idcompra_grano','$tipo_grano','$unidad_medida','$peso_bruto',
        '$sacos','$dcto_humedad','$dcto_rendimiento','$dcto_segunda','$dcto_cascara','$dcto_taza','$dcto_tara',
        '$peso_neto', '$quintal_neto',
        '$precio_sin_igv','$precio_igv','$precio_con_igv','$descuento', '$subtotal_producto')";
      
      $det_compra_new =  ejecutarConsulta_retornarID($sql_detalle); if ($det_compra_new['status'] == false) { return  $det_compra_new;}

      //add registro en nuestra bitacora.
      $sql_bit_d = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('detalle_compra_grano','".$det_compra_new['data']."','Agregar Detalle compra cafe','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit_d); if ( $bitacora['status'] == false) {return $bitacora; } 

      return $det_compra_new;
    } else { 
      return $retorno = ['status'=>false, 'mesage'=>'no hay nada', 'data'=>'sin data', ]; 
    }
  }

  public function mostrar_compra_para_editar($idcompra_grano) {

    $sql = "SELECT p.nombres as cliente, p.es_socio, p.tipo_documento, p.numero_documento, p.celular, p.direccion, p.correo, tp.nombre as tipo_persona, 
    cg.idcompra_grano, cg.idpersona, cg.fecha_compra,  cg.tipo_comprobante, cg.numero_comprobante, cg.val_igv, 
    cg.subtotal_compra, cg.igv_compra, cg.total_compra, cg.tipo_gravada, cg.descripcion, cg.metodo_pago, cg.fecha_proximo_pago
    FROM compra_grano as cg, persona  p, tipo_persona as tp
    WHERE cg.idpersona = p.idpersona AND p.idtipo_persona = tp.idtipo_persona AND idcompra_grano = '$idcompra_grano' ;";
    $compra = ejecutarConsultaSimpleFila($sql); if ($compra['status'] == false) { return $compra; }

    $sql_2 = "SELECT iddetalle_compra_grano, idcompra_grano, tipo_grano, unidad_medida, peso_bruto, 
    sacos, dcto_humedad, dcto_rendimiento, dcto_segunda, dcto_cascara, dcto_taza, dcto_tara,
    peso_neto, quintal_neto,
    precio_sin_igv, precio_igv, precio_con_igv, descuento_adicional, subtotal
    FROM detalle_compra_grano 
    WHERE estado = '1' AND estado_delete = '1' AND idcompra_grano = '$idcompra_grano'; ";
    $producto = ejecutarConsultaSimpleFila($sql_2); if ($producto['status'] == false) { return $producto;  }   

    $results = [
      "idcompra_grano"    => $compra['data']['idcompra_grano'],      
      "idpersona"         => $compra['data']['idpersona'],
      "fecha_compra"      => $compra['data']['fecha_compra'],      
      "tipo_comprobante"  => $compra['data']['tipo_comprobante'],
      "numero_comprobante"=> $compra['data']['numero_comprobante'],
      "val_igv"           => $compra['data']['val_igv'],
      "subtotal_compra"   => $compra['data']['subtotal_compra'],
      "igv_compra"        => $compra['data']['igv_compra'],
      "total_compra"      => $compra['data']['total_compra'],
      "tipo_gravada"      => $compra['data']['tipo_gravada'],
      "descripcion"       => $compra['data']['descripcion'],
      "metodo_pago"       => $compra['data']['metodo_pago'],
      "fecha_proximo_pago"=> $compra['data']['fecha_proximo_pago'],

      "cliente"           => $compra['data']['cliente'],
      "es_socio"          => $compra['data']['es_socio'],
      "tipo_documento"    => $compra['data']['tipo_documento'],
      "numero_documento"  => $compra['data']['numero_documento'],
      "celular"           => $compra['data']['celular'],
      "direccion"         => $compra['data']['direccion'],
      "correo"            => $compra['data']['correo'],
      "tipo_persona"      => $compra['data']['tipo_persona'],

      "detalle_compra"    => $producto['data'],


    ];

    return $retorno = ["status" => true, "message" => 'todo oka', "data" => $results] ;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idcompra_grano) {
    $sql = "UPDATE compra_grano SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idcompra_grano='$idcompra_grano'";
		$desactivar= ejecutarConsulta($sql); if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_grano','".$idcompra_grano."','Compra desactivada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function activar($idcompra_grano) {
    $sql = "UPDATE compra_grano SET estado='1' WHERE idcompra_grano='$idcompra_grano'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar($idcompra_grano) {
    $sql = "UPDATE compra_grano SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idcompra_grano='$idcompra_grano'";
		$eliminar =  ejecutarConsulta($sql);if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_grano','$idcompra_grano','Compra Eliminada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementar un método para listar los registros
  public function tbla_principal( $fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND cg.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND cg.fecha_compra = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND cg.fecha_compra = '$fecha_2'";
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND cg.idpersona = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else {
      $filtro_comprobante = "AND cg.tipo_comprobante = '$comprobante'"; 
    } 

    $data = Array();    

    $sql = "SELECT p.nombres as cliente, p.es_socio, p.tipo_documento, p.numero_documento, tp.nombre as tipo_persona, cg.idcompra_grano, cg.idpersona, cg.fecha_compra, 
    cg.metodo_pago, cg.tipo_comprobante, cg.numero_comprobante, cg.total_compra, cg.descripcion, cg.estado
    FROM compra_grano as cg, persona  p, tipo_persona as tp
    WHERE cg.idpersona = p.idpersona AND p.idtipo_persona = tp.idtipo_persona AND cg.estado = '1' AND cg.estado_delete = '1'
     $filtro_proveedor $filtro_comprobante $filtro_fecha
		ORDER BY cg.fecha_compra DESC ";
    $compra = ejecutarConsultaArray($sql); if ($compra['status'] == false) { return $compra; }    

    foreach ($compra['data'] as $key => $value) {      
      $id = $value['idcompra_grano'];
      $sql_3 ="SELECT SUM(monto) as deposito FROM pago_compra_grano WHERE idcompra_grano = '$id' AND estado ='1' AND estado_delete = '1'";
      $pagos = ejecutarConsultaSimpleFila($sql_3); if ($pagos['status'] == false) { return $pagos; }

      $data[] = [
        'idcompra_grano'  => $value['idcompra_grano'],
        'idcliente'       => $value['idpersona'],
        'cliente'         => $value['cliente'],
        'tipo_documento'  => $value['tipo_documento'],
        'numero_documento'=> $value['numero_documento'],
        'tipo_persona'    => $value['tipo_persona'],
        'es_socio'        => ($value['es_socio'] ? 'SOCIO' : 'NO SOCIO') ,
        'fecha_compra'    => $value['fecha_compra'],
        'tipo_comprobante'=> $value['tipo_comprobante'],
        'numero_comprobante' => $value['numero_comprobante'],
        'descripcion'     => $value['descripcion'],
        'total_compra'    => $value['total_compra'],
        'metodo_pago'     => $value['metodo_pago'],
        'estado'          => $value['estado'],
        'total_pago'      => (empty($pagos['data']) ? 0 : (empty($pagos['data']['deposito']) ? 0 : floatval($pagos['data']['deposito']) ) ),
      ];
    }

    return $retorno = ['status' => true, 'message' => 'todo ok pe.', 'data' =>$data, 'affected_rows' =>$compra['affected_rows'],  ] ;
  }

  //pago servicio
  public function pago_servicio($idcompra_proyecto) {

    $sql = "SELECT SUM(monto) as total_pago_compras
		FROM pago_compras 
		WHERE idcompra_proyecto='$idcompra_proyecto' AND estado='1' AND estado_delete='1'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros x proveedor
  public function tabla_compra_x_cliente() {
    // $idproyecto=2;
    $sql = "SELECT p.idpersona, p.nombres, p.tipo_documento, p.numero_documento, p.celular, COUNT(idcompra_grano) as cantidad, SUM(total_compra) as total_compra
    FROM compra_grano AS cg, persona AS p
    WHERE cg.idpersona = p.idpersona AND cg.estado AND cg.estado_delete GROUP BY cg.idpersona ORDER BY p.nombres ASC;";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros x proveedor
  public function listar_detalle_comprax_provee($idpersona) {

    $sql = "SELECT * FROM compra_grano WHERE idpersona='$idpersona' AND estado = '1' AND estado_delete = '1'";

    return ejecutarConsulta($sql);
  }

  //mostrar detalles uno a uno de la factura
  public function ver_compra($idcompra_proyecto) {

    $sql = "SELECT cpp.idcompra_proyecto as idcompra_proyecto, 
		cpp.idproyecto , 
		cpp.idproveedor , 
		p.razon_social , p.tipo_documento, p.ruc, p.direccion, p.telefono, 
		cpp.fecha_compra , 
		cpp.tipo_comprobante , 
		cpp.serie_comprobante , 
    cpp.val_igv,
		cpp.descripcion , 
    cpp.glosa,
		cpp.subtotal, 
		cpp.igv , 
		cpp.total ,
    cpp.tipo_gravada ,
		cpp.estado 
		FROM compra_por_proyecto as cpp, proveedor as p 
		WHERE idcompra_proyecto='$idcompra_proyecto'  AND cpp.idproveedor = p.idproveedor";

    return ejecutarConsultaSimpleFila($sql);
  }

  //lismatamos los detalles
  public function ver_detalle_compra($id_compra) {

    $sql = "SELECT 
		dp.idproducto as idproducto,
		dp.ficha_tecnica_producto  as ficha_tecnica_old, p.ficha_tecnica as ficha_tecnica_new,
		dp.cantidad ,
    dp.unidad_medida, dp.color,
		dp.precio_sin_igv ,
    dp.igv ,
    dp.precio_con_igv ,
		dp.descuento ,
    dp.subtotal ,
		p.nombre as nombre, p.imagen, um.abreviacion
		FROM detalle_compra  dp, producto as p, unidad_medida as um
		WHERE p.idunidad_medida = um.idunidad_medida AND idcompra_proyecto='$id_compra' AND  dp.idproducto=p.idproducto";

    return ejecutarConsulta($sql);
  }

  // ::::::::::::::::::::::::::::::::::::::::: S E C C I O N   P A G O S ::::::::::::::::::::::::::::::::::::::::: 

  public function crear_pago_compra($idcompra_grano_p, $forma_pago_p, $fecha_pago_p, $monto_p, $descripcion_p, $comprobante_pago)  {
    $sql_1 = "INSERT INTO pago_compra_grano(idcompra_grano, forma_pago, fecha_pago, monto, descripcion, comprobante)
    VALUES ('$idcompra_grano_p', '$forma_pago_p', '$fecha_pago_p', '$monto_p', '$descripcion_p', '$comprobante_pago')";
    return ejecutarConsulta($sql_1);
  }

  public function editar_pago_compra($idpago_compra_grano_p, $idcompra_grano_p, $forma_pago_p, $fecha_pago_p, $monto_p, $descripcion_p, $comprobante_pago)  {
    $sql_1 = "UPDATE pago_compra_grano SET idcompra_grano='$idcompra_grano_p', forma_pago='$forma_pago_p',
    fecha_pago='$fecha_pago_p', monto='$monto_p', descripcion='$descripcion_p', comprobante='$comprobante_pago' 
    WHERE idpago_compra_grano ='$idpago_compra_grano_p'; ";
    return ejecutarConsulta($sql_1);
  }

  public function tabla_pago_compras($idcompra_grano)  {
    $sql_1 = "SELECT pcg.idpago_compra_grano, pcg.idcompra_grano, pcg.forma_pago, pcg.fecha_pago, pcg.monto, pcg.descripcion, pcg.comprobante, pcg.estado,
    p.nombres as cliente, p.tipo_documento, p.numero_documento
    FROM pago_compra_grano as pcg, compra_grano as cg, persona as p
    WHERE pcg.idcompra_grano = cg.idcompra_grano AND cg.idpersona = p.idpersona and pcg.idcompra_grano = '$idcompra_grano' AND pcg.estado = '1' AND pcg.estado_delete = '1' ORDER BY pcg.fecha_pago DESC";
    return ejecutarConsulta($sql_1);
  }

  //Implementamos un método para desactivar categorías
  public function papelera_pago_compra($idpago_compra_grano) {
    $sql = "UPDATE pago_compra_grano SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idpago_compra_grano='$idpago_compra_grano'";
		$desactivar= ejecutarConsulta($sql); if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_compra_grano','".$idpago_compra_grano."','Pago compra papelera','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
		
		return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function eliminar_pago_compra($idpago_compra_grano) {
    $sql = "UPDATE pago_compra_grano SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idpago_compra_grano='$idpago_compra_grano'";
		$eliminar =  ejecutarConsulta($sql);if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('pago_compra_grano','$idpago_compra_grano','Pago compra Eliminada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementamos un método para activar categorías
  public function mostrar_editar_pago($idpago_compra_grano) {
    $sql = "SELECT * FROM pago_compra_grano WHERE idpago_compra_grano = '$idpago_compra_grano';";
		return ejecutarConsultaSimpleFila($sql);
  }

  //Implementamos un método para activar categorías
  public function obtener_doc_pago_compra($idpago_compra_grano) {
    $sql = "SELECT idpago_compra_grano, comprobante FROM  pago_compra_grano WHERE idpago_compra_grano ='$idpago_compra_grano'; ";
		$doc =  ejecutarConsultaSimpleFila($sql);		
		return $doc;
  }

  // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  :::::::::::::::::::::::::: 


  // ::::::::::::::::::::::::::::::::::::::::: S I N C R O N I Z A R  ::::::::::::::::::::::::::::::::::::::::: 
  public function sincronizar_comprobante() {
    $sql = "SELECT idcompra_proyecto, comprobante FROM compra_por_proyecto WHERE comprobante != 'null' AND comprobante != '';";
    $comprobantes = ejecutarConsultaArray($sql);
    if ($comprobantes == false) {  return $comprobantes; }

    foreach ($comprobantes['data'] as $key => $value) {
      $id_compra = $value['idcompra_proyecto']; $comprobante = $value['comprobante'];
      $sql2 = "INSERT INTO factura_compra_insumo ( idcompra_proyecto, comprobante ) VALUES ( '$id_compra', '$comprobante')";
      $factura_compra = ejecutarConsulta($sql2);
      if ($factura_compra == false) {  return $factura_compra; }
    }

    $sql3 = "SELECT	idcompra_proyecto, comprobante FROM factura_compra_insumo ;";
    $factura_compras = ejecutarConsultaArray($sql3);
    if ($factura_compras == false) {  return $factura_compras; }

    return $retorno = ['status'=>true, 'message'=>'todo oka', 'data'=>['comprobante'=>$comprobantes['data'],'factura_compras'=>$factura_compras['data'],], ];
  }  
}

?>
