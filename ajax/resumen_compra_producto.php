<?php
  ob_start();

  if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {
    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
  } else {
    //Validamos el acceso solo al usuario logueado y autorizado.
    if ($_SESSION['almacen_abono'] == 1) {

      require_once "../modelos/Resumen_compra_producto.php";
      require_once "../modelos/Producto.php";

      $resumen_producto = new ResumenCompraProducto();
      $producto = new Producto($_SESSION['idusuario']);

      date_default_timezone_set('America/Lima'); $date_now = date("d-m-Y h.i.s A");
      $imagen_error = "this.src='../dist/svg/404-v2.svg'";
      $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

      // :::::::::::::::::::::::::::::::::::: D A T O S   C O M P R A ::::::::::::::::::::::::::::::::::::::
      // $idproyecto         = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
      // $idcompra_proyecto  = isset($_POST["idcompra_proyecto"]) ? limpiarCadena($_POST["idcompra_proyecto"]) : "";
      // $idproveedor        = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
      // $fecha_compra       = isset($_POST["fecha_compra"]) ? limpiarCadena($_POST["fecha_compra"]) : "";

      switch ($_GET["op"]) {      

        case 'tbla_principal':          

          $rspta = $resumen_producto->tbla_principal();
          //Vamos a declarar un array
          $data = []; $count = 1;          

          if ($rspta['status']) {
            while ($reg = $rspta['data']->fetch_object()) {
              $clas_stok = "";
              $imagen = (empty($reg->imagen) ? '../dist/docs/producto/img_perfil/producto-sin-foto.svg' : '../dist/docs/producto/img_perfil/'.$reg->imagen) ;

              if ( $reg->stock <= 0) {
                $clas_stok = 'badge-danger';
              }else if ($reg->stock > 0 && $reg->stock <= 10) {
                $clas_stok = 'badge-warning';
              }else if ($reg->stock > 10) {
                $clas_stok = 'badge-success';
              }

              $data[] = [     
                "0"  => $count++,       
                "1" => '<button class="btn btn-info btn-sm" onclick="mostrar_detalle_material(' . $reg->idproducto . ')" data-toggle="tooltip" data-original-title="Ver detalle Producto"><i class="far fa-eye"></i></button>',       
                "2" => zero_fill($reg->idproducto, 6),    
                "3" => '<div class="user-block"> 
                  <img class="profile-user-img img-responsive img-circle cursor-pointer" src="' . $imagen . '" onclick="ver_img_producto(\'' . $imagen . '\', \''.encodeCadenaHtml($reg->nombre_producto).'\');" alt="User Image" onerror="' .  $imagen_error .  '" data-toggle="tooltip" data-original-title="Ver imagen">
                  <span class="username"><p class="text-primary m-b-02rem" >' . $reg->nombre_producto . '</p></span>
                  <span class="description"><b>Categoria: </b>'.(empty($reg->categoria_producto) ? ' - ' : $reg->categoria_producto ).'</span>
                </div>',
                "4" => $reg->unidad_medida,
                "5" => $reg->contenido_neto,
                "6" => '<span class="badge '.$clas_stok.' font-size-14px" id="table_stock_'.$reg->idproducto.'">'.$reg->stock.'</span>',
                "7" => $reg->cantidad,
                "8" => '<button class="btn btn-info btn-sm mb-2" onclick="tbla_facuras( ' . $reg->idproducto . ', \'' .  htmlspecialchars($reg->nombre_producto, ENT_QUOTES) . '\')" data-toggle="tooltip" data-original-title="Ver compras"><i class="fa-solid fa-file-invoice-dollar"></i></button>'. $toltip,
                "9" => $reg->precio_venta,
                "10" => $reg->descuento,
                "11" => $reg->subtotal,
                
                "12" => $reg->nombre_producto,
                "13" => $reg->categoria_producto,
         
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

        // :::::::::::::::::::::::::: S E C C I O N   M A T E R I A L E S ::::::::::::::::::::::::::        

        case 'mostrar_materiales':

          $rspta = $producto->mostrar($_POST["idproducto"]);
          //Codificar el resultado utilizando json
          echo json_encode($rspta, true);
      
        break;  

        // :::::::::::::::::::::::::: S E C C I O N   C O M P R A ::::::::::::::::::::::::::       

        case 'tbla_facturas':

          $rspta = $resumen_producto->tbla_facturas($_GET["idproducto"]);
          //Vamos a declarar un array
          $data = []; $cont = 1;

          if ($rspta['status']) {
            // idcompra_proyecto,num_orden, num_comprobante, fecha
            foreach ($rspta['data'] as $key => $reg) {
              // validamos si existe una ficha tecnica              

              $btn_tipo = (empty($reg['cant_comprobantes']) ? 'btn-outline-info' : 'btn-info');
              $descrip_toltip = (empty($reg['cant_comprobantes']) ? 'Vacío' : ($reg['cant_comprobantes']==1 ?  $reg['cant_comprobantes'].' comprobante' : $reg['cant_comprobantes'].' comprobantes'));       

              $data[] = [    
                "0" => $cont++,
                "1" => '<button class="btn btn-info btn-sm" onclick="ver_detalle_compras(' . $reg['idcompra_producto'] .', '. $reg['idproducto'] . ')" data-toggle="tooltip" data-original-title="Ver detalle compra"><i class="fa fa-eye"></i></button>' . $toltip ,
                "2" => '<span class="text-primary font-weight-bold" >' . $reg['persona']. '</span>',    
                "3" => '<span class="" ><b>' . $reg['tipo_comprobante'] .  '</b> '.(empty($reg['serie_comprobante']) ?  "" :  '- '.$reg['serie_comprobante']).'</span>' .$toltip,  
                "4" => $reg['fecha_compra'],
                "5" => $reg['cantidad'],
                "6" => $reg['precio_venta'] ,
                "7" => $reg['descuento'],
                "8" => $reg['subtotal'],
                "9" => $reg['tipo_comprobante'],
                "10" => $reg['serie_comprobante'],

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
        
        // :::::::::::::::::::::::::: S E C C I O N   C O M P R O B A N T E  :::::::::::::::::::::::::: 

        case 'tbla_comprobantes_compra':
          $cont_compra = $_GET["num_orden"];
          $id_compra = $_GET["id_compra"];
          $rspta = $compra->tbla_comprobantes( $id_compra );
          //Vamos a declarar un array
          $data = []; $cont = 1;        
          
          if ($rspta['status']) {
            while ($reg = $rspta['data']->fetch_object()) {
              $data[] = [
                "0" => $cont,
                "1" => '<div class="text-nowrap">'.
                ' <a class="btn btn-info btn-sm " href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'"  download="'.$cont_compra.'·'.$cont.' '.removeSpecialChar((empty($reg->serie_comprobante) ?  " " :  ' ─ '.$reg->serie_comprobante).' ─ '.$reg->razon_social).' ─ '. format_d_m_a($reg->fecha_compra).'" data-toggle="tooltip" data-original-title="Descargar" ><i class="fas fa-cloud-download-alt"></i></a>              
                </div>'.$toltip,
                "2" => '<a class="btn btn-info btn-sm" href="../dist/docs/compra_insumo/comprobante_compra/'.$reg->comprobante.'" target="_blank" rel="noopener noreferrer"><i class="fas fa-receipt"></i></a>' ,
                "3" => $reg->updated_at,
              ];
              $cont++;
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

        default: 
          $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
        break;
        
      }    

      //Fin de las validaciones de acceso
    } else {
      $retorno = ['status'=>'nopermiso', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
      echo json_encode($retorno);
    }
  }

  ob_end_flush();
?>
