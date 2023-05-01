<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Compra_producto
{
  //Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

  // ::::::::::::::::::::::::::::::::::::::::: S E C C I O N   C O M P R A  ::::::::::::::::::::::::::::::::::::::::: 
  //Implementamos un método para insertar registros
  public function insertar( $idsucursal, $idproveedor, $num_doc, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, $total_compra, $subtotal_compra,
  $igv_compra, $total_descuento, $tipo_gravada, 
  $idproducto, $lote, $unidad_medida,$um_abreviatura, $presentacion, $laboratorio, $cantidad, $precio_sin_igv, $precio_igv, $precio_con_igv,$precio_venta, $descuento) {    

    // buscamos al si la FACTURA existe
    $sql_2 = "SELECT  cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante, cp.igv, cp.total_compra, cp.estado, cp.estado_delete, 
    p.nombres, p.numero_documento, p.tipo_documento
    FROM compra_producto as cp, persona as p 
    WHERE cp.idpersona = p.idpersona AND cp.tipo_comprobante ='$tipo_comprobante' AND cp.serie_comprobante = '$serie_comprobante' AND p.numero_documento='$num_doc'";
    $compra_existe = ejecutarConsultaArray($sql_2); if ($compra_existe['status'] == false) { return  $compra_existe;}

    if (empty($compra_existe['data']) || $tipo_comprobante == 'Ninguno') {
     
      $sql_3 = "INSERT INTO compra_producto(idpersona, idpersona_sucursal, fecha_compra, tipo_comprobante, serie_comprobante, val_igv, subtotal, total_descuento, igv, total_compra,tipo_gravada, descripcion) 
      VALUES ('$idproveedor', '$idsucursal','$fecha_compra','$tipo_comprobante','$serie_comprobante','$val_igv','$subtotal_compra', '$total_descuento', '$igv_compra','$total_compra','$tipo_gravada','$descripcion')";
      $idventanew = ejecutarConsulta_retornarID($sql_3); if ($idventanew['status'] == false) { return  $idventanew;}

      //add registro en nuestra bitacora
      $sql_d = $idsucursal .', '.$idproveedor.', '.$num_doc.', '.$fecha_compra.', '. $tipo_comprobante.', '.$serie_comprobante.', '.$val_igv.', '.$descripcion.', '.$total_compra.', '.$subtotal_compra.', '.$igv_compra.', '.$total_descuento.', '.$tipo_gravada;
      $sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5,'compra_producto','".$idventanew['data']."','$sql_d','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; } 

      $ii = 0;
      $compra_new = "";

      if ( !empty($idventanew['data']) ) {
      
        while ($ii < count($idproducto)) {

          $id = $idventanew['data'];
          $subtotal_producto = (floatval($cantidad[$ii]) * floatval($precio_con_igv[$ii])) - $descuento[$ii];

          $sql_detalle = "INSERT INTO detalle_compra_producto(idcompra_producto, idproducto, idlote, unidad_medida,um_abreviatura, presentacion, laboratorio, cantidad, precio_sin_igv, igv, 
          precio_con_igv,precio_venta, descuento, subtotal, user_created) 
          VALUES ('$id','$idproducto[$ii]', '$lote[$ii]', '$unidad_medida[$ii]', '$um_abreviatura[$ii]', '$presentacion[$ii]', '$laboratorio[$ii]', '$cantidad[$ii]', '$precio_sin_igv[$ii]', '$precio_igv[$ii]', 
          '$precio_con_igv[$ii]','$precio_venta[$ii]', '$descuento[$ii]', '$subtotal_producto','$this->id_usr_sesion')";
          $compra_new =  ejecutarConsulta_retornarID($sql_detalle); if ($compra_new['status'] == false) { return  $compra_new;}

          //add registro en nuestra bitacora.
          $sql_d = $id.', '.$idproducto[$ii].', '.$unidad_medida[$ii].', '.$laboratorio[$ii].', '.$cantidad[$ii].', '.$precio_sin_igv[$ii].', '.$precio_igv[$ii].', '.
          $precio_con_igv[$ii].', '.$precio_venta[$ii].', '. $descuento[$ii].', '.$subtotal_producto;
          $sql_bit_d = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5,'detalle_compra_producto','".$compra_new['data']."','$sql_d','$this->id_usr_sesion')";
          $bitacora = ejecutarConsulta($sql_bit_d); if ( $bitacora['status'] == false) {return $bitacora; } 

          //add update table PRODUCTO el precio de venta
          $sql_producto = "UPDATE producto SET precio_venta='$precio_venta[$ii]', precio_compra='$precio_con_igv[$ii]', user_updated='$this->id_usr_sesion' WHERE idproducto = '$idproducto[$ii]'";
          $producto = ejecutarConsulta($sql_producto); if ($producto['status'] == false) { return  $producto;}

          //add update table LOTE el stock
          $sql_stock = "UPDATE lote SET stock = stock + '$cantidad[$ii]', precio_venta='$precio_venta[$ii]', precio_compra='$precio_con_igv[$ii]', user_updated='$this->id_usr_sesion' WHERE idlote = '$lote[$ii]'";
          $stock_p = ejecutarConsulta($sql_stock); if ($stock_p['status'] == false) { return  $stock_p;}          

          $ii = $ii + 1;
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
          <b>Total: </b>S/ '.number_format($value['total_compra'], 2, '.', ',').'<br>
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b> 
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );      
    }      
  }

  //Implementamos un método para editar registros
  public function editar( $idcompra_producto, $idsucursal, $idproveedor, $num_doc, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, $total_compra, $subtotal_compra,
  $igv_compra, $total_descuento, $tipo_gravada, 
  $idproducto, $lote, $unidad_medida,$um_abreviatura, $presentacion, $laboratorio, $cantidad, $precio_sin_igv, $precio_igv, $precio_con_igv,$precio_venta, $descuento) {

    if ( !empty($idcompra_producto) ) {
      //Eliminamos todos los productos asignados para volverlos a registrar
      $sqldel = "DELETE FROM detalle_compra_producto WHERE idcompra_producto='$idcompra_producto';";
      $delete_compra = ejecutarConsulta($sqldel);  if ($delete_compra['status'] == false) { return $delete_compra; }

      $sql = "UPDATE compra_producto SET idpersona='$idproveedor',fecha_compra='$fecha_compra',tipo_comprobante='$tipo_comprobante',
      serie_comprobante='$serie_comprobante',val_igv='$val_igv',subtotal='$subtotal_compra',igv='$igv_compra',
      total_compra='$total_compra', total_descuento='$total_descuento', tipo_gravada='$tipo_gravada',descripcion='$descripcion', user_updated = '$this->id_usr_sesion' 
      WHERE idcompra_producto = '$idcompra_producto'";
      $update_compra = ejecutarConsulta($sql); if ($update_compra['status'] == false) { return $update_compra; }

      //add registro en nuestra bitacora
      $sql_d = $idcompra_producto.', '.$idsucursal.', '.$idproveedor.', '.$num_doc.', '.$fecha_compra.', '.$tipo_comprobante.', '.$serie_comprobante.', '.$val_igv.', '.$descripcion.', '.$total_compra.', '.$subtotal_compra.', '.$igv_compra.', '.$total_descuento.', '.$tipo_gravada;
      $sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6, 'compra_producto','$idcompra_producto','$sql_d','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }

      $ii = 0; $detalle_compra = "";

      while ($ii < count($idproducto)) {

        $subtotal_producto = (floatval($cantidad[$ii]) * floatval($precio_con_igv[$ii])) - $descuento[$ii];

        $sql_detalle = "INSERT INTO detalle_compra_producto(idcompra_producto, idproducto, idlote, unidad_medida, um_abreviatura, presentacion, laboratorio, cantidad, 
        precio_sin_igv, igv, precio_con_igv, precio_venta, descuento, subtotal, user_created) 
        VALUES ('$idcompra_producto','$idproducto[$ii]', '$lote[$ii]', '$unidad_medida[$ii]',  '$um_abreviatura[$ii]', '$presentacion[$ii]', '$laboratorio[$ii]', '$cantidad[$ii]', 
        '$precio_sin_igv[$ii]', '$precio_igv[$ii]', '$precio_con_igv[$ii]','$precio_venta[$ii]', '$descuento[$ii]', 
        '$subtotal_producto','$this->id_usr_sesion')";
        $detalle_compra =  ejecutarConsulta_retornarID($sql_detalle); if ($detalle_compra['status'] == false) { return  $detalle_compra;}

        //add registro en nuestra bitacora.
        $sql_d = $idcompra_producto.', '.$idproducto[$ii].', '.$unidad_medida[$ii].', '.$laboratorio[$ii].', '.$cantidad[$ii].', '.$precio_sin_igv[$ii].', '.$precio_igv[$ii].', '.
        $precio_con_igv[$ii].', '.$precio_venta[$ii].', '. $descuento[$ii].', '.$subtotal_producto;
        $sql_bit_d = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6,'detalle_compra_producto','".$idcompra_producto."','$sql_d','$this->id_usr_sesion')";
        $bitacora = ejecutarConsulta($sql_bit_d); if ( $bitacora['status'] == false) {return $bitacora; } 

        //add update table PRODUCTO el precio de  venta
        $sql_producto = "UPDATE producto SET precio_venta='$precio_venta[$ii]', precio_compra='$precio_con_igv[$ii]', user_updated='$this->id_usr_sesion' WHERE idproducto = '$idproducto[$ii]'";
        $producto = ejecutarConsulta($sql_producto); if ($producto['status'] == false) { return  $producto;}

        // :::::::::::::: bsucamos para asigar el stock ::::::::::::
        $sql_1 = "SELECT SUM(dcp.cantidad) as cant_compra FROM detalle_compra_producto as dcp
        WHERE dcp.estado = '1' AND dcp.estado_delete = '1' AND dcp.idproducto = '$idproducto[$ii]';";
        $compra = ejecutarConsultaSimpleFila($sql_1); if ( $compra['status'] == false) {return $compra; }  

        $sql_2 = "SELECT SUM(dvp.cantidad) as cant_venta FROM detalle_venta_producto as dvp
        WHERE dvp.estado = '1' AND dvp.estado_delete = '1' AND dvp.idproducto = '$idproducto[$ii]';";
        $venta = ejecutarConsultaSimpleFila($sql_2); if ( $venta['status'] == false) {return $venta; }  

        $n_compra = empty($compra['data']) ? 0 : (empty($compra['data']['cant_compra']) ? 0 : floatval($compra['data']['cant_compra']) ) ;
        $n_venta  = empty($venta['data']) ? 0 : (empty($venta['data']['cant_venta']) ? 0 : floatval($venta['data']['cant_venta']) ) ;

        $stock = $n_compra - $n_venta;

        //add update table LOTE el stock
        $sql_stock = "UPDATE lote SET stock ='$stock', precio_venta='$precio_venta[$ii]', precio_compra='$precio_con_igv[$ii]', user_updated='$this->id_usr_sesion' WHERE idlote = '$lote[$ii]'";
        $stock_p = ejecutarConsulta($sql_stock); if ($stock_p['status'] == false) { return  $stock_p;}          

        $ii++;
      }
      return $detalle_compra; 
    } else { 
      return $retorno = ['status'=>false, 'mesage'=>'no hay nada', 'data'=>'sin data', ]; 
    }
  }

  

  //Implementamos un método para desactivar categorías
  public function desactivar($id) {    
    
    // buscamos las cantidades
    $sql_restaurar = "SELECT dcp.idproducto, dcp.idlote, dcp.cantidad FROM detalle_compra_producto AS dcp, compra_producto AS cp 
    WHERE dcp.idcompra_producto = cp.idcompra_producto AND cp.estado = '1' AND cp.estado_delete = '1' AND dcp.estado = '1' AND dcp.estado_delete = '1' AND dcp.idcompra_producto = '$id';";
    $restaurar_stok =  ejecutarConsultaArray($sql_restaurar); if ( $restaurar_stok['status'] == false) {return $restaurar_stok; }

    // actualizamos el stock
    foreach ($restaurar_stok['data'] as $key => $value) {  
      $cant = $value['cantidad'];    
      $update_producto = "UPDATE lote SET stock = stock - '$cant', user_updated = '$this->id_usr_sesion' WHERE idlote = '".$value['idlote']."';";
      $producto = ejecutarConsulta($update_producto); if ($producto['status'] == false) { return  $producto;}
    }	

    // desactivamos las compras
    $sql = "UPDATE compra_producto SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idcompra_producto='$id'";
		$desactivar= ejecutarConsulta($sql);if ($desactivar['status'] == false) {  return $desactivar; }
		
		//add registro en nuestra bitacora
		$sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (2, 'compra_producto','$id','$id','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function eliminar($id) {    

    // buscamos las cantidades
    $sql_restaurar = "SELECT dcp.idproducto, dcp.idlote, dcp.cantidad FROM detalle_compra_producto AS dcp, compra_producto AS cp 
    WHERE dcp.idcompra_producto = cp.idcompra_producto AND cp.estado = '1' AND cp.estado_delete = '1' AND dcp.estado = '1' AND dcp.estado_delete = '1' AND dcp.idcompra_producto = '$id';";
    $restaurar_stok =  ejecutarConsultaArray($sql_restaurar); if ( $restaurar_stok['status'] == false) {return $restaurar_stok; }

    // actualizamos el stock
    foreach ($restaurar_stok['data'] as $key => $value) {   
      $cant = $value['cantidad'];      
      $update_producto = "UPDATE lote SET stock = stock - '$cant', user_updated = '$this->id_usr_sesion' WHERE idlote = '".$value['idlote']."';";
      $producto = ejecutarConsulta($update_producto); if ($producto['status'] == false) { return  $producto;}
    }	

    //  borramos la compra
    $sql = "UPDATE compra_producto SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idcompra_producto='$id'";
		$eliminar =  ejecutarConsulta($sql);if ( $eliminar['status'] == false) {return $eliminar; }  
		
		//add registro en nuestra bitacora
		$sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (4, 'compra_producto','$id','$id','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
		
		return $eliminar;
  }

  //Implementar un método para listar los registros
  public function tbla_principal($nube_idsucursal, $fecha_1, $fecha_2, $id_proveedor, $comprobante) {

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

    $sql = "SELECT cp.idcompra_producto, cp.idpersona, cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante, cp.subtotal, cp.igv, cp.total_descuento, cp.total_compra, cp.tipo_gravada, cp.comprobante, cp.descripcion, p.nombres
    FROM compra_producto as cp, persona as p  
    WHERE cp.idpersona = p.idpersona AND cp.estado= '1' AND cp.estado_delete = '1' AND cp.idpersona_sucursal = '$nube_idsucursal' $filtro_proveedor $filtro_comprobante $filtro_fecha
		ORDER BY cp.fecha_compra DESC ";

    return ejecutarConsultaArray($sql);

  }

  //Implementar un método para listar los registros x proveedor
  public function listar_compra_x_porveedor($nube_idsucursal) {
    // $idproyecto=2;
    $sql = "SELECT  COUNT(cp.idcompra_producto) as cantidad, SUM(cp.total_compra) as total_compra, 
    cp.idpersona , p.nombres as razon_social, p.celular
		FROM compra_producto as cp, persona as p 
		WHERE  cp.idpersona=p.idpersona AND cp.estado = '1' AND cp.estado_delete = '1' AND cp.idpersona_sucursal = '$nube_idsucursal'
    GROUP BY cp.idpersona ORDER BY p.nombres ASC";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros x proveedor
  public function listar_detalle_comprax_provee($idproveedor) {

    $sql = "SELECT cp.idcompra_producto, cp.idpersona,cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante,cp.total_compra,cp.descripcion, cp.comprobante
    FROM compra_producto as cp WHERE cp.idpersona = '$idproveedor' AND cp.estado= '1' AND cp.estado_delete = '1'";

    return ejecutarConsulta($sql);
  }

  //mostrar detalles uno a uno de la factura
  public function ver_compra($idcompra_producto) {

    $sql = "SELECT cp.idcompra_producto, cp.idpersona, cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante, cp.val_igv, cp.subtotal, cp.total_descuento, cp.igv, cp.total_compra, cp.tipo_gravada, 
    cp.descripcion, p.nombres, p.tipo_documento, p.numero_documento, p.celular, p.correo 
    FROM compra_producto as cp, persona as p 
    WHERE cp.idpersona = p.idpersona AND cp.idcompra_producto ='$idcompra_producto';";
    $compra=  ejecutarConsultaSimpleFila($sql); if ($compra['status'] == false) {return $compra; }

    $sql = "SELECT dcp.idproducto, dcp.unidad_medida, dcp.um_abreviatura, dcp.presentacion, dcp.laboratorio, dcp.cantidad, dcp.precio_sin_igv, dcp.igv, dcp.precio_con_igv, 
    dcp.precio_venta, dcp.descuento, dcp.subtotal, p.nombre, p.imagen, p.codigo, l.idlote, l.nombre as lote, l.fecha_vencimiento
    FROM detalle_compra_producto as dcp, producto as p, lote as l
    WHERE dcp.idproducto =p.idproducto AND l.idlote = dcp.idlote AND dcp.idcompra_producto ='$idcompra_producto';";
    $detalle = ejecutarConsultaArray($sql);    if ($detalle['status'] == false) {return $detalle; }

    return $datos= Array('status' => true, 'data' => ['compra' => $compra['data'], 'detalle' => $detalle['data']], 'message' => 'Todo ok' );

  }

  // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  ::::::::::::::::::::::::::

  public function editar_comprobante($idcompra, $comprobante) {
    $sql    = "UPDATE compra_producto SET comprobante='$comprobante' WHERE idcompra_producto='$idcompra'";
		$editar =  ejecutarConsulta($sql);if ( $editar['status'] == false) {return $editar; }  
		
		//add registro en nuestra bitacora
    $sql_d  = $idcompra.', '.$comprobante;
		$sql    = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6,'compra_producto','$idcompra','$sql_d','$this->id_usr_sesion')";
		$bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  

		return $editar;
  }

  public function obtener_comprobante($id_compra) {
    $sql = "SELECT comprobante FROM compra_producto WHERE idcompra_producto ='$id_compra'";
    return ejecutarConsultaSimpleFila($sql);
  }

}

?>
