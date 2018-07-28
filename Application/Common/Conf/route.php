<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 18/7/9
 * Time: 下午11:00
 */
return array(
    'URL_ROUTER_ON'       => true,

    'URL_ROUTE_RULES'=>array(
//        'news/:year/:month/:day' => array('News/archive', 'status=1'),
//        'news/:id'               => 'News/read',
//        '/^new\/(\d{4})\/(\d{2})$/' => 'News/achive?year=:1&month=:2',
        'login'  => 'Login/index',
        '/^admin$/'  => '/Admin/Index/index',
        'signin' => 'Login/dologin',
        'center' => 'Center/index',
        'logout' => 'Login/loginOut',
        //个人中心其他地址可以不做  映射   只是网站的外表可以简单包装,个人中心就算了,没事,麻烦
        'edituser' => 'Center/edituser',
        'news'=>'News/index',
        'detail/:id'=>'News/detail'
    )
);
