<?php

namespace Admin\Controller;


class BaohuoController extends AdminController
{
    protected function _initialize(){
        parent::_initialize();
    }
    public function baohuo(){
        $order_state = intval(i('order_state'));
        $where = array();

        if($order_state != -4){
            $where['order_state'] = $order_state;
        }

        $baohuoM = M('baohuo');
        $count = $baohuoM->where($where)->count();
        $p     = new \Think\Page($count,4);
        $page  = $p->show();
        $data  = $baohuoM->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();

        $user = M('user');
        $cmM= M('cm_size');
        $usegM = M('user_group');
        $changM = M('changjia');
        foreach ($data as &$item){
            $item['user_name'] = $user->where("id={$item['uid']}")->getField('user_name');
            $item['cm_name'] = $cmM->where("id={$item['cm_id']}")->getField('cm_name');
            $item['group_name'] = $usegM->where("id={$item['gid']}")->getField('group_name');
            $item['chang_name'] = $changM->where("id={$item['chang_id']}")->getField('cname');
        }
        $this->assign('order_state',$order_state);
        $this->assign('page',$page);
        $this->assign('data',$data);
        $this->display();
    }
    public function showBaohuo(){
        $id = i('id');
        if(empty($id)){
            $this->error('参数有误!');
        }
        $info = M('baohuo')->find($id);
        $info['cm_name']   = M('cm_size')->where("id={$info['cm_id']}")->getField('cm_name');
        $shopData = M('user_shop')->where("id={$info['shop_id']}")->find();
        $addressData = M('address')->where("id={$info['address_id']}")->find();
        $info['shop_name'] = $shopData['shop_name'];
        $info['shop_zg']   = $shopData['shop_zg'];
        $info['send_name']      = $addressData['send_name'];
        $info['send_mobile']    = $addressData['send_mobile'];
        $info['send_address']   = $addressData['send_address'];
        $info['shop_zg']   = $shopData['shop_zg'];
        $userData  = M('user')->where("id={$info['uid']}")->find();
        $info['chang_name']  = M('changjia')->where("id={$info['chang_id']}")->getField('cname');
        $info['is_jie_group']  = M('user_group')->where("id={$info['is_jie']}")->getField('group_name');
        $info['user_name']   = $userData['user_name'];
        $info['user_mobile'] = $userData['mobile'];

//        ALTER TABLE `baohuo` ADD `send_order_code` VARCHAR(18) NULL DEFAULT '' COMMENT '发货单号对应物流代号' AFTER `chang_order`;
//        http://m.kuaidi100.com/index_all.html?type=yuantong&postid=V00191980886#result
        $info['yu_order_url'] = "http://m.kuaidi100.com/index_all.html?type={$info['yu_order_code']}&postid={$info['yu_order']}#result";
        $info['send_order_url'] = "http://m.kuaidi100.com/index_all.html?type={$info['send_order_code']}&postid={$info['send_order']}#result";
        $info['chang_order_url'] = "http://m.kuaidi100.com/index_all.html?type={$info['chang_order_code']}&postid={$info['chang_order']}#result";
        if(!empty($info['yu_order_code'])){
            $info['yu_order_name']    =  M('wuliu')->where("code='{$info['yu_order_code']}'")->getField('name');
        }
        if(!empty($info['send_order_code'])){
            $info['send_order_name']    =  M('wuliu')->where("code='{$info['send_order_code']}'")->getField('name');
        }
        if(!empty($info['chang_order_code'])){
            $info['chang_order_name'] =  M('wuliu')->where("code='{$info['chang_order_code']}'")->getField('name');
        }
        $wuliuData = M('wuliu')->order('sort asc')->select();
        $changData = M('changjia')->where("is_delete=0")->select();
        $this->assign('info',$info);
        $this->assign('wuliuData',$wuliuData);
        $this->assign('changData',$changData);
        $this->display();
    }


    public function closeBaohuo(){
        $id = i('id');
        if(empty($id)){
            $this->error('参数有误!');
        }
        $info = M('baohuo')->find($id);
        if($info['order_state'] != 0){
            $this->error('订单需申请中才能关闭!');
        }
        M('baohuo')->where("id={$id}")->save(array('order_state'=>-1));
        $this->success('订单已经关闭');
    }

    public function sureOrder(){
        $data = i('post.');
        $id = $data['id'];
        if(empty($id)){
            $this->error('参数有误!');
        }
        unset($data['id']);
        M('baohuo')->where("id={$id}")->save($data);
        $this->success('预约单号已经确认');
    }
    public function sureSend(){
        $data = i('post.');
        $id = $data['id'];
        if(empty($id)){
            $this->error('参数有误!');
        }
        unset($data['id']);
        //获取报货信息
        $baoData   = M('baohuo')->find($id);
        $userMoney = M('user_group')->where("id={$baoData['gid']}")->getField('money');
        $kouMoney  = $baoData['jin_price']*$baoData['num']+$baoData['da_price'];
        if($kouMoney > $userMoney){
            $this->error('账户资金不足以抵扣');
        }
        //只有是库存 或者 调货完毕才能进行发货
        if($baoData['diaohuo'] == 2 || $baoData['diaohuo'] == 3){
            $this->error('该订单目前不能进行发货');
        }

        // 用货源id  和尺码id
        //判断库存是否够 发货
        $where = array();
        $where['huo_id'] = $baoData['huo_id'];
        $where['cm_id']  = $baoData['cm_id'];
        $where['uid']    = $baoData['uid'];
        $kuData = M('kucun')->where($where)->find();
        if($baoData['num'] > $kuData['num']){
            $this->error("当前库存剩余{$kuData['num']}个");
        }

        //扣除资金
        $shen_Money = $userMoney-$kouMoney;
        M("user_group")->where("gid={$baoData['gid']}")->save(array('money'=>$shen_Money));
        //记录账单
        $bill_data = array();
        $bill_data['bao_id'] = $baoData['id'];
        $bill_data['shop_id'] = $baoData['shop_id'];
        $bill_data['uid']     = $baoData['uid'];
        $bill_data['gid']     = $baoData['gid'];
        $bill_data['pic']     = $baoData['pic'];
        $bill_data['cat_id1']     = $baoData['cat_id1'];
        $bill_data['cat_id2']     = $baoData['cat_id2'];
        $bill_data['cm_id']     = $baoData['cm_id'];
        $bill_data['jin_price']     = $baoData['jin_price']*$baoData['num'];
        $bill_data['da_price']     = $baoData['da_price'];
        $bill_data['mai_price']     = $baoData['mai_price'];
        $bill_data['shen_price']     = $shen_Money;
        $bill_data['c_date']     = time();
        $bill_data['state']     = 2;  //发货
        $bill_data['info']     = '发货扣除金额'.$kouMoney;
        M('bill')->add($bill_data);

        //更新报货状态
        $data['order_state'] = 3;
        $data['s_date'] = time();
        M('baohuo')->where("id={$id}")->save($data);

        //减掉库存
        $shen_ku = $kuData['num']-$baoData['num'];
        M('kucun')->where("id={$kuData['id']}")->where(array('num'=>$shen_ku));

        $this->success('已经确认发货');

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
