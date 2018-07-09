<?php

namespace Admin\Controller;


class BaohuoController extends AdminController
{
    protected function _initialize(){
        parent::_initialize();
    }
    public function baohuo(){
        $this->display();
    }
    public function kucun(){
        $kucunM = M('kucun');
        $where = array();
        if(!empty(i('user_name'))){
            $b_uid = M('user')->where(array('user_name'=>i('user_name')))->getField('id');
            if($b_uid){
                $where['uid'] = $b_uid;
            }else{
                $where['uid'] = 0;
            }
        }
        $count = $kucunM->where($where)->count();
        $p     = new \Think\Page($count,20);
        $page  = $p->show();
        $data  = $kucunM->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
        $userM  = M('user');
        $usergM = M('user_group');
        $catM   = M('category');
        $chanM  = M('changjia');
        $cmM    = M('cm_size');
        foreach($data as &$itmes){
            $itmes['username']    = $userM->where("id={$itmes['uid']}")->getField('user_name');
            $itmes['groupname']   = $usergM->where("id={$itmes['gid']}")->getField('group_name');
            $itmes['catname1']    = $catM->where("id={$itmes['cat_id1']}")->getField('cat_name');
            $itmes['catname2']    = $catM->where("id={$itmes['cat_id2']}")->getField('cat_name');
//            $itmes['chanjia']     = $chanM->where("id={$itmes['chang_id']}")->getField('cname');
            $itmes['cm_name']     = $cmM->where("id={$itmes['cm_id']}")->getField('cm_name');
        }
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->display();
    }
    public function backchanjia(){
        $newsM = M('backchanjia');
        $where = array();
        if(!empty(i('user_name'))){
            $b_uid = M('user')->where(array('user_name'=>i('user_name')))->getField('id');
            if($b_uid){
                $where['uid'] = $b_uid;
            }else{
                $where['uid'] = 0;
            }
        }
        $count = $newsM->where($where)->count();
        $p     = new \Think\Page($count,20);
        $page  = $p->show();
        $data  = $newsM->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
        $userM  = M('user');
        $usergM = M('user_group');
        $catM   = M('category');
        $chanM = M('changjia');
        foreach($data as &$itmes){
            $itmes['username']    = $userM->where("id={$itmes['uid']}")->getField('user_name');
            $itmes['groupname']   = $usergM->where("id={$itmes['gid']}")->getField('group_name');
            $itmes['catname1']   = $catM->where("id={$itmes['cat_id1']}")->getField('cat_name');
            $itmes['catname2']   = $catM->where("id={$itmes['cat_id2']}")->getField('cat_name');
            $itmes['chanjia']     = $chanM->where("id={$itmes['chang_id']}")->getField('cname');
        }
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->display();
    }

    public function delChan(){
        $id = intval(I('id'));
        M('backchanjia')->where("id={$id}")->delete();
        $this->success('记录已经删除成功!');
    }
}
