<?php
require '../vendor/autoload.php';
use Luecano\NumeroALetras\NumeroALetras;

if (strlen(session_id()) < 1) {
  session_start();
}

if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
      
  require 'Factura.php';
  require_once "../modelos/Venta_producto.php";

  //Establecemos la configuración de la factura
  $pdf = new PDF_Invoice('P', 'mm', 'A4');  
  $venta_producto = new Venta_producto();
  $numero_a_letra = new NumeroALetras();


  if (empty($_GET)) {
    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
  } else if ($_GET['id'] != '') {
    $id = $_GET['id'];
    $rspta = $venta_producto->mostrar_venta_para_editar($id);
  } else {    
  }

  //Establecemos los datos de la empresa
  $logo     = "../dist/img/default/empresa-logo.jpg";
  $ext_logo = "jpg";
  $empresa  = 'INTEGRA PERÚ S.A.C';
  $documento= 'RUC: 3532432423' ;
  $direccion= 'JR. LAS ROSASA / JAEN / PERU';
  $telefono = '938-724-523' ;

  //Enviamos los datos de la empresa al método addSociete de la clase Factura
  $pdf->AddPage();  
  $pdf->addSociete(utf8_decode($empresa), 
  $documento . "\n" . utf8_decode("Dirección: ") . utf8_decode($direccion) . "\n" . utf8_decode("Teléfono: ") . $telefono , 
  $logo, $ext_logo);
  $pdf->fact_dev($rspta['data']['venta']['tipo_comprobante'], $rspta['data']['venta']['serie_comprobante']); #comprobante y numero
  $pdf->addClient( zero_fill($rspta['data']['venta']['idpersona'], 6) ); #codigo de Persona
  $pdf->addDate(format_d_m_a($rspta['data']['venta']['fecha_venta']));  

  $pdf->temporaire( utf8_decode("Integra Perú") ); #marca de agua

  //Enviamos los datos del cliente al método addClientAdresse de la clase Factura
  $pdf->addClientAdresse(utf8_decode($rspta['data']['venta']['nombres']), 
    utf8_decode($rspta['data']['venta']['direccion']), 
    utf8_decode($rspta['data']['venta']['tipo_documento']) , 
    utf8_decode($rspta['data']['venta']['numero_documento']), 
    utf8_decode($rspta['data']['venta']['correo']), 
    utf8_decode($rspta['data']['venta']['celular'])
  );
  $pdf->addReference( utf8_decode( decodeCadenaHtml((empty($rspta['data']['venta']['descripcion'])) ? '- - -' :$rspta['data']['venta']['descripcion']) ));

  //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
  $cols = [ "#" => 8, "PRODUCTO" => 63, "UM" => 18, "CANT." => 14, "V/U" => 18, "IGV" => 14, "P.U." => 20, "DSCT." => 13, "SUBTOTAL" => 22];
  $pdf->addCols($cols);
  $cols = [ "#" => "C", "PRODUCTO" => "L", "UM" => "C",  "CANT." => "C", "V/U" => "R", "IGV" => "R","P.U." => "R", "DSCT." => "R", "SUBTOTAL" => "R"];
  $pdf->addLineFormat($cols);
  $pdf->addLineFormat($cols);
  //Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
  $y = 85;

  $cont = 1;
  //Obtenemos todos los detalles de la venta actual
  foreach ($rspta['data']['detalle'] as $key => $reg) {

    $line = [ "#" => $cont++, 
      "PRODUCTO" => utf8_decode( decodeCadenaHtml($reg['nombre'])), 
      "UM" => $reg['abreviatura'], 
      "CANT." => $reg['cantidad'], 
      "V/U" => number_format($reg['precio_sin_igv'], 2, '.',','), 
      "IGV" => number_format($reg['igv'], 2, '.',','), 
      "P.U." => number_format($reg['precio_con_igv'], 2, '.',','), 
      "DSCT." => number_format($reg['descuento'], 2, '.',','), 
      "SUBTOTAL" => number_format($reg['subtotal'], 2, '.',',')
    ];
    $size = $pdf->addLine($y, $line);
    $y += $size + 2;
  }

  //Convertimos el total en letras
  $num_total = $numero_a_letra->toMoney( $rspta['data']['venta']['total'], 2, 'soles' );  #echo $num_total; die;
  $decimales_mun = explode('.', $rspta['data']['venta']['total']); #echo $decimales_mun[1]; die;
  $centimos = (isset($decimales_mun[1])? $decimales_mun[1] : '00' ) . '/100 CÉNTIMOS';
  $con_letra = strtoupper( utf8_decode($num_total .' '. $centimos) );
  $pdf->addCadreTVAs("- " . $con_letra);


  //Mostramos el impuesto
  $pdf->addTVAs(number_format($rspta['data']['venta']['subtotal'], 2, '.',','), number_format($rspta['data']['venta']['igv'], 2, '.',','), number_format($rspta['data']['venta']['total'], 2, '.',','), "S/ ");
  $pdf->addCadreEurosFrancs('IGV ('.( ( empty($rspta['data']['venta']['val_igv']) ? 0 : floatval($rspta['data']['venta']['val_igv']) )  * 100 ) . '%)');
  $pdf->Output('Reporte de compra.pdf', 'I');
   
}



?>
