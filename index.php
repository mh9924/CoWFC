<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_drivers/Database.php');

class PageController {
	private $requested_page;
	public $database;
	public $pages = array();
	public $mode;
	
	public function __construct(){
		$this->database = new Database();
		$this->database->connect();
		$this->database = $this->database->getConn();
		$this->requested_page = 'Home';
		$this->mode = 'pages';
		if(isset($_GET['page'])){
			$this->requested_page = ucwords($_GET['page']);
			if($this->requested_page == 'Admin'){
				$this->requested_page = ucwords($_GET['section']);
				$this->mode = 'admin';
			}
		}
		$this->pages = scandir("_{$this->mode}");
		foreach($this->pages as $i=>$page){
			if (is_dir($_SERVER["DOCUMENT_ROOT"] . "/_{$this->mode}/{$page}")){
				unset($this->pages[$i]);
			}
		}
	}
	
	public function loadPage(): Page {
		if(in_array("{$this->requested_page}.php", $this->pages)){
			include("_{$this->mode}/{$this->requested_page}.php");
			$page_class = str_replace(' ', '', $this->requested_page);
			return new $page_class($this);
		}
		if($this->mode == 'pages'){
			include("_pages/Error/NotFound.php");
			return new NotFound($this, "Error");
		}
		die("Page not found.");
	}
}

$s = new PageController();
$s->loadPage();
?>