<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_drivers/Database.php');

class DWCDatabase extends Database {

	public function ban(string $type, array $target_aliases, string $identifier, string $reason='none', int $time=0): void {
		$this->{"ban{$type}"}($identifier, $reason, $time);
		// Write ban log to a text file that includes $type, $reason, $time, and $_SESSION['username']
	}

	public function getFCBans(): array {
		$sql = "SELECT * from console_cfc_banned";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	public function getWhitelist(): array {
		$sql = "SELECT * from allowed_games";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	public function addToWhitelist(string $game): void {
		$sql = "INSERT INTO allowed_games (gamecd) VALUES (:gamecd)";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindValue(':gamecd', strtoupper($game));
		$stmt->execute();
	}
	
	public function removeFromWhitelist(string $game): void {
		$sql = "DELETE FROM allowed_games WHERE gamecd = :gamecd";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':gamecd', $game);
		$stmt->execute();
	}
	
	private function banIP(string $ip, string $reason='none', int $time=0): void {
		$ubtime = time() + $time;
		if($time == 0) $ubtime = 99999999999;
		$sql = "INSERT INTO ip_banned (ipaddr, timestamp, reason, ubtime) VALUES (:ipaddr, :timestamp, :reason, :ubtime)";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':ipaddr', $ip);
		$stmt->bindValue(':timestamp', time());
		$stmt->bindParam(':reason', $reason);
		$stmt->bindParam(':ubtime', $ubtime);
		$stmt->execute();
	}
	
	public function unbanIP(string $ip): void {
		$sql = "DELETE FROM ip_banned WHERE ipaddr = :ipaddr";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':ipaddr', $ip);
		$stmt->execute();
	}
	
	public function getIPBans(): array {
		$sql = "SELECT * FROM IP_BANNED WHERE ubtime > ".time();
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	public function getRegisteredConsoles(): array {
		$sql = "SELECT * FROM registered";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	public function regAndActivateConsole(string $console): void {
		$sql = "INSERT INTO pending (macadr) VALUES (:macadr)";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->execute();
		$this->activateConsole($console);
	}
	
	public function activateConsole(string $console): void {
		$sql = "INSERT INTO registered (macadr) VALUES (:macadr)";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->execute();
	}
	
	private function banConsole(string $console, string $reason='none', int $time=0): void {
		$ubtime = time() + $time;
		if($time == 0) $ubtime = 99999999999;
		$sql = "INSERT INTO console_macadr_banned (macadr, timestamp, reason, ubtime) VALUES (:macadr, :timestamp, :reason, :ubtime)";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->bindValue(':timestamp', time());
		$stmt->bindParam(':reason', $reason);
		$stmt->bindParam(':ubtime', $ubtime);
		$stmt->execute();
	}
	
	public function unbanConsole(string $console): void {
		$sql = "DELETE FROM console_macadr_banned WHERE macadr = :macadr";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->execute();
	}
	
	public function unregisterConsole(string $console): void {
		$sql = "DELETE FROM pending WHERE macadr = :macadr";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->execute();
		$sql = "DELETE FROM registered WHERE macadr = :macadr";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->execute();
	}
		
	public function getPendingConsoles(): array {
		$sql = "SELECT * FROM pending";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	public function getBannedConsoles(): array {
		$sql = "SELECT * FROM console_macadr_banned";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	public function getSNBans(): array {
		$sql = "SELECT * from console_csnum_banned";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	public function getUsers(): array {
		$sql = "SELECT users.profileid,enabled,data,users.gameid,console,users.userid 
				FROM nas_logins 
				INNER JOIN users 
				ON users.userid = nas_logins.userid 
				INNER JOIN (
					SELECT max(profileid) newestpid,userid,gameid,devname 
					FROM users GROUP BY userid,gameid) 
				ij on ij.userid = users.userid and 
				users.profileid = ij.newestpid 
				ORDER BY users.gameid";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	public function getBannedList(): array {
		$sql = "SELECT * FROM IP_BANNED WHERE ubtime > ".time();
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		$banned = array();
		foreach($stmt->fetchAll() as $row){
			$banned[] = $row[0];
		}
		return $banned;
	}
	public function getNumBannedProfiles(): int {
		$sql = "SELECT COUNT(*) FROM IP_BANNED WHERE ubtime > ".time();
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetch()[0];
	}
	public function getNumBannedConsoles(): int {
		$sql = "SELECT COUNT(*) FROM console_macadr_banned where ubtime > ".time();
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetch()[0];
	}
	public function getActiveGames(): int {
		$sql = "SELECT COUNT(*) FROM allowed_games";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetch()[0];
	}
	public function getConsoles(): int {
		$sql = "SELECT COUNT(*) FROM registered";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetch()[0];
	}
	public function getProfiles(): int {
		$sql = "SELECT COUNT(*) FROM users";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetch()[0];
	}
}
