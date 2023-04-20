<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    
    require_once "../modelos/Lote.php";

    $lote = new Lote($_SESSION['idusuario']);

    $idlote = isset($_POST["idlote"]) ? limpiarCadena($_POST["idlote"]) : "";
    $nombre_lote = isset($_POST["nombre_lote"]) ? limpiarCadena($_POST["nombre_lote"]) : "";
    $fecha_vencimiento = isset($_POST["fecha_vencimiento"]) ? limpiarCadena($_POST["fecha_vencimiento"]) : "";
    $descripcion = isset($_POST["descripcion_lot"]) ? limpiarCadena($_POST["descripcion_lot"]) : "";

    switch ($_GET["op"]) {

      case 'guardaryeditar_lote':

        if (empty($idlote)) {
          $rspta = $lote->insertar($nombre_lote, $fecha_vencimiento,$descripcion);
          echo json_encode( $rspta, true) ;
        } else {
          $rspta = $lote->editar($idlote, $nombre_lote, $fecha_vencimiento,$descripcion);
          echo json_encode( $rspta, true) ;
        }
      break;

      case 'desactivar_lote':
        $rspta = $lote->desactivar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;
      
      case 'eliminar_lote':
        $rspta = $lote->eliminar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'mostrar_lote':
        $rspta = $lote->mostrar($idlote);
        //Codificar el resultado utilizando json
        echo json_encode( $rspta, true) ;
      break;    

      case 'tbla_lote':
        $rspta = $lote->tbla_lote();
        //Vamos a declarar un array
        $data = []; $cont = 1;

        $toltip = '<script> $(function() { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_lote(' . $reg->idlote . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_lote(' . $reg->idlote .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>'
                : '<button class="btn btn-warning btn-sm" onclick="mostrar_lote(' . $reg->idlote . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar_lote(' . $reg->idlote . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->nombre,
              "3" => $reg->stock,
              "4" => $reg->fecha_vencimiento,
              "5" => '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 45px;">'. $reg->descripcion. '</div>',
              #"5" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
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

      case 'salir':
        //Limpiamos las variables de sesión
        session_unset();
        //Destruìmos la sesión
        session_destroy();
        //Redireccionamos al login
        header("Location: ../index.php");
      break;

      default: 
        $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
      break;
    }
  }
  
  
  ob_end_flush();
?>
