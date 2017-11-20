<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class GameWhitelist extends AdminPage {
	private $whitelist = array();
	
	private function handleReq(): void {
		if(isset($_POST['action'], $_POST['identifier'])){
			switch($_POST['action']){
				case 'add': $this->site->database->addToWhitelist($_POST['identifier']);break;
				case 'rm': $this->site->database->removeFromWhitelist($_POST['identifier']);break;
			}
		}
		$this->whitelist = $this->site->database->getWhitelist();
		// $this->titles = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/_admin/GameWhitelist/games.json"), true);
	}
	
	private function buildWhitelistTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>gamecd</th><th>Un-whitelist</th><th>Game</th><th>Platform</th>";
		echo '</tr></thead>';
		foreach($this->whitelist as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "<td>";
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='rm'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Un-whitelist'></form>";
			echo "</td>";
			echo "<td>";
			// echo $this->titles[$row[0]]['Title'];
			echo "</td>";
			echo "<td>";
			// echo $this->titles[$row[0]]['platform'];
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
		<form action='' method='post'>Add Game: <input type='hidden' name='action' id='action' value='add'><input class='form-control' style='width:125px;' type='text' name='identifier' id='identifier' maxlength='3'><input type='submit' class='btn btn-primary' value='Whitelist'></form>

        <?php $this->buildWhitelistTable(); ?>

      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- /.content-wrapper -->
<?php
	}
}
?>