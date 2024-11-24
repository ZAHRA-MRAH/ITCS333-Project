<?php
session_srart();
$_SESSION = array();
seesion_destroy();
header("Location: login.php");
?>
