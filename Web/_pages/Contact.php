<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/Page.php');

final class Contact extends Page {
	
	protected function buildPage(): void {
?>
<div id="main" class="wrapper style1">
	<div class="container">
		<header class="major">
			<h2><?php echo $this->meta_title; ?></h2>
			<p>Get in touch with the <?php echo $this->site->config["main"]["name"]; ?> team.</p>
		</header>

		<!-- Content -->
			<section id="content">
				<h3>Join us on the official Discord chat:</h3>
				<p>
					<a href="https://discord.gg/CqQCeAK">https://discord.gg/CqQCeAK</a>
				</p>
				<p>
					Remember, all rules for the matchmaking server apply on the Discord chat as well!
				</p>
			</section>

	</div>
</div>
<?php
	}
}
?>
