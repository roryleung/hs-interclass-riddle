<?php
$im = imagecreatefrompng("color_a.png");
$rgb = imagecolorat($im, 10, 15);

$colors = imagecolorsforindex($im, $rgb);

var_dump($colors);
?>
