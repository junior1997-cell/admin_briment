<?php 
  require '../vendor/autoload.php'; 
  use PhpOffice\PhpSpreadsheet\Spreadsheet;  
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Style\Border;
  use PhpOffice\PhpSpreadsheet\Style\Color;


  $spreadsheet = new Spreadsheet();
  $spreadsheet->getProperties()->setCreator("Integra Peru")->setTitle("Producto mas usados");
  
  $spreadsheet->setActiveSheetIndex(0);
  $spreadsheet->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal('center');

  $spreadsheet->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
  
  $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5); # numeral
  $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(45); # Producto
  $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20); # Precio
  $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(13); # Cantidad

  $spreadsheet->getActiveSheet()->getStyle('A1:D1')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('000000'));
  
  $spreadsheet->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('66c07b');
  
  $hojaActiva = $spreadsheet->getActiveSheet();

  // $spreadsheet->getDefaultStyle()->getFont()->setName("Tahoma");
  // $spreadsheet->getDefaultStyle()->getFont()->setSize(15);

  $hojaActiva->setCellValue('A1', '#');
  $hojaActiva->setCellValue('B1', 'Producto');
  $hojaActiva->setCellValue('C1', 'Precio referencial');
  $hojaActiva->setCellValue('D1', 'Cantidad');

  require_once "../modelos/Chart_compra_producto.php";
  $chart_venta_producto = new ChartCompraProducto();

  $rspta      = $chart_venta_producto->export_productos_mas_usados($_GET['anio'], $_GET['mes']);  

  $fila_1 = 2; 

  foreach ($rspta['data'] as $key => $reg) {     
    
    $hojaActiva->setCellValue('A'.$fila_1, ($key+1));
    $hojaActiva->setCellValue('B'.$fila_1, decodeCadenaHtml( $reg['producto']));
    $hojaActiva->setCellValue('C'.$fila_1, $reg['precio_referencial']);
    $hojaActiva->setCellValue('D'.$fila_1, $reg['cantidad_vendida']);

    $spreadsheet->getActiveSheet()->getStyle('D'.$fila_1)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));

    $fila_1++;    
  }

  $spreadsheet->getActiveSheet()->getStyle('C'.($fila_1).':'.'D'.($fila_1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f8e700');
  $spreadsheet->getActiveSheet()->getStyle('A2:D'.($fila_1 - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  $spreadsheet->getActiveSheet()->getStyle('C'.($fila_1).':'.'D'.($fila_1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('000000'));
  
  // redirect output to client browser
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Producto_mas_usado.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

?>
