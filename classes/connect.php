<?php

final class database {

	private static $instance = NULL;

	private $pdo; 

	private function __construct() {
		try {
			$options = [
				\PDO::ATTR_ERRMODE			=> \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
				\PDO::ATTR_EMULATE_PREPARES   => false,
			];
			$host= 'localhost';
			$db = 'usermanagement';
			$user = 'postgres';
			$password = 'Usk^Qp^5Jr%okjVcVGoV3P2nJ5t@%mRt';
			$charset = 'utf8mb4';

			$dsn = "pgsql:host=$host;port=5432;dbname=$db;";
			$database = new PDO(
				$dsn,
				$user,
				$password,
				$options
			);
		} catch(PDOException $e) {
			echo $e->getmessage();
			die();
		}
		$this->pdo = $database; //saved the connection into the new variable
	}

	public static function getInstance() {
		static $instance = null;
		if (self::$instance === NULL) {
			$instance = new database();
		}
		return $instance;
	}

	//added a function to get the connection itself
	function getConnection(){
		return $this->pdo;
	}
}
?>