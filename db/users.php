<?php 
	class users {
		private $id;
		private $name;
		private $password;
		private $email;
		private $loginStatus;
		private $lastLogin;
		public $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name; }
		function getName() { return $this->name; }
		function setPassword($password){ $this->password = $password;}
		function getPassword(){ return $this->password;}
		function setEmail($email) { $this->email = $email; }
		function getEmail() { return $this->email; }
		function setLoginStatus($loginStatus) { $this->loginStatus = $loginStatus; }
		function getLoginStatus() { return $this->loginStatus; }
		function setLastLogin($lastLogin) { $this->lastLogin = $lastLogin; }
		function getLastLogin() { return $this->lastLogin; }

		public function __construct() {
			require_once("DbConnect.php");
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function save() {
			$sql = "INSERT INTO `users`(`id`, `name`, `password`, `email`, `login_status`, `last_login`) VALUES (null, :name, :password, :email, :loginStatus, :lastLogin)";
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":password", $this->password);
			$stmt->bindParam(":email", $this->email);
			$stmt->bindParam(":loginStatus", $this->loginStatus);
			$stmt->bindParam(":lastLogin", $this->lastLogin);
			try {
				if($stmt->execute()) {
					return true;
				} else {
					return false;
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
		
		public function login()
		{
			$stmt = $this->dbConn->prepare('SELECT * FROM users WHERE email = :email and password = :password');
			$stmt->bindParam(':password', $this->password);
			$stmt->bindParam(':email', $this->email);
			try {
				if($stmt->execute()) {
					$user = $stmt->fetch(PDO::FETCH_ASSOC);
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			return $user;
		}

		public function getUserByEmail() {
			$stmt = $this->dbConn->prepare('SELECT * FROM users WHERE email = :email');
			$stmt->bindParam(':email', $this->email);
			try {
				if($stmt->execute()) {
					$user = $stmt->fetch(PDO::FETCH_ASSOC);
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			return $user;
		}

		public function getUserById() {
			$stmt = $this->dbConn->prepare('SELECT * FROM users WHERE id = :id');
			$stmt->bindParam(':id', $this->id);
			try {
				if($stmt->execute()) {
					$user = $stmt->fetch(PDO::FETCH_ASSOC);
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			return $user;
		}

		public function updateLoginStatus() {
			$stmt = $this->dbConn->prepare('UPDATE users SET login_status = :loginStatus, last_login = :lastLogin WHERE id = :id');
			$stmt->bindParam(':loginStatus', $this->loginStatus);
			$stmt->bindParam(':lastLogin', $this->lastLogin);
			$stmt->bindParam(':id', $this->id);
			try {
				if($stmt->execute()) {
					return true;
				} else {
					return false;
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		public function getAllUsers() {
			$stmt = $this->dbConn->prepare("SELECT * FROM users");
			$stmt->execute();
			$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $users;
		}

	}