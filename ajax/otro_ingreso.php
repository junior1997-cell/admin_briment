<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['otro_ingreso'] == 1) {

      require_once "../modelos/Otro_ingreso.php";
      $otro_ingreso = new Otro_ingreso();
            
      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");   
      
      $idotro_ingreso = isset($_POST["idotro_ingreso"]) ? limpiarCadena($_POST["idotro_ingreso"]) : "";      
      $idpersona = isset($_POST["idpersona"]) ? limpiarCadena($_POST["idpersona"]) : "";  
      $fecha_i = isset($_POST["fecha_i"]) ? limpiarCadena($_POST["fecha_i"]) : "";
      $forma_pago = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
      $nro_comprobante = isset($_POST["nro_comprobante"]) ? limpiarCadena($_POST["nro_comprobante"]) : "";
      $subtotal = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
      $igv = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
      $val_igv          = isset($_POST["val_igv"])? limpiarCadena($_POST["val_igv"]):"";
      $tipo_gravada     = isset($_POST["tipo_gravada"])? limpiarCadena($_POST["tipo_gravada"]):"";  
      
      $precio_parcial = isset($_POST["precio_parcial"]) ? limpiarCadena($_POST["precio_parcial"]) : "";
      $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

      $ruc = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
      $razon_social = isset($_POST["razon_social"]) ? limpiarCadena($_POST["razon_social"]) : "";
      $direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";

      $foto2 = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";

    // :::::::::::::::::::::::::::::::::::: D A T O S   P E R S O N A ::::::::::::::::::::::::::::::::::::::

      $idpersona	  	  = isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
      $id_tipo_persona  = isset($_POST["idtipopersona"])? limpiarCadena($_POST["idtipopersona"]):"";
      $nombre 		      = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
      $tipo_documento 	= isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
      $num_documento  	= isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
      $direccion		    = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
      $telefono		      = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";  
      $banco            = isset($_POST["banco"])? $_POST["banco"] :"";
      $cta_bancaria_format  = isset($_POST["c_bancaria"])?$_POST["c_bancaria"]:"";
      $cta_bancaria     = isset($_POST["c_bancaria"])?$_POST["c_bancaria"]:"";
      $cci_format      	= isset($_POST["cci"])? $_POST["cci"]:"";
      $cci            	= isset($_POST["cci"])? $_POST["cci"]:"";
      $titular_cuenta		= isset($_POST["titular_cuenta"])? limpiarCadena($_POST["titular_cuenta"]):"";

      // $idpersona, $id_tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $banco, $cta_bancaria, $cci, $titular_cuenta

      switch ($_GET["op"]) {
        case 'guardar_y_editar_otros_ingresos':
          // Comprobante
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
      
            $comprobante = $_POST["doc_old_1"];
      
            $flat_ficha1 = false;
      
          } else {
      
            $ext1 = explode(".", $_FILES["doc1"]["name"]);
      
            $flat_ficha1 = true;
      
            $comprobante = $date_now .' '.random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
      
            move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/otro_ingreso/comprobante/" . $comprobante);
          }
      
          if (empty($idotro_ingreso)) {
            //var_dump($idproyecto,$idproveedor);
            $rspta = $otro_ingreso->insertar($idpersona, $fecha_i, $forma_pago, $tipo_comprobante, $nro_comprobante, $subtotal, $igv, $val_igv, $tipo_gravada, $precio_parcial, $descripcion, $comprobante);
            
            echo json_encode($rspta,true);
      
          } else {
            //validamos si existe comprobante para eliminarlo
            if ($flat_ficha1 == true) {
      
              $datos_ficha1 = $otro_ingreso->ficha_tec($idotro_ingreso);
      
              $ficha1_ant = $datos_ficha1['data']->fetch_object()->comprobante;
      
              if ($ficha1_ant != "") {
      
                unlink("../dist/docs/otro_ingreso/comprobante/" . $ficha1_ant);
              }
            }
      
            $rspta = $otro_ingreso->editar($idotro_ingreso,$idpersona, $fecha_i, $forma_pago, $tipo_comprobante, $nro_comprobante, $subtotal, $igv, $val_igv, $tipo_gravada, $precio_parcial, $descripcion, $comprobante);
            //var_dump($idotro_ingreso,$idproveedor);
            echo json_encode($rspta,true);
          }
        break;
      
        case 'desactivar':
      
          $rspta = $otro_ingreso->desactivar($_GET['id_tabla']);
      
          echo json_encode($rspta,true);
      
        break;

        case 'eliminar':
      
          $rspta = $otro_ingreso->eliminar($_GET['id_tabla']);
      
          echo json_encode($rspta,true);
      
        break;
      
        case 'mostrar':
      
          $rspta = $otro_ingreso->mostrar($idotro_ingreso);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
      
        case 'verdatos':
      
          $rspta = $otro_ingreso->mostrar($idotro_ingreso);
          //Codificar el resultado utilizando json
          echo json_encode($rspta,true);
      
        break;
      
        case 'tbla_principal':
          $rspta = $otro_ingreso->tbla_principal();
          //Vamos a declarar un array
          $data = [];
          $comprobante = '';
          $cont = 1;
          if ($rspta['status']) {
            while ($reg = $rspta['data']->fetch_object()) {

              empty($reg->comprobante)
                ? ($comprobante = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
                : ($comprobante = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante(' . "'" . $reg->comprobante . "'" . ',' . "'" . $reg->tipo_comprobante . "'" . ',' . "'" .(empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante). "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
              if (strlen($reg->descripcion) >= 20) {
                $descripcion = substr($reg->descripcion, 0, 20) . '...';
              } else {
                $descripcion = $reg->descripcion;
              }
              $tool = '"tooltip"';
              $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>";
              $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idotro_ingreso . ')"><i class="fas fa-pencil-alt"></i></button>' .
                    ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg->idotro_ingreso . ',' . "'" . $reg->tipo_comprobante . "'" . ',' . "'" . (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante). "'". ')"><i class="fas fa-skull-crossbones"></i> </button>'.
                    ' <button class="btn btn-info btn-sm" onclick="ver_datos(' . $reg->idotro_ingreso . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
                "2" =>'<div class="user-block">
                  <span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>' .
                  ((empty($reg->nombres)) ? 'Sin Razón social' : $reg->nombres ) .
                  '</p> </span>
                    <span class="description" style="margin-left: 0px !important;">N° ' .
                  (empty($reg->numero_documento) ? "Sin Ruc" : $reg->numero_documento) .
                  '</span>         
                  </div>',
                "3" => $reg->forma_de_pago,
                "4" =>'<div class="user-block">
                    <span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>' .
                  $reg->tipo_comprobante .
                  '</p> </span>
                    <span class="description" style="margin-left: 0px !important;">N° ' .
                  (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante) .
                  '</span>         
                  </div>',
                "5" => $reg->fecha_ingreso,
                "6" =>$reg->precio_sin_igv,
                "7" =>$reg->precio_igv,
                "8" =>$reg->precio_con_igv,
                "9" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg->descripcion . '</textarea>',
                "10" => $comprobante. $toltip,
                "11"=>$reg->numero_documento,
                "12"=>$reg->nombres,
                "13"=>$reg->direccion,
                "14"=>$reg->tipo_comprobante,
                "15"=>$reg->numero_comprobante,
                "16"=>$reg->tipo_gravada
              ];
            }
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
            echo json_encode($results);
          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break;
      
        case 'total':
          $rspta = $otro_ingreso->total();
          echo json_encode($rspta,true);
        break;
      
        case 'selecct_produc_o_provee':

          $rspta = $otro_ingreso->selecct_produc_o_provee(); $cont = 1; $data = "";

          if ($rspta['status']) {
  
            foreach ($rspta['data'] as $key => $value) {  

                $data .= '<option value=' .$value['idpersona']. '>'.( !empty($value['nombres']) ?  $value['tipo'].' : '.$value['nombres'].' - ' : '') .$value['numero_documento'].'</option>';
    
            }
  
            $retorno = array(
              'status' => true, 
              'message' => 'Salió todo ok', 
              'data' => '<option value="vacio">Sin proveedor</option>'.$data, 
            );
    
            echo json_encode($retorno, true);
  
          } else {
  
            echo json_encode($rspta, true); 
          }

        break;

        case 'select_tipo_persona':
          $rspta = $otro_ingreso->select_tipo_persona(); $cont = 1; $data = "";

          if ($rspta['status']) {
  
            foreach ($rspta['data'] as $key => $value) {  

                $data .= '<option value=' .$value['idtipo_persona']. '>'.( !empty($value['nombre']) ?  $value['nombre'] : '') .'</option>';
    
            }
  
            $retorno = array(
              'status' => true, 
              'message' => 'Salió todo ok', 
              'data' => $data, 
            );
    
            echo json_encode($retorno, true);
  
          } else {
  
            echo json_encode($rspta, true); 
          }
        break;

                
      // :::::::::::::::::::::::::: S E C C I O N   P R O V E E D O R  ::::::::::::::::::::::::::
      case 'guardarpersona':
    
        if (empty($idpersona)){

          $rspta=$otro_ingreso->insertar_persona($id_tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $banco, $cta_bancaria, $cci, $titular_cuenta);
          echo json_encode($rspta, true);
          
        }else{
    
          echo "error";
        }
    
      break;

        case 'salir':
          //Limpiamos las variables de sesión
          session_unset();
          //Destruìmos la sesión
          session_destroy();
          //Redireccionamos al login
          header("Location: ../index.php");      
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
