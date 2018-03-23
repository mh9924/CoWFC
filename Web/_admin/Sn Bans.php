<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class SnBans extends AdminPage {
	private $sn_bans = array();
	
	private function buildSNTable(): void {
		$this->sn_bans = $this->site->database->getSNBans();
		
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>csnum</th><th>Ban/Unban csnum</th>";
		echo '</tr></thead>';
		
		foreach($this->sn_bans as $row){
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

        <?php $this->buildSNTable(); ?>

      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- /.content-wrapper -->
<?php
	}
}
?>
