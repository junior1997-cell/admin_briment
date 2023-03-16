<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    
    require_once "../modelos/Bancos.php";

    $bancos = new Bancos();

    date_default_timezone_set('America/Lima');
    $date_now = date("d-m-Y h.i.s A");

    $idbancos = isset($_POST["idbancos"]) ? limpiarCadena($_POST["idbancos"]) : "";
    $nombre = isset($_POST["nombre_b"]) ? limpiarCadena($_POST["nombre_b"]) : "";
    $alias = isset($_POST["alias"]) ? limpiarCadena($_POST["alias"]) : "";

    $formato_cci = isset($_POST["formato_cci"]) ? limpiarCadena($_POST["formato_cci"]) : "";
    $formato_cta = isset($_POST["formato_cta"]) ? limpiarCadena($_POST["formato_cta"]) : "";
    $formato_detracciones = isset($_POST["formato_detracciones"]) ? limpiarCadena($_POST["formato_detracciones"]) : "";

    $imagen1 = isset($_POST["imagen1"]) ? limpiarCadena($_POST["imagen1"]) : "";

    switch ($_GET["op"]) {

      case 'guardaryeditar_bancos':

        // imgen
        if (!file_exists($_FILES['imagen1']['tmp_name']) || !is_uploaded_file($_FILES['imagen1']['tmp_name'])) {

          $imagen1 = $_POST["imagen1_actual"];

          $flat_img1 = false;

        } else {

          $ext1 = explode(".", $_FILES["imagen1"]["name"]);

          $flat_img1 = true;

          $imagen1 = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);

          move_uploaded_file($_FILES["imagen1"]["tmp_name"], "../dist/docs/banco/logo/" . $imagen1);
        }

        if (empty($idbancos)) {      

          $rspta = $bancos->insertar($nombre, $alias, $formato_cta, $formato_cci, $formato_detracciones, $imagen1);

          echo json_encode( $rspta, true) ;

        } else {

          // validamos si existe LA IMG para eliminarlo
          if ($flat_img1 == true) {

            $datos_f1 = $bancos->obtenerImg($idbancos);

            $img1_ant = $datos_f1['data']['icono'];

            if ($img1_ant != "") {  unlink("../dist/docs/banco/logo/" . $img1_ant); }
          }

          $rspta = $bancos->editar($idbancos, $nombre, $alias, $formato_cta, $formato_cci, $formato_detracciones, $imagen1);
          echo json_encode( $rspta, true) ;
        }
      break;

      case 'desactivar_bancos':
        $rspta = $bancos->desactivar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'eliminar_bancos':
        $rspta = $bancos->eliminar($_GET["id_tabla"]);
        echo json_encode( $rspta, true) ;
      break;

      case 'mostrar_bancos':
        $rspta = $bancos->mostrar($idbancos);
        //Codificar el resultado utilizando json
        echo json_encode( $rspta, true) ;
      break;

      case 'listar':
        $rspta = $bancos->listar();
        //Vamos a declarar un array
        $data = [];

        $cta = "00000000000000000000000000000"; $cci = "00000000000000000000000000000"; $detraccion = "00000000000000000000000000000";
        $cont=1;

        $imagen_error = "this.src='../dist/docs/banco/logo/logo-sin-banco.svg'";
        $toltip = '<script> $(function() { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

        $imagen = '';
        
        if ($rspta['status']) {

          while ($reg = $rspta['data']->fetch_object()) {

            if (empty($reg->icono)) { $imagen = 'logo-sin-banco.svg';  } else { $imagen = $reg->icono;   }

            $data[] = [
              "0"=>$cont++,
              "1" => $reg->estado
                ? '<button class="btn btn-warning btn-sm" onclick="mostrar_bancos(' . $reg->idbancos . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                  ' <button class="btn btn-danger  btn-sm" onclick="eliminar_bancos(' . $reg->idbancos .', \''.encodeCadenaHtml($reg->nombre).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i> </button>':
                  '<button class="btn btn-warning btn-sm" onclick="mostrar_bancos(' . $reg->idbancos . ')"><i class="fas fa-pencil-alt"></i></button>' . 
                  ' <button class="btn btn-primary btn-sm" onclick="activar_bancos(' . $reg->idbancos . ')"><i class="fa fa-check"></i></button>',
              "2" => '<div class="user-block">
              <img class="img-circle cursor-pointer" src="../dist/docs/banco/logo/'. $imagen .'" alt="User Image" onerror="'.$imagen_error.'" onclick="ver_perfil(\'../dist/docs/banco/logo/' . $imagen . '\', \''.encodeCadenaHtml($reg->nombre).'\');" data-toggle="tooltip" data-original-title="Ver imagen">
              <span class="username"><p class="text-primary m-b-02rem" >'. $reg->nombre .'</p></span>
              <span class="description">'. $reg->alias .
              '</div>',
              "3" => '<div class="bg-color-242244245 " style="overflow: auto; resize: vertical; height: 45px;">'.
                '<span> <b>Formato CTA :</b>' . $reg->formato_cta . '<br><b>Ej. cta: </b>' . darFormatoBanco($cta, $reg->formato_cta) .'</span> <br>'. 
                '<span> <b>Formato CCI :</b>' . $reg->formato_cci . '<br> <b>Ej. cci: </b>' . darFormatoBanco($cci, $reg->formato_cci) . '</span><br>'.
                '<span> <b>Formato Detrac. :</b>' . $reg->formato_detracciones . '<br>  
                <b>Ej. cci: </b>' . darFormatoBanco($detraccion, $reg->formato_detracciones) . '</span>'. 
              '</div>',
              "4" => ($reg->estado ? '<span class="text-center badge badge-success">Activado</span>' : '<span class="text-center badge badge-danger">Desactivado</span>').$toltip,
              "5" => $reg->nombre,
              "6" => $reg->alias,
              "7" => $reg->formato_cta,
              "8" => $reg->formato_cci,
              "9" => $reg->formato_detracciones,
            ];
          }
          $results = [
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data,
          ];
          echo json_encode($results, true) ;
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
  
  

  function darFormatoBanco($numero, $formato) {
    $format_array = explode("-", $formato);
    $format_cuenta = "";
    $cont_format = 0;
    $indi = 0;

    foreach ($format_array as $indice => $key) {
      if ($key == "__" || $key == "0_" || $key == "1_" || $key == "2_" || $key == "3_" || $key == "4_" || $key == "5_" || $key == "6_" || $key == "7_" || $key == "8_" || $key == "9_") {
        $cont_format = $cont_format + 0;
      } else {
        if (intval($key) == 0) {
          $format_cuenta .= substr($numero, $cont_format, $key);

          $cont_format = $cont_format + intval($key); //$indi = $indice;
        } else {
          $format_cuenta .= substr($numero, $cont_format, $key) . '-';

          $cont_format = $cont_format + intval($key);
        }
      }
    }
    return substr($format_cuenta, 0, -1);
  }

  ob_end_flush();
?>
