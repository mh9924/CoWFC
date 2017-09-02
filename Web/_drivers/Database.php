<?php
class Database {
	private $conn;
	private $username;
	private $password;
	
	public function connect(string $dir='sqlite:/var/www/dwc_network_server_emulator/gpcm.db', string $username='', string $password=''): PDO {
		if ($this->conn == null){
			try {
				if(!empty($username) && !empty($password)){
					// We aren't using garbage.
					$this->conn = new PDO('mysql:host=localhost;dbname=cowfc', $username, $password);
					$this->username = $username;
					$this->password = $password;
				} else {
					// We are using garbage.
					$this->conn = new PDO($dir);
				}
			} catch (PDOException $e) {
				$error = $e->getMessage();
				die("There was an error: {$error}");
			}
		}
		return $this->conn;
	}
	
	public function getConn(): PDO {
		return $this->conn;
	}
		
	public function disconnect(): void {
		if ($this->conn != null){
			$this->conn = null;
		}
	}
}
?>