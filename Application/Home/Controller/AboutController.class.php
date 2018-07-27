<?php
namespace Home\Controller;
use Think\Controller;


class AboutController extends Controller {
    public function index(){
        $sets = M('siteconfig')->find();
        $this->assign("sets",$sets);
        $this->display();
	}

}