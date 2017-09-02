<?php
session_start();
session_destroy();
header("Location: http://cowfc.com/?page=admin&section=dashboard");
?>