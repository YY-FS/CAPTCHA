<?php
class File
{
    public function downloadFile($path)
    {
        if (!file_exists($path)) {
            exit('文件不存在');
        }
        set_time_limit(0);                              //脚本执行时间不限
        $filesize = filesize($path) - 1;                //文件总字节数=文件大小-1
        $filename = basename($path);                    //取路径中的文件名
        $encoded_file_name = rawurldecode($filename);   //编码文件名
        $ua = $_SERVER['HTTP_USER_AGENT'];              // 兼容多浏览器下载
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename=' . $encoded_file_name);
        } else {
            if (preg_match("/Firefox/", $ua)) {
                header('Content-Disposition: attachment; filename*=utf8\'\'' . $filename);
            } else {
                header('Content-Disposition: attachment; filename=' . $filename);
            }
        }
        //未知文件类型，可告诉浏览器输出的是字节流
        header('content-type:application/octet-stream');
        header("Accept-Ranges:bytes");                       //告诉浏览器返回的文件大小类型是字节

        $range = 0;                                          //定义文件指针位置
        if (isset($_SERVER['HTTP_RANGE'])) {                 //如果有range，证明是断点续传
            // 断点后再次连接 $_SERVER['HTTP_RANGE'] 的值 bytes=4390912-
            list($bytes, $range) = explode('=', $_SERVER['HTTP_RANGE']);
            list($startPos, $endPos) = explode('-', $range);
            $range = trim($startPos);                        //开始位置
            header("HTTP/1.1 206 Partial Content");          //标志位206证明断点续传
            header("Content-Length:" . $filesize - $range);  //告诉浏览区返回的文件大小
            //Content-Range长这样：Content-Range: bytes 4908618-4988927/4988928
            header("Content-Range: bytes $range-$filesize/$fileSize");
        } else {
            header("HTTP/1.1 200 OK");             //标志位200
            header("Content-Length:" . $filesize); //告诉浏览区返回的文件大小
        } 
        ob_clean();                                //首次清除缓存
        flush();
        $buffer = 1024 * 8;                        //定义每次读取的长度
        $buffer_sum = 0;
        $fp = fopen($path, 'rb');                  //文件以二进制形式打开
        fseek($fp, $range);                        //设置指针位置
        while (!feof($fp) && $buffer_sum < $filesize) {
            $data = fread($fp, $buffer);           //读文件
            $buffer_sum += $buffer;
            echo $data;
            ob_clean();                            // 清除缓存，防止文件过大
            flush();
        }
        fclose($fp);                               //关闭句柄
        exit();                                    //使用exit退出，防止脚本占用文件，导致文件打不开
    }

    /**
     * [填充文件，必须cli运行]
     * @param  [type] $path [description]
     */
    public function createPDF($path)
    {
        $num = 0;
        $limit = 100000;
        $fp = fopen($path, 'a');                //追加模式打开
        for ($i = 1; $i <= 100000000; $i++) {
            $num++;
            //刷新一下输出buffer，防止由于数据过多造成问题
            if ($limit == $num) {
                ob_flush();
                flush();
                $num = 0;
            }
            fwrite($fp, $i . PHP_EOL);
        }
        fclose($fp);
    }
}

$file = new File();
//$file->createPDF('test.txt');
$file->downloadFile('test.txt');