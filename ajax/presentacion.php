<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    
    require_once "../modelos/Unidades_m.php";

    $unidades_m = new Unidades_m();

    $idunidad_medida = isset($_POST["idunidad_medida"]) ? limpiarCadena($_POST["idunidad_medida"]) : "";
    $nombre = isset($_POST["nombre_medida"]) ? limpiarCadena($_POST["nombre_medida"]) : "";
    $abreviatura = isset($_POST["abreviatura"]) ? limpiarCadena($_POST["abreviatura"]) : "";
    $descripcion = isset($_POST["descripcion_m"]) ? limpiarCadena($_POST["descripcion_m"]) : "";

    switch ($_GET["op"]) {

      case 'guardaryeditar_unidades_m':

        if (empty($idunidad_medida)) {
          $rspta = $unidades_m->insertar($nombre, $abreviatura,$descripcion);
          echo json_encode( $rspta, true) ;
        } else {
          $rspta = $unidades_m->editar($idunidad_medida, $nombre, $abreviatura,$descripcion);
          echo json_encode( $rspta, true) ;
        }
      break;

      case 'desactivar_unidades_m':
        $rspta = $unidades_m->desactivar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;
      
      case 'eliminar_unidades_m':
        $rspta = $unidades_m->eliminar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'mostrar_unidades_m':
        $rspta = $unidades_m->mostrar($idunidad_medida);
        //Codificar el resultado utilizando json
        echo json_encode( $rspta, true) ;
      break;    

      case 'tbla_unidad_medida':
        $rspta = $unidades_m->tbla_unidad_medida();
        //Vamos a declarar un array
        $data = []; $cont = 1;

        $toltip = '<script> $(function() { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_unidades_m(' . $reg->idunidad_medida . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_unidades_m(' . $reg->idunidad_medida .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>'
                : '<button class="btn btn-warning btn-sm" onclick="mostrar_unidades_m(' . $reg->idunidad_medida . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar_unidades_m(' . $reg->idunidad_medida . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->nombre,
              "3" => $reg->abreviatura,
              "4" => '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 45px;">'.
                $reg->descripcion,
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
