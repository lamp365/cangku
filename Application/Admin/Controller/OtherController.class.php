<?php

namespace Admin\Controller;


class OtherController extends AdminController
{
    protected function _initialize(){
        parent::_initialize();
    }
    public function news(){
        $newsM = M('news');

        $count = $newsM->count();
        $p     = new \Think\Page($count,20);
        $page  = $p->show();
        $data  = $newsM->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();

        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->display();
    }

    public function editNews(){
        $id = intval(I('id'));
        $news = M('news')->find($id);
        if(IS_POST){
            $data = I('post.');
            unset($data['id']);
            if(empty($data['pic'])){
                $data['pic'] = "/Public/Admin/img/news.jpg";
            }
            if(empty($id)){
                $data['c_date'] = time();
                M('news')->add($data);
            }else{
                M('news')->where("id={$id}")->save($data);
            }
            $this->success('文章操作成功!');
        }
        $news['content'] = htmlspecialchars_decode(html_entity_decode($news['content']));
        $this->assign('res',$news);
        $this->display();
    }

    public function delNews(){
        $id = intval(I('id'));
        M('news')->where("id={$id}")->delete();
        $this->success('文章删除成功!');
    }

    public function borrow(){
        $newsM = M('borrow');

        $count = $newsM->count();
        $p     = new \Think\Page($count,20);
        $page  = $p->show();
        $data  = $newsM->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
        $userM  = M('user');
        $usergM = M('user_group');
        foreach($data as &$itmes){
            $itmes['b_username']  = $userM->where("id={$itmes['b_uid']}")->getField('user_name');
            $itmes['username']    = $userM->where("id={$itmes['uid']}")->getField('user_name');
            $itmes['groupname']    = $usergM->where("id={$itmes['gid']}")->getField('group_name');
        }
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->display();
    }
}
