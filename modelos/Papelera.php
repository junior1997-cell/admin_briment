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

    //sql para mostrar los datos de persona
    $sql6 = "SELECT p.idpersona, p.nombres, p.numero_documento, p.correo, p.created_at, p.updated_at, tp.nombre tipo_persona 
    FROM persona as p, tipo_persona as tp 
    WHERE p.idtipo_persona = tp.idtipo_persona AND p.estado='0' AND p.estado_delete=1;";
    $persona = ejecutarConsultaArray($sql6); if ($persona['status'] == false) { return $persona; }

    if (!empty($persona['data'])) {
      foreach ($persona['data'] as $key => $value6) {
        $data[] = array(
          'nombre_tabla'    => 'persona',
          'nombre_id_tabla' => 'idpersona',
          'modulo'          => 'Persona',
          'id_tabla'        => $value6['idpersona'],
          'nombre_archivo'  => $value6['nombres'],
          'descripcion'     => $value6['tipo_persona']."\n"."N° Doc : ".$value6['numero_documento']."\n"."Correo : ".$value6['correo'],
          'created_at'      => $value6['created_at'],
          'updated_at'      => $value6['updated_at'],
        );
      }
    }

    //sql para mostrar los datos de usuruio
    $sql7 = "SELECT u.idusuario, u.login, u.created_at, u.updated_at, p.nombres as persona 
    FROM usuario as u, persona as p 
    WHERE u.idpersona=p.idpersona AND u.estado='0' AND u.estado_delete=1;";
    $usuario = ejecutarConsultaArray($sql7); if ($usuario['status'] == false) { return $usuario; }

    if (!empty($usuario['data'])) {
      foreach ($usuario['data'] as $key => $value7) {
        $data[] = array(
          'nombre_tabla'    => 'usuario',
          'nombre_id_tabla' => 'idusuario',
          'modulo'          => 'Usuario',
          'id_tabla'        => $value7['idusuario'],
          'nombre_archivo'  => 'Usuario :'.$value7['login'],
          'descripcion'     => 'Nombre : '.$value7['persona'],
          'created_at'      => $value7['created_at'],
          'updated_at'      => $value7['updated_at'],
        );
      }
    } 

    //sql para mostrar los datos de producto
    $sql8 = "SELECT p.idproducto, p.nombre, p.descripcion, p.created_at, p.updated_at, cp.nombre as categoria_producto 
    FROM producto as p, categoria_producto as cp WHERE p.idcategoria_producto=cp.idcategoria_producto AND p.estado='0' AND p.estado_delete=1;";
    $producto = ejecutarConsultaArray($sql8); if ($producto['status'] == false) { return $producto; }

    if (!empty($producto['data'])) {
      foreach ($producto['data'] as $key => $value8) {
        $data[] = array(
          'nombre_tabla'    => 'producto',
          'nombre_id_tabla' => 'idproducto',
          'modulo'          => 'Producto',
          'id_tabla'        => $value8['idproducto'],
          'nombre_archivo'  => $value8['nombre'],
          'descripcion'     => $value8['descripcion'],
          'created_at'      => $value8['created_at'],
          'updated_at'      => $value8['updated_at'],
        );
      }
    }

    //Sql para mostrar los datos de compra_producto
    $sql9 = "SELECT cp.idcompra_producto,cp.idpersona,cp.fecha_compra,cp.tipo_comprobante,cp.serie_comprobante,
    cp.subtotal,cp.descripcion,cp.created_at,cp.updated_at, p.nombres as proveedor 
    FROM compra_producto as cp, persona as p 
    WHERE cp.idpersona=p.idpersona AND cp.estado='0' AND cp.estado_delete=1;";

    $compra_producto = ejecutarConsultaArray($sql9); if ($compra_producto['status'] == false) { return $compra_producto; }
    if (!empty($compra_producto['data'])) {
      foreach ($compra_producto['data'] as $key => $value9) {
        $data[] = array(
          'nombre_tabla'    => 'compra_producto',
          'nombre_id_tabla' => 'idcompra_producto',
          'modulo'          => 'Compra producto',
          'id_tabla'        => $value9['idcompra_producto'],
          'nombre_archivo'  => $value9['tipo_comprobante'].' : '.$value9['serie_comprobante'],
          'descripcion'     => $value9['descripcion'],
          'created_at'      => $value9['created_at'],
          'updated_at'      => $value9['updated_at'],
        );
      }
    }

    //Sql para mostrar los datos de venta_producto
    $sql10 = "SELECT vp.idventa_producto,vp.idpersona,vp.fecha_venta,vp.tipo_comprobante,vp.serie_comprobante,
    vp.subtotal,vp.descripcion,vp.created_at,vp.updated_at, p.nombres as cliente
    FROM venta_producto as vp, persona as p 
    WHERE vp.idpersona=p.idpersona AND vp.estado='0' AND vp.estado_delete=1;";
    $venta_producto = ejecutarConsultaArray($sql10); if ($venta_producto['status'] == false) { return $venta_producto; }

    if (!empty($venta_producto['data'])) {
      foreach ($venta_producto['data'] as $key => $value10) {
        $data[] = array(
          'nombre_tabla'    => 'venta_producto',
          'nombre_id_tabla' => 'idventa_producto',
          'modulo'          => 'Venta productos',
          'id_tabla'        => $value10['idventa_producto'],
          'nombre_archivo'  => $value10['tipo_comprobante'].' : '.$value10['serie_comprobante'],
          'descripcion'     => $value10['descripcion'],
          'created_at'      => $value10['created_at'],
          'updated_at'      => $value10['updated_at'],
        );
      }
    }

    //sql para mostrar los datos de compra_grano
    $sql11 = "SELECT cg.idcompra_grano,cg.idpersona,cg.tipo_comprobante,cg.numero_comprobante,cg.descripcion,
    cg.created_at,cg.updated_at, p.nombres as persona
    FROM compra_grano as cg, persona as p
    WHERE cg.idpersona = p.idpersona AND cg.estado='0' AND cg.estado_delete='1';";
    $compra_grano = ejecutarConsultaArray($sql11); if ($compra_grano['status'] == false) { return $compra_grano; }

    if (!empty($compra_grano['data'])) {
      foreach ($compra_grano['data'] as $key => $value11) {
        $data[] = array(
          'nombre_tabla'    => 'compra_grano',
          'nombre_id_tabla' => 'idcompra_grano',
          'modulo'          => 'Compra granos',
          'id_tabla'        => $value11['idcompra_grano'],
          'nombre_archivo'  => $value11['tipo_comprobante'].' : '.$value11['numero_comprobante'],
          'descripcion'     => $value11['descripcion'],
          'created_at'      => $value11['created_at'],
          'updated_at'      => $value11['updated_at'],
        );
      }
    }

    //sql para mostrar los datos de pago_compra_grano
    $sql12 = "SELECT idpago_compra_grano,monto,descripcion,forma_pago,fecha_pago,created_at,updated_at 
    FROM pago_compra_grano 
    WHERE estado='0' AND estado_delete='1';";
    $pago_compra_grano = ejecutarConsultaArray($sql12); if ($pago_compra_grano['status'] == false) { return $pago_compra_grano; };
    
    if (!empty($pago_compra_grano['data'])) {
      foreach ($pago_compra_grano['data'] as $key => $value12) {
        $data[] = array(
          'nombre_tabla'    => 'pago_compra_grano',
          'nombre_id_tabla' => 'idpago_compra_grano',
          'modulo'          => 'Pago compra grano',
          'id_tabla'        => $value12['idpago_compra_grano'],
          'nombre_archivo'  => $value12['forma_pago'].' : '.$value12['monto'],
          'descripcion'     => $value12['descripcion'],
          'created_at'      => $value12['created_at'],
          'updated_at'      => $value12['updated_at'],
        );
      }
    }
    
    //sql para mostrar los datos de pago_trabajador
    $sql13 = "SELECT idpago_trabajador,nombre_mes,monto,descripcion,created_at,updated_at FROM pago_trabajador WHERE estado='0' AND estado_delete='1';";
    $pago_trabajador = ejecutarConsultaArray($sql13); if ($pago_trabajador['status'] == false) { return $pago_trabajador; };
    
    if (!empty($pago_trabajador['data'])) {
      foreach ($pago_trabajador['data'] as $key => $value13) {
        $data[] = array(
          'nombre_tabla'    => 'pago_trabajador',
          'nombre_id_tabla' => 'idpago_trabajador',
          'modulo'          => 'Pago Trabajador',
          'id_tabla'        => $value13['idpago_trabajador'],
          'nombre_archivo'  => 'Pago del mes '.$value13['nombre_mes'].' : '.$value13['monto'],
          'descripcion'     => $value13['descripcion'],
          'created_at'      => $value13['created_at'],
          'updated_at'      => $value13['updated_at'],
        );
      }
    }

    //sql para mostrar los datos de pago_venta_producto
    $sql14 = "SELECT idpago_venta_producto,monto,descripcion,forma_pago,fecha_pago,created_at,updated_at
    FROM pago_venta_producto
    WHERE estado='0' AND estado_delete='1';";
    $pago_venta_producto = ejecutarConsultaArray($sql14); if ($pago_venta_producto['status'] == false) { return $pago_venta_producto; };
    
    if (!empty($pago_venta_producto['data'])) {
      foreach ($pago_venta_producto['data'] as $key => $value14) {
        $data[] = array(
          'nombre_tabla'    => 'pago_venta_producto',
          'nombre_id_tabla' => 'idpago_venta_producto',
          'modulo'          => 'Pago venta producto',
          'id_tabla'        => $value14['idpago_venta_producto'],
          'nombre_archivo'  => $value14['forma_pago'].' : '.$value14['monto'],
          'descripcion'     => $value14['descripcion'],
          'created_at'      => $value14['created_at'],
          'updated_at'      => $value14['updated_at'],
        );
      }
    }

    //sql para mostrar los datos de otro_ingreso
    $sql15 = "SELECT idotro_ingreso,precio_con_igv,tipo_comprobante,numero_comprobante,forma_de_pago,descripcion,created_at,updated_at 
    FROM otro_ingreso 
    WHERE estado='0' AND estado_delete='1';";
    $otro_ingreso = ejecutarConsultaArray($sql15); if ($otro_ingreso['status'] == false) { return $otro_ingreso; };
       
    if (!empty($otro_ingreso['data'])) {
      foreach ($otro_ingreso['data'] as $key => $value15) {
        $data[] = array(
          'nombre_tabla'    => 'otro_ingreso',
          'nombre_id_tabla' => 'idotro_ingreso',
          'modulo'          => 'Otros Ingresos',
          'id_tabla'        => $value15['idotro_ingreso'],
          'nombre_archivo'  => $value15['tipo_comprobante'].' : '.$value15['numero_comprobante'],
          'descripcion'     => $value15['forma_de_pago'].' : '.$value15['precio_con_igv'],
          'created_at'      => $value15['created_at'],
          'updated_at'      => $value15['updated_at'],
        );
      }
    }
 






    return $data;
  }
  /*----------------------------------------------------------------
  ----------F I N  M O D U L O   P E R S O N A S--------------------
  ----------------------------------------------------------------*/
   //sql para mostrar los datos de producto



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
