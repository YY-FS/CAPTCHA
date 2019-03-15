<?php
class CheckCode
{
    public function __construct()
    {
        session_start();
    }

    public function check($code)
    {
        if ($code) {
            if ($code == $_SESSION['code']) {
                unset($_SESSION['code']);
                $this->jsonOutPut('验证码正确');
            } else {
                $this->jsonOutPut('验证码错误');
            }
        } else {
            $this->jsonOutPut('未输入验证码');
        }
    }
    /**
     * [输出json]
     * @param  [type] $msg [description]
     * @return [type]      [description]
     */
    public function jsonOutPut($msg)
    {
        $data = [
            'code' => 200,
            'msg' => $msg
        ];
        echo json_encode($data);
    }
}

$code = strtoupper($_GET['code'] ?? '');    //获得参数，并且全部转换为大写字母
$check = new CheckCode();
$check->check($code);                       //检查并输出
