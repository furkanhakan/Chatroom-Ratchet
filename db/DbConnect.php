<?php 
	class DbConnect {
		private $host = 'localhost';
		private $dbName = 'web';
		private $user = 'root';
		private $pass = '';

		public function __construct()
		{
			// Create Database
			$conn = new PDO('mysql:host=' . $this->host . ';', $this->user, $this->pass);
			$sql = "CREATE DATABASE IF NOT EXISTS $this->dbName";
			$conn->exec($sql);
			$conn = new PDO("mysql:host=$this->host; dbname=$this->dbName;charset=utf8mb4", $this->user, $this->pass);

			// Create chatrooms table
			$sql = "CREATE TABLE IF NOT EXISTS chatrooms (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL, 
                userid INT(6) NOT NULL,
                msg VARCHAR(200) NOT NULL,
                created_on DATETIME NOT NULL
                ) CHARSET=utf8mb4";
			$conn->exec($sql);
			
			// Create users table
			$sql = "CREATE TABLE IF NOT EXISTS users (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL, 
                name VARCHAR(30) NOT NULL,
                password VARCHAR(16) NOT NULL,
                email VARCHAR(50) NOT NULL,
				login_status TINYINT(4) NOT NULL DEFAULT 0,
				last_login DATETIME NOT NULL
                ) CHARSET=utf8mb4";
			$conn->exec($sql);
		}
		

		public function connect() {
			try {
				$conn = new PDO("mysql:host=$this->host; dbname=$this->dbName;charset=utf8mb4", $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch( PDOException $e) {
				echo 'Database Error: ' . $e->getMessage();
			}
		}
	}
 ?>