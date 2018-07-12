<?php

namespace Admin\Controller;


class BillController extends AdminController
{
    protected function _initialize(){
        parent::_initialize();
    }
    public function index(){
        $is_check    = intval(i('is_check'));
        $state    = intval(i('state'));
        $timeInfo = getTimestapFromTimeJS();
        $s_time   = $timeInfo['s_time'];
        $e_time   = $timeInfo['e_time'];
        $gid      = intval(i('gid'));
        $shop_id  = intval(i('shop_id'));
        $user_name= i('user_name');
        $uid      = getUidFromName($user_name);
        $where    = '';
        $pwhere   = array();
        $where    = " is_check={$is_check} ";
        $pwhere['is_check'] = $is_check;
        if(!empty($gid)){
            $pwhere['gid'] = $gid;
            $where .= " and gid={$gid} ";
        }
        if(!empty($state)){
            $pwhere['state'] = $state;
            $where .= " and state={$state} ";
        }
        if(!empty($gid)){
            $pwhere['uid'] = $uid;
            $where .= " and uid={$uid} ";
        }
        if(!empty($shop_id)){
            $pwhere['shop_id'] = $shop_id;
            $where .= " and shop_id={$shop_id} ";
        }
        if(!empty($s_time) && !empty($e_time)){
            $where .= "and c_date > {$s_time} and c_date < {$e_time} ";
            $pwhere['c_date'] = array(array('gt',$s_time),array('lt',$e_time));
        }

        $billM    = M('bill');

        //计算出总和
        $sql = "select sum(jin_price) as jin_price, sum(da_price) as da_price,sum(shua_price) as shua_price,sum(mai_price) as mai_price from bill where {$where}";
        $price_info = $billM->query($sql);
        $total_lirun = $price_info[0]['mai_price']-$price_info[0]['shua_price']-$price_info[0]['da_price']-$price_info[0]['jin_price'];

        $count = $billM->where($where)->count();
        $p     = new \Think\Page($count,30);
        $page  = $p->show();
        $data  = $billM->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
        $userM  = M('user');
        $usergM = M('user_group');
        $userShop = M('user_shop');

        foreach ($data as &$item){
            $user_info  = $userM->where(array('id'=>$item['uid']))->find();
            $group_name = $usergM->where(array('id'=>$item['gid']))->getField('group_name');
            $shop_info  = $userShop->where(array('id'=>$item['shop_id']))->find();
            $item['user_name']  = $user_info['user_name'];
            $item['mobile']     = $user_info['mobile'];
            $item['group_name'] = $group_name;
            $item['shop_name']  = $shop_info['shop_name'];
            $item['shop_zg']  = $shop_info['shop_zg'];
        }

        //获取小组
        $all_group = $usergM->field('id,group_name')->select();
        //获取店铺
        $all_shop  = $userShop->field('id,shop_name')->select();

        $this->assign('page', $page);
        $this->assign('data', $data);
        $this->assign('is_check', $is_check);
        $this->assign('all_group', $all_group);
        $this->assign('all_shop', $all_shop);

        $this->assign('s_time',$s_time);
        $this->assign('e_time',$e_time);
        $this->assign('gid',$gid);
        $this->assign('state',$state);
        $this->assign('shop_id',$shop_id);
        $this->assign('user_name',$user_name);
        $this->assign('price_info',$price_info);
        $this->assign('total_lirun',number_format($total_lirun,2,'.',''));

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
        $data  = $recordM->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
        $userM  = M('user');
        $usergM = M('user_group');
        foreach ($data as &$item){
            $user_info  = $userM->where(array('id'=>$item['uid']))->find();
            $group_name = $usergM->where(array('id'=>$item['gid']))->getField('group_name');
            $pic        = array();
            if(!empty($item['pic'])){
                $pic = explode(',',$item['pic']);
            }
            $item['user_name']  = $user_info['user_name'];
            $item['mobile']     = $user_info['mobile'];
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
    //审核充值的
    public function okBill(){
        $id = intval(i('id'));
        M('recharge')->where("id={$id}")->save(array('state'=>1));
        $priceInfo = M('recharge')->where("id={$id}")->find();
        //加到小组资金中
        $price = $priceInfo['chon_price'];
        $gid   = $priceInfo['gid'];
        $data = array(
            'money'   => array('exp', "`money`+{$price}"),
        );
        M('user_group')->where("id={$gid}")->save($data);
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
    //审核账单的
    public function checkBill(){
        $id = intval(i('id'));
        M('bill')->where("id={$id}")->save(array('is_check'=>1,'x_date'=>time()));
        $this->success('已经审核成功');
    }

    public function updateBill(){
        $id = intval(i('id'));
        if(IS_POST){
            $data = i('post.');
            $data['jin_price']  = number_format($data['jin_price'],2,'.','');
            $data['da_price']   = number_format($data['da_price'],2,'.','');
            $data['shua_price'] = number_format($data['shua_price'],2,'.','');
            $data['mai_price']  = number_format($data['mai_price'],2,'.','');
            unset($data['id']);
            M('bill')->where("id={$id}")->save($data);
            $this->success('已经修改成功');
        }else{
            $billInfo = M('bill')->find($id);
            $user_info  = M('user')->where(array('id'=>$billInfo['uid']))->find();
            $group_name = M('user_group')->where(array('id'=>$billInfo['gid']))->getField('group_name');
            $shop_info  = M('user_shop')->where(array('id'=>$billInfo['shop_id']))->find();
            $billInfo['user_name']  = $user_info['user_name'];
            $billInfo['mobile']     = $user_info['mobile'];
            $billInfo['group_name'] = $group_name;
            $billInfo['shop_name']  = $shop_info['shop_name'];
            $billInfo['shop_zg']  = $shop_info['shop_zg'];
            $this->assign('billInfo',$billInfo);
            $this->assign('id',$id);
            $this->display();
        }

    }

    public function addBill(){
        if(IS_POST){
            $data = i('post.');
            if(empty($data['gid'])){
                $this->error('请选择分组');
            }
            if(empty($data['uid'])){
                $this->error('请选择用户');
            }
            if(empty($data['shop_id'])){
                $this->error('请选择店铺');
            }
            if(empty($data['shua_price']) || !is_numeric($data['shua_price'])){
                $this->error('请填写金额,并且是数字');
            }
            $data['pic']    = "/Public/no_photo.png";
            $data['kind']   = 2;
            $data['c_date'] = time();
            $data['state']  = 3;
            $data['is_check'] = 1;
            $data['cat_id1'] = 0;
            $data['cat_id2'] = 0;
            $data['cm_id'] = 0;
            M('bill')->add($data);
            $this->success('账单添加成功');
        }else{
            $user_group = M('user_group')->where('is_delete=0')->select();
            $this->assign('user_group',$user_group);
            $this->display();
        }
    }

    public function ajaxUser(){
        $gid = i('gid');
        if(empty($gid)){
            $this->error('系统错误,参数有误');
        }
        $users = M('user')->where("gid={$gid}")->select();
        $shops = M('user_shop')->where("gid={$gid}")->select();
        $data['users'] = $users;
        $data['shops'] = $shops;
        $this->success($data);
    }
}
