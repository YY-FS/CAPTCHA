<?php
class Dir{
    public $fileList = [];          //最终存储目录的数组
    function readDirs($path,$deep){
        $handle = opendir($path);   //打开句柄资源
        $fileInfo = [];
        while(false !== ($filename = readdir($handle))){
            if($filename == '.' || $filename == '..')   //为当前目录或上一目录则跳过
                continue;
            $fileInfo['filename'] = $filename;          //文件名
            $fileInfo['deep'] = $deep;                  //当前深度
            $this->fileList[] = $fileInfo;              //存入结果数组中
            $newPath = $path.'/'.$filename;             //构建新的路径
            if(is_dir($newPath)){                       //判断新的路径是否依旧是目录，是则递归
                $this->readDir($newPath,$deep+1);
            }
        }
        closedir($handle);          //关闭资源
    }
    public function rmDirs($path){
        $handle = opendir($path);   //打开句柄资源
        while(false !== ($filename = readdir($handle))){
            if($filename == '.' || $filename == '..') 
                continue;
            $newPath = $path.'/'.$filename;
            if(is_dir($newPath)){
                $this->rmDirs($newPath);    //新的路径为目录则递归删除
            }else{
                unlink($newPath);   //删除文件
            }
        }
        closedir($handle);
    }
}
$dir = new Dir();
$dir->readDirs('./path', 0);
echo '<pre>';
var_dump($dir->fileList);
echo '</pre>';
$dir->rmDirs('./path');