<?php 

	class PermisosModel extends Mysql
	{
		public $intIdpermiso;
		public $intRolid;
		public $intModuloid;
		public $r;
		public $w;
		public $u;
		public $d;

		public function __construct()
		{
			parent::__construct();
		}	

		public function selectModulos()
		{
			$sql = "SELECT * FROM MODULO WHERE MOD_STATUS != 0";
			$request = $this->select_all($sql);
			return $request;
		}	

		public function selectPermisosRol(int $idrol)
		{
			$this->intRolid = $idrol;
			$sql = "SELECT * FROM PERMISOS WHERE PRM_ROL_ID = $this->intRolid";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deletePermisos(int $idrol)
		{
			$this->intRolid = $idrol;
			$sql = "DELETE FROM PERMISOS WHERE PRM_ROL_ID = $this->intRolid";
			$request = $this->delete($sql);
			return $request;
		}

		public function insertPermisos(int $idrol, int $idmodulo, int $r, int $w, int $u, int $d){
			$this->intRolid = $idrol;
			$this->intModuloid = $idmodulo;
			$this->r = $r;
			$this->w = $w;
			$this->u = $u;
			$this->d = $d;
			$query_insert  = "INSERT INTO PERMISOS(PRM_ROL_ID,PRM_MOD_ID,PRM_R,PRM_W,PRM_U,PRM_D) VALUES(?,?,?,?,?,?)";
        	$arrData = array($this->intRolid, $this->intModuloid, $this->r, $this->w, $this->u, $this->d);
        	$request_insert = $this->insert($query_insert,$arrData);		
	        return $request_insert;
		}		

		public function permisosModulo(int $idrol){
			$this->intRolid = $idrol;
			$sql = "SELECT PRM_ROL_ID,
						   PRM_MOD_ID,
						   MOD_TITULO as modulo,
						   PRM_R,
						   PRM_W,
						   PRM_U,
						   PRM_D
					  FROM PERMISOS INNER JOIN MODULO ON PRM_MOD_ID = MOD_ID
					 WHERE PRM_ROL_ID = $this->intRolid";
			$request = $this->select_all($sql);
			$arrPermisos = array();
			for ($i=0; $i < count($request); $i++) { 
				$arrPermisos[$request[$i]['PRM_MOD_ID']] = $request[$i];
			}
			return $arrPermisos;
		}

	}
 ?>