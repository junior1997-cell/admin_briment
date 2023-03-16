<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['grafico_valorizacion'] == 1) {
      
      require_once "../modelos/Chart_valorizacion.php";

      $chart_valorizacion = new ChartValorizacion();

      date_default_timezone_set('America/Lima');
      $date_now = date("d-m-Y h.i.s A");

      $idproducto = isset($_POST["idproducto"]) ? limpiarCadena($_POST["idproducto"]) : "";
      $idcategoria = isset($_POST["idcategoria_insumos_af"]) ? limpiarCadena($_POST["idcategoria_insumos_af"]) : "";

      switch ($_GET["op"]) {
        
        case 'box_content_reporte':
          $rspta = $chart_valorizacion->box_content_reporte($_POST["idnubeproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;
        break;

        case 'chart_linea':
          $rspta = $chart_valorizacion->chart_linea($_POST["idnubeproyecto"], $_POST["valorizacion_filtro"], $_POST["array_fechas_valorizacion"], $_POST["num_val"], $_POST["fecha_inicial"], $_POST["fecha_final"], $_POST["cant_valorizacion"]);
          //Codificar el resultado utilizando json
          //echo json_encode( json_decode( $_POST["array_fechas_valorizacion"], true), true) ;
          echo json_encode( $rspta, true) ;
        break;

        case 'listar_btn_q_s':
          $rspta=$chart_valorizacion->listar_btn_q_s($_POST["nube_idproyecto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true) ;	
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
