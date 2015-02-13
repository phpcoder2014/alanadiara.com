<?php
session_start();
if($_SESSION['netadmin'] !=true) {
    header("location:login.php");
    exit();
}

?>
