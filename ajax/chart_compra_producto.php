<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['almacen_abono'] == 1) {
      
      require_once "../modelos/Chart_compra_producto.php";

      $chart_venta_producto = new ChartCompraProducto();

      date_default_timezone_set('America/Lima');  $date_now = date("d-m-Y h.i.s A");

      $idproducto = isset($_POST["idproducto"]) ? limpiarCadena($_POST["idproducto"]) : "";
      $idcategoria = isset($_POST["idcategoria_insumos_af"]) ? limpiarCadena($_POST["idcategoria_insumos_af"]) : "";

      switch ($_GET["op"]) {
        
        case 'box_content_reporte':
          $rspta = $chart_venta_producto->box_content_reporte();
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;
        break;

        case 'chart_linea':
          $rspta = $chart_venta_producto->chart_linea($_POST["idnubeproyecto"], $_POST["year_filtro"], $_POST["month_filtro"], $_POST["dias_por_mes"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;
        break;

        case 'export_productos_mas_usados':
          $rspta = $chart_venta_producto->export_productos_mas_usados($_GET["anio"], $_GET["mes"]);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;
        break;

        case 'anios_select2':
          $rspta = $chart_venta_producto->anios_select2();

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
