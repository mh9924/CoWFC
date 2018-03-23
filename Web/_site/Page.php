<?php
include('Header.php');
include('Footer.php');

abstract class Page {
	public $site;
	public $meta_title;
	protected $header;
	protected $footer;
	
	abstract protected function buildPage();
	
	public function __construct(PageController $site, string $meta_title = "") {
		$this->site = $site;
		$this->meta_title = $meta_title;
		$this->header = new Header($this);
		$this->footer = new Footer($this);
		
		if (empty($this->meta_title))
			$this->meta_title = trim(preg_replace("/(?<!\ )[A-Z]/", " $0", get_class($this)));
		
		$this->buildHeader();
		$this->buildPage();
		$this->buildFooter();
	}
		
	protected function buildHeader(): void {
		if ($this->site->config["main"]["debug"])
			echo "CoWFC is running in debug mode. Errors will be visible.";
		
		$this->header->build();
	}
	
	protected function buildFooter(): void {
		$this->footer->build();
	}
}
?>
