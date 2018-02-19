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
						<li id="error">22222 - Banned from Mario Kart Wii: Improper console identification. Please use a real console!</li
						<li id="error">23800 - Game not supported by <?php echo $this->site->config["main"]["name"]; ?> - <?php echo $this->site->config["main"]["name"]; ?> only support Mario Kart Wii and Mario Kart DS. This error may also be caused if your game ID is improperly set.</li>
						<li id="error">23913 - Console creation denied!</li>
						<li id="error">23914 - Console is banned!</li>
						<li id="error">23915 - Attemtpting to tamper with console identifiers</li>
						<li id="error">23917 - Full ban from <?php echo $this->site->config["main"]["name"]; ?></li>
						<li id="error">23921 - Unknown console - This error should never appear. If it does, that means something is broken on your end. You should always get 23888 if it's a new console connecting.</li>
					</ul>
				</p>					
			</section>

	</div>
</div>
<?php
	}
}
?>
