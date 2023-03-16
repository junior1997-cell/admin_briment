<?php 
  require '../vendor/autoload.php'; 
  use PhpOffice\PhpSpreadsheet\Spreadsheet;  
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Style\Border;
  use PhpOffice\PhpSpreadsheet\Style\Color;


  $spreadsheet = new Spreadsheet();
  $spreadsheet->getProperties()->setCreator("Integra Peru")->setTitle("Compra de Producto");
  
  $spreadsheet->setActiveSheetIndex(0);
  $spreadsheet->getActiveSheet()->getStyle('K1:K2')->getAlignment()->setVertical('center');
  $spreadsheet->getActiveSheet()->getStyle('K1:K2')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A4:K4')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal('left');
  // $spreadsheet->getActiveSheet()->getStyle('F:I')->getAlignment()->setHorizontal('right'); # subtotal
  $spreadsheet->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal('right'); # subtotal


  $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A4:K4')->getFont()->setBold(true);
  
  $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
  $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(13);
  $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(35);
  $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
  $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
  $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10); #cantidad
  $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15); #descuento
  $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(25); #Subtotal

  $spreadsheet->getActiveSheet()->getStyle('A1:K4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  $spreadsheet->getActiveSheet()->getStyle('A4:K4')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('000000'));
  
  $spreadsheet->getActiveSheet()->getStyle('A4:K4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('66c07b');
  
  
  $hojaActiva = $spreadsheet->getActiveSheet();


  // $spreadsheet->getDefaultStyle()->getFont()->setName("Tahoma");
  // $spreadsheet->getDefaultStyle()->getFont()->setSize(15);

  $hojaActiva->mergeCells('A1:A3'); #Vacio
  $hojaActiva->mergeCells('C1:J1'); #Proveedor
  $hojaActiva->mergeCells('C2:J2'); #Ruc
  $hojaActiva->mergeCells('C3:G3'); #Fecha
  $hojaActiva->mergeCells('I3:K3'); #Glosa
  $hojaActiva->mergeCells('B4:C4'); #Material  

  $hojaActiva->setCellValue('B1', 'Proveedor:');
  $hojaActiva->setCellValue('B2', 'RUC:');
  $hojaActiva->setCellValue('B3', 'Fecha:');
  $hojaActiva->setCellValue('H3', 'IGV:');

  $hojaActiva->setCellValue('A4', '#');
  $hojaActiva->setCellValue('B4', 'Material');
  $hojaActiva->setCellValue('D4', 'Categoría');
  $hojaActiva->setCellValue('E4', 'U.M.');
  $hojaActiva->setCellValue('F4', 'Cant.');
  $hojaActiva->setCellValue('G4', 'V/U');
  $hojaActiva->setCellValue('H4', 'IGV');
  $hojaActiva->setCellValue('I4', 'P/V');
  $hojaActiva->setCellValue('J4', 'Desct.');
  $hojaActiva->setCellValue('K4', 'Subtotal');

  require_once "../modelos/Venta_producto.php";
  $venta_producto = new Venta_producto();

  $rspta      = $venta_producto->ver_compra($_GET['id']);
  // echo json_encode($rspta, true);

  $hojaActiva->setCellValue('C1', $rspta['data']['venta']['nombres']);
  $hojaActiva->setCellValue('C2', $rspta['data']['venta']['numero_documento']);
  $hojaActiva->setCellValue('C3', format_d_m_a( $rspta['data']['venta']['fecha_venta']));
  $hojaActiva->setCellValue('I3', $rspta['data']['venta']['val_igv']);
  $hojaActiva->setCellValue('K1', $rspta['data']['venta']['tipo_comprobante']);
  $hojaActiva->setCellValue('K2', $rspta['data']['venta']['serie_comprobante']);

  $fila_1 = 5; 

  foreach ($rspta['data']['detalle'] as $key => $reg) {         
    
    $hojaActiva->mergeCells('B'.$fila_1.':C'.$fila_1); #aprellidos y nombres  
    
    $hojaActiva->setCellValue('A'.$fila_1, ($key+1));
    $hojaActiva->setCellValue('B'.$fila_1, decodeCadenaHtml( $reg['nombre']));
    $hojaActiva->setCellValue('D'.$fila_1, $reg['categoria']);
    $hojaActiva->setCellValue('E'.$fila_1, $reg['unidad_medida']);
    $hojaActiva->setCellValue('F'.$fila_1, $reg['cantidad']);
    $hojaActiva->setCellValue('G'.$fila_1, $reg['precio_sin_igv']);
    $hojaActiva->setCellValue('H'.$fila_1, $reg['igv']);
    $hojaActiva->setCellValue('I'.$fila_1, $reg['precio_con_igv']);
    $hojaActiva->setCellValue('J'.$fila_1, $reg['descuento']);
    $hojaActiva->setCellValue('K'.$fila_1, $reg['subtotal']);

    $spreadsheet->getActiveSheet()->getStyle('K'.$fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

    $fila_1++;
    
  }

  $hojaActiva->setCellValue('J'.($fila_1), $rspta['data']['venta']['tipo_gravada']);
  $hojaActiva->setCellValue('J'.($fila_1 + 1), "IGV(".( ( empty($rspta['data']['venta']['val_igv']) ? 0 : floatval($rspta['data']['venta']['val_igv']) )  * 100 )."%)");
  $hojaActiva->setCellValue('J'.($fila_1 + 2), "TOTAL");

  $hojaActiva->setCellValue('K'.($fila_1), number_format($rspta['data']['venta']['subtotal'], 2, '.',',') );
  $hojaActiva->setCellValue('K'.($fila_1 + 1), number_format($rspta['data']['venta']['igv'], 2, '.',',') );
  $hojaActiva->setCellValue('K'.($fila_1 + 2), number_format($rspta['data']['venta']['total'], 2, '.',','));

  $spreadsheet->getActiveSheet()->getStyle('J'.($fila_1 + 2).':'.'K'.($fila_1 + 2))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f8e700');
  
  $spreadsheet->getActiveSheet()->getStyle('J'.($fila_1).':'.'J'.($fila_1 + 2))->getFont()->setBold(true);
  $spreadsheet->getActiveSheet()->getStyle('A5:K'.($fila_1 - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  $spreadsheet->getActiveSheet()->getStyle('J'.($fila_1).':'.'K'.($fila_1 + 2))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  

  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Compra_de_producto.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
