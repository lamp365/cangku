<?php
namespace Home\Controller;
use Think\Controller;


class NewsController extends Controller {
    public function index(){
        $sets = M('siteconfig')->find();

        $count = M('news')->count();
        $p     = new \Think\Page($count,8);
        $page  = $p->show();
        $newsData  = M('news')->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();

        $this->assign("newsData",$newsData);
        $this->assign("page",$page);
        $this->assign("sets",$sets);
        $this->display();
	}
	public function detail(){
	    $id = i('id');
        if(empty($id)){
            $this->error('参数有误!');
        }
        $newsinfo = M('news')->find($id);
        $nextNews = M('news')->where("id>{$id}")->find();
        $sets = M('siteconfig')->find();
        $sql     = "select * from `news` order by rand() limit 6";
        $randNews = M('news')->query($sql);
        $this->assign("newsinfo",$newsinfo);
        $this->assign("nextNews",$nextNews);
        $this->assign("randNews",$randNews);
        $this->assign("sets",$sets);
        $this->display();
    }

}