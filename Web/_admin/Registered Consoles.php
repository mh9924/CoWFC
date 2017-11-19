<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class RegisteredConsoles extends AdminPage {
	private $reg_consoles = array();
	private $pen_consoles = array();
	
	private function handleReq(): void {
		if(isset($_POST['action'], $_POST['identifier'])){
			switch($_POST['action']){
				case 'add': $this->regAndActivateConsole($_POST['identifier']);break;
				case 'act': $this->activateConsole($_POST['identifier']);break;
				case 'rm': $this->unregisterConsole($_POST['identifier']);break;
			}
		}
		$this->reg_consoles = $this->getRegisteredConsoles();
		$this->pen_consoles = $this->getPendingConsoles();
	}
	
	private function getRegisteredConsoles(): array {
		$sql = "SELECT * FROM registered";
		$stmt = $this->site->database->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	private function regAndActivateConsole(string $console): void {
		$sql = "INSERT INTO pending (macadr) VALUES (:macadr)";
		$stmt = $this->site->database->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->execute();
		$this->activateConsole($console);
	}
	
	private function activateConsole(string $console): void {
		$sql = "INSERT INTO registered (macadr) VALUES (:macadr)";
		$stmt = $this->site->database->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->execute();
	}
	
	private function unregisterConsole(string $console): void {
		$sql = "DELETE FROM pending WHERE macadr = :macadr";
		$stmt = $this->site->database->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->execute();
		$sql = "DELETE FROM registered WHERE macadr = :macadr";
		$stmt = $this->site->database->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->execute();
	}
		
	
	private function getPendingConsoles(): array {
		$sql = "SELECT * FROM pending";
		$stmt = $this->site->database->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	private function buildRegisteredTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;" id="dataTable">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th><th>Unregister</th>";
		echo '</tr></thead>';
		foreach($this->reg_consoles as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "<td>";
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='rm'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Unregister'></form>";
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	
	private function buildPendingTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th><th>Activate</th>";
		echo '</tr></thead>';
		foreach($this->pen_consoles as $row){
			if(!in_array($row, $this->reg_consoles)){
				echo "<tr>";
				echo "<td>";
				echo $row[0];
				echo "</td>";
				echo "<td>";
				echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='act'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Activate'></form>";
				echo "</td>";
				echo "</tr>";
			}
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
		<form action='' method='post'>Register and Activate MAC: <input type='hidden' name='action' id='action' value='add'><input class='form-control' style='width:175px;' type='text' name='identifier' id='identifier' maxlength='12'><input type='submit' class='btn btn-primary' value='Register & Activate'></form>

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-table"></i>
				Pending Consoles
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<?php $this->buildPendingTable(); ?>
				</div>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-table"></i>
				Registered Consoles
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<?php $this->buildRegisteredTable(); ?>
				</div>
			</div>
		</div>

      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- /.content-wrapper -->
<?php
	}
}
?>