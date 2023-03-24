<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    
    require_once "../modelos/presentacion.php";

    $presentacion = new presentacion();

    $idpresentacion = isset($_POST["idpresentacion"]) ? limpiarCadena($_POST["idpresentacion"]) : "";
    $nombre = isset($_POST["nombre_presentacion"]) ? limpiarCadena($_POST["nombre_presentacion"]) : "";
    $descripcion = isset($_POST["descripcion_p"]) ? limpiarCadena($_POST["descripcion_p"]) : "";

    switch ($_GET["op"]) {

      case 'guardaryeditar_presentacion':

        if (empty($idpresentacion)) {
          $rspta = $presentacion->insertar($nombre, $descripcion);
          echo json_encode( $rspta, true) ;
        } else {
          $rspta = $presentacion->editar($idpresentacion, $nombre, $descripcion);
          echo json_encode( $rspta, true) ;
        }
      break;

      case 'desactivar_presentacion':
        $rspta = $presentacion->desactivar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;
      
      case 'eliminar_presentacion':
        $rspta = $presentacion->eliminar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'mostrar_presentacion':
        $rspta = $presentacion->mostrar($idpresentacion);
        //Codificar el resultado utilizando json
        echo json_encode( $rspta, true) ;
      break;    

      case 'tbla_presentacion':
        $rspta = $presentacion->tbla_presentacion();
        //Vamos a declarar un array
        $data = []; $cont = 1;

        $toltip = '<script> $(function() { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning btn-sm" onclick="mostrar_presentacion(' . $reg->idpresentacion . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_presentacion(' . $reg->idpresentacion .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>'
                : '<button class="btn btn-warning btn-sm" onclick="mostrar_presentacion(' . $reg->idpresentacion . ')"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary btn-sm" onclick="activar_presentacion(' . $reg->idpresentacion . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->nombre,
              "3" => '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 45px;">'.
                $reg->descripcion,
              '</div>',
              "4" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
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
