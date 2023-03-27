<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    
    require_once "../modelos/Sucursal.php";

    $sucursal = new Sucursal($_SESSION['idusuario']);

    $toltip = '<script> $(function() { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    $idsucursal         = isset($_POST["idsucursal"]) ? limpiarCadena($_POST["idsucursal"]) : "";
    $nombre_sucursal    = isset($_POST["nombre_sucursal"]) ? limpiarCadena($_POST["nombre_sucursal"]) : "";
    $codigo_sucursal    = isset($_POST["codigo_sucursal"]) ? limpiarCadena($_POST["codigo_sucursal"]) : "";
    $direccion_sucursal = isset($_POST["direccion_sucursal"]) ? limpiarCadena($_POST["direccion_sucursal"]) : "";

    switch ($_GET["op"]) {

      case 'guardar_y_editar_sucursal':

        if (empty($idsucursal)) {
          $rspta = $sucursal->insertar($nombre_sucursal, $codigo_sucursal,$direccion_sucursal);
          echo json_encode( $rspta, true) ;
        } else {
          $rspta = $sucursal->editar($idsucursal, $nombre_sucursal, $codigo_sucursal,$direccion_sucursal);
          echo json_encode( $rspta, true) ;
        }
      break;

      case 'desactivar_sucursal':
        $rspta = $sucursal->desactivar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;
      
      case 'eliminar_sucursal':
        $rspta = $sucursal->eliminar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'mostrar_sucursal':
        $rspta = $sucursal->mostrar($idsucursal);
        //Codificar el resultado utilizando json
        echo json_encode( $rspta, true) ;
      break;    

      case 'tbla_sucursal':
        $rspta = $sucursal->tbla_sucursal();
        //Vamos a declarar un array
        $data = []; $cont = 1;        

        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_sucursal(' . $reg->idsucursal . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_sucursal(' . $reg->idsucursal .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>'
                : '<button class="btn btn-warning btn-sm" onclick="mostrar_sucursal(' . $reg->idsucursal . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar_sucursal(' . $reg->idsucursal . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->nombre,
              "3" => $reg->codigo,
              "4" => '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 45px;">'.
                $reg->direccion,
              '</div>',
              "5" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
            ];
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode( $results, true) ;
        } else {
          echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
        }  

      break;

      default: 
        $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
      break;
    }
  }
  
  
  ob_end_flush();
?>
