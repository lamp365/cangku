<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function _initialize(){
        //登录验证
        $user = session("web_user");
        if (empty($user)) {
            redirect("/index.php/login");
        }
        $cont_name = CONTROLLER_NAME;
        $this->assign('cont_name',$cont_name);
	}

}