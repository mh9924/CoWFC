<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/_drivers/Database.php');
include($_SERVER["DOCUMENT_ROOT"] . '/_site/Page.php');

abstract class AdminPage extends Page {
	
	public $udatabase;
	public $logged_in = false;
	protected $user;
	
	abstract protected function buildAdminPage();
	
	public function __construct(PageController $site) {
		$this->site = $site;
		session_start();
		$this->initMySQL();
		if(isset($_SESSION['username']))
			$this->logged_in = true;
			# $this->user = new User($_SESSION);
		if(!$this->logged_in){
			include($_SERVER["DOCUMENT_ROOT"] . "/_admin/Auth/Login.php");
			return new Login($this);
		}
		parent::__construct($site);
	}
	
	private function initMySQL(): void {
		$this->udatabase = new Database($this->site);
		$config = $this->site->config['admin'];
		$this->udatabase->connect($config['db_host'],$config['db_name'],$config['db_user'],$config['db_pass']);
		$this->udatabase = $this->udatabase->getConn();
		$stmt = $this->udatabase->prepare("CREATE TABLE IF NOT EXISTS users 
											(id INTEGER AUTO_INCREMENT, 
											Username VARCHAR(20), 
											Password BINARY(60), 
											Rank INT(1),
											PRIMARY KEY (id))");
		$stmt->execute();
	}
	
	protected function buildPage(): void {
		$this->buildAdminPage();
	}
	
	protected function buildHeader(): void {
?>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Panel | <?php echo $this->meta_title; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Plugin CSS -->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin.css" rel="stylesheet">

  </head>

  <body class="fixed-nav" id="page-top">

    <!-- Navigation -->
    <?php $this->header->generateNav(); ?>
<?php
	}
	
	protected function buildFooter(): void {
?>
<!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>

    <!-- Logout Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Select "Logout" below if you are ready to end your current session.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="/_admin/Auth/Logout.php">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/sb-admin.min.js"></script>
  </body>

</html>
<?php
	}
}
?>
