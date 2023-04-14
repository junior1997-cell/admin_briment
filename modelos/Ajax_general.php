<?php 
  //Incluímos inicialmente la conexión a la base de datos
  require "../config/Conexion_v2.php";

  Class Ajax_general
  {
    //Implementamos nuestro variable global
    public $id_usr_sesion;

    //Implementamos nuestro constructor
    public function __construct($id_usr_sesion = 0)
    {
      $this->id_usr_sesion = $id_usr_sesion;
    }

    //CAPTURAR PERSONA  DE RENIEC 
    public function datos_reniec($dni) { 

      $url = "https://dniruc.apisperu.com/api/v1/dni/".$dni."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
      //  Iniciamos curl
      $curl = curl_init();
      // Desactivamos verificación SSL
      curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
      // Devuelve respuesta aunque sea falsa
      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
      // Especificamo los MIME-Type que son aceptables para la respuesta.
      curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );
      // Establecemos la URL
      curl_setopt( $curl, CURLOPT_URL, $url );
      // Ejecutmos curl
      $json = curl_exec( $curl );
      // Cerramos curl
      curl_close( $curl );

      $respuestas = json_decode( $json, true );

      return $respuestas;
    }

    //CAPTURAR PERSONA  DE RENIEC
    public function datos_sunat($ruc)	{ 
      $url = "https://dniruc.apisperu.com/api/v1/ruc/".$ruc."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
      //  Iniciamos curl
      $curl = curl_init();
      // Desactivamos verificación SSL
      curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
      // Devuelve respuesta aunque sea falsa
      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
      // Especificamo los MIME-Type que son aceptables para la respuesta.
      curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );
      // Establecemos la URL
      curl_setopt( $curl, CURLOPT_URL, $url );
      // Ejecutmos curl
      $json = curl_exec( $curl );
      // Cerramos curl
      curl_close( $curl );

      $respuestas = json_decode( $json, true );

      return $respuestas;    	

    }

    /* ══════════════════════════════════════ C O M P R O B A N T E  ══════════════════════════════════════ */

    //Implementamos un método para activar categorías
  public function autoincrement_comprobante() {
    $update_producto = "SELECT * FROM autoincrement_comprobante WHERE idautoincrement_comprobante = '1'";
		$val =  ejecutarConsultaSimpleFila($update_producto); if ( $val['status'] == false) {return $val; }   

		$compra_producto_f = empty($val['data']) ? 1 : (empty($val['data']['compra_producto_f']) ? 1 : (intval($val['data']['compra_producto_f']) +1)); 
    $compra_producto_b = empty($val['data']) ? 1 : (empty($val['data']['compra_producto_b']) ? 1 : (intval($val['data']['compra_producto_b']) +1));
    $compra_producto_nv = empty($val['data']) ? 1 : (empty($val['data']['compra_producto_nv']) ? 1 : (intval($val['data']['compra_producto_nv']) +1));

    $venta_producto_f =  empty($val['data']) ? 1 : (empty($val['data']['venta_producto_f']) ? 1 : (intval($val['data']['venta_producto_f']) +1)); 
    $venta_producto_b =  empty($val['data']) ? 1 : (empty($val['data']['venta_producto_b']) ? 1 : (intval($val['data']['venta_producto_b']) +1)); 
    $venta_producto_nv =  empty($val['data']) ? 1 : (empty($val['data']['venta_producto_nv']) ? 1 : (intval($val['data']['venta_producto_nv']) +1)); 

    $compra_cafe_f = empty($val['data']) ? 1 : (empty($val['data']['compra_cafe_f']) ? 1 : (intval($val['data']['compra_cafe_f']) +1));
    $compra_cafe_b = empty($val['data']) ? 1 : (empty($val['data']['compra_cafe_b']) ? 1 : (intval($val['data']['compra_cafe_b']) +1));
    $compra_cafe_nv = empty($val['data']) ? 1 : (empty($val['data']['compra_cafe_nv']) ? 1 : (intval($val['data']['compra_cafe_nv']) +1));

    $venta_cafe_f = empty($val['data']) ? 1 : (empty($val['data']['venta_cafe_f']) ? 1 : (intval($val['data']['venta_cafe_f']) +1));
    $venta_cafe_n = empty($val['data']) ? 1 : (empty($val['data']['venta_cafe_n']) ? 1 : (intval($val['data']['venta_cafe_n']) +1));
    $venta_cafe_nv = empty($val['data']) ? 1 : (empty($val['data']['venta_cafe_nv']) ? 1 : (intval($val['data']['venta_cafe_nv']) +1));

    return $sw = array( 'status' => true, 'message' => 'todo okey bro', 
      'data' => [
        'compra_producto_f'=> zero_fill($compra_producto_f, 6), 
        'compra_producto_b'=> zero_fill($compra_producto_b, 6), 
        'compra_producto_nv'=> zero_fill($compra_producto_nv, 6),

        'venta_producto_f'=> zero_fill($venta_producto_f, 6), 
        'venta_producto_b'=> zero_fill($venta_producto_b, 6), 
        'venta_producto_nv'=> zero_fill($venta_producto_nv, 6), 

        'compra_cafe_f'=> zero_fill($compra_cafe_f, 6), 
        'compra_cafe_b'=> zero_fill($compra_cafe_b, 6), 
        'compra_cafe_nv'=> zero_fill($compra_cafe_nv, 6), 

        'venta_cafe_f'=> zero_fill($venta_cafe_f, 6),
        'venta_cafe_n'=> zero_fill($venta_cafe_n, 6),
        'venta_cafe_nv'=> zero_fill($venta_cafe_nv, 6),
        
      ] 
    );      
  }

    /* ══════════════════════════════════════ T R A B A J A D O R ══════════════════════════════════════ */

    public function select2_trabajador(){
      $sql = "SELECT t.idtrabajador as id, t.nombres as nombre, t.tipo_documento as documento, t.sueldo_mensual, t.numero_documento, t.imagen_perfil, ct.nombre as cargo_trabajador
      FROM trabajador as t, cargo_trabajador as ct WHERE ct.idcargo_trabajador = t.idcargo_trabajador AND t.estado = '1' AND t.estado_delete = '1' ORDER BY t.nombres ASC;";
      return ejecutarConsultaArray($sql);
    }

    public function select2_cargo_trabajador() {
      $sql = "SELECT * FROM cargo_trabajador WHERE estado='1' AND estado_delete = '1' AND idcargo_trabajador > 1 ORDER BY nombre ASC";
      return ejecutarConsulta($sql);
    }
    
    /* ══════════════════════════════════════ C L I E N T E  ══════════════════════════════════════ */
    public function select2_cliente() {
      $sql = "SELECT p.idpersona, p.idtipo_persona, p.idbancos, p.nombres, p.es_socio, p.tipo_documento,  p.numero_documento, p.foto_perfil, tp.nombre as tipo_persona
      FROM persona AS p, tipo_persona as tp
      WHERE p.idtipo_persona = tp.idtipo_persona and p.idtipo_persona = 2 and p.estado='1' AND p.estado_delete = '1' AND p.idpersona > 1 ORDER BY p.nombres ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ TIPO PERSONA  ══════════════════════════════════════ */
    public function select2_tipo_persona() {
      $sql = "SELECT idtipo_persona, nombre, descripcion FROM tipo_persona WHERE estado='1' AND estado_delete = '1' AND idtipo_persona > 1 ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    

    /* ══════════════════════════════════════ P R O V E E D O R -- C L I E N T E S  ══════════════════════════════════════ */

    public function select2_proveedor_cliente($tipo) {
      $sql = "SELECT idpersona, nombres, tipo_documento, numero_documento, foto_perfil FROM persona 
      WHERE idtipo_persona ='$tipo' AND estado='1' AND estado_delete ='1'";

      return ejecutarConsulta($sql);
      // var_dump($return);die();
    }

    /* ══════════════════════════════════════ S U C U R S A L  ══════════════════════════════════════ */

    public function select2_sucursal() {
      $sql = "SELECT * FROM sucursal WHERE estado='1' AND estado_delete ='1'";

      return ejecutarConsulta($sql);
      // var_dump($return);die();
    }

    /* ══════════════════════════════════════ B A N C O ══════════════════════════════════════ */

    public function select2_banco() {
      $sql = "SELECT idbancos as id, nombre, alias, icono FROM bancos WHERE estado='1' AND estado_delete = '1' AND idbancos > 1 ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    public function formato_banco($idbanco){
      $sql="SELECT nombre, formato_cta, formato_cci, formato_detracciones FROM bancos WHERE estado='1' AND idbancos = '$idbanco';";
      return ejecutarConsultaSimpleFila($sql);		
    }

    /* ══════════════════════════════════════ C O L O R ══════════════════════════════════════ */

    public function select2_color() {
      $sql = "SELECT idcolor AS id, nombre_color AS nombre, hexadecimal FROM color WHERE idcolor > 1 AND estado='1' AND estado_delete = '1' ORDER BY nombre_color ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ U N I D A D   D E   M E D I D A ══════════════════════════════════════ */

    public function select2_unidad_medida() {
      $sql = "SELECT idunidad_medida AS id, nombre, abreviatura FROM unidad_medida WHERE estado='1' AND estado_delete = '1' AND idunidad_medida > 1 ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ C A T E G O R I A ══════════════════════════════════════ */

    public function select2_categoria() {
      $sql = "SELECT idcategoria_producto as id, nombre FROM categoria_producto WHERE estado='1' AND estado_delete = '1' AND idcategoria_producto > 1 ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    public function select2_categoria_all() {
      $sql = "SELECT idcategoria_producto as id, nombre FROM categoria_producto WHERE estado='1' AND estado_delete = '1' ORDER BY nombre ASC;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ T I P O   T I E R R A   C O N C R E T O ══════════════════════════════════════ */

    public function select2_tierra_concreto() {
      $sql = "SELECT idtipo_tierra_concreto as id, nombre, modulo FROM tipo_tierra_concreto  WHERE estado='1' AND estado_delete = '1' AND idtipo_tierra_concreto > 1  ORDER BY modulo ASC;";
      return ejecutarConsulta($sql);
    }


    //funcion para mostrar registros de prosuctos
    public function tblaProductos() {
      $sql = "SELECT p.idproducto, p.idunidad_medida, p.idlaboratorio,p.idpresentacion, p.nombre, p.descripcion,p.codigo, p.precio_actual, 
      p.descripcion, p.imagen, p.estado,um.nombre as unidad_medida, l.nombre as laboratorio, pre.nombre as presentacion
      FROM producto as p, unidad_medida as um, laboratorio as l, presentacion as pre 
      WHERE p.idunidad_medida =um.idunidad_medida AND p.idlaboratorio =l.idlaboratorio AND p.idpresentacion =pre.idpresentacion 
      and p.estado='1' AND p.estado_delete='1' ORDER BY p.nombre ASC";
      $producto = ejecutarConsultaArray($sql); if ( $producto['status'] == false) {return $producto; }  

      foreach ($producto['data'] as $key => $val) {
        $sql_1 = "SELECT SUM(dcp.cantidad) as cant_compra FROM detalle_compra_producto as dcp
        WHERE dcp.idproducto = 1 AND dcp.estado = '1' AND dcp.estado_delete = '1';";
        $compra = ejecutarConsultaSimpleFila($sql_1); if ( $compra['status'] == false) {return $compra; }  
  
        $sql_1 = "SELECT SUM(dvp.cantidad) as cant_venta FROM detalle_venta_producto as dvp
        WHERE dvp.idproducto = 1 AND dvp.estado = '1' AND dvp.estado_delete = '1';";
        $venta = ejecutarConsultaSimpleFila($sql_1); if ( $venta['status'] == false) {return $venta; }  
  
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
    /* ══════════════════════════════════════ S E R V i C I O S  M A Q U I N A RI A ════════════════════════════ */

    public function select2_servicio($tipo) {
      $sql = "SELECT mq.idmaquinaria as idmaquinaria, mq.nombre as nombre, mq.codigo_maquina as codigo_maquina, p.razon_social as nombre_proveedor, mq.idproveedor as idproveedor
      FROM maquinaria as mq, proveedor as p WHERE mq.idproveedor=p.idproveedor AND mq.estado='1' AND mq.estado_delete='1' AND mq.tipo=$tipo";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ E M P R E S A   A   C A R G O ══════════════════════════════════════ */
    public function select2_empresa_a_cargo() {
      $sql3 = "SELECT idempresa_a_cargo as id, razon_social as nombre, tipo_documento, numero_documento, logo FROM empresa_a_cargo WHERE estado ='1' AND estado_delete ='1' AND idempresa_a_cargo > 1 ;";
      return ejecutarConsultaArray($sql3);
    }

    /* ══════════════════════════════════════ M A R C A S   D E   A C T I V O S ════════════════════════════ */

    public function marcas_activos() {
      $sql = "SELECT idmarca, nombre_marca FROM marca WHERE estado=1 and estado_delete=1;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════ P R E S E N T A C I O N ════════════════════════════ */

    public function presentacion(){
      $sql = "SELECT idpresentacion, nombre FROM presentacion WHERE estado=1 AND estado_delete=1;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════  L A B O R A T O R I O  ════════════════════════════ */
    public function laboratorio(){
      $sql = "SELECT idlaboratorio, nombre FROM laboratorio WHERE estado=1 AND estado_delete=1;";
      return ejecutarConsulta($sql);
    }

    /* ══════════════════════════════════════  L O T E  ════════════════════════════ */
    public function select2_lote(){
      $sql = "SELECT idlote, nombre, stock, fecha_vencimiento, descripcion 
      FROM lote WHERE estado = '1' AND estado_delete = '1'";
      return ejecutarConsulta($sql);
    }

  }

?>