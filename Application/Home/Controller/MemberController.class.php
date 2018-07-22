<?php
namespace Home\Controller;


class MemberController extends CommonController {
    public function _initialize(){
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    //小组成员
    public function index(){
        $session_user = session("web_user");
        $gid = $session_user['gid'];
        $where['gid'] = $gid;
        $info = M('user') -> where($where)->order('level desc')->select();

        $this->assign('info', $info);
        $this->assign('session_user', $session_user);
        $this->display();
	}

	public function addMember(){
        $session_user = session("web_user");
        if($session_user['level'] == 0){
            $this->error('只有组长能操作!');
        }
        if(IS_POST){
            $data = i('post.');
            if(strlen($data['mobile']) != 11 || !is_numeric($data['mobile'])){
                $this->error('手机号有误');
            }
            if(empty($data['user_name'])){
                $this->error('用户名不能为空');
            }
            if(empty($data['password'])){
                $this->error('密码不能为空');
            }
            $wh['mobile'] = $data['mobile'];
            $res = M('user')->where($wh)->find();
            if($res){
                $this->error('手机号已经存在!');
            }
            $data['gid']    = $session_user['gid'];
            $data['c_date'] = time();
            $data['password'] = md5(md5($data['password']));
            M('user')->add($data);
            $this->success("用户添加成功");
        }else{
            $this->display();
        }
    }

    public function forbid(){
        $id = i('id');
        $is_forbid = i('is_forbid');
        M('user')->where("id={$id}")->save(array('is_forbid'=>$is_forbid));
        $this->success('操作成功!');
    }
    public function address(){
        $uid = getUidFromSession();
        $data = M("address")->where("uid={$uid}")->order('is_default desc,id desc')->select();
        $this->assign('data',$data);
        $this->display();
    }

    public function setDefault(){
        $id = i('id');
        $is_default = i('is_default');
        M('address')->where("id={$id}")->save(array('is_default'=>$is_default));
        $this->success('操作成功!');
    }

    public function addAddress(){
        $id = intval(i('id'));
        $info = M('address')->find($id);
        $this->assign('info',$info);
        if(IS_POST){
            $data = i('post.');
            unset($data['id']);
            if(empty($data['send_name']))  $this->error('发货人不能为空');
            if(empty($data['send_mobile']))  $this->error('发货人手机不能为空');
            if(empty($data['send_address']))  $this->error('发货人地址不能为空');
            if(!empty($id)){
                M('address')->where("id={$id}")->save($data);
            }else{
                $data['c_date'] = time();
                $data['uid'] = getUidFromSession();
                M('address')->add($data);
            }
            $this->success('操作成功!');
        }else{
            $this->display();
        }
    }

    public function ceshi(){
        $this->display('center/ceshi');
    }

}