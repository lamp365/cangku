<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {

    public function _initialize(){

        $user = session("web_user");
        if (empty($user)) {
            redirect("/index.php/login");
        }
        $cont_name = CONTROLLER_NAME;
        $this->assign('cont_name',$cont_name);
	}

}