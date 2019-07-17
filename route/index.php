<?php
echo '<pre>';
var_dump($_SERVER);
echo '</pre>';

//定义根目录
define('APPPATH', trim(__DIR__,'/'));

//获得请求地址
$root = $_SERVER['SCRIPT_NAME']; //"/code/route/index.php"
$request = trim($_SERVER['PATH_INFO'],'/'); //"admin/666/1111/213213"
if($request == ''){
    $class = 'index'
}