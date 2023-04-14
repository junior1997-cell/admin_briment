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
    if ($_SESSION['calendario'] == 1) {

      require_once "../modelos/Calendario.php";

      $calendario = new Calendario($_SESSION['idusuario']);

      $idcalendario		  = isset($_POST["idcalendario"])? limpiarCadena($_POST["idcalendario"]):"";
      $idproyecto 		  = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):"";
      $fecha_feriado	  = isset($_POST["fecha_feriado"])? limpiarCadena($_POST["fecha_feriado"]):"";
      $text_color	      = isset($_POST["text_color"])? limpiarCadena($_POST["text_color"]):"";
      $titulo		        = isset($_POST["titulo"])? limpiarCadena($_POST["titulo"]):"";
      $background_color = isset($_POST["background_color"])? limpiarCadena($_POST["background_color"]):"";
      $descripcion	  	= isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
      

      switch ($_GET["op"]) {

        case 'guardaryeditar':          

          if (empty($idcalendario)){

            $rspta=$calendario->insertar($idproyecto, $titulo, $descripcion, $fecha_feriado, $background_color, $text_color);
            
            echo $rspta ? "ok" : "No se pudieron registrar todos los datos";
  
          }else {

            // editamos un trabajador existente
            $rspta=$calendario->editar($idcalendario, $idproyecto, $titulo, $descripcion, $fecha_feriado, $background_color, $text_color);
            
            echo $rspta ? "ok" : "Fecha no se pudo actualizar";
          }            

        break;

        case 'desactivar':

          $rspta=$calendario->desactivar($idcalendario);

 				  echo $rspta ? "Usuario Desactivado" : "calendario no se puede desactivar";

        break;

        case 'activar':

          $rspta=$calendario->activar($idcalendario);

 				  echo $rspta ? "Usuario activado" : "calendario no se puede activar";

        break; 
        
        case 'desactivar_domingo':

          $rspta=$calendario->desactivar_domingo($idproyecto);

 				  echo $rspta ? "Domingo Desactivado" : "Domingo no se puede desactivar";

        break;

        case 'activar_domingo':

          $rspta=$calendario->activar_domingo($idproyecto);

 				  echo $rspta ? "Domingo activado" : "Domingo no se puede activar";

        break;

        case 'listar-calendario':          

          $rspta=$calendario->listar($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);         

        break;
        case 'listar-calendario-e':          

          $rspta=$calendario->listar_e($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);         

        break;

        case 'estado_domingo':          

          $rspta=$calendario->estado_domingo($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);         

        break;

        case 'detalle_dias_proyecto':          

          $rspta=$calendario->detalle_dias_proyecto($idproyecto);
          //Codificar el resultado utilizando json
          echo json_encode($rspta);         

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
