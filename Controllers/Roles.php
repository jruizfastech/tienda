<?php 

	class Roles extends Controllers{
		public function __construct()
		{
			sessionStar();
			parent::__construct();
			//session_start();
			//session_regenerate_id(true);			
			if(empty($_SESSION['login'])){
				header('Location: '.base_url().'/login');
				die();
			}
			
			getpermisos(2);
		}

		public function Roles()
		{
			if($_SESSION['permisosMod']['PRM_R'] == 0){
				header("Location:".base_url().'/dashboard');
			}			
			$data['page_id']           = 3;
			$data['page_tag']          = "Roles Usuario";
			$data['page_name']         = "rol_usuario";
			$data['page_title']        = "Roles usuario <small> TiendaVirtual</small>";			
			$data['page_functions_js'] = "functions_roles.js";
			$this->views->getView($this,"roles",$data);
		}

		public function getRoles(){
			if($_SESSION['permisosMod']['PRM_R']){
				$btnView   = '';
				$btnEdit   = '';
				$btnDelete = '';				
				$arrData = $this->model->selectRoles();
				for ($i=0; $i < count($arrData); $i++) { 
					if($arrData[$i]['ROL_STATUS'] == 1){
						$arrData[$i]['ROL_STATUS'] = '<span class="badge badge-success">Activo</span>';	
					} else{
						$arrData[$i]['ROL_STATUS'] = '<span class="badge badge-danger">Inactivo</span>';	
					}

					if($_SESSION['permisosMod']['PRM_U'] == 1){
						$btnView   = '<button class="btn btn-secondary btn-sm btnPermisosRol" onClick="fntPermisos('.$arrData[$i]['ROL_ID'].')" title="Permisos"><i class="fas fa-key"></i></button>';
						$btnEdit   = '<button class="btn btn-primary btn-sm btnEditRol" onClick="fntEditRol('.$arrData[$i]['ROL_ID'].')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
					}				
					if($_SESSION['permisosMod']['PRM_D'] == 1){
						$btnDelete = '<button class="btn btn-danger btn-sm btnDelRol" onClick="fntDelRol('.$arrData[$i]['ROL_ID'].')" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
					}				
					$arrData[$i]['OPTIONS'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getSelectRoles()
		{
			$htmlOptions = "";
			$arrData = $this->model->selectRoles();
			if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					if($arrData[$i]['ROL_STATUS'] == 1 ){
					$htmlOptions .= '<option value="'.$arrData[$i]['ROL_ID'].'">'.$arrData[$i]['ROL_NOMBRE'].'</option>';
					}
				}
			}
			echo $htmlOptions;
			die();		
		}		

		public function getRol(int $idrol)
		{
			if($_SESSION['permisosMod']['PRM_R']){
				$intIdrol = intval(strClean($idrol));
				if($intIdrol > 0)
				{
					$arrData = $this->model->selectRol($intIdrol);
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

		public function setRol(){
			if($_SESSION['permisosMod']['PRM_W']){
				$intIdrol       = intval($_POST['idRol']);
				$strRol         = strClean($_POST['txtNombre']);
				$strDescripcion = strClean($_POST['txtDescripcion']);
				$intStatus      = intval($_POST['listStatus']);

				if($intIdrol == 0)
				{
					//Crear
					$request_rol = $this->model->insertRol($strRol, $strDescripcion,$intStatus);
					$option = 1;
				}else{
					//Actualizar
					$request_rol = $this->model->updateRol($intIdrol, $strRol, $strDescripcion, $intStatus);
					$option = 2;
				}

				if($request_rol > 0 )
				{
					if($option == 1)
					{
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					}else{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				}else if($request_rol == 'exist'){			
					$arrResponse = array('status' => false, 'msg' => '¡Atención! El Rol ya existe.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function delRol()
		{
			if($_SESSION['permisosMod']['PRM_D']){
				if($_POST){
					$intIdrol = intval($_POST['idrol']);
					$requestDelete = $this->model->deleteRol($intIdrol);
					if($requestDelete == 'ok')
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Rol');
					}else if($requestDelete == 'exist'){
						$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un Rol asociado a usuarios.');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Rol.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}		
	}
 ?>