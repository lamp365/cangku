<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {

    public function _initialize(){

        $session_user = session("web_user");
        $sets = M('siteconfig')->find();
        if (empty($session_user)) {
            redirect("/index.php/login");
        }
        $group_info = M('user_group')->where("id={$session_user['gid']}")->find();
        //得到属组
        $session_group_name  = $group_info['group_name'];
        //得到目前资金
        $session_group_money = $group_info['money'];
        $cont_name = CONTROLLER_NAME;
        $this->assign('sets',$sets);
        $this->assign('cont_name',$cont_name);
        $this->assign('session_user',$session_user);
        $this->assign('session_group_name',$session_group_name);
        $this->assign('session_group_money',$session_group_money);

    }

}