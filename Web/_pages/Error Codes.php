<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/Page.php');

final class ErrorCodes extends Page {
	
	protected function buildPage(): void {
?>
<div id="main" class="wrapper style1">
	<div class="container">
		<header class="major">
			<h2><?php echo $this->meta_title; ?></h2>
		</header>

		<!-- Content -->
			<section id="content">
				<h3>Here is a list of all known error codes to <?php echo $this->site->config["main"]["name"]; ?> and what they mean:</h3>
				<p>
					<ul>
						<li id="error">23000 - Console registered - if getting this error more than once it might mean that manual activation is enabled. Please contact us to be sure.</li
						<li id="error">23800 - Game not supported by <?php echo $this->site->config["main"]["name"]; ?> - <?php echo $this->site->config["main"]["name"]; ?> only support Mario Kart Wii and Mario Kart DS. This error may also be caused if your game ID is improperly set.</li>
						<li id="error">23913 - Console creation denied!</li>
						<li id="error">23914 - Console is banned!</li>
						<li id="error">23915 - Attemtpting to tamper with console identifiers</li>
						<li id="error">23917 - Full ban from <?php echo $this->site->config["main"]["name"]; ?></li>
					</ul>
				</p>					
			</section>

	</div>
</div>
<?php
	}
}
?>
