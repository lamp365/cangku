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

    //充值余额管理
    public function torecord(){
        $state    = intval(i('state'));
        $timeInfo = getTimestapFromTimeJS();
        $s_time  = $timeInfo['s_time'];
        $e_time  = $timeInfo['e_time'];
        $gid     = intval(i('gid'));
        $recordM = M('recharge');
        $pwhere   = array();
        $where    = '';
        if(!empty($gid)){
            $pwhere['gid'] = $gid;
            $where .= " gid={$gid} ";
        }
        $pwhere['state']   = $state;
        $where .= "and state={$state} ";

        if(!empty($s_time) && !empty($e_time)){
            $where .= "and c_date > {$s_time} and c_date < {$e_time} ";
            $pwhere['c_date'] = array(array('gt',$s_time),array('lt',$e_time));
        }

        $where = ltrim($where,'and');
        $count = $recordM->where($where)->count();
        //计算出总和
        $sql = "select sum(chon_price) as chon_price from recharge where {$where}";
        $price_info = $recordM->query($sql);


        $p     = new \Think\Page($count,30);
        $page  = $p->show();
        $data  = $recordM->where($where)->limit($p->firstRow.','.$p->listRows)->select();
        $userM  = M('user');
        $usergM = M('user_group');
        foreach ($data as &$item){
            $user_info  = $userM->where(array('id'=>$item['uid']))->getField('id,user_name,mobile');
            $group_name = $usergM->where(array('id'=>$item['gid']))->getField('group_name');
            $pic        = array();
            if(!empty($item['pic'])){
                $pic = explode(',',$item['pic']);
            }
            $item['user_name']  = $user_info[1]['user_name'];
            $item['mobile']     = $user_info[1]['mobile'];
            $item['group_name'] = $group_name;
            $item['pic_list']   = $pic;
        }

        //获取小组
        $all_group = $usergM->field('id,group_name')->select();

        $this->assign('page', $page);
        $this->assign('data', $data);
        $this->assign('state', $state);
        $this->assign('all_group', $all_group);
        $this->assign('chon_price', $price_info[0]['chon_price']);

        $this->assign('s_time',$s_time);
        $this->assign('e_time',$e_time);
        $this->assign('gid',$gid);
        $this->display();
    }

    public function delBill(){
        $id = intval(i('id'));
        M('recharge')->where("id={$id}")->delete();
        $this->success('删除成功');
    }

    public function okBill(){
        $id = intval(i('id'));
        M('recharge')->where("id={$id}")->save(array('state'=>1));
        $this->success('已经审核成功');
    }

    public function eidtBill(){
        $id    = intval(i('id'));
        $price = i('price');
        if(!is_numeric($price)){
            $this->error('请设置金额为数字');
        }
        M('recharge')->where("id={$id}")->save(array('chon_price'=>$price));
        $this->success('修改成功');
    }

}
