<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class RegisteredConsoles extends AdminPage {
	private $reg_consoles = array();
	private $pen_consoles = array();
	private $banned_consoles = array();
	
	private function handleReq(): void {
		if(isset($_POST['action'], $_POST['identifier'])){
			switch($_POST['action']){
				case 'add': $this->site->database->regAndActivateConsole($_POST['identifier']);break;
				case 'act': $this->site->database->activateConsole($_POST['identifier']);break;
				case 'rm': $this->site->database->unregisterConsole($_POST['identifier']);break;
				case 'ban':$this->site->database->banConsole($_POST['identifier']);break;
				case 'unban':$this->site->database->unbanConsole($_POST['identifier']);break;
			}
		}
		$this->reg_consoles = $this->site->database->getRegisteredConsoles();
		$this->pen_consoles = $this->site->database->getPendingConsoles();
		$this->banned_consoles = $this->site->database->getBannedConsoles();
	}
	
	private function buildRegisteredTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;" id="dataTable">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th><th>Unregister</th><th>Ban</th>";
		echo '</tr></thead>';
		foreach($this->reg_consoles as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "<td>";
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='rm'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Unregister'></form>";
			echo "</td>";
			echo "<td>";
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='ban'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Ban'></form>";
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
	
	private function buildBannedTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th><th>Unban</th>";
		echo '</tr></thead>';
		foreach($this->banned_consoles as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "<td>";
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='unban'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Unban'></form>";
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

	  </div>
	  <!-- /.container-fluid -->

	</div>
	<!-- /.content-wrapper -->
<?php
	}
}
?>
