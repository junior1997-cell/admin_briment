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
    if ($_SESSION['escritorio'] == 1) {

      require_once "../modelos/Escritorio.php";

      $escritorio = new Escritorio();

      date_default_timezone_set('America/Lima'); $date_now = date("d-m-Y h.i.s A");

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/front_jdl/admin/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');

      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      $idproyecto				    = isset($_POST["idproyecto"])? limpiarCadena($_POST["idproyecto"]):""; 
      $tipo_documento			  = isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";

      switch ($_GET["op"]){        

        case 'tablero':
          $rspta=$escritorio->tablero();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	
        break;

        case 'chart_producto':
          $rspta=$escritorio->chart_producto();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	
        break;

        case 'sumas_totales':
          $rspta=$escritorio->sumas_totales();
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);	
        break;

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;
        
      }

    }else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

  ob_end_flush();
?>