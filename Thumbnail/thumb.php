<?php
$file = './LFF.jpg';
list($width, $height) = getimagesize($file);
$percent = 0.3;     //定义缩放比例
$dstWidth = $percent * $width;      //缩略图的宽
$dstHeight = $percent * $height;    //缩略图的高
$srcim = imagecreatefromjpeg($file);//从原图创建画布
$dstim = imagecreatetruecolor($dstWidth, $dstHeight);  //创建正彩色画布
imagecopyresized($dstim, $srcim, 0, 0, 0, 0, $dstWidth, $dstHeight, $width, $height);    //创建缩略图
$newFile = './LFF_thumb.jpg';       //缩略图生成地址
imagejpeg($dstim, $newFile, 100);   //导出