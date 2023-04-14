<?php
ob_start();
if (strlen(session_id()) < 1) {
  session_start(); //Validamos si existe o no la sesión
}

if (!isset($_SESSION["nombre"])) {
  $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
  echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
} else {

  if ($_SESSION['compra_grano'] == 1) {
    
    require_once "../modelos/Compra_cafe.php";
    require_once "../modelos/Persona.php";

    $compra_cafe = new Compra_cafe($_SESSION['idusuario']);
    $persona = new Persona($_SESSION['idusuario']);
    
    date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");
    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_briment/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

    // :::::::::::::::::::::::::::::::::::: D A T O S   C O M P R A ::::::::::::::::::::::::::::::::::::::
    $idcompra_grano     = isset($_POST["idcompra_grano"]) ? limpiarCadena($_POST["idcompra_grano"]) : "";
    $idcliente          = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
    $ruc_dni_cliente    = isset($_POST["ruc_dni_cliente"]) ? limpiarCadena($_POST["ruc_dni_cliente"]) : "";
    $fecha_compra       = isset($_POST["fecha_compra"]) ? limpiarCadena($_POST["fecha_compra"]) : "";
    $establecimiento    = isset($_POST["establecimiento"]) ? limpiarCadena($_POST["establecimiento"]) : "";
    $tipo_comprobante   = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";    
    $numero_comprobante = isset($_POST["numero_comprobante"]) ? limpiarCadena($_POST["numero_comprobante"]) : "";    
    $descripcion        = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
    $metodo_pago        = isset($_POST["metodo_pago"]) ? limpiarCadena($_POST["metodo_pago"]) : "";
    $monto_pago_compra  = isset($_POST["monto_pago_compra"]) ? limpiarCadena($_POST["monto_pago_compra"]) : "";
    $fecha_proximo_pago = isset($_POST["fecha_proximo_pago"]) ? limpiarCadena($_POST["fecha_proximo_pago"]) : "";

    $subtotal_compra    = isset($_POST["subtotal_compra"]) ? limpiarCadena($_POST["subtotal_compra"]) : "";
    $val_igv            = isset($_POST["val_igv"]) ? limpiarCadena($_POST["val_igv"]) : "";  
    $igv_compra         = isset($_POST["igv_compra"]) ? limpiarCadena($_POST["igv_compra"]) : "";
    $total_compra       = isset($_POST["total_compra"]) ? limpiarCadena($_POST["total_compra"]) : "";
    $tipo_gravada       = isset($_POST["tipo_gravada"]) ? limpiarCadena($_POST["tipo_gravada"]) : "";    

    // :::::::::::::::::::::::::::::::::::: D A T O S   P A G O   C O M P R A ::::::::::::::::::::::::::::::::::::::
    $idpago_compra_grano_p  = isset($_POST["idpago_compra_grano_p"]) ? limpiarCadena($_POST["idpago_compra_grano_p"]) : "";
    $idcompra_grano_p       = isset($_POST["idcompra_grano_p"]) ? limpiarCadena($_POST["idcompra_grano_p"]) : "";  
    $forma_pago_p           = isset($_POST["forma_pago_p"]) ? limpiarCadena($_POST["forma_pago_p"]) : "";
    $fecha_pago_p           = isset($_POST["fecha_pago_p"]) ? limpiarCadena($_POST["fecha_pago_p"]) : "";
    $monto_p                = isset($_POST["monto_p"]) ? limpiarCadena($_POST["monto_p"]) : "";  
    $descripcion_p          = isset($_POST["descripcion_p"]) ? limpiarCadena($_POST["descripcion_p"]) : "";     

    // :::::::::::::::::::::::::::::::::::: D A T O S   C L I E N T E ::::::::::::::::::::::::::::::::::::::
    $idpersona_per	  	  = isset($_POST["idpersona_per"])? limpiarCadena($_POST["idpersona_per"]):"";
    $id_tipo_persona_per 	= isset($_POST["id_tipo_persona_per"])? limpiarCadena($_POST["id_tipo_persona_per"]):"";
    $nombre_per 		      = isset($_POST["nombre_per"])? limpiarCadena($_POST["nombre_per"]):"";
    $tipo_documento_per 	= isset($_POST["tipo_documento_per"])? limpiarCadena($_POST["tipo_documento_per"]):"";
    $num_documento_per  	= isset($_POST["num_documento_per"])? limpiarCadena($_POST["num_documento_per"]):"";
    $input_socio_per     	= isset($_POST["input_socio_per"])? limpiarCadena($_POST["input_socio_per"]):"";
    $direccion_per		    = isset($_POST["direccion_per"])? limpiarCadena($_POST["direccion_per"]):"";
    $telefono_per		      = isset($_POST["telefono_per"])? limpiarCadena($_POST["telefono_per"]):"";     
    $email_per			      = isset($_POST["email_per"])? limpiarCadena($_POST["email_per"]):"";
    
    $banco                = isset($_POST["banco"])? $_POST["banco"] :"";
    $cta_bancaria_format  = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
    $cta_bancaria         = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
    $cci_format      	    = isset($_POST["cci"])? $_POST["cci"]:"";
    $cci            	    = isset($_POST["cci"])? $_POST["cci"]:"";
    $titular_cuenta_per		= isset($_POST["titular_cuenta_per"])? limpiarCadena($_POST["titular_cuenta_per"]):"";

    $nacimiento_per       = isset($_POST["nacimiento_per"])? limpiarCadena($_POST["nacimiento_per"]):"";
    $cargo_trabajador_per = isset($_POST["cargo_trabajador_per"])? limpiarCadena($_POST["cargo_trabajador_per"]):"";
    $sueldo_mensual_per   = isset($_POST["sueldo_mensual_per"])? limpiarCadena($_POST["sueldo_mensual_per"]):"";
    $sueldo_diario_per    = isset($_POST["sueldo_diario_per"])? limpiarCadena($_POST["sueldo_diario_per"]):"";
    $edad_per             = isset($_POST["edad_per"])? limpiarCadena($_POST["edad_per"]):"";
      
    $imagen1			        = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";

    switch ($_GET["op"]) {       
        
      // :::::::::::::::::::::::::: S E C C I O N   C L I E N T E  ::::::::::::::::::::::::::
      case 'guardar_y_editar_cliente':
    
        // imgen de perfil
        if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {
          $imagen1=$_POST["foto1_actual"]; $flat_img1 = false;
        } else {
          $ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;
          $imagen1 = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
          move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/persona/perfil/" . $imagen1);          
        }

        if (empty($idpersona_per)){

          $rspta=$persona->insertar($id_tipo_persona_per,$tipo_documento_per,$num_documento_per,$nombre_per,$input_socio_per,$email_per,$telefono_per,$banco,$cta_bancaria,$cci,
            $titular_cuenta_per,$direccion_per,$nacimiento_per,$cargo_trabajador_per,$sueldo_mensual_per,$sueldo_diario_per,$edad_per, $imagen1);
                      
          echo json_encode($rspta, true);
          
        }else{
          // validamos si existe LA IMG para eliminarlo
          if ($flat_img1 == true) {
            $datos_f1 = $persona->obtenerImg($idpersona_per);
            $img1_ant = $datos_f1['data']['foto_perfil'];
            if ( !empty($img1_ant) ) { unlink("../dist/docs/persona/perfil/" . $img1_ant);  }
          }           

          // editamos un persona existente
          $rspta=$persona->editar($idpersona_per,$id_tipo_persona_per,$tipo_documento_per,$num_documento_per,$nombre_per,$input_socio_per,$email_per,$telefono_per,$banco,$cta_bancaria,$cci,
            $titular_cuenta_per,$direccion_per,$nacimiento_per,$cargo_trabajador_per,$sueldo_mensual_per,$sueldo_diario_per,$edad_per, $imagen1);
            
          echo json_encode($rspta, true);
        }
    
      break;

      case 'mostrar_editar_cliente':
        $rspta = $persona->mostrar($_POST["idcliente"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
      break;
    
      // :::::::::::::::::::::::::: S E C C I O N   C O M P R A  ::::::::::::::::::::::::::
      case 'guardar_y_editar_compra_grano':

        if (empty($idcompra_grano)) {

          $rspta = $compra_cafe->insertar( $idcliente, $ruc_dni_cliente, $fecha_compra,  $tipo_comprobante, $numero_comprobante, 
          $descripcion, $metodo_pago, quitar_formato_miles($monto_pago_compra), $fecha_proximo_pago, $subtotal_compra, $val_igv, $igv_compra, $total_compra, $tipo_gravada, $_POST["tipo_grano"], $_POST["unidad_medida"], $_POST["peso_bruto"], 
          $_POST["sacos"], $_POST["dcto_humedad"], $_POST["dcto_rendimiento"], $_POST["dcto_segunda"], $_POST["dcto_cascara"], $_POST["dcto_taza"], $_POST["dcto_tara"], 
          $_POST["peso_neto"], $_POST["quintal_neto"], 
          $_POST["precio_sin_igv"], $_POST["precio_igv"], $_POST["precio_con_igv"], $_POST["descuento"], $_POST["subtotal_producto"] );

          echo json_encode($rspta, true);
        } else {

          $rspta = $compra_cafe->editar( $idcompra_grano, $idcliente, $ruc_dni_cliente, $fecha_compra, $tipo_comprobante, $numero_comprobante, 
          $descripcion, $metodo_pago, $fecha_proximo_pago, $subtotal_compra, $val_igv, $igv_compra, $total_compra, $tipo_gravada, $_POST["tipo_grano"], $_POST["unidad_medida"], $_POST["peso_bruto"], 
          $_POST["dcto_humedad"], $_POST["dcto_cascara"], $_POST["dcto_tara"], $_POST["peso_neto"], $_POST["precio_sin_igv"],
          $_POST["precio_igv"], $_POST["precio_con_igv"], $_POST["descuento"], $_POST["subtotal_producto"] );
    
          echo json_encode($rspta, true);
        }
    
      break;      
      
      case 'anular':
        $rspta = $compra_cafe->desactivar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'des_anular':
        $rspta = $compra_cafe->activar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;

      case 'eliminar_compra':

        $rspta = $compra_cafe->eliminar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'tbla_principal':
        $rspta = $compra_cafe->tbla_principal( $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_cliente"], $_GET["comprobante"]);
        
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status'] == true) {
          foreach ($rspta['data'] as $key => $reg) {                          
            $saldo = $reg['total_compra'] - $reg['total_pago'];
            
            if ($saldo == $reg['total_compra']) {
              $estado = '<span class="text-center badge badge-danger">Sin pagar</span>';
              $color_btn = "danger"; $nombre = "Pagar"; $icon = "dollar-sign";
            } else if ($saldo < $reg['total_compra'] && $saldo > "0") {              
              $estado = '<span class="text-center badge badge-warning">En proceso</span>';
              $color_btn = "warning"; $nombre = "Pagar"; $icon = "dollar-sign";
            } else if ($saldo <= "0" || $saldo == "0") {              
              $estado = '<span class="text-center badge badge-success">Pagado</span>';
              $color_btn = "success"; $nombre = "Ver"; $icon = "eye";
            } else {
              $estado = '<span class="text-center badge badge-success">Error</span>';               
            }           

            $data[] = [
              "0" => $cont,
              "1" => '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg['idcompra_grano'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
                ' <button class="btn bg-purple btn-sm" onclick="copiar_venta(' . $reg['idcompra_grano'] . ')" data-toggle="tooltip" data-original-title="copiar"><i class="fa-regular fa-copy"></i></button>' . 
                '<!-- <button class="btn btn-warning btn-sm" onclick="ver_compra_editar(' . $reg['idcompra_grano'] . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button> -->' .                  
                ' <button class="btn btn-danger  btn-sm" onclick="eliminar_compra(' . $reg['idcompra_grano'] .', \''.encodeCadenaHtml('<del><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['numero_comprobante']) ?  "" :  '- '.$reg['numero_comprobante']).'</del> <del>'.$reg['cliente'].'</del>'). '\')" data-toggle="tooltip" data-original-title="Papelera o Eliminar"><i class="fas fa-skull-crossbones"></i> </button>' . $toltip ,
              "2" => $reg['fecha_compra'],
              "3" => '<span class="text-primary font-weight-bold" >' . $reg['cliente'] . '</span>',
              "4" => $reg['es_socio'],
              "5" =>'<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['numero_comprobante']) ?  "" :  '- '.$reg['numero_comprobante']).'</span>',              
              "6" => $reg['metodo_pago'],              
              "7" => $reg['total_compra'],
              "8" => '<div class="text-center text-nowrap">'.
                '<button class="btn btn-' . $color_btn . ' btn-xs m-t-2px" onclick="tbla_pago_compra(' . $reg['idcompra_grano'] . ', ' . $reg['total_compra'] . ', ' . floatval($reg['total_pago']) .', \''.encodeCadenaHtml($reg['cliente']) .'\')" data-toggle="tooltip" data-original-title="Ingresar a pagos"> <i class="fas fa-' . $icon . ' nav-icon"></i> ' . $nombre . '</button>' . 
                ' <button style="font-size: 14px;" class="btn btn-' . $color_btn . ' btn-sm">' . number_format(floatval($reg['total_pago']), 2, '.', ',') . '</button>'.
              '</div>',
              "9" => $saldo,

              "10" => $reg['tipo_documento'],
              "11" => $reg['numero_documento'],
              "12" => $reg['tipo_comprobante'],
              "13" => $reg['numero_comprobante'],
              "14" => $reg['total_pago'],
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
    
      case 'tabla_compra_x_cliente':
        
        $rspta = $compra_cafe->tabla_compra_x_cliente();
        //Vamos a declarar un array
        $data = []; $cont = 1;
        $c = "info";
        $nombre = "Ver";
        $info = "info";
        $icon = "eye";
        
        if ($rspta['status'] == true) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => '<button class="btn btn-info btn-sm" onclick="listar_facuras_cliente(' . $reg->idpersona .', \''.$reg->nombres. '\')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
              "2" => $reg->nombres,
              "3" => "<center>$reg->cantidad</center>",
              "4" => '<a href="tel:+51'.quitar_guion($reg->celular).'">'.$reg->celular.'</a>' ,
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
    
      case 'listar_detalle_compra_x_cliente':
        
        $rspta = $compra_cafe->listar_detalle_comprax_provee($_GET["idcliente"]);
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status'] == true) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => '<center><button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg->idcompra_grano . ')" data-toggle="tooltip" data-original-title="Ver detalle">Ver detalle <i class="fa fa-eye"></i></button></center>',
              "2" => $reg->fecha_compra,
              "3" => $reg->tipo_comprobante,
              "4" => $reg->numero_comprobante,
              "5" => $reg->total_compra,
              "6" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg->descripcion.'</textarea>',
              "7" => $reg->estado == '1' ? '<span class="badge bg-success">Aceptado</span>' : '<span class="badge bg-danger">Anulado</span>',
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

        $rspta = $compra_cafe->mostrar_compra_para_editar($idcompra_grano);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
    
      break;      
      
      // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  :::::::::::::::::::::::::: 


      // :::::::::::::::::::::::::: S E C C I O N   P A G O  ::::::::::::::::::::::::::     
      case 'guardar_y_editar_pago_compra':
    
        // imgen de perfil
        if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
          $comprobante_pago = $_POST["doc_old_1"]; $flat_doc1 = false;
        } else {
          $ext1 = explode(".", $_FILES["doc1"]["name"]); $flat_doc1 = true;	
          $comprobante_pago  = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
          move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/compra_grano/comprobante_pago/" . $comprobante_pago );          
        }

        if (empty($idpago_compra_grano_p)){
          
          $rspta=$compra_cafe->crear_pago_compra(  $idcompra_grano_p, $forma_pago_p, $fecha_pago_p, $monto_p, $descripcion_p, $comprobante_pago);          
          echo json_encode($rspta, true);

        }else {

          // validamos si existe LA IMG para eliminarlo
          if ($flat_doc1 == true) {
            $doc_pago = $compra_cafe->obtener_doc_pago_compra($idpago_compra_grano_p);
            $doc_pago_antiguo = $doc_pago['data']['comprobante'];
            if ($doc_pago_antiguo != "") { unlink("../dist/docs/compra_grano/comprobante_pago/" . $doc_pago_antiguo);  }
          }            

          // editamos un persona existente
          $rspta=$compra_cafe->editar_pago_compra( $idpago_compra_grano_p, $idcompra_grano_p, $forma_pago_p, $fecha_pago_p, $monto_p, $descripcion_p, $comprobante_pago );          
          echo json_encode($rspta, true);
        }
    
      break;

      case 'tabla_pago_compras':
        
        $rspta = $compra_cafe->tabla_pago_compras($_GET["idcompra_grano"]);
        //Vamos a declarar un array
        $data = []; $cont = 1;
        
        if ($rspta['status'] == true) {
          while ($reg = $rspta['data']->fetch_object()) {
            $doc = (empty($reg->comprobante) ? '<a href="#" class="btn btn-sm btn-outline-info" data-toggle="tooltip" data-original-title="Vacio" ><i class="fa-regular fa-file-pdf fa-2x"></i></a>' : '<a href="#" class="btn btn-sm btn-info" data-toggle="tooltip" data-original-title="Ver documento" onclick="ver_documento_pago(\''.$reg->comprobante. '\', \'' . removeSpecialChar($reg->cliente) . ' - ' .date("d/m/Y", strtotime($reg->fecha_pago)).'\')"><i class="fa-regular fa-file-pdf fa-2x"></i></a>');
            $data[] = [
              "0" => $cont++,
              "1" => '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras_activo_fijo(' . $reg->idpago_compra_grano . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' .
              ' <button class="btn btn-sm btn-warning" id="btn_monto_pagado_' . $reg->idpago_compra_grano . '" monto_pagado="'.$reg->monto.'" onclick="mostrar_editar_pago(' . $reg->idpago_compra_grano . ')" data-toggle="tooltip" data-original-title="Editar compra"><i class="fas fa-pencil-alt"></i></button>' .
              ' <button class="btn btn-sm btn-danger" onclick="eliminar_pago_compra(' . $reg->idpago_compra_grano .', \''.encodeCadenaHtml( number_format($reg->monto, 2, '.',',')).' - '.date("d/m/Y", strtotime($reg->fecha_pago)).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>',
              "2" => $reg->fecha_pago,
              "3" => $reg->forma_pago,
              "4" => $reg->monto,
              "5" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly >'.$reg->descripcion.'</textarea>',
              "6" => $doc,
              "7" => $reg->estado == '1' ? '<span class="badge bg-success">Aceptado</span>' : '<span class="badge bg-danger">Anulado</span>',
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
      
      case 'papelera_pago_compra':
        $rspta = $compra_cafe->papelera_pago_compra($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;    
      
      case 'eliminar_pago_compra':

        $rspta = $compra_cafe->eliminar_pago_compra($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;

      case 'mostrar_editar_pago':

        $rspta = $compra_cafe->mostrar_editar_pago($_POST["idpago_compra_grano"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      // ::::::::::::::::::::::::::::::::::::::::: S I N C R O N I Z A R  :::::::::::::::::::::::::::::::::::::::::
      case 'sincronizar_comprobante':

        $rspta = $compra_cafe->sincronizar_comprobante();
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);

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
