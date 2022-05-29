<?php 
	class ClientesModel extends Mysql
	{
		private $intIdUsuario;
		private $strIdentificacion;
		private $strNombre;
		private $strApellido;
		private $intTelefono;
		private $strEmail;
		private $strPassword;
		private $strToken;
		private $intTipoId;
		private $intStatus;
		private $strNit;
		private $strNomFiscal;
		private $strDirFiscal;

		public function __construct()
		{
			parent::__construct();
		}	

		public function insertCliente(string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, string $nit, string $nomFiscal, string $dirFiscal){

			$this->strIdentificacion = $identificacion;
			$this->strNombre         = $nombre;
			$this->strApellido       = $apellido;
			$this->intTelefono       = $telefono;
			$this->strEmail          = $email;
			$this->strPassword       = $password;
			$this->intTipoId         = $tipoid;
			$this->strNit            = $nit;
			$this->strNomFiscal      = $nomFiscal;
			$this->strDirFiscal      = $dirFiscal;
			$return = 0;

			$sql = "SELECT * FROM PERSONA WHERE 
					PER_EMAIL = '{$this->strEmail}' or PER_IDENTIFICACION = '{$this->strIdentificacion}' ";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO PERSONA(PER_IDENTIFICACION,PER_NOMBRE,PER_APELLIDOS,PER_TELEFONO,PER_EMAIL,PER_PASSWORD,PER_ROL_ID,PER_NIT,PER_NOMBRE_FISCAL,PER_DIRECCION_FISCAL) 
								  VALUES(?,?,?,?,?,?,?,?,?,?)";
	        	$arrData = array($this->strIdentificacion,
        						 $this->strNombre,
        						 $this->strApellido,
        						 $this->intTelefono,
        						 $this->strEmail,
        						 $this->strPassword,
        						 $this->intTipoId,
        						 $this->strNit,
        						 $this->strNomFiscal,
        						 $this->strDirFiscal);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}

		public function selectClientes()
		{

			$sql = "SELECT PER_ID, 
			               PER_IDENTIFICACION, 
			               PER_NOMBRE, 
			               PER_APELLIDOS, 
			               PER_TELEFONO, 
			               PER_EMAIL, 
			               PER_STATUS
  					  FROM PERSONA
					 WHERE PER_ROL_ID  = 4
					   AND PER_STATUS != 0 ";
					$request = $this->select_all($sql);
					return $request;
		}	

		public function selectCliente(int $idpersona){
			$this->intIdUsuario = $idpersona;
			$sql = "SELECT PER_ID,
			               PER_IDENTIFICACION,
			               PER_NOMBRE,
			               PER_APELLIDOS,
			               PER_TELEFONO,
			               PER_EMAIL,
			               PER_NIT,
			               PER_NOMBRE_FISCAL,
			               PER_DIRECCION_FISCAL,
			               PER_STATUS, 
			               DATE_FORMAT(PER_DATECREATED,'%d-%m-%Y') as fechaRegistro 
					  FROM PERSONA
					 WHERE PER_ID     = $this->intIdUsuario
					   AND PER_ROL_ID = 4";
			$request = $this->select($sql);
			return $request;
		}

		public function updateCliente(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, string $nit, string $nomFiscal, String $dirFiscal){
			$this->intIdUsuario      = $idUsuario;
			$this->strIdentificacion = $identificacion;
			$this->strNombre         = $nombre;
			$this->strApellido       = $apellido;
			$this->intTelefono       = $telefono;
			$this->strEmail          = $email;
			$this->strPassword       = $password;
			$this->strNit            = $nit;
			$this->strNomFiscal      = $nomFiscal;
			$this->strDirFiscal      = $dirFiscal;

			$sql = "SELECT * 
			          FROM PERSONA 
			         WHERE (PER_EMAIL = '{$this->strEmail}' AND PER_ID != $this->intIdUsuario)
 						   OR (PER_IDENTIFICACION = '{$this->strIdentificacion}' AND PER_ID != $this->intIdUsuario) ";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				if($this->strPassword  != "")
				{
					$sql = "UPDATE PERSONA 
					           SET PER_IDENTIFICACION=?, 
					               PER_NOMBRE=?, 
					               PER_APELLIDOS=?, 
					               PER_TELEFONO=?, 
					               PER_EMAIL=?, 
					               PER_PASSWORD=?, 
					               PER_NIT=?, 
					               PER_NOMBRE_FISCAL=?, 
					               PER_DIRECCION_FISCAL=? 
							 WHERE PER_ID = $this->intIdUsuario ";
					$arrData = array($this->strIdentificacion,
	        						 $this->strNombre,
	        						 $this->strApellido,
	        						 $this->intTelefono,
	        						 $this->strEmail,
	        						 $this->strPassword,
	        						 $this->strNit,
	        						 $this->strNomFiscal,
	        						 $this->strDirFiscal);
				}else{
					$sql = "UPDATE PERSONA 
					           SET PER_IDENTIFICACION=?, 
					               PER_NOMBRE=?, 
					               PER_APELLIDOS=?, 
					               PER_TELEFONO=?, 
					               PER_EMAIL=?, 
					               PER_NIT=?, 
					               PER_NOMBRE_FISCAL=?, 
					               PER_DIRECCION_FISCAL=? 
							 WHERE PER_ID = $this->intIdUsuario ";
					$arrData = array($this->strIdentificacion,
	        						 $this->strNombre,
	        						 $this->strApellido,
	        						 $this->intTelefono,
	        						 $this->strEmail,
	        						 $this->strNit,
	        						 $this->strNomFiscal,
	        						 $this->strDirFiscal);
				}
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;		
		}

		public function deleteCliente(int $intIdpersona)
		{
			$this->intIdUsuario = $intIdpersona;
			$sql = "UPDATE PERSONA
			           SET PER_STATUS = ? 
			         WHERE PER_ID = $this->intIdUsuario ";
			$arrData = array(0);
			$request = $this->update($sql,$arrData);
			return $request;
		}

	}
?>