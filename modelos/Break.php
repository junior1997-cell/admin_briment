<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion_v2.php";

Class Breaks
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	
	//Implementamos un método para insertar registros
	public function insertar_editar($array_break,$fechas_semanas_btn,$idproyecto){

		$total  = 0;
		$desglese_break = json_decode($array_break,true); 
		$fechas_semanas_btn = json_decode($fechas_semanas_btn, true);
		$retorno = "";

		// registramos o editamos los "Break por semana"
		foreach ($desglese_break as $indice => $key) {
		
			if ( empty($key['idbreak'])) {

				// insertamos un nuevo registro
				$sql_2="INSERT INTO breaks (idproyecto, fecha_compra, dia_semana, cantidad, costo_parcial, descripcion)
				VALUES ('$idproyecto', '".$key['fecha_compra']."', '".$key['dia_semana']."', '".$key['cantidad_compra']."', '".$key['precio_compra']."', '".$key['descripcion_compra']."')";

				// ejecutarConsulta($sql_2) or $sw = false;
				$retorno=ejecutarConsulta($sql_2);

				if ($retorno['status'] == false) {  return $retorno; }

			} else {

				# editamos el registro existente
				$sql_3="UPDATE breaks SET idproyecto='$idproyecto', 
				fecha_compra='".$key['fecha_compra']."', 
				dia_semana='".$key['dia_semana']."', 
				cantidad='".$key['cantidad_compra']."', 
				costo_parcial='".$key['precio_compra']."',
				descripcion='".$key['descripcion_compra']."'	
				WHERE idbreak='".$key['idbreak']."';";
				
				//ejecutarConsulta($sql_3) or $sw = false;
				$retorno=ejecutarConsulta($sql_3);

				if ($retorno['status'] == false) {  return $retorno; }
			}

			$total = $total+ floatval($key['precio_compra']); 
		}

		foreach ($fechas_semanas_btn as $key => $value) {

			$sql_4 = "SELECT idsemana_break FROM semana_break WHERE idproyecto='$idproyecto' AND fecha_inicial = '".$value['fecha_in_btn']."' AND  fecha_final = '".$value['fecha_fi_btn']."' ";
			
			$buscar_idbreak = ejecutarConsultaSimpleFila($sql_4);

			if ($buscar_idbreak['status'] == false) {  return $buscar_idbreak; }

			if(empty($buscar_idbreak['data']['idsemana_break'])){

				$sql5 = "INSERT INTO semana_break(idproyecto, numero_semana, fecha_inicial, fecha_final, total) 
				VALUES ('$idproyecto','".$value['num_semana']."','".$value['fecha_in_btn']."','".$value['fecha_fi_btn']."','$total')";

                // ejecutarConsulta($sql5) or $sw = false;
                $retorno=ejecutarConsulta($sql5);

				if ($retorno['status'] == false) {  return $retorno; }

			}else{
				$sql6 = " UPDATE semana_break SET 
					idproyecto='$idproyecto',
					numero_semana='".$value['num_semana']."',
					fecha_inicial='".$value['fecha_in_btn']."',
					fecha_final='".$value['fecha_fi_btn']."',
					total='$total'
					WHERE  idsemana_break='".$buscar_idbreak['data']['idsemana_break']."';";
				 // ejecutarConsulta($sql6) or $sw = false;
				$retorno=ejecutarConsulta($sql6);

				if ($retorno['status'] == false) {  return $retorno; }
			}
		}
		return $retorno;	
	}

	public function listarsemana_botones($nube_idproyecto){
		$sql="SELECT p.idproyecto, p.fecha_inicio, p.fecha_fin, p.plazo, p.fecha_pago_obrero, p.fecha_valorizacion FROM proyecto as p WHERE p.idproyecto='$nube_idproyecto'";
		return ejecutarConsultaSimpleFila($sql);
	}
	//ver detalle semana a semana
	public function ver_detalle_semana_dias($f1,$f2,$nube_idproyect){
		//var_dump($f1,$f2,$nube_idproyect);die();
		$sql="SELECT * FROM breaks WHERE idproyecto='$nube_idproyect' AND fecha_compra BETWEEN '$f1' AND '$f2' ";
		return ejecutarConsultaArray($sql);
	}	

	public function listar($nube_idproyecto)
	{
		$sql="SELECT * FROM semana_break WHERE idproyecto ='$nube_idproyecto' ORDER BY numero_semana DESC";
		return ejecutarConsulta($sql);
	}

	// ------------------------------------------------------------------------------------
	// ------------------------------------------------------------------------------------   
	// ------------------C O M P R O B A N T E S   B R E A K -----------------------------
	// ------------------------------------------------------------------------------------
	// ------------------------------------------------------------------------------------

	public function insertar_comprobante($idsemana_break,$forma_pago,$tipo_comprobante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$val_igv,$tipo_gravada,$imagen2,$ruc,$razon_social,$direccion){
		
		if ($tipo_comprobante =='Factura' || $tipo_comprobante =='Boleta' ) { } else {
			$ruc =''; $razon_social =''; $direccion ='';
		}

		$sql = "SELECT idsemana_break, ruc, razon_social, nro_comprobante, forma_de_pago, fecha_emision,tipo_comprobante, descripcion, estado, estado_delete 
		FROM factura_break WHERE idsemana_break='$idsemana_break' AND tipo_comprobante = '$tipo_comprobante' AND ruc = '$ruc';";
		$val_prob = ejecutarConsultaArray($sql);
		
		if ($val_prob['status'] == false) { return  $val_prob;}
	
		if (empty($val_prob['data']) || $tipo_comprobante=='Ninguno') {
		
		  $sql="INSERT INTO factura_break (idsemana_break,nro_comprobante, fecha_emision, monto, igv, val_igv, tipo_gravada, subtotal,forma_de_pago, tipo_comprobante, descripcion, comprobante,ruc, razon_social, direccion) 
		  VALUES ('$idsemana_break','$nro_comprobante','$fecha_emision','$monto','$igv','$val_igv','$tipo_gravada','$subtotal','$forma_pago','$tipo_comprobante','$descripcion','$imagen2','$ruc','$razon_social','$direccion')";
		  return ejecutarConsulta($sql);
	
		} else {
	
		  $info_repetida = '';
	
		  foreach ($val_prob['data'] as $key => $value) {
			$info_repetida .= '<li class="text-left font-size-13px">
			<span class="font-size-18px text-danger"><b >'.$value['tipo_comprobante'].': </b> '.$value['nro_comprobante'].'</span><br>
			<b>Razón Social: </b>'.$value['razon_social'].'<br>
			<b>Ruc: </b>'.$value['ruc'].'<br>          
			<b>Fecha: </b>'.format_d_m_a($value['fecha_emision']).'<br>
			<b>Forma de pago: </b>'.$value['forma_de_pago'].'<br>
			<b>Papelera: </b>'.( $value['estado']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO') .' <b>|</b> 
			<b>Eliminado: </b>'. ($value['estado_delete']==0 ? '<i class="fas fa-check text-success"></i> SI':'<i class="fas fa-times text-danger"></i> NO').'<br>
			<hr class="m-t-2px m-b-2px">
			</li>'; 
		}

		  return $sw = array( 'status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ol>'.$info_repetida.'</ol>', 'id_tabla' => '' );
		}
	}
	
	//Implementamos un método para editar registros
	public function editar_comprobante($idfactura_break,$idsemana_break,$forma_pago,$tipo_comprobante,$nro_comprobante,$monto,$fecha_emision,$descripcion,$subtotal,$igv,$val_igv,$tipo_gravada,$imagen2,$ruc,$razon_social,$direccion){
		
		if ($tipo_comprobante =='Factura' || $tipo_comprobante =='Boleta' ) { } else {
			$ruc =''; $razon_social =''; $direccion ='';
		}

		$sql="UPDATE `factura_break` SET 		
		idsemana_break='$idsemana_break',
		forma_de_pago='$forma_pago',
		nro_comprobante='$nro_comprobante',
		fecha_emision='$fecha_emision',
		monto='$monto',
		igv='$igv',
		val_igv='$val_igv',
		tipo_gravada='$tipo_gravada',
		subtotal='$subtotal',
		tipo_comprobante='$tipo_comprobante',
		descripcion='$descripcion',
		comprobante='$imagen2',
		ruc='$ruc',
		razon_social='$razon_social',
		direccion='$direccion'
		 WHERE idfactura_break='$idfactura_break';";	
		return ejecutarConsulta($sql);	
		//return $vaa;
	}

	// obtebnemos los DOCS para eliminar
	public function obtenerDoc($idfactura_break) {

		$sql = "SELECT comprobante FROM factura_break WHERE idfactura_break  ='$idfactura_break'";	
		return ejecutarConsulta($sql);
	}

	public function listar_comprobantes($idsemana_break){

		$sql="SELECT * FROM factura_break WHERE idsemana_break  ='$idsemana_break' AND estado_delete='1' AND estado='1' ORDER BY fecha_emision DESC";
		return ejecutarConsulta($sql);
	}

	//mostrar_comprobante
	public function mostrar_comprobante($idfactura_break){
		
		$sql="SELECT * FROM factura_break WHERE idfactura_break ='$idfactura_break '";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos un método para activar 
	public function desactivar_comprobante($idfactura_break){
		
		$sql="UPDATE factura_break SET estado='0' WHERE idfactura_break ='$idfactura_break '";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar 
	public function activar_comprobante($idfactura_break){
		
		$sql="UPDATE factura_break SET estado='1' WHERE idfactura_break ='$idfactura_break '";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar 
	public function eliminar_comprobante($idfactura_break){
	
		$sql="UPDATE factura_break SET estado_delete='0' WHERE idfactura_break ='$idfactura_break '";
		return ejecutarConsulta($sql);
	}

	public function total_monto_comp($idsemana_break){
		
		$sql="SELECT SUM(monto) as total FROM factura_break WHERE idsemana_break='$idsemana_break' AND estado_delete='1' AND estado='1'";
		return ejecutarConsultaSimpleFila($sql);

	}


}

?>