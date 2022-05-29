<?php  
	class Clientes extends Controllers{
		public function __construct()
		{
			parent::__construct();
			sessionStar();			
			session_regenerate_id(true);
			if(empty($_SESSION['login'])){
				header('Location: '.base_url().'/login');
			}
			
			getpermisos(3);
		}

		public function Clientes()
		{
			if($_SESSION['permisosMod']['PRM_R'] == 0){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag']          = "Clientes";
			$data['page_title']        = "CLIENTES <small>Tienda Virtual</small>";
			$data['page_name']         = "clientes";
			$data['page_functions_js'] = "functions_clientes.js";
			$this->views->getView($this,"clientes",$data);
		}

		public function setCliente(){
			if($_POST){				
				if(empty($_POST['txtIdentificacion']) || empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono']) || empty($_POST['txtEmail']) || empty($_POST['txtNit']) || empty($_POST['txtNombreFiscal']) || empty($_POST['txtDirFiscal']))
				{
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{ 
					$idUsuario         = intval($_POST['idUsuario']);
					$strIdentificacion = strClean($_POST['txtIdentificacion']);
					$strNombre         = ucwords(strClean($_POST['txtNombre']));
					$strApellido       = ucwords(strClean($_POST['txtApellido']));
					$intTelefono       = intval(strClean($_POST['txtTelefono']));
					$strEmail          = strtolower(strClean($_POST['txtEmail']));
					$strNit            = strClean($_POST['txtNit']);
					$strNomFiscal      = strClean($_POST['txtNombreFiscal']);
					$strDirFiscal      = strClean($_POST['txtDirFiscal']);
					$intTipoId         = 4;
					$request_user      = "";
					if($idUsuario == 0)
					{
						$option = 1;
						$strPassword =  empty($_POST['txtPassword']) ? passGenerator() : $_POST['txtPassword'];
						$strPasswordEncript = hash("SHA256",$strPassword);
						if($_SESSION['permisosMod']['PRM_W']){
							$request_user = $this->model->insertCliente($strIdentificacion,
																		$strNombre, 
																		$strApellido, 
																		$intTelefono, 
																		$strEmail,
																		$strPasswordEncript, 
																		$intTipoId,
																		$strNit,
																		$strNomFiscal,
																		$strDirFiscal);
						}
					}else{
						$option = 2;
						$strPassword =  empty($_POST['txtPassword']) ? "" : hash("SHA256",$_POST['txtPassword']);
						if($_SESSION['permisosMod']['PRM_U']){
							$request_user = $this->model->updateCliente($idUsuario,
																		$strIdentificacion, 
																		$strNombre,
																		$strApellido, 
																		$intTelefono, 
																		$strEmail,
																		$strPassword, 
																		$strNit,
																		$strNomFiscal,
																		$strDirFiscal);
						}
					}

					if($request_user > 0 )
					{
			 			if($option == 1){
							$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
							$nombreUsuario = $strNombre.' '.$strApellido;
							$dataUsuario = array('nombreUsuario' => $nombreUsuario,
											 	 'email' => $strEmail,
											 	 'password' => $strPassword,
											     'asunto' => 'Bienvenido a tu tienda en línea');

							sendEmail($dataUsuario,'email_bienvenida');
			 			}else{
			 				$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
			 			}
					}else if($request_user == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! el email o la identificación ya existe, ingrese otro.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
			 		}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getClientes()
		{
			if($_SESSION['permisosMod']['PRM_R']){
				$arrData = $this->model->selectClientes();				
				for ($i=0; $i < count($arrData); $i++) {
					$btnView   = '';
					$btnEdit   = '';
					$btnDelete = '';

					if($_SESSION['permisosMod']['PRM_R'] == 1){
						$btnView   = '<button class="btn btn-info btn-sm btnViewInfo" onClick="fntViewInfo('.$arrData[$i]['PER_ID'].')" title="Ver Cliente"><i class="far fa-eye"></i></button>';
					}

					if($_SESSION['permisosMod']['PRM_U']){
						$btnEdit = '<button class="btn btn-primary  btn-sm btnEditInfo" onClick="fntEditInfo(this,'.$arrData[$i]['PER_ID'].')" title="Editar Cliente"><i class="fas fa-pencil-alt"></i></button>';
					}

					if($_SESSION['permisosMod']['PRM_D'] == 1){
						$btnDelete = '<button class="btn btn-danger btn-sm btnDelInfoCliente" onClick="fntDelInfo('.$arrData[$i]['PER_ID'].')" title="Eliminar Cliente"><i class="far fa-trash-alt"></i></button>';
					}				
					$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}		

		public function getCliente($idpersona){
			if($_SESSION['permisosMod']['PRM_R']){
				$idusuario = intval($idpersona);
				if($idusuario > 0)
				{
					$arrData = $this->model->selectCliente($idusuario);
					if(empty($arrData))
					{
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function delCliente()
		{
			if($_POST){
				if($_SESSION['permisosMod']['PRM_D']){
					$intIdpersona = intval($_POST['idUsuario']);
					$requestDelete = $this->model->deleteCliente($intIdpersona);
					if($requestDelete)
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el cliente');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el cliente.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

	}
?>