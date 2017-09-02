<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class FcBans extends AdminPage {
	private $fc_bans = array();
	
	private function getFCBans(): array {
		$sql = "SELECT * from console_cfc_bans";
		$stmt = $this->site->database->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	private function buildFCTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>cfc</th><th>Ban/Unban cfc</th>";
		echo '</tr></thead>';
		$this->fc_bans = $this->getFCBans();
		foreach($this->fc_bans as $row){
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

        <?php $this->buildFCTable(); ?>

      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- /.content-wrapper -->
<?php
	}
}
?>