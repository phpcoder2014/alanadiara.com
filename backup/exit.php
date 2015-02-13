<?php
session_start();

unset ($_SESSION['net_users']);
session_destroy();
header('Location: index.php');
?>
