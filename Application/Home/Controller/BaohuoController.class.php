<?php
namespace Home\Controller;


class BaohuoController extends CommonController {
    public function _initialize(){
        parent::_initialize(); // TODO: Change the autogenerated stub
    }


    public function index(){

        $this->display();
	}
    public function addHuoyuan(){
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

            M('huoyuan') -> add($data);
            $this->success('增加成功！');

        }else{
            //查询出顶级分类
            $cate = M('category') -> where(array('is_delete' => 0, 'pid' => 0)) -> select();
            $this->assign('cate', $cate);
            $this->display();
        }
    }
    public function getCate(){
        $cat_id = i('post.cat_id1');
        $cate = M('category')->where(array('pid'=>$cat_id))->select();
        $this->ajaxReturn($cate);
    }
	public function addStep1(){
        $this->display();
    }

    public function addStep2(){
        $this->display();
    }

}