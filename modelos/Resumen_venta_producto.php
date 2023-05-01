<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ResumenVentaProducto
{
  //Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

  //Implementar un método para listar los registros
  public function tbla_principal() {
    $data = [];
    $sql = "SELECT p.idproducto, p.codigo, p.nombre AS nombre_producto, p.imagen, um.nombre AS unidad_medida, 
    l.nombre AS laboratorio, 
    SUM(dvp.cantidad) AS cantidad, SUM(dvp.precio_sin_igv) AS precio_sin_igv, SUM(dvp.igv) AS igv, SUM(dvp.precio_con_igv) AS precio_con_igv,
    AVG(dvp.precio_con_igv) AS precio_venta_promedio, SUM(dvp.descuento) AS descuento, SUM(dvp.subtotal) AS subtotal
    FROM venta_producto as vp, detalle_venta_producto as dvp, producto as p, unidad_medida as um, laboratorio as l
    WHERE vp.idventa_producto = dvp.idventa_producto AND dvp.idproducto = p.idproducto AND p.idunidad_medida = um.idunidad_medida 
    AND p.idlaboratorio = l.idlaboratorio AND vp.estado = '1' AND vp.estado_delete = '1' 
    GROUP BY dvp.idproducto ORDER BY p.nombre ASC;";
    $producto = ejecutarConsulta($sql); if ( $producto['status'] == false) {return $producto; } 

    foreach ($producto['data'] as $key => $val) {

      $id = $val['idproducto'];
      $sql_1 = "SELECT SUM(dcp.cantidad) as cant_compra FROM detalle_compra_producto as dcp, compra_producto as cp
      WHERE dcp.idcompra_producto = cp.idcompra_producto AND cp.estado = '1' AND cp.estado_delete = '1' AND dcp.estado = '1' AND dcp.estado_delete = '1' AND dcp.idproducto = '$id';";
      $compra = ejecutarConsultaSimpleFila($sql_1); if ( $compra['status'] == false) {return $compra; }  

      $sql_2 = "SELECT SUM(dvp.cantidad) as cant_venta FROM detalle_venta_producto as dvp, venta_producto as vp
      WHERE vp.idventa_producto = dvp.idventa_producto AND vp.estado = '1' AND vp.estado_delete = '1' AND dvp.estado = '1' AND dvp.estado_delete = '1' AND dvp.idproducto = '$id';";
      $venta = ejecutarConsultaSimpleFila($sql_2); if ( $venta['status'] == false) {return $venta; }  

      $n_compra = empty($compra['data']) ? 0 : (empty($compra['data']['cant_compra']) ? 0 : floatval($compra['data']['cant_compra']) ) ;
      $n_venta  = empty($venta['data']) ? 0 : (empty($venta['data']['cant_venta']) ? 0 : floatval($venta['data']['cant_venta']) ) ;

      $stock = $n_compra - $n_venta;

      $data[] = [
        "idproducto"      => $val['idproducto'],
        "codigo"          => $val['codigo'],
        "nombre_producto" => $val['nombre_producto'],
        "imagen"          => $val['imagen'],
        "unidad_medida"   => $val['unidad_medida'],
        "laboratorio"     => $val['laboratorio'],
        "cantidad"        => $val['cantidad'],
        "precio_sin_igv"  => $val['precio_sin_igv'],
        "igv"             => $val['igv'],
        "precio_con_igv"  => $val['precio_con_igv'],
        "precio_venta_promedio"    => $val['precio_venta_promedio'],
        "descuento"       => $val['descuento'],
        "subtotal"        => $val['subtotal'],
        "stock"           => $stock,
      ];
    }
    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ]; 

  }

  public function tbla_facturas( $idproducto) {
    $sql = "SELECT p.idproducto, vp.idventa_producto, per.nombres as persona, vp.fecha_venta, vp.tipo_comprobante, vp.serie_comprobante, dvp.cantidad, 
    dvp.precio_con_igv as precio_venta, dvp.descuento, dvp.subtotal
    FROM venta_producto as vp, persona AS per, detalle_venta_producto as dvp, producto as p, unidad_medida as um, laboratorio as l
    WHERE vp.idventa_producto = dvp.idventa_producto AND vp.idpersona = per.idpersona AND dvp.idproducto = p.idproducto AND p.idunidad_medida = um.idunidad_medida 
    AND p.idlaboratorio = l.idlaboratorio AND vp.estado = '1' AND vp.estado_delete = '1' AND dvp.idproducto = '$idproducto' 
		ORDER BY vp.fecha_venta DESC;";
    return ejecutarConsultaArray($sql);
  }

}

?>
