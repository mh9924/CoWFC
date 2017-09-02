<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class Dashboard extends AdminPage {
	
	protected function buildAdminPage(): void {
?>
<div class="content-wrapper py-3">

      <div class="container-fluid">

        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><?php echo $this->meta_title; ?></li>
        </ol>

        Welcome to the CoWFC admin panel!
If you are seeing this page, it means you have Moderator access to CoWFC. By using this page, you agree to use it responsibly. If you are found abusing the system, your access to it will be revoked. This panel will allow you to:
        <li>Ban IPs</li>
        <li>Ban Consoles</li>
        <li>Manage existing bans
        <li>Manage console states (activated, pending, deactivate, etc)</li>

      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- /.content-wrapper -->
<?php
	}
}
?>
