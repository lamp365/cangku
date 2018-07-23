<?php
namespace Home\Controller;
use Think\Controller;

class LoginController extends Controller {

    public function index(){
        $session_user = session("web_user");
        if (!empty($session_user)) {
            redirect("/index.php/center");
        }
        $this->display('Public/login');
	}

	public function dologin(){

        $phone = I('phone');//手机
        $password = I('password');//密码
       //先查找是否有次手机用户
        $result = M('user') -> where(array('mobile' => $phone)) -> find();

        if (!$result) {
            $this->error('无此用户');
        }
        if ($result['is_forbid'] != 0) {
            $this->error('该用户已经被禁止登录，请联系管理员！');
        }

        if ($result['password'] != md5(md5($password))) {
            $this->error('密码错误！');
        }
        //登录成功
        session('web_user',$result);
        
        $this->success('登录成功');
    }

    public function loginOut(){
        session_destroy();
        redirect("/");
    }
}