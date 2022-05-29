<?php 

	class LoginModel extends Mysql
	{
		private $intIdUsuario;
		private $strUsuario;
		private $strPassword;
		private $strToken;

		public function __construct()
		{
			parent::__construct();
		}	

		public function loginUser(string $usuario, string $password)
		{
			$this->strUsuario  = $usuario;
			$this->strPassword = $password;
			$sql = "SELECT PER_ID, PER_STATUS
			          FROM PERSONA
			         WHERE PER_EMAIL     = '$this->strUsuario' 
			           AND PER_PASSWORD  = '$this->strPassword' 
			           AND PER_STATUS   != 0 ";
			$request = $this->select($sql);
			return $request;
		}

		public function sessionLogin(int $iduser){
			$this->intIdUsuario = $iduser;
			//BUSCAR ROL 
			$sql = "SELECT PER_ID,
						   PER_IDENTIFICACION,
						   PER_NOMBRE,
						   PER_APELLIDOS,
						   PER_TELEFONO,
						   PER_EMAIL,
						   PER_NIT,
						   PER_NOMBRE_FISCAL,
						   PER_DIRECCION_FISCAL,
						   ROL_ID,
						   ROL_NOMBRE,
						   PER_STATUS
					  FROM PERSONA INNER JOIN ROL ON PER_ROL_ID = ROL_ID
					 WHERE PER_ID = $this->intIdUsuario";
			$request = $this->select($sql);
			$_SESSION['userData'] = $request;
			return $request;
		}

		public function getUserEmail(string $strEmail){
			$this->strUsuario = $strEmail;
			$sql = "SELECT PER_ID,
			               PER_NOMBRE,
			               PER_APELLIDOS,
			               PER_STATUS
			          FROM PERSONA 
			         WHERE PER_EMAIL  = '$this->strUsuario' 
			           AND PER_STATUS = 1 ";
			$request = $this->select($sql);
			return $request;
		}	

		public function setTokenUser(int $idpersona, string $token){
			$this->intIdUsuario = $idpersona;
			$this->strToken = $token;
			$sql = "UPDATE PERSONA
			           SET PER_TOKEN = ? 
			         WHERE PER_ID = $this->intIdUsuario ";
			$arrData = array($this->strToken);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function getUsuario(string $email, string $token){
			$this->strUsuario = $email;
			$this->strToken = $token;
			$sql = "SELECT PER_ID
			          FROM PERSONA
			         WHERE PER_EMAIL  = '$this->strUsuario' 
			           AND PER_TOKEN  = '$this->strToken'
			           AND PER_STATUS = 1 ";
			$request = $this->select($sql);
			return $request;
		}

		public function insertPassword(int $idPersona, string $password){
			$this->intIdUsuario = $idPersona;
			$this->strPassword = $password;
			$sql = "UPDATE PERSONA
			           SET PER_PASSWORD = ?, 
			               PER_TOKEN = ? 
			         WHERE PER_ID = $this->intIdUsuario ";
			$arrData = array($this->strPassword,"");
			$request = $this->update($sql,$arrData);
			return $request;
		}		
	}
 ?>