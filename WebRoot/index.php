<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',False);

require '../vendor/autoload.php';
// 定义应用目录
define('APP_PATH','../Application/');

define('RUNTIME_PATH','../Runtime/');

//define('BIND_MODULE','Admin');
//define('BUILD_CONTROLLER_LIST','Admin,Account,File');
// 定义根目录
define ( 'ROOT_PATH', dirname(__FILE__));

// 引入ThinkPHP入口文件
require '../ThinkPHP/ThinkPHP.php';

//手动生成MODEL OR CONTROLLER
// \Think\Build::buildController('Admin','Admin');
// \Think\Build::buildModel('Admin','User');
// 亲^_^ 后面不需要任何代码了 就是如此简单
