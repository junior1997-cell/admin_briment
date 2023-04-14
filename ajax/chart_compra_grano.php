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
      
      require_once "../modelos/Chart_compra_grano.php";

      $chart_compra_grano = new ChartCompraGrano($_SESSION['idusuario']);

      date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");

      $idproducto = isset($_POST["idproducto"]) ? limpiarCadena($_POST["idproducto"]) : "";
      $idcategoria = isset($_POST["idcategoria_insumos_af"]) ? limpiarCadena($_POST["idcategoria_insumos_af"]) : "";

      switch ($_GET["op"]) {
        
        case 'box_content_reporte':
          $rspta = $chart_compra_grano->box_content_reporte();
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;
        break;

        case 'chart_linea':
          $rspta = $chart_compra_grano->chart_linea($_POST["idnubeproyecto"], $_POST["year_filtro"], $_POST["month_filtro"], $_POST["dias_por_mes"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;
        break;

        case 'anios_select2':
          $rspta = $chart_compra_grano->anios_select2();

          $data ="";
         
          if ($rspta['status']) {
            foreach ($rspta['data'] as $key => $value) {    
              $data .= '<option value=' . $value['anios'] . '>' . $value['anios'] .'</option>';
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
