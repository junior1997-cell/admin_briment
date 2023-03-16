<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Compra_producto
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  // ::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C O M P R A  ::::::::::::::::::::::::::::::::::::::::: 
  //Implementamos un método para insertar registros
  public function insertar( $idproveedor, $num_doc, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
  $total_compra, $subtotal_compra, $igv_compra,  $idproducto, $unidad_medida, $categoria, $cantidad, $precio_sin_igv, $precio_igv, 
  $precio_con_igv,$precio_venta, $descuento, $tipo_gravada) {    

    // buscamos al si la FACTURA existe
    $sql_2 = "SELECT  cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante, cp.igv, cp.total, cp.estado, cp.estado_delete, 
    p.nombres, p.numero_documento, p.tipo_documento
    FROM compra_producto as cp, persona as p 
    WHERE cp.idpersona = p.idpersona AND cp.tipo_comprobante ='$tipo_comprobante' AND cp.serie_comprobante = '$serie_comprobante' AND p.numero_documento='$num_doc'";
    $compra_existe = ejecutarConsultaArray($sql_2); if ($compra_existe['status'] == false) { return  $compra_existe;}

    if (empty($compra_existe['data']) || $tipo_comprobante == 'Ninguno') {
     
      $sql_3 = "INSERT INTO compra_producto(idpersona, fecha_compra, tipo_comprobante, serie_comprobante, val_igv, subtotal, igv, total,tipo_gravada, descripcion) 
      VALUES ('$idproveedor','$fecha_compra','$tipo_comprobante','$serie_comprobante','$val_igv','$subtotal_compra','$igv_compra','$total_compra','$tipo_gravada','$descripcion')";
      $idventanew = ejecutarConsulta_retornarID($sql_3); if ($idventanew['status'] == false) { return  $idventanew;}

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_producto','".$idventanew['data']."','Nueva compra','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

      $num_elementos = 0;
      $compra_new = "";

      if ( !empty($idventanew['data']) ) {
      
        while ($num_elementos < count($idproducto)) {

          $id = $idventanew['data'];
          $subtotal_producto = (floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos])) - $descuento[$num_elementos];

          $sql_detalle = "INSERT INTO detalle_compra_producto(idcompra_producto, idproducto, unidad_medida, categoria, cantidad, precio_sin_igv, igv, 
          precio_con_igv,precio_venta, descuento, subtotal, user_created) 
          VALUES ('$id','$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]',  '$categoria[$num_elementos]', '$cantidad[$num_elementos]', 
          '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]','$precio_venta[$num_elementos]', '$descuento[$num_elementos]', 
          '$subtotal_producto','" . $_SESSION['idusuario'] . "')";

          $compra_new =  ejecutarConsulta_retornarID($sql_detalle); if ($compra_new['status'] == false) { return  $compra_new;}

          //add update table producto el stock
          $sql_producto = "UPDATE producto SET stock = stock + '$cantidad[$num_elementos]', precio_unitario='$precio_venta[$num_elementos]', precio_compra_actual='$precio_con_igv[$num_elementos]' WHERE idproducto = '$idproducto[$num_elementos]'";
          $producto = ejecutarConsulta($sql_producto); if ($producto['status'] == false) { return  $producto;}

          //add registro en nuestra bitacora.
          $sql_bit_d = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('detalle_compra_producto','".$compra_new['data']."','Detalle compra','" . $_SESSION['idusuario'] . "')";
          $bitacora = ejecutarConsulta($sql_bit_d); if ( $bitacora['status'] == false) {return $bitacora; } 

          $num_elementos = $num_elementos + 1;
        }
      }
      return $compra_new;

    } else {

      $info_repetida = ''; 

      foreach ($compra_existe['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b class="font-size-18px text-danger">'.$value['tipo_comprobante'].': </b> <span class="font-size-18px text-danger">'.$value['serie_comprobante'].'</span><br>
          <b>Razón Social: </b>'.$value['nombres'].'<br>
          <b>'.$value['tipo_documento'].': </b>'.$value['numero_documento'].'<br>          
          <b>Fecha: </b>'.format_d_m_a($value['fecha_compra']).'<br>
          <b>Total: </b>S/ '.number_format($value['total'], 2, '.', ',').'<br>
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b> 
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );      
    }      
  }

  //Implementamos un método para editar registros
  public function editar( $idcompra_producto, $idproveedor, $num_doc, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, $total_venta, 
  $subtotal_compra, $igv_compra,  $idproducto, $unidad_medida, $categoria, $cantidad, $precio_sin_igv, $precio_igv,  $precio_con_igv,$precio_venta, $descuento, $tipo_gravada) {

    if ( !empty($idcompra_producto) ) {
      //Eliminamos todos los permisos asignados para volverlos a registrar
      $sqldel = "DELETE FROM detalle_compra_producto WHERE idcompra_producto='$idcompra_producto';";
      $delete_compra = ejecutarConsulta($sqldel);
      if ($delete_compra['status'] == false) { return $delete_compra; }

      $sql = "UPDATE compra_producto SET idpersona='$idproveedor',fecha_compra='$fecha_compra',tipo_comprobante='$tipo_comprobante',
      serie_comprobante='$serie_comprobante',val_igv='$val_igv',subtotal='$subtotal_compra',igv='$igv_compra',
      total='$total_venta',tipo_gravada='$tipo_gravada',descripcion='$descripcion' 
      WHERE idcompra_producto = '$idcompra_producto'";

      $update_compra = ejecutarConsulta($sql); if ($update_compra['status'] == false) { return $update_compra; }

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_producto','$idcompra_producto','Editar compra','" . $_SESSION['idusuario'] . "')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }

      $num_elementos = 0; $detalle_compra = "";

      while ($num_elementos < count($idproducto)) {

        $subtotal_producto = (floatval($cantidad[$num_elementos]) * floatval($precio_con_igv[$num_elementos])) - $descuento[$num_elementos];

          $sql_detalle = "INSERT INTO detalle_compra_producto(idcompra_producto, idproducto, unidad_medida, categoria, cantidad, precio_sin_igv, igv, 
          precio_con_igv,precio_venta, descuento, subtotal, user_created) 
          VALUES ('$idcompra_producto','$idproducto[$num_elementos]', '$unidad_medida[$num_elementos]',  '$categoria[$num_elementos]', '$cantidad[$num_elementos]', 
          '$precio_sin_igv[$num_elementos]', '$precio_igv[$num_elementos]', '$precio_con_igv[$num_elementos]','$precio_venta[$num_elementos]', '$descuento[$num_elementos]', 
          '$subtotal_producto','" . $_SESSION['idusuario'] . "')";

          $detalle_compra =  ejecutarConsulta_retornarID($sql_detalle); if ($detalle_compra['status'] == false) { return  $detalle_compra;}

          //add update table producto el stock
          $sql_producto = "UPDATE producto SET stock = stock + '$cantidad[$num_elementos]', precio_unitario='$precio_venta[$num_elementos]' WHERE idproducto = '$idproducto[$num_elementos]'";
          $producto = ejecutarConsulta($sql_producto); if ($producto['status'] == false) { return  $producto;}

        //add registro en nuestra bitacora.
        $sql_bit_d = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('detalle_compra_producto','".$detalle_compra['data']."','Detalle editado compra','" . $_SESSION['idusuario'] . "')";
        $bitacora = ejecutarConsulta($sql_bit_d); if ( $bitacora['status'] == false) {return $bitacora; } 

        $num_elementos = $num_elementos + 1;
      }
      return $detalle_compra; 
    } else { 
      return $retorno = ['status'=>false, 'mesage'=>'no hay nada', 'data'=>'sin data', ]; 
    }
  }

  public function mostrar_compra_para_editar($idcompra_producto) {

    $sql = "SELECT  cp.idcompra_producto,cp.fecha_compra,cp.idpersona, cp.tipo_comprobante, cp.serie_comprobante, cp.val_igv, cp.subtotal, cp.igv, cp.total, cp.tipo_gravada, 
    cp.descripcion, p.nombres, p.tipo_documento, p.numero_documento, p.celular, p.correo, p.direccion,p.correo
    FROM compra_producto as cp, persona as p 
    WHERE cp.idpersona = p.idpersona AND cp.idcompra_producto ='$idcompra_producto';";

    $compra=  ejecutarConsultaSimpleFila($sql); if ($compra['status'] == false) {return $compra; }

    $sql = "SELECT dcp.idproducto, dcp.unidad_medida, dcp.categoria, dcp.cantidad, dcp.precio_sin_igv, dcp.igv, dcp.precio_con_igv, 
    dcp.precio_venta, dcp.descuento, dcp.subtotal, p.nombre, p.imagen, c.nombre as categoria, um.abreviatura
    FROM detalle_compra_producto as dcp, producto as p, categoria_producto as c, unidad_medida as um
    WHERE dcp.idproducto =p.idproducto AND p.idcategoria_producto = c.idcategoria_producto AND p.idunidad_medida = um.idunidad_medida AND dcp.idcompra_producto ='$idcompra_producto';";

    $detalle = ejecutarConsultaArray($sql);    if ($detalle['status'] == false) {return $detalle; }

    return $datos= Array('status' => true, 'data' => ['compra' => $compra['data'], 'detalle' => $detalle['data']], 'message' => 'Todo ok' );

  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idcompra_producto) {
    // var_dump($idcompra_producto);die();
    $sql = "UPDATE compra_producto SET estado='0',user_trash= '" . $_SESSION['idusuario'] . "' WHERE idcompra_producto='$idcompra_producto'";
		$desactivar= ejecutarConsulta($sql);if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_producto','".$idcompra_producto."','Compra desactivada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
    
    // buscamos las cantidades
    $sql_restaurar = "SELECT idproducto, cantidad FROM detalle_compra_producto WHERE idcompra_producto = '$idcompra_producto';";
    $restaurar_stok =  ejecutarConsultaArray($sql_restaurar); if ( $restaurar_stok['status'] == false) {return $restaurar_stok; }
    // actualizamos el stock
    foreach ($restaurar_stok['data'] as $key => $value) {      
      $update_producto = "UPDATE producto SET stock = stock - '".$value['cantidad']."' WHERE idproducto = '".$value['idproducto']."';";
      $producto = ejecutarConsulta($update_producto); if ($producto['status'] == false) { return  $producto;}
    }	
		
		return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function eliminar($idcompra_producto) {
    $sql = "UPDATE compra_producto SET estado_delete='0',user_delete= '" . $_SESSION['idusuario'] . "' WHERE idcompra_producto='$idcompra_producto'";
		$eliminar =  ejecutarConsulta($sql);if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('compra_producto','$idcompra_producto','Compra Eliminada','" . $_SESSION['idusuario'] . "')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

    // buscamos las cantidades
    $sql_restaurar = "SELECT idproducto, cantidad FROM detalle_compra_producto WHERE idcompra_producto = '$idcompra_producto';";
    $restaurar_stok =  ejecutarConsultaArray($sql_restaurar); if ( $restaurar_stok['status'] == false) {return $restaurar_stok; }
    // actualizamos el stock
    foreach ($restaurar_stok['data'] as $key => $value) {      
      $update_producto = "UPDATE producto SET stock = stock - '".$value['cantidad']."' WHERE idproducto = '".$value['idproducto']."';";
      $producto = ejecutarConsulta($update_producto); if ($producto['status'] == false) { return  $producto;}
    }	
		
		return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idcompra_producto) {
    $sql = "SELECT * FROM compra_producto WHERE idcompra_producto='$idcompra_producto'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para listar los registros
  public function tbla_principal($fecha_1, $fecha_2, $id_proveedor, $comprobante) {

    $filtro_proveedor = ""; $filtro_fecha = ""; $filtro_comprobante = ""; 

    if ( !empty($fecha_1) && !empty($fecha_2) ) {
      $filtro_fecha = "AND cp.fecha_compra BETWEEN '$fecha_1' AND '$fecha_2'";
    } else if (!empty($fecha_1)) {      
      $filtro_fecha = "AND cp.fecha_compra = '$fecha_1'";
    }else if (!empty($fecha_2)) {        
      $filtro_fecha = "AND cp.fecha_compra = '$fecha_2'";
    }    

    if (empty($id_proveedor) ) {  $filtro_proveedor = ""; } else { $filtro_proveedor = "AND cp.idpersona = '$id_proveedor'"; }

    if ( empty($comprobante) ) { } else { $filtro_comprobante = "AND cp.tipo_comprobante = '$comprobante'"; } 

    $data = Array();
    $scheme_host=  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_briment/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

    $sql = "SELECT cp.idcompra_producto, cp.idpersona,cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante,cp.total, cp.tipo_gravada,cp.descripcion, p.nombres
    FROM compra_producto as cp, persona as p  
    WHERE cp.idpersona = p.idpersona AND cp.estado= '1' AND cp.estado_delete = '1' $filtro_proveedor $filtro_comprobante $filtro_fecha
		ORDER BY cp.fecha_compra DESC ";

    return ejecutarConsultaArray($sql);

  }

  //Implementar un método para listar los registros x proveedor
  public function listar_compraxporvee() {
    // $idproyecto=2;
    $sql = "SELECT  COUNT(cp.idcompra_producto) as cantidad, SUM(cp.total) as total, 
    cp.idpersona , p.nombres as razon_social, p.celular
		FROM compra_producto as cp, persona as p 
		WHERE  cp.idpersona=p.idpersona AND cp.estado = '1' AND cp.estado_delete = '1'
    GROUP BY cp.idpersona ORDER BY p.nombres ASC";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros x proveedor
  public function listar_detalle_comprax_provee($idproveedor) {

    $sql = "SELECT cp.idcompra_producto, cp.idpersona,cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante,cp.total,cp.descripcion
    FROM compra_producto as cp WHERE cp.idpersona = '$idproveedor' AND cp.estado= '1' AND cp.estado_delete = '1'";

    return ejecutarConsulta($sql);
  }

  //mostrar detalles uno a uno de la factura
  public function ver_compra($idcompra_producto) {

    $sql = "SELECT cp.idpersona, cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante, cp.val_igv, cp.subtotal, cp.igv, cp.total, cp.tipo_gravada, 
    cp.descripcion, p.nombres, p.tipo_documento, p.numero_documento, p.celular, p.correo 
    FROM compra_producto as cp, persona as p 
    WHERE cp.idpersona = p.idpersona AND cp.idcompra_producto ='$idcompra_producto';";

    $compra=  ejecutarConsultaSimpleFila($sql); if ($compra['status'] == false) {return $compra; }

    $sql = "SELECT dcp.idproducto, dcp.unidad_medida, dcp.categoria, dcp.cantidad, dcp.precio_sin_igv, dcp.igv, dcp.precio_con_igv, 
    dcp.precio_venta, dcp.descuento, dcp.subtotal, p.nombre, p.imagen, c.nombre as categoria 
    FROM detalle_compra_producto as dcp, producto as p, categoria_producto as c 
    WHERE dcp.idproducto =p.idproducto AND p.idcategoria_producto = c.idcategoria_producto AND dcp.idcompra_producto ='$idcompra_producto';";

    $detalle = ejecutarConsultaArray($sql);    if ($detalle['status'] == false) {return $detalle; }

    return $datos= Array('status' => true, 'data' => ['compra' => $compra['data'], 'detalle' => $detalle['data']], 'message' => 'Todo ok' );

  }

}

?>
