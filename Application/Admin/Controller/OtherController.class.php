<?php

namespace Admin\Controller;


class OtherController extends AdminController
{
    protected function _initialize(){
        parent::_initialize();
    }
    public function news(){
        $tuijian = intval(i('tuijian'));
        $where = array();
        if(!empty($tuijian)){
            $where['tuijian'] = $tuijian;
        }
        $newsM = M('news');

        $count = $newsM->where($where)->count();
        $p     = new \Think\Page($count,20);
        $page  = $p->show();
        $data  = $newsM->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();

        $this->assign('tuijian',$tuijian);
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

    public function tuijian(){
        $id = intval(I('id'));
        $tuijian = i('tuijian');
        M('news')->where("id={$id}")->save(array("tuijian"=>$tuijian));
        $this->success('操作成功!');
    }

    public function borrow(){
        $newsM = M('borrow');
        $where = array();
        $states = intval(i('states'));
        if(empty($states)){
            $states = 1;
            $_GET['states'] = 1;
        }
        $where['states'] = $states;
        if(!empty(i('user_name'))){
            $b_uid = M('user')->where(array('user_name'=>i('user_name')))->getField('id');
            if($b_uid){
                $where['b_uid'] = $b_uid;
            }else{
                $where['b_uid'] = 0;
            }
        }
        $count = $newsM->where($where)->count();
        $p     = new \Think\Page($count,25);
        $page  = $p->show();
        $data  = $newsM->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
        $userM  = M('user');
        $usergM = M('user_group');
        foreach($data as &$itmes){
            $itmes['b_username']  = $userM->where("id={$itmes['b_uid']}")->getField('user_name');
            $itmes['username']    = $userM->where("id={$itmes['uid']}")->getField('user_name');
            $itmes['groupname']   = $usergM->where("id={$itmes['gid']}")->getField('group_name');
        }
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('states',$states);
        $this->display();
    }

    public function sureBack(){
        $id = intval(i('id'));
        $newsM = M('borrow');
        $borrow = $newsM->find($id);

        $res = $newsM->where("id={$id}")->save(array('states'=>2));
        if($res){
            $cu_id = $borrow['cu_id'];
            $ku_data = M('kucun')->find($cu_id);
            //数量换回去 加1
            /*$data = array(
                'num'   => array('exp', "`num`+1"),
            );*/
            $new_num = $borrow['num']+$ku_data['num'];
            $data = array('num'=>$new_num);
            M('kucun')->where("id={$cu_id}")->save($data);
        }
        $this->success('已经确认归还');
    }
}
