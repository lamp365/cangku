<?php
namespace Home\Controller;


class AfterController extends CommonController {
    public function _initialize(){
        parent::_initialize(); // TODO: Change the autogenerated stub
    }


    public function index(){
        $after_state = intval(i('after_state'));
        empty($after_state) && $after_state = 1;
        $where = array();
        $gid   = getGidFromSession();
        $where['gid'] = $gid;
        $where['after_state'] = $after_state;


        $baohuoM = M('baohuo');
        $count = $baohuoM->where($where)->count();
        $p     = new \Think\Page($count,25);
        $page  = $p->show();
        $data  = $baohuoM->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();

        $user = M('user');
        $cmM= M('cm_size');
        foreach ($data as &$item){
            $item['user_name'] = $user->where("id={$item['uid']}")->getField('user_name');
            $item['cm_name'] = $cmM->where("id={$item['cm_id']}")->getField('cm_name');
        }
        $this->assign('after_state',$after_state);
        $this->assign('page',$page);
        $this->assign('data',$data);
        $this->display();
	}

}