<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ResumenVentaProducto
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementar un método para listar los registros
  public function tbla_principal() {
    $sql = "SELECT p.idproducto, p.nombre AS nombre_producto, p.marca, p.contenido_neto, p.stock, p.imagen, um.nombre AS unidad_medida, 
    catp.nombre AS categoria_producto, 
    SUM(dvp.cantidad) AS cantidad, SUM(dvp.precio_sin_igv) AS precio_sin_igv, SUM(dvp.igv) AS igv, SUM(dvp.precio_con_igv) AS precio_con_igv,
    AVG(dvp.precio_con_igv) AS precio_venta, SUM(dvp.descuento) AS descuento, SUM(dvp.subtotal) AS subtotal
    FROM venta_producto as vp, detalle_venta_producto as dvp, producto as p, unidad_medida as um, categoria_producto as catp
    WHERE vp.idventa_producto = dvp.idventa_producto AND dvp.idproducto = p.idproducto AND p.idunidad_medida = um.idunidad_medida 
    AND p.idcategoria_producto = catp.idcategoria_producto AND vp.estado = '1' AND vp.estado_delete = '1' 
    GROUP BY dvp.idproducto ORDER BY p.nombre ASC;";

    return ejecutarConsulta($sql);
  }

  public function tbla_facturas( $idproducto) {
    $sql = "SELECT p.idproducto, vp.idventa_producto, per.nombres as persona, vp.fecha_venta, vp.tipo_comprobante, vp.serie_comprobante, dvp.cantidad, 
    dvp.precio_con_igv as precio_venta, dvp.descuento, dvp.subtotal
    FROM venta_producto as vp, persona AS per, detalle_venta_producto as dvp, producto as p, unidad_medida as um, categoria_producto as catp
    WHERE vp.idventa_producto = dvp.idventa_producto AND vp.idpersona = per.idpersona AND dvp.idproducto = p.idproducto AND p.idunidad_medida = um.idunidad_medida 
    AND p.idcategoria_producto = catp.idcategoria_producto AND vp.estado = '1' AND vp.estado_delete = '1' AND dvp.idproducto = '$idproducto' 
		ORDER BY vp.fecha_venta DESC;";
    return ejecutarConsultaArray($sql);
  }

}

?>
