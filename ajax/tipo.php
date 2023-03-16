<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    
    require_once "../modelos/Tipo.php";

    $tipo = new Tipo();

    $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    $idtipo_persona = isset($_POST["idtipo_persona"]) ? limpiarCadena($_POST["idtipo_persona"]) : "";
    $nombre_tipo    = isset($_POST["nombre_tipo"]) ? limpiarCadena($_POST["nombre_tipo"]) : "";
    $descripcion    = isset($_POST["descripcion_t"]) ? limpiarCadena($_POST["descripcion_t"]) : "";

    switch ($_GET["op"]) {
      case 'guardaryeditar_tipo':
        if (empty($idtipo_persona)) {
          $rspta = $tipo->insertar($nombre_tipo, $descripcion);
          echo json_encode( $rspta, true) ;
        } else {
          $rspta = $tipo->editar($idtipo_persona, $nombre_tipo, $descripcion);
          echo json_encode( $rspta, true) ;
        }
      break;

      case 'desactivar_tipo':
        $rspta = $tipo->desactivar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'eliminar_tipo':
        $rspta = $tipo->eliminar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'mostrar_tipo':
        $rspta = $tipo->mostrar($idtipo_persona);
        //Codificar el resultado utilizando json
        echo json_encode( $rspta, true) ;
      break;

      case 'listar_tipo':
        $rspta = $tipo->listar_tipo();
        //Vamos a declarar un array
        $data = [];  $cont = 1;       

        if ($rspta['status'] == true) {
          while ($reg = $rspta['data']->fetch_object()) {

            //static butom
            $disable = ""; $onclick = ""; $onclick_mostrar = ""; $editar_title = ""; $eliminar_title = "";          

            if ( $reg->idtipo_persona <= 4 ) {
              $disable = 'disabled';
              $onclick = '';
              $editar_title = 'Sin Acción';
              $eliminar_title = 'Sin Acción';
              $onclick_mostrar = "";
            }else {  
              $onclick = $reg->idtipo_persona;   
              $editar_title = 'Editar'; 
              $eliminar_title = 'Eliminar o Papelera'; 
              $onclick_mostrar = 'mostrar_tipo(' . $reg->idtipo_persona . ')';
            }            
            
            $data[] = [
              "0" => $cont++,
              "1" => $reg->estado ? '<button class="btn btn-warning '.$disable.' btn-sm"  onclick="'.$onclick_mostrar.'" data-toggle="tooltip" data-original-title="'.$editar_title.'"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger '.$disable.' btn-sm" onclick="eliminar_tipo(' .  $onclick .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="'.$eliminar_title.'"><i class="fas fa-skull-crossbones"></i> </button>'
                : '<button class="btn btn-warning '.$disable.' btn-sm"  onclick="'.$onclick_mostrar.'"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-primary '.$disable.' btn-sm" onclick="activar_tipo(' . $onclick . ')"><i class="fa fa-check"></i></button>',
              "2" => $reg->nombre,
              "3" => '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 45px;">'. $reg->descripcion .'</div>',
              "4" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
            ];
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true);
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
