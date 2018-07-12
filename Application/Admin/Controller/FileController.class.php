<?php
/**
 * Created by PhpStorm.
 * User: fely
 * Date: 1/19/15
 * Time: 1:57 PM
 */

namespace Admin\Controller;

use Think\Upload;
use Think\Controller;

class FileController extends Controller{

    /**
     * 上传图片
     */
    public function uploadPicture(){
        $dir  = $_GET['dir'];
        $info = uploadpic($dir);
        $this->ajaxReturn($info);
    }
}