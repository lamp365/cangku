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
        $info['back_order_url'] = "http://m.kuaidi100.com/index_all.html?type={$info['back_order_code']}&postid={$info['back_order']}#result";
        if(!empty($info['yu_order_code'])){
            $info['yu_order_name']    =  M('wuliu')->where("code='{$info['yu_order_code']}'")->getField('name');
        }
        if(!empty($info['send_order_code'])){
            $info['send_order_name']    =  M('wuliu')->where("code='{$info['send_order_code']}'")->getField('name');
        }
        if(!empty($info['chang_order_code'])){
            $info['chang_order_name'] =  M('wuliu')->where("code='{$info['chang_order_code']}'")->getField('name');
        }
        if(!empty($info['back_order_code'])){
            $info['back_order_name'] =  M('wuliu')->where("code='{$info['back_order_code']}'")->getField('name');
        }
        $wuliuData = M('wuliu')->order('sort asc')->select();
        $changData = M('changjia')->where("is_delete=0")->select();
        $userGData = M('user_group')->where("is_delete=0")->select();
        $this->assign('info',$info);
        $this->assign('userGData',$userGData);
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

    //确认预约
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
        if(empty($data['send_order'])){
            $this->error('发货单号不能为空!');
        }
        unset($data['id']);

        //获取报货信息
        $baoData   = M('baohuo')->find($id);
        $userMoney = M('user_group')->where("id={$baoData['gid']}")->getField('money');
        $kouMoney  = $baoData['da_price'];

        if($baoData['order_state'] == 0){
            $this->error('请先更新订单状态为处理中');
        }
        //只有是库存 或者 调货完毕才能进行发货
        if($baoData['diaohuo'] == 2 || $baoData['diaohuo'] == 3){
            $this->error('该订单目前不能进行发货');
        }

        if($kouMoney > $userMoney){
            $this->error("账户资金{$userMoney}不足以抵扣");
        }


        // 用货源id  和尺码id
        //判断库存是否够 发货
        $where = array();
        $where['huo_id'] = $baoData['huo_id'];
        $where['cm_id']  = $baoData['cm_id'];
        $where['uid']    = $baoData['uid'];
        $kuData = M('kucun')->where($where)->find();
        $kuData_num = intval($kuData['num']);
        if($baoData['num'] > $kuData_num){
            $this->error("当前库存剩余{$kuData_num}个");
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
        $bill_data['jin_price']     = 0;
        $bill_data['da_price']     = $baoData['da_price'];
        $bill_data['mai_price']     = $baoData['mai_price'];
        $bill_data['shen_price']     = $shen_Money;
        $bill_data['c_date']     = time();
        $bill_data['state']     = 2;  //发货
        $bill_data['info']     = '发货扣除打包金额'.$kouMoney;
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

    public function suerAfter(){
        $id = i('id');
        $after_state = i('after_state');
        //把发货账单取出  更新发货账单为 0  并备注 退货或者换货完毕
        $where['bao_id'] = $id;
        $where['state']  = 2;
        if($after_state == -1){
            $info = '换货完毕';
        }else{
            $info = '退货完毕';
        }
        $billData = M('bill')->where($where)->find();
        $info = $billData['info']."--".$info;
        $saveData = array(
            'info'      => $info,
            'mai_price' => 0,
            'x_date'    =>time()
        );
        $res = M('bill')->where("id={$billData['id']}")->save($saveData);
        if($res){
            M('baohuo')->where("id={$id}")->save(array('after_state'=>$after_state));
            $this->success('操作成功!');
        }else{
            $this->error('系统开小差了!');
        }

    }

    //立即处理订单
    public function sureCheckOrder(){
        $data = i('post.');
        if(empty($data['jin_price']) || !is_numeric($data['jin_price'])){
            $this->error('金额有误!');
        }
        if(empty($data['da_price']) || !is_numeric($data['da_price'])){
            $this->error('金额有误!');
        }
        if(empty($data['num']) || !is_numeric($data['num'])){
            $this->error('数量有误!');
        }
        $id = $data['id'];
        unset($data['id']);

        //判断如果是调货 资金够扣除么  扣除调货资金
        $baoData  = M('baohuo')->find($id);
        if($data['diaohuo'] == 2){
            //验证之前账单 记录过  不用重复记录
            $bill_data = array();
            $bill_data['bao_id']  = $baoData['id'];
            $bill_data['uid']     = $baoData['uid'];
            $bill_data['state']   = 1;  //调货
            $bill_res = M('bill')->where($bill_data)->find();
            if(!$bill_res){
                $usergData = M('user_group')->find($baoData['gid']);
                $kouMoney  = $baoData['jin_price']*$baoData['num'];
                if($kouMoney>$usergData['money']){
                    $this->error("当前账户余额{$usergData['money']}不够调货");
                }
                //扣除用户资金
                $shen_Money = $usergData['money']-$kouMoney;
                M("user_group")->where("gid={$baoData['gid']}")->save(array('money'=>$shen_Money));
                //记录账单
                $bill_data['shop_id'] = $baoData['shop_id'];
                $bill_data['gid']     = $baoData['gid'];
                $bill_data['pic']     = $baoData['pic'];
                $bill_data['cat_id1']     = $baoData['cat_id1'];
                $bill_data['cat_id2']     = $baoData['cat_id2'];
                $bill_data['cm_id']     = $baoData['cm_id'];
                $bill_data['jin_price']     = $baoData['jin_price']*$baoData['num'];
                $bill_data['da_price']      = 0;
                $bill_data['mai_price']     = 0;
                $bill_data['shen_price']    = $shen_Money;
                $bill_data['c_date']     = time();
                $bill_data['info']     = '调货扣除金额'.$kouMoney;
                M('bill')->add($bill_data);
            }
        }
        //如果是缺货
        if($data['diaohuo'] == 3){
            //扣除的账单 还回来
            $bill_data = array();
            $bill_data['bao_id']  = $baoData['id'];
            $bill_data['uid']     = $baoData['uid'];
            $bill_data['state']   = 1;  //调货
            $bill_res = M('bill')->where($bill_data)->find();
            if($bill_res){
                $usergData = M('user_group')->find($baoData['gid']);
                //还回用户资金
                $huan_Money = $usergData['money']+$bill_res['jin_price'];
                M("user_group")->where("gid={$baoData['gid']}")->save(array('money'=>$huan_Money));
                //账单删除
                M('bill')->where("id={$bill_res['id']}")->delete();
            }
        }
        //调货完毕
        if($data['diaohuo'] == 4){
            if($baoData['diaohuo'] == 1){
                $this->error('操作不规范,不允许操作');
            }
            $data['f_date'] = time();
            //库存加加
            // 用货源id  和尺码id
            //判断库存是否够 发货
            $where = array();
            $where['huo_id'] = $baoData['huo_id'];
            $where['cm_id']  = $baoData['cm_id'];
            $where['uid']    = $baoData['uid'];
            $kuData = M('kucun')->where($where)->find();
            if(empty($kuData)){
                $add_kucun = array();
                $add_kucun['huo_id'] = $baoData['huo_id'];
                $add_kucun['uid']    = $baoData['uid'];
                $add_kucun['gid']    = $baoData['gid'];
                $add_kucun['pic']    = $baoData['pic'];
                $add_kucun['cat_id1'] = $baoData['cat_id1'];
                $add_kucun['cat_id2'] = $baoData['cat_id2'];
                $add_kucun['cm_id']   = $baoData['cm_id'];
                $add_kucun['num']     = $data['num'];
                $add_kucun['jin_price']= $data['jin_price'];
                $add_kucun['da_price']= $data['da_price'];
                $add_kucun['c_date']  = time();
                M('kucun')->add($add_kucun);
            }else{
                //加加
                $new_ku = $kuData['num']+$baoData['num'];
                M('kucun')->where("id={$kuData['id']}")->save(array('num'=>$new_ku,'x_date'=>time()));
            }
        }

        $res = M('baohuo')->where("id={$id}")->save($data);
        if(!$res){
            $this->error('系统开小差了!');
        }
        $this->success('操作成功!');
    }

    public function afterSale(){

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

    public function addBack1(){
        $user_group = M('user_group')->where('is_delete=0')->select();
        $this->assign('user_group',$user_group);
        $this->display();
    }
    public function addBack2(){
        $uid = i('uid');
        $gid = i('gid');
        $cat_id1 = i('cat_id1');
        $cat_id2 = i('cat_id2');
        if(empty($uid) || empty($gid)){
            $this->error('参数有误!');
        }
        $user_name = M('user')->where("id={$uid}")->getField('user_name');
        $group_name = M('user_group')->where("id={$gid}")->getField('group_name');
        //找出该组库存
        $where = array();
        $where['gid'] = $gid;
        $where['uid'] = $uid;
        if(!empty($cat_id1)) $where['cat_id1'] = $cat_id1;
        if(!empty($cat_id2)) $where['cat_id2'] = $cat_id2;

        $sonCate = array();
        if(!empty($cat_id1)){
            $sonCate =  M('category')->where("pid={$cat_id1}")->select();
        }

        $count = M('kucun') ->where($where)->count();
        $p     = new \Think\Page($count,30);
        $page  = $p->show();
        $data  = M('kucun') ->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
        if(!empty($data)){
            foreach ($data as &$item){
                $item['h_name'] = M('huoyuan')->where("id={$item['huo_id']}")->getField('h_name');
            }
        }
        $cate = M('category') -> where(array('is_delete' => 0, 'pid' => 0)) -> select();
        $this->assign('cate', $cate);
        $this->assign('sonCate', $sonCate);
        $this->assign('data',$data);
        $this->assign('user_name',$user_name);
        $this->assign('group_name',$group_name);
        $this->assign('uid',$uid);
        $this->assign('gid',$gid);
        $this->assign('cat_id1',$cat_id1);
        $this->assign('cat_id2',$cat_id2);
        $this->assign('page',$page);
        $this->display();
    }

    public function addBack3(){
        $ku_id = i('ku_id');
        if(empty($ku_id)){
            $this->error('参数有误!');
        }
        $ku_data  = M('kucun')->find($ku_id);
        $huo_data = M('huoyuan')->find($ku_data['huoyuan']);
        $user_name = M('user')->where("id={$ku_data['uid']}")->getField('user_name');
        $group_name = M('user_group')->where("id={$ku_data['gid']}")->getField('group_name');
        $changjia = M('changjia')->where("is_delete = 0")->select();
        $this->assign('ku_data',$ku_data);
        $this->assign('huo_data',$huo_data);
        $this->assign('changjia',$changjia);
        $this->assign('user_name',$user_name);
        $this->assign('group_name',$group_name);
        $this->display();
    }

    public function suerAddBack(){
        $data = i('post.');
        $ku_data = M('kucun')->find($data['ku_id']);
        $ku_id = $data['ku_id'];
        unset($data['ku_id']);
        if($data['state'] == 1 && empty($data['price'])){
            $this->error('退回单价不能为空!');
        }
        $data['cat_id1'] = $ku_data['cat_id1'];
        $data['cat_id2'] = $ku_data['cat_id2'];
        $data['pic'] = $ku_data['pic'];
        $data['uid'] = $ku_data['uid'];
        $data['gid'] = $ku_data['gid'];
        $data['c_date'] = time();
        $res = M('backchanjia')->add($data);
        //库存减去
        if($res){
            $new_num = $ku_data['num']-$data['num'];
            M('kucun')->where("id={$ku_id}")->save(array('num'=>$new_num));
            //如果是现金退回  钱退到账户上
            $group_money = M('user_group')->where("id={$ku_data['gid']}")->getField('money');
            $back_money = $data['price']*$data['num'];
            $r_data = array();
            $r_data['uid'] = $ku_data['uid'];
            $r_data['gid'] = $ku_data['gid'];
            $r_data['chon_price'] = $back_money;
            $r_data['shen_price'] = $back_money+$group_money;
            $r_data['pic'] = "/Public/no_photo.png";
            $r_data['c_date'] = time();
            $r_data['state'] = 0;
            $r_data['info']  = "库存退回厂家,收取退回现金";
            M('recharge')->add($r_data);
            M('user_group')->where("id={$ku_data['gid']}")->save(array('money'=>$r_data['shen_price']));
        }else{
            $this->error('系统开小差了');
        }
    }

    public function delChan(){
        $id = intval(I('id'));
        M('backchanjia')->where("id={$id}")->save(array('is_delete'=>1));
        $this->success('记录已经删除成功!');
    }

    public function getCate(){
        $cat_id = i('post.cat_id1');
        $cate = M('category')->where(array('pid'=>$cat_id))->select();
        $this->ajaxReturn($cate);
    }

    public function addKucun(){
        $user_group = M('user_group')->where('is_delete=0')->select();
        //查询出顶级分类
        $cate = M('category') -> where(array('is_delete' => 0, 'pid' => 0)) -> select();
        $this->assign('cate', $cate);
        $this->assign('user_group',$user_group);
        $this->display();
    }

    public function addStep2(){
        $cat_id1 = i('cat_id1');
        $cat_id2 = i('cat_id2');
        $uid     = i('uid');
        $gid     = i('gid');
        if(empty($cat_id1) || empty($cat_id2) || empty($uid) || empty($gid)){
            $this->error('参数有误');
        }
        $user_name  = M('user')->where("id={$uid}")->getField('user_name');
        $group_name = M('user_group')->where("id={$gid}")->getField('group_name');

        $where['cat_id1'] = $cat_id1;
        $where['cat_id2'] = $cat_id2;
        $count = M('huoyuan') ->where($where)->count();
        $p     = new \Think\Page($count,30);
        $page  = $p->show();
        $info  = M('huoyuan') ->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();

        $this->assign('cat_id1', $cat_id1);
        $this->assign('cat_id2', $cat_id2);
        $this->assign('page', $page);
        $this->assign('info', $info);
        $this->assign('uid', $uid);
        $this->assign('gid', $gid);
        $this->assign('user_name', $user_name);
        $this->assign('group_name', $group_name);

        $this->display();
    }

    public function addStep3(){
        $huo_id = i('huo_id');
        $uid = i('uid');
        $gid = i('gid');
        $huo_id = i('huo_id');
        $id     = intval(i('id'));
        if(empty($huo_id)){
            $this->error('参数有误!');
        }

        $cmM      = M('cm_size');
        $cuData   = M('kucun')->find($id);
        $cm_size  = $cmM->where(array('is_delete' => 0, 'pid' => 0))->select();
        $data = M('huoyuan')->find($huo_id);

        //获取尺码
        $parentSize   = array();
        $sonSize   = array();
        if(!empty($cuData)){

            //当前尺码pid
            $selfPid    = $cmM->where("id={$cuData['cm_id']}")->getField('pid');
            $parentSize = $cmM->where("id={$selfPid}")->find();
            $sonSize    = $cmM->where("pid={$selfPid}")->select();
            $uid = $cuData['uid'];
            $gid = $cuData['gid'];

        }else{
            $cuData['jin_price'] = $data['jin_price'];
            $cuData['da_price'] = $data['da_price'];
        }
        $user_name  = M('user')->where("id={$uid}")->getField('user_name');
        $group_name = M('user_group')->where("id={$gid}")->getField('group_name');
        $this->assign('data', $data);
        $this->assign('cm_size', $cm_size);
        $this->assign('parentSize', $parentSize);
        $this->assign('sonSize', $sonSize);
        $this->assign('gid', $gid);
        $this->assign('uid', $uid);
        $this->assign('cuData', $cuData);
        $this->assign('user_name', $user_name);
        $this->assign('group_name', $group_name);
        $this->display();
    }

    public function getSize(){
        $cm_id1 = i('cm_id1');
        $size = M('cm_size')->where(array('pid'=>$cm_id1))->select();
        $this->ajaxReturn($size);
    }

    public function sureAdd(){
        $data = i('post.');
        if(empty($data['cm_id'])){
            $this->error('请选择尺码');
        }
        if(empty($data['num']) || !is_numeric($data['num'])){
            $this->error('请设置好数量');
        }
        if(empty($data['jin_price']) || !is_numeric($data['jin_price'])){
            $this->error('请设置好进货价');
        }if(empty($data['da_price']) || !is_numeric($data['da_price'])){
            $this->error('请设置好打包价');
        }
        //找到该库存
        $ku_data = array();
        $ku_data['uid']   = $data['uid'];
        $ku_data['huo_id'] = $data['huo_id'];
        $ku_data['cm_id']  = $data['cm_id'];
        $res = M('kucun')->where($ku_data)->find();
        if($res){
            $data['num'] = $res['num']+$data['num'];
            $data['x_date'] = time();
            M('kucun')->where("id={$res['id']}")->save($data);
        }else{
            $data['c_date'] = time();
            M('kucun')->add($data);
        }
        $this->success('库存添加成功');
    }
}
