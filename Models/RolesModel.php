<?php 

	class RolesModel extends Mysql
	{
		public $intIdRol;
		public $strRol;
		public $strDescripcion;
		public $intStatus;

		public function __construct()
		{
			parent::__construct();
		}	

		public function selectRoles(){
			$whereAdmin = "";
			if($_SESSION['idUser'] != 1){
				$whereAdmin = " AND ROL_ID !=1 ";
			}

			$sql = "SELECT * 
			          FROM ROL 
			         WHERE ROL_STATUS != 0".$whereAdmin;
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectRol(int $idrol)
		{
			$this->intIdrol = $idrol;
			$sql = "SELECT * 
			          FROM ROL
			         WHERE ROL_ID = $this->intIdrol";
			$request = $this->select($sql);
			return $request;
		}

		public function insertRol(string $rol, string $descripcion, int $status){
			$return = "";
			$this->strRol         = $rol;
			$this->strDescripcion = $descripcion;
			$this->intStatus      = $status;			

			$sql = "SELECT * FROM ROL WHERE ROL_NOMBRE = '{$this->strRol}' ";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO ROL(ROL_NOMBRE,ROL_DESCRIPCION,ROL_STATUS) VALUES(?,?,?)";
	        	$arrData = array($this->strRol, $this->strDescripcion, $this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
			return $return;
		}	

		public function updateRol(int $idrol, string $rol, string $descripcion, int $status){
			$this->intIdrol       = $idrol;
			$this->strRol         = $rol;
			$this->strDescripcion = $descripcion;
			$this->intStatus      = $status;

			$sql = "SELECT * FROM ROL WHERE ROL_NOMBRE = '$this->strRol' AND ROL_ID != $this->intIdrol";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$sql = "UPDATE ROL SET ROL_NOMBRE = ?, ROL_DESCRIPCION = ?, ROL_STATUS = ? WHERE ROL_ID = $this->intIdrol ";
				$arrData = array($this->strRol, $this->strDescripcion, $this->intStatus);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;			
		}

		public function deleteRol(int $idrol)
		{
			$this->intIdrol = $idrol;
			$sql = "SELECT * FROM PERSONA WHERE PER_ROL_ID = $this->intIdrol";
			$request = $this->select_all($sql);
			if(empty($request))
			{
				$sql = "UPDATE ROL SET ROL_STATUS = ? WHERE ROL_ID = $this->intIdrol ";
				$arrData = array(0);
				$request = $this->update($sql,$arrData);
				if($request)
				{
					$request = 'ok';	
				}else{
					$request = 'error';
				}
			}else{
				$request = 'exist';
			}
			return $request;
		}

	}
 ?>