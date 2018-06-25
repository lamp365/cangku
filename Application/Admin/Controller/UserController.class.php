<?php

namespace Admin\Controller;


class UserController extends AdminController
{
    protected function _initialize(){
        parent::_initialize();
    }
    public function index(){

        $this->display();
    }

    public function loadUser(){
        $value = i('search');
        $offset = i("offset");
        $limit = i("limit");
        $where = [];
        $search_value = i('search');
        if(!empty($search_value)){
            $where["user_name|mobile"] = array("like","%".$search_value."%");
        }
        $count = M('user') -> where($where) -> order('id desc') -> count();
        $res = M('user') -> where($where) -> limit($offset,$limit) -> order('id desc') -> select();

        foreach ($res as &$v) {
            $v['c_date'] = date('Y-m-d H:i:s' ,$v['c_date']);
            $v['gid'] = M('user_group') -> where(['id' => $v['gid']]) ->getField('group_name');
            if($v['is_frobid'] == 0 ){
                $v['is_frobid'] = '正常';
            } else if ($v['is_frobid'] == 1) {
                $v['is_frobid'] = '禁用';
            } else {
                $v['is_frobid'] = '拉黑';
            }
            $v['level'] = $v['level'] == 1 ? '组长' : '组员';

        }

        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);
    }
    //新增员工信息
    public function addUser(){
        if(IS_POST){
            $data = i('post.');

            $data['c_date'] = time();
            if(empty($data['mobile']) || empty($data['user_name'])){
                $this->error('用户名和手机不能为空');
            }
            if(empty($data['gid'])){
                $this->error('请选择用户属组');
            }
            $res = M('user') -> where(array('mobile' =>$data['mobile'])) -> find();
            if($res){
                $this->error('该手机已有人使用！');
            }
            //添加成功要给初始化密码
            $data['password'] = md5(md5('123456'));
            //dump($data);
            //exit;
            $result = M('user') -> add($data);
            if($result){
                $this -> success('添加成功！该会员的初始密码为123456');
            }else{
                $this -> error('添加失败！');
            }

        }else{
            //遍历\会员组
            $where['is_delete'] = 0;
            $res =M('user_group') -> where($where) -> order('id desc') -> getField('id,group_name');
            $this->assign('group',$res);
            $this-> display('editUser');
        }
    }

    //修改会员信息

    public function editUser(){

        $id = i('id');
        $where['id'] = $id;
        if(IS_POST){
            $data = i('post.');

            if(empty($data['mobile']) || empty($data['user_name'])){
                $this->error('用户名和手机不能为空');
            }
            if(empty($data['gid'])){
                $this->error('请选择用户属组');
            }
            M('user') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $res = M('user') -> where($where)-> find();

            $g =M('user_group') -> order('id desc') -> getField('id,group_name');
            $this->assign('group',$g);

            $this->assign('info',$res);
            $this-> display('editUser');
        }
    }

    //删除会员

    public function delUser(){
        $array_id['id'] = array('in',$_POST['ids']);
        M('user') -> where($array_id) -> delete();
        $this -> success('删除成功！');
    }

    //重置密码
    public function editPWD(){

        if(IS_POST){
            $data = i('post.');
            $id = i('id');
            if($data['pass'] != $data['repass']){
                $this -> error('两次密码不一致！');
                exit;
            }

            $sa["password"] = md5(md5($data['pass']));
            M('user') -> where(array('id'=>$id)) -> save($sa);
            $this -> success('重置成功！');

        }else{
            $this -> display();
        }
    }

    //设为组长

    public function zuzhang(){
        $id    = i('id');
        $umode = M('user');
        //组下其他成员取消组长,该用户设置为组长
        $user  = $umode->find($id);
        $gid   = $user['gid'];
        $alluser = $umode->where(array('gid'=>$gid))->select();
        foreach ($alluser as $item){
            if($item['level'] == 1){
                //取消组长
                $where = array('id'=>$item['id']);
                $umode->where($where)->save(array('level'=>0));
            }
        }
        $umode->where(array('id'=>$id))->save(array('level'=>1));
        $this -> success('设置成功');
    }
    //查看用户发货地址
    public function address(){
        $id    = i('id');
        $alladdress = M('address')->where(array('uid'=>$id))->select();
        $this->assign('alladdress',$alladdress);
        $this -> display();
    }

    //会员店铺
    public function shop(){
        $this->display();
    }
    //加载会员店铺
    public function loadShop(){
        $offset = i("offset");
        $limit = i("limit");
        $where['is_delete'] = 0;

        $search_value = i('search');
        if(!empty($search_value)){
            $where["shop_name"] = array("like","%".$search_value."%");
        }
        $count = M('user_shop') -> where($where) -> count();
        $res = M('user_shop') -> where($where) -> limit($offset,$limit) -> select();
        foreach ($res as &$v) {
            $v['c_date'] = date('Y-m-d H:i:s' ,$v['c_date']);
            $v['group_name'] = M('user_group') -> where(['id' => $v['gid']]) ->getField('group_name');
            $v['user_name'] = M('user') -> where(['id' => $v['gid']]) ->getField('user_name');
            $v['is_forbid'] = $v['is_forbid'] == 0 ? '正常' : '禁用';

        }

        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);

    }

    //新增会员店铺
    public function addShop(){
        if(IS_POST){
            $data = i('post.');

            $data['c_date'] = time();

            $res = M('user_shop') -> where(array('shop_name' =>$data['shop_name'])) -> find();

            if($res){
                $this->error('该店铺名称已经存在，请输入新的店铺名称！');
            }

            $user_id = M('user') -> where(array('user_name' =>$data['user_name'])) -> getField('id');
            $g_id = M('user') -> where(array('user_name' =>$data['user_name'])) -> getField('gid');

            if(!$user_id){
                $this->error('该会员不存在');
            }

            $data['uid'] = $user_id;
            $data['gid'] = $g_id;
            unset($data['user_name']);
            $result = M('user_shop') -> add($data);
            if($result){
                $this -> success('添加成功');
            }else{
                $this -> error('添加失败！');
            }

        }else{

            $this-> display('editshop');
        }
    }

    //修改会员店铺
    public function editShop(){

        $id = i('id');
        $where['id'] = $id;
        if(IS_POST){
            $data = i('post.');

            $user_id = M('user') -> where(array('user_name' =>$data['user_name'])) -> getField('id');
            $g_id = M('user') -> where(array('user_name' =>$data['user_name'])) -> getField('gid');
            unset($data['user_name']);
            $data['uid'] = $user_id;
            $data['gid'] = $g_id;

            M('user_shop') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $res = M('user_shop') -> where($where)-> find();
            $res['user_name'] = M('user') -> where(array('id' => $res['uid'])) -> getField('user_name');

            $this->assign('info',$res);
            $this-> display('editshop');
        }

    }

    //删除会员店铺
//删除用户组
    public function delShop(){
        $array_id['id'] = array('in',$_POST['ids']);
        $data['is_delete'] = 1;
        M('user_shop') -> where($array_id) -> save($data);
        $this -> success('删除成功！');
    }

    //黑名单管理
    public function black(){

        $this->display();
    }

    //加载黑名单用户
    public function loadBlack(){
        $offset = i("offset");
        $limit = i("limit");
        $where['is_delete'] = 0;

        $search_value = i('search');
        if(!empty($search_value)){
            $where["d_mobile|d_name|d_name"] = array("like","%".$search_value."%");
        }
        $count = M('dangeruser') -> where($where) -> count();
        $res = M('dangeruser') -> where($where) -> limit($offset,$limit) -> select();
        foreach ($res as &$v) {
            $v['c_date'] = date('Y-m-d H:i:s', $v['c_date']);
            $v['uid'] =  M('user') -> where(array('id' =>$v['uid'])) -> getField('user_name');;
        }

        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);
    }

    //移除拉黑会员
    public function delBlack(){
        $array_id['id'] = array('in',$_POST['ids']);

        $result = M('dangeruser') -> where($array_id) -> find();
        $res = M('dangeruser') -> where($array_id) -> delete();
        if($res){
            foreach($result as $v){
                unlink(ROOT_PATH.$v['pic']);
            }
        }
        $this -> success('移除成功！');
    }


    //权限管理 用户组管理
    public function userGroup(){
        $this->display();
    }
    //加载用户组
    public function loadUserGroup(){
        $offset = i("offset");
        $limit = i("limit");
        $where['is_delete'] = 0;

        $search_value = i('search');
        if(!empty($search_value)){
            $where["group_name"] = array("like","%".$search_value."%");
        }
        $count = M('user_group') -> where($where) -> count();
        $res = M('user_group') -> where($where) -> limit($offset,$limit) -> select();
        foreach ($res as &$v) {
            $v['c_date'] = date('Y-m-d H:i:s', $v['c_date']);
        }

        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);

    }
    //添加用户组
    public function addGroup(){

        if(IS_POST){
            $data = i('post.');
            $data['c_date'] = time();
            $data['money']  = intval($data['money']);
            if(empty($data['group_name'])){
                $this->error('属组名不能为空!');
            }
            M('user_group') -> add($data);
            $this->success('增加成功！');
        }else{
            $res['group_name']  = '';
            $res['money']       = 0;
            $this->assign('info',$res);
            $this->display('editGroup');
        }

    }
    //修改用户组
    public function editGroup(){
        $id = i('id');
        $where['id'] = $id;
        if(IS_POST){
            $data           = i('post.');
            if(empty($data['group_name'])){
                $this->error('属组名不能为空!');
            }
            $data['money']  = intval($data['money']);
            M('user_group') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $res = M('user_group') -> where($where)-> find();
            $this->assign('info',$res);
            $this-> display();
        }
    }
    //删除用户组
    public function delGroup(){
        $ids   = $_POST['ids'];
        $user  = M('user');
        $group = M('user_group');
        foreach ($ids as $id){
            //查询以下是否有用户 没有则可删除
            $uwhere['gid'] = $id;
            $data = $user->where($uwhere)->find();
            if(empty($data)){
                $where['id'] = $id;
                $group->where($where)->delete();
            }
        }
        $this -> success('删除成功！');
    }


}
