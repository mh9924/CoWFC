<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/Page.php');

final class Rules extends Page {
	
	protected function buildPage(): void {
?>
<div id="main" class="wrapper style1">
	<div class="container">
		<header class="major">
			<h2><?php echo $this->meta_title; ?></h2>
			<p>CoWFC is based off the hacking element, but legits are also welcome.</p>
		</header>

		<!-- Content -->
			<section id="content">
				<h3>At all times, the following is forbidden:</h3>
				<p>
					<ul>
						<li>Targeting legits that appear in your worldwide</li>
						<li>FTW (for the win) hacking</li>
						<li>Trolling</li>
						<li>Freezing the lobby</li>
						<li>Disconnecting the lobby</li>
						<li>Manipulating network traffic</li>
						<li>Manipulating and abusing console identifiers for ban circomvention</li>
					</ul>
				By using CoWFC, you agree to abide by these rules or else you could be banned from accessing the server.
				</p>					
			</section>

	</div>
</div>
<?php
	}
}
?>
