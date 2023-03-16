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

      require_once "../modelos/Persona.php";

      $persona = new Persona();

      date_default_timezone_set('America/Lima'); $date_now = date("d-m-Y h.i.s A");

      $imagen_error = "this.src='../dist/svg/user_default.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';
      
      $idpersona	  	  = isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
      $id_tipo_persona 	= isset($_POST["id_tipo_persona"])? limpiarCadena($_POST["id_tipo_persona"]):"";
      $nombre 		      = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
      $tipo_documento 	= isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
      $num_documento  	= isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
      $input_socio     	= isset($_POST["input_socio"])? limpiarCadena($_POST["input_socio"]):"";
      $direccion		    = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
      $telefono		      = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";     
      $email			      = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
      $banco            = isset($_POST["banco"])? $_POST["banco"] :"";
      $cta_bancaria_format  = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
      $cta_bancaria     = isset($_POST["cta_bancaria"])?$_POST["cta_bancaria"]:"";
      $cci_format      	= isset($_POST["cci"])? $_POST["cci"]:"";
      $cci            	= isset($_POST["cci"])? $_POST["cci"]:"";
      $titular_cuenta		= isset($_POST["titular_cuenta"])? limpiarCadena($_POST["titular_cuenta"]):"";

      $nacimiento       = isset($_POST["nacimiento"])? limpiarCadena($_POST["nacimiento"]):"";
      $cargo_trabajador = isset($_POST["cargo_trabajador"])? limpiarCadena($_POST["cargo_trabajador"]):"";
      $sueldo_mensual   = isset($_POST["sueldo_mensual"])? limpiarCadena($_POST["sueldo_mensual"]):"";
      $sueldo_diario    = isset($_POST["sueldo_diario"])? limpiarCadena($_POST["sueldo_diario"]):"";
      $edad             = isset($_POST["edad"])? limpiarCadena($_POST["edad"]):"";
       
      $imagen1			    = isset($_POST["foto1"])? limpiarCadena($_POST["foto1"]):"";
      switch ($_GET["op"]) {

        case 'guardaryeditar':

          // imgen de perfil
          if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {
						$imagen1=$_POST["foto1_actual"]; $flat_img1 = false;
					} else {
						$ext1 = explode(".", $_FILES["foto1"]["name"]); $flat_img1 = true;
            $imagen1 = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/persona/perfil/" . $imagen1);						
					}

          if (empty($idpersona)){
            
            $rspta=$persona->insertar($id_tipo_persona,$tipo_documento,$num_documento,$nombre,$input_socio,$email,$telefono,$banco,$cta_bancaria,$cci,
            $titular_cuenta,$direccion,$nacimiento,$cargo_trabajador,$sueldo_mensual,$sueldo_diario,$edad, $imagen1);
            
            echo json_encode($rspta, true);
  
          }else {

            // validamos si existe LA IMG para eliminarlo
            if ($flat_img1 == true) {
              $datos_f1 = $persona->obtenerImg($idpersona);
              $img1_ant = $datos_f1['data']['foto_perfil'];
              if ( !empty($img1_ant) ) { unlink("../dist/docs/persona/perfil/" . $img1_ant);  }
            }            

            // editamos un persona existente
            $rspta=$persona->editar($idpersona,$id_tipo_persona,$tipo_documento,$num_documento,$nombre,$input_socio,$email,$telefono,$banco,$cta_bancaria,$cci,
            $titular_cuenta,$direccion,$nacimiento,$cargo_trabajador,$sueldo_mensual,$sueldo_diario,$edad, $imagen1);
            
            echo json_encode($rspta, true);
          }            

        break;

        case 'desactivar':

          $rspta=$persona->desactivar($_GET["id_tabla"]);

          echo json_encode($rspta, true);

        break;

        case 'eliminar':

          $rspta=$persona->eliminar($_GET["id_tabla"]);

          echo json_encode($rspta, true);

        break;

        case 'mostrar':

          $rspta=$persona->mostrar($idpersona);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);

        break;

        case 'tbla_principal':          

          $rspta=$persona->tbla_principal($_GET["tipo_persona"]);
          
          //Vamos a declarar un array
          $data= Array(); $cont=1;

          if ($rspta['status'] == true) {

            foreach ($rspta['data'] as $key => $value) {        

              $imagen = (empty($value['foto_perfil']) ? '../dist/svg/user_default.svg' : '../dist/docs/persona/perfil/'.$value['foto_perfil']) ;
              $edit_tipo = $_GET["tipo_persona"] == 'todos' ? '' : '<button class="btn btn-warning btn-sm" onclick="mostrar('.$value['idpersona'].')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' ;
              
              $data[]=array(
                "0"=>$cont++,
                "1"=>$edit_tipo.
                  ' <button class="btn btn-danger btn-sm" onclick="eliminar_persona('.$value['idpersona'].', \''.encodeCadenaHtml($value['nombres']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>',
                "2"=>'<div class="user-block">
                  <img class="profile-user-img img-responsive img-circle cursor-pointer" src="'. $imagen .'" alt="User Image" onerror="'.$imagen_error.'" onclick="ver_img_persona(\'' . $imagen . '\', \''.encodeCadenaHtml($value['nombres']).'\');" data-toggle="tooltip" data-original-title="Ver foto">
                  <span class="username"><p class="text-primary m-b-02rem" >'. $value['nombres'] .'</p></span>
                  <span class="description"><b>Cargo: </b>'. $value['cargo'] .' ─ <b>'. $value['tipo_documento'] .':</b> '. $value['numero_documento'] .' </span>
                </div>',
                "3"=> '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $value['direccion'] . '</textarea>',
                "4"=> '<a href="tel:+51'.quitar_guion($value['celular']).'" data-toggle="tooltip" data-original-title="Llamar al persona.">'. $value['celular'] . '</a>',
                "5"=> '<b>'.$value['banco'] .': </b>'. $value['cuenta_bancaria'] .' <br> <b>CCI: </b>'.$value['cci'],
                "6"=> (($value['estado'])?'<span class="text-center badge badge-success">Activado</span>': '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
                "7"=> $value['nombres'],
                "8"=> $value['tipo_documento'],
                "9"=> $value['numero_documento'],
                "10"=> $value['banco'],
                "11"=> $value['cuenta_bancaria'],
                "12"=> $value['cci']

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
          $rspta=$persona->verdatos($idpersona);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
        break;        

        case 'formato_banco':           
          $rspta=$persona->formato_banco($_POST["idbanco"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);           
        break;

        /* =========================== S E C C I O N   R E C U P E R A R   B A N C O S =========================== */
        case 'recuperar_banco':           
          $rspta=$persona->recuperar_banco();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);           
        break;
        /* =========================== S E C C I O N  T I P O   P E R S O N A  =========================== */
        case 'tipo_persona':

          $rspta=$persona->tipo_persona();
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

  ob_end_flush();

?>
