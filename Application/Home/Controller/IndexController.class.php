<?php
namespace Home\Controller;
use Think\Controller;


class IndexController extends Controller {
    public function index(){
        $sets = M('siteconfig')->find();
        $this->assign("sets",$sets);
        $this->display();
	}

	public function ceshi(){
        $sets = M('siteconfig')->find();
        $this->assign("sets",$sets);
        $this->display();
    }

}