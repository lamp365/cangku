<?php
namespace Home\Controller;
use Think\Controller;


class IndexController extends Controller {
    public function index(){
        $sets = M('siteconfig')->find();
        $newData = M('news')->where("tuijian=1")->limit(4)->select();
        $this->assign("newData",$newData);
        $this->assign("sets",$sets);
        $this->display();
	}

	public function ceshi(){
        $sets = M('siteconfig')->find();
        $this->assign("sets",$sets);
        $this->display();
    }

}