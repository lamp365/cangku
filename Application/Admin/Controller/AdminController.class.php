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
}