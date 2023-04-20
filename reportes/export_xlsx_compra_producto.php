<?php 
  require '../vendor/autoload.php'; 
  use PhpOffice\PhpSpreadsheet\Spreadsheet;  
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Style\Border;
  use PhpOffice\PhpSpreadsheet\Style\Color;


  $spreadsheet = new Spreadsheet();
  $spreadsheet->getProperties()->setCreator("Briment Company SAC")->setTitle("Compra de Producto");
  
  $spreadsheet->setActiveSheetIndex(0);
  $spreadsheet->getActiveSheet()->getStyle('L1:L2')->getAlignment()->setVertical('center');
  $spreadsheet->getActiveSheet()->getStyle('G5:G50')->getAlignment()->setHorizontal('center'); #cantidad
  $spreadsheet->getActiveSheet()->getStyle('L1:L2')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A4:L4')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal('left');
  // $spreadsheet->getActiveSheet()->getStyle('F:I')->getAlignment()->setHorizontal('right'); # subtotal
  $spreadsheet->getActiveSheet()->getStyle('L1:L2')->getAlignment()->setHorizontal('center'); # subtotal
  $spreadsheet->getActiveSheet()->getStyle('L5:L50')->getAlignment()->setHorizontal('right'); # subtotal


  $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true); #proveedor
  $spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setBold(true); #ruc
  $spreadsheet->getActiveSheet()->getStyle('B3')->getFont()->setBold(true); #Fecha
  $spreadsheet->getActiveSheet()->getStyle('L1')->getFont()->setBold(true); #factura
  $spreadsheet->getActiveSheet()->getStyle('I3')->getFont()->setBold(true); #igv
  $spreadsheet->getActiveSheet()->getStyle('A4:L4')->getFont()->setBold(true); #descripcion de venta
  
  $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5); #
  $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(13);
  $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(35);
  $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20); #laboratorio
  $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20); #lote
  $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15); #unidad medida
  $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10); #cantidad
  $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15); #descuento
  $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(25); #Subtotal

  $spreadsheet->getActiveSheet()->getStyle('A1:L4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  $spreadsheet->getActiveSheet()->getStyle('A4:L4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('000000'));
  
  $spreadsheet->getActiveSheet()->getStyle('A4:L4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('66c07b');
  
  
  $hojaActiva = $spreadsheet->getActiveSheet();


  // $spreadsheet->getDefaultStyle()->getFont()->setName("Tahoma");
  // $spreadsheet->getDefaultStyle()->getFont()->setSize(15);

  $hojaActiva->mergeCells('A1:A3'); #Vacio
  $hojaActiva->mergeCells('C1:K1'); #Proveedor
  $hojaActiva->mergeCells('C2:K2'); #Ruc
  $hojaActiva->mergeCells('C3:H3'); #Fecha
  $hojaActiva->mergeCells('I3:J3'); #IGV
  $hojaActiva->mergeCells('K3:L3'); #val IGV
  $hojaActiva->mergeCells('B4:C4'); #Material  

  $hojaActiva->setCellValue('B1', 'Proveedor:');
  $hojaActiva->setCellValue('B2', 'RUC:');
  $hojaActiva->setCellValue('B3', 'Fecha:');
  $hojaActiva->setCellValue('I3', 'IGV:');

  $hojaActiva->setCellValue('A4', '#');
  $hojaActiva->setCellValue('B4', 'Material');
  $hojaActiva->setCellValue('D4', 'Laboratorio');
  $hojaActiva->setCellValue('E4', 'Lote - F.V.');
  $hojaActiva->setCellValue('F4', 'U.M.');
  $hojaActiva->setCellValue('G4', 'Cant.');
  $hojaActiva->setCellValue('H4', 'V/U');
  $hojaActiva->setCellValue('I4', 'IGV');
  $hojaActiva->setCellValue('J4', 'P/V');
  $hojaActiva->setCellValue('K4', 'Desct.');
  $hojaActiva->setCellValue('L4', 'Subtotal');

  require_once "../modelos/Compra_producto.php";
  $compra_producto = new Compra_producto();

  $rspta      = $compra_producto->ver_compra($_GET['id']);
  // echo json_encode($rspta, true);

  $hojaActiva->setCellValue('C1', $rspta['data']['compra']['nombres']);
  $hojaActiva->setCellValue('C2', $rspta['data']['compra']['numero_documento']);
  $hojaActiva->setCellValue('C3', format_d_m_a( $rspta['data']['compra']['fecha_compra']));
  $hojaActiva->setCellValue('K3', $rspta['data']['compra']['val_igv']);
  $hojaActiva->setCellValue('L1', $rspta['data']['compra']['tipo_comprobante']);
  $hojaActiva->setCellValue('L2', $rspta['data']['compra']['serie_comprobante']);

  $fila_1 = 5; 

  foreach ($rspta['data']['detalle'] as $key => $reg) {         
    
    $hojaActiva->mergeCells('B'.$fila_1.':C'.$fila_1); #aprellidos y nombres  
    
    $hojaActiva->setCellValue('A'.$fila_1, ($key+1));
    $hojaActiva->setCellValue('B'.$fila_1, decodeCadenaHtml( $reg['nombre']));
    $hojaActiva->setCellValue('D'.$fila_1, $reg['laboratorio']);
    $hojaActiva->setCellValue('E'.$fila_1, $reg['lote'] . ' - ' . $reg['fecha_vencimiento']);
    $hojaActiva->setCellValue('F'.$fila_1, $reg['unidad_medida']);
    $hojaActiva->setCellValue('G'.$fila_1, $reg['cantidad']);
    $hojaActiva->setCellValue('H'.$fila_1, $reg['precio_sin_igv']);
    $hojaActiva->setCellValue('I'.$fila_1, $reg['igv']);
    $hojaActiva->setCellValue('J'.$fila_1, $reg['precio_con_igv']);
    $hojaActiva->setCellValue('K'.$fila_1, $reg['descuento']);
    $hojaActiva->setCellValue('L'.$fila_1, $reg['subtotal']);

    $spreadsheet->getActiveSheet()->getStyle('L'.$fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

    $fila_1++;
    
  }

  $hojaActiva->setCellValue('K'.($fila_1), $rspta['data']['compra']['tipo_gravada']);
  $hojaActiva->setCellValue('K'.($fila_1 + 1), "TOTAL DCTO.");
  $hojaActiva->setCellValue('K'.($fila_1 + 2), "IGV(".( ( empty($rspta['data']['compra']['val_igv']) ? 0 : floatval($rspta['data']['compra']['val_igv']) )  * 100 )."%)");
  $hojaActiva->setCellValue('K'.($fila_1 + 3), "TOTAL");

  $hojaActiva->setCellValue('L'.($fila_1), number_format($rspta['data']['compra']['subtotal'], 2, '.',',') );
  $hojaActiva->setCellValue('L'.($fila_1 + 1), number_format($rspta['data']['compra']['total_descuento'], 2, '.',',') );
  $hojaActiva->setCellValue('L'.($fila_1 + 2), number_format($rspta['data']['compra']['igv'], 2, '.',',') );
  $hojaActiva->setCellValue('L'.($fila_1 + 3), number_format($rspta['data']['compra']['total_compra'], 2, '.',','));

  $spreadsheet->getActiveSheet()->getStyle('K'.($fila_1 + 3).':'.'L'.($fila_1 + 3))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f8e700');
  
  $spreadsheet->getActiveSheet()->getStyle('K'.($fila_1).':'.'K'.($fila_1 + 3))->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A5:L'.($fila_1 - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  $spreadsheet->getActiveSheet()->getStyle('K'.($fila_1).':'.'L'.($fila_1 + 3))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  

  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Compra_de_producto.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
