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
      require_once "../modelos/Persona.php";

      $otro_ingreso = new Otro_ingreso($_SESSION['idusuario']);
      $persona = new Persona($_SESSION['idusuario']);
            
      date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");   
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      
      $idotro_ingreso   = isset($_POST["idotro_ingreso"]) ? limpiarCadena($_POST["idotro_ingreso"]) : "";      
      $idpersona        = isset($_POST["idpersona"]) ? limpiarCadena($_POST["idpersona"]) : "";  
      $fecha_i          = isset($_POST["fecha_i"]) ? limpiarCadena($_POST["fecha_i"]) : "";
      $forma_pago       = isset($_POST["forma_pago"]) ? limpiarCadena($_POST["forma_pago"]) : "";
      $tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
      $nro_comprobante  = isset($_POST["nro_comprobante"]) ? limpiarCadena($_POST["nro_comprobante"]) : "";
      $subtotal         = isset($_POST["subtotal"]) ? limpiarCadena($_POST["subtotal"]) : "";
      $igv              = isset($_POST["igv"]) ? limpiarCadena($_POST["igv"]) : "";
      $val_igv          = isset($_POST["val_igv"])? limpiarCadena($_POST["val_igv"]):"";
      $tipo_gravada     = isset($_POST["tipo_gravada"])? limpiarCadena($_POST["tipo_gravada"]):"";  
      
      $precio_parcial   = isset($_POST["precio_parcial"]) ? limpiarCadena($_POST["precio_parcial"]) : "";
      $descripcion      = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

      $ruc              = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
      $razon_social     = isset($_POST["razon_social"]) ? limpiarCadena($_POST["razon_social"]) : "";
      $direccion        = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";

      $foto2            = isset($_POST["doc1"]) ? limpiarCadena($_POST["doc1"]) : "";

      // :::::::::::::::::::::::::::::::::::: D A T O S   P E R S O N A ::::::::::::::::::::::::::::::::::::::

      $idpersona_per	  	  = isset($_POST["idpersona_per"])? limpiarCadena($_POST["idpersona_per"]):"";
      $id_tipo_persona_per 	= isset($_POST["id_tipo_persona_per"])? limpiarCadena($_POST["id_tipo_persona_per"]):"";
      $nombre_per 		      = isset($_POST["nombre_per"])? limpiarCadena($_POST["nombre_per"]):"";
      $tipo_documento_per 	= isset($_POST["tipo_documento_per"])? limpiarCadena($_POST["tipo_documento_per"]):"";
      $num_documento_per  	= isset($_POST["num_documento_per"])? limpiarCadena($_POST["num_documento_per"]):"";      
      $direccion_per		    = isset($_POST["direccion_per"])? limpiarCadena($_POST["direccion_per"]):"";
      $telefono_per		      = isset($_POST["telefono_per"])? limpiarCadena($_POST["telefono_per"]):"";     
      $email_per			      = isset($_POST["email_per"])? limpiarCadena($_POST["email_per"]):"";
      
      $nacimiento_per       = isset($_POST["nacimiento_per"])? limpiarCadena($_POST["nacimiento_per"]):"";
      $cargo_trabajador_per = isset($_POST["cargo_trabajador_per"])? limpiarCadena($_POST["cargo_trabajador_per"]):"";
      $sueldo_mensual_per   = isset($_POST["sueldo_mensual_per"])? limpiarCadena($_POST["sueldo_mensual_per"]):"";
      $sueldo_diario_per    = isset($_POST["sueldo_diario_per"])? limpiarCadena($_POST["sueldo_diario_per"]):"";
      $edad_per             = isset($_POST["edad_per"])? limpiarCadena($_POST["edad_per"]):"";
      
      $imagen1			        = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";

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
          $data = [];  $comprobante = '';   $cont = 1;

          if ($rspta['status'] == true) {
            while ($reg = $rspta['data']->fetch_object()) {

              empty($reg->comprobante)
              ? ($comprobante = '<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>')
              : ($comprobante = '<div><center><a type="btn btn-danger" class=""  href="#" onclick="modal_comprobante(' . "'" . $reg->comprobante . "'" . ',' . "'" . $reg->tipo_comprobante . "'" . ',' . "'" .(empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante). "'" . ')"><i class="fas fa-file-invoice-dollar fa-2x"></i></a></center></div>');
                            
              $data[] = [
                "0" => $cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->idotro_ingreso . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar(' . $reg->idotro_ingreso . ',' . "'" . $reg->tipo_comprobante . "'" . ',' . "'" . (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante). "'". ')"><i class="fas fa-skull-crossbones"></i> </button>'.
                  ' <button class="btn btn-info btn-sm" onclick="ver_datos(' . $reg->idotro_ingreso . ')" data-toggle="tooltip" data-original-title="Ver detalle"><i class="fa fa-eye"></i></button>',
                "2" => $reg->fecha_ingreso,
                "3" =>'<div class="user-block">
                  <span class="username ml-0"><p class="text-primary m-b-02rem" >'.((empty($reg->nombres)) ? 'Sin Razón social' : $reg->nombres ).'</p> </span>
                  <span class="description ml-0" ><b>' . $reg->tipo_documento .  ': </b> '.(empty($reg->numero_documento) ? "Sin Ruc" : $reg->numero_documento) .'</span>         
                </div>',
                "4" => $reg->forma_de_pago,
                "5" =>'<span><b class="text-primary" >'.$reg->tipo_comprobante.' - </b>' . (empty($reg->numero_comprobante) ? " - " : $reg->numero_comprobante) . '</span>',                
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
        case 'guardar_persona':
      
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
          $direccion_per,$nacimiento_per,$cargo_trabajador_per,$sueldo_mensual_per,$sueldo_diario_per, $imagen1);
          
          echo json_encode($rspta, true);

        }else {

          // validamos si existe LA IMG para eliminarlo
          if ($flat_img1 == true) {
            $datos_f1 = $persona->obtenerImg($idpersona_per);
            $img1_ant = $datos_f1['data']['foto_perfil'];
            if ( !empty($img1_ant) ) { unlink("../dist/docs/persona/perfil/" . $img1_ant);  }
          }            

          // editamos un persona existente
          $rspta=$persona->editar($idpersona_per,$id_tipo_persona_per,$tipo_documento_per,$num_documento_per,$nombre_per,$email_per,$telefono_per,
          $direccion_per,$nacimiento_per,$cargo_trabajador_per,$sueldo_mensual_per,$sueldo_diario_per, $imagen1);
          
          echo json_encode($rspta, true);
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
