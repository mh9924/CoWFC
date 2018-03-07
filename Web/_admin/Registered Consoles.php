<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class RegisteredConsoles extends AdminPage {
	private $reg_consoles = array();
	private $pen_consoles = array();
	private $banned_consoles = array();
	private $abused_consoles = array();
	private $identifierActions = array(
		"add" => "regAndActivateConsole",
		"rm" => "unregisterConsole",
		"unban" => "unbanConsole"
	);
	
	private function handleReq(): void {
		if(isset($_POST["action"], $_POST["identifier"])){
			switch($_POST["action"]){
				case "ban": 
					if(isset($_POST["reason"]))
						$this->site->database->ban("Console", array(), $_POST["identifier"], $_POST["reason"]); 
					break;
				default:
					if(array_key_exists($_POST["action"], $this->identifierActions))
						$this->site->database->{$this->identifierActions[$_POST["action"]]}($_POST["identifier"]);
					break;
			}
		}
		$this->reg_consoles = $this->site->database->getRegisteredConsoles();
		$this->banned_consoles = $this->site->database->getBannedConsoles();
		$this->abused_consoles = $this->site->database->getAbusedConsoles();
	}
	
	private function buildRegisteredTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;" id="dataTable">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th><th>Platform</th><th>Serial Number (Wii)</th><th>Unregister</th><th>Ban</th>";
		echo '</tr></thead>';
		foreach($this->reg_consoles as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "<td>";
			echo $row[2];
			echo "</td>";
			echo "<td>";
			echo $row[1];
			echo "</td>";
			echo "<td>";
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='rm'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Unregister'></form>";
			echo "</td>";
			echo "<td>";
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='ban'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input class='form-control' style='width:225px;' type='text' name='reason' id='reason' placeholder='Reason'><input type='submit' class='btn btn-primary' value='Ban'></form>";
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	private function buildAbusedTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th>";
		echo '</tr></thead>';
		foreach($this->abused_consoles as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "</tr>";
			
		}
	}

	private function buildAbusedTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th>";
		echo '</tr></thead>';
		foreach($this->abused_consoles as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "</tr>";
			
		}
	}
	
	private function buildBannedTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th><th>Unban MAC</th><th>Timestamp</th><th>Until</th><th>Reason</th>";
		echo '</tr></thead>';
		foreach($this->banned_consoles as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "<td>";
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='unban'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Unban'></form>";
			echo "<td>";
			echo date('m/d/Y H:i:s', $row[1]);
			echo "</td>";
			echo "<td>";
			if ($row[3] == 99999999999)
				echo 'Forever';
			else
				echo date('m/d/Y H:i:s', $row[3]);
			echo "</td>";
			echo "<td>";
			echo htmlentities($row[2]);
			echo "</td>";
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
		<form action='' method='post'>Register and Activate MAC: <input type='hidden' name='action' id='action' value='add'><input class='form-control' style='width:175px;' type='text' name='identifier' id='identifier' maxlength='12'><input type='submit' class='btn btn-primary' value='Register & Activate'></form>
		<form action='' method='post'>Ban MAC address: <input type='hidden' name='action' id='action' value='ban'><input class='form-control' style='width:175px;' type='text' name='identifier' id='identifier' maxlength='12'><input class='form-control' style='width:225px;' type='text' name='reason' id='reason' placeholder='Reason'><input type='submit' class='btn btn-primary' value='Ban'></form>

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
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-table"></i>
				Banned Consoles
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<?php $this->buildBannedTable(); ?>
				</div>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-table"></i>
				Abused Consoles
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<?php $this->buildAbusedTable(); ?>
				</div>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-table"></i>
				Abused Consoles
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<?php $this->buildAbusedTable(); ?>
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
