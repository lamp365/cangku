<?php

namespace Admin\Controller;


class BillController extends AdminController
{
    protected function _initialize(){
        parent::_initialize();
    }
    public function index(){

        $this->display();
    }

    public function tocheck(){
        $this->display();
    }

    //等待审核
    public function torecord(){
        $recordM = M('recharge');
        $where   = array();
        if(!empty(i('gid'))){
            $where['gid'] = i('gid');
        }
        $where['state']   = 0;
        $count = $recordM->where($where)->count();
        $p     = new \Think\Page($count,5,$where);
        $page  = $p->show();
        $data  = $recordM->where($where)->limit($p->firstRow.','.$p->listRows)->select();
        $userM  = M('user');
        $usergM = M('user_group');
        foreach ($data as &$item){
            $user_name  = $userM->where(array('id'=>$item['uid']))->getField('user_name');
            $group_name = $usergM->where(array('id'=>$item['gid']))->getField('group_name');
            $pic        = array();
            if(!empty($item['pic'])){
                $pic = explode(',',$item['pic']);
            }
            $item['user_name']  = $user_name;
            $item['group_name'] = $group_name;
            $item['pic_list']   = $pic;
        }
        $this->assign('page', $page);
        $this->assign('data', $data);
        $this->display();
    }

    public function record(){
        $this->display();
    }




}
