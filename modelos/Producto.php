<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

class Producto
{
  //Implementamos nuestro constructor
  public $id_usr_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0)
  {
    $this->id_usr_sesion = $id_usr_sesion;
  }

  //Implementamos un método para insertar registros
  public function insertar( $codigo,$nombre_producto,$laboratorio,$presentacion,$unidad_medida,$principio_activo,$descripcion,$imagen) {
    $sql = "SELECT p.idproducto, p.idunidad_medida, p.idlaboratorio, p.idpresentacion, p.codigo, p.nombre,um.nombre as unidad_medida, 
    l.nombre as laboratorio, pre.nombre as presentacion
    FROM producto as p, unidad_medida as um, laboratorio as l, presentacion as pre 
    WHERE p.idunidad_medida =um.idunidad_medida AND p.idlaboratorio =l.idlaboratorio AND p.idpresentacion =pre.idpresentacion 
    AND p.codigo = '$codigo' AND p.nombre = '$nombre_producto';";
    $buscando = ejecutarConsultaArray($sql);  if ($buscando['status'] == false) { return $buscando; }

    if ( empty($buscando['data']) ) {
      $sql = "INSERT INTO producto( idunidad_medida, idlaboratorio, idpresentacion, codigo, nombre, principio_activo, descripcion, imagen,user_created) 
      VALUES ('$unidad_medida','$laboratorio','$presentacion','$codigo','$nombre_producto','$principio_activo','$descripcion','$imagen','$this->id_usr_sesion')";     
      $intertar =  ejecutarConsulta_retornarID($sql); if ($intertar['status'] == false) {  return $intertar; } 

      //add registro en nuestra bitacora
      $sql_d = $codigo.','.$nombre_producto.','.$laboratorio.','.$presentacion.','.$unidad_medida.','.$principio_activo.','.$descripcion.','.$imagen;
      $sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (5,'producto','".$intertar['data']."','$sql_d','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

      return $intertar;

    } else {
      $info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Nombre: </b>'.$value['nombre'].'<br>
          <b>Codigo: </b>'.$value['codigo'].'<br>
          <b>Laboratorio: </b>'.$value['laboratorio'].'<br>
          <b>Presentacion: </b>'.$value['presentacion'].'<br>
          <b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .'<br>
          <b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
          <hr class="m-t-2px m-b-2px">
        </li>'; 
      }
      $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>'.$info_repetida.'</ul>', 'id_tabla' => '' );
      return $sw;
    }      
    
  }

  //Implementamos un método para editar registros
  public function editar($idproducto,$codigo,$nombre_producto,$laboratorio,$presentacion,$unidad_medida,$principio_activo,$descripcion,$imagen1) {
    // var_dump($idproducto, $idcategoria_producto, $unidad_medida, $nombre, $marca, $contenido_neto, $descripcion, $img_pefil);die();
    $sql = "UPDATE producto SET 
    idunidad_medida='$unidad_medida',
    idlaboratorio='$laboratorio',
    idpresentacion='$presentacion',
    codigo='$codigo',
    nombre='$nombre_producto',
    principio_activo='$principio_activo',
    descripcion='$descripcion',
    imagen='$imagen1',
    user_updated= '$this->id_usr_sesion'
		WHERE idproducto='$idproducto'";
    $editar =  ejecutarConsulta($sql);  if ( $editar['status'] == false) {return $editar; } 

    //add registro en nuestra bitacora
    $sql_d = $idproducto.','.$codigo.','.$nombre_producto.','.$laboratorio.','.$presentacion.','.$unidad_medida.','.$principio_activo.','.$descripcion.','.$imagen1;
    $sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (6,'producto','$idproducto','$sql_d','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  

    return $editar;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($id) {
    $sql = "UPDATE producto SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idproducto ='$id'";
    $desactivar= ejecutarConsulta($sql); if ($desactivar['status'] == false) {  return $desactivar; }
    
    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (2,'producto','".$id."','$id','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
    
    return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function activar($id) {
    $sql = "UPDATE producto SET estado='1' WHERE idproducto ='$id'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar($id) {
    $sql = "UPDATE producto SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idproducto ='$id'";
    $eliminar =  ejecutarConsulta($sql); if ( $eliminar['status'] == false) {return $eliminar; }  
    
    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( idcodigo, nombre_tabla, id_tabla, sql_d, id_user) VALUES (4,'producto','$id','$id','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
    
    return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idproducto) {
    $data = Array();

    $sql = "SELECT p.idproducto, p.idunidad_medida, p.idlaboratorio,p.idpresentacion, p.nombre, p.descripcion,p.codigo, p.precio_actual, 
    p.descripcion, p.imagen,p.created_at, p.estado,um.nombre as unidad_medida, l.nombre as laboratorio, pre.nombre as presentacion
    FROM producto as p, unidad_medida as um, laboratorio as l, presentacion as pre 
    WHERE p.idunidad_medida =um.idunidad_medida AND p.idlaboratorio =l.idlaboratorio AND p.idpresentacion =pre.idpresentacion 
    AND  p.idproducto='$idproducto'";

    $producto = ejecutarConsultaSimpleFila($sql);

    if ($producto['status'] == false) {  return $producto; }

    $data = array(
      'idproducto'      => $producto['data']['idproducto'],
      'codigo'      => $producto['data']['codigo'],
      'idlaboratorio' => $producto['data']['idlaboratorio'],
      'idunidad_medida' => $producto['data']['idunidad_medida'],
      'idpresentacion' => $producto['data']['idpresentacion'],          
      'nombre'          => decodeCadenaHtml($producto['data']['nombre']),
      'precio_actual' => (empty($producto['data']['precio_actual']) ? 0 : $producto['data']['precio_actual']), 
      'descripcion'     => decodeCadenaHtml($producto['data']['descripcion']),
      'imagen'          => $producto['data']['imagen'],
      'estado'          => $producto['data']['estado'],
      'fecha'           => $producto['data']['created_at'],
      //'nombre_medida'   => ( empty($producto['data']['nombre_medida']) ? '' : $producto['data']['nombre_medida']),
    );

    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];    
  }

  //Implementar un método para listar los registros
  public function tbla_principal($idpresentacion) {

    $tipo_presentacion = ''; $data = [];

    if ($idpresentacion == 'todos') {
      $tipo_presentacion = "";
    } else{
      $tipo_presentacion = "AND p.idpresentacion = '$idpresentacion'";
    }

    $sql_0 = "SELECT p.idproducto, p.idunidad_medida, p.idlaboratorio,p.idpresentacion, p.nombre, p.descripcion,p.codigo, p.precio_actual, 
    p.descripcion, p.imagen, p.estado,um.nombre as unidad_medida, l.nombre as laboratorio, pre.nombre as presentacion
    FROM producto as p, unidad_medida as um, laboratorio as l, presentacion as pre 
    WHERE p.idunidad_medida =um.idunidad_medida AND p.idlaboratorio =l.idlaboratorio AND p.idpresentacion =pre.idpresentacion 
    $tipo_presentacion and p.estado='1' AND p.estado_delete='1' ORDER BY p.nombre ASC";
    $producto = ejecutarConsulta($sql_0); if ( $producto['status'] == false) {return $producto; }  

    foreach ($producto['data'] as $key => $val) {
      $id = $val['idproducto'];
      $sql_1 = "SELECT SUM(dcp.cantidad) as cant_compra FROM detalle_compra_producto as dcp
      WHERE dcp.estado = '1' AND dcp.estado_delete = '1' AND dcp.idproducto = '$id';";
      $compra = ejecutarConsultaSimpleFila($sql_1); if ( $compra['status'] == false) {return $compra; }  

      $sql_2 = "SELECT SUM(dvp.cantidad) as cant_venta FROM detalle_venta_producto as dvp
      WHERE dvp.estado = '1' AND dvp.estado_delete = '1' AND dvp.idproducto = '$id';";
      $venta = ejecutarConsultaSimpleFila($sql_2); if ( $venta['status'] == false) {return $venta; }  

      $n_compra = empty($compra['data']) ? 0 : (empty($compra['data']['cant_compra']) ? 0 : floatval($compra['data']['cant_compra']) ) ;
      $n_venta  = empty($venta['data']) ? 0 : (empty($venta['data']['cant_venta']) ? 0 : floatval($venta['data']['cant_venta']) ) ;

      $stock = $n_compra - $n_venta;
      $data[] = [
        "idproducto"      => $val['idproducto'],
        "idunidad_medida" => $val['idunidad_medida'],
        "idlaboratorio"   => $val['idlaboratorio'],
        "idpresentacion"  => $val['idpresentacion'],
        "nombre"          => $val['nombre'],
        "descripcion"     => $val['descripcion'],
        "codigo"          => $val['codigo'],
        "precio_actual"   => $val['precio_actual'],
        "descripcion"     => $val['descripcion'],
        "imagen"          => $val['imagen'],
        "estado"          => $val['estado'],
        "unidad_medida"   => $val['unidad_medida'],
        "laboratorio"     => $val['laboratorio'],
        "presentacion"    => $val['presentacion'],
        "stock"            => $stock,
      ];
    }

    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ]; 
  }
  
  //OBTENEMOS LA IMAGEN PARA REEMPLAZARLO
  public function obtenerImg($idproducto) {
    $sql = "SELECT imagen FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }

  // ══════════════════════════════════════  P R E S E N T A C I O N   P R O D U C T O  ══════════════════════════════════════

  public function lista_de_presentacion(  )  {
    $sql = "SELECT idpresentacion, nombre FROM presentacion WHERE estado=1 AND estado_delete=1;";
    return ejecutarConsultaArray($sql);
  }

  // ══════════════════════════════════════  L O T E   P R O D U C T O  ══════════════════════════════════════

  public function tbla_lote($id_producto) {
    $sql = "SELECT  dcp.idlote, l.nombre, l.stock, l.fecha_vencimiento, l.descripcion, l.estado
    FROM detalle_compra_producto as dcp, lote as l
    WHERE dcp.idlote = l.idlote AND dcp.estado = '1' AND dcp.estado_delete = '1' AND 
    l.estado = '1' AND l.estado_delete = '1' AND dcp.idproducto  = '$id_producto'
    GROUP BY dcp.idlote;";
    return ejecutarConsultaArray($sql);
  }


  
}

?>
