<?php

/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 18/7/28
 * Time: 下午4:28
 */
namespace Home\Service;
use Think\Model;
use QL\QueryList;

class CaijiService extends Model{
    protected $autoCheckFields = False;

    public function __construct()
    {
        parent::__construct();
    }

    public function getJindong($data)
    {
        $run_url    = $data['cat_url'];
        $caiji_rule = $data['caiji_rule'];
        $result =  QueryList::Query($run_url,$caiji_rule,'')->getData(function($item) use($run_url){
            foreach($item as $key => &$content){
                if($key == 'cat2_html'){
                    $cate2_info = QueryList::Query($content,array(
                        'cat2_name'  => array('a','text'),
                        'href2_link' => array('a','href')
                    ))->data;

                    foreach($cate2_info as $key2 => &$row2){
                        $row2['href2_link'] = "https:".$row2['href2_link'];
                    }
                    unset($item[$key]);
                    $item['son_cat'] = $cate2_info;
                }else{

                    $key_arr = explode('_',$key);
                    $link = array_pop($key_arr);
                    if($link == 'link'){
                        //对抓取的内容 带有链接的补全路径
                        /* if(!strstr($content,'http://') && !strstr($content,"https://")){
                             $url_arr = parse_url($run_url);
                             $url     = $url_arr['scheme'].'://'.$url_arr['host'];
                             $content = rtrim($url,'/').'/'.ltrim($content,'/');
                         }*/
                        $content = 'https:'.$content;
                    }else if($link == 'contlink'){
                        $content = 'https:'.$content;
                    }////link end if
                }///cat2_html   end else

            }// foreach end
            return $item;
        });
        return $result;
    }

}