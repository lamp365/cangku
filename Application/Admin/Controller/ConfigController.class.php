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
        $res[0]['about'] =  htmlspecialchars_decode(html_entity_decode($res[0]['about']));
        $this->assign('res',$res[0]);
        $this->display();
    }
}
