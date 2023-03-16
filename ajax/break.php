<?php

	ob_start();

	if (strlen(session_id()) < 1){
		session_start();//Validamos si existe o no la sesión
	}
  
  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['viatico'] == 1) {

      require_once "../modelos/Break.php";

      $breaks=new Breaks();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      //============C o m p r o b a n t e s========================
      $idsemana_break   = isset($_POST["idsemana_break"])? limpiarCadena($_POST["idsemana_break"]):"";
      $idfactura_break  = isset($_POST["idfactura_break"])? limpiarCadena($_POST["idfactura_break"]):"";
      $forma_pago       = isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
      $tipo_comprobante = isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";

      $nro_comprobante  = isset($_POST["nro_comprobante"])? limpiarCadena($_POST["nro_comprobante"]):"";
      $monto            = isset($_POST["monto"])? limpiarCadena($_POST["monto"]):"";
      $fecha_emision    = isset($_POST["fecha_emision"])? limpiarCadena($_POST["fecha_emision"]):"";
      $descripcion      = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
      $subtotal         = isset($_POST["subtotal"])? limpiarCadena($_POST["subtotal"]):"";
      $igv              = isset($_POST["igv"])? limpiarCadena($_POST["igv"]):"";
      $val_igv          = isset($_POST["val_igv"])? limpiarCadena($_POST["val_igv"]):"";
      $tipo_gravada     = isset($_POST["tipo_gravada"])? limpiarCadena($_POST["tipo_gravada"]):"";
      
      $ruc              = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
      $razon_social     = isset($_POST["razon_social"]) ? limpiarCadena($_POST["razon_social"]) : "";
      $direccion        = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";

      $imagen2          = isset($_POST["doc1"])? limpiarCadena($_POST["doc1"]):"";

      switch ($_GET["op"]){

        case 'guardaryeditar':

          $rspta=$breaks->insertar_editar($_POST['array_break'],$_POST['fechas_semanas_btn'],$_POST['idproyecto']);
          
          echo json_encode($rspta);

        break;

        case 'listar_semana_botones':

          $rspta=$breaks->listarsemana_botones($_POST["nube_idproyecto"]);
          echo json_encode($rspta,true);	

        break;

        case 'ver_datos_semana':

          $rspta=$breaks->ver_detalle_semana_dias($_POST["f1"],$_POST["f2"],$_POST["nube_idproyect"]);
          echo json_encode($rspta,true);		

        break;

        case 'listar_totales_semana':

          $rspta=$breaks->listar($_GET['nube_idproyecto']);
          $data= Array();
          $cont = 1;
          if ($rspta['status']) {

            while ($reg=$rspta['data']->fetch_object()){ 

              $data[]=array(
                "0"=>$cont++,
                "1"=>'<div class="user-block">
                      <span style="font-weight: bold;" ><p class="text-primary"style="margin-bottom: 0.2rem !important"; > Semana. '.$reg->numero_semana.'</p></span>
                      <span style="font-weight: bold; font-size: 15px;">'.date("d/m/Y", strtotime($reg->fecha_inicial)).' - '.date("d/m/Y", strtotime($reg->fecha_final)).' </span>
                    </div>',
                "2"=>'<b>'.number_format($reg->total, 2, '.', ',').'</b>', 
                "3"=>'<div class="text-center"> <button class="btn btn-info btn-sm" onclick="listar_comprobantes('.$reg->idsemana_break.')"><i class="fas fa-file-invoice fa-lg btn-info nav-icon"></i></button></div>',   
                "4"=> "Semana".' '.$reg->numero_semana,
                "5"=>date("d/m/Y", strtotime($reg->fecha_inicial)),
                "6"=>date("d/m/Y", strtotime($reg->fecha_final))
              );

            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
              "aaData"=>$data);
            echo json_encode($results);

          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        
        break;

        // ------------------------------------------------------------------------------------
        // ------------------------------------------------------------------------------------   
        // ------------------C O M P R O B A N T E S   B R E A K -----------------------------
        // ------------------------------------------------------------------------------------
        // ------------------------------------------------------------------------------------

        case 'guardaryeditar_Comprobante':

            // imgen de perfil
          if (!file_exists($_FILES['doc1']['tmp_name']) || !is_uploaded_file($_FILES['doc1']['tmp_name'])) {
  
              $imagen2=$_POST["doc_old_1"]; $flat_img1 = false;
  
          } else {
  
              $ext1 = explode(".", $_FILES["doc1"]["name"]); $flat_img1 = true;						
  
              $imagen2 = $date_now .' '.random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
  
              move_uploaded_file($_FILES["doc1"]["tmp_name"], "../dist/docs/break/comprobante/" . $imagen2);
            
          }
        
          if (empty($idfactura_break)){
            
            $rspta=$breaks->insertar_comprobante($idsemana_break,$forma_pago,$tipo_comprobante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$val_igv,$tipo_gravada,$imagen2,$ruc,$razon_social,$direccion);
            echo json_encode($rspta,true);

          } else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {
  
              $datos_f1 = $breaks->obtenerDoc($idfactura_break);
        
              $img1_ant = $datos_f1['data']->fetch_object()->comprobante;
        
              if ($img1_ant != "") {
        
                unlink("../dist/docs/break/comprobante/" . $img1_ant);
              }
            }
            
            $rspta=$breaks->editar_comprobante($idfactura_break,$idsemana_break,$forma_pago,$tipo_comprobante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$val_igv,$tipo_gravada,$imagen2,$ruc,$razon_social,$direccion);
            
            echo json_encode($rspta,true);

          }

        break;
        
        case 'listar_comprobantes':

          $rspta=$breaks->listar_comprobantes($_GET['idsemana_break']);
          $data= Array();
          $comprobante='';
          $subtotal=0;
          $igv=0;
          $monto=0;
          $cont=1;

          if ($rspta['status']) {

            while ($reg=$rspta['data']->fetch_object()){

              $subtotal=round($reg->subtotal, 2); $igv=round($reg->igv, 2); $monto=round($reg->monto, 2 );

              if (strlen($reg->descripcion) >= 20 ) { $descripcion = substr($reg->descripcion, 0, 20).'...';  } else { $descripcion = $reg->descripcion; }

              empty($reg->comprobante)?$comprobante='<div><center><a type="btn btn-danger" class=""><i class="fas fa-file-invoice-dollar fa-2x text-gray-50"></i></a></center></div>':
              $comprobante='<div><center><a type="btn btn-danger" class=""  href="#" onclick="ver_modal_comprobante('."'".$reg->comprobante."'".','."'".$reg->tipo_comprobante."'".','."'".$reg->nro_comprobante."'".')"><i class="fas fa-file-invoice fa-2x"></i></a></center></div>';
              
              $tool = '"tooltip"';   $toltip = "<script> $(function () { $('[data-toggle=$tool]').tooltip(); }); </script>"; 
              
              $data[]=array(

                "0"=>$cont++,
                "1"=>'<button class="btn btn-warning btn-sm" onclick="mostrar_comprobante('.$reg->idfactura_break .')"><i class="fas fa-pencil-alt"></i></button>'.
                ' <button class="btn btn-danger  btn-sm" onclick="eliminar_comprobante(' . $reg->idfactura_break . ','."'".$reg->tipo_comprobante."'".','."'".$reg->nro_comprobante."'".')"><i class="fas fa-skull-crossbones"></i> </button>',
                "2"=> empty($reg->forma_de_pago)?' - ':$reg->forma_de_pago,
                "3"=>'<div class="user-block">
                    <span class="username" style="margin-left: 0px !important;"> <p class="text-primary" style="margin-bottom: 0.2rem !important";>'.$reg->tipo_comprobante.'</p> </span>
                    <span class="description" style="margin-left: 0px !important;">N° '.(empty($reg->nro_comprobante)?" - ":$reg->nro_comprobante).'</span>         
                  </div>',	
                "4"=>date("d/m/Y", strtotime($reg->fecha_emision)),
                "5"=>'S/ '.number_format($subtotal, 2, '.', ','), 
                "6"=>'S/ '.number_format($igv, 2, '.', ','),
                "7"=>'S/ '.number_format($monto, 2, '.', ','),
                "8"=>'<textarea cols="30" rows="1" class="textarea_datatable" readonly="">'.$reg->descripcion.'</textarea>',
                "9"=>$comprobante.''.$toltip,
                "10"=>$reg->ruc,
                "11"=>$reg->razon_social,
                "12"=>$reg->direccion,
                "13"=>$reg->tipo_comprobante,
                "14"=>$reg->nro_comprobante,
                "15"=>$reg->tipo_gravada,
                "16"=>$reg->glosa,
              
              );

            }

            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data
              );
            echo json_encode($results);

          } else {

            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }

        break;

        case 'desactivar_comprobante':

          $rspta=$breaks->desactivar_comprobante($_GET['id_tabla']);
          echo json_encode($rspta,true);
	
        break;

        case 'eliminar_comprobante':

          $rspta=$breaks->eliminar_comprobante($_GET['id_tabla']);
          echo json_encode($rspta,true);
	
        break;

        case 'mostrar_comprobante':

          $rspta=$breaks->mostrar_comprobante($idfactura_break);
          echo json_encode($rspta,true);

        break;

        case 'total_monto':

          $rspta=$breaks->total_monto_comp($idsemana_break);
          echo json_encode($rspta,true);

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