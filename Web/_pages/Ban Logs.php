<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/Page.php');

final class BanLogs extends Page {
	
	private function buildLogs(): void {
		$lines = file($this->site->config['admin']['banlog_path']);
		$logs = array_reverse(array_slice($lines, -20));
		echo "<table>";
		foreach($logs as $log) 
			echo "<tr><td>" . htmlentities($log) . "</td></tr>";
		echo "</table>";
	}
	
	protected function buildPage(): void {
?>
<div id="main" class="wrapper style1">
	<div class="container">
		<header class="major">
			<h2><?php echo $this->meta_title; ?></h2>
			<p>Last 20 bans on <?php echo $this->site->config['main']['name']; ?></p>
		</header>

		
			<section id="content">
				<h3></h3>
				<p>
				<?php $this->buildLogs(); ?>
				</p>					
			</section>
		

	</div>
</div>
<?php
	}
}
?>