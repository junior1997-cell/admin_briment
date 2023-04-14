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
    
    require_once "../modelos/Compra_grano.php";
    require_once "../modelos/Persona.php";

    $compra_grano = new Compra_grano($_SESSION['idusuario']);
    $cliente = new Persona($_SESSION['idusuario']);
    
    date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");
    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost//' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

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
    $idpersona_cli	  	  = isset($_POST["idpersona_cli"])? limpiarCadena($_POST["idpersona_cli"]):"";
    $id_tipo_persona_cli 	= isset($_POST["id_tipo_persona_cli"])? limpiarCadena($_POST["id_tipo_persona_cli"]):"";
    $nombre_cli 		      = isset($_POST["nombre_cli"])? limpiarCadena($_POST["nombre_cli"]):"";
    $tipo_documento_cli 	= isset($_POST["tipo_documento_cli"])? limpiarCadena($_POST["tipo_documento_cli"]):"";
    $num_documento_cli  	= isset($_POST["num_documento_cli"])? limpiarCadena($_POST["num_documento_cli"]):"";
    $input_socio_cli     	= isset($_POST["input_socio_cli"])? limpiarCadena($_POST["input_socio_cli"]):"";
    $direccion_cli		    = isset($_POST["direccion_cli"])? limpiarCadena($_POST["direccion_cli"]):"";
    $telefono_cli		      = isset($_POST["telefono_cli"])? limpiarCadena($_POST["telefono_cli"]):"";     
    $email_cli			      = isset($_POST["email_cli"])? limpiarCadena($_POST["email_cli"]):"";
    $banco_cli            = isset($_POST["banco_cli"])? $_POST["banco_cli"] :"";
    $cta_bancaria_format_cli  = isset($_POST["cta_bancaria_cli"])?$_POST["cta_bancaria_cli"]:"";
    $cta_bancaria_cli     = isset($_POST["cta_bancaria_cli"])?$_POST["cta_bancaria_cli"]:"";
    $cci_format_cli      	= isset($_POST["cci_cli"])? $_POST["cci_cli"]:"";
    $cci_cli            	= isset($_POST["cci_cli"])? $_POST["cci_cli"]:"";
    $titular_cuenta_cli		= isset($_POST["titular_cuenta_cli"])? limpiarCadena($_POST["titular_cuenta_cli"]):"";

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

        if (empty($idpersona_cli)){
          
          $rspta=$cliente->insertar($id_tipo_persona_cli, $tipo_documento_cli, $num_documento_cli, $nombre_cli, $input_socio_cli, $email_cli, $telefono_cli, $banco_cli, $cta_bancaria_cli, $cci_cli, $titular_cuenta_cli, $direccion_cli,  $imagen1);          
          echo json_encode($rspta, true);

        }else {

          // validamos si existe LA IMG para eliminarlo
          if ($flat_img1 == true) {
            $datos_f1 = $cliente->obtenerImg($idpersona_cli);
            $img1_ant = $datos_f1['data']['foto_perfil'];
            if ($img1_ant != "") { unlink("../dist/docs/persona/perfil/" . $img1_ant);  }
          }            

          // editamos un persona existente
          $rspta=$cliente->editar($idpersona_cli,$id_tipo_persona_cli, $tipo_documento_cli, $num_documento_cli, $nombre_cli, $input_socio_cli, $email_cli, $telefono_cli, $banco_cli, $cta_bancaria_cli, $cci_cli, $titular_cuenta_cli, $direccion_cli,  $imagen1);          
          echo json_encode($rspta, true);
        }
    
      break;

      case 'mostrar_editar_cliente':
        $rspta = $cliente->mostrar($_POST["idcliente"]);
        //Codificar el resultado utilizando json
        echo json_encode($rspta, true);
      break;
    
      // :::::::::::::::::::::::::: S E C C I O N   C O M P R A  ::::::::::::::::::::::::::
      case 'guardar_y_editar_compra_grano':

        if (empty($idcompra_grano)) {

          $rspta = $compra_grano->insertar( $idcliente, $ruc_dni_cliente, $fecha_compra,  $tipo_comprobante, $numero_comprobante, 
          $descripcion, $metodo_pago, quitar_formato_miles($monto_pago_compra), $fecha_proximo_pago, $subtotal_compra, $val_igv, $igv_compra, $total_compra, $tipo_gravada, $_POST["tipo_grano"], $_POST["unidad_medida"], $_POST["peso_bruto"], 
          $_POST["dcto_humedad"], $_POST["porcentaje_cascara"], $_POST["dcto_embase"], $_POST["peso_neto"], $_POST["precio_sin_igv"],
          $_POST["precio_igv"], $_POST["precio_con_igv"], $_POST["descuento"], $_POST["subtotal_producto"] );

          echo json_encode($rspta, true);
        } else {

          $rspta = $compra_grano->editar( $idcompra_grano, $idcliente, $ruc_dni_cliente, $fecha_compra, $tipo_comprobante, $numero_comprobante, 
          $descripcion, $metodo_pago, $fecha_proximo_pago, $subtotal_compra, $val_igv, $igv_compra, $total_compra, $tipo_gravada, $_POST["tipo_grano"], $_POST["unidad_medida"], $_POST["peso_bruto"], 
          $_POST["dcto_humedad"], $_POST["porcentaje_cascara"], $_POST["dcto_embase"], $_POST["peso_neto"], $_POST["precio_sin_igv"],
          $_POST["precio_igv"], $_POST["precio_con_igv"], $_POST["descuento"], $_POST["subtotal_producto"] );
    
          echo json_encode($rspta, true);
        }
    
      break;      
      
      case 'anular':
        $rspta = $compra_grano->desactivar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'des_anular':
        $rspta = $compra_grano->activar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;

      case 'eliminar_compra':

        $rspta = $compra_grano->eliminar($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      case 'tbla_principal':
        $rspta = $compra_grano->tbla_principal( $_GET["fecha_1"], $_GET["fecha_2"], $_GET["id_cliente"], $_GET["comprobante"]);
        
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
        
        $rspta = $compra_grano->tabla_compra_x_cliente();
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
        
        $rspta = $compra_grano->listar_detalle_comprax_provee($_GET["idcliente"]);
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
    
      case 'ver_detalle_compras_grano':
        
        $rspta = $compra_grano->mostrar_compra_para_editar($_GET['idcompra_grano']);

        $es_socio = $rspta['data']['cliente'] ? 'SOCIO': 'NO SOCIO';    

        $inputs = '<!-- Tipo de Empresa -->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="idproveedor">Proveedor</label>
              <h5 class="form-control form-control-sm" >'.$rspta['data']['cliente'].' - '.$rspta['data']['numero_documento'].' - '.$es_socio.'</h5>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Fecha </label>
              <span class="form-control form-control-sm"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;&nbsp;'.format_d_m_a($rspta['data']['fecha_compra']).' </span>
            </div>
          </div>
          <!-- fecha -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="fecha_compra">Método de pago </label>
              <span class="form-control form-control-sm">'.$rspta['data']['metodo_pago'].' </span>
            </div>
          </div>
          <!-- Tipo de comprobante -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="font-size-15px" for="tipo_comprovante">Tipo Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['tipo_comprobante'])) ? '- - -' :  $rspta['data']['tipo_comprobante'])  .' </span>
            </div>
          </div>
          <!-- serie_comprovante-->
          <div class="col-lg-2">
            <div class="form-group">
              <label class="font-size-15px" for="serie_comprovante">N° de Comprobante</label>
              <span  class="form-control form-control-sm"> '. ((empty($rspta['data']['numero_comprobante'])) ? '- - -' :  $rspta['data']['numero_comprobante']).' </span>
            </div>
          </div>
          <!-- IGV-->
          <div class="col-lg-1 " >
            <div class="form-group">
              <label class="font-size-15px" for="igv">IGV</label>
              <span class="form-control form-control-sm"> '.$rspta['data']['val_igv'].' </span>                                 
            </div>
          </div>
          <!-- Descripcion-->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="font-size-15px" for="descripcion">Descripción </label> <br />
              <textarea class="form-control form-control-sm" readonly rows="1">'.((empty($rspta['data']['descripcion'])) ? '- - -' :$rspta['data']['descripcion']).'</textarea>
            </div>
        </div>';

        $tbody = ""; $cont = 1;

        foreach ($rspta['data']['detalle_compra'] as $key => $reg) {
          
          $tbody .= '<tr class="filas">
            <td class="text-center p-6px">' . $cont++ . '</td>
            <td class="text-left p-6px">' . $reg['tipo_grano'] . '</td>
            <td class="text-center p-6px">'. $reg['unidad_medida'] . '</td>
            <td class="text-right p-6px">' . $reg['peso_bruto'] . '</td>
            <td class="text-right p-6px">' . $reg['dcto_humedad'] . '</td>	
            <td class="text-right p-6px">' . $reg['porcentaje_cascara'] . '</td>	
            <td class="text-right p-6px">' . $reg['dcto_embase'] . '</td>	
            <td class="text-right p-6px">' . number_format($reg['peso_neto'], 2, '.',',') . '</td>
            <td class="text-right p-6px">' . number_format($reg['precio_sin_igv'], 2, '.',',') . '</td>
            <td class="text-right p-6px">' . number_format($reg['precio_igv'], 2, '.',',') . '</td>
            <td class="text-right p-6px">' . number_format($reg['precio_con_igv'], 2, '.',',') . '</td>
            <td class="text-right p-6px">' . number_format($reg['descuento_adicional'], 2, '.',',') .'</td>
            <td class="text-right p-6px">' . number_format($reg['subtotal'], 2, '.',',') .'</td>
          </tr>';
        }         

        $tabla_detalle = '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover" id="tabla_detalle_factura">
            <thead style="background-color:#00821e80;">
              <tr class="text-center hidden">
                <th class="p-10px">Proveedor:</th>
                <th class="text-center p-10px" colspan="12" >'.$rspta['data']['cliente'].'</th>
              </tr>
              <tr class="text-center hidden">                
                <th class="text-center p-10px" colspan="3" >'.((empty($rspta['data']['tipo_comprobante'])) ? '' :  $rspta['data']['tipo_comprobante']). ' ─ ' . ((empty($rspta['data']['numero_comprobante'])) ? '' :  $rspta['data']['numero_comprobante']) .'</th>
                <th class="p-10px">Fecha:</th>
                <th class="text-center p-10px" colspan="3" >'.format_d_m_a($rspta['data']['fecha_compra']).'</th>
                <th class="p-10px">Metodo de pago:</th>
                <th class="text-center p-10px" colspan="3" >'.$rspta['data']['metodo_pago'].'</th>
              </tr>
              <tr class="text-center">
                <th rowspan="2" class="p-y-2px" data-toggle="tooltip" data-original-title="Opciones">#</th>
                <th rowspan="2" class="p-y-2px">Tipo Grano</th>
                <th rowspan="2" class="p-y-2px">Unidad</th>
                <th rowspan="2" class="p-y-2px">Peso Bruto</th>
                <th colspan="3" class="p-y-2px">Descuento en KG</th>
                <th rowspan="2" class="p-y-2px">Peso Neto</th>
                <th rowspan="2" class="p-y-2px" data-toggle="tooltip" data-original-title="Valor Unitario" >V/U</th>
                <th rowspan="2" class="p-y-2px">IGV</th>
                <th rowspan="2" class="p-y-2px" data-toggle="tooltip" data-original-title="Precio Unitario">P/U</th>
                <th rowspan="2" class="p-y-2px">Descuento <br> <small>(adicional)</small></th>
                <th rowspan="2" class="p-y-2px">Subtotal</th>
              </tr>

              <tr class="text-center">
                <th class="p-y-1px" >Humedad</th>
                <th class="p-y-1px" >Cascara</th>
                <th class="p-y-1px" >Embase</th>
              </tr>
            </thead>
            <tbody>'.$tbody.'</tbody>          
            <tfoot>
              <tr>
                  <td class="p-0" colspan="11"></td>
                  <td class="p-0 text-right"> <h6 class="mt-1 mb-1 mr-1">'.$rspta['data']['tipo_gravada'].'</h6> </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['subtotal_compra'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="11"></td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1">IGV('.( ( empty($rspta['data']['val_igv']) ? 0 : floatval($rspta['data']['val_igv']) )  * 100 ).'%)</h6>
                  </td>
                  <td class="p-0 text-right">
                    <h6 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['igv_compra'], 2, '.',',') . '</h6>
                  </td>
                </tr>
                <tr>
                  <td class="p-0" colspan="11"></td>
                  <td class="p-0 text-right"> <h5 class="mt-1 mb-1 mr-1 font-weight-bold">TOTAL</h5> </td>
                  <td class="p-0 text-right">
                    <h5 class="mt-1 mb-1 mr-1 pl-1 font-weight-bold text-nowrap formato-numero-conta"><span>S/</span>' . number_format($rspta['data']['total_compra'], 2, '.',',') . '</h5>
                  </td>
                </tr>
            </tfoot>
          </table>
        </div> ';
        $retorno = ['status' => true, 'message' => 'todo oka', 'data' => $inputs . $tabla_detalle ,];
        echo json_encode( $retorno, true );

      break;
    
      case 'ver_compra_editar':

        $rspta = $compra_grano->mostrar_compra_para_editar($idcompra_grano);
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
          
          $rspta=$compra_grano->crear_pago_compra(  $idcompra_grano_p, $forma_pago_p, $fecha_pago_p, $monto_p, $descripcion_p, $comprobante_pago);          
          echo json_encode($rspta, true);

        }else {

          // validamos si existe LA IMG para eliminarlo
          if ($flat_doc1 == true) {
            $doc_pago = $compra_grano->obtener_doc_pago_compra($idpago_compra_grano_p);
            $doc_pago_antiguo = $doc_pago['data']['comprobante'];
            if ($doc_pago_antiguo != "") { unlink("../dist/docs/compra_grano/comprobante_pago/" . $doc_pago_antiguo);  }
          }            

          // editamos un persona existente
          $rspta=$compra_grano->editar_pago_compra( $idpago_compra_grano_p, $idcompra_grano_p, $forma_pago_p, $fecha_pago_p, $monto_p, $descripcion_p, $comprobante_pago );          
          echo json_encode($rspta, true);
        }
    
      break;

      case 'tabla_pago_compras':
        
        $rspta = $compra_grano->tabla_pago_compras($_GET["idcompra_grano"]);
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
        $rspta = $compra_grano->papelera_pago_compra($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;    
      
      case 'eliminar_pago_compra':

        $rspta = $compra_grano->eliminar_pago_compra($_GET["id_tabla"]);
    
        echo json_encode($rspta, true);
    
      break;

      case 'mostrar_editar_pago':

        $rspta = $compra_grano->mostrar_editar_pago($_POST["idpago_compra_grano"]);
    
        echo json_encode($rspta, true);
    
      break;
    
      // ::::::::::::::::::::::::::::::::::::::::: S I N C R O N I Z A R  :::::::::::::::::::::::::::::::::::::::::
      case 'sincronizar_comprobante':

        $rspta = $compra_grano->sincronizar_comprobante();
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
