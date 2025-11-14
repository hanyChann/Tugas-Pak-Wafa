<?php
session_start();
$_SESSION = array();
session_destroy();
session_start();
$_SESSION['login_success'] = "Anda telah berhasil logout.";
header("location: index.php");
exit;
?>