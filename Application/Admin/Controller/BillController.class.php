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
        $pwhere   = array();
        $where    = '';
        if(!empty(i('gid'))){
            $pwhere['gid'] = i('gid');
            $where .= " gid=".i('gid');
        }
        $pwhere['state']   = 0;
        if(empty($where)){
            $where .= " state=0";
        }else{
            $where .= " and state=0";
        }


        $count = $recordM->where($where)->count();
        //计算出总和
        $sql = "select sum(chon_price) as chon_price from recharge where {$where}";
        $price_info = $recordM->query($sql);


        $p     = new \Think\Page($count,5,$pwhere);
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
        $this->assign('chon_price', $price_info[0]['chon_price']);
        $this->display();
    }

    public function record(){
        $this->display();
    }




}
