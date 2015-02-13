<?php
session_start();
include("securimage/securimage.php");
$img = new Securimage();
$valid = $img->check($_POST['code']);

if($valid == true) {
   echo "ok";
} else {
   echo "no";
}
?>
