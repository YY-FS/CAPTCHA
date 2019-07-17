<?php
$fileInfo = $_FILES['file'];
switch ($fileInfo['type']) {            //选择合适的方法创建画布
    case 'image/jpeg':
    case 'image/jpg':
        $im = imagecreatefromjpeg($fileInfo['tmp_name']);
        break;
    case 'image/png':
        $im = imagecreatefrompng($fileInfo['tmp_name']);
        break;
    case 'image/gif':
        $im = imagecreatefromgif($fileInfo['tmp_name']);
        break;
}
switch ($_POST['filter']) {             //选择特效样式
    case 1:
        $filter = IMG_FILTER_NEGATE;    //反色
        break;
    case 2:
        $filter = IMG_FILTER_EMBOSS;    //浮雕
        break;
    case 3:
        $filter = IMG_FILTER_SELECTIVE_BLUR;    //模糊
        break;
    case 4:
        $filter = IMG_FILTER_GRAYSCALE; //灰度
        break;
}
imagefilter($im, $filter);              //图片特效绘制
$file = './filter.jpg';
imagejpeg($im, $file, 100);      //输出图像
imagedestroy($im);                      //销毁图像
echo json_encode([
    'code' => 0,
    'msg' => 'success',
    'src' => $file
]);
