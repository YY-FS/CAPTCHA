<?php
class Dir{
    public $fileList = [];
    function readDirs($path,$deep){
        $handle = opendir($path);
        $fileInfo = [];
        while(false !== ($filename = readdir($handle))){
            if($filename == '.' || $filename == '..') 
                continue;
            $fileInfo['filename'] = $filename;
            $fileInfo['deep'] = $deep;
            $this->fileList[] = $fileInfo;
            $newPath = $path.'/'.$filename;
            if(is_dir($newPath)){
                $this->readDir($newPath,$deep+1);
            }
        }
        closedir($handle);
    }
    public function rmDirs($path){
        $handle = opendir($path);
        while(false !== ($filename = readdir($handle))){
            if($filename == '.' || $filename == '..') 
                continue;
            $newPath = $path.'/'.$filename;
            if(is_dir($newPath)){
                $this->rmDirs($newPath);
            }else{
                unlink($newPath);
            }
        }
        closedir($handle);
    }
}
$dir = new Dir();
$dir->rmDirs('./path',0);
echo '<pre>';
//var_dump($dir->fileList);
echo '</pre>';