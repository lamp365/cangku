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
        'signin' => 'Login/dologin',
        'center' => 'Center/index',
        'edituser' => 'Center/edituser',
    )
);
