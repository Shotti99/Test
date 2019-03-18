<?php
$image=$_GET['img'];

if (!is_file($image))
	$image='images/no-photo.jpg';
$size	= GetImageSize($image);
$mime	= $size['mime'];
if (substr($mime, 0, 6) != 'image/'){
	/*
	header('HTTP/1.1 400 Bad Request');
	exit();
	*/
}
$quality= (int) $_GET['q'];
$quality = ($quality==0?100:$quality);
$width			= $size[0];
$height			= $size[1];
$maxWidth		= (isset($_GET['w'])) ? (int) $_GET['w'] : $width;
$maxHeight		= (isset($_GET['h'])) ? (int) $_GET['h'] : $height;
$xRatio		= $maxWidth / $width;
$yRatio		= $maxHeight / $height;
if ($xRatio * $height < $maxHeight){ 
	$tnHeight	= ceil($xRatio * $height);
	$tnWidth	= $maxWidth;
}
else
{
	$tnWidth	= ceil($yRatio * $width);
 	$tnHeight	= $maxHeight;
}
$tnWidth=($tnWidth==0?$width:$tnWidth);
$tnHeight=($tnHeight==0?$height:$tnHeight);

$dst	= imagecreatetruecolor($tnWidth, $tnHeight);
switch ($size['mime'])
{
	case 'image/gif':
		$creationFunction	= 'ImageCreateFromGif';
		$outputFunction		= 'ImagePng';
		$mime				= 'image/png'; 
		$doSharpen			= FALSE;
		$quality			= round(10 - ($quality / 10)); 
	break;
	
	case 'image/x-png':
	case 'image/png':
		$creationFunction	= 'ImageCreateFromPng';
		$outputFunction		= 'ImagePng';
		$doSharpen			= FALSE;
		$quality			= round(10 - ($quality / 10)); 
	break;
	
	default:
		$creationFunction	= 'ImageCreateFromJpeg';
		$outputFunction	 	= 'ImageJpeg';
		$doSharpen			= TRUE;
	break;
}

$src	= $creationFunction($image);

if (in_array($size['mime'], array('image/gif', 'image/png')))
{

		imagealphablending($dst, false);
		imagesavealpha($dst, true);

}
ImageCopyResampled($dst, $src, 0, 0, 0, 0, $tnWidth, $tnHeight, $width, $height);
header("Content-type: ".$size['mime']."");
$outputFunction($dst,'',$quality);
ImageDestroy($src);
ImageDestroy($dst);


?>