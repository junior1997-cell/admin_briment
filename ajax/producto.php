<?php
  ob_start();
  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {

    if ($_SESSION['recurso'] == 1) {
      
      require_once "../modelos/Producto.php";

      $producto = new Producto($_SESSION['idusuario']);

      date_default_timezone_set('America/Lima'); $date_now = date("d-m-Y h.i.s A");
      $imagen_error = "this.src='../dist/svg/404-v2.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/admin_briment/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');
      
      $idproducto       = isset($_POST["idproducto"]) ? limpiarCadena($_POST["idproducto"]) : "" ;
      $codigo           = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "" ;
      $nombre_producto  = isset($_POST["nombre_producto"]) ? limpiarCadena($_POST["nombre_producto"]) : "" ;
      $laboratorio      = isset($_POST["laboratorio"]) ? limpiarCadena($_POST["laboratorio"]) : "" ;
      $presentacion     = isset($_POST["presentacion"]) ? encodeCadenaHtml($_POST["presentacion"]) : "" ;
      $unidad_medida    = isset($_POST["unidad_medida"]) ? encodeCadenaHtml($_POST["unidad_medida"]) : "" ;
      $principio_activo = isset($_POST["principio_activo"]) ? encodeCadenaHtml($_POST["principio_activo"]) : "" ;
      $descripcion      = isset($_POST["descripcion"]) ? encodeCadenaHtml($_POST["descripcion"]) : "" ;
      $precio_venta    = isset($_POST["precio_venta"]) ? encodeCadenaHtml($_POST["precio_venta"]) : "" ;
      $imagen1          = isset($_POST["foto1"]) ? limpiarCadena($_POST["foto1"]) : "" ;
      
      switch ($_GET["op"]) {

        case 'guardaryeditar':
          // imagen
          if (!file_exists($_FILES['foto1']['tmp_name']) || !is_uploaded_file($_FILES['foto1']['tmp_name'])) {
            $imagen1 = $_POST["foto1_actual"];
            $flat_img1 = false;
          } else {
            $ext1 = explode(".", $_FILES["foto1"]["name"]);
            $flat_img1 = true;
            $imagen1 = $date_now .' '. random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext1);
            move_uploaded_file($_FILES["foto1"]["tmp_name"], "../dist/docs/producto/img_perfil/" . $imagen1);
          }

          if (empty($idproducto)) {
           
            $rspta = $producto->insertar($codigo,$nombre_producto,$laboratorio,$presentacion,$unidad_medida,$principio_activo,$descripcion,$imagen1 );            
            echo json_encode( $rspta, true);

          } else {

            // validamos si existe LA IMG para eliminarlo          
            if ($flat_img1 == true) {
              $datos_f1 = $producto->obtenerImg($idproducto);
              $img1_ant = $datos_f1['data']->fetch_object()->imagen;
              if ( !empty( $img1_ant ) ) { unlink("../dist/docs/producto/img_perfil/" . $img1_ant); }
            }
            
            $rspta = $producto->editar($idproducto,$codigo,$nombre_producto,$laboratorio,$presentacion,$unidad_medida,$principio_activo, $descripcion,$imagen1 );            
            echo json_encode( $rspta, true) ;
          }
        break;
    
        case 'desactivar':

          $rspta = $producto->desactivar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;      

        case 'eliminar':

          $rspta = $producto->eliminar( $_GET["id_tabla"] );

          echo json_encode( $rspta, true) ;

        break;
    
        case 'mostrar':

          $rspta = $producto->mostrar($idproducto);
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true) ;

        break;
    
        case 'tbla_principal':
          $rspta = $producto->tbla_principal($_GET["idpresentacion"]);
          //Vamos a declarar un array
          $data = []; $cont=1;

          if ($rspta['status'] == true) {
            
            foreach ( $rspta['data'] as $key => $reg) {   
              $imagen = (empty($reg['imagen']) ? 'producto-sin-foto.svg' : $reg['imagen'] );
              $clas_stok = "";

              if ( $reg['stock'] <= 0) { $clas_stok = 'badge-danger'; }
              else if ($reg['stock'] > 0 && $reg['stock'] <= 10) { $clas_stok = 'badge-warning'; }
              else if ($reg['stock'] > 10) { $clas_stok = 'badge-success'; }
              
              $data[] = [
                "0"=>$cont++,
                "1" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg['idproducto'] . ')" data-toggle="tooltip" data-original-title="Editar"><i class="fas fa-pencil-alt"></i></button>' .
                ' <button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg['idproducto'] .', \''.encodeCadenaHtml($reg['nombre']).'\')" data-toggle="tooltip" data-original-title="Eliminar o papelera"><i class="fas fa-skull-crossbones"></i></button>',
                "2" => $reg['codigo'],
                "3" => '<div class="user-block">'.
                  '<img class="profile-user-img img-responsive img-circle cursor-pointer" src="../dist/docs/producto/img_perfil/' . $imagen . '" alt="user image" onerror="'.$imagen_error.'" onclick="ver_perfil(\'../dist/docs/producto/img_perfil/' . $imagen . '\', \''.encodeCadenaHtml($reg['unidad_medida']).'\');" data-toggle="tooltip" data-original-title="Ver imagen">
                  <span class="username"><p class="mb-0">' . $reg['nombre'] . '</p></span>
                  <span class="description"><b>UM: </b>' . $reg['unidad_medida'] . '</span>
                </div>' . $toltip,
                "4" =>  $reg['laboratorio'],
                "5" => $reg['presentacion'],     
                "6" => '<span class="badge '.$clas_stok.' font-size-14px cursor-pointer" onclick="tbla_lote(' . $reg['idproducto'] .', \''.encodeCadenaHtml($reg['nombre']).'\')" data-toggle="tooltip" data-original-title="Ver stock">'.$reg['stock'].'</span>',
                "7" => $reg['precio_venta'],
                "8" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg['principio_activo'] . '</textarea>',

                "9" => $reg['nombre'],
                "10" => $reg['unidad_medida']                  
              ];
            }
  
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
            ];
  
            echo json_encode( $results, true) ;
          } else {
            echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
          }
          
        break;

        // ══════════════════════════════════════  P R E S E N T A C I O N   P R O D U C T O  ══════════════════════════════════════

        case 'lista_de_presentacion':

          $rspta = $producto->lista_de_presentacion();
          //Codificar el resultado utilizando json
          echo json_encode( $rspta, true);

        break;

        // ══════════════════════════════════════  L O T E   P R O D U C T O  ══════════════════════════════════════
        case 'tbla_lote':
          $rspta = $producto->tbla_lote($_GET["idproducto"]);
          //Vamos a declarar un array
          $data = []; $cont=1;

          if ($rspta['status'] == true) {
            
            foreach ( $rspta['data'] as $key => $reg) {                 
              
              $data[] = [
                "0"=>$cont++,
                "1" => $reg['nombre'],
                "2" => $reg['stock'],
                "3" => $reg['fecha_vencimiento'],
                "4" => '<textarea cols="30" rows="1" class="textarea_datatable" readonly="">' . $reg['descripcion'] . '</textarea>'                
              ];
            }
  
            $results = [
              "sEcho" => 1, //Información para el datatables
              "iTotalRecords" => count($data), //enviamos el total registros al datatable
              "iTotalDisplayRecords" => 1, //enviamos el total registros a visualizar
              "data" => $data,
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
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }  
  } 
  
  ob_end_flush();
?>
