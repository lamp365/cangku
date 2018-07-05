<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\AuthRuleModel;

class AdminController extends Controller {


	protected $admin_authoried = true;
	protected function _initialize(){
		$user = get_user();
		if ($this->admin_authoried && empty($user)) {
			redirect("/index.php/admin/account/login");
		}

		$this->assign("self", __SELF__);

	}

	public function editPass(){
        if(IS_POST){
            $data = i('post.');
            if($data['repass'] != $data['pass']){
                $this->error('确认密码有误!');
            }
            if(empty($data['repass']) || empty($data['pass'])){
                $this->error('密码不能为空!');
            }
            $empoloyeeID = get_user_id();
            $user_info = M("employee")->where(array("employeeID"=>$empoloyeeID))->find();
            $the_pass  = $user_info['password'];
            $the_rand  = $user_info['random'];
            if($the_pass!= get_guoyuanPWD($data['oldPass'],$the_rand)){
                $this->error('旧的密码输入有误!');
            }
            $new_rand = rand(10000,99999);
            $new_pass =  get_guoyuanPWD($data['pass'],$new_rand);
            M("employee")->where(array("employeeID"=>$empoloyeeID))->save(array('password'=>$new_pass,'random'=>$new_rand));
            $this->success('密码已经修改,请牢记!');
        }
	    $this->display();
    }

    public function editInfo(){
        if(IS_POST){

        }
        $this->display('editInfo');

    }
}