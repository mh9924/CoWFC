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
				<h2> NOTE: For now, new players must contact a moderator on Discord providing their MAC address to get their console activated. We had to resort to this to thwart evil hackers away who don't follow the rules. Activation is instant upon contacting us.</h2>
				<h3>Here is a list of all known error codes to CoWFC and what they mean:</h3>
				<p>
					<ul>
						<li id="error">23800 - Game not supported by CoWFC - CoWFC only support Mario Kart Wii and Mario Kart DS. This error may also be caused if your game ID is improperly set.</li>
						<li id="error">23888 - Your console was automatically registered and awaiting activation. Please contact a moderator on Discord and provide your MAC. We will then verify that it is you and activate your console</li>
						<li id="error">23913 - Console creation denied!</li>
						<li id="error">23914 - Console is banned!</li>
						<li id="error">23915 - Attemtpting to tamper with console identifiers</li>
						<li id="error">23916 - Invalid MAC prefix. Only genuine Nintendo MAC addresses are allowed. If you believe this is in error, please contact an admin at our Discord group with a picture of the MAC address screen so we can verify it as having a valid MAC prefix.</li>
						<li id="error">23917 - Full ban from CoWFC</li>
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
