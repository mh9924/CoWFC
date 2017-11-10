<?php

class Login {
	private $username;
	private $password;
	private $errors = array();
	private $panel;
	private static $secret;
	
	public function __construct(AdminPage $panel){
		$this->panel = $panel;
		$this->secret = $this->panel->site->config["admin"]["recaptcha_secret"];
		if(isset($_POST['username'], $_POST['password'], $_POST['g-recaptcha-response'])){
			$this->username = $_POST['username'];
			$this->password = $_POST['password'];
			$this->doLogin();
		}
		if (!$this->panel->logged_in){
			$this->buildLogin();
		} else {
			header("Location: /?page=admin&section=dashboard");
		}
	}
	
	private function buildErrors(): void {
		foreach($this->errors as $error){
			echo "{$error}<br>";
		}
	}
	
	private function doLogin(): void {
		$robot = false;
		if (!$this->verifyCaptcha()) {
			$this->errors[] = "Invalid captcha.";
			$robot = true;
		}
		if(empty($this->username) or empty($this->password)){
			$this->errors[] = "Please complete all fields.";
			return;
		}
		elseif(!$this->verifyUsername()){
			$this->errors[] = "Username not found.";
			return;
		}
		elseif(!$this->verifyPassword()){
			$this->errors[] = "Incorrect password.";
			return;
		}
		if($robot) return;
		else {
			$this->panel->logged_in = true;
			$_SESSION['username'] = $this->username;
			$_SESSION['password'] = $this->password;
		}
	}
	
	private function verifyUsername(): bool {
		$stmt = $this->panel->udatabase->prepare("SELECT * FROM users WHERE Username = :Username");
		$stmt->bindParam(':Username', $this->username);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);  # SQlite does not support rowCount() ...
		if (!$result) return false;
		return true;
	}
	
	private function verifyPassword(): bool {
		$stmt = $this->panel->udatabase->prepare("SELECT * from users WHERE Username = :Username");
		$stmt->bindParam(':Username', $this->username);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return password_verify($this->password, $result['Password']);
	}
	
	private function verifyCaptcha(): bool {
		require_once("recaptchalib.php");
		$response = null;
		$reCaptcha = new reCaptcha(self::$secret);
		if ($_POST['g-recaptcha-response']) {
			$response = $reCaptcha->verifyResponse(
			$_SERVER["REMOTE_ADDR"],
			$_POST["g-recaptcha-response"]
			);
		}
		return $response != null && $response->success;
	}
	
	private function buildLogin(){
?>
<html>
<head>
<title>Login</title>
<script src='https://www.google.com/recaptcha/api.js'></script>
<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
<style>
@font-face {
	font-family: Continuum;
	src: url('../fonts/contm.ttf');
}
</style>
</head>
<body style="text-align: center;margin-top:100px;font-family: Arial;background-color: #222222;">
<img src="images/cowfc-panel.png">
<h4 style="font-family: Continuum;color: white;">Use the form below to login to the admin panel.</h4>
<mark style="background-color: red;"><?php $this->buildErrors(); ?></mark>
<br />
<form action="" method="post" class="pure-form">
<input type="text" name="username" id="username" maxlength="24" placeholder="Username">
<br />
<input type="password" name="password" id="password" maxlength="32" placeholder="Password">
<br />
<br />
<center><div class="g-recaptcha" data-theme="dark" data-sitekey="SITE_KEY_HERE"></div></center>
<br />
<button type="submit" class="pure-button pure-button-primary">Login</button>
</body>
</html>
<?php 
	}
}
?>
