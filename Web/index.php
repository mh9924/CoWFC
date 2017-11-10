<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_drivers/Database.php');

$s = new PageController("config.ini");
$s->loadPage();

class PageController {
	public $config;
	public $database;
	public $mode;
	public $pages = array();
	private $requested_page;
	
	public function __construct(string $config){
		$this->loadConfiguration($config);
		$this->loadDatabase();
		if (!$this->config['main']['debug'])
			error_reporting(0);
		$this->requested_page = 'Home';
		$this->mode = 'pages';
		if(isset($_GET['page'])){
			$this->requested_page = ucwords($_GET['page']);
			if($this->requested_page == 'Admin' && isset($_GET['section'])){
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
	
	private function loadConfiguration($config): void {
		try {
			$this->config = parse_ini_file($config, true);
		} catch (Exception $e){
			echo "Could not find configuration file. $e";
		}
	}
	
	private function loadDatabase(): void {
		$this->database = new Database();
		$this->database->connect("sqlite:".$this->config['pages']['dwc_db_path']);
		$this->database = $this->database->getConn();
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
?>