<?php
session_start();
session_destroy();
header("Location: /?page=admin&section=dashboard");
?>