<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class ResumenCompraProducto
{
  //Implementamos nuestro variable global
	public $id_usr_sesion;

	//Implementamos nuestro constructor
	public function __construct($id_usr_sesion = 0)
	{
		$this->id_usr_sesion = $id_usr_sesion;
	}

  //Implementar un método para listar los registros
  public function tbla_principal($nube_idsucursal) {
    $data = [];
    $sql = "SELECT p.idproducto, p.codigo, p.nombre AS nombre_producto, p.precio_venta, p.imagen, um.nombre AS unidad_medida, 
    l.nombre AS laboratorio, 
    SUM(dcp.cantidad) AS cantidad, SUM(dcp.precio_sin_igv) AS precio_sin_igv, SUM(dcp.igv) AS igv, SUM(dcp.precio_con_igv) AS precio_con_igv,
    AVG(dcp.precio_con_igv) AS precio_compra_promedio, SUM(dcp.descuento) AS descuento, SUM(dcp.subtotal) AS subtotal
    FROM compra_producto as cp, detalle_compra_producto as dcp, producto as p, unidad_medida as um, laboratorio as l
    WHERE cp.idcompra_producto = dcp.idcompra_producto AND dcp.idproducto = p.idproducto AND p.idunidad_medida = um.idunidad_medida 
    AND p.idlaboratorio = l.idlaboratorio AND cp.estado = '1' AND cp.estado_delete = '1' AND cp.idpersona_sucursal = $nube_idsucursal
    GROUP BY dcp.idproducto ORDER BY p.nombre ASC;";
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
        "precio_compra_promedio"    => $val['precio_compra_promedio'],
        "precio_venta"   => $val['precio_venta'],
        "descuento"       => $val['descuento'],
        "subtotal"        => $val['subtotal'],
        "stock"           => $stock,
      ];
    }
    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ]; 
  }

  public function tbla_facturas( $idproducto) {
    $sql = "SELECT p.idproducto, cp.idcompra_producto, per.nombres as persona, cp.fecha_compra, cp.tipo_comprobante, cp.serie_comprobante, dcp.cantidad, 
    dcp.precio_con_igv as precio_compra, dcp.precio_venta, dcp.descuento, dcp.subtotal
    FROM compra_producto as cp, persona AS per, detalle_compra_producto as dcp, producto as p, unidad_medida as um
    WHERE cp.idcompra_producto = dcp.idcompra_producto AND cp.idpersona = per.idpersona AND dcp.idproducto = p.idproducto AND p.idunidad_medida = um.idunidad_medida 
    AND cp.estado = '1' AND cp.estado_delete = '1' AND dcp.idproducto = '$idproducto' 
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
