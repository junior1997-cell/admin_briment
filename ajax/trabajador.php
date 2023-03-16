<?php

  ob_start();

  if (strlen(session_id()) < 1) {

    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['recurso'] == 1) {

      require_once "../modelos/Trabajador.php";

      $trabajador = new Trabajador();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $imagen_error = "this.src='../dist/svg/user_default.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      
      $idtrabajador	  	= isset($_POST["idtrabajador"])? limpiarCadena($_POST["idtrabajador"]):"";
      $nombre 		      = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
      $tipo_documento 	= isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
      $num_documento  	= isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
      $direccion		    = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
      $telefono		      = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
      $nacimiento		    = isset($_POST["nacimiento"])? limpiarCadena($_POST["nacimiento"]):"";
      $edad		          = isset($_POST["edad"])? limpiarCadena($_POST["edad"]):"";      
      $email			      = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
      $banco            = isset($_POST["banco"])? $_POST["banco"] :"";     
      $cta_bancaria		  = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
      $cta_bancaria_format= isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
      $cci	          	= isset($_POST["cci"])?$_POST["cci"]:"";
      $cci_format      	= isset($_POST["cci"])? $_POST["cci"]:"";
      $titular_cuenta		= isset($_POST["titular_cuenta"])? limpiarCadena($_POST["titular_cuenta"]):"";
      $ruc	          	= isset($_POST["ruc"])? limpiarCadena($_POST["ruc"]):"";
      $idcargo_trabajador = isset($_POST["cargo_trabajador"])? limpiarCadena($_POST["cargo_trabajador"]):"";
      $sueldo_mensual   = isset($_POST["sueldo_mensual"])? limpiarCadena($_POST["sueldo_mensual"]):"";
      $sueldo_diario    = isset($_POST["sueldo_diario"])? limpiarCadena($_POST["sueldo_diario"]):"";
       
      $imagen1			    = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";
      switch ($_GET["op"]) {

        case 'guardaryeditar':

          // imgen de perfil
          if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {

						$imagen1=$_POST["foto1_actual"]; $flat_img1 = false;

					} else {

						$ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;						

            $imagen1 = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);

            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/trabajador/perfil/" . $imagen1);
						
					}

          if (empty($idtrabajador)){
            
            $rspta=$trabajador->insertar($idcargo_trabajador,$nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad,  $email, $banco, $cta_bancaria_format,  $cci_format,  $titular_cuenta, $ruc,$sueldo_mensual,$sueldo_diario, $imagen1);
            
            echo json_encode($rspta, true);
  
          }else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {
              $datos_f1 = $trabajador->obtenerImg($idtrabajador);
              $img1_ant = $datos_f1['data']['imagen_perfil'];
              if ($img1_ant != "") { unlink("../dist/docs/trabajador/perfil/" . $img1_ant);  }
            }            

            // editamos un trabajador existente
            $rspta=$trabajador->editar($idtrabajador,$idcargo_trabajador,$nombre, $tipo_documento, $num_documento, $direccion, $telefono, $nacimiento, $edad,  $email, $banco, $cta_bancaria_format,  $cci_format,  $titular_cuenta, $ruc,$sueldo_mensual,$sueldo_diario, $imagen1);
            
            echo json_encode($rspta, true);
          }            

        break;

        case 'desactivar':

          $rspta=$trabajador->desactivar($_GET["id_tabla"]);

          echo json_encode($rspta, true);

        break;

        case 'eliminar':

          $rspta=$trabajador->eliminar($_GET["id_tabla"]);

          echo json_encode($rspta, true);

        break;

        case 'mostrar':

          $rspta=$trabajador->mostrar($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;

        case 'tbla_principal':          

          $rspta=$trabajador->tbla_principal();
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $value) {             
          
              $data[]=array(
                "0"=>$cont++,
                "1"=>'<button class="btn btn-warning btn-sm" onclick="mostrar('.$value['idtrabajador'].')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>'.
                  ' <button class="btn btn-danger btn-sm" onclick="eliminar_trabajador('.$value['idtrabajador'].', \''.encodeCadenaHtml($value['nombres']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>'.
                  ' <button class="btn btn-info btn-sm" onclick="verdatos('.$value['idtrabajador'].')"data-toggle="tooltip" data-original-title="ver datos"><i class="far fa-eye"></i></button>',
                "2"=>'<div class="user-block">
                  <img class="img-circle" src="../dist/docs/trabajador/perfil/'. $value['imagen_perfil'] .'" alt="User Image" onerror="'.$imagen_error.'">
                  <span class="username"><p class="text-primary m-b-02rem" >'. $value['nombres'] .'</p></span>
                  <span class="description">'. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>
                  </div>',
                "3"=> $value['cargo'],
                "4"=> '<div>
                <span class="description">Mensual: <b>'. number_format($value['sueldo_mensual']) .'</b> </span><br>
                <span class="description">Diario: <b> '. $value['sueldo_diario'] .'</b> </span>
                </div>',
                "5"=>'<a href="tel:+51'.quitar_guion($value['telefono']).'" data-toggle="tooltip" data-original-title="Llamar al trabajador.">'. $value['telefono'] . '</a>',
                "6"=>format_d_m_a($value['fecha_nacimiento']).'<b>: </b>'. '<b>'.calculaedad($value['fecha_nacimiento']).'</b>' ,
                "7"=> '<b>'.$value['banco'] .': </b>'. $value['cuenta_bancaria'] .' <br> <b>CCI: </b>'.$value['cci'],
                "8"=>(($value['estado'])?'<span class="text-center badge badge-success">Activado</span>': '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
                "9"=> $value['nombres'],
                "10"=> $value['tipo_documento'],
                "11"=> $value['numero_documento'],
                "12"=> format_d_m_a($value['fecha_nacimiento']),
                "13"=>calculaedad($value['fecha_nacimiento']),
                "14"=> $value['banco'],
                "15"=> $value['cuenta_bancaria'],
                "16"=> $value['cci'],
                "17"=> number_format($value['sueldo_mensual']),
                "18"=> $value['sueldo_diario'],

              );
            }
            $results = array(
              "sEcho"=>1, //Información para el datatables
              "iTotalRecords"=>count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords"=>1, //enviamos el total registros a visualizar
              "data"=>$data);
            echo json_encode($results, true);

          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
        break;  

        case 'verdatos':
          $rspta=$trabajador->verdatos($idtrabajador);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;        

        case 'formato_banco':           
          $rspta=$trabajador->formato_banco($_POST["idbanco"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);           
        break;

        /* =========================== S E C C I O N   R E C U P E R A R   B A N C O S =========================== */
        case 'recuperar_banco':           
          $rspta=$trabajador->recuperar_banco();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);           
        break;

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;

      }

      //Fin de las validaciones de acceso
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

  function calculaedad($fechanacimiento){
    $ano_diferencia = '-';
    if (empty($fechanacimiento) || $fechanacimiento=='0000-00-00') { } else{
      list($ano,$mes,$dia) = explode("-",$fechanacimiento);
      $ano_diferencia  = date("Y") - $ano;
      $mes_diferencia = date("m") - $mes;
      $dia_diferencia   = date("d") - $dia;
      if ($dia_diferencia < 0 || $mes_diferencia < 0)
        $ano_diferencia--;
    } 
    
    return $ano_diferencia;
  }

  ob_end_flush();

?>
