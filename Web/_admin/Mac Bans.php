<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class MacBans extends AdminPage {
	private $mac_bans = array();
	
	private function getMACBans(): array {
		$sql = "SELECT * from console_macadr_banned";
		$stmt = $this->site->database->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	private function buildMACTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th><th>Ban/Unban MAC</th>";
		echo '</tr></thead>';
		$this->mac_bans = $this->getMACBans();
		foreach($this->mac_bans as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	
	protected function buildAdminPage(): void {
?>
<div class="content-wrapper py-3">

      <div class="container-fluid">

        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><?php echo $this->meta_title; ?></li>
        </ol>

        <?php $this->buildMACTable(); ?>

      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- /.content-wrapper -->
<?php
	}
}
?>