<?php

class File
{
    private $allowExt = ["gif", "jpeg", "jpg", "png"];  //允许的后缀名
    private $maxSize = 10485760;                        //文件大小限制:10MB:1024*1024*10
    private $MIME = ["image/gif", "image/jpeg", "image/jpg", "image/pjpeg", "image/x-png", "image/png"];//允许的文件内容形式
    private $file;

    public function __construct()
    {
        $this->file = $_FILES['file'];
    }

    public function upload()
    {
        $filename = pathinfo($this->file['name']);
        $file = $filename['filename'];   //获取文件名称
        $ext = $filename['extension'];   //获取后缀名称
        //1.检查文件是否有错
        $this->checkFile($ext);
        echo "上传文件名: " . $this->file["name"] . "<br>";
        echo "文件类型: " . $this->file["type"] . "<br>";
        echo "文件大小: " . ($this->file["size"] / 1024) . " kB<br>";
        echo "文件临时存储的位置: " . $this->file["tmp_name"] . "<br>";
        //2.将文件转移到指定位置
        $this->moveFile($file, $ext);
    }

    private function checkFile($ext)
    {
        switch ($this->file['error']) {
            case 1:
                exit('上传的文件超过了php.ini中upload_max_filesize选项限制的值');
            case 2:
                exit('上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值');
            case 3:
                exit('文件没有上传完毕');
            case 4:
                exit('没有上传文件');
            case 5:
                exit('上传的是空文件');
            case 6:
                exit('临时上传目录未找到');
            case 7:
                exit('磁盘空间不足，权限不允许');
        }
        if (!in_array($this->file['type'], $this->MIME) || !in_array($ext, $this->allowExt)) {
            exit('文件传输类型有错');
        }
        if ($this->file['size'] > $this->maxSize) {
            exit('文件大小超出限制');
        }
    }

    private function moveFile($filename, $ext)
    {
        $dir = "upload/";
        $filename = $dir . $filename . date("_Ymd", time()) . '.' . $ext;
        $filename = iconv('utf-8', 'gbk', $filename);
        // 判断当前目录下的 upload 目录是否存在该文件
        if (file_exists($filename)) {
            echo $this->file['name'], "文件已经存在!";
        } else {
            // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
            move_uploaded_file($this->file["tmp_name"], $filename);
            echo "文件存储在: $filename";
        }
    }
}

$file = new File();
$file->upload();