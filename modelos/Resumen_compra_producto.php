<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ResumenCompraProducto
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementar un método para listar los registros
  public function tbla_principal() {
    $sql = "SELECT p.idproducto, p.nombre AS nombre_producto, p.marca, p.contenido_neto, p.stock, p.imagen, um.nombre AS unidad_medida, 
    catp.nombre AS categoria_producto, 
    SUM(dcp.cantidad) AS cantidad, SUM(dcp.precio_sin_igv) AS precio_sin_igv, SUM(dcp.igv) AS igv, SUM(dcp.precio_con_igv) AS precio_con_igv,
    AVG(dcp.precio_venta) AS precio_venta, SUM(dcp.descuento) AS descuento, SUM(dcp.subtotal) AS subtotal
    FROM compra_producto as cp, detalle_compra_producto as dcp, producto as p, unidad_medida as um, categoria_producto as catp
    WHERE cp.idcompra_producto = dcp.idcompra_producto AND dcp.idproducto = p.idproducto AND p.idunidad_medida = um.idunidad_medida 
    AND p.idcategoria_producto = catp.idcategoria_producto AND cp.estado = '1' AND cp.estado_delete = '1' 
    GROUP BY dcp.idproducto ORDER BY p.nombre ASC;";

    return ejecutarConsulta($sql);
  }

  public function tbla_facturas( $idproducto) {
    $sql = "SELECT p.idproducto, cp.idcompra_producto, per.nombres as persona, cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante, dcp.cantidad, 
    dcp.precio_venta, dcp.descuento, dcp.subtotal
    FROM compra_producto as cp, persona AS per, detalle_compra_producto as dcp, producto as p, unidad_medida as um, categoria_producto as catp
    WHERE cp.idcompra_producto = dcp.idcompra_producto AND cp.idpersona = per.idpersona AND dcp.idproducto = p.idproducto AND p.idunidad_medida = um.idunidad_medida 
    AND p.idcategoria_producto = catp.idcategoria_producto AND cp.estado = '1' AND cp.estado_delete = '1' AND dcp.idproducto = '$idproducto' 
		ORDER BY cp.fecha_compra DESC;";
    return ejecutarConsultaArray($sql);


  }

  public function sumas_factura_x_material($idproyecto, $idproducto) {
    $sql = "SELECT  SUM(dc.cantidad) AS cantidad, AVG(dc.precio_con_igv) AS precio_promedio, SUM(dc.descuento) AS descuento, SUM(dc.subtotal) AS subtotal
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr, proveedor AS prov
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto 
		AND dc.idproducto = pr.idproducto AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1'
		AND cpp.idproveedor = prov.idproveedor AND dc.idproducto = '$idproducto' 
		ORDER BY cpp.fecha_compra DESC;";

    return ejecutarConsultaSimpleFila($sql);
  }

  public function suma_total_compras($idproyecto)  {
    $sql = "SELECT SUM( dc.subtotal ) AS suma_total_compras, SUM( dc.cantidad ) AS suma_total_productos
		FROM proyecto AS p, compra_por_proyecto AS cpp, detalle_compra AS dc, producto AS pr
		WHERE p.idproyecto = cpp.idproyecto AND cpp.idcompra_proyecto = dc.idcompra_proyecto AND dc.idproducto = pr.idproducto 
		AND pr.idcategoria_insumos_af = '1' AND cpp.idproyecto ='$idproyecto' AND cpp.estado = '1' AND cpp.estado_delete = '1';";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listar_productos() {
    $sql = "SELECT
            p.idproducto AS idproducto,
            p.idunidad_medida AS idunidad_medida,
            p.idcolor AS idcolor,
            p.nombre AS nombre,
            p.marca AS marca,
            ciaf.nombre AS categoria,
            p.descripcion AS descripcion,
            p.imagen AS imagen,
            p.estado_igv AS estado_igv,
            p.precio_unitario AS precio_unitario,
            p.precio_igv AS precio_igv,
            p.precio_sin_igv AS precio_sin_igv,
            p.precio_total AS precio_total,
            p.ficha_tecnica AS ficha_tecnica,
            p.estado AS estado,
            c.nombre_color AS nombre_color,
            um.nombre_medida AS nombre_medida
        FROM producto p, unidad_medida AS um, color AS c, categoria_insumos_af AS ciaf
        WHERE um.idunidad_medida=p.idunidad_medida  AND c.idcolor=p.idcolor  AND ciaf.idcategoria_insumos_af = p.idcategoria_insumos_af 
		AND p.estado = '1' AND p.estado_delete = '1'
        ORDER BY p.nombre ASC";

    return ejecutarConsulta($sql);
  }

  //Seleccionar
  public function obtenerImgPerfilProducto($idproducto)
  {
    $sql = "SELECT imagen FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }
}

?>
