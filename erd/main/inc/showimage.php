<?
if(!isset($_GET['p'])) { exit; }
$diagram="../$_GET[p]";
if(!file_exists($diagram)) { exit; }

Header("Pragma: no-cache");
Header("Expires: Thu, 26-Oct-1972 12:00:00");
Header("Content-type: image/png");
$image = ImageCreateFromPNG($diagram);

ImagePng($image);
ImageDestroy($image);