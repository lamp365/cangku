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
            if(empty($data['ch_name']) || empty($data['cname'])){
                $this -> error('名字或者别名不能为空！');
            }
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
            if(empty($data['ch_name']) || empty($data['cname'])){
                $this -> error('名字或者别名不能为空！');
            }
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
        $data = catTree($res);
        foreach ($data as &$v) {
           if (0 == $v['pid']) {
               $v['p_name'] = '父级尺码';
           } else {
               $v['cm_name'] = "|".$v['html'].$v['cm_name'];
               $v['p_name']  = '二级尺码';
           }

        }

        $list_array= array("total"=>$count,"rows"=>$data?$data:array());
        echo json_encode($list_array);

    }

    //新增尺码
    public function addSizes(){
        if(IS_POST){
            $data = i('post.');
            if(empty($data['cm_name'])){
                $this -> error('尺码名字不为空！');
            }
            if(empty($data['pid'])){
                $data['pid'] = 0;
            }
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
            if(empty($data['cm_name'])){
                $this -> error('尺码名字不为空！');
            }
            //获取一次 看该尺码如果是父急,不能移动为二级
            $res = M('cm_size') -> where($where)-> find();
            if($res['pid'] == 0 && $data['pid'] != 0){
                $this -> error('一级不能为修改为二级！');
            }
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
        $ids = $_POST['ids'];
        foreach ($ids as $id){
            //如果是一级,把二级全部删除
            $res = M('category')->find($id);
            if($res['pid'] == 0){
                M('cm_size')->where(array('pid'=>$id))->save(array('is_delete'=>1));
            }
            M('cm_size')->where(array('id'=>$id))->save(array('is_delete'=>1));
        }
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
        $data = catTree($res);
        foreach ($data as &$v) {
            if (0 == $v['pid']) {
                $v['p_name'] = '父级分类';
            } else {
                $v['cat_name'] = "|".$v['html'].$v['cat_name'];
                $v['p_name']  = '二级分类';
            }
        }

        $list_array= array("total"=>$count,"rows"=>$data?$data:array());
        echo json_encode($list_array);
    }

    //新增分类
    public function addCate(){
        if(IS_POST){
            $data = i('post.');
            if(empty($data['cat_name'])){
                $this -> error('分类名字不为空！');
            }
            if(empty($data['pid'])){
                $data['pid'] = 0;
            }
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
            if(empty($data['cat_name'])){
                $this -> error('分类名字不为空！');
            }
            //获取一次 看该尺码如果是父急,不能移动为二级
            $res = M('category') -> where($where)-> find();
            if($res['pid'] == 0 && $data['pid'] != 0){
                $this -> error('一级不能为修改为二级！');
            }
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
        $ids = $_POST['ids'];
        foreach ($ids as $id){
            //如果是一级,把二级全部删除
            $res = M('category')->find($id);
            if($res['pid'] == 0){
                M('category')->where(array('pid'=>$id))->save(array('is_delete'=>1));
            }
            M('category')->where(array('id'=>$id))->save(array('is_delete'=>1));
        }
        $this -> success('删除成功！');
    }

    //货源管理
    public function huoyuan(){
        $cat_id1= intval(i("cat_id1"));
        $cat_id2= intval(i("cat_id2"));
        $sonCate = array();
        $allCate = M('category')->where("pid=0 and is_delete=0")->select();
        if(!empty($cat_id1)){
            $sonCate = M('category')->where("pid={$cat_id1} and is_delete=0")->select();
        }
        $this->assign('cat_id1',$cat_id1);
        $this->assign('cat_id2',$cat_id2);
        $this->assign('sonCate',$sonCate);
        $this->assign('allCate',$allCate);
        $this->display();
    }
    //加载货源管理
    public function loadHuoyuan(){
        $offset = i("offset");
        $limit  = i("limit");
        $cat_id2= intval(i("cat_id2"));
        !empty($cat_id1) && $where['cat_id1'] = $cat_id1;
        !empty($cat_id2) && $where['cat_id2'] = $cat_id2;
        $where['is_delete'] = 0;


        $search_value = i('search');
        if(!empty($search_value)){
            $where["h_name"] = array("like","%".$search_value."%");
        }
        $count = M('huoyuan') -> where($where) -> count();
        $res   = M('huoyuan') -> where($where) -> limit($offset,$limit) -> select();
        $catM  = M('category');
        foreach ($res as &$v) {
            $cat_name1 = $catM->where(array('id'=>$v['cat_id1']))->getField('cat_name');
            $cat_name2 = $catM->where(array('id'=>$v['cat_id2']))->getField('cat_name');
            $v['cate'] = $cat_name1.'-'.$cat_name2;
            $v['pic_html'] = '<img src="'.$v['pic'].'" class="the_pic"/>';
        }
        $list_array= array("total"=>$count,"rows"=>$res?$res:array());
        echo json_encode($list_array);

    }
    //添加货源
    public function addHuoyuan(){
        $id = i('id');
        $id = intval($id);
        if(IS_POST){
            $data = i('post.');
            $data['c_date'] = time();
            if(empty($data['h_name'])){
                $this->error('名称不能为空');
            }
            if(empty($data['jin_price']) || empty($data['da_price']) || !is_numeric($data['jin_price']) || !is_numeric($data['da_price'])){
                $this->error('进价和费用不为空,且为数字');
            }
            if(empty($data['cat_id1']) || empty($data['cat_id2'])){
                $this->error('请选择好分类');
            }
            if (empty($data['pic'])) {
                $this->error('请上传主图');
            }

            if($id!=0){
                M('huoyuan') ->where(array('id'=>$id))-> save($data);
                $this->success('修改成功！');
            }else{
                unset($data['id']);
                M('huoyuan') -> add($data);
                $this->success('增加成功！');
            }

        }else{
            $info = array();
            if($id != 0){
                $info = M('huoyuan')->find($id);
            }
            //查询出顶级分类
            $cate = M('category') -> where(array('is_delete' => 0, 'pid' => 0)) -> select();
            $this->assign('hide_id', $id);
            $this->assign('cate', $cate);
            $this->assign('info',$info);
            $this->display('edithuoyuan');
        }

    }
    public function getCate(){
        $cat_id = i('post.cat_id1');
        $cate = M('category')->where(array('pid'=>$cat_id))->select();
        $this->ajaxReturn($cate);
    }

    //删除用户组
    public function delGroup(){
        $array_id['id'] = array('in',$_POST['ids']);
        $data['is_delete'] = 1;
        M('user_group') -> where($array_id) -> save($data);
        $this -> success('删除成功！');
    }


}
