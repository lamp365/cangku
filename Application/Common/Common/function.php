<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 18/6/25
 * Time: 下午9:19
 */
/**
 * 方便打印输出查看数据
 * 使用方式：pp($val1,$arr,$val2)等等可以连续打印多个
 */
function pp()
{
    $arr = func_get_args();
    echo '<pre>';
    foreach($arr as $val) {
        print_r($val);
        echo '</pre>';
        echo '<pre>';
    }
    echo '</pre>';
}

function ppd()
{
    $arr = func_get_args();
    echo '<pre>';
    foreach($arr as $val) {
        print_r($val);
        echo '</pre>';
        echo '<pre>';
    }
    echo '</pre>';
    die();
}

//无限 分类数
function catTree(&$list,$pid=0,$level=0,$html='--'){
    static $tree = array();
    foreach($list as $v){
        if($v['pid'] == $pid){
            $v['sort'] = $level;
            $v['html'] = str_repeat($html,$level);
            $tree[] = $v;
            catTree($list,$v['id'],$level+1,$html);
        }
    }
    return $tree;
}

/**
 * @param string $rootPath  保存目录
 * @param int $autoSub      是否按照月 或者 日 划分文件  1或者0  默认开启
 * @param int $subType      1为 日划分  2为月划分
 * @return array   array(errno => ,message=>)   errno为200则成功 不为200 把错误信息提示出来
 */
function uploadpic($rootPath = 'picture',$autoSub = 1 ,$subType = 2){
    //图片上传设置
    if(empty($rootPath)){
        return array('errno'=>0,'message'=>'系统错误,请设置保存目录');
    }
    $path     = 'upload/'.$rootPath.'/';

    if(!mkdirs($path)){
        return array('errno'=>0,'message'=>'目录创建失败');
    }
    $config = C('PICTURE_UPLOAD');
    /*'PICTURE_UPLOAD' => array(
        'mimes'    => '', //允许上传的文件MiMe类型
        'maxSize'  => 1*1024*1024, //上传的文件大小限制 (0-不做限制)
        'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
        'autoSub'  => true, //自动子目录保存文件
        'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => './uploads/picture/', //保存根路径
        'savePath' => '', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'  => '', //文件保存后缀，空则使用原后缀
        'replace'  => false, //存在同名是否覆盖
        'hash'     => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ), //图片上传相关配置（文件上传类配置）
    */
    $config['rootPath'] = $path;
    if($autoSub == 0){
        $config['autoSub'] = false;
    }else{
        switch ($subType){
            case 1:
                //日划分
                $config['subName'] = array('date', 'Ymd');
                break;
            case 2:
                //月划分
                $config['subName'] = array('date', 'Ym');
                break;
            default:
                return array('errno'=>0,'message'=>'系统错误,参数有误');
        }

    }
    $upload = new \Think\Upload($config,'local');// 实例化上传类
    $images = $upload->upload();
    //判断是否有图
    if($images){
        $data = array();
        foreach ($images as $items){
            $data = $items;
        }
        $pic_url = '/'.$path.$data['savepath'].$data['savename'];
        return array('errno'=>200,'message'=>$pic_url);
        //返回文件地址和名给JS作回调用
    }else{
        $error = $upload->getError();
        return array('errno'=>0,'message'=>$error);
    }

}

function mkdirs($dir){
    if(!is_dir($dir))
    {
        if(!mkdirs(dirname($dir))){
            return false;
        }
        if(!mkdir($dir,0777)){
            return false;
        }
        chmod($dir, 0777);
    }
    return true;
}

//获取时间插件的时间戳
function getTimestapFromTimeJS(){
    $s_time = i('s_time');
    $e_time = i('e_time');
    if($s_time == '开始时间' || empty($s_time)){
        $s_time = '';
    }else{
        $s_time = strtotime($s_time);
    }
    if($e_time == '结束时间' || empty($e_time)){
        $e_time = '';
    }else{
        $e_time = strtotime($e_time)+3600*24;
    }
    return  array('s_time'=>$s_time,'e_time'=>$e_time);
}
//魔板时间控件时间处理
function tplhtmlTime($type,$time){
    if($type == 'start'){
        $mes = '开始时间';
        $qu  = 0;
    }else if($type == 'end'){
        $mes = '结束时间';
        $qu  = 3600*24;
    }else{
        $mes = '参数有误';
        $qu  = 0;
    }
    if(!empty($time)){
        $mes = date("Y-m-d",$time-$qu);
    }
    return  $mes;
}

function getUidFromName($userName){
    if(empty($userName)) return 0;
    $uid = M('user')->where('user_name='.$userName)->getField('id');
    return $uid;
}

function getUidFromSession(){
    $user = session('web_user');
    return $user['id'];
}
function getGidFromSession(){
    $user = session('web_user');
    return $user['gid'];
}
/**
 * 将HTML转为实体
 * @param string $str     需要处理的字符串
 * @param string $charset 编码，默认为gb2312
 * @return string
 */
function html_to_entities($str, $charset = "utf8")
{
    // 参数判断
    if(empty($str)) return "";

    // 1.将常用的预定义字符转为实体
    $new_str = htmlspecialchars($str, ENT_QUOTES, $charset);

    // 2.替换反斜杠
    $new_str = preg_replace("/\\\/", "&#092;", $new_str);

    // 3.替换斜杠
    $new_str = preg_replace("/\//", "&#47;", $new_str);

    return $new_str;
}

/**
 * 将实体转为HTML
 * @param string $str     需要处理的字符串
 * @return string
 */
function entities_to_html($str)
{
    // 参数判断
    if(empty($str)) return "";

    // 1.将实体转为预定义字符
    $new_str = htmlspecialchars_decode($str, ENT_QUOTES);

    // 2.替换反斜杠实体
    $new_str = str_replace("&#092;", "\\", $new_str);

    // 3.替换斜杠实体
    $new_str = str_replace("&#47;", "/", $new_str);

    return $new_str;
}

/**
 * @param $uid
 * @param $huo_id
 * @param $cm_id
 * @param $type 1 为获取具体数据  2 为获取 数量
 */
function getUserKucunFormHuoyuan($uid,$huo_id,$cm_id,$type){
    $where['uid']    = $uid;
    $where['huo_id'] = $huo_id;
    $where['$cm_id'] = $cm_id;
    $data = M('kucun')->where($where)->find();
    if($type == 1){
        return $data;
    }else{
        return intval($data['num']);
    }
}

function getDiaohuoState($state){
    switch ($state){
        case 1: return '库存';break;
        case 2: return '调货';break;
        case 3: return '缺货';break;
        case 4: return '调货完毕';break;
    }
}