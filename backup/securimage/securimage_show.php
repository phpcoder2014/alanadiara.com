<?php
include 'securimage.php';

$img = new securimage();
// Change some settings
$img->code_length = rand(5,6);
$img->image_width = 300;
$img->image_height = 90;
$img->perturbation = 0.7; // 1.0 = high distortion, higher numbers = more distortion
$img->image_bg_color = new Securimage_Color("#ffffff");
$img->text_color = new Securimage_Color("#bbb");
$img->text_transparency_percentage = 50; // 100 = completely transparent
$img->num_lines = 10;
$img->line_color = new Securimage_Color("#cccccc");
///$img->signature_color = new Securimage_Color(rand(0, 64), rand(64, 128), rand(128, 255));
$img->image_type = SI_IMAGE_PNG;
$img->show(); // alternate use:  $img->show('/path/to/background_image.jpg');
?>