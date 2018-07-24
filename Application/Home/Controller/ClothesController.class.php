<?php
namespace Home\Controller;
use Think\Controller;


class ClothesController extends Controller {
    public function index(){

/*  CREATE TABLE `clother` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `uri` varchar(256) NOT NULL DEFAULT '',
 `url` text NOT NULL,
 `tmall_id` varchar(256) NOT NULL,
 `pingtai` varchar(80) NOT NULL,
 `c_date` int(10) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `tamll_id` (`tmall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 */

        $where = array();
        $search = i('search');
        if(!empty($search)){
            $res = explode('http',$search);
            if(count($res) == 2){
                $where['uri'] = $search;
            }else{
                $where['tmall_id'] = $search;
            }
        }
        $sets = M('siteconfig')->find();

        $count = M('Clother')->where($where)->count();
        $p     = new \Think\Page($count,30);
        $page  = $p->show();
        $newsData  = M('Clother')->where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();

        $this->assign("newsData",$newsData);
        $this->assign("page",$page);
        $this->assign("sets",$sets);
        $this->assign("search",$search);
        $this->display();
	}
	public function add(){
	    if(IS_POST){
            $data = i('post.');
            if(empty($data['tmall_id'])){
                $this->error('请输入宝贝ID');
            }
            if(empty($data['url'])){
                $this->error('请输入来源地址');
            }
            if(empty($data['pingtai'])){
                $this->error('请输入平台');
            }
            $data['c_date'] = time();
            $arr = parse_url($data['url']);
            $http = $arr['scheme'];
            $host = $arr['host'];
            $data['uri'] = $http.'://'.$host;
            M('Clother')->add($data);
            $this->success('操作成功!');
        }
        $this->display();
    }

    public function del(){
        $id = I('id');
        if(empty($id)) $this->error('id不能为空!');
        M("Clother")->where("id={$id}")->delete();
        $this->success("删除成功!");
    }

}