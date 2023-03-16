<?php
// (c) Xavier Nicolay
// Exemple de g�n�ration de devis/facture PDF

require('Factura_2.php');

$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
//Establecemos los datos de la empresa
$logo     = "../dist/img/default/empresa-logo.jpg";
$ext_logo = "jpg";
$empresa  = 'INTEGRA PERU SAC';
$documento= 'RUC: 3532432423' ;
$direccion= 'JR. LAS ROSASA / JAEN / PERU';
$telefono = '938-724-523' ;

$pdf->AddPage();
$pdf->addSociete( "INTEGRA PERU SAC",  $documento . "\n" . utf8_decode("Dirección: ") . utf8_decode($direccion) . "\n" . utf8_decode("Teléfono: ") . $telefono );
$pdf->fact_dev( "Factura ", "F-000002" );
$pdf->temporaire( "Integra Peru" ); #marca de agua
$pdf->addDate( "03/12/2003");
$pdf->addClient("CL01");
$pdf->addPageNumber("1");
$pdf->addClientAdresse("CARLA LISETH GALAN MASS \n Jr San Martin barrio la ribera S/N \n DNI: 75898727 \n Email: juniorcercado@upeu.edu.pe \n Telefono: 987-654-324");
$pdf->addReglement("Cheque de reception de facture");
$pdf->addEcheance("03/12/2003");
$pdf->addNumTVA("FR888777666");
$pdf->addReference("Se compro una computador ay un capo magnetido de la dao ér ahi ya no esta la sad es po esi bo bnde h dsdsdu dsud asdas.");
$cols=array( "#" => 8,  "DESIGNATION"  => 78, "QUANTITE"=> 22, "P.U. HT"=> 26, "MONTANT H.T." => 30, "TVA"  => 11 );
$pdf->addCols( $cols);
$cols=array( "#" => "C", "DESIGNATION" => "L", "QUANTITE" => "C", "P.U. HT" => "R", "MONTANT H.T." => "R", "TVA" => "C" );
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);

$y    = 109;
$line = array( "#" => "1", "DESIGNATION"=> "Elosan 720 SC", "QUANTITE"=> "1", "P.U. HT" => "600.00", "MONTANT H.T." => "600.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "2", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "3", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "4", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "5", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "6", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "7", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "8", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "9", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "10", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "11", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "12", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "13", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "14", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "15", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "16", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "17", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "18", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "19", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "20", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "21", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "22", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "23", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "24", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;
$line = array( "#" => "25", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "26", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "27", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "#" => "28", "DESIGNATION"  => "Amistar 50 WG", "QUANTITE" => "1", "P.U. HT" => "10.00", "MONTANT H.T." => "60.00", "TVA" => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$pdf->addCadreTVAs();
        
# ======================================= Diseño del Footer ==========================================
// invoice = array( "px_unit" => value, "qte"  => qte, "tva" => code_tva );
// tab_tva = array( "1"  => 19.6, "2"  => 5.5, ... );
// params  = array( "RemiseGlobale"   => [0|1],
//                  "remise_tva"      => [1|2...],  // {el descuento se aplica a este código de IVA}
//                  "remise"          => value,     // {importe de descuento}
//                  "remise_percent"  => percent,   // {porcentaje de descuento sobre este importe de IVA}
//                  "FraisPort"       => [0|1],
//                  "portTTC"         => value,     // importe de los gastos de envío incluidos los impuestos --   // IVA por defecto = 19,6%
//                  "portHT"          => value,     // importe de los gastos de envío sin IVA
//                  "portTVA"         => tva_value, // valor del IVA que se aplicará al importe sin IVA
//                  "AccompteExige"   => [0|1],
//                  "accompte"        => value    // importe del depósito (impuestos incluidos)
//                  "accompte_percent"=> percent  // porcentaje de depósito (impuestos incluidos)
//                  "Remarque"        => "texte");              // texto

$tot_prods = array( array ( "px_unit" => 600, "qte" => 1, "tva" => 1 ), array ( "px_unit" =>  10, "qte" => 1, "tva" => 1 ));
$tab_tva = array( "1" => 19.6, "2"  => 5.5);
$params  = array( "RemiseGlobale"   => 1,
                  "remise_tva"      => 1,       // {la remise s'applique sur ce code TVA}
                  "remise"          => 0,       // {montant de la remise}
                  "remise_percent"  => 10,      // {pourcentage de remise sur ce montant de TVA}
                  "FraisPort"       => 1,
                  "portTTC"         => 10,      // montant des frais de ports TTC -- // par defaut la TVA = 19.6 %
                  "portHT"          => 0,       // montant des frais de ports HT
                  "portTVA"         => 19.6,    // valeur de la TVA a appliquer sur le montant HT
                  "AccompteExige"   => 1,
                  "accompte"        => 0,     // montant de l'acompte (TTC)
                  "accompte_percent"=> 15,    // pourcentage d'acompte (TTC)
                  "Remarque"        => utf8_decode("MIL TREINTA Y SEIS SOLES 00/100 CÉNTIMOS") );

$pdf->addTVAs( $params, $tab_tva, $tot_prods);
$pdf->addCadreEurosFrancs();
$pdf->Output();
?>
