<?php 

	class UsuariosModel extends Mysql
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

		public function insertUsuario(string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, int $status){

			$this->strIdentificacion = $identificacion;
			$this->strNombre = $nombre;
			$this->strApellido = $apellido;
			$this->intTelefono = $telefono;
			$this->strEmail = $email;
			$this->strPassword = $password;
			$this->intTipoId = $tipoid;
			$this->intStatus = $status;
			$return = 0;

			$sql = "SELECT * FROM PERSONA WHERE 
					PER_EMAIL = '{$this->strEmail}' or PER_IDENTIFICACION = '{$this->strIdentificacion}' ";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO PERSONA(PER_IDENTIFICACION,PER_NOMBRE,PER_APELLIDOS,PER_TELEFONO,PER_EMAIL,PER_PASSWORD,PER_ROL_ID,PER_STATUS) 
								  VALUES(?,?,?,?,?,?,?,?)";
	        	$arrData = array($this->strIdentificacion,
        						 $this->strNombre,
        						 $this->strApellido,
        						 $this->intTelefono,
        						 $this->strEmail,
        						 $this->strPassword,
        						 $this->intTipoId,
        						 $this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}

		public function selectUsuarios()
		{
			$whereAdmin = "";
			if($_SESSION['idUser'] != 1){
				$whereAdmin = " AND PER_ID != 1";
			}
			$sql = "SELECT PER_ID, 
			               PER_IDENTIFICACION, 
			               PER_NOMBRE, 
			               PER_APELLIDOS, 
			               PER_TELEFONO, 
			               PER_EMAIL, 
			               PER_STATUS, 
			               ROL_ID,
			               ROL_NOMBRE
  					  FROM PERSONA INNER JOIN ROL ON PER_ROL_ID = ROL_ID
					 WHERE PER_STATUS != 0 ".$whereAdmin;
					$request = $this->select_all($sql);
					return $request;
		}

		public function selectUsuario(int $idpersona){
			$this->intIdUsuario = $idpersona;
			$sql = "SELECT PER_ID,PER_IDENTIFICACION,PER_NOMBRE,PER_APELLIDOS,PER_TELEFONO,PER_EMAIL,PER_NIT,PER_NOMBRE_FISCAL,PER_DIRECCION_FISCAL,ROL_ID,ROL_NOMBRE,PER_STATUS, DATE_FORMAT(PER_DATECREATED, '%d-%m-%Y') as fechaRegistro 
					  FROM PERSONA INNER JOIN ROL ON PER_ROL_ID = ROL_ID
					 WHERE PER_ID = $this->intIdUsuario";
			$request = $this->select($sql);
			return $request;
		}

		public function updateUsuario(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, int $status){
			$this->intIdUsuario = $idUsuario;
			$this->strIdentificacion = $identificacion;
			$this->strNombre = $nombre;
			$this->strApellido = $apellido;
			$this->intTelefono = $telefono;
			$this->strEmail = $email;
			$this->strPassword = $password;
			$this->intTipoId = $tipoid;
			$this->intStatus = $status;

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
					               PER_ROL_ID=?, 
					               PER_STATUS=? 
							 WHERE PER_ID = $this->intIdUsuario ";
					$arrData = array($this->strIdentificacion,
	        						$this->strNombre,
	        						$this->strApellido,
	        						$this->intTelefono,
	        						$this->strEmail,
	        						$this->strPassword,
	        						$this->intTipoId,
	        						$this->intStatus);
				}else{
					$sql = "UPDATE PERSONA
					           SET PER_IDENTIFICACION=?, 
					               PER_NOMBRE=?, 
					               PER_APELLIDOS=?, 
					               PER_TELEFONO=?, 
					               PER_EMAIL=?, 
					               PER_ROL_ID=?, 
					               PER_STATUS=? 
							 WHERE PER_ID = $this->intIdUsuario ";
					$arrData = array($this->strIdentificacion,
	        						$this->strNombre,
	        						$this->strApellido,
	        						$this->intTelefono,
	        						$this->strEmail,
	        						$this->intTipoId,
	        						$this->intStatus);
				}
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;		
		}

		public function deleteUsuario(int $intIdpersona)
		{
			$this->intIdUsuario = $intIdpersona;
			$sql = "UPDATE PERSONA
			           SET PER_STATUS = ? 
			         WHERE PER_ID = $this->intIdUsuario ";
			$arrData = array(0);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function updatePerfil(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $password){
			$this->intIdUsuario = $idUsuario;
			$this->strIdentificacion = $identificacion;
			$this->strNombre = $nombre;
			$this->strApellido = $apellido;
			$this->intTelefono = $telefono;
			$this->strPassword = $password;

			if($this->strPassword != ""){
				$sql = "UPDATE PERSONA
				           SET PER_IDENTIFICACION=?, 
				               PER_NOMBRE=?, 
				               PER_APELLIDOS=?, 
				               PER_TELEFONO=?, 
				               PER_PASSWORD=? 
						 WHERE PER_ID = $this->intIdUsuario ";
				$arrData = array($this->strIdentificacion,
								 $this->strNombre,
								 $this->strApellido,
								 $this->intTelefono,
								 $this->strPassword);
			}else{
				$sql = "UPDATE PERSONA
				           SET PER_IDENTIFICACION=?, 
				               PER_NOMBRE=?, 
				               PER_APELLIDOS=?, 
				               PER_TELEFONO=? 
						 WHERE PER_ID = $this->intIdUsuario ";
				$arrData = array($this->strIdentificacion,
								 $this->strNombre,
								 $this->strApellido,
								 $this->intTelefono);
			}
			$request = $this->update($sql,$arrData);
		    return $request;
		}

		public function updateDataFiscal(int $idUsuario, string $strNit, string $strNomFiscal, string $strDirFiscal){
			$this->intIdUsuario = $idUsuario;
			$this->strNit       = $strNit;
			$this->strNomFiscal = $strNomFiscal;
			$this->strDirFiscal = $strDirFiscal;
			$sql = "UPDATE PERSONA
			           SET PER_NIT=?, 
			               PER_NOMBRE_FISCAL=?, 
			               PER_DIRECCION_FISCAL=? 
					 WHERE PER_ID = $this->intIdUsuario ";
			$arrData = array($this->strNit,
							 $this->strNomFiscal,
							 $this->strDirFiscal);
			$request = $this->update($sql,$arrData);
		    return $request;
		}

	}
 ?>