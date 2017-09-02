<?php
class Header {
	private $page;
	
	public function __construct(Page $page) {
		$this->page = $page;
	}
	
	public function generateNav() {
		if($this->page->site->mode == 'admin'){
			return $this->generateAdminNav();
		}
		return $this->generatePagesNav();
	}
	
	public function generatePagesNav(): void {
		echo "<nav id='nav'>";
		echo "<ul>";
		foreach($this->page->site->pages as $page){
			if ($page !== 'Home.php'){
				$page = substr($page, 0, -4);
				echo "<li><a href='?page=".strtolower($page)."'".($page == 'Stats' ? " class='button special'" : "").">{$page}</a></li>";
			}
		}
		echo "</ul>";
		echo "</nav>";
	}
	
	public function generateAdminNav(): void {
		echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">';
		echo '<a class="navbar-brand" href="#"><img src="images/cowfc-panel.png" width="96" height="32"></a>';
		echo '<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarRepsonsive" aria-expanded="false" aria-label="Toggle navigation">';
		echo '<span class="navbar-toggler-icon"></span>';
		echo '</button>';
		echo '<div class="collapse navbar-collapse" id="navbarResponsive">';
		echo '<ul class="navbar-nav navbar-sidenav">';
		foreach($this->page->site->pages as $page){
			$page = substr($page, 0, -4);
			echo "<li class='nav-item".($page == $this->page->meta_title ? ' active' : '')."' data-toggle='tooltip' data-placement='right' title='{$page}'>";
			echo "<a class='nav-link' href='?page=admin&section=".strtolower($page)."'>";
			echo "<i class='fa fa-fw fa-".($page == 'Dashboard' ? 'dashboard' : 'wrench')."'></i>";
			echo "<span class='nav-link-text'> {$page}</span>";
			echo "</a>";
			echo "</li>";
		}
		echo '</ul>';
		echo '<ul class="navbar-nav sidenav-toggler"><li class="nav-item"><a class="nav-link text-center" id="sidenavToggler"><i class="fa fa-fw fa-angle-left"></i></a></li></ul>';
		echo '<ul class="navbar-nav ml-auto">';
		echo '<li class="nav-item"><a class="nav-link" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-fw fa-sign-out"></i>Logout</a></li></ul>';
		echo '</div>';
		echo '</nav>';
	}
			
	public function build(): void {
?>
<html>
	<head>
		<title>CoWFC | <?php echo $this->page->meta_title; ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="../assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="../assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="../assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="../assets/css/ie8.css" /><![endif]-->
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	</head>
	<body class="landing">
		<div id="page-wrapper">

			<!-- Header -->
				<header id="header">
					<h1 id="logo"><a href="/">CoWFC</a></h1>
					<?php $this->generateNav(); ?>
				</header>
<?php
	}
}
?>