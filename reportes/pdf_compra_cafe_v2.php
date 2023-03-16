<?php
require '../vendor/autoload.php';
use Luecano\NumeroALetras\NumeroALetras;

if (strlen(session_id()) < 1) {
  session_start();
}

if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
      
  require 'Factura_2.php';  
  require_once "../modelos/Compra_cafe_v2.php";

  //Establecemos la configuración de la factura
  $pdf = new PDF_Invoice_2('P', 'mm', 'A4');  
  $compra_grano = new Compra_cafe_v2();
  $numero_a_letra = new NumeroALetras();

  if (empty($_GET)) {
    header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
  } else if ($_GET['id'] != '') {
    $id = $_GET['id'];
    $rspta = $compra_grano->mostrar_compra_para_editar($id); #echo json_encode($rspta, true); die;
  } else {    
  }

  //Establecemos los datos de la empresa
  $logo     = "../dist/img/default/empresa-logo.jpg";
  $ext_logo = "jpg";
  $empresa  = 'INTEGRA PERU SAC';
  $documento= 'RUC: 20608636766' ;
  $direccion= 'C.P. Churuyacu / Tabaconas / San Ingnacio ';
  $telefono = '937-594-303 / 995-742-995 ' ;
  $email = 'integraperu20@gmail.com' ;

  //Enviamos los datos de la empresa al método addSociete de la clase Factura
  $pdf->AddPage();  
  $pdf->addSociete(utf8_decode($empresa), 
  $documento . "\n" . utf8_decode("Dirección: ") . utf8_decode($direccion) . "\n" . utf8_decode("Teléfono: ") . $telefono  . "\n" . utf8_decode("Email: ") . $email, 
  $logo, $ext_logo);
  $pdf->fact_dev($rspta['data']['tipo_comprobante'], $rspta['data']['numero_comprobante']);
  $pdf->addClient( zero_fill($rspta['data']['idpersona'], 6) ); #codigo de Persona
  $pdf->addDate(format_d_m_a($rspta['data']['fecha_compra']));

  $pdf->temporaire( utf8_decode("Integra Perú") );

  //Enviamos los datos del cliente al método addClientAdresse de la clase Factura
  $pdf->addClientAdresse(utf8_decode($rspta['data']['cliente']), 
    utf8_decode($rspta['data']['direccion']), 
    $rspta['data']['tipo_documento'] ,
    $rspta['data']['numero_documento'], 
    $rspta['data']['correo'], 
    $rspta['data']['celular']
  );
  $pdf->addReference( utf8_decode( decodeCadenaHtml((empty($rspta['data']['descripcion'])) ? '- - -' :$rspta['data']['descripcion']) ));

  //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
 
  //Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
  $y = 77;  

  // Table with 20 rows and 4 columns
  $pdf->SetWidths_mc(array(80, 110));  
  $pdf->Row_mc($y, array('KILOS BRUTOS', number_format($rspta['data']['detalle_compra']['peso_neto'] ,2, '.', ',')  ));
  $y += 7 ;
  $pdf->Row_mc($y, array('SACOS',number_format($rspta['data']['detalle_compra']['sacos'], 2, '.', ',') ));
  $y += 7 ;
  $pdf->Row_mc($y, array('HUMEDAD(%)', number_format($rspta['data']['detalle_compra']['dcto_humedad'], 2, '.', ',')  ));
  $y += 7 ;
  $pdf->Row_mc($y, array('RENDIMINETO(%)', $rspta['data']['detalle_compra']['dcto_rendimiento'] ));
  $y += 7 ;
  $pdf->Row_mc($y, array('SEGUNDA(%)',number_format($rspta['data']['detalle_compra']['dcto_segunda'], 2, '.', ',') ));
  $y += 7 ;
  $pdf->Row_mc($y, array('CASCARA(%)', $rspta['data']['detalle_compra']['dcto_cascara'] ));
  $y += 7 ;
  $pdf->Row_mc($y, array('TAZA(%)',number_format($rspta['data']['detalle_compra']['dcto_taza'], 2, '.', ',') ));
  $y += 7 ;
  $pdf->Row_mc($y, array('TARA(SACOS + HUMEDAD)',number_format($rspta['data']['detalle_compra']['dcto_tara'], 2, '.', ',') ));
  $y += 7 ;
  $pdf->Row_mc($y, array('KILOS NETOS', number_format($rspta['data']['detalle_compra']['peso_neto'] ,2, '.', ',')  ));
  $y += 7 ;
  $pdf->Row_mc($y, array('QUINTALES NETOS', number_format( ($rspta['data']['detalle_compra']['quintal_neto']) ,2, '.', ',')  ));
  $y += 7 ;
  $pdf->Row_mc($y, array('PRECIO', number_format( $rspta['data']['detalle_compra']['precio_con_igv'], 2, '.', ',')  ));
  $y += 7 ;

  //Convertimos el total en letras
  $num_total = $numero_a_letra->toMoney( $rspta['data']['total_compra'], 2, 'soles' );  #echo $num_total; die;
  $decimales_mun = explode('.', $rspta['data']['total_compra']); #echo $decimales_mun[1]; die;
  $centimos = (isset($decimales_mun[1])? $decimales_mun[1] : '00' ) . '/100 CÉNTIMOS';
  $con_letra = strtoupper( utf8_decode($num_total .' '. $centimos) );
  $pdf->addCadreTVAs("- " . $con_letra);

  //Mostramos el impuesto
  $pdf->addTVAs(number_format($rspta['data']['subtotal_compra'], 2, '.',','), number_format($rspta['data']['igv_compra'], 2, '.',','), number_format($rspta['data']['total_compra'], 2, '.',','), "S/ ");
  $pdf->addCadreEurosFrancs('IGV ('.( ( empty($rspta['data']['val_igv']) ? 0 : floatval($rspta['data']['val_igv']) )  * 100 ) . '%)');
  $pdf->Output('Reporte de compra.pdf', 'I');
   
}

?>
