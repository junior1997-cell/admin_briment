<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    
    require_once "../modelos/categoria_p.php";

    $categoria_p = new Categoria_p();

    $idcategoria_producto = isset($_POST["idcategoria_producto"]) ? limpiarCadena($_POST["idcategoria_producto"]) : "";
    $nombre_categoria = isset($_POST["nombre_categoria"]) ? limpiarCadena($_POST["nombre_categoria"]) : "";
    $descripcion = isset($_POST["descripcion_cat"]) ? limpiarCadena($_POST["descripcion_cat"]) : "";

    switch ($_GET["op"]) {
      case 'guardaryeditar_c_insumos_af':
        if (empty($idcategoria_producto)) {
          $rspta = $categoria_p->insertar($nombre_categoria, $descripcion);
          echo json_encode( $rspta, true) ;
        } else {
          $rspta = $categoria_p->editar($idcategoria_producto, $nombre_categoria, $descripcion);
          echo json_encode( $rspta, true) ;
        }
      break;

      case 'desactivar':
        $rspta = $categoria_p->desactivar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'delete':
        $rspta = $categoria_p->delete($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'mostrar':
        //$idcategoria_producto='1';
        $rspta = $categoria_p->mostrar($idcategoria_producto, $descripcion);
        //Codificar el resultado utilizando json
        echo json_encode( $rspta, true) ;
      break;

      case 'listar_c_producto':
        $rspta = $categoria_p->listar();
        //Vamos a declarar un array
        $data = [];  $cont = 1;

        $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

        if ($rspta['status']) {
          while ($reg = $rspta['data']->fetch_object()) {
            $data[] = [
              "0" => $cont++,
              "1" => $reg->estado
                ? '<button class="btn btn-warning btn-sm" onclick="mostrar_c_insumos_af(' .  $reg->idcategoria_producto . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger btn-sm" onclick="eliminar_c_insumos_af(' . $reg->idcategoria_producto .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>'
                : '',
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
