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

  //Establecemos los datos de la empresa
  $logo     = "../dist/img/default/logo_jdl.jpg";
  $ext_logo = "jpg";
  $empresa  = 'JDL TECHNOLOGY S.A.C';
  $documento= '---' ;
  $direccion= 'Jr. Los Mártires 240, Morales - San Martin.';
  $telefono = '+51 921 305 769' ;

  //Enviamos los datos de la empresa al método addSociete de la clase Factura
  $pdf->AddPage();  
  $pdf->addSociete(utf8_decode($empresa), 
  $documento . "\n" . utf8_decode("Dirección: ") . utf8_decode($direccion) . "\n" . utf8_decode("Teléfono: ") . $telefono , 
  $logo, $ext_logo);
  $pdf->fact_dev('Nota de venta','NV-000001'); #comprobante y numero
  $pdf->addClient( zero_fill(34, 6) ); #codigo de Persona
  $pdf->addDate(date('Y-m-d'));  #fecha de venta

  $pdf->temporaire( utf8_decode("JDL Technology") ); #marca de agua

  //Enviamos los datos del cliente al método addClientAdresse de la clase Factura
  $pdf->addClientAdresse(utf8_decode('Mark Garcia'), 
    utf8_decode('Tarapoto'), 
    utf8_decode('DNI') , #tipo doc.
    utf8_decode('--'), #num documento
    utf8_decode('--'), #correo
    utf8_decode('+51 983 665 719') #celular
  );
  $pdf->addReference( utf8_decode( '---' ));

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
  

    $line = [ "#" => $cont++, 
      "PRODUCTO" => utf8_decode( 'Cambio de Disco SDD 256GB'), 
      "UM" => 'U', 
      "CANT." => '1', 
      "V/U" => number_format(160, 2, '.',','), 
      "IGV" => number_format(0, 2, '.',','), 
      "P.U." => number_format(160, 2, '.',','), 
      "DSCT." => number_format(0, 2, '.',','), 
      "SUBTOTAL" => number_format(160, 2, '.',',')
    ];
    $size = $pdf->addLine($y, $line); $y += $size + 2;

    $line = [ "#" => $cont++, 
      "PRODUCTO" => utf8_decode( 'Formateo de laptop y Antivirus Eset Not'), 
      "UM" => 'U', 
      "CANT." => '1', 
      "V/U" => number_format(50, 2, '.',','), 
      "IGV" => number_format(0, 2, '.',','), 
      "P.U." => number_format(50, 2, '.',','), 
      "DSCT." => number_format(0, 2, '.',','), 
      "SUBTOTAL" => number_format(50, 2, '.',',')
    ];
    $size = $pdf->addLine($y, $line); $y += $size + 2;   
  

  //Convertimos el total en letras
  $num_total = $numero_a_letra->toMoney( 210, 2, 'soles' );  #echo $num_total; die;
  $decimales_mun = explode('.', 210); #echo $decimales_mun[1]; die;
  $centimos = (isset($decimales_mun[1])? $decimales_mun[1] : '00' ) . '/100 CÉNTIMOS';
  $con_letra = strtoupper( utf8_decode($num_total .' '. $centimos) );
  $pdf->addCadreTVAs("- " . $con_letra);


  //Mostramos el impuesto
  $pdf->addTVAs(number_format(210, 2, '.',','), number_format(0, 2, '.',','), number_format(210, 2, '.',','), "S/ ");
  $pdf->addCadreEurosFrancs('IGV ('.( ( 0 )  * 100 ) . '%)');
  $pdf->Output('Nota de venta.pdf', 'I');
   
}



?>
