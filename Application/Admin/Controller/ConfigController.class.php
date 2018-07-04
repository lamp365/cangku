<?php

namespace Admin\Controller;


class ConfigController extends AdminController
{
    protected function _initialize(){
        parent::_initialize();
    }
    public function set(){
        $conf = M('siteconfig');
        $res = $conf->select();
        if(IS_POST){
            $data = i('post.');
            $data['about'] = htmlspecialchars_decode($data['about'],ENT_NOQUOTES);
            if(empty($res)){
                unset($data['id']);
                $conf->add($data);
            }else{
                $id = $data['id'];
                unset($data['id']);

                $conf->where("id={$id}")->save($data);
            }

            $this->success('网站信息操作成功!');
        }
        $this->assign('res',$res[0]);
        $this->display();
    }
}
