<?php 
	ob_start();

	if (strlen(session_id()) < 1){

		session_start();//Validamos si existe o no la sesión
	}

	if (!isset($_SESSION["nombre"])) {
		$retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
		echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.
	} else {
		//Validamos el acceso solo al usuario logueado y autorizado.
		if ($_SESSION['acceso']==1)	{ 

			require_once "../modelos/Permiso.php";

			$permiso=new Permiso($_SESSION['idusuario']);
			$imagen_error = "this.src='../dist/svg/user_default.svg'";

			switch ($_GET["op"]){
				
				case 'listar':
					$rspta=$permiso->listar();
					//Vamos a declarar un array
					$cont=1;
					$data= Array();
					if ($rspta['status']) {
						foreach ($rspta['data'] as $key => $value) {

							$data[]=array(
								"0"=>$cont++,
								"1"=>'<button class="btn btn-info btn-sm" onclick="mostrar_usuarios('.$value['idpermiso'].')"><i class="fas fa-eye"></i></button>',
								"2"=>$value['nombre']
							);
						}
	
						$results = array(
							"sEcho"=>1, //Información para el datatables
							"iTotalRecords"=>count($data), //enviamos el total registros al datatable
							"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
							"aaData"=>$data
						);
						echo json_encode($results);
					} else {
						echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
					}					

				break;

				case 'listar_usuario':				

					$rspta=$permiso->ver_usuarios($_GET["id"]);
					$cont=1;
					//Vamos a declarar un array
					$data= Array();
					
					if ($rspta['status']) {
						foreach ($rspta['data'] as $key => $value) {

							$data[]=array(
								"0"=>$cont++,
								"1"=>'<div class="user-block">
									<img class="img-circle" src="../dist/docs/persona/perfil/'. $value['foto_perfil'] .'" alt="User Image" onerror="'.$imagen_error.'">
									<span class="username"><p class="text-primary m-b-02rem" >'. $value['nombres'] .'</p></span>
									<span class="description">'. $value['tipo_documento'] .': '. $value['numero_documento'] .' </span>
								</div>',
								"2"=>$value['cargo'], 
								"3"=> nombre_dia_semana( date("Y-m-d", strtotime($value['created_at'])) ) .', <br>'. date("d/m/Y", strtotime($value['created_at'])) .' - '. date("g:i a", strtotime($value['created_at']))
							);
						}
	
						$results = array(
							"sEcho"=>1, //Información para el datatables
							"iTotalRecords"=>count($data), //enviamos el total registros al datatable
							"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
							"aaData"=>$data
						);
						echo json_encode($results);
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