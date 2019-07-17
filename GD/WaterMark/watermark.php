<?php
$srcim = imagecreatefromjpeg('./LFF.jpg');
/************** 图片水印 **************/
$waterim = imagecreatefrompng('./cctv.png');
list($waterWidth, $waterHeight) = getimagesize('./cctv.png');
//普通水印
imagecopy($srcim, $waterim, 20, 20, 0, 0, $waterWidth, $waterHeight);
//半透明水印
imagecopymerge($srcim, $waterim, 20, 20, 0, 0, $waterWidth, $waterHeight, 20);
/************** 文字水印 **************/
$fontStyle = './consola.ttf';          //定义字体模式
$color = imagecolorallocate($srcim, 255, 255, 255); //RGB白色
imagefttext($srcim, 30, 0, 20, 50, $color, $fontStyle, 'YY-FS');

$file = './LFF_water.jpg';
imagejpeg($srcim, $file, 100);  //输出图像
imagedestroy($waterim);                //销毁资源
imagedestroy($srcim);