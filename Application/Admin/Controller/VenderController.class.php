<?php

namespace Admin\Controller;


class VenderController extends AdminController
{
    protected function _initialize(){
        parent::_initialize();
    }
    //厂家管理
    public function index(){

        $this->display();
    }

    //加载厂家管理
    public function loadVender(){
        $value = i('search');
        $offset = i("offset");
        $limit = i("limit");
        $where['is_delete'] = 0;
        $search_value = i('search');
        if(!empty($search_value)){
            $where["ch_name"] = array("like","%".$search_value."%");
        }
        $count = M('changjia') -> where($where) -> order('id desc') -> count();
        $res = M('changjia') -> where($where) -> limit($offset,$limit) -> order('id desc') -> select();

        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);
    }

    //新增厂家信息
    public function addVender(){
        if(IS_POST){
            $data = i('post.');

            $result = M('changjia') -> add($data);
            if($result){
                //添加成功要给初始化密码
                $this -> success('添加成功！');
            }else{
                $this -> error('添加失败！');
            }

        }else{
            //遍历\会员组
            $this-> display('editvender');
        }
    }

    //修改会员信息

    public function editVender(){

        $id = i('id');
        $where['id'] = $id;
        if(IS_POST){
            $data = i('post.');
            M('changjia') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $res = M('changjia') -> where($where)-> find();

            $this->assign('info',$res);
            $this-> display('editvender');
        }
    }

    //删除厂家

    public function delVender(){
        $array_id['id'] = array('in',$_POST['ids']);
        $data['is_delete'] = 1;
        M('changjia') -> where($array_id) -> save($data);
        $this -> success('删除成功！');
    }

    //尺码管理
    public function sizes(){
        $this->display();
    }

    //加载尺码
    public function loadSizes(){
        $offset = i("offset");
        $limit = i("limit");
        $where['is_delete'] = 0;

        $search_value = i('search');
        if(!empty($search_value)){
            $where["cm_name"] = array("like","%".$search_value."%");
        }
        $count = M('cm_size') -> where($where) -> count();
        $res = M('cm_size') -> where($where) -> limit($offset,$limit) -> select();
        foreach ($res as &$v) {
           if (0 == $v['pid']) {
               $v['p_name'] = '父级尺码';
           } else {
               $v['p_name'] = M('cm_size') -> where(['id' => $v['pid']]) ->getField('cm_name');
           }

        }

        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);

    }

    //新增尺码
    public function addSizes(){
        if(IS_POST){
            $data = i('post.');

            $result = M('cm_size') -> add($data);
            if($result){
                $this -> success('添加成功');
            }else{
                $this -> error('添加失败！');
            }

        }else{
            $where['is_delete'] = 0;
            $where['pid'] = 0;
            $res =M('cm_size') -> where($where) -> order('id desc') -> getField('id,cm_name');
            $this->assign('group',$res);
            $this-> display('editsizes');
        }
    }

    //修改尺码
    public function editSizes(){

        $id = i('id');
        $where['id'] = $id;
        if(IS_POST){
            $data = i('post.');

            M('cm_size') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $res = M('cm_size') -> where($where)-> find();
            $p['is_delete'] = 0;
            $p['pid'] = 0;
            $result =M('cm_size') -> where($p) -> order('id desc') -> getField('id,cm_name');
            $this->assign('group',$result);

            $this->assign('info',$res);
            $this-> display('editsizes');
        }

    }

    //删除尺码
    public function delSizes(){
        $array_id['id'] = array('in',$_POST['ids']);
        $data['is_delete'] = 1;
        M('cm_size') -> where($array_id) -> save($data);
        $this -> success('删除成功！');
    }

    //分类管理
    public function cate(){

        $this->display();
    }

    //加载分类
    public function loadCate(){
        $offset = i("offset");
        $limit = i("limit");
        $where['is_delete'] = 0;

        $search_value = i('search');
        if(!empty($search_value)){
            $where["cat_name"] = array("like","%".$search_value."%");
        }
        $count = M('category') -> where($where) -> count();
        $res = M('category') -> where($where) -> limit($offset,$limit) -> select();
        foreach ($res as &$v) {
            if (0 == $v['pid']) {
                $v['p_name'] = '父级分类';
            } else {
                $v['p_name'] = M('category') -> where(['id' => $v['pid']]) ->getField('cat_name');
            }
        }

        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);
    }

    //新增分类
    public function addCate(){
        if(IS_POST){
            $data = i('post.');

            $result = M('category') -> add($data);
            if($result){
                $this -> success('添加成功');
            }else{
                $this -> error('添加失败！');
            }

        }else{
            $where['is_delete'] = 0;
            $where['pid'] = 0;
            $res =M('category') -> where($where) -> order('id desc') -> getField('id,cat_name');
            $this->assign('group',$res);
            $this-> display('editcate');
        }
    }

    //修改尺码
    public function editCate(){

        $id = i('id');
        $where['id'] = $id;
        if(IS_POST){
            $data = i('post.');

            M('category') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $res = M('category') -> where($where)-> find();
            $p['is_delete'] = 0;
            $p['pid'] = 0;
            $result = M('category') -> where($p) -> order('id desc') -> getField('id,cat_name');
            $this->assign('group',$result);

            $this->assign('info',$res);
            $this-> display('editcate');
        }

    }

    //删除分类
    public function delCate(){
        $array_id['id'] = array('in',$_POST['ids']);
        $data['is_delete'] = 1;
        M('category') -> where($array_id) -> save($data);
        $this -> success('删除成功！');
    }

    //货源管理
    public function huoyuan(){
        $this->display();
    }
    //加载货源管理
    public function loadHuoyuan(){
        $offset = i("offset");
        $limit = i("limit");
        $where['is_delete'] = 0;

        $search_value = i('search');
        if(!empty($search_value)){
            $where["h_name"] = array("like","%".$search_value."%");
        }
        $count = M('huoyuan') -> where($where) -> count();
        $res = M('huoyuan') -> where($where) -> limit($offset,$limit) -> select();
        foreach ($res as &$v) {

        }

        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);

    }
    //添加货源
    public function addHuoyuan(){

        if(IS_POST){
            $data = i('post.');
            $data['c_date'] = time();
            M('user_group') -> add($data);
            $this->success('增加成功！');
        }else{

            //查询出顶级分类
            $cate = M('category') -> where(array('is_delete' => 0, 'pid' => 0)) -> select();

            $this->assign('cate', $cate);
            $this->display('edithuoyuan');
        }

    }
    //修改用户组
    public function editGroup(){
        $id = i('id');
        $where['id'] = $id;
        if(IS_POST){
            $data = i('post.');
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
        $array_id['id'] = array('in',$_POST['ids']);
        $data['is_delete'] = 1;
        M('user_group') -> where($array_id) -> save($data);
        $this -> success('删除成功！');
    }


}
