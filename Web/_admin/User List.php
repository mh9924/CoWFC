<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class UserList extends AdminPage {
	private $users = array();
	private $banned_list = array();
	
	private function handleReq(): void {
		if(isset($_POST['action'], $_POST['identifier'])){
			switch($_POST['action']){
				case 'ban': if(isset($_POST['reason'])){ $this->banIP($_POST['identifier'], $_POST['reason'], 60 * (int)$_POST['time']); } break;
				case 'unban': $this->unbanIP($_POST['identifier']);break;
			}
		}
		$this->users = $this->getUsers();
		$this->banned_list = $this->getBannedList();
	}
	
	private function banIP(string $ip, string $reason='none', int $time): void {
		$ubtime = time() + $time;
		if($time == 0) $ubtime = 99999999999;
		$sql = "INSERT INTO ip_banned (ipaddr, timestamp, reason, ubtime) VALUES (:ipaddr, :timestamp, :reason, :ubtime)";
		$stmt = $this->site->database->prepare($sql);
		$stmt->bindParam(':ipaddr', $ip);
		$stmt->bindParam(':timestamp', time());
		$stmt->bindParam(':reason', $reason);
		$stmt->bindParam(':ubtime', $ubtime);
		$stmt->execute();
	}
	
	private function unbanIP(string $ip): void {
		$sql = "DELETE FROM ip_banned WHERE ipaddr = :ipaddr";
		$stmt = $this->site->database->prepare($sql);
		$stmt->bindParam(':ipaddr', $ip);
		$stmt->execute();
	}
	
	private function getUsers(): array {
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
		$stmt = $this->site->database->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	private function getBannedList(): array {
		$sql = "SELECT * FROM IP_BANNED WHERE ubtime > ".time();
		$stmt = $this->site->database->prepare($sql);
		$stmt->execute();
		$banned = array();
		foreach($stmt->fetchAll() as $row){
			$banned[] = $row[0];
		}
		return $banned;
	}
	
	private function buildBlacklistTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>Name</th><th>Action</th><th>gameid</th><th>Enabled</th><th>newest dwc_pid</th><th>gsbrcd</th><th>userid</th><th>IP Address</th><th>Console MAC Address</th><th>Wii Friend Code</th><th>Console Serial Number (Wii ONLY)</th>";
		echo '</tr></thead>';
		foreach($this->users as $row){
			$nasdata = json_decode($row[2], true);
			$is_console = $row[4];
			if(array_key_exists('ingamesn', $nasdata)){
				$ingamesn = $nasdata['ingamesn'];
			} elseif(array_key_exists('devname', $nasdata)){
				$ingamesn = $nasdata['devname'];
			}
			if(isset($ingamesn)){
				$ingamesn = base64_decode($ingamesn);
				if($is_console){
					$ingamesn = iconv('UTF-16BE', 'UTF-8', $ingamesn);
				} else {
					$ingamesn = iconv('UTF-16LE', 'UTF-8', $ingamesn);
				}
			}
			echo "<tr>";
			echo "<td>".htmlentities($ingamesn)."</td>";
			echo "<td>";
			if(in_array($nasdata['ipaddr'], $this->banned_list)){
				echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='unban'><input type='hidden' name='identifier' id='identifier' value='{$nasdata['ipaddr']}'><input type='submit' class='btn btn-primary' value='Unban'></form>";
			} else {
				echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='ban'><input type='hidden' name='identifier' id='identifier' value='{$nasdata['ipaddr']}'><input type='text' class='form-control' placeholder='Reason' name='reason' id='reason' style='width: 100px;'><input type='text' class='form-control' placeholder='# minutes' name='time' id='time' style='width: 100px;' value='0' maxlength='11'><input type='submit' class='btn btn-primary' value='Ban'></form>";
			}
			echo "</td>";
			echo "<td>{$row[3]}</td>";
			echo "<td>{$row[1]}</td>";
			echo "<td>{$row[0]}</td>";
			echo "<td>{$nasdata['gsbrcd']}</td>";
			echo "<td>{$row[5]}</td>";
			echo "<td>{$nasdata['ipaddr']}</td>";
			echo "<td>{$nasdata['macadr']}</td>";
			echo "<td>{$nasdata['cfc']}</td>";
			echo "<td>{$nasdata['csnum']}</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	
	protected function buildAdminPage(): void {
		$this->handleReq();
?>
<div class="content-wrapper py-3">

      <div class="container-fluid">

        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><?php echo $this->meta_title; ?></li>
        </ol>

        <?php $this->buildBlacklistTable(); ?>

      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- /.content-wrapper -->
<?php
	}
}
?>
