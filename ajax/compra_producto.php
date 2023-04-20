<?php
ob_start();
if (strlen(session_id()) < 1) {
  session_start(); //Validamos si existe o no la sesión
}

if (!isset($_SESSION["nombre"])) {
  $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
  echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
} else {

  if ($_SESSION['almacen_abono'] == 1) {
    
    require_once "../modelos/Compra_producto.php";
    require_once "../modelos/Persona.php";
    require_once "../modelos/Producto.php";
    require_once "../modelos/Lote.php";    

    $compra_producto  = new Compra_producto($_SESSION['idusuario']);
    $persona          = new Persona($_SESSION['idusuario']);
    $producto         = new Producto($_SESSION['idusuario']);  
    $lote             = new Lote($_SESSION['idusuario']);    
    
    date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");
    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
    $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_briment/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

    // :::::::::::::::::::::::::::::::::::: D A T O S   C O M P R A ::::::::::::::::::::::::::::::::::::::
    $idsucursal         = isset($_POST["idsucursal"]) ? limpiarCadena($_POST["idsucursal"]) : "";
    $idcompra_producto  = isset($_POST["idcompra_producto"]) ? limpiarCadena($_POST["idcompra_producto"]) : "";
    $idproveedor        = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
    $num_doc            = isset($_POST["num_doc"]) ? limpiarCadena($_POST["num_doc"]) : "";
    $fecha_compra       = isset($_POST["fecha_compra"]) ? limpiarCadena($_POST["fecha_compra"]) : "";
    $tipo_comprobante   = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";    
    $serie_comprobante  = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
    $val_igv            = isset($_POST["val_igv"]) ? limpiarCadena($_POST["val_igv"]) : "";
    $descripcion        = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
    $subtotal_compra    = isset($_POST["subtotal_compra"]) ? limpiarCadena($_POST["subtotal_compra"]) : "";
    $tipo_gravada       = isset($_POST["tipo_gravada"]) ? limpiarCadena($_POST["tipo_gravada"]) : "";    
    $total_descuento    = isset($_POST["total_descuento"]) ? limpiarCadena($_POST["total_descuento"]) : "";
    $igv_compra         = isset($_POST["igv_compra"]) ? limpiarCadena($_POST["igv_compra"]) : "";
    $total_compra       = isset($_POST["total_compra"]) ? limpiarCadena($_POST["total_compra"]) : "";

    // :::::::::::::::::::::::::::::::::::: D A T O S   P A G O   C O M P R A ::::::::::::::::::::::::::::::::::::::
    $beneficiario_pago  = isset($_POST["beneficiario_pago"]) ? limpiarCadena($_POST["beneficiario_pago"]) : "";
    $forma_pago         = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
    $tipo_pago          = isset($_POST["tipo_pago"]) ? limpiarCadena($_POST["tipo_pago"]) : "";
    $cuenta_destino_pago = isset($_POST["cuenta_destino_pago"]) ? limpiarCadena($_POST["cuenta_destino_pago"]) : "";
    $banco_pago         = isset($_POST["banco_pago"]) ? limpiarCadena($_POST["banco_pago"]) : "";
    $titular_cuenta_pago = isset($_POST["titular_cuenta_pago"]) ? limpiarCadena($_POST["titular_cuenta_pago"]) : "";
    $fecha_pago         = isset($_POST["fecha_pago"]) ? limpiarCadena($_POST["fecha_pago"]) : "";
    $monto_pago         = isset($_POST["monto_pago"]) ? limpiarCadena($_POST["monto_pago"]) : "";
    $numero_op_pago     = isset($_POST["numero_op_pago"]) ? limpiarCadena($_POST["numero_op_pago"]) : "";
    $descripcion_pago   = isset($_POST["descripcion_pago"]) ? limpiarCadena($_POST["descripcion_pago"]) : "";
    $idcompra_producto_p = isset($_POST["idcompra_producto_p"]) ? limpiarCadena($_POST["idcompra_producto_p"]) : "";
    $idpago_compras     = isset($_POST["idpago_compras"]) ? limpiarCadena($_POST["idpago_compras"]) : ""; 
    $idproveedor_pago   = isset($_POST["idproveedor_pago"]) ? limpiarCadena($_POST["idproveedor_pago"]) : "";
    $imagen1            = isset($_POST["doc3"]) ? limpiarCadena($_POST["doc3"]) : "";

    // :::::::::::::::::::::::::::::::::::: D A T O S   P R O D U C T O  ::::::::::::::::::::::::::::::::::::::
    $idproducto_pro       = isset($_POST["idproducto_pro"]) ? limpiarCadena($_POST["idproducto_pro"]) : "" ;
    $codigo_pro           = isset($_POST["codigo_pro"]) ? limpiarCadena($_POST["codigo_pro"]) : "" ;
    $nombre_producto_pro  = isset($_POST["nombre_producto_pro"]) ? limpiarCadena($_POST["nombre_producto_pro"]) : "" ;
    $laboratorio_pro      = isset($_POST["laboratorio_pro"]) ? limpiarCadena($_POST["laboratorio_pro"]) : "" ;
    $presentacion_pro     = isset($_POST["presentacion_pro"]) ? encodeCadenaHtml($_POST["presentacion_pro"]) : "" ;
    $unidad_medida_pro    = isset($_POST["unidad_medida_pro"]) ? encodeCadenaHtml($_POST["unidad_medida_pro"]) : "" ;
    $principio_activo_pro = isset($_POST["principio_activo_pro"]) ? encodeCadenaHtml($_POST["principio_activo_pro"]) : "" ;
    $descripcion_pro      = isset($_POST["descripcion_pro"]) ? encodeCadenaHtml($_POST["descripcion_pro"]) : "" ;
    $precio_actual_pro    = isset($_POST["precio_actual_pro"]) ? encodeCadenaHtml($_POST["precio_actual_pro"]) : "" ;
    $imagen2              = isset($_POST["foto2"]) ? limpiarCadena($_POST["foto2"]) : "" ;

    // :::::::::::::::::::::::::::::::::::: D A T O S   P R O V E E D O R ::::::::::::::::::::::::::::::::::::::
    $idpersona_per	  	  = isset($_POST["idpersona_per"])? limpiarCadena($_POST["idpersona_per"]):"";
    $id_tipo_persona_per 	= isset($_POST["id_tipo_persona_per"])? limpiarCadena($_POST["id_tipo_persona_per"]):"";
    $nombre_per 		      = isset($_POST["nombre_per"])? limpiarCadena($_POST["nombre_per"]):"";
    $tipo_documento_per 	= isset($_POST["tipo_documento_per"])? limpiarCadena($_POST["tipo_documento_per"]):"";
    $num_documento_per  	= isset($_POST["num_documento_per"])? limpiarCadena($_POST["num_documento_per"]):"";
    $direccion_per		    = isset($_POST["direccion_per"])? limpiarCadena($_POST["direccion_per"]):"";
    $telefono_per		      = isset($_POST["telefono_per"])? limpiarCadena($_POST["telefono_per"]):"";     
    $email_per			      = isset($_POST["email_per"])? limpiarCadena($_POST["email_per"]):"";
    
    $cargo_trabajador_per = isset($_POST["cargo_trabajador_per"])? limpiarCadena($_POST["cargo_trabajador_per"]):"";      
    $imagen1			        = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";

    // :::::::::::::::::::::::::::::::::::: D A T O S  L O T E ::::::::::::::::::::::::::::::::::::::    
    $idlote_lot           = isset($_POST["idlote_lot"]) ? limpiarCadena($_POST["idlote_lot"]) : "";
    $nombre_lot           = isset($_POST["nombre_lot"]) ? limpiarCadena($_POST["nombre_lot"]) : "";
    $fecha_vencimiento_lot= isset($_POST["fecha_vencimiento_lot"]) ? limpiarCadena($_POST["fecha_vencimiento_lot"]) : "";
    $descripcion_lot      = isset($_POST["descripcion_lot"]) ? limpiarCadena($_POST["descripcion_lot"]) : "";

    switch ($_GET["op"]) {   
      
      // :::::::::::::::::::::::::: S E C C I O N   M A T E R I A L E S ::::::::::::::::::::::::::
      case 'guardar_y_editar_productos':
        // imagen
        if (!file_exists($_FILES['foto2']['tmp_name']) || !is_uploaded_file($_FILES['foto2']['tmp_name'])) {
          $imagen2 = $_POST["foto2_actual"];
          $flat_img2 = false;
        } else {
          $ext1 = explode(".", $_FILES["foto2"]["name"]);
          $flat_img2 = true;
          $imagen2 = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
          move_uploaded_file($_FILES["foto2"]["tmp_name"], "../dist/docs/producto/img_perfil/" . $imagen2);
        }

        if (empty($idproducto_pro)) {

          $rspta = $producto->insertar($codigo_pro,$nombre_producto_pro,$laboratorio_pro,$presentacion_pro,$unidad_medida_pro,$principio_activo_pro,$descripcion_pro,$imagen2 );            
          echo json_encode( $rspta, true);
    
        } else {

          // validamos si existe LA IMG para eliminarlo          
          if ($flat_img2 == true) {
            $datos_f1 = $producto->obtenerImg($idproducto_pro);
            $img2_ant = $datos_f1['data']->fetch_object()->imagen;
            if ( !empty( $img2_ant ) ) { unlink("../dist/docs/producto/img_perfil/" . $img2_ant); }
          }
            
          $rspta = $producto->editar($idproducto_pro,$codigo_pro,$nombre_producto_pro,$laboratorio_pro,$presentacion_pro,$unidad_medida_pro,$principio_activo_pro, $descripcion_pro,$imagen2 );            
          echo json_encode( $rspta, true);
        }
    
      break;
    
      case 'mostrar_productos':
    
        $rspta = $producto->mostrar($idproducto_pro);
        echo json_encode($rspta, true);
    
      break;
        
      // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
      case 'guardar_proveedor':
    
        // imgen de perfil
        if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {
          $imagen1=$_POST["foto1_actual"]; $flat_img1 = false;
        } else {
          $ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;
          $imagen1 = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
          move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/persona/perfil/" . $imagen1);          
        }

        if (empty($idpersona_per)){

          $rspta=$persona->insertar($id_tipo_persona_per,$tipo_documento_per,$num_documento_per,$nombre_per,$email_per,$telefono_per,
          $direccion_per,'',$cargo_trabajador_per,0,0, $imagen1);
                      
          echo json_encode($rspta, true);
          
        }else{
          // validamos si existe LA IMG para eliminarlo
          if ($flat_img1 == true) {
            $datos_f1 = $persona->obtenerImg($idpersona_per);
            $img1_ant = $datos_f1['data']['foto_perfil'];
            if ( !empty($img1_ant) ) { unlink("../dist/docs/persona/perfil/" . $img1_ant);  }
          }           

          // editamos un persona existente
          $rspta=$persona->editar($idpersona_per, $id_tipo_persona_per,$tipo_documento_per,$num_documento_per,$nombre_per,$email_per,$telefono_per,
          $direccion_per,'',$cargo_trabajador_per,0,0, $imagen1);
            
          echo json_encode($rspta, true);
        }
    
      break;

      case 'mostrar_editar_proveedor':
        $rspta = $persona->mostrar($_POST["idproveedor"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
      break;
    
      // :::::::::::::::::::::::::: S E C C I O N   C O M P R A  ::::::::::::::::::::::::::
      case 'guardaryeditarcompra':

        if (empty($idcompra_producto)) {
          // $idcompra_producto,$idproveedor,$fecha_compra,$tipo_comprobante,$serie_comprobante,$val_igv,$descripcion,$subtotal_compra,$tipo_gravada,$igv_compra,$total_compra
          $rspta = $compra_producto->insertar($idsucursal, $idproveedor, $num_doc, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
          $total_compra, $subtotal_compra, $igv_compra, $total_descuento, $tipo_gravada, $_POST["idproducto"], $_POST["lote"], $_POST["unidad_medida"], $_POST["um_abreviatura"], 
          $_POST["presentacion"], $_POST["laboratorio"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST['precio_venta'], $_POST["descuento"] );

          echo json_encode($rspta, true);
        } else {

          $rspta = $compra_producto->editar( $idcompra_producto, $idsucursal, $idproveedor, $num_doc, $fecha_compra,  $tipo_comprobante, $serie_comprobante, $val_igv, $descripcion, 
          $total_compra, $subtotal_compra, $igv_compra, $total_descuento, $tipo_gravada, $_POST["idproducto"], $_POST["lote"], $_POST["unidad_medida"], $_POST["um_abreviatura"], 
          $_POST["presentacion"], $_POST["laboratorio"], $_POST["cantidad"], $_POST["precio_sin_igv"], $_POST["precio_igv"],  $_POST["precio_con_igv"], $_POST['precio_venta'], $_POST["descuento"]);
    
          echo json_encode($rspta, true);
        }
    
      break;      
      
      case 'anular':
        $rspta = $compra_producto->desactivar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'eliminar_compra':

        $rspta = $compra_producto->eliminar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;      
    
      case 'tbla_principal':
        $rspta = $compra_producto->tbla_principal($_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_proveedor"], $_GET["comprobante"]);
        
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status'] == true) {
          foreach ($rspta['data'] as $key => $reg) {

            $btn_tipo = (empty( $reg['comprobante'])) ? 'btn-outline-info' : 'btn-info';   
            $tooltip_comprobante = (empty( $reg['comprobante'])) ? 'data-toggle="tooltip" data-original-title="Vacío"' : 'data-toggle="tooltip" data-original-title="Comprobante"';

            $data[] = [
              "0" => $cont,
              "1" => '<button class="btn btn-info btn-sm p-x-5px" onclick="ver_detalle_compras(' . $reg['idcompra_producto'] .', \''.$reg['comprobante']. '\')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                ' <button class="btn bg-purple btn-sm p-x-5px" onclick="copiar_o_ver_editar_venta(' . $reg['idcompra_producto'] . ', 1)" data-toggle="tooltip" data-original-title="Copiar"><i class="fa-regular fa-copy"></i></button>' . 
                ' <button class="btn btn-warning btn-sm p-x-5px" onclick="copiar_o_ver_editar_venta(' . $reg['idcompra_producto'] . ', 2)" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger  btn-sm p-x-5px" onclick="eliminar_compra(' . $reg['idcompra_producto'] .', \''.encodeCadenaHtml('<del><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</del> <del>'.$reg['nombres'].'</del>'). '\')" data-toggle="tooltip" data-original-title="Eliminar o Papelera"><i class="fas fa-skull-crossbones"></i> </button>',                 
              "2" => $reg['fecha_compra'],
              "3" => '<span class="text-primary font-weight-bold" >' . $reg['nombres'] . '</span>',
              "4" =>'<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</span>' . $toltip ,
              "5" => $reg['total_compra'],
              "6" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg['descripcion'] . '</textarea>',
              "7" => '<button class="btn '.$btn_tipo.' btn-sm" onclick="comprobante_compra(' . $reg['idcompra_producto'] . ', \'' . $reg['comprobante'] . '\', \''. $reg['tipo_comprobante'].(empty($reg['serie_comprobante']) ?  "" :  ' - '.$reg['serie_comprobante']). '\')"  '.$tooltip_comprobante.' ><i class="fas fa-file-invoice fa-lg"></i></button>',
              
              "8" => $reg['tipo_comprobante'],
              "9" => $reg['serie_comprobante'],
              "10" => $reg['subtotal'],
              "11" => $reg['igv'],
              "12" => $reg['total_descuento'],
            ];
            $cont++;
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "data" => $data,
          ];
          echo json_encode($results, true);
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
    
      break;
    
      case 'listar_compra_x_porveedor':
        
        $rspta = $compra_producto->listar_compra_x_porveedor();
        //Vamos a declarar un array
        $data = []; $cont = 1;
        $c = "info";
        $nombre = "Ver";
        $info = "info";
        $icon = "eye";
        
        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => '<button class="btn btn-info btn-sm" onclick="listar_facuras_proveedor(' . $reg->idpersona . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
              "2" => $reg->razon_social,
              "3" => "<center>$reg->cantidad</center>",
              "4" => $reg->celular,
              "5" => $reg->total_compra, 
            ];
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
    
      break;
    
      case 'listar_detalle_compraxporvee':
        
        $rspta = $compra_producto->listar_detalle_comprax_provee($_GET["idproveedor"]);
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => '<center><button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg->idcompra_producto .', \''.$reg->comprobante. '\')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>',
              "2" => $reg->fecha_compra,
              "3" => $reg->tipo_comprobante,
              "4" => $reg->serie_comprobante,
              "5" => number_format($reg->total_compra, 2, '.', ','),
              "6" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg->descripcion.'</textarea>'
            ];
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }
    
      break;      
    
      case 'ver_compra_editar':

        $rspta = $compra_producto->ver_compra($idcompra_producto);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
    
      break;      

      // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  ::::::::::::::::::::::::::

      case 'guardar_y_editar_comprobante':     
      
        // imgen de perfil
        if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
          $doc1 = $_POST["doc_old_1"];
          $flat_comprob = false;
        } else {
          $ext1 = explode(".", $_FILES["doc1"]["name"]);
          $flat_comprob = true;
          $doc1 = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
          move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/compra_producto/comprobante_compra/" . $doc1);
        }
        
        //Borramos el comprobante
        if ($flat_comprob == true) {
          $datos_f1 = $compra_producto->obtener_comprobante($_POST["idcompra"]);
          $doc1_ant = $datos_f1['data']['comprobante'];
          if ( empty( $doc1_ant) ) {  unlink("../dist/docs/compra_producto/comprobante_compra/" . $doc1_ant); }
        }

        // editamos un documento existente
        $rspta = $compra_producto->editar_comprobante($_POST["idcompra"], $doc1);
        echo json_encode( $rspta, true);

      break;   

      // :::::::::::::::::::::::::: S E C C I O N   L O T E  ::::::::::::::::::::::::::
      case 'guardar_y_editar_lote':

        if (empty($idlote_lot)) {
          $rspta = $lote->insertar($nombre_lot, $fecha_vencimiento_lot,$descripcion_lot);
          echo json_encode( $rspta, true) ;
        } else {
          $rspta = $lote->editar($idlote_lot, $nombre_lot, $fecha_vencimiento_lot,$descripcion_lot);
          echo json_encode( $rspta, true) ;
        }
      break;

      default: 
        $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
      break;
    }

  } else {
    $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);
  }  
}

ob_end_flush();
?>
