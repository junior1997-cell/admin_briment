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
  public function insertar( $idcategoria_producto, $idunidad_medida, $nombre, $marca, $contenido_neto, $descripcion, $imagen) {
    $sql = "SELECT p.nombre, p.marca, p.contenido_neto, p.estado, p.descripcion,
    p.imagen, p.estado, p.estado_delete, um.nombre as nombre_medida, cp.nombre as nombre_categoria
		FROM producto p, unidad_medida as um, categoria_producto as cp
    WHERE um.idunidad_medida=p.idunidad_medida AND cp.idcategoria_producto=p.idcategoria_producto AND  
    p.idcategoria_producto = '$idcategoria_producto' AND p.idunidad_medida = '$idunidad_medida' AND 
    p.nombre='$nombre'";
    $buscando = ejecutarConsultaArray($sql);  if ($buscando['status'] == false) { return $buscando; }

    if ( empty($buscando['data']) ) {
      $sql = "INSERT INTO producto (idcategoria_producto, idunidad_medida, nombre, marca, contenido_neto, descripcion, imagen, user_created) 
      VALUES ( '$idcategoria_producto', '$idunidad_medida', '$nombre', '$marca', '$contenido_neto', '$descripcion', '$imagen','$this->id_usr_sesion')";
     
      $intertar =  ejecutarConsulta_retornarID($sql); if ($intertar['status'] == false) {  return $intertar; } 

      //add registro en nuestra bitacora
      $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','".$intertar['data']."','Nuevo producto registrado','$this->id_usr_sesion')";
      $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   

      return $intertar;

    } else {
      $info_repetida = ''; 

      foreach ($buscando['data'] as $key => $value) {
        $info_repetida .= '<li class="text-left font-size-13px">
          <b>Nombre: </b>'.$value['nombre'].'<br>
          <b>Clasificaciòn: </b>'.$value['nombre_categoria'].'<br>
          <b>UM: </b>'.$value['unidad_medida'].'<br>
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
  public function editar($idproducto, $idcategoria_producto, $unidad_medida, $nombre, $marca, $contenido_neto, $descripcion, $img_pefil) {
    // var_dump($idproducto, $idcategoria_producto, $unidad_medida, $nombre, $marca, $contenido_neto, $descripcion, $img_pefil);die();
    $sql = "UPDATE producto SET idcategoria_producto = '$idcategoria_producto',	idunidad_medida = '$unidad_medida',	nombre = '$nombre',
		marca = '$marca',	contenido_neto = '$contenido_neto',	descripcion = '$descripcion',	imagen = '$img_pefil', user_updated= '$this->id_usr_sesion'
		WHERE idproducto='$idproducto'";

    $editar =  ejecutarConsulta($sql);
    if ( $editar['status'] == false) {return $editar; } 

    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','$idproducto','Producto editado','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }  

    return $editar;
  }

  //Implementamos un método para desactivar categorías
  public function desactivar($idproducto) {
    $sql = "UPDATE producto SET estado='0',user_trash= '$this->id_usr_sesion' WHERE idproducto ='$idproducto'";
    $desactivar= ejecutarConsulta($sql);

    if ($desactivar['status'] == false) {  return $desactivar; }
    
    //add registro en nuestra bitacora
    $sql_bit = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','".$idproducto."','Producto desactivado','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql_bit); if ( $bitacora['status'] == false) {return $bitacora; }   
    
    return $desactivar;
  }

  //Implementamos un método para activar categorías
  public function activar($idproducto) {
    $sql = "UPDATE producto SET estado='1' WHERE idproducto ='$idproducto'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para activar categorías
  public function eliminar($idproducto) {
    $sql = "UPDATE producto SET estado_delete='0',user_delete= '$this->id_usr_sesion' WHERE idproducto ='$idproducto'";
    $eliminar =  ejecutarConsulta($sql);
    if ( $eliminar['status'] == false) {return $eliminar; }  
    
    //add registro en nuestra bitacora
    $sql = "INSERT INTO bitacora_bd( nombre_tabla, id_tabla, accion, id_user) VALUES ('producto','$idproducto','Producto Eliminado','$this->id_usr_sesion')";
    $bitacora = ejecutarConsulta($sql); if ( $bitacora['status'] == false) {return $bitacora; }  
    
    return $eliminar;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idproducto) {
    $data = Array();

    $sql = "SELECT p.idproducto, p.idcategoria_producto, p.idunidad_medida, p.nombre, p.marca, p.contenido_neto, p.precio_unitario, p.stock, p.descripcion, p.imagen, p.estado, p.created_at,
    um.nombre as nombre_medida, cp.nombre AS categoria
		FROM producto AS p, unidad_medida AS um, categoria_producto AS cp
    WHERE p.idunidad_medida = um.idunidad_medida AND p.idcategoria_producto = cp.idcategoria_producto AND p.idproducto = '$idproducto'";

    $producto = ejecutarConsultaSimpleFila($sql);

    if ($producto['status'] == false) {  return $producto; }

    $data = array(
      'idproducto'      => $producto['data']['idproducto'],
      'idcategoria_producto' => $producto['data']['idcategoria_producto'],
      'idunidad_medida' => $producto['data']['idunidad_medida'],
      'nombre_medida'   => $producto['data']['nombre_medida'],
      'categoria'       => $producto['data']['categoria'],           
      'nombre'          => decodeCadenaHtml($producto['data']['nombre']),
      'marca'           => decodeCadenaHtml($producto['data']['marca']),
      'contenido_neto'  => decodeCadenaHtml($producto['data']['contenido_neto']),
      'precio_unitario' => (empty($producto['data']['precio_unitario']) ? 0 : $producto['data']['precio_unitario']),
      'stock'           => $producto['data']['stock'],
      'descripcion'     => decodeCadenaHtml($producto['data']['descripcion']),
      'imagen'          => $producto['data']['imagen'],
      'estado'          => $producto['data']['estado'],
      'fecha'           => $producto['data']['created_at'],
      //'nombre_medida'   => ( empty($producto['data']['nombre_medida']) ? '' : $producto['data']['nombre_medida']),
    );

    return $retorno = ['status'=> true, 'message' => 'Salió todo ok,', 'data' => $data ];    
  }

  //Implementar un método para listar los registros
  public function tbla_principal($idcategoria) {

    $tipo_categoria = '';

    if ($idcategoria == 'todos') {
      $tipo_categoria = "";
    } else{
      $tipo_categoria = "AND p.idcategoria_producto = '$idcategoria'";
    }

    $sql = "SELECT p.idproducto, p.idcategoria_producto, p.idunidad_medida, p.nombre, p.marca, p.contenido_neto, p.precio_unitario, p.stock, 
    p.descripcion, p.imagen, p.estado,  
    um.nombre as nombre_medida, cp.nombre AS categoria
    FROM producto as p, unidad_medida AS um, categoria_producto AS cp
    WHERE p.idcategoria_producto = cp.idcategoria_producto and p.idunidad_medida = um.idunidad_medida 
    $tipo_categoria and p.estado='1' AND p.estado_delete='1' ORDER BY p.nombre ASC";
    return ejecutarConsulta($sql);
  }
  
  //OBTENEMOS LA IMAGEN PARA REEMPLAZARLO
  public function obtenerImg($idproducto) {
    $sql = "SELECT imagen FROM producto WHERE idproducto='$idproducto'";
    return ejecutarConsulta($sql);
  }

  // ══════════════════════════════════════  C A T E G O R I A S   P R O D U C T O  ══════════════════════════════════════

  public function lista_de_categorias(  )  {
    $sql = "SELECT * FROM categoria_producto WHERE estado = '1' AND estado_delete ='1';";
    return ejecutarConsultaArray($sql);
  }

  
}

?>
