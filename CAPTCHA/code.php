<?php
class Code
{
    private $str = "023456789ABCDEFGHJKMNOPQRSTUVWXYZ";//验证码的字符，去除1，i，l，因为有点像
    private $len, $width, $height;                     //定义验证码的字符数量，宽，高
    private $im;                                       //画布资源

    public function __construct($len = 4, $width = 30, $height = 50)
    {
        $this->width = $width;      //初始化成员变量
        $this->height = $height;
        $this->len = $len;
        session_start();            //开启session
        $this->getCode();           //获得验证码图片
    }

    /**
     * 获得验证码图片
     */
    private function getCode()
    {
        //1.创建画布资源
        $this->createPic();
        //2.创建背景颜色
        $gray = $this->createColor(176, 196, 222);
        imagefill($this->im, 0, 0, $gray);
        //3.加上验证码字
        $this->addString();
        //4.加干扰元素
        $this->addPix();
        $this->addLine();
    }

    /**
     * 4.加干扰元素：加像素点
     */
    private function addPix()
    {
        for ($i = 0; $i < 200; $i++) {
            $color = $this->createColor(mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100)); //随机获取颜色，取比较深的
            //加像素点
            imagesetpixel($this->im, mt_rand(1, ($this->width * $this->len) - 1), mt_rand(1, $this->height - 1),
                $color);
        }
    }

    /**
     * 4.加干扰元素：加线 
     */
    private function addLine()
    {
        for ($i = 0; $i < 5; $i++) {
            $color = $this->createColor(mt_rand(0, 180), mt_rand(0, 180), mt_rand(0, 180));
            //画线
            imageline($this->im, mt_rand(1, ($this->width * $this->len) - 1), mt_rand(1, $this->height - 1),
                mt_rand(1, ($this->width * $this->len) - 1), mt_rand(1, $this->height - 1), $color);
        }
    }

    /**
     * 3.加字
     */
    private function addString()
    {
        $lens = strlen($this->str);     //计算验证码的长度
        $sessStr = '';                  //最终塞入session的验证码
        for ($i = 0; $i < $this->len; $i++) {
            //1.设置字体颜色
            $str = $this->str[mt_rand(0, $lens)];   //随机取出一个字符
            $sessStr .= $str;                       //连接验证码
            $color = $this->createColor(mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200)); //生成颜色
            //2.加字，imagettftext可控制字体，大小，颜色，旋转角度，摆放位置
            imagettftext($this->im, mt_rand(15, 25), mt_rand(-70, 70), $this->width * $i + mt_rand(5, 15),
                mt_rand(20, 40), $color, 'consola.ttf', $str);
        }
        $_SESSION['code'] = $sessStr;   //存入session中
    }

    /**
     * 2.创建画布资源
     */
    private function createPic()
    {
        $this->im = imagecreatetruecolor($this->width * $this->len, $this->height);
    }

    /**
     * 创建颜色
     * @param $r
     * @param $g
     * @param $b
     * @return int
     */
    private function createColor($r, $g, $b)
    {
        return imagecolorallocate($this->im, $r, $g, $b);
    }

    /**
     * 输出验证码图片
     */
    public function showCode()
    {
        header('content-type:image/png');
        imagepng($this->im);
        imagedestroy($this->im);
    }
}

$q = new Code();
$q->showCode();