<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/Page.php');

class NotFound extends Page {
	
	public function buildPage(): void {
?>
<div id="main" class="wrapper style1">
	<div class="container">
		<header class="major">
			<h2>404</h2>
			<p>Oops! We couldn't find the page you were looking for.</p>
		</header>
	</div>
</div>
<?php
	}
}
?>